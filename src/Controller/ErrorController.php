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

namespace App\Controller;

use App\Config\Config;
use App\Ui\Ui;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ErrorController
{
    public function error(Request $request, Ui $ui) :Response
    {
        /** @var HttpException $exception */
        $exception = $request->get('exception');

        // Don't show error page when in debug mode
        if (Config::getInstance()->isDebug()) {
            throw $exception;
        }

        $ui->setTemplate('error.twig');
        $ui->addArg('status', $exception->getStatusCode());
        $ui->addArg('error', $exception->getMessage());

        // TODO create page
        return new Response($ui->render(), $exception->getStatusCode());
    }
}