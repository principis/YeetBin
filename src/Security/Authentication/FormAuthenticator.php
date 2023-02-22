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

use App\Ui\Ui;
use App\Util\Random;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FormAuthenticator
{
    use UsernamePasswordAuthenticatorTrait;

    public function start(
        AuthenticationManager $authManager,
        Request $request,
        Session $session,
        UrlGeneratorInterface $urlGenerator,
        Ui $ui
    ) :Response {
        $error = '';

        if ($this->isFormValid($request, $session)) {
            if ($authManager->authenticateUsernamePassword(
                $request->request->get('username'),
                $request->request->get('password')
            )) {
                return $this->success($urlGenerator);
            }

            $error = 'Invalid username or password!';
        }

        $token = Random::generateToken();
        $session->set('csrf_token_login', $token);
        $ui->setTemplate('sign_in.twig');
        $ui->addArg('error', $error);
        $ui->addArg('csrf_token', $token);

        return new Response($ui->render());
    }

    protected function isFormValid(Request $request, SessionInterface $session)
    {
        return $request->request->has('username')
            && $request->request->has('password')
            && $request->request->has('_csrf_token')
            && $session->has('csrf_token_login')
            && $session->get('csrf_token_login') === $request->request->get('_csrf_token');
    }
}