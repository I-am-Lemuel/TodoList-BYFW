<?php

require_once '../vendor/autoload.php';

use \RedBeanPHP\R as R;

R::setup(
    'mysql:host=localhost;dbname=todo',
    'root',
    ''
);
$twig = Twig_Environment();
$link = $_SERVER['REQUEST_URI'];
session_start();
if ($_SERVER['REQUEST_URI'] == "/") {
    require_once "controllers/HomeController.class.php";
    $home = (new HomeController())->index($twig);
    die();
}

$link = explode("/", $link);
$method = isset($link[2]) ? $link[2] : "";
$class = ucfirst($link[1]);
$classcontroller = $class . 'Controller';
$file = "controllers/" . $class . "Controller.class.php";

if (!file_exists($file)) {
    echo $file;
    echo "De pagina waar je naar zoekt is er helaas niet (meer).";
    http_response_code(404);
    die();
}

require_once $file;

$reqMethod = $_SERVER['REQUEST_METHOD'];

switch ($reqMethod) {
    case 'GET':
        break;
    case 'POST':
        $method .= 'Post';
        break;
}
if (method_exists($classcontroller, $method)) {
    $controller = $class . "Controller";
    $home = (new $controller())->$method($twig);
    die();
}

echo "De pagina waar je naar zoekt is er helaas niet (meer).";
http_response_code(404);
die();

function Twig_Environment()
{
    $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/views');
    $twig = new \Twig\Environment($loader);
    return $twig;
}
