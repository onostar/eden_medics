<?php

    $department = ucwords(htmlspecialchars(stripslashes($_POST['department'])));
    $data = array(
        'department' => $department
    );
    //instantiate class
    
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";

    //check if department exists
    $check = new selects();
    $results = $check->fetch_count_cond('departments', 'department', $department);
    if($results > 0){
        echo "<p class='exist'>$department already exists</p>";
    }else{
        //add new record
        $add_data = new add_data('departments', $data);
        $add_data->create_data();
        if($add_data){
            echo "<p>$department added</p>";
        }
    }
    