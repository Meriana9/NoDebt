<?php

namespace App\Models;

use App\DB\Query;

require_once('db/Query.php');

class User{
    public function register($user){
        $q = "INSERT INTO users(name, firstname, email, password) VALUES(?, ?, ?, ?)";
        $user["password"] = password_hash($user['password'], PASSWORD_DEFAULT);
        Query::execute($q, [$user["name"], $user["firstname"], $user["email"], $user["password"]]);
    }

    public function login($email, $password){
        $q = "SELECT * FROM users WHERE email=? AND active=1";
        $result = Query::get($q, [$email]);

        foreach ($result as $u){
            if (password_verify($password, $u['password']))
                return $u;
        }

        return false;
    }

    public function emailAlreadyExists($email){
        $q = "SELECT id FROM users WHERE email=? AND active=1";
        $result = Query::get($q, [$email]);

        return count($result) > 0 ? true : false;
    }

    public function resetPassword($email, $new_password){
        $q = "UPDATE users SET password=? WHERE email=?";
        Query::execute($q, [$new_password, $email]);
    }

    public function invite($email){
        
    }

    public function inActiveAccount($email){
        $q = "UPDATE users SET active=0 WHERE email=?";
        Query::execute($q, [$email]);
    }

    public function activateAccount($email){
        $q = "UPDATE users SET active=1 WHERE email=?";
        Query::execute($q, [$email]);
    }

    public function getUser($email){
        $q = "SELECT * FROM users WHERE email=?";
        $result = Query::get($q, [$email]);

        return $result[0];
    }

    public function updateProfileData($name, $firstname, $old_email, $new_email){
        $q = "UPDATE users SET name=? ,firstname=?, email=? WHERE email=?";
        Query::execute($q, [$name, $firstname, $new_email, $old_email]);
    }

    public function updatePassword($email ,$password){
        $q = "UPDATE users SET password=? WHERE email=?";
        Query::execute($q, [password_hash($password, PASSWORD_DEFAULT), $email]);
    }

    public function isActive($email){
        $q = "SELECT active FROM users WHERE email=?";
        $result = Query::get($q, [$email]);

        return $result[0]["active"] == 1 ? true : false;
    }

    public static function getID($email){
        $q = "SELECT id FROM users WHERE email=?";
        $result = Query::get($q, [$email]);

        return $result[0]["id"];
    }

    public function deleteAccount($email){
        $q = "DELETE FROM users WHERE email=?";
        Query::execute($q, [$email]);
    }
}