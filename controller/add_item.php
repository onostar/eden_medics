<?php

    $department = htmlspecialchars(stripslashes($_POST['department']));
    $category = htmlspecialchars(stripslashes($_POST['item_category']));
    $item = strtoupper(htmlspecialchars(stripslashes(($_POST['item']))));
    $barcode = htmlspecialchars(stripslashes(($_POST['barcode'])));
    $reorder_level = 10;
    $data = array(
        'item_name' => $item,
        'department' => $department,
        'category' => $category,
        'barcode' => $barcode,
        'reorder_level' => $reorder_level
    );
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";

    //check if item already Exist
    $check = new selects();
    $results = $check->fetch_count_2cond('items', 'category', $category, 'item_name', $item);
    if($results > 0){
        echo "<p class='exist'><span>$item</span> already exists</p>";
    }else{
        //create item
        $add_data = new add_data('items', $data);
        $add_data->create_data();
        if($add_data){
            echo "<p><span>$item</span> created successfully!</p>";
        }
    }