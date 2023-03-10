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

namespace App\Util;

class Random
{
    public static function pasteId(int $length) :string
    {
        $result = '';

        while (strlen($result) < $length) {
            $bytes = random_bytes($length * 2); // Generate enough bytes
            $result = substr(str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($bytes)), 0, $length);
        }

        return $result;
    }

    public static function generateToken() :string
    {
        $bytes = random_bytes(32);

        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($bytes));
    }
}