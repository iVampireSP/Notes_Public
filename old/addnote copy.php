<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    return '*';
}
require_once('config/config.php');
require_once('config/theme.php');
?>

<!DOCTYPE html>
<html>

<head>
    <?php mduiHead('新增记事本'); ?>
</head>
    <?php mduiBody(); mduiHeader('新增记事本') ; mduiMenu(); ?>
    <form name="addnote" method="post" action="addnote.php">
        <div class="mdui-textfield mdui-textfield-floating-label">
            <label class="mdui-textfield-label">标题</label>
            <input class="mdui-textfield-input" type="text" name="title" autocomplete="off" autofocus required />
        </div>
        <div class="mdui-textfield">
            <textarea class="mdui-textfield-input" rows="10" placeholder="请输入记事本内容" name="content" autocomplete="off"></textarea>
        </div>
        <div class="mdui-col">
            <button type="submit" class="mdui-btn mdui-btn-block mdui-color-theme-accent mdui-ripple">新增记事本</button>
        </div>
    </form>
    <?php
        if (!empty($_REQUEST['title'])||!empty($_REQUEST['content'])) {
            $user->addNote($_REQUEST['title'], $_REQUEST['content']);
            echo '<script>window.location.replace("index.php");</script>';
        }
    ?>
</body>

</html>
