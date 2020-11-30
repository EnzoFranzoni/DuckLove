<?php

class UserAccess {

    private
            $id,
            $name;

    public function __construct($id = null, $name = null) {
        $this->id = $id;
        $this->name = $name;
    }

    public function getUserAccess() {
        $pdo = Db::getInstance();

        $query = 'SELECT `id`, `name` FROM `user_access` WHERE `id` = :id ORDER BY `id` ASC';

        $exe = $pdo->prepare($query);

        $exe->bindValue('id', $this->id, PDO::PARAM_INT);

        if (!$exe->execute()) {
            throw new Exception(USER_ACCESS_GET_USER_ACCESS_EXECUTE_ERROR);
        }

        if ($exe->rowCount() == 0) {
            return null;
        }

        $res = $exe->fetch(PDO::FETCH_OBJ);

        $this->id = (int) $res->id;
        $this->name = $res->name;

        return $this;
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
        return $this;
    }

    public function __destruct() {
        
    }

}
