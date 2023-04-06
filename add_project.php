<?php
require_once("init.php");
require_once("functions/helpers.php");
require_once("functions/functions.php");

session_start();


if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $project_form = filter_input_array(INPUT_POST, ["name" => FILTER_DEFAULT, "user_id" => FILTER_DEFAULT]);

    $required = ['name'];
    $errors = [];
    $project_form["user_id"] = $_SESSION['id'];

    $rules = [
        'name' => function () use ($con) {
            return validate_project_name($con, 'name');
        },
    ];

    foreach ($project_form as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }
        if (in_array($key, $required) && empty($value)) {
            $errors[$key] = $rule($value);
        }
    }

    $errors = array_filter($errors);

    if (empty($errors)) {
        $sql = "INSERT INTO projects (name, user_id) VALUES (?, ?)";
        $stmt = db_get_prepare_stmt($con, $sql, $project_form);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            header("Location: index.php ");
        } else {
            $errors['project'] = "project creation error:" . mysqli_error($con);
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

    $add_p_content = include_template("form-project.php", [
        "sidebar" => $sidebar,
        "errors" => $errors,
    ]);

    $layout_content = include_template("layout.php", [
        "content" => $add_p_content,
        "title" => "Дела в порядке",
    ]);
}
print($layout_content);