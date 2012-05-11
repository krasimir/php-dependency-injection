<?php

    require_once("../src/DI.php");
    
    /**
    * @Inject currentDate
    */
    class View {
        private $line = 0;
        public function show($str) {
            echo $this->currentDate." -> ".$this->line.": ".$str."<br />";
            $this->line += 1;
        }
    }
    
    /**
    * @Inject users
    * @Inject view
    */
    class Page {
    
        private $pageName;
    
        public function __construct($pageName) {
            $this->pageName = $pageName;
        }
        public function showUsers() {   
            $this->view->show("Page: ".$this->pageName);
            foreach($this->users as $user) {
                $this->view->show($user->firstName." ".$user->lastName);
            }
        }
    }
    
    /**
    * @Inject view
    */
    class Navigation {
        public function show() {
            $this->view->show("Home | Users | Contacts");
        }
    }
    
    // mapping
    DI::mapValue("users", array(
        (object) array("firstName" => "John", "lastName" => "Doe"),
        (object) array("firstName" => "Mark", "lastName" => "Black")
    ));
    DI::mapValue("currentDate", date("H:i"));
    DI::mapClass("view", "View");
    // DI::mapClassAsSingleton("view", "View");
    
    // showing content
    $nav = DI::getInstanceOf("Navigation");
    $nav->show();
    $page = DI::getInstanceOf("Page", "Users' page");
    $page->showUsers();
    

?>