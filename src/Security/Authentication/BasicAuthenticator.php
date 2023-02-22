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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class BasicAuthenticator
{
    use UsernamePasswordAuthenticatorTrait;

    public function start(
        Request $request,
        AuthenticationManager $authManager,
        UrlGeneratorInterface $urlGenerator
    ) :Response {
        if ($this->authenticate($authManager, $request)) {
            return $this->success($urlGenerator);
        }

        return $this->askAuthentication();
    }

    protected function authenticate(AuthenticationManager $authManager, Request $request) :bool
    {
        if (null === $username = $request->headers->get('PHP_AUTH_USER')) {
            return false;
        }

        if (null === $password = $request->headers->get('PHP_AUTH_PW')) {
            return false;
        }

        return $authManager->authenticateUsernamePassword($username, $password);
    }

    protected function askAuthentication() :Response
    {
        $response = new Response();
        $response->headers->set('WWW-Authenticate', 'Basic realm="YeetBin"');
        $response->setStatusCode(401);

        return $response;
    }
}