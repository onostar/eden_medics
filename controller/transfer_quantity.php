<?php
    session_start();
    $posted = $_SESSION['user_id'];    
    $store = $_SESSION['store_id'];    
    $trans_type ="transfer between items";
    $item_from = htmlspecialchars(stripslashes($_POST['transfer_qty_from']));
    $item_to = htmlspecialchars(stripslashes($_POST['transfer_qty_to']));
    $remove_qty = htmlspecialchars(stripslashes($_POST['remove_qty']));
    $add_qty = htmlspecialchars(stripslashes($_POST['add_qty']));
    //instantiate classes
    include "../classes/dbh.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    include "../classes/select.php";
    //get item details 
    $get_item_det = new selects();
    $itemss = $get_item_det->fetch_details_cond('items', 'item_id', $item_from);
    foreach($itemss as $items){
        $from_name = $items->item_name;
    }
    $get_item_det_to = new selects();
    $itemsss = $get_item_det_to->fetch_details_cond('items', 'item_id', $item_to);
    foreach($itemsss as $itemss){
        $to_name = $itemss->item_name;
        $to_cost = $itemss->cost_price;
        $reorder = $itemss->reorder_level;
    }
    // get item to transfer from previous quantity in inventory;
    $get_prev_qty = new selects();
    $prev_qtys = $get_prev_qty->fetch_details_2cond('inventory', 'item', 'store', $item_from, $store);
    if(gettype($prev_qtys) === 'array'){
        foreach($prev_qtys as $prev_qty){
            $inv_qty = $prev_qty->quantity;
            $expiration = $prev_qty->expiration_date;
        }
    }
    //check item quantity
    if($remove_qty > $inv_qty){
        echo "<div class='notify' style='padding:4px!important'><p style='color:#fff!important'><span>$from_name</span> do not have enough quantity! Cannot proceed</p>";
    }else{
        
    //update stock balance of item transferred from
    $new_qty = $inv_qty - $remove_qty;
        $update_inventory = new Update_table();
        $update_inventory->update2Cond('inventory', 'quantity', 'store', 'item', $new_qty, $store, $item_from);
    //insert into audit trail
    //data to insert in audit trail
    $audit_data_from = array(
        'item' => $item_from,
        'transaction' => 'transfer to item',
        'previous_qty' => $inv_qty,
        'quantity' => $remove_qty,
        'posted_by' => $posted,
        'store' => $store
    );
    
    $inser_trail = new add_data('audit_trail', $audit_data_from);
    $inser_trail->create_data();
    //update quantity of item transferred to
    //check if item is in store inventory
    $get_prev_qty_to = new selects();
    $prev_to_qtys = $get_prev_qty_to->fetch_details_2cond('inventory', 'item', 'store', $item_to, $store);
    if(gettype($prev_to_qtys) === 'array'){
        foreach($prev_to_qtys as $prev_to_qty){
            $inv_qty_to = $prev_to_qty->quantity;
            $expiration_to = $prev_to_qty->expiration_date;
        }
        //update current quantity in inventory
        $new_qty_to = $inv_qty_to + $add_qty;
        $update_inventory_to = new Update_table();
        $update_inventory_to->update2Cond('inventory', 'quantity', 'store', 'item', $new_qty_to, $store, $item_to);
    }
    //insert into inventory if not found;
    if(gettype($prev_to_qtys) === 'string'){
        $inv_qty_to = 0;
        //get item details
        $insert_new = array(
            "item" => $item_to,
            "store" => $store,
            "cost_price" => $to_cost,
            "quantity" => $add_qty,
            "reorder_level" => $reorder,
            "expiration_date" => $expiration
        );

        // $insert to inventory
        $add_inv = new add_data('inventory', $insert_new);
        $add_inv->create_data();
        

    }
    //data to insert in audit trail for second item
    $audit_data_to = array(
        'item' => $item_to,
        'transaction' => 'transfer from item',
        'previous_qty' => $inv_qty_to,
        'quantity' => $add_qty,
        'posted_by' => $posted,
        'store' => $store
    );
    $inser_trail_to = new add_data('audit_trail', $audit_data_to);
    $inser_trail_to->create_data();
    //inseet into items transfer table
    $data = array(
        'item_from' => $item_from,
        'item_to' => $item_to,
        'removed_qty' => $remove_qty,
        'added_qty' => $add_qty,
        'posted_by' => $posted,
        'store' => $store
    );
    $complete_transfer = new add_data('item_transfers', $data);
    $complete_transfer->create_data();
    if($complete_transfer){
        
?>
    
    <div class="notify"><p><i class="fas fa-thumbs-up"></i> Items quantity transferred successfully!</p></div>
</div>
<?php
        }
    
    }
?>