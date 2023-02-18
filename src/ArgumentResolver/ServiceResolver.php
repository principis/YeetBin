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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class ServiceResolver implements ValueResolverInterface
{

    private array $services;

    private array $instances = [];

    public function __construct(array $services)
    {
        $this->services = $services;
    }

    public function resolve(Request $request, ArgumentMetadata $argument) :iterable
    {
        $class = $argument->getType();
        if (!in_array($class, $this->services, true)) {
            return [];
        }

        if (isset($this->instances[$class])) {
            return [$this->instances[$class]];
        }


        return [$this->resolveServiceArgument($class)];
    }

    private function resolveServiceArgument($class) :null|object
    {
        if (isset($this->instances[$class])) {
            return $this->instances[$class];
        }

        $reflect = new \ReflectionClass($class);

        $constructor = $reflect->getConstructor();
        if ($constructor !== null) {
            $args = [];
            foreach ($reflect->getConstructor()->getParameters() as $parameter) {
                $args[] = $this->resolveServiceArgument($parameter->getType()->getName());
            }

            $obj = $reflect->newInstanceArgs($args);
        } else {
            $obj = $reflect->newInstanceWithoutConstructor();
        }

        $this->instances[$class] = $obj;

        return $obj;
    }
}