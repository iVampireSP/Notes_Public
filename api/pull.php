<?php
error_reporting(0);
require_once('../config/database.php');
require_once('../class/User.class.php');
$type = htmlspecialchars(urlencode($_REQUEST['type']));
if (empty($type)) {
    $type = 'plain';
}

$user = new User();
$user->db_con = $db_con;
$user->noteid = $_REQUEST['id'];
switch ($_REQUEST['action']) {
    case 'getTitle':
        header("Content-Type: text/$type");
        echo $user->getTitle();
    break;

    case 'getContent':
        header("Content-Type: text/$type");
        echo $user->viewNote();
    break;

    default:
    header('Content-Type:application/json; charset=utf-8');
    $array = array(
        'title' => $user->getTitle(),
        'content' => $user->viewNote()
    );
    print_r(json_encode($array));
break;
}