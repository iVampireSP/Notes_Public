<?php
// 数据库连接配置
define('DB_HOST', 'localhost');
define('DB_USER', 'notes_public');
define('DB_PASS', 'notes_public');
define('DB_NAME', 'notes_public');
$db_con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$db_con->query("set names utf8");

// 判断数据库是否连接正常
if ($db_con->connect_error) {
    die('数据库连接失败：' . $db_con->connect_error);
}