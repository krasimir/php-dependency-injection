<?php

    class View {
        public function show($str) {
            echo "<p>".$str."</p>";
        }
    }
    
    class UsersModel {
        public function get() {
            return array(
                (object) array("firstName" => "John", "lastName" => "Doe"),
                (object) array("firstName" => "Mark", "lastName" => "Black")
            );
        }
    }
    
    class Navigation {
        private $view;
        public function __construct() {
            $this->view = new View();
        }
        public function show() {
            $this->view->show('
                <a href="#" title="Home">Home</a> | 
                <a href="#" title="Home">Products</a> | 
                <a href="#" title="Home">Contacts</a>
            ');
        }
    }
    
    class Content {
    
        private $title;
        private $view;
        private $usersModel;
        
        public function __construct($title) {
            $this->title = $title;
            $this->view = new View();
            $this->usersModel = new UsersModel();
        }
        public function show() {  
            $users = $this->usersModel->get();
            $this->view->show($this->title);
            foreach($users as $user) {
                $this->view->show($user->firstName." ".$user->lastName);
            }
        }
    }
    
    class PageController {
        public function show() {
            $navigation = new Navigation();
            $content = new Content("Content title!");
            $navigation->show();
            $content->show();
        }
    }
    
    $page = new PageController();
    $page->show();


?>