<?php

namespace Jeidison\NamedQuery;

use \Illuminate\Support\Facades\Facade;

class NamedQueryFacade extends Facade
{

    protected static function getFacadeAccessor() {
        return 'NamedQuery';
    }

}