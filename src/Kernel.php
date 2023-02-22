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

namespace App;

use App\ArgumentResolver\AuthenticationManagerResolver;
use App\ArgumentResolver\DbResolver;
use App\ArgumentResolver\ServiceResolver;
use App\ArgumentResolver\UiResolver;
use App\ArgumentResolver\UrlGeneratorResolver;
use App\Config\Config;
use App\Controller\ErrorController;
use App\EventListener\AuthenticationListener;
use App\EventListener\FirewallListener;
use App\Security\Authentication\AuthenticationManager;
use App\Security\Authorization\AuthorizationChecker;
use App\Security\Firewall;
use App\Service\FileUploadHandler;
use App\Service\FormParser;
use App\Service\ImageHelper;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Controller;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\ErrorListener;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;


class Kernel
{
    private Request $request;
    private RequestStack $requestStack;
    private Session $session;
    private Routing\RouteCollection $routes;
    private RequestContext $context;
    private Routing\Matcher\UrlMatcher $matcher;
    private Firewall $firewall;
    private AuthenticationManager $authManager;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct()
    {
        $config = Config::getInstance();
        if ($config->isDebug()) {
            Debug::enable();
        }

        $this->request = Request::createFromGlobals();
        $this->requestStack = new RequestStack();
        $this->session = new Session();
        $this->request->setSession($this->session);
        $this->routes = $config::loadRoutes();

        $this->context = new Routing\RequestContext();
        $this->context->fromRequest($this->request);
        $this->matcher = new Routing\Matcher\UrlMatcher($this->routes, $this->context);

        $authChecker = new AuthorizationChecker($this->session);
        $this->firewall = new Firewall($authChecker, $config->get('firewall')['routes'] ?? []);
        $this->authManager = new AuthenticationManager($this->session);
        $this->authManager->configure($this->routes);
        $this->urlGenerator = new Routing\Generator\UrlGenerator($this->routes, $this->context);
    }

    public function run() :void
    {
        $kernel = new HttpKernel(
            $this->getEventDispatcher(),
            new ControllerResolver(),
            $this->requestStack,
            $this->getArgumentResolver()
        );

        $response = $kernel->handle($this->request);
        $response->send();
    }

    private function getEventDispatcher() :EventDispatcherInterface
    {
        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new RouterListener($this->matcher, $this->requestStack));
        $dispatcher->addSubscriber(new ErrorListener([ErrorController::class, 'error']));
        $dispatcher->addSubscriber(new AuthenticationListener($this->urlGenerator));
        $dispatcher->addSubscriber(new FirewallListener($this->firewall));

        return $dispatcher;
    }

    private function getArgumentResolver() :Controller\ArgumentResolver
    {
        $resolvers = Controller\ArgumentResolver::getDefaultArgumentValueResolvers();
        $resolvers[] = new UiResolver($this->urlGenerator, $this->authManager, $this->firewall);
        $resolvers[] = new DbResolver();
        $resolvers[] = new UrlGeneratorResolver($this->routes, $this->context);
        $resolvers[] = new AuthenticationManagerResolver($this->authManager);
        $resolvers[] = new ServiceResolver([FileUploadHandler::class, FormParser::class, ImageHelper::class]);

        return new Controller\ArgumentResolver(null, $resolvers);
    }
}