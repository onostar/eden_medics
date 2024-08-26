<?php

    class LoginController extends Login{
        private $username;
        private $password;

        public function __construct($username, $password)
        {
            $this->username = $username;
            $this->password = $password;            
        }

        public function get_user(){
            $this->checkUser($this->username, $this->password);
        }
    }