<?php


    // if(isset($_POST['change_passwords'])){
        $username = Ucwords(htmlspecialchars(stripslashes($_POST['username'])));
        $cur_password = htmlspecialchars(stripslashes($_POST['current_password']));
        $new_password = htmlspecialchars(stripslashes($_POST['new_password']));
        // $retype_password = htmlspecialchars(stripslashes($_POST['retype_password']));
        

        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/update.php";

        $update = new Update_table();
        $update->updatePassword($username, $cur_password, $new_password);
        
    // }