<?php
    session_start();
    if(isset($_SESSION['user_id'])){
        $accepted = $_SESSION['user_id'];
        $store = $_SESSION['store_id'];
        if(isset($_GET['transfer_id'])){
            $id = $_GET['transfer_id'];
        }
        $trans_type = "accept";
    
    //instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    
    //get items
    $get_item = new selects();
    $the_items = $get_item->fetch_details_cond('transfers', 'transfer_id', $id);
    foreach($the_items as $the_item){
        $item = $the_item->item;
        $quantity = $the_item->quantity;
        $expiration = $the_item->expiration;
        $status = $the_item->transfer_status;
    }
    //get item details 
    $get_item_det = new selects();
    $itemss = $get_item_det->fetch_details_cond('items', 'item_id', $item);
    foreach($itemss as $items){
        $cost_price = $items->cost_price;
        // $sales_price = $items->sales_price;
        $name = $items->item_name;
        $reorder_level = $items->reorder_level;
    }
    //check if item exists in inventory
    $check_inv = new selects();
    $invs = $check_inv->fetch_details_2cond('inventory', 'item', 'store', $item, $store);
    if(gettype($invs) == 'array'){
        // get item previous quantity in inventory;
        $get_prev_qty = new selects();
        $prev_qtys = $get_prev_qty->fetch_sum_double('inventory', 'quantity', 'store', $store, 'item', $item);
        if(gettype($prev_qtys) === 'array'){
            foreach($prev_qtys as $prev_qty){
                $inv_qty = $prev_qty->total;
            }
            $check_batch = new selects();
            $btcs = $check_batch->fetch_details_3cond('inventory', 'item', 'store', 'expiration_date', $item, $store, $expiration);
        }
    }
    
    if(gettype($invs) === 'string'){
        $inv_qty = 0;
    }
    // echo $inv_qty
    //insert into audit trail
    $audit_data = array(
        'item' => $item,
        'transaction' => $trans_type,
        'previous_qty' => $inv_qty,
        'quantity' => $quantity,
        'posted_by' => $accepted,
        'store' => $store
    );
    $inser_trail = new add_data('audit_trail', $audit_data);
    $inser_trail->create_data();
    //check if item is in store inventory
    if(gettype($invs) === 'array'){
        if(gettype($btcs) == 'array'){
            //update current quantity in inventory for similar batch
            foreach($btcs as $btc){
                $btc_qty = $btc->quantity;
            }
            $new_qty = $btc_qty + $quantity;
            $update_inventory = new Update_table();
            $update_inventory->update_double3Cond('inventory', 'quantity', $new_qty, 'cost_price', $cost_price, 'expiration_date', $expiration, 'item', $item, 'store', $store);
        }
        if(gettype($btcs) == 'string'){
            //insert into inventory ifbatch not found
            $inventory_data = array(
                'item' => $item,
                'cost_price' => $cost_price,
                'expiration_date' => $expiration,
                'quantity' => $quantity,
                'reorder_level' => $reorder_level,
                'store' => $store
            );
            $insert_item = new add_data('inventory', $inventory_data);
            $insert_item->create_data();
        }
    }
    //add to inventory if not found
    if(gettype($invs) == "string"){
        //data to insert
        $inventory_data = array(
            'item' => $item,
            'cost_price' => $cost_price,
            'expiration_date' => $expiration,
            'quantity' => $quantity,
            'reorder_level' => $reorder_level,
            'store' => $store
        );
        $insert_item = new add_data('inventory', $inventory_data);
        $insert_item->create_data();
    }
    //update transfer item
    if($status == -1){
        $update_transfer = new Update_table();
        $update_transfer->update_double('transfers', 'transfer_status', -2, 'accept_by', $accepted, 'transfer_id', $id);
    }else{
        $update_transfer = new Update_table();
        $update_transfer->update_double('transfers', 'transfer_status', 2, 'accept_by', $accepted, 'transfer_id', $id);
    }
    if($update_transfer){
        echo "<div class='notify' style='padding:4px!important'><p style='color:#fff!important'><span>$quantity $name</span> accepted into inventory</p>";
    }
?>
    <!-- display transfers for this invoice number -->
    
<?php
        }
  
?>