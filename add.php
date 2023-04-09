<?php
ini_set("display_errors", '1');
ini_set("display_startup_errors", '1');
error_reporting(E_ALL);

require_once("init.php");
require_once("functions/helpers.php");
require_once("functions/functions.php");


session_start();

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

    $add_content = include_template("form-task.php", [
        "projects" => $projects,
        "tasks" => $tasks,
        "sidebar" => $sidebar,
        "errors" => $errors,
    ]);

    $layout_content = include_template("layout.php", [
        "content" => $add_content,
        "title" => "Дела в порядке",
    ]);
}
if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $task_form = filter_input_array(INPUT_POST, ["title" => FILTER_DEFAULT, "project" => FILTER_DEFAULT,
            "date_finish" => FILTER_DEFAULT,
            "user_id" => FILTER_DEFAULT, "file" => FILTER_DEFAULT], true);

        $required = ['title', 'project'];
        $errors = [];
        $sa_projects_id = array_map('intval', $projects_id);
        $task_form['user_id'] = $_SESSION['id'];
        $rules = array(
            'title' => function () {
                return validate_task_title('title');
            },
            'project' => function () use ($sa_projects_id) {
                return validate_project($sa_projects_id);
            },
            'date_finish' => function () {
                return validate_date($_POST['date_finish'], date_create("now")->format("Y-m-d"));
            }
        );

        foreach ($task_form as $key => $value) {
            if (isset($rules[$key])) {
                $rule = $rules[$key];
                $errors[$key] = $rule($value);
            }
            if (in_array($key, $required) && empty($value)) {
                $errors[$key] = $rule($value);
            }
        }

        $errors = array_filter($errors);


        if (!empty($_FILES['file']['name'])) {
            $filepath = save_file();

            if ($filepath === 0) {
                $errors['file'] = "Ошибка загрузки файла";
            } else {
                $task_form["file"] = $filepath;
            }
        }

        if (empty($errors)) {
            if ($task_form['date_finish'] === "") {
                $task_form['date_finish'] = null;
            }
            $sql = "INSERT INTO tasks (title, project_id, date_finish, user_id, file, date_creation)
                        VALUES (?, ?, ?, ?, ?, NOW())";
            $stmt = db_get_prepare_stmt($con, $sql, $task_form);
            $res = mysqli_stmt_execute($stmt);

            if ($res) {
                header("Location: index.php");
            } else {
                $errors['task'] = "task creation error:" . mysqli_error($con);
            }
        }
}

print($layout_content);
