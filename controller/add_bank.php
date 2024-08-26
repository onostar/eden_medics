<?php

    $bank = ucwords(htmlspecialchars(stripslashes($_POST['bank'])));
    $acn = htmlspecialchars(stripslashes(($_POST['account_num'])));

    $data = array(
        'bank' => $bank,
        'account_number' => $acn
    );
    //instantiate class
    
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";

    //check if bank account already exists
    $check = new selects();
    $results = $check->fetch_count_2cond('banks', 'bank', $bank, 'account_number', $acn);
    if($results > 0){
        echo "<p class='exist'>This <span>$bank</span> account already exists!</p>";
    }else{
        //create user
        $add_data = new add_data('banks', $data);
        $add_data->create_data();
        if($add_data){
            echo "<p><span>$bank</span> added successfully!</p>";
        }
    }