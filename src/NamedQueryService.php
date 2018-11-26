<?php

namespace Jeidison\NamedQuery;

use DOMDocument;
use Illuminate\Support\Facades\DB;

class NamedQueryService
{

    public function executeNamedQuery($module, $name, $params = array(), $resultClass = null, $isBind = true, $debug = false)
    {
        $query = $this->normalize($module, $name, $params, $isBind);
        if ($debug) {
            echo $query;
            die;
        }

        return $this->executeQuery($query, $resultClass);
    }

    private function executeQuery($query, $resultClass = null)
    {
        $results = DB::select(DB::raw($query));
        if ($resultClass == null) {
            return $results;
        }

        if (count($results) == 1) {
            return new $resultClass((array)$results);
        } elseif (count($results) == 0) {
            return null;
        }

        $listObj = collect();
        foreach ($results as $result) {
            $object = new $resultClass((array)$result);
            $listObj->push($object);
        }

        return $listObj;
    }

    private function normalize($module, $name, array $params, $isBind)
    {
        $xmlDoc = new DOMDocument();
        $settings = config_path('named-query.php');
        $xmlDoc->load($settings['path-sql'] . "/" . $module . "/" . $name . '.xml');
        $searchNode = $xmlDoc->getElementsByTagName("query");
        foreach ($searchNode as $node) {
            $queryName = $node->getAttribute('name');
            if ($queryName != $name) {
                continue;
            }
            if ($isBind) {
                return $this->bind($node->nodeValue, $params);
            }
            return $this->buildSql($node, $params);
        }
    }

    private function buildSql($node, array $params)
    {
        $index = 1;
        $newQuery = $node->nodeValue;
        foreach ($params as $key => $param) {
            $newQuery = preg_replace("/\\?{$index}/", "'" . $param . "'", $newQuery, 1);
            $index++;
        }
        return $newQuery;
    }

    private function bind($query, array $params)
    {
        foreach ($params as $key => $param) {
            $query = str_replace(':' . $key, "'" . $param . "'", $query);
        }
        return $query;
    }

}