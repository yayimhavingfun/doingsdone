<?php
ini_set("display_errors", '1');
ini_set("display_startup_errors", '1');
error_reporting(E_ALL);


require_once("init.php");
require_once("functions/functions.php");
require_once("functions/helpers.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $reg_form = filter_input_array(INPUT_POST, ["email" => FILTER_DEFAULT, "password" => FILTER_DEFAULT,
        "name" => FILTER_DEFAULT], true);

    $required = ['email', 'password', 'name'];
    $errors = [];

    $rules = [
        'email' => function() {
            return validate_email('email');
        }
    ];

    foreach ($reg_form as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }
        if (in_array($key, $required) && empty($value)) {
            $errors[$key] = $rule($value);
        }
    }

    $errors = array_filter($errors);

    $reg_form['password'] = password_hash($reg_form['password'], PASSWORD_DEFAULT);

    if (empty($errors)) {
        $sql = "INSERT INTO users (email, password, name, date_created)
                    VALUES (?, ?, ?, NOW())";
        $stmt = db_get_prepare_stmt($con, $sql, $reg_form);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            header("Location: index.php");
        } else {
            $errors['reg'] = "user registration error:" . mysqli_error($con);
        }
    }
}


$reg_content = include_template("register_tmp.php", [
    "errors" => $errors ?? null,
]);

$layout_content = include_template("layout.php", [
    "content" => $reg_content,
    "title" => "Дела в порядке",
]);
print($layout_content);
