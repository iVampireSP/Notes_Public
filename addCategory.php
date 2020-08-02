<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    return '*';
}
require_once('config/config.php');
if (!empty($_POST['name'])) {
    $user->addCategory($_POST['name']);
    return '*';
}
?>
<button class="mdui-btn" style="position: relative;top:10px"  onclick="loadIndex();"><i class="mdui-icon material-icons">arrow_back</i>返回首页</button>
    <form name="newcg">
        <div class="mdui-textfield mdui-textfield-floating-label">
            <label class="mdui-textfield-label">分类名</label>
            <input id="name" class="mdui-textfield-input" type="text" autocomplete="off" autofocus required />
        </div>
        <div class="mdui-col">
            <span id="a-but" onclick="newCategory()" class="mdui-fab mdui-fab-fixed mdui-color-theme-accent"><i class="mdui-icon material-icons">add</i></span>
        </div>
    </form>