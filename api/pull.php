<?php
error_reporting(0);
require_once('../config/database.php');
require_once('../class/User.class.php');
header('Content-Type: text/plain');
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
