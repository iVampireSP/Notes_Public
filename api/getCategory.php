<?php
error_reporting(0);
session_start();
require_once('../config/database.php');
require_once('../class/User.class.php');
header('Content-Type: text/plain');
$user = new User();
$user->db_con = $db_con;
echo '<li class="mdui-list-item mdui-ripple"><i class="mdui-list-item-icon mdui-icon material-icons">playlist_add</i><div class="mdui-list-item-content" onclick="loadAddCategory()">新增分类</div></li>';
$user->getCategorylist();