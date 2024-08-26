<?php

    $menu = ucwords(htmlspecialchars(stripslashes($_POST['menus'])));
    $sub_menu = ucwords(htmlspecialchars(stripslashes($_POST['sub_menu'])));
    $url = htmlspecialchars(stripslashes($_POST['sub_menu_url']));
    $data = array(
        'menu' => $menu,
        'sub_menu' => $sub_menu,
        'url' => $url,
    );
    //instantiate class
    
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";

    //check if sub menu exists
    $check = new selects();
    $results = $check->fetch_count_2cond('sub_menus', 'menu', $menu, 'sub_menu', $sub_menu);
    if($results > 0){
        echo "<p class='exist'>$sub_menu already exists</p>";
    }else{
        //add new record
        $add_data = new add_data('sub_menus', $data);
        $add_data->create_data();
        if($add_data){
            echo "<p>$sub_menu added</p>";
        }
    }
    