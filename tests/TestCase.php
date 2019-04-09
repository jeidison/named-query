<?php

namespace Tests;

use Jeidison\NamedQuery\NamedQuery;
use Jeidison\NamedQuery\Providers\NamedQueryServiceProvider;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return array(NamedQueryServiceProvider::class);
    }

    protected function getPackageAliases($app)
    {
        return array(
            'NamedQuery' => NamedQuery::class,
        );
    }
}