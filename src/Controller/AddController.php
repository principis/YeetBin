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
use App\Util\Languages;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AddController
{
    public function add(Request $request, UrlGeneratorInterface $urlGenerator, Database $db, string $type) :Response
    {
        if (!$request->request->has('paste')) {
            throw new BadRequestHttpException();
        }

        $paste = $request->request->all('paste');
        $paste = array_filter(array_map('trim', $paste), 'strlen');

        return match ($type) {
            'text' => $this->textPaste($paste, $urlGenerator, $db),
            default => throw new BadRequestHttpException('Unknown paste type')
        };
    }

    private function textPaste(array $formData, UrlGeneratorInterface $urlGenerator, Database $db) :Response
    {
        $title = $formData['title'] ?? null;
        $language = $formData['language'] ?? null;
        $content = $formData['content'] ?? null;
        if ($content === null) {
            throw new BadRequestHttpException("Empty paste is not allowed!");
        }
        if (strlen($content) > 64000) { // TODO set in config
            throw new BadRequestHttpException("Paste is longer than 64000 bytes!");
        }
        if ($language === null || $language !== 'autodetect' && Languages::getLanguage($language) === null) {
            throw new BadRequestHttpException("Language is required!");
        }

        $id = $db->addPaste(new TextPaste(null, $title, $language, $content));

        return new RedirectResponse($urlGenerator->generate('view', ['id' => $id]));
    }
}