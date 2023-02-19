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

use App\Config\Config;
use Symfony\Component\HttpFoundation\File\File;

trait FileTrait
{
    private ?File $_file = null;

    public function getMimeType() :string
    {
        return $this->getFile()->getMimeType();
    }

    public function getFile() :File
    {
        if ($this->_file === null) {
            $this->_file = new File($this->getPath());
        }

        return $this->_file;
    }

    public function getPath() :string
    {
        return Config::getUploadDirectory().'/'.$this->getId();
    }
}