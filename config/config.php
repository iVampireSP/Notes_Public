<?php
/* 站点配置 */
// 名称
define('SITENAME', 'Sweet Home');

// 实例化
require_once('database.php');
require_once('class/User.class.php');
$user = new User();
$user->db_con = $db_con;