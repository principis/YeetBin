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

class TextPaste extends Paste
{
    private readonly string $language;

    private readonly string $content;

    public function __construct(?string $id, ?string $title, string $language, string $content)
    {
        parent::__construct($id, $title);

        $this->language = $language;
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent() :string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getLanguage() :string
    {
        return $this->language;
    }
}