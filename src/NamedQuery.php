<?php

namespace Jeidison\NamedQuery;

use \Illuminate\Support\Facades\Facade;

/**
 * Class NamedQuery
 * @method static executeNamedQuery($name, $parans = array(), $resultClass = null, $debug = false)
 * @method static buildQuery($name = "", $parans = array())
 * @method static executeQuery($query, $resultClass = null)
 * @package Jeidison\NamedQuery
 */
class NamedQuery extends Facade
{

    protected static function getFacadeAccessor() {
        return 'named-query';
    }

}