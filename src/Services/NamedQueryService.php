<?php

namespace Jeidison\NamedQuery\Services;

use DOMDocument;
use Illuminate\Support\Facades\DB;
use Jeidison\NamedQuery\Enums\TypeBind;
use Jeidison\NamedQuery\Enums\TypeFile;

class NamedQueryService
{

    public function executeNamedQuery(string $name, string $module = 'named-query', array $params = null,
                                      $resultClass = null, bool $debug = false)
    {
        $query = $this->normalize($module, $name, $params);
        if ($debug) {
            return $query;
        }

        return $this->executeQuery($query, $resultClass);
    }

    private function executeQuery($query, $resultClass = null)
    {
        $results = DB::select(DB::raw($query));
        if ($resultClass == null) {
            return $results;
        }

        if ($results == null) {
            return null;
        }

        return $this->toObject($results, $resultClass);
    }

    private function toObject($results, $resultClass)
    {
        if (count($results) == 1) {
            return $this->hydrateObject($resultClass, $results[0]);
        }

        $listObj = collect();
        foreach ($results as $result) {
            $object = $this->hydrateObject($resultClass, $result);
            $listObj->push($object);
        }
        return $listObj;
    }

    private function normalize($module, $name, array $params)
    {
        $settings = config('named-query');
        $query = $this->getSqlAsString($settings, $module, $name);
        if ($settings['type-bind'] == TypeBind::TWO_POINTS) {
            return $this->bindTwoPoints($query, $params);
        }
        return $this->buildNumber($query, $params);
    }

    private function getSqlAsString($settings, $module, $name)
    {
        $path = $settings['path-sql'] . DIRECTORY_SEPARATOR . $module;
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

    private function buildNumber($node, array $params)
    {
        $index = 1;
        $newQuery = $node->nodeValue;
        foreach ($params as $key => $param) {
            $newQuery = preg_replace("/\\?{$index}/", "'" . $param . "'", $newQuery, 1);
            $index++;
        }
        return $newQuery;
    }

    private function bindTwoPoints($query, array $params)
    {
        foreach ($params as $key => $param) {
            $query = str_replace(':' . $key, "'" . $param . "'", $query);
        }
        return $query;
    }

    private function hydrateObject($resultClass, $data)
    {
        $instance = new $resultClass();
        $fill     = array('*');
        $values   = ($fill) ? (array) $data : array_intersect_key( (array) $data, array_flip($fill));
        $instance->setRawAttributes($values, true);
        $instance->exists = true;
        return $instance;
    }
}