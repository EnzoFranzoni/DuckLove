<?php

class Comment {

    private
            $id,
            $message,
            $published,
            $item_id,
            $create_user_id,
            $create_date,
            $creator;

    public function __construct($id = null, $message = null, $published = null, $item_id = null, $create_user_id = null, $create_date = null) {
        $this->id = $id;
        $this->message = $message;
        $this->published = $published;
        $this->item_id = $item_id;
        $this->create_user_id = $create_user_id;
        $this->create_date = $create_date;
    }

    public function insertComment() {
        $this->checkField('insert');

        $pdo = Db::getInstance();

        $query = "INSERT INTO `comment` (`message`, `published`, `item_id`, `create_user_id`, `create_date`) VALUES (:message, :published, :item_id, :create_user_id, :create_date)";

        $exe = $pdo->prepare($query);

        $exe->bindValue('message', $this->message, PDO::PARAM_STR);
        $exe->bindValue('published', $this->published, PDO::PARAM_BOOL);
        $exe->bindValue('item_id', $this->item_id, PDO::PARAM_INT);
        $exe->bindValue('create_user_id', $this->create_user_id, PDO::PARAM_INT);
        $exe->bindValue('create_date', $this->create_date, PDO::PARAM_STR);

        if (!$exe->execute()) {
            throw new Exception(COMMENT_INSERT_COMMENT_EXECUTE_ERROR);
        }

        $this->id = $pdo->lastInsertId();

        return true;
    }

    public function deleteComment() {
        $pdo = Db::getInstance();

        $query = "DELETE FROM `comment` WHERE `id` = :id";

        $exe = $pdo->prepare($query);

        $exe->bindValue('id', $this->id, PDO::PARAM_INT);

        if (!$exe->execute()) {
            throw new Exception(COMMENT_DELETE_COMMENT_EXECUTE_ERROR);
        }

        return true;
    }

    public function getCommentList() {
        $pdo = Db::getInstance();

        $query = "SELECT `id`, `message`, `published`, `item_id`, `create_user_id`, `create_date` FROM `comment` WHERE (`item_id` = :item_id OR :item_id IS NULL) ORDER BY `create_date` DESC";

        $exe = $pdo->prepare($query);

        $exe->bindValue('item_id', $this->item_id, PDO::PARAM_INT);

        if (!$exe->execute()) {
            throw new Exception(COMMENT_GET_COMMENT_LIST_EXECUTE_ERROR);
        }

        $commentList = [];

        while ($res = $exe->fetch(PDO::FETCH_OBJ)) {
            $this->id = (int) $res->id;
            $this->message = $res->message;
            $this->published = (int) $res->published;
            $this->item_id = (int) $res->item_id;
            $this->create_user_id = (int) $res->create_user_id;
            $this->create_date = $res->create_date;

            $creator = new User;
            $creator->id = $res->create_user_id;
            $this->creator = $creator->getUser();

            $commentList[] = clone $this;
        }

        return $commentList;
    }

    public function getComment() {
        $pdo = Db::getInstance();

        $query = 'SELECT `id`, `message`, `published`,  `create_user_id`, `create_date` FROM `comment` WHERE (`id` = :id OR :id IS NULL) AND (`message` = :message OR :message IS NULL) ORDER BY `id` ASC';

        $exe = $pdo->prepare($query);

        $exe->bindValue('id', $this->id, PDO::PARAM_INT);
        $exe->bindValue('message', $this->message, PDO::PARAM_STR);

        if (!$exe->execute()) {
            throw new Exception(COMMENT_GET_COMMENT_EXECUTE_ERROR);
        }

        if ($exe->rowCount() == 0) {
            return null;
        }

        $res = $exe->fetch(PDO::FETCH_OBJ);

        $this->id = (int) $res->id;
        $this->message = $res->message;
        $this->published = $res->published;
        $this->item_id = (int) $res->item_id;
        $this->create_user_id = (int) $res->create_user_id;
        $this->create_date = $res->create_date;

        return $this;
    }

    private function checkField($type) {
        switch ($type) {
            case 'insert':
                if (Functions::checkStringOrNull($this->message)) {
                    throw new CheckException(COMMENT_CHECK_FIELD_MESSAGE_ERROR);
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
