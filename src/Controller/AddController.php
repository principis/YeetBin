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
use App\Exception\FormParserException;
use App\Service\FileUploadHandler;
use App\Service\FormParser;
use App\Util\Languages;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AddController
{
    public function file(Request $request, UrlGeneratorInterface $urlGenerator, Database $db, FormParser $formParser, FileUploadHandler $fileHandler) :Response {
        try {
            $pasteData = $formParser->parsePasteDataFromRequest($request);
            $file = $formParser->parseFileForm($request);
        } catch (FormParserException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        $paste = FilePaste::fromUploadedFile($pasteData['title'], $file);

        $id = $db->addPaste($paste);
        try {
            $fileHandler->move($file, $id);
        } catch (FileException) {
            // Failed to move file, remove paste from DB
            $db->removePaste($id);
            throw new \RuntimeException("The file was not uploaded due to an unknown error.");
        }

        return new RedirectResponse($urlGenerator->generate('view', ['id' => $id]));
    }

    public function text(Request $request, UrlGeneratorInterface $urlGenerator, Database $db, FormParser $formParser) :Response {
        try {
            $paste = $formParser->parseTextForm($request);
        } catch (FormParserException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if (empty($paste->getContent())) {
            throw new BadRequestHttpException("Empty paste is not allowed!");
        }
        if (strlen($paste->getContent()) > 64000) { // TODO set in config
            throw new BadRequestHttpException("Paste is longer than 64000 bytes!");
        }
        $language = $paste->getLanguage();
        if ($language !== 'autodetect' && Languages::getLanguage($language) === null) {
            throw new BadRequestHttpException("Language is required!");
        }

        $id = $db->addPaste($paste);

        return new RedirectResponse($urlGenerator->generate('view', ['id' => $id]));
    }
}