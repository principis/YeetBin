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

namespace App\Security;

use App\Security\Authorization\AuthorizationCheckerInterface;

class Firewall
{
    private AuthorizationCheckerInterface $securityChecker;
    private array $protectedRoutes;

    public function __construct(AuthorizationCheckerInterface $securityChecker, array $protectedRoutes)
    {
        $this->securityChecker = $securityChecker;
        $this->protectedRoutes = $protectedRoutes;
    }

    public function isAllowed(string $route) :bool
    {
        if (!$this->isProtected($route)) {
            return true;
        }

        return $this->securityChecker->isAuthorized($route);
    }

    public function isProtected(string $route) :bool
    {
        return in_array($route, $this->protectedRoutes, true);
    }
}