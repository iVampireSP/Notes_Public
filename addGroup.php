<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    return '*';
}
require_once('config/config.php');
if (!empty($_POST['name'])) {
    echo $_POST['name'] . $_POST['nickname'] . $_POST['ip_port']. $_POST['password'];
    $user->addGroup($_POST['name'], $_POST['nickname'], $_POST['ip_port'], $_POST['password']);
    return '*';
}
?>
<button class="mdui-btn" style="position: relative;top:10px"  onclick="loadIndex();"><i class="mdui-icon material-icons">arrow_back</i>返回首页</button>
    <h1 style="font-weight: 400;">组</h1>
    <span>组服务器是一个多人协作的服务器。无论是哪一个用户，只要在同一个服务器中，就可以实时编辑内容。<br />组功能还在完善中...</span>
    <form name="newgroup">
        <div class="mdui-textfield mdui-textfield-floating-label">
            <label class="mdui-textfield-label">您在组中的昵称</label>
            <input id="nickname" class="mdui-textfield-input" type="text" autocomplete="off" autofocus required />
        </div>
        <div class="mdui-textfield mdui-textfield-floating-label">
            <label class="mdui-textfield-label">组名</label>
            <input id="name" class="mdui-textfield-input" type="text" autocomplete="off" autofocus required />
        </div>
        <div class="mdui-textfield mdui-textfield-floating-label">
            <label class="mdui-textfield-label">组服务器IP:端口</label>
            <input id="group_ip_port" class="mdui-textfield-input" type="text" autocomplete="off" autofocus required />
        </div>
        <div class="mdui-textfield mdui-textfield-floating-label">
            <label class="mdui-textfield-label">组服务器密码（任意，目前不支持）</label>
            <input id="group_pwd" class="mdui-textfield-input" type="password" autocomplete="off" autofocus required />
        </div>
        <div class="mdui-col">
            <span id="a-but" onclick="newGroup()" class="mdui-fab mdui-fab-fixed mdui-color-theme-accent"><i class="mdui-icon material-icons">add</i></span>
        </div>
    </form>