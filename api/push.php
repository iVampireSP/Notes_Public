<?php
// error_reporting(0);
// 1595152469031
session_start();
require_once('../config/database.php');
require_once('../class/User.class.php');
header('Content-Type: text/plain');
$user = new User();
$user->db_con = $db_con;
// 判断是否完整
if (empty($_REQUEST['userid'])||empty($_REQUEST['password'])||empty($_REQUEST['title'])||empty($_REQUEST['content'])) {
    echo 'NULL';
    return '*';
}

// 登录
echo $user->Login($_REQUEST['userid'], $_REQUEST['password']);
// 提交记事本
$user->addNote($_REQUEST['title'], $_REQUEST['content']);
// 注销
// $user->userLogout();