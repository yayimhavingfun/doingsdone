<?php
ini_set("display_errors", '1');
ini_set("display_startup_errors", '1');
error_reporting(E_ALL);


session_start();
$projects_id = [];
$show_complete_tasks = rand(0, 1);
$s_id = $_SESSION['id'];

$con = mysqli_connect('localhost', 'root', '', 'doingsdone');
mysqli_set_charset($con, "utf8");
mysqli_select_db($con, 'doingsdone');
if (!$con) {
    $error = "cannot connect with the database: " . mysqli_connect_error();
    print($error);
}

$user_query = "SELECT id FROM users u WHERE u.id = '$s_id'";
$user_res = mysqli_query($con, $user_query);
if ($user_query) {
    $user_id = mysqli_fetch_all($user_res, MYSQLI_ASSOC);
} else {
    $error = "cannot complete query:" . mysqli_error($con);
    print($error);
}


$sql = "SELECT id, user_id, name FROM projects p WHERE p.user_id = '$s_id'";
$res = mysqli_query($con, $sql);
if ($res) {
    $projects = mysqli_fetch_all($res, MYSQLI_ASSOC);
    $projects_name = array_map('mb_strtolower', array_column($projects, 'name'));
    $projects_id = array_column($projects, 'id');
    $max_project_id = mysqli_insert_id($con);
} else {
    $error = "cannot complete query:" . mysqli_error($con);
    print($error);
}


$sql = "SELECT * FROM tasks t WHERE t.user_id = '$s_id'";
$res = mysqli_query($con, $sql);
if ($res) {
    $tasks = mysqli_fetch_all($res, MYSQLI_ASSOC);
} else {
    $error = "cannot complete query:" . mysqli_error($con);
    print($error);
}

$tasks_sb = $tasks;
$tasks_main = $tasks;







