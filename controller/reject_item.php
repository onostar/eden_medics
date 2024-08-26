<?php
    session_start();
    if(isset($_SESSION['user_id'])){
        $accepted = $_SESSION['user_id'];
        $store = $_SESSION['store_id'];
        if(isset($_GET['transfer_id'])){
            $id = $_GET['transfer_id'];
        }
    
    //instantiate classes
    include "../classes/dbh.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    include "../classes/select.php";

    //get items
    $get_item = new selects();
    $the_items = $get_item->fetch_details_cond('transfers', 'transfer_id', $id);
    foreach($the_items as $the_item){
        $item = $the_item->item;
        $quantity = $the_item->quantity;
        $expiration = $the_item->expiration;
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
    
    //update transfer item
    $update_transfer = new Update_table();
    $update_transfer->update_double('transfers', 'transfer_status', -1, 'accept_by', $accepted, 'transfer_id', $id);
        
    if($update_transfer){
        echo "<div class='notify' style='padding:4px!important'><p style='color:#fff!important'><span>$quantity $name</span> Rejected</p>";
    }
?>
    <!-- display transfers for this invoice number -->
    
<?php
        }
  
?>