<?php

namespace Jeidison\NamedQuery;

use Illuminate\Support\ServiceProvider;

class NamedQueryServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__ . '../database/named-querys.xml' => database_path('named-query/named-query.xml'),
        ], 'NamedQuery');
    }

    public function register()
    {
        $this->app->make('Jeidison\NamedQuery\NamedQueryService');
    }

    public function provides() {
        return ['NamedQuery'];
    }
}
