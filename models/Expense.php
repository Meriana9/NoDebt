<?php

namespace App\Models;

use App\DB\Query;

require_once('db/Query.php');

class Expense{
    public static function add($expense){
        $q = "INSERT INTO expenses(spend_date, amount, description, user_id, tag, group_id) VALUES (?, ?, ?, ?, ?, ?)";
        Query::execute($q, [$expense['spend_date'], $expense['amount'], $expense['description'], $expense['user_id'], $expense['tag'], $expense['group_id']]);
    }

    public static function get($eid){
        $q = "SELECT e.* , u.name FROM expenses e INNER JOIN users u ON e.user_id = u.id WHERE e.id = ?";
        $result = Query::get($q, [$eid]);

        return $result[0];
    }

    public static function getGroupExpenses($gid, $filters){
        $q = "SELECT e.* , u.name, g.name AS group_name FROM expenses e INNER JOIN users u ON e.user_id = u.id INNER JOIN groups g ON e.group_id = g.id WHERE e.group_id = ?";

        $params = [$gid];
        $desc_exists = false;

        if ($filters['description'] != ''){
            $q = $q . "AND e.description Like '%" . $filters['description'] . "%'";
            $desc_exists = true;
        }
        
        if ($filters['from'] != 0 || $filters['to'] != 0){
            $q = $q . " AND (e.amount >= ? AND e.amount <= ?) ";
            array_push($params, $filters['from']);
            array_push($params, $filters['to']);
        }

        if ($filters['start_date'] != ''){
            $q = $q . "AND e.spend_date >= ? ";
            array_push($params, $filters['start_date']);
        }

        if ($filters['end_date'] != ''){
            $q = $q . " AND e.spend_date <= ? ";
            array_push($params, $filters['end_date']);
        }

        if (count($params) == 1 && !$desc_exists)
            $q = $q . 'AND true';

        $result = Query::get($q, $params);
        return $result;
    }

    public static function getGroupExpensesWithoutFilters($gid){
        $q = "SELECT e.* , u.name, g.name AS group_name FROM expenses e INNER JOIN users u ON e.user_id = u.id INNER JOIN groups g ON e.group_id = g.id WHERE e.group_id = ?";

        $params = [$gid];

        $result = Query::get($q, $params);
        return $result;
    }

    public static function getGroupLatestExpenses($gid){
        $q = "SELECT e.* , u.name, g.name AS group_name FROM expenses e INNER JOIN users u ON e.user_id = u.id INNER JOIN groups g ON e.group_id = g.id WHERE e.group_id = ? LIMIT 3";
        $result = Query::get($q, [$gid]);
        return $result;
    }

    public static function getUserTotalExpenses($uid, $gid){
        $q = "SELECT SUM(amount) AS total FROM expenses WHERE group_id=? AND user_id=?";
        $result = Query::get($q, [$gid, $uid]);
        return $result[0]['total'];
    }

    public static function delete($eid){
        $q = "DELETE FROM expenses WHERE id=?";
        Query::execute($q, [$eid]);
    }
}