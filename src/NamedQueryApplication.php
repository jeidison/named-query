<?php

namespace Jeidison\NamedQuery;

use DOMDocument;

class NamedQueryApplication
{

    public static function normalize($name, array $parans)
    {
        $newQuery = "";
        $xmlDoc = new DOMDocument();
        $xmlDoc->load(database_path('named-query/named-query.xml'));
        $searchNode = $xmlDoc->getElementsByTagName("query");
        foreach ($searchNode as $node) {
            $queryName = $node->getAttribute('name');
            if ($queryName != $name)
                continue;

            $newQuery = self::buildSql($node, $parans);
        }

        return $newQuery;
    }

    private static function buildSql($node, array $parans)
    {
        $index = 1;
        $newQuery = $node->nodeValue;
        foreach ($parans as $param) {
            $newQuery = str_replace('?' . $index, "'".$param."'", $newQuery);
            $index++;
        }

        return $newQuery = $newQuery.";";
    }

}