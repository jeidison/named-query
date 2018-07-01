<?php

namespace Jeidison\NamedQuery;

use DOMDocument;

class NamedQueryApplication
{

    public static function normalize($name, array $params)
    {
        $xmlDoc = new DOMDocument();
        $xmlDoc->load(database_path('named-query/named-query.xml'));
        $searchNode = $xmlDoc->getElementsByTagName("query");
        foreach ($searchNode as $node) {
            $queryName = $node->getAttribute('name');
            if ($queryName == $name) {
                return self::buildSql($node, $params);
            }
        }
    }

    private static function buildSql($node, array $params)
    {
        $newQuery = $node->nodeValue;
        foreach ($params as $key => $param) {
            $newQuery = str_replace(':' . $key, "'" . $param . "'", $newQuery);
        }
        return $newQuery;
    }

}