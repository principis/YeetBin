<?php

namespace App\ArgumentResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class ServiceResolver implements ValueResolverInterface
{

    private array $services;

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

        return [new $class];
    }
}