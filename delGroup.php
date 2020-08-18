<?php
session_start();
require_once('config/config.php');
if (!isset($_REQUEST['id'])) {
    echo '这里没有什么可以显示的。';
    return '*';
}else {
    $user->delGroup($_REQUEST['id']);
}
?>