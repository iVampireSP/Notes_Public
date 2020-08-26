<?php
session_start();
setcookie('page', '1', time() + 1000, '/api');
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    return '*';
}
require_once('config/config.php');
require_once('config/theme.php');
// $user->addNote('标题', '内容', NULL, $db_con);
// 翻页
?>
<style type="text/css">.mdui-list-item,.mdui-list-item .mdui-ripple {border-radius: 10px;}</style>
<!-- 别问我什么在标题标签里面套button，因为他居中一直可以的 -->
<h5 style="text-align:center"><button id="loadMore-btn" onclick="loadMore()" class="mdui-btn mdui-btn-raised mdui-btn-dense mdui-color-theme-accent mdui-ripple">加载更多</button></h5>
<div id="loadMore-btn" style="width: 163px; height: 20px; margin: 0px auto;"><span id="loadMore-btn" style="color: gray;">Powered&nbsp;By&nbsp;Loli.Rocks&nbsp;Team.</span></div>