<?php

namespace Jeidison\NamedQuery;

use Illuminate\Support\ServiceProvider;

class NamedQueryServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/named-query.php' => config_path('named-query.php'),
        ], 'NamedQuery');

        $this->publishes([
            __DIR__ . '/../database/queries/named-querys.xml' => database_path('named-query/queries/nfe/named-querys.xml'),
        ], 'NamedQuery');

        $this->publishes([
            __DIR__ . '/../database/queries/named-query.php' => database_path('named-query/queries/nfe/named-querys.php'),
        ], 'NamedQuery');
    }

    public function register()
    {
        $this->app->singleton('named-query', function () {
            return new NamedQueryService();
        });
    }

    public function provides() {
        return ['NamedQuery'];
    }
}
