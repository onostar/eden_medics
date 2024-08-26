<?php

    if(isset($_POST['submit_login'])){
        $username = ucwords(htmlspecialchars(stripslashes($_POST['username'])));
        $password = htmlspecialchars(stripslashes($_POST['password']));


        /* instantiate classes */
        include "../classes/dbh.php";
        include "../classes/login.php";
        include "../classes/loginContr.php";

        $loginUser = new LoginController($username, $password);
        /* check and login user */
        $loginUser->get_user();
    }