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
use App\Util\Languages;
use Symfony\Component\HttpFoundation\Response;

class DefaultController
{
    public function index(Ui $ui) :Response
    {
        $ui->setTemplate('new.twig');
        $ui->addArg('title', 'New Paste | YeetBin');
        $ui->addArg('languages', Languages::getForView());
        $ui->addArg('max_file_size', Config::getInstance()->getMaxUploadFileSize());

        return new Response($ui->render());
    }
}