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

namespace App\Service;

use App\Entity\TextPaste;
use App\Exception\FormParserException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class FormParser
{
    private FileUploadHandler $fileUploadHandler;

    public function __construct(FileUploadHandler $fileUploadHandler)
    {
        $this->fileUploadHandler = $fileUploadHandler;
    }

    public function parseFileForm(Request $request) :UploadedFile
    {
        if (!$request->files->has('paste')
            || !isset($request->files->get('paste')['content'])) {
            throw new FormParserException("The paste was not submitted due to an unknown error");
        }

        $file = $request->files->get('paste')['content'];

        try {
            $this->fileUploadHandler->validate($file);
        } catch (FileException $e) {
            throw new FormParserException($e->getMessage());
        }

        return $file;
    }

    public function parseTextForm(Request $request) :TextPaste
    {
        $paste = $this->parsePasteDataFromRequest($request, ['language', 'content']);

        return new TextPaste(null, $paste['title'], $paste['language'], $paste['content']);
    }

    public function parsePasteDataFromRequest(Request $request, array $required = []) :array
    {
        if (!$request->request->has('paste')) {
            throw new FormParserException("The paste was not submitted due to an unknown error");
        }

        $paste = $request->request->all('paste');
        $paste = array_filter(array_map('trim', $paste), 'strlen');
        $paste['title'] ??= null;

        foreach ($required as $el) {
            if (!isset($paste[$el])) {
                throw new FormParserException(); // TODO improve messages
            }
        }

        return $paste;
    }
}