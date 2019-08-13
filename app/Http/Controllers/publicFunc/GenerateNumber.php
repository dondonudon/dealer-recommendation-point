<?php

namespace App\Http\Controllers\publicFunc;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class GenerateNumber extends Controller
{
    public static function generate($key,$tableName,$columnName) {
        $yM = date('ym');
        $prospect = DB::table($tableName)
            ->select($columnName)
            ->where($columnName,'like',$key.'-'.$yM.'%')
            ->orderBy($columnName,'desc');
        if ($prospect->exists()) {
            $lastID = $prospect->first();
            $keyLen = strlen($key) + 6;
            $lastNum = substr($lastID->$columnName,$keyLen);
            $newNum = ((int) $lastNum) + 1;

            $result = $key.'-'.$yM.'-'.str_pad($newNum,4,'0', STR_PAD_LEFT);
        } else {
            $result = $key.'-'.$yM.'-'.str_pad('1',4,'0', STR_PAD_LEFT);
        }

        return $result;
    }
}
