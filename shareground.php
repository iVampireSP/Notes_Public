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
    <?php mduiHead('记事本共享'); ?>
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
    </style>
</head>
    <?php mduiBody(); mduiHeader('记事本共享') ; mduiMenu(); ?>
    <script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.min.js"></script>
    <script src="ajax.js"></script>
    <div id="mainContent">
        <ul class="mdui-list">
            <?php
                $user->listSharednote();
            ?>
        </ul>
        <div style="width: 163px; height: 20px; margin: 0px auto;"><span style="position: absolute; bottom: 5px; color: gray;">Powered&nbsp;By&nbsp;Loli.Rocks&nbsp;Team.</span></div>
    </div>
</body>

</html>
