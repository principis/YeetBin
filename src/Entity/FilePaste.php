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

namespace App\Entity;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FilePaste extends Paste
{
    use FileTrait;

    private readonly string $originalName;
    private readonly string $originalExtension;

    private ?File $_file = null;

    public function __construct(?string $id, ?string $title, string $originalName, ?string $originalExtension)
    {
        parent::__construct($id, $title);

        $this->originalName = $originalName;
        $this->originalExtension = $originalExtension;
    }

    public static function fromUploadedFile(?string $title, UploadedFile $file) :static
    {
        return new static(null, $title, $file->getClientOriginalName(), $file->getClientOriginalExtension());
    }

    public function getContent() :?string
    {
        $file = $this->getFile();
        $mimeType = $file->getMimeType();

        if (str_starts_with($mimeType, 'text/')) {
            return $file->getContent();
        }

        return null;
    }

    public function getOriginalExtension() :string
    {
        return $this->originalExtension;
    }

    public function getOriginalName() :string
    {
        return $this->originalName;
    }

    function getFormattedTitle() :string
    {
        return $this->getName();
    }

    public function getName() :string
    {
        return $this->getTitle() === null
            ? $this->originalName
            : $this->getTitle().(empty($this->originalExtension) ?: ".{$this->originalExtension}");
    }
}