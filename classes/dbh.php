<?php
date_default_timezone_set("Africa/Lagos");
    class Dbh{
        protected function connectdb(){
            try {
                $username = "onostarmedia";
                $password = "yMcmb@her0123!";
                $dbh = new PDO('mysql:host=localhost; dbname=eden_medics', $username, $password);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
                return $dbh;
            } catch (PDOException $e) {
                print "Error!". $e->getMessage().
                "<br>";
                die();
            }
        }
    }