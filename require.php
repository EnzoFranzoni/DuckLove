<?php

session_start();

require_once('vendor/autoload.php');
require_once('includes/configuration.php');
require_once('includes/language.php');
require_once('includes/functions.php');
require_once('includes/message.php');
require_once('includes/pagination.php');
require_once('objects/check_exception.php');
require_once('objects/db.php');
require_once('objects/select.php');
require_once('objects/comment.php');
require_once('objects/item.php');
require_once('objects/user.php');
require_once('objects/user_access.php');
require_once ('objects/category.php');

$user_session = isset($_SESSION['user']) ? unserialize($_SESSION['user']) : null;

if (DEBUG) {
    var_dump($user_session);
}

$breadcrumbs = [];
