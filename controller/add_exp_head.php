<?php

    $exp_head = ucwords(htmlspecialchars(stripslashes($_POST['exp_head'])));

    //instantiate class
    
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    $data = array(
        'expense_head' => $exp_head,
    );
    //check if item already Exist
    $check = new selects();
    $results = $check->fetch_count_cond('expense_heads', 'expense_head', $exp_head);
    if($results > 0){
        echo "<p class='exist'><span>$exp_head</span> already exists</p>";
    }else{
        $add_item = new add_data('expense_heads', $data);
        $add_item->create_data();
        if($add_item){
            echo "<p><span>$exp_head</span> created successfully!</p>";
        }
    }