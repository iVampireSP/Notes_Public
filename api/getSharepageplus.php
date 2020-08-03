<?php
session_start();
error_reporting(0);
require_once('../config/database.php');
require_once('../class/User.class.php');
header('Content-Type: text/plain');
$user = new User();
$user->db_con = $db_con;
$user->noteid = $_REQUEST['id'];

// 翻页

// setcookie +1
setcookie('page', $_COOKIE['page']+1, time() + 1000, '/api');

$user->listSharednoteplus();
?>
<!-- 别问我什么在标题标签里面套button，因为他居中一直可以的 -->
<h5 style="text-align:center"><button id="loadMore-btn" onclick="loadMore()" class="mdui-btn mdui-btn-raised mdui-btn-dense mdui-color-theme-accent mdui-ripple">加载更多</button></h5>
<div id="loadMore-btn" style="width: 163px; height: 20px; margin: 0px auto;"><span id="loadMore-btn" style="color: gray;">Powered&nbsp;By&nbsp;Loli.Rocks&nbsp;Team.</span></div>