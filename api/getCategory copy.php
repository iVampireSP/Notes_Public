<?php
error_reporting(0);
session_start();
require_once('../config/database.php');
require_once('../class/User.class.php');
header('Content-Type: text/plain');
$user = new User();
$user->db_con = $db_con;
echo '<span class="mdui-list-item mdui-ripple" onclick="loadAddCategory()">新增分类</span>';
$user->getCategorylist();