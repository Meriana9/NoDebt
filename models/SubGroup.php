<?php

namespace App\Models;

use App\DB\Query;

require_once('db/Query.php');

class SubGroup{
    public static function create($admin_id, $group_id, $name){
        $q = "INSERT INTO sub_groups(gid, uid, name) VALUES(?, ?, ?)";
        Query::execute($q, [$group_id, $admin_id, $name]);
    }

    public static function getSubGroups($gid){
        $q = "SELECT sg.*, sg.name AS sgname, sg.id AS sgid, u.*, u.name AS uname, u.firstname AS ufname FROM sub_groups sg INNER JOIN users u ON u.id = sg.uid WHERE sg.gid=?";
        $result = Query::get($q, [$gid]);

        return $result;
    }

    public static function delete($sgid){
        $q = "DELETE FROM sub_groups WHERE id=?";
        Query::execute($q, [$sgid]);

        $q1 = "DELETE FROM user_sub_group WHERE sgid=?";
        Query::execute($q1, [$sgid]);
    }

    public static function get($sgid){
        $q = "SELECT * FROM sub_groups WHERE id=?";
        $result = Query::get($q, [$sgid]);

        return $result[0];
    }

    public static function getUsersToAdd($sgid){
        $q = "SELECT gid FROM sub_groups WHERE id = ?";
        $gid = Query::get($q, [$sgid])[0]['gid'];

        $q1 = "SELECT * FROM users WHERE id IN (SELECT user_id FROM user_group WHERE group_id = ?) AND id NOT IN (SELECT uid FROM user_sub_group WHERE sgid = ?) AND id NOT IN (SELECT uid FROM sub_groups WHERE id = ?)";
        $result = Query::get($q1, [$gid, $sgid, $sgid]);

        return $result;
    }

    public static function addUser($sgid, $uid){
        $q = "INSERT INTO user_sub_group(sgid, uid) VALUES(?, ?)";
        Query::execute($q, [$sgid, $uid]);
    }

    public static function getMembers($sgid){
        $q = "SELECT u.name, u.firstname FROM users u INNER JOIN user_sub_group usg ON usg.uid = u.id WHERE usg.sgid=?";
        $result = Query::get($q, [$sgid]);

        return $result;
    }
}