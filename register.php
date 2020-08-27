<?php
session_start();
if (isset($_SESSION['user'])) {
    header('Location: index.php');
}
require_once('config/config.php');
if (!empty($_REQUEST['password'])) {
    // 用户注册
    sleep(1); // 这一秒，是为了打动你的心。
    echo $user->Register($_REQUEST['password']);
    return '*';
}
require_once('config/theme.php');
?>
<!DOCTYPE html>
<html>

<head>
    <?php mduiHead('注册 Sweet Home Notes'); ?>
        <style type="text/css">
        .texto {
            width: 50%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        #menu .mdui-list-item,
        .mdui-collapse-item-header {
            border-radius: 0px 50px 50px 0px;
        }

        #categorys .mdui-list-item,
        .mdui-collapse-item-header {
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

        .mdui-appbar {
            box-shadow: 0 1px 6px 0 rgba(32, 33, 36, .28);
        }
        
        #menu .mdui-list-item-content {
            font-size: unset
        }

        #categorys .mdui-list-item-content {
            font-size: unset
        }
        
        .mdui-list-item-active {
            color: rgb(27,116,232);
        }
        
        .mdui-list-item-active * {
            color: rgb(27,116,232);
        }
    </style>
</head>

<?php mduiBody();
mduiHeader('注册');
mduiMenu(); ?>
<div id="mainContent">
<h1 class="mdui-text-color-theme" style="font-weight: unset;position:relative;top:4px;">Register - 洁白 闪耀 奇迹 之花 White Lily!</h1>
<p>注册非常简单。您只需要输入密码然后点按"注册"即可完成，用户ID可在上方工具栏获取。</p>
<form name="Register">
    <div class="mdui-textfield mdui-textfield-floating-label">
        <label class="mdui-textfield-label">密码</label>
        <input id="password" class="mdui-textfield-input" type="password" />
    </div>
    <div class="mdui-row-xs-2">
        <div class="mdui-col">
            <span class="mdui-btn mdui-btn-block mdui-color-theme-accent mdui-ripple" onclick="userReg()">注册</span>
        </div>
        <div class="mdui-col">
            <a class="mdui-btn mdui-color-theme-accent mdui-ripplent" href="login.php">登录</a>
        </div>
    </div>
</form>
<div style="width: 163px; height: 20px; margin: 0px auto;padding-top: 1rem"><span style="position: absolute; bottom: 5px; color: gray;">Powered&nbsp;By&nbsp;Loli.Rocks&nbsp;Team.</span></div>
</div>
<?php mduiFooter(); ?>
</body>

</html>