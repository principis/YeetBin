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

namespace App\Database;

use App\Entity\FilePaste;
use App\Entity\ImagePaste;
use App\Entity\Paste;
use App\Entity\TextPaste;

enum PasteType: int
{
    case TEXT = 0;
    case FILE = 1;
    case IMAGE = 2;

    public static function fromPaste(Paste $paste) :PasteType
    {
        return match (get_class($paste)) {
            TextPaste::class => self::TEXT,
            FilePaste::class => self::FILE,
            ImagePaste::class => self::IMAGE,
        };
    }
}
