<?php
session_start();
require_once('config/config.php');
if (!isset($_REQUEST['id'])) {
    echo '这里没有什么可以显示的。';
    return '*';
}else {
    $user->cgid = $_REQUEST['id'];
}
?>
 <ul class="mdui-list">
            <?php
                $user->listCg()
            ?>
        </ul>
<div style="width: 163px; height: 20px; margin: 0px auto;"><span style="color: gray;">Powered&nbsp;By&nbsp;Loli.Rocks&nbsp;Team.</span></div>