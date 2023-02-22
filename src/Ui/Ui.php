<?php

/*
 * This file is part of YeetBin.
 * Copyright (C) 2023 Arthur Bols
 *
 * YeetBin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * YeetBin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with YeetBin.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace App\Ui;

use App\Config\Config;
use App\Security\Authentication\AuthenticationManager;
use App\Security\Firewall;
use App\Ui\Extension\AuthenticationExtension;
use App\Ui\Extension\FirewallExtension;
use Symfony\Bridge\Twig\Extension\AssetExtension;
use Symfony\Bridge\Twig\Extension\RoutingExtension;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Asset\PathPackage;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TemplateWrapper;

class Ui
{
    private Environment $twig;
    private TemplateWrapper $template;
    private array $args = [];

    public function __construct(Request $request, UrlGeneratorInterface $urlGenerator, AuthenticationManager $authManager, Firewall $firewall)
    {
        $twigLoader = new FilesystemLoader(dirname(__DIR__, 2).'/templates');

        $envOptions = [
            'cache' => dirname(__DIR__, 2).'/var/cache',
            'debug' => Config::getInstance()->isDebug(),
        ];

        $this->twig = new Environment($twigLoader, $envOptions);

        $defaultPackage = new PathPackage($request->getBasePath(), new EmptyVersionStrategy());
        $packages = new Packages($defaultPackage);
        $this->twig->addExtension(new AssetExtension($packages));
        $this->twig->addExtension(new RoutingExtension($urlGenerator));
        $this->twig->addExtension(new AuthenticationExtension($authManager));
        $this->twig->addExtension(new FirewallExtension($firewall));

        if (Config::getInstance()->isDebug()) {
            $this->twig->addExtension(new DebugExtension());
        }
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function setTemplate(string $template) :void
    {
        $this->template = $this->twig->load($template);
    }

    public function addArgs(array $args) :void
    {
        foreach ($args as $key => $value) {
            $this->addArg($key, $value);
        }
    }

    public function addArg(string $name, $value) :void
    {
        $this->args[$name] = $value;
    }

    public function render() :string
    {
        return $this->template->render($this->args);
    }
}