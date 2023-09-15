<?php

namespace App\Models;

use App\DB\Query;

require_once('db/Query.php');

class Invitation{
    public static function create($group_id, $email){
        $q = "INSERT INTO invitations(group_id, email, status) VALUES(?, ?, 0)";
        Query::execute($q, [$group_id, $email]);
    }

    public static function getUserInvitations($email){
        $q = "SELECT g.*, i.id AS i_id FROM groups g INNER JOIN invitations i ON i.group_id = g.id WHERE i.email = ?";
        $result = Query::get($q, [$email]);

        return $result;
    }

    public static function reject($i){
        $q = "DELETE FROM invitations WHERE id=?";
        Query::execute($q, [$i]);
    }

    public static function accept($i){
        $q = "SELECT * FROM invitations WHERE id=?";
        $invitation = Query::get($q, [$i])[0];

        $q3 = "SELECT * FROM users WHERE email = ?";
        $user = Query::get($q3, [$invitation['email']])[0];

        $q1 = "DELETE FROM invitations WHERE id=?";
        Query::execute($q1, [$i]);

        $q2 = "INSERT INTO user_group(user_id, group_id) VALUES(?, ?)";
        Query::execute($q2, [$user['id'], $invitation['group_id']]);
    }
}