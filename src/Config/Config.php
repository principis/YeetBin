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

use Symfony\Component\Routing\Loader\Configurator\RouteConfigurator;
use Symfony\Component\Routing\RouteCollection;

class Config
{
    private static ?Config $instance = null;
    private ConfigLoaderInterface $loader;

    protected function __construct()
    {
        if (PhpConfigLoader::available()) {
            $this->loader = new PhpConfigLoader();
        } else {
            $this->loader = new EnvConfigLoader();
        }
    }

    public static function getInstance() :Config
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public static function getUploadDirectory() :string
    {
        return dirname(__DIR__, 2).'/var/uploads';
    }

    public function getMaxUploadFileSize() :int
    {
        return $this->get('max_upload_filesize') ?? 1024 * 1024;
    }

    public function get(string $key) :string|array|null
    {
        return $this->loader->get($key);
    }

    public function isDebug() :bool
    {
        return $this->get('APP_ENV') === 'DEBUG';
    }

    public static function loadRoutes() :RouteCollection
    {
        $routes = new RouteCollection();
        $configurator = new RouteConfigurator($routes, new RouteCollection());
        $cfg = include dirname(__DIR__, 2).'/config/routes.php';
        $cfg($configurator);

        return $routes;
    }

    protected function __clone()
    {
    }
}