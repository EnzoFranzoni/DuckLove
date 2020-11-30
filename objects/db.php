<?php

class Db {

    protected static $db = null;

    public static function getInstance() {
        if (!isset(self::$db)) {
            self::$db = new PDO('mysql:host=' . db_host . ';dbname=' . db_name, db_user, db_pass, [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8']);
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$db;
    }

    protected function __clone() {
        
    }

}
