<?php
    session_start();
    $company = $_SESSION['company_id'];
    $store = ucwords(htmlspecialchars(stripslashes($_POST['store_name'])));
    $store_address = htmlspecialchars(stripslashes($_POST['store_address']));
    $phone = htmlspecialchars(stripslashes(($_POST['phone'])));

    $data = array(
        'company' => $company,
        'store' => $store,
        'store_address' => $store_address,
        'phone_number' => $phone
    );
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";

    //check if store exist
    $check = new selects();
    $results = $check->fetch_count_2cond('stores', 'company', $company, 'store', $store);
    if($results > 0){
        echo "<p class='exist'>$store already exists</p>";
    }else{
        //add new store
        $add_data = new add_data('stores', $data);
        $add_data->create_data();
        if($add_data){
            echo "<p>$store added</p>";
        }
    }