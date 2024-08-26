<?php
    session_start();    
    // if(isset($_POST['change_prize'])){
        $item = htmlspecialchars(stripslashes($_POST['item_id']));
        $rol = ucwords(htmlspecialchars(stripslashes($_POST['rol'])));

        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/update.php";
        include "../classes/select.php";

        //get item name
        $get_name = new selects();
        $row = $get_name->fetch_details_group('items', 'item_name', 'item_id', $item);
        $item_name = $row->item_name;
        //update quantity
        $change_level = new Update_table();
        $change_level->update('items', 'reorder_level', 'item_id', $rol, $item);
        $change_level->update('inventory', 'reorder_level', 'item', $rol, $item);
        if($change_level){
             echo "<div class='success'><p>$item_name Reorder level adjusted successfully! <i class='fas fa-thumbs-up'></i></p></div>";
        }else{
            echo "<p style='background:red; color:#fff; padding:5px'>Failed to modify reorder level <i class='fas fa-thumbs-down'></i></p>";
        }
    // }