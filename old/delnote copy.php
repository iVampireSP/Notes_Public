<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    return '*';
}
if (!isset($_GET['noteid'])) {
    header("Location: index.php");
    return '*';
}
require_once('config/config.php');
require_once('config/theme.php');
$user->noteid = $_GET['noteid'];
$user->delNote();
echo '<script>window.location.replace("index.php");</script>';
?>