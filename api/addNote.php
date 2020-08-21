<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    return '*';
}
require_once('../config/database.php');
require_once('../class/User.class.php');
header('Content-Type:application/json; charset=utf-8');
$user = new User();
$user->db_con = $db_con;

$data = json_decode(file_get_contents('php://input'), true);
 // echo json_encode($data);

$user->addNote($data['title'], $data['content'], $data['cgid']);
$return = array('success' => '成功');
echo json_encode($return);
    //echo '新的记事本已添加。';
    // return '*';