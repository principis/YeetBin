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

namespace App\ArgumentResolver;

use App\Config\Config;
use App\Database\Database;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class DbResolver implements ValueResolverInterface
{
    private array $config;

    public function __construct()
    {
        $cfg = Config::getInstance();
        $this->config = $cfg->get('database');
    }

    public function resolve(Request $request, ArgumentMetadata $argument) :iterable
    {
        $type = $argument->getType();
        if (Database::class !== $type && !is_subclass_of($type, Database::class)) {
            return [];
        }

        return [
            new Database(
                $this->config['dsn'],
                $this->config['username'],
                $this->config['password'],
                $this->config['options'],
            ),
        ];
    }
}