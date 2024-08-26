<?php

    if(isset($_GET['store'])){
        $store = $_GET['store'];
        $user = $_GET['user'];
    }
    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/update.php";
    
    //get store name
    $get_store = new selects();
    $str = $get_store->fetch_details_group('stores', 'store', 'store_id', $store);
    $store_name = $str->store;

    //update user store
    $update_store = new Update_table();
    $update_store->update('users', 'store', 'user_id', $store, $user);
    if($update_store){
        header("Location: ../view/users.php");
    }