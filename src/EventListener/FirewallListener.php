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

namespace App\EventListener;

use App\Security\Firewall;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class FirewallListener implements EventSubscriberInterface
{
    private Firewall $firewall;

    public function __construct(Firewall $firewall)
    {
        $this->firewall = $firewall;
    }

    public static function getSubscribedEvents() :array
    {
        return [
            KernelEvents::CONTROLLER_ARGUMENTS => 'onController',
        ];
    }

    public function onController(KernelEvent $event)
    {
        if (!$event->isMainRequest()) {
            return;
        }
        $request = $event->getRequest();

        if (!$route = $request->attributes->get('_route')) {
            return;
        }

        if (!$this->firewall->isAllowed($route)) {
            throw new AccessDeniedHttpException();
        }
    }
}