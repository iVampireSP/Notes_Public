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
$user->noteid = $_GET['noteid'];
$user->delNote();
?>