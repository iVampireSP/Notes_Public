<?php
session_start();
require_once('config/config.php');
$user->noteid = $_REQUEST['noteid'];
if (!isset($_SESSION['user'])) {
    // 判断是否被共享
    if ($user->getShare() == NULL) {
        header("Location: login.php");
        return '*';
    }
}
if (!isset($_REQUEST['noteid'])) {
    header("Location: index.php");
    return '*';
}
if (!empty($_REQUEST['action'])) {
    // 判断是否登录
    if (!isset($_SESSION['user'])) {
        echo '您没有权限。';
        return '*';
    }else {
        echo $user->shareNote();
        return '*';
    }
}

// Pjax................................... 啊啊啊啊得重写大部分代码了

// 部分内容
$somecontent = <<<START
<style type="text/css">
        img {
            width: 99.5%;
        }
</style>
<div id="noteContent">
<h1 class="mdui-text-color-theme" style="text-align: center">
START;

// 中间内容
$centercontent = <<<START
</h1>
    <pre style="font-family: Arial, Helvetica, sans-serif; white-space: pre-wrap; word-wrap: break-word; font-size: 16px">

START;
// 结束内容
$endcontent = <<<START
</pre>
    <button id="a-but" class="mdui-fab mdui-fab-fixed mdui-color-theme-accent mdui-ripple" onclick="sharenote($user->noteid)"><i class="mdui-icon material-icons">share</i></button>
    </div>
START;
// 返回标题
$content['title'] = $user->getTitle();
// 返回内容
$content['content'] = $somecontent.$user->getTitle().$centercontent.$user->viewNote().$endcontent;
//下面这两句是把PHP数组转成JSON对象返回
header('Content-Type: application/json; charset=utf-8');
echo json_encode($content);
?>