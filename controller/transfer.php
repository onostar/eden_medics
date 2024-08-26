<?php

    $trans_type ="transfer";    
    $posted = htmlspecialchars(stripslashes($_POST['posted_by']));
    $store_from = htmlspecialchars(stripslashes($_POST['store_from']));
    $store_to = htmlspecialchars(stripslashes($_POST['store_to']));
    $item = htmlspecialchars(stripslashes($_POST['item_id']));
    $invoice = htmlspecialchars(stripslashes($_POST['invoice']));
    $quantity = htmlspecialchars(stripslashes($_POST['quantity']));
    // $expiration = htmlspecialchars(stripslashes($_POST['expiration']));
    //instantiate classes
    include "../classes/dbh.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    include "../classes/select.php";
    //get item id
    $get_item_id = new selects();
    $ids = $get_item_id->fetch_details_group('inventory', 'item', 'inventory_id', $item);
    $id = $ids->item;
    //get item details 
    $get_item_det = new selects();
    $itemss = $get_item_det->fetch_details_cond('items', 'item_id', $id);
    foreach($itemss as $items){
        $cost_price = $items->cost_price;
        $price = $items->sales_price;
        $name = $items->item_name;
    }
    // get item previous quantity in inventory;
    $get_prev_qty = new selects();
    $prev_qtys = $get_prev_qty->fetch_details_2cond('inventory', 'inventory_id', 'store', $item, $store_from);
    if(gettype($prev_qtys) === 'array'){
        foreach($prev_qtys as $prev_qty){
            // $inv_qty = $prev_qty->quantity;
            $expiration = $prev_qty->expiration_date;
        }
    }
    //get total previous quantity from all batches
    $get_prev = new selects();
    $sums = $get_prev->fetch_sum_double('inventory', 'quantity', 'store', $store_from, 'item', $id);
    foreach($sums as $sum){
        $inv_qty = $sum->total;
    }
    //get sinigle item in batch quantity
    $get_btc_qty = new selects();
    $btc_qtys = $get_btc_qty->fetch_details_group('inventory', 'quantity', 'inventory_id', $item);
    $btc_qty = $btc_qtys->quantity;
    //check item quantity
    if($quantity > $btc_qty){
        echo "<div class='notify' style='padding:4px!important'><p style='color:#fff!important'><span>$name</span> do not have enough quantity! Cannot proceed</p>";
    }else{
    //insert into audit trail
    //data to insert in audit trail
    $audit_data = array(
        'item' => $item,
        'transaction' => $trans_type,
        'previous_qty' => $inv_qty,
        'quantity' => $quantity,
        'posted_by' => $posted,
        'store' => $store_from
    );
    $inser_trail = new add_data('audit_trail', $audit_data);
    $inser_trail->create_data();
    //check if item is in store inventory
    $check_item = new selects();
    if(gettype($prev_qtys) === 'array'){
        //update current quantity in inventory
        $new_qty = $btc_qty - $quantity;
        $update_inventory = new Update_table();
        $update_inventory->update2Cond('inventory', 'quantity', 'store', 'inventory_id', $new_qty, $store_from, $item);
    }
    
    //transfer item
    //data to transfer
    $transfer_data = array(
        'item' => $id,
        'invoice' => $invoice,
        'sales_price' => $price,
        'cost_price' => $cost_price,
        'quantity' => $quantity,
        'posted_by' => $posted,
        'expiration' => $expiration,
        'from_store' => $store_from,
        'to_store' => $store_to
    );
    $transfer = new add_data('transfers', $transfer_data);
    $transfer->create_data();
    
    if($transfer){
        
?>
    <!-- display transfers for this invoice number -->
<div class="displays allResults" id="stocked_items">
    <h2>Items transfered with invoice <?php echo $invoice?></h2>
    <table id="stock_items_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Item name</td>
                <td>Quantity</td>
                <td>Unit cost</td>
                <td>Unit sales</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_items = new selects();
                $details = $get_items->fetch_details_2cond('transfers', 'from_store', 'invoice', $store_from, $invoice);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
                    $get_ind = new selects();
                    $alls = $get_ind->fetch_details_cond('items', 'item_id', $detail->item);
                    foreach($alls as $all){
                        $sales_price = $all->sales_price;
                        $itemname = $all->item_name;
                    }
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td style="color:var(--moreClor);">
                    <?php
                        echo $itemname;
                    ?>
                </td>
                <td style="text-align:center"><?php echo $detail->quantity?></td>
                <td>
                    <?php 
                        echo "₦".number_format($detail->cost_price, 2);
                    ?>
                </td>
                <td>
                    <?php 
                        echo "₦".number_format($sales_price, 2);
                    ?>
                </td>
                <td>
                    <a style="color:red; font-size:1rem" href="javascript:void(0) "title="delete item" onclick="deleteTransfer('<?php echo $detail->transfer_id?>', <?php echo $detail->item?>)"><i class="fas fa-trash"></i></a>
                </td>
                
            </tr>
            
            <?php $n++; endforeach;}?>
        </tbody>
    </table>

    
    <?php
        if(gettype($details) == "string"){
            echo "<p class='no_result'>'$details'</p>";
        }
        // get sum
        $get_total = new selects();
        $amounts = $get_total->fetch_sum_2con('transfers', 'cost_price', 'quantity', 'from_store', 'invoice', $store_from, $invoice);
        foreach($amounts as $amount){
            $total_amount = $amount->total;
        }
        // $total_worth = $total_amount * $total_qty;
        echo "<p class='total_amount' style='color:red'>Total Cost: ₦".number_format($total_amount, 2)."</p>";
    ?>
    <div class="close_stockin">
        <button onclick="postTransfer('<?php echo $invoice?>')" style="background:green; padding:8px; border-radius:5px;">Post transfer <i class="fas fa-upload"></i></button>
    </div>
</div>
<?php
        }
    
    }
?>