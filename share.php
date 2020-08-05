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

require_once('config/theme.php');

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
?>
<!DOCTYPE html>
<html>

<head>
    <?php mduiHead('浏览：' . $user->getTitle()); ?>
    <style type="text/css">
        img {
            width: 99.5%;
        }
    </style>
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

        #menu * {
            color: rgb(27,116,232);
        }
        
        #menu .mdui-list-item-content {
            font-size: unset
        }

        #categorys * {
            color: rgb(27,116,232);
        }

        #categorys .mdui-list-item-content {
            font-size: unset
        }
    </style>
</head>
    <?php mduiBody(); mduiHeader('浏览：' . $user->getTitle()); mduiMenu(); ?>
    <div id="mainContent">
    <h1 class="mdui-text-color-theme" style="text-align: center"><?php echo $user->getTitle();?></h1>
    <pre style="font-family: Arial, Helvetica, sans-serif; white-space: pre-wrap; word-wrap: break-word; font-size: 16px"><?php echo $user->viewNote(); ?></pre>
    <button class="mdui-fab mdui-fab-fixed mdui-color-theme-accent mdui-ripple" onclick="sharenote(<?php echo $_REQUEST['noteid']; ?>)" ><i class="mdui-icon material-icons">share</i></button>
    </div>
    <?php
    mduiFooter();
    ?>
</body>

</html>
