<?php
session_start();
require_once('config/config.php');
if (isset($_REQUEST['userid']) || isset($_REQUEST['password'])) {
    // 用户登录
    echo $user->Login($_REQUEST['userid'], $_REQUEST['password']);
    return '*';
}
if (isset($_SESSION['user'])) {
    header('Location: index.php');
}
require_once('config/theme.php');
?>
<!DOCTYPE html>
<html>

<head>
    <?php mduiHead('登录'); ?>
    <style type="text/css">
        .texto {
            width: 50%;
            overflow:hidden;
            text-overflow:ellipsis;
            white-space:nowrap;
        }
        
        #menu .mdui-list-item, .mdui-collapse-item-header {
            border-radius: 0px 50px 50px 0px;
        }
        ul .mdui-list-item {
            border-radius: 10px;
        }

        .mdui-dialog {
            border-radius: 7px;
        }

        .mdui-btn {
            border-radius: 5px;
        }
    </style>
</head>
<?php mduiBody();
mduiHeader('登录');
mduiMenu(); ?>
<div id="mainContent">
<h1 class="mdui-text-color-theme" style="font-weight: unset;position:relative;top:4px;">Login</h1>
<form name="Login">
    <div class="mdui-textfield mdui-textfield-floating-label">
        <label class="mdui-textfield-label">用户ID</label>
        <input id="userid" class="mdui-textfield-input" type="text" />
    </div>
    <div class="mdui-textfield mdui-textfield-floating-label">
        <label class="mdui-textfield-label">密码</label>
        <input id="password" class="mdui-textfield-input" type="password" />
    </div>
    <div class="mdui-row-xs-2">
        <div class="mdui-col">
            <span class="mdui-btn mdui-btn-block mdui-color-theme-accent mdui-ripple" onclick="userLogin()">登录</span>
        </div>
        <a class="mdui-btn mdui-color-theme-accent mdui-ripplent" href="register.php">注册</a>
    </div>
</form>
<div style="width: 163px; height: 20px; margin: 0px auto;"><span style="position: absolute; bottom: 5px; color: gray;">Powered&nbsp;By&nbsp;Loli.Rocks&nbsp;Team.</span></div>
</div>
<?php mduiFooter(); ?>
</body>

</html>