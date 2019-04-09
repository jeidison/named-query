<?php

namespace Jeidison\NamedQuery;

use \Illuminate\Support\Facades\Facade;

/**
 * @method static executeNamedQuery(string $name, string $module = 'named-query', array $params = null, $resultClass = null, bool $debug = false)
 */
class NamedQuery extends Facade
{

    protected static function getFacadeAccessor() {
        return 'NamedQuery';
    }

}