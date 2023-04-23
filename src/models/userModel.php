<?php
require_once "src/models/connection.php";

class userModel
{
    public static function getUsers()
    {
        try {
            $result = connection::query("SELECT * FROM users");

            return ['status' => 200, 'result' => $result];
        } catch (Exception $error) {
            return $error;
        }
    }
}
