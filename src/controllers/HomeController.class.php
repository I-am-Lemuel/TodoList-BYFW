<?php
use \RedBeanPHP\R as R;
require_once "services/UserService.class.php";
class HomeController
{
    
    public function index($twig)
    {  
        echo $twig->render("homeIndex.html");
    }
}    
?>