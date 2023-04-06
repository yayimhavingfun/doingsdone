<?php
require_once("init.php");
require_once("functions/functions.php");
require_once("functions/helpers.php");


if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $auth_form = filter_input_array(INPUT_POST, ["email" => FILTER_DEFAULT, "password" => FILTER_DEFAULT], true);

    $required = ['email', 'password'];
    $errors = [];

    $rules = [
        'email' => function() {
            return validate_email('email');
        }
    ];

    foreach ($auth_form as $key => $value) {
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
        $user = get_user($con, $auth_form['email']);
        if ($user !== null) {
            $errors['password'] = check_password($auth_form['password'], $user);
            if ($errors["password"] === null) {
                session_start();
                $_SESSION["email"] = $user['email'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['id'] = $user['id'];
                header("Location: index.php");
                } else {
                    $errors["email"] = "Такой пользователь не найден";
                }
            }
    }
}

$auth_content = include_template("auth_tmp.php", [
    "errors" => $errors,
]);

$layout_content = include_template("layout.php", [
    "content" => $auth_content,
    "title" => "Дела в порядке",
]);

print($layout_content);
