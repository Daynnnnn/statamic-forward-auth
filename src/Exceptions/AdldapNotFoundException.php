<?php

namespace Daynnnnn\Statamic\Auth\ForwardAuth\Exceptions;

use Exception;
use Facade\IgnitionContracts\Solution;
use Facade\IgnitionContracts\BaseSolution;
use Facade\IgnitionContracts\ProvidesSolution;

class AdldapNotFoundException extends Exception implements ProvidesSolution
{
    /** @return  \Facade\IgnitionContracts\Solution */
    public function getSolution(): Solution
    {
        return BaseSolution::create('Install Adldap2')
            ->setSolutionDescription('`composer require adldap2/adldap2`');
    }
}
