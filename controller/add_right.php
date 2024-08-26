<?php

    $menu = htmlspecialchars(stripslashes($_POST['menu']));
    $sub_menu = htmlspecialchars(stripslashes($_POST['sub_menu']));
    $user = htmlspecialchars(stripslashes($_POST['user']));
    $data = array(
        'menu' => $menu,
        'sub_menu' => $sub_menu,
        'user' => $user
    );
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";


    //get sub menu name
    $get_sub = new selects();
    $menu_name = $get_sub->fetch_details_group('sub_menus', 'sub_menu', 'sub_menu_id', $sub_menu);
    $right = $menu_name->sub_menu;

    //check if user already has right
    $check = new selects();
    $results = $check->fetch_count_2cond('rights', 'user', $user, 'sub_menu', $sub_menu);
    if($results > 0){
        echo "<p class='exist'>Right already exists for user</p>";
    }else{
        //create user
        $add_data = new add_data('rights', $data);
        $add_data->create_data();
        if($add_data){
            echo "<p>'$right' added to user!</p>";
        }
    }
    
?>
