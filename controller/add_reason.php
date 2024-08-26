<?php

    $reason = ucwords(htmlspecialchars(stripslashes($_POST['reason'])));

    $data = array(
        'reason' => $reason
    );
    //instantiate class
    
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";

    //check if reason already exists
    $check = new selects();
    $results = $check->fetch_count_cond('remove_reasons', 'reason', $reason);
    if($results > 0){
        echo "<p class='exist'><span>$reason</span> already exists</p>";
    }else{
        //add reason
        $add_data = new add_data('remove_reasons', $data);
        $add_data->create_data();
        if($add_data){
            echo "<p><span>$reason</span> created successfully!</p>";
        }
    }