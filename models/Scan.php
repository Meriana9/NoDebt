<?php

namespace App\Models;

use App\DB\Query;

require_once('db/Query.php');

class Scan{
    public static function add($scan, $eid){
        $q = "INSERT INTO scans(scan, expense_id) VALUES (?, ?)";
        Query::execute($q, [$scan, $eid]);
    }
}