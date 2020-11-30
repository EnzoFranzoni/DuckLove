<?php

class Item {

    private
            $id,
            $title,
            $description,
            $details,
            $published,
            $create_date,
            $create_user_id,
            $filename,
            $creator,
            $categories,
            $comments;

    public function __construct($id = null, $title = null, $description = null, $details = null, $published = null, $create_date = null, $create_user_id = null, $filename = null) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->details = $details;
        $this->published = $published;
        $this->create_date = $create_date;
        $this->create_user_id = $create_user_id;
        $this->filename = $filename;
    }

    public function insertItem() {
        $this->checkField('insert');

        $checkTitle = $this->getItem($this->title);

        if (!Functions::checkNull($checkTitle)) {
            throw new Exception(ITEM_INSERT_USER_CHECK_TITLE_ERROR);
        }

        $pdo = Db::getInstance();

        $query = "INSERT INTO `item` (`title`, `description`, `published`, `create_date`, `create_user_id`, `filename`) VALUES (:title, :description, :published, :create_date, :create_user_id, :filename)";

        $exe = $pdo->prepare($query);

        $exe->bindValue('title', $this->title, PDO::PARAM_STR);
        $exe->bindValue('description', $this->description, PDO::PARAM_STR);
        $exe->bindValue('details', $this->details, PDO::PARAM_STR);
        $exe->bindValue('published', $this->published, PDO::PARAM_BOOL);
        $exe->bindValue('create_date', $this->create_date, PDO::PARAM_STR);
        $exe->bindValue('create_user_id', $this->create_user_id, PDO::PARAM_INT);
        $exe->bindValue('filename', $this->filename, PDO::PARAM_STR);

        if (!$exe->execute()) {
            throw new Exception(ITEM_INSERT_ITEM_EXECUTE_ERROR);
        }

        $this->id = $pdo->lastInsertId();

        return true;
    }

    public function updateItem() {
        $pdo = Db::getInstance();

        $query = "UPDATE `item` SET `title` = :title, `description` = :description, `details` = :details, `published` = :published, `create_date` = :create_date, `create_user_id` = :create_user_id, `filename` = :filename WHERE `id` = :id";

        $exe = $pdo->prepare($query);

        $exe->bindValue('title', $this->title, PDO::PARAM_STR);
        $exe->bindValue('description', $this->description, PDO::PARAM_STR);
        $exe->bindValue('details', $this->details, PDO::PARAM_STR);
        $exe->bindValue('published', $this->published, PDO::PARAM_BOOL);
        $exe->bindValue('create_date', $this->create_date, PDO::PARAM_STR);
        $exe->bindValue('create_user_id', $this->create_user_id, PDO::PARAM_STR);
        $exe->bindValue('filename', $this->filename, PDO::PARAM_STR);

        if (!$exe->execute()) {
            throw new Exception(ITEM_UPDATE_ITEM_EXECUTE_ERROR);
        }

        return true;
    }

    public function deleteItem() {
        $pdo = Db::getInstance();

        $query = "DELETE FROM `item` WHERE `id` = :id";

        $exe = $pdo->prepare($query);

        $exe->bindValue('id', $this->id, PDO::PARAM_INT);

        if (!$exe->execute()) {
            throw new Exception(ITEM_DELETE_ITEM_EXECUTE_ERROR);
        }

        return true;
    }

    public function getItemTotal($category_id = null) {
        $pdo = Db::getInstance();

        $query = "SELECT COUNT(*) FROM `item` AS `i` INNER JOIN (SELECT `item_id`, `category_id` FROM `category_item` WHERE (`category_id` = :category_id OR :category_id IS NULL) GROUP BY `item_id`) AS `ci` ON `ci`.`item_id` = `i`.`id` INNER JOIN `category` AS `c` ON `c`.`id` = `ci`.`category_id` WHERE (`i`.`published` = :published OR :published IS NULL) AND (`c`.`published` = :published OR :published IS NULL) ";

        $exe = $pdo->prepare($query);

        $exe->bindValue('published', $this->published, PDO::PARAM_INT);
        $exe->bindValue('category_id', $category_id, PDO::PARAM_INT);

        if (!$exe->execute()) {
            throw new Exception(ITEM_GET_ITEM_TOTAL_SELECT_ERROR);
        }

        $itemCount = $exe->fetchColumn();

        return $itemCount;
    }

    public function getItemList($category_id = null, $offset = 0, $count = null) {
        $pdo = Db::getInstance();

        if (Functions::checkNull($count)) {
            $count = $this->getItemTotal();
        }

        $query = "SELECT `i`.`id`, `title`, `i`.`description`, `details`, `i`.`published`, `i`.`create_date`, `create_user_id`, `filename` FROM `item` AS `i` INNER JOIN (SELECT `item_id`, `category_id` FROM `category_item` WHERE (`category_id` = :category_id OR :category_id IS NULL) GROUP BY `item_id`) AS `ci` ON `ci`.`item_id` = `i`.`id` INNER JOIN `category` AS `c` ON `c`.`id` = `ci`.`category_id` WHERE (`i`.`published` = :published OR :published IS NULL) AND (`c`.`published` = :published OR :published IS NULL) ORDER BY `create_date` DESC LIMIT :offset, :count";

        $exe = $pdo->prepare($query);

        $exe->bindValue('published', $this->published, PDO::PARAM_INT);
        $exe->bindValue('category_id', $category_id, PDO::PARAM_INT);
        $exe->bindValue('offset', $offset, PDO::PARAM_INT);
        $exe->bindValue('count', $count, PDO::PARAM_INT);

        if (!$exe->execute()) {
            throw new Exception(ITEM_GET_ITEM_LIST_EXECUTE_ERROR);
        }

        $itemList = [];

        while ($res = $exe->fetch(PDO::FETCH_OBJ)) {
            $this->id = (int) $res->id;
            $this->title = $res->title;
            $this->description = $res->description;
            $this->details = $res->details;
            $this->published = (int) $res->published;
            $this->create_date = $res->create_date;
            $this->create_user_id = (int) $res->create_user_id;
            $this->filename = $res->filename;

            $creator = new User;
            $creator->id = $res->create_user_id;
            $this->creator = $creator->getUser();

            $category = new Category;
            $this->categories = $category->getItemCategoryList($res->id);

            $comment = new Comment;
            $comment->item_id = $res->id;
            $this->comments = $comment->getCommentList();

            $itemList[] = clone $this;
        }

        return $itemList;
    }

    public function getItem() {
        $pdo = Db::getInstance();

        $query = 'SELECT `id`, `title`, `description`, `details`, `published`, `create_date`, `create_user_id`, `filename` FROM `item` WHERE `id` = :id ORDER BY `id` ASC';

        $exe = $pdo->prepare($query);

        $exe->bindValue('id', $this->id, PDO::PARAM_INT);

        if (!$exe->execute()) {
            throw new Exception(ITEM_GET_ITEM_EXECUTE_ERROR);
        }

        if ($exe->rowCount() == 0) {
            return null;
        }

        $res = $exe->fetch(PDO::FETCH_OBJ);

        $this->id = (int) $res->id;
        $this->title = $res->title;
        $this->description = $res->description;
        $this->details = $res->details;
        $this->published = (int) $res->published;
        $this->create_date = $res->create_date;
        $this->create_user_id = (int) $res->create_user_id;
        $this->filename = $res->filename;

        $creator = new User;
        $creator->id = $res->create_user_id;
        $this->creator = $creator->getUser();

        $category = new Category;
        $this->categories = $category->getItemCategoryList($res->id);

        $comment = new Comment;
        $comment->item_id = $res->id;
        $this->comments = $comment->getCommentList();

        return $this;
    }

    private function checkField($type) {
        switch ($type) {
            case 'insert':
                if (Functions::checkStringOrNull($this->title)) {
                    throw new CheckException(USER_CHECK_FIELD_TITLE_ERROR);
                }
                if (Functions::checkStringOrNull($this->description)) {
                    throw new CheckException(USER_CHECK_FIELD_DESCRIPTION_ERROR);
                }
                break;

            default :
                throw new Exception(UNMANAGED_ERROR);
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
