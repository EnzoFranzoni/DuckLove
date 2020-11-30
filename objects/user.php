<?php

class User {

    private
            $id,
            $username,
            $password,
            $last_name,
            $first_name,
            $email,
            $user_access_id,
            $published,
            $create_date,
            $access_key;

    public function __construct($id = null, $username = null, $password = null, $last_name = null, $first_name = null, $email = null, $user_access_id = null, $published = null, $create_date = null, $access_key = null) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->last_name = $last_name;
        $this->first_name = $first_name;
        $this->email = $email;
        $this->user_access_id = $user_access_id;
        $this->published = $published;
        $this->create_date = $create_date;
        $this->access_key = $access_key;
    }

    public function insertUser() {
        $this->checkField('insert');

        $pdo = Db::getInstance();

        $query = "INSERT INTO `user` (`username`, `password`, `last_name`, `first_name`, `email`, `user_access_id`, `published`, `create_date`, `access_key`) VALUES (:username, :password, :last_name, :first_name, :email, :user_access_id, :published, :create_date, :access_key)";

        $exe = $pdo->prepare($query);

        $exe->bindValue('username', $this->username, PDO::PARAM_STR);
        $exe->bindValue('password', $this->password, PDO::PARAM_STR);
        $exe->bindValue('last_name', $this->last_name, PDO::PARAM_STR);
        $exe->bindValue('first_name', $this->first_name, PDO::PARAM_STR);
        $exe->bindValue('email', $this->email, PDO::PARAM_STR);
        $exe->bindValue('user_access_id', $this->user_access_id, PDO::PARAM_INT);
        $exe->bindValue('published', $this->published, PDO::PARAM_BOOL);
        $exe->bindValue('create_date', $this->create_date, PDO::PARAM_STR);
        $exe->bindValue('access_key', $this->access_key, PDO::PARAM_STR);

        if (!$exe->execute()) {
            throw new Exception(USER_INSERT_USER_EXECUTE_ERROR);
        }

        $this->id = $pdo->lastInsertId();

        return true;
    }

    public function updateUser() {
        $this->checkField('update');

        $pdo = Db::getInstance();

        $query = "UPDATE `user` SET `username` = :username, `password` = :password, `last_name` = :last_name, `first_name` = :first_name, `email` = :email, `user_access_id` = :user_access_id, `published` = :published, `create_date` = :create_date, `access_key` = :access_key WHERE `id` = :id";

        $exe = $pdo->prepare($query);

        $exe->bindValue('id', $this->id, PDO::PARAM_INT);
        $exe->bindValue('username', $this->username, PDO::PARAM_STR);
        $exe->bindValue('password', $this->password, PDO::PARAM_STR);
        $exe->bindValue('last_name', $this->last_name, PDO::PARAM_STR);
        $exe->bindValue('first_name', $this->first_name, PDO::PARAM_STR);
        $exe->bindValue('email', $this->email, PDO::PARAM_STR);
        $exe->bindValue('user_access_id', $this->user_access_id, PDO::PARAM_INT);
        $exe->bindValue('published', $this->published, PDO::PARAM_BOOL);
        $exe->bindValue('create_date', $this->create_date, PDO::PARAM_STR);
        $exe->bindValue('access_key', $this->access_key, PDO::PARAM_STR);

        if (!$exe->execute()) {
            throw new Exception(USER_UPDATE_USER_EXECUTE_ERROR);
        }

        return true;
    }

    public function deleteUser() {
        $pdo = Db::getInstance();

        $query = "DELETE FROM `user` WHERE `id` = :id";

        $exe = $pdo->prepare($query);

        $exe->bindValue('id', $this->id, PDO::PARAM_INT);

        if (!$exe->execute()) {
            throw new Exception(USER_DELETE_USER_EXECUTE_ERROR);
        }

        return true;
    }

    public function checkAccount() {
        $this->checkField('login');

        $pdo = Db::getInstance();

        $query = "SELECT `id`, `username`, `password`, `last_name`, `first_name`, `email`, `user_access_id`, `published`, `create_date`, `access_key` FROM `user` WHERE `username` = :username AND `password` = :password";

        $exe = $pdo->prepare($query);

        $exe->bindValue('username', $this->username, PDO::PARAM_STR);
        $exe->bindValue('password', $this->password, PDO::PARAM_STR);

        if (!$exe->execute()) {
            throw new Exception(USER_CHECK_ACCOUNT_EXECUTE_ERROR);
        }

        if ($exe->rowCount() == 0) {
            throw new CheckException(USER_CHECK_ACCOUNT_USERNAME_PASSWORD_ERROR);
        }

        $res = $exe->fetch(PDO::FETCH_OBJ);

        if ($res->published == 0) {
            throw new CheckException(USER_CHECK_ACCOUNT_PUBLISHED_ERROR);
        }

        $this->id = (int) $res->id;
        $this->username = $res->username;
        $this->password = $res->password;
        $this->last_name = $res->last_name;
        $this->first_name = $res->first_name;
        $this->email = $res->email;
        $this->user_access_id = (int) $res->user_access_id;
        $this->published = (int) $res->published;
        $this->create_date = $res->create_date;
        $this->access_key = $res->access_key;

        return $this;
    }

    public function checkReset() {
        $pdo = Db::getInstance();

        $query = "SELECT `id`, `username`, `password`, `last_name`, `first_name`, `email`, `user_access_id`, `published`, `create_date`, `access_key` FROM `user` WHERE `username` = :username AND `access_key` = :access_key";

        $exe = $pdo->prepare($query);

        $exe->bindValue('username', $this->username, PDO::PARAM_STR);
        $exe->bindValue('access_key', $this->access_key, PDO::PARAM_STR);

        if (!$exe->execute()) {
            throw new Exception(USER_CHECK_RESET_EXECUTE_ERROR);
        }

        if ($exe->rowCount() == 0) {
            throw new CheckException(USER_CHECK_RESET_USERNAME_ACCESSKEY_ERROR);
        }

        $res = $exe->fetch(PDO::FETCH_OBJ);

        $this->id = (int) $res->id;
        $this->username = $res->username;
        $this->password = $res->password;
        $this->last_name = $res->last_name;
        $this->first_name = $res->first_name;
        $this->email = $res->email;
        $this->user_access_id = (int) $res->user_access_id;
        $this->published = (int) $res->published;
        $this->create_date = $res->create_date;
        $this->access_key = $res->access_key;

        return $this;
    }

    public function getUserList() {
        $pdo = Db::getInstance();

        $query = "SELECT `id`, `username`, `password`, `last_name`, `first_name`, `email`, `user_access_id`, `published`, `create_date`, `access_key` FROM `user` ORDER BY `id` ASC";

        $exe = $pdo->prepare($query);

        if (!$exe->execute()) {
            throw new Exception(USER_GET_USER_LIST_EXECUTE_ERROR);
        }

        $userList = [];

        while ($res = $exe->fetch(PDO::FETCH_OBJ)) {
            $this->id = (int) $res->id;
            $this->username = $res->username;
            $this->password = $res->password;
            $this->last_name = $res->last_name;
            $this->first_name = $res->first_name;
            $this->email = $res->email;
            $this->user_access_id = (int) $res->user_access_id;
            $this->published = (int) $res->published;
            $this->create_date = $res->create_date;
            $this->access_key = $res->access_key;

            $userList[] = clone $this;
        }

        return $userList;
    }

    public function getUser() {
        $pdo = Db::getInstance();

        $query = 'SELECT `id`, `username`, `password`, `last_name`, `first_name`, `email`, `user_access_id`, `published`, `create_date`, `access_key` FROM `user` WHERE (`id` = :id OR :id IS NULL) AND (`username` = :username OR :username IS NULL) AND (`email` = :email OR :email IS NULL) ORDER BY `id` ASC';

        $exe = $pdo->prepare($query);

        $exe->bindValue('id', $this->id, PDO::PARAM_INT);
        $exe->bindValue('username', $this->username, PDO::PARAM_STR);
        $exe->bindValue('email', $this->email, PDO::PARAM_STR);

        if (!$exe->execute()) {
            throw new Exception(USER_GET_USER_EXECUTE_ERROR);
        }

        if ($exe->rowCount() == 0) {
            return null;
        }

        $res = $exe->fetch(PDO::FETCH_OBJ);

        $this->id = (int) $res->id;
        $this->username = $res->username;
        $this->password = $res->password;
        $this->last_name = $res->last_name;
        $this->first_name = $res->first_name;
        $this->email = $res->email;
        $this->user_access_id = (int) $res->user_access_id;
        $this->published = (int) $res->published;
        $this->create_date = $res->create_date;
        $this->access_key = $res->access_key;

        return $this;
    }

    private function checkField($type) {
        switch ($type) {
            case 'login':
                if (Functions::checkStringOrNull($this->username)) {
                    throw new CheckException(USER_CHECK_FIELD_USERNAME_ERROR);
                }
                if (Functions::checkStringOrNull($this->password)) {
                    throw new CheckException(USER_CHECK_FIELD_PASSWORD_ERROR);
                }
                break;

            case 'insert':
            case 'update':
                if (Functions::checkStringOrNull($this->username)) {
                    throw new CheckException(USER_CHECK_FIELD_USERNAME_ERROR);
                }
                if (Functions::checkStringOrNull($this->password)) {
                    throw new CheckException(USER_CHECK_FIELD_PASSWORD_ERROR);
                }
                if (Functions::checkStringOrNull($this->last_name)) {
                    throw new CheckException(USER_CHECK_FIELD_LASTNAME_ERROR);
                }
                if (Functions::checkStringOrNull($this->first_name)) {
                    throw new CheckException(USER_CHECK_FIELD_FIRSTNAME_ERROR);
                }
                if (!Functions::checkEmail($this->email)) {
                    throw new CheckException(USER_CHECK_FIELD_EMAIL_ERROR);
                }
                if (!Functions::checkInteger($this->user_access_id)) {
                    throw new CheckException(USER_CHECK_FIELD_USER_ACCESS_ERROR);
                }
                if (!Functions::checkInteger($this->published)) {
                    throw new CheckException(USER_CHECK_FIELD_PUBLISHED_ERROR);
                }
                break;

            default :
                throw new CheckException(UNMANAGED_ERROR);
        }

        return true;
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
