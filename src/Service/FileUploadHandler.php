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

use App\Config\Config;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadHandler
{

    public function validate(UploadedFile $file) :void
    {
        if (!$file->isValid()) {
            throw new FileException($file->getErrorMessage());
        }

        $maxSize = Config::getInstance()->getMaxUploadFileSize();
        if ($file->getSize() > $maxSize) {
            throw new FileException(
                sprintf(
                    'The file "%s" exceeds maximum allowed file size (limit is %d KiB).',
                    $file->getClientOriginalName(),
                    $maxSize / 1024
                )
            );
        }
    }

    /**
     * @param UploadedFile $file
     * @param string $uid
     * @return void
     * @throws FileException if, for any reason, the file could not have been moved
     */
    public function move(UploadedFile $file, string $uid) :void
    {
        $file->move(Config::getUploadDirectory(), $uid);
    }
}