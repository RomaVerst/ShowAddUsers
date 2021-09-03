<?php
require_once 'Twig/autoload.php';
spl_autoload_register(function($classname){
    require_once 'c/' . $classname . '.php'; 
});
$loader = new \Twig\Loader\FilesystemLoader('v');
$twig = new \Twig\Environment($loader);
$act = ($_GET['act']) ? $_GET['act'] : 'show_users';
$controller = new User($twig);

$controller->$act();
?>