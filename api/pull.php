<?php
error_reporting(0);
require_once('../config/database.php');
require_once('../class/User.class.php');
$type = htmlspecialchars(urlencode($_REQUEST['type']));
if (empty($type)) {
    $type = 'plain';
}
header("Content-Type: text/$type");
$user = new User();
$user->db_con = $db_con;
$user->noteid = $_REQUEST['id'];
switch ($_REQUEST['action']) {
    case 'getTitle':
        echo $user->getTitle();
    break;

    case 'getContent':
        echo $user->viewNote();
    break;

    default:
    echo $user->getTitle() . PHP_EOL;
    echo $user->viewNote();
break;
}