<?php
    session_start(); 
    $store = $_SESSION['store_id'];   
    // $removed_by = $_SESSION['user_id'];
    // if(isset($_POST['change_prize'])){
        $item = htmlspecialchars(stripslashes($_POST['item_id']));
        $exp = htmlspecialchars(stripslashes($_POST['exp_date']));

        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/update.php";
        include "../classes/select.php";
        //get item from inventoy first
        $get_item = new selects();
        $details = $get_item->fetch_details_group('inventory', 'item', 'inventory_id', $item);
        $items = $details->item;
        //get item details
        $get_name = new selects();
        $rows = $get_name->fetch_details_cond('items', 'item_id', $items);
        foreach($rows as $row){
            $item_name = $row->item_name;
            // $prev_qty = $row->quantity;
        }
        //update expiration
        $update_exp = new Update_table();
        $update_exp->update('inventory', 'expiration_date', 'inventory_id', $exp, $item);
        if($update_exp){
           
             echo "<div class='success'><p> $item_name expiration date updated successfully! <i class='fas fa-thumbs-up'></i></p></div>";
        }else{
            echo "<p style='background:red; color:#fff; padding:5px'>Failed to update expiration <i class='fas fa-thumbs-down'></i></p>";
        }