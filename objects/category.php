<?php

class Category {

    private
            $id,
            $name,
            $description,
            $published,
            $create_date;

    public function __construct($id = null, $name = null, $description = null, $published = null, $create_date = null) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->published = $published;
        $this->create_date = $create_date;
    }

    public function insertCategory() {
        $this->checkField('insert');

        $pdo = Db::getInstance();

        $query = "INSERT INTO `category` (`name`,`description`, `published`, `create_date`) VALUES (:name, :description, :published, :create_date)";

        $exe = $pdo->prepare($query);

        $exe->bindValue('name', $this->name, PDO::PARAM_STR);
        $exe->bindValue('description', $this->description, PDO::PARAM_STR);
        $exe->bindValue('published', $this->published, PDO::PARAM_BOOL);
        $exe->bindValue('create_date', $this->create_date, PDO::PARAM_STR);

        if (!$exe->execute()) {
            throw new Exception(CATEGORY_INSERT_CATEGORY_EXECUTE_ERROR);
        }

        $this->id = $pdo->lastInsertId();

        return true;
    }

    public function updateCategory() {
        $this->checkField('update');

        $pdo = Db::getInstance();

        $query = "UPDATE `category` SET `name` = :name, `description` = :description, `published` = :published, `create_date` = :create_date WHERE `id` = :id";

        $exe = $pdo->prepare($query);

        $exe->bindValue('id', $this->id, PDO::PARAM_INT);
        $exe->bindValue('name', $this->name, PDO::PARAM_STR);
        $exe->bindValue('description', $this->description, PDO::PARAM_STR);
        $exe->bindValue('published', $this->published, PDO::PARAM_BOOL);
        $exe->bindValue('create_date', $this->create_date, PDO::PARAM_STR);

        if (!$exe->execute()) {
            throw new Exception(CATEGORY_UPDATE_CATEGORY_EXECUTE_ERROR);
        }

        return true;
    }

    public function deleteCategory() {
        $pdo = Db::getInstance();

        $query = "DELETE FROM `category` WHERE `id` = :id";

        $exe = $pdo->prepare($query);

        $exe->bindValue('id', $this->id, PDO::PARAM_INT);

        if (!$exe->execute()) {
            throw new Exception(CATEGORY_DELETE_CATEGORY_EXECUTE_ERROR);
        }

        return true;
    }

    public function getCategoryTotal() {
        $pdo = Db::getInstance();

        $query = "SELECT COUNT(*) FROM `category` WHERE (published = :published OR :published IS NULL)";

        $exe = $pdo->prepare($query);

        $exe->bindValue('published', $this->published, PDO::PARAM_INT);

        if (!$exe->execute()) {
            throw new Exception(CATEGORY_GET_CATEGORY_TOTAL_SELECT_ERROR);
        }

        $categoryCount = $exe->fetchColumn();

        return $categoryCount;
    }

    public function getCategoryList() {
        $pdo = Db::getInstance();

        $query = "SELECT `id`, `name`, `description`, `published`, `create_date` FROM `category` WHERE (published = :published OR :published IS NULL) ORDER BY `create_date` ASC";

        $exe = $pdo->prepare($query);

        $exe->bindValue('published', $this->published, PDO::PARAM_INT);

        if (!$exe->execute()) {
            throw new Exception(CATEGORY_GET_CATEGORY_LIST_EXECUTE_ERROR);
        }

        $categoryList = [];

        while ($res = $exe->fetch(PDO::FETCH_OBJ)) {
            $this->id = (int) $res->id;
            $this->name = $res->name;
            $this->description = $res->description;
            $this->published = (int) $res->published;
            $this->create_date = $res->create_date;

            $categoryList[] = clone $this;
        }

        return $categoryList;
    }

    public function getItemCategoryList($item_id = null, $offset = 0, $count = null) {
        $pdo = Db::getInstance();

        if (Functions::checkNull($count)) {
            $count = $this->getCategoryTotal();
        }

        $query = "SELECT `c`.`id`, `name`, `description`, `published`, `create_date` FROM `category` AS `c` INNER JOIN `category_item` AS `ci` ON `ci`.`category_id` = `c`.`id` WHERE (published = :published OR :published IS NULL) AND (`item_id` = :item_id OR :item_id IS NULL) GROUP BY `c`.`id` ORDER BY `create_date` DESC LIMIT :offset, :count";

        $exe = $pdo->prepare($query);

        $exe->bindValue('published', $this->published, PDO::PARAM_INT);
        $exe->bindValue('item_id', $item_id, PDO::PARAM_INT);
        $exe->bindValue('offset', $offset, PDO::PARAM_INT);
        $exe->bindValue('count', $count, PDO::PARAM_INT);

        if (!$exe->execute()) {
            throw new Exception(CATEGORY_GET_CATEGORY_LIST_EXECUTE_ERROR);
        }

        $categoryList = [];

        while ($res = $exe->fetch(PDO::FETCH_OBJ)) {
            $this->id = (int) $res->id;
            $this->name = $res->name;
            $this->description = $res->description;
            $this->published = (int) $res->published;
            $this->create_date = $res->create_date;

            $categoryList[] = clone $this;
        }

        return $categoryList;
    }

    public function getCategory() {
        $pdo = Db::getInstance();

        $query = 'SELECT `id`, `name`, `description`,  `published`, `create_date` FROM `category` WHERE `id` = :id ORDER BY `id` ASC';

        $exe = $pdo->prepare($query);

        $exe->bindValue('id', $this->id, PDO::PARAM_INT);

        if (!$exe->execute()) {
            throw new Exception(CATEGORY_GET_CATEGORY_EXECUTE_ERROR);
        }

        if ($exe->rowCount() == 0) {
            return null;
        }

        $res = $exe->fetch(PDO::FETCH_OBJ);

        $this->id = (int) $res->id;
        $this->name = $res->name;
        $this->description = $res->description;
        $this->published = (int) $res->published;
        $this->create_date = $res->create_date;

        return $this;
    }

    private function checkField($type) {
        switch ($type) {
            case 'insert':
            case 'update':
                if (Functions::checkStringOrNull($this->name)) {
                    throw new CheckException(CATEGORY_CHECK_FIELD_NAME_ERROR);
                }
                if (Functions::checkStringOrNull($this->description)) {
                    throw new CheckException(CATEGORY_CHECK_FIELD_DESCRIPTION_ERROR);
                }
                if (!Functions::checkInteger($this->published)) {
                    throw new CheckException(CATEGORY_CHECK_FIELD_PUBLISHED_ERROR);
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
