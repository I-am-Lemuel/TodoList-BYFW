<?php
use \RedBeanPHP\R as R;
class UserService
{
    
    public function validateLoggedIn()
    {  

        if ($_SESSION['token'] == null) {
            header('Location: /user/login');
            exit;
        }
    }
}
?>