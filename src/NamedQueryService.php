<?php

namespace Jeidison\NamedQuery;

use Illuminate\Support\Facades\DB;

class NamedQueryService
{

    public function buildQuery($name = "", $parans = array())
    {
        return NamedQueryApplication::normalize($name, $parans);
    }

    public function executeNamedQuery($name, $parans = array(), $resultClass = null, $debug = false)
    {
        $query = $this->buildQuery($name, $parans);
        if($debug)
            echo $query;

        return $this->executeQuery($query, $resultClass);
    }

    public function executeQuery($query, $resultClass = null)
    {
        $results = DB::select($query);
        if ($resultClass === null) {
            return $results;
        }

        if (!is_array($results)) {
            return new $resultClass((array)$results);
        }

        $listObj = collect();
        foreach ($results as $result) {
            $object = new $resultClass((array)$result);
            $listObj->push($object);
        }

        return $listObj;
    }

}