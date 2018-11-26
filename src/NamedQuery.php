<?php

namespace Jeidison\NamedQuery;

use \Illuminate\Support\Facades\Facade;

/**
 * @method static executeNamedQuery($module, $name, $params = array(), $resultClass = null, $isBind = true, $debug = false)
 */
class NamedQuery extends Facade
{

    protected static function getFacadeAccessor() {
        return 'named-query';
    }

}