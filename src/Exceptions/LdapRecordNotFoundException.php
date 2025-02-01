<?php

namespace Daynnnnn\Statamic\Auth\ForwardAuth\Exceptions;

use Exception;
use Facade\IgnitionContracts\BaseSolution;
use Facade\IgnitionContracts\ProvidesSolution;
use Facade\IgnitionContracts\Solution;

class LdapRecordNotFoundException extends Exception implements ProvidesSolution
{
    public function getSolution(): Solution
    {
        return BaseSolution::create('Install LdapRecord')
            ->setSolutionDescription('`composer require directorytree/ldaprecord`');
    }
}
