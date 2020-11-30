<?php

class Select {

    public static function getUserAccessList() {
        $pdo = Db::getInstance();

        $query = "SELECT `id` AS value, `name` AS text FROM `user_access` ORDER BY `id` ASC";

        $exe = $pdo->prepare($query);

        if (!$exe->execute()) {
            throw new Exception(SELECT_GET_USER_ACCESS_LIST_EXECUTE_ERROR);
        }

        $userAccessList = $exe->fetchAll(PDO::FETCH_OBJ);

        return $userAccessList;
    }

    public static function getStateList() {
        $stateList = [1 => SELECT_OPTION_PUBLISHED, 0 => SELECT_OPTION_UNPUBLISHED];

        return $stateList;
    }

}
