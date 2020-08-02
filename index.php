<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    return '*';
}
require_once('config/config.php');
require_once('config/theme.php');
// $user->addNote('标题', '内容', NULL, $db_con);
?>
<!DOCTYPE html>
<html>

<head>
    <?php mduiHead('记事本'); ?>
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
    </style>
</head>
<?php mduiBody();
mduiHeader('记事本');
mduiMenu(); ?>

<div id="mainContent">
    <ul class="mdui-list">
        <?php
        $user->listNote();
        ?>
    </ul>
    
    <div style="width: 163px; height: 20px; margin: 0px auto;"><span style="color: gray;">Powered&nbsp;By&nbsp;Loli.Rocks&nbsp;Team.</span></div>
</div>
<?php
mduiFooter();
?>
</body>

</html>