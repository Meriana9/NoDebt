<?php

namespace App\Models;

use App\DB\Query;

require_once('db/Query.php');

class Tag{
    public static function get($gid){
        $q = "SELECT * FROM tags WHERE group_id=?";
        $result = Query::get($q, [$gid]);

        return $result;
    }
}