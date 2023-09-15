<?php

namespace App\Models;

use App\DB\Query;

require_once('db/Query.php');

class Group{
    public static function create($name, $currency, $user_id){
        $q = "INSERT INTO groups(name, currency, user_id) VALUES (?,?,?)";
        Query::execute($q, [$name, $currency, $user_id]);

        $q2 = "SELECT MAX(id) AS last_group FROM groups";
        $gid = Query::get($q2, [])[0]['last_group'];

        $q3 = "INSERT INTO user_group(user_id, group_id) VALUES(?,?)";
        Query::execute($q3, [$user_id, $gid]);
    }

    public static function edit($name, $currency, $group_id){
        $q = "UPDATE groups SET name=?, currency=? WHERE id=?";
        Query::execute($q, [$name, $currency, $group_id]);
    }

    public static function get($id){
        $q = "SELECT * FROM groups WHERE id=?";
        $result = Query::get($q, [$id]);

        return $result[0];
    }

    public static function getUsers($group_id){
        $q = "SELECT u.name, u.id FROM users u INNER JOIN user_group ug ON u.id = ug.user_id WHERE ug.group_id=?";
        $result = Query::get($q, [$group_id]);

        return $result;
    }

    public static function updateDivide($group_id){
        $q = "UPDATE groups SET divide=(SELECT SUM(amount) FROM expenses WHERE group_id=?) / (SELECT COUNT(id) FROM user_group WHERE group_id=?) WHERE id=?";
        Query::execute($q, [$group_id, $group_id, $group_id]);
    }

    public static function getUserGroups($user_id){
        $q = "SELECT g.* FROM groups g INNER JOIN user_group ug ON ug.group_id = g.id WHERE ug.user_id=? UNION SELECT * FROM groups WHERE user_id=?";
        $result = Query::get($q, [$user_id, $user_id]);

        return $result;
    }

    public static function getUserGroupsOwner($user_id){
        $q = "SELECT g.* FROM groups g WHERE g.user_id=?";
        $result = Query::get($q, [$user_id]);

        return $result;
    }

    public static function delete($group_id){
        $q = "DELETE FROM groups WHERE id=?";
        $q1 = "DELETE FROM expenses WHERE group_id=?";
        $q2 = "DELETE FROM user_group WHERE group_id=?";
        $q3 = "DELETE FROM payments WHERE group_id=?";

        Query::execute($q, [$group_id]);
        Query::execute($q1, [$group_id]);
        Query::execute($q2, [$group_id]);
        Query::execute($q3, [$group_id]);
    }
}