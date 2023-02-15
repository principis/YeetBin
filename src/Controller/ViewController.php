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

use App\Database\Database;
use App\Entity\TextPaste;
use App\Ui\Ui;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ViewController
{
    public function view(Ui $ui, Database $db, string $id) :Response
    {
        $paste = self::getPaste($db, $id);

        $ui->setTemplate('view.twig');
        $ui->addArg('paste', $paste);

        return new Response($ui->render());
    }

    private static function getPaste(Database $db, string $id) :TextPaste
    {
        $paste = $db->getPaste($id);
        if ($paste === false) {
            throw new BadRequestHttpException("Paste not found");
        }

        return $paste;
    }

    public function raw(Database $db, string $id) :Response
    {
        $paste = self::getPaste($db, $id);

        return new Response($paste->getContent(), 200, ['content-type' => 'text/plain; charset=UTF-8']);
    }

    public function download(Database $db, string $id) :Response
    {
        $paste = self::getPaste($db, $id);

        $filename = $paste->getTitle() ?? $id;
        $filecontent = $paste->getContent() ?? '';

        $response = new Response($filecontent);
        $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename);
        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('content-type','text/plain; charset=UTF-8');

        return $response;
    }
}