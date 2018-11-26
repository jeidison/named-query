<?php

namespace Jeidison\NamedQuery;

use DOMDocument;
use Illuminate\Support\Facades\DB;

class NamedQueryService
{

    public function executeNamedQuery($module, $name, $params = array(), $resultClass = null, $debug = false)
    {
        $query = $this->normalize($module, $name, $params);
        if ($debug) {
            dd($query);
        }

        return $this->executeQuery($query, $resultClass);
    }

    private function executeQuery($query, $resultClass = null)
    {
        $results = DB::select(DB::raw($query));
        if ($resultClass == null) {
            return $results;
        }
        return $this->toObject($results, $resultClass);
    }

    private function toObject($results, $resultClass)
    {
        if (count($results) == 0) {
            return null;
        }

        if (count($results) == 1) {
            $results = $results[0];
            $instance = new $resultClass();
            $fill = ['*'];
            $values = ($fill) ? (array) $results : array_intersect_key( (array) $results, array_flip($fill));
            $instance->setRawAttributes($values, true);
            $instance->exists = true;
            return $instance;
        }

        $listObj = collect();
        foreach ($results as $result) {
            $instance = new $resultClass();
            $values = (['*']) ? (array) $result : array_intersect_key( (array) $result, array_flip(['*']));
            $instance->setRawAttributes($values, true);
            $instance->exists = true;
            $listObj->push($instance);
        }

        return $listObj;
    }

    private function normalize($module, $name, array $params)
    {
        $settings = config('named-query');
        $query = $this->getSqlAsString($settings, $module, $name);
        if ($settings['type-bind'] == TypeBind::TWO_POINTS) {
            return $this->bind($query, $params);
        }
        return $this->buildSql($query, $params);
    }

    private function getSqlAsString($settings, $module, $name)
    {
        $path = $settings['path-sql'] . "/" . $module;
        if ($settings['type'] == TypeFile::XML) {
            return $this->getSqlFromXml($path, $name);
        }
        return $this->getSqlFromPhp($path, $name);
    }

    private function getSqlFromPhp($path, $name)
    {
        $path = $path . '.php';
        include_once($path);
        return constant($name);
    }

    private function getSqlFromXml($path, $name)
    {
        $xmlDoc = new DOMDocument();
        $path = $path . '.xml';
        $xmlDoc->load($path);
        $searchNode = $xmlDoc->getElementsByTagName("query");
        foreach ($searchNode as $node) {
            $queryName = $node->getAttribute('name');
            if ($queryName != $name) {
                continue;
            }
            return $node->nodeValue;
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