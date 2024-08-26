<?php

    $menu = ucwords(htmlspecialchars(stripslashes($_POST['menu'])));
    $data = array(
        'menu' => $menu
    );
    //instantiate class
    
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";

    //check if menu exists
    $check = new selects();
    $results = $check->fetch_count_cond('menus', 'menu', $menu);
    if($results > 0){
        echo "<p class='exist'>$menu already exists</p>";
    }else{
        //add new record
        $add_data = new add_data('menus', $data);
        $add_data->create_data();
        if($add_data){
            echo "<p>$menu added</p>";
        }
    }
    