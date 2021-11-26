<?php

use \RedBeanPHP\R as R;

class UserController
{

    public function login($twig)
    {
        echo $twig->render("loginIndex.html");
    }
    public function loginPOST()
    {
        $_POST;
        if (isset($_POST['login'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $usernames = R::getAll("SELECT * FROM users WHERE username='$username'");
            if (empty($usernames)) {
                echo '<p class="error">Username password combination is wrong!</p>';
            } else {
                if (password_verify($password, $usernames[0]['password'])) {
                    $sessions = R::dispense('sessions');
                    $sessions->username = $username;
                    $token = bin2hex(random_bytes(64));
                    $sessions->token = $token;
                    R::store($sessions);
                    $_SESSION['token'] = $token;
                    header("Location:/todo/index");
                } else {
                    echo '<p class="error">Username password combination is wrong!</p>';
                }
            }
        }
    }
}
