<?php

namespace Miax\DI;

class CDIFactory extends CDIFactoryDefault
{
    public function __construct()
    {
        parent::__construct();
		
		
 		// Include support for database 
        $this->setShared('db', function() {
            $db = new \Mos\Database\CDatabaseBasic();
            $db->setOptions(require MIAX_APP_PATH . 'config/config_mysql.php');
			//$db->setOptions(require MIAX_APP_PATH . 'config/config_sqlite.php');
            $db->connect();
            return $db;
        });
		
		// Starts/injects the Controller for the Users model
        $this->setShared('UsersController', function() {
            $controller = new \Miax\Users\UsersController();
            $controller->setDI($this);
            return $controller;
        });

        // Starts/injects the Controller for the Setup model
        $this->setShared('SetupController', function() {
            $controller = new \Miax\Setup\Setup();
            $controller->setDI($this);
            return $controller;
        });

        // Starts/injects the Controller for the Questions model
        $this->setShared('QuestionsController', function() {
            $controller = new \Miax\Questions\QuestionsController();
            $controller->setDI($this);
            return $controller;
        });

        $this->setShared('users', function(){
            $users = new \Miax\Users\Users();
            $users->setDI($this);
            return $users;
        });

        $this->setShared('questions', function(){
            $questions = new \Miax\Questions\Questions();
            $questions->setDI($this);
            return $questions;
        });
		
		// Create controller service for forms.
        $this->set('form', '\Mos\HTMLForm\CForm');

    }
}
