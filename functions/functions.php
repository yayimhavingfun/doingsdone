<?php
require_once('init.php');
// counts the number of projects in a sidebar
function count_projects(array $tasks, $project_name): int
{
    $i = 0;
    foreach ($tasks as $task) {
        if ($task['project_id'] == $project_name){
            $i++;
        }
    }
    return $i;
}
// counts the difference between dates and lights up the expiring in 24h ones

function get_hours_left ($date): float|int|string
{
    $post_date = date_create($date);
    $cur_date = date_create();
    $diff = date_diff($cur_date, $post_date);
    $format_diff = date_interval_format($diff, "%d %H %I" );
    $arr = explode(" ", $format_diff);
    return $arr[0] * 24 + $arr[1];
}


// saves the text in a form
function get_post_val($name){
    return $_POST[$name] ?? "";
}

function validate_task_title(string $value){
    if (empty($_POST[$value])) {
        return "Поле названия надо заполнить";
    }
}

function validate_project($sa_projects_id){
    if ($_POST['project'] > count($sa_projects_id) && $_POST['project'] < 0) {
        return "Указан несуществующий проект";
    }
}

function validate_date($date_str, string $cur_date)
{
    if (isset($date_str)) {
        if (empty(trim($date_str))) {
            return null;
        }

        if (is_date_valid($date_str) === false) {
            return "Неверный формат даты";
        }

        if ($cur_date > $date_str) {
            return "Выбранная дата должна быть больше или равна текущей";
        }
    } else {
        return null;
    }
}

//saves files
function save_file(): ?string
{
    $name = $_FILES["file"]["name"];
    $path = __DIR__ . '/uploads/';

    $is_moved = move_uploaded_file($_FILES["file"]["tmp_name"], $path . $name);

    if (!$is_moved) {
        return null;
    }
    return "uploads/" . $name;
}

function validate_email($name) {
    if (!filter_input(INPUT_POST, $name, FILTER_VALIDATE_EMAIL)) {
        return "Введите корректный email";
    }
}

function validate_project_name($con, $name) {
    if (empty($_POST[$name])) {
        return "Поле названия надо заполнить";
    }

    $pr_names = $_POST[$name];
    $sql = "SELECT name FROM projects WHERE MATCH(name) AGAINST ('$pr_names')";
    $res = mysqli_query($con, $sql);
    if ($res) {
        $projects_src = mysqli_fetch_all($res, MYSQLI_ASSOC);
    } else {
        $error = "cannot complete query:" . mysqli_error($con);
        print($error);
    }
    if (!empty($projects_src)) {
        return "Такой проект уже существует";
    }
}

function get_user($con, $email) {

    $sql = "SELECT * FROM users WHERE email = ?";

    $stmt = db_get_prepare_stmt($con, $sql, ["email" => $email]);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        return mysqli_error($con);
    }

    $user = mysqli_fetch_all($result, MYSQLI_ASSOC);
    if (empty($user)) {
        return null;
    }

    return $user[0];
}

function check_password($form_password, $user): ?string
{
    if (password_verify($form_password, $user["password"])) {
        return null;
    }

    return "Неверный пароль";
}
