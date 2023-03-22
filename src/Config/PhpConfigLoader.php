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

namespace App\Config;


/**
 * PhpConfigLoader loads configuration from the config/config.php configuration file.
 */
class PhpConfigLoader implements ConfigLoaderInterface
{
    private array $config;

    public function __construct()
    {
        // TODO: handle error
        $config = require dirname(__DIR__, 2).'/config/config.php';

        $this->config = $config;
    }

    public static function available() :bool
    {
        return file_exists(dirname(__DIR__, 2).'/config/config.php');
    }

    public function get(string $key) :null|string|array
    {
        if (!isset($this->config[$key])) {
            return null;
        }

        return $this->config[$key];
    }
}