<?php

namespace Jeidison\NamedQuery;

use Illuminate\Support\ServiceProvider;

class NamedQueryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/named-querys.xml' => database_path('named-query/named-query.xml'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Jeidison\NamedQuery\NamedQueryController');
    }
}
