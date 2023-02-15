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

abstract class Paste
{
    private readonly ?int $id;
    private readonly ?string $title;

    public function __construct(?int $id, ?string $title)
    {
        $this->id = $id;
        $this->title = $title;
    }

    /**
     * @return int|null
     */
    public function getId() :?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getTitle() :?string
    {
        return $this->title ?? $this->id;
    }
}