<?php
ini_set("display_errors", '1');
ini_set("display_startup_errors", '1');
error_reporting(E_ALL);

require_once("functions/helpers.php");
require_once("functions/functions.php");
require_once("init.php");


$search = $_GET['search'] ?? null;
$sql = "SELECT * FROM tasks WHERE MATCH(title) AGAINST ('$search')";
$res = mysqli_query($con, $sql);
if ($res) {
    $tasks_src = mysqli_fetch_all($res, MYSQLI_ASSOC);
} else {
    $error = "cannot complete query:" . mysqli_error($con);
    print($error);
}

if (isset($_GET['show_completed'])) {
    if ($_GET['show_completed'] === '1') {
        $show_complete_tasks = 1;
    }
}

if (isset($_GET['check']) and isset($_GET['task_id'])) {
    $status = intval($_GET['check']);
    $task_id = intval($_GET['task_id']);
    mysqli_query($con, "UPDATE tasks SET status = '$status'  WHERE id = '$task_id'");
}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if ($search != null) {
        $search = trim($search);
    }
    if ($search) {
        $tasks_main = $tasks_src;

        if (empty($tasks_src)) {
            $search_empty_response = "Ничего не найдено по вашему запросу";
        }
    }
}

if (isset($_GET['filter'])) {
    if ($_GET['filter'] == 'today') {
        $sql = "SELECT * FROM tasks WHERE date_finish = CURDATE()";
        $res = mysqli_query($con, $sql);
        if ($res) {
            $tasks_main = mysqli_fetch_all($res, MYSQLI_ASSOC);
        } else {
            $error = "cannot complete query:" . mysqli_error($con);
            print($error);
        }
    }


    if ($_GET['filter'] == 'tomorrow') {
        $sql = "SELECT * FROM tasks WHERE DATEDIFF(date_finish, CURDATE()) = 1";
        $res = mysqli_query($con, $sql);
        if ($res) {
            $tasks_main = mysqli_fetch_all($res, MYSQLI_ASSOC);
        } else {
            $error = "cannot complete query:" . mysqli_error($con);
            print($error);
        }
    }

    if ($_GET['filter'] == 'yesterday') {
        $sql = "SELECT * FROM tasks WHERE DATEDIFF(date_finish, CURDATE()) < -1";
        $res = mysqli_query($con, $sql);
        if ($res) {
            $tasks_main = mysqli_fetch_all($res, MYSQLI_ASSOC);
        } else {
            $error = "cannot complete query:" . mysqli_error($con);
            print($error);
        }
    }
}

if (empty($_SESSION)) {
    $guest_content = include_template('guest.php',[]);
    $layout_content = include_template('layout.php',[
        "title" => "Дела в порядке",
        "content" => $guest_content,
    ]);
} else {
    $sidebar = include_template("projects-sidebar.php", [
        "projects" => $projects,
        "tasks_sb" => $tasks_sb,

    ]);

    $page_content = include_template("main.php", [
        "projects" => $projects,
        "tasks_main" => $tasks_main,
        "show_complete_tasks" => $show_complete_tasks,
        "sidebar" => $sidebar,
        "tasks_src" => $tasks_src,
        "search_empty_response" => $search_empty_response ?? null,
    ]);

    $layout_content = include_template("layout.php", [
        "content" => $page_content,
        "title" => "Дела в порядке",
    ]);

}
print($layout_content);


