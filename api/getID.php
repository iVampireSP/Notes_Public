<?php
error_reporting(0);
session_start();
require_once('../class/User.class.php');
header('Content-Type: text/plain');
$user = new User();
echo $user->getID();