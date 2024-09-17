<?php

namespace Daynnnnn\Statamic\Auth\ForwardAuth\Exceptions;

use Exception;
use Facade\IgnitionContracts\Solution;
use Facade\IgnitionContracts\BaseSolution;
use Facade\IgnitionContracts\ProvidesSolution;

class LdapRecordNotFoundException extends Exception implements ProvidesSolution
{
    /** @return  \Facade\IgnitionContracts\Solution */
    public function getSolution(): Solution
    {
        return BaseSolution::create('Install LdapRecord')
            ->setSolutionDescription('`composer require directorytree/ldaprecord`');
    }
}
