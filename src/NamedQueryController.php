<?php

namespace Jeidison\NamedQuery;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class NamedQueryController extends Controller
{

    public function buildQuery($name = "", $parans = array())
    {
        return NamedQueryApplication::normalize($name, $parans);
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
            $NFe = new $resultClass((array)$result);
            $listObj->push($NFe);
        }

        return $listObj;
    }

}