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

namespace App\Ui\Extension;

use App\Security\Authentication\AuthenticationManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AuthenticationExtension extends AbstractExtension
{
    private AuthenticationManager $authManager;

    public function __construct(AuthenticationManager $authManager)
    {
        $this->authManager = $authManager;
    }

    public function getFunctions() :array
    {
        return [
            new TwigFunction('has_auth', $this->authManager->isEnabled(...)),
            new TwigFunction('is_authenticated', $this->authManager->isAuthenticated(...)),
        ];
    }
}