<?php
error_reporting(0);
require_once('../config/database.php');
require_once('../class/User.class.php');
header('Content-Type: text/plain');
$user = new User();
$user->db_con = $db_con;
$user->noteid = $_REQUEST['id'];


// 翻页
$page = $_GET['page'];
if (empty($page)) {
    return '*';
}else {
    echo $user->listSharednote($page);
}
