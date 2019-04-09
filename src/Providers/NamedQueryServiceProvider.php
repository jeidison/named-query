<?php

namespace Jeidison\NamedQuery\Providers;

use Illuminate\Support\ServiceProvider;
use Jeidison\NamedQuery\Services\NamedQueryService;

class NamedQueryServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes(array(
            __DIR__ . '/../../config/named-query.php' => config_path('named-query.php'),
        ), 'NamedQuery');

        $this->publishes(array(
            __DIR__ . '/../../database/queries/named-query.xml' => database_path('named-query/queries/nfe/named-querys.xml'),
        ), 'NamedQuery');

        $this->publishes(array(
            __DIR__ . '/../../database/queries/named-query.php' => database_path('named-query/queries/nfe/named-querys.php'),
        ), 'NamedQuery');
    }

    public function register()
    {
        $this->app->singleton(NamedQueryService::class, function () {
            return new NamedQueryService();
        });

        $this->app->alias(NamedQueryService::class, 'NamedQuery');
    }

    public function provides() {
        return array('NamedQuery');
    }
}
