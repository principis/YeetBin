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

namespace App\Security\Authentication;

use App\Config\Config;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class AuthenticationManager
{
    const IS_AUTHENTICATED = 'is_authenticated';

    private SessionInterface $session;
    private ?string $name;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        $cfg = Config::getInstance()->get('authentication');
        $this->name = $cfg['authenticator'] ?? null;
    }

    public function configure(RouteCollection $routes) :void
    {
        $authenticator = $this->getAuthenticator();
        if ($authenticator === null) {
            return;
        }

        $routes->add('login', new Route('/login', ['_controller' => [$authenticator, 'start']]));
        $routes->add('logout', new Route('/logout', ['_controller' => [$authenticator, 'logout']]));
    }

    private function getAuthenticator() :?string
    {
        return match ($this->name) {
            'basic' => BasicAuthenticator::class,
            'form' => FormAuthenticator::class,
            default => null
        };
    }

    public function isAuthenticated() :bool
    {
        return $this->session->get(AuthenticationManager::IS_AUTHENTICATED, false);
    }

    public function isEnabled() :bool
    {
        return $this->getAuthenticator() !== null;
    }

    public function authenticateUsernamePassword(string $username, string $password) :bool
    {
        $cfg = Config::getInstance()->get('authentication');

        $result = $username === ($cfg['username'] ?? null)
            && password_verify($password, $cfg['password_hash'] ?? '');

        if ($result) {
            $this->authenticationSuccess();
        }

        return $result;
    }

    private function authenticationSuccess() :void
    {
        $this->session->set(self::IS_AUTHENTICATED, true);
    }

    public function logout() :void
    {
        $this->session->remove(self::IS_AUTHENTICATED);
    }
}