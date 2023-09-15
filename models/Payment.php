<?php

namespace App\Models;

use App\DB\Query;

require_once('db/Query.php');

class Payment{
    public static function create($amount, $group_id, $user_id){
        $q = "INSERT INTO payments(amount, group_id, user_id) VALUES (?,?,?)";
        Query::execute($q, [$amount, $group_id, $user_id]);
    }

    public static function getGroupPayments($group_id){
        $q = "SELECT u.name, p.id, p.amount, p.pay_date, p.is_confirmed FROM payments p INNER JOIN groups g ON p.group_id = g.id INNER JOIN users u ON p.user_id = u.id WHERE g.id = ?";
        $result = Query::get($q, [$group_id]);

        return $result;
    }

    public static function confirm($pid){
        $q = "UPDATE payments SET is_confirmed=1 WHERE id = ?";
        Query::execute($q, [$pid]);
    }

    public static function getUserTotalPayments($uid, $gid){
        $q = "SELECT SUM(amount) AS total FROM payments WHERE group_id=? AND user_id=?";
        $result = Query::get($q, [$gid, $uid]);
        return $result[0]['total'];
    }
}