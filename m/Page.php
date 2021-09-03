<?php
class Page {
    private $twig;
    function __construct($twig){
        $this->twig = $twig;
    }
    function show($content, $values){
        try{
            $template = $this->twig->load($content);
            if($values === 0){
                echo $this->twig->render($template);
            } else{
                echo $this->twig->render($template, $values);
            }
        } catch(Exception $e){
            die('Error: ' . $e->getMessage());
        }
    }
    function compilation($title, $act, $values = 0){
        $this->show('header.html',[ 'title' => $title ]);
        $this->show('menu.html', 0);
        $this->show($act, $values);
        $this->show('footer.html', 0);
    }
}
?>