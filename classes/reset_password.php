<?php

    class reset_password extends Dbh{
        protected function update_password($username, $newPassword){
            $hashed_pwd = password_hash($newPassword, PASSWORD_DEFAULT);
            $change_password = $this->connectdb()->prepare("UPDATE users SET user_password = :user_password WHERE username = :username");
            $change_password->bindValue("user_password", $hashed_pwd);
            $change_password->bindValue("username", $username);
            $change_password->execute();
            
        }
    }