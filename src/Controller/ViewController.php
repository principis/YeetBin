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
use App\Entity\FilePaste;
use App\Entity\ImagePaste;
use App\Entity\Paste;
use App\Entity\TextPaste;
use App\Ui\Ui;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

class ViewController
{
    public function view(Ui $ui, Database $db, string $id) :Response
    {
        $paste = self::getPaste($db, $id);

        return match (get_class($paste)) {
            TextPaste::class => $this->viewText($ui, $paste),
            FilePaste::class => $this->viewFile($ui, $paste),
            ImagePaste::class => $this->viewImage($ui, $paste),
        };
    }

    private static function getPaste(Database $db, string $id) :Paste
    {
        $paste = $db->getPaste($id);
        if ($paste === false) {
            throw new BadRequestHttpException("Paste not found");
        }

        return $paste;
    }

    private function viewText(Ui $ui, TextPaste $paste) :Response
    {
        $ui->setTemplate('view_text.twig');
        $ui->addArg('paste', $paste);

        $content = $paste->getContent();
        $ui->addArg('paste_bytes', strlen($content));
        $ui->addArg('paste_lines', substr_count($content, "\n") + (empty($content) ? 0 : 1));

        return new Response($ui->render());
    }

    private function viewFile(Ui $ui, FilePaste $paste) :Response
    {
        $ui->setTemplate('view_file.twig');
        $ui->addArg('paste', $paste);

        $ui->addArg('paste_bytes', $paste->getFile()->getSize());
        $content = $paste->getContent();

        if ($content !== null) {
            $ui->addArg('content', $content);
            $ui->addArg('paste_lines', substr_count($content, "\n") + (empty($content) ? 0 : 1));
        }

        return new Response($ui->render());
    }

    private function viewImage(Ui $ui, ImagePaste $paste) :Response
    {
        $ui->setTemplate('view_image.twig');
        $ui->addArg('paste', $paste);

        $ui->addArg('paste_bytes', $paste->getFile()->getSize());

        return new Response($ui->render());
    }

    public function raw(Database $db, string $id) :Response
    {
        $paste = self::getPaste($db, $id);

        if ($paste instanceof ImagePaste) {
            return $this->downloadFile($paste, ResponseHeaderBag::DISPOSITION_INLINE);
        }

        $content = $paste->getContent();
        if ($content === null) {
            throw new NotAcceptableHttpException("This paste does not support this operation.");
        }

        return new Response($content, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public function download(Database $db, string $id) :Response
    {
        $paste = self::getPaste($db, $id);

        return match (get_class($paste)) {
            TextPaste::class => $this->downloadText($paste),
            FilePaste::class, ImagePaste::class => $this->downloadFile($paste),
        };
    }

    public function downloadText(TextPaste $paste) :Response
    {
        $filename = $paste->getFormattedTitle();
        $filecontent = $paste->getContent() ?? '';

        $response = new Response($filecontent);
        $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename);
        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Content-Type', 'text/plain; charset=UTF-8');

        return $response;
    }

    public function downloadFile(FilePaste $paste, string $disposition = ResponseHeaderBag::DISPOSITION_ATTACHMENT) :BinaryFileResponse
    {
        $response = new BinaryFileResponse($paste->getFile());
        $response->setContentDisposition(
            $disposition,
            $paste->getFormattedTitle()
        );

        return $response;
    }
}