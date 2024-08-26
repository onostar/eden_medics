<?php
    session_start();

    class reset_passwordController extends reset_password{
        private $username;
        private $new_password;
        private $re_password;

        public function __construct($username, $new_password, $re_password)
        {
            $this->username = $username;
            $this->new_password = $new_password;
            $this->re_password = $re_password;
        }

        public function change_password(){
            if(strlen($this->new_password) < 6){
                $_SESSION['error'] = "Error! Password too short";
                header("Location: ../view/change_password.php");
            }elseif ($this->new_password !== $this->re_password) {
                $_SESSION['error'] = "Error! Password does not match";
                header("Location: ../view/change_password.php");
            }else{
                $this->update_password($this->username, $this->new_password);
                $_SESSION['success'] = "Password Changed Successfully! <br> Please login";
                unset($_SESSION['user']);
                header("Location: ../index.php");
            }
        }
    }