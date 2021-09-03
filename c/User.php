<?php
require_once 'm/Db.php';
require_once 'm/Page.php';

class User{
    private $page;
    private $db;
    function __construct($twig){
        $this->page = new Page($twig);
        $this->db = Db::init_Db();
    }
    function show_users(){
        $table= $this->db::select("SELECT * FROM `users`");
        if(!$table){
            $table = null;
        }
        $this->page->compilation('Пользователи', 'table_users.html', [
            'table' => $table
        ]);
    }
    function add_users(){
        if(isset($_POST['submit'])){
            if(!$_POST['login'] || !$_POST['name'] || !$_POST['password']){
                $message = 'Введите все необходимые параметры';
                $this->page->compilation('Новый пользователь', 'add_user.html', [
                    'message' => $message
                ]);
                die();
            }
            $login = htmlspecialchars(trim($_POST['login']));
            $password = md5($login . htmlspecialchars(trim($_POST['password'])));
            $name = htmlspecialchars(trim($_POST['name']));
            if($this->db::select("SELECT * FROM `users` WHERE `login` = '$login'")){
                $this->page->compilation('Новый пользователь', 'add_user.html', [
                    'message' => 'Придумайте другой логин'
                ]);
            } else{
                $this->db::insert('users', ['login', 'password', 'name'], [$login, $password, $name]);
                $this->page->compilation('Новый пользователь', 'add_user.html', [
                    'message' => 'Новый пользователь добавлен'
                ]);
            }
        } else {
            $this->page->compilation('Новый пользователь', 'add_user.html');
        }
    }
    function del_users(){
        if($_GET['id']){
            $this->db::delete('users', $_GET['id']);
            $this->show_users();
        } else {
            die();
        }
    }
}
?>