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
 * EnvConfigLoader loads configuration from the environment.
 */
class EnvConfigLoader implements ConfigLoaderInterface
{
    private readonly array $env;
    private readonly array $envKeys;

    public function __construct()
    {
        $this->env = getenv();
        $this->envKeys = array_keys($this->env);
    }

    public static function available() :bool
    {
        return true;
    }

    public function get(string $key) :null|string|array
    {
        // env vars are always uppercase, but our config vars aren't
        $key = strtoupper($key);

        if (isset($this->env[$key])) {
            return $this->getValue($key);
        }

        $subKeys = [];

        $prefix = $key.'_';
        foreach ($this->envKeys as $envKey) {
            if (str_starts_with($envKey, $prefix)) {
                $subKeys[substr($envKey, strlen($prefix))] = $this->getValue($envKey);
            }
        }

        $subKeys = array_combine(array_map('strtolower', array_keys($subKeys)), array_values($subKeys));

        return empty($subKeys) ? null : $subKeys;
    }

    private function getValue(string $key) :null|string|array
    {
        $value = $this->env[$key];
        if (ctype_digit($value)) {
            return intval($value);
        }

        if (str_starts_with($value, '[') && str_ends_with($value, ']')) {
            if (null !== $decoded = json_decode($value)) {
                return $decoded;
            }
        }

        if ($value === '') {
            return null;
        }

        return $value;
    }
}