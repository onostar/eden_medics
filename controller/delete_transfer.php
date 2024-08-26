<?php
    
    // if(isset($_GET['id'])){
    //     $id = $_GET['id'];
        $transfer = $_GET['transfer_id'];
        $item = $_GET['item_id'];
        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/delete.php";
        include "../classes/update.php";
// echo $item;
        //get item reorder_level
        $get_level = new selects();
        $levels = $get_level->fetch_details_group('items', 'reorder_level', 'item_id', $item);
        $level = $levels->reorder_level;
        //get item details
        $get_item = new selects();
        $row = $get_item->fetch_details_group('items', 'item_name', 'item_id', $item);
        $name = $row->item_name;
        //get invoice and quantity
        $get_invoice = new selects();
        $rows = $get_invoice->fetch_details_cond('transfers', 'transfer_id', $transfer);
        foreach($rows as $row){
            $invoice = $row->invoice;
            $quantity = $row->quantity;
            $store = $row->from_store;
            $expiration = $row->expiration;
            $cost = $row->cost_price;
        }
        //get previous quantity in inventory
        $get_inv = new selects();
        $invs = $get_inv->fetch_details_2cond('inventory', 'item', 'store', $item, $store);
        if(gettype($invs) == "array"){
            //get total quantity to insert in audit trail
           /*  $get_sum = new selects();
            $sums = $get_sum->fetch_sum_double('inventory', 'quantity', 'item', $item, 'store', $store);
            foreach($sums as $sum){
                $prev_qty = $sum->quantity;
            } */
            //check for batches in th inventory
            $check_batch = new selects();
            $btcs = $check_batch->fetch_details_3cond('inventory', 'item', 'store', 'expiration_date', $item, $store, $expiration);
            // if batch exists update previous quantity
            if(gettype($btcs) == 'array'){
                //update current quantity in inventory for similar batch
                foreach($btcs as $btc){
                    $btc_qty = $btc->quantity;
                }
                $new_qty = $btc_qty + $quantity;
                $update_inventory = new Update_table();
                $update_inventory->update_double3Cond('inventory', 'quantity', $new_qty, 'cost_price', $cost, 'expiration_date', $expiration, 'item', $item, 'store', $store);
            }
            if(gettype($btcs) == 'string'){
                //insert into inventory ifbatch not found
                $inventory_data = array(
                    'item' => $item,
                    'cost_price' => $cost,
                    'expiration_date' => $expiration,
                    'quantity' => $quantity,
                    'reorder_level' => $level,
                    'store' => $store
                );
                $insert_item = new add_data('inventory', $inventory_data);
                $insert_item->create_data();
            }
        }
        
        //if item is not found in inventory anymore
        if(gettype($invs) == "string"){
            $prev_qty = 0;
            //data to insert
            $inventory_data = array(
                'item' => $item,
                'cost_price' => $cost,
                'expiration_date' => $expiration,
                'quantity' => $quantity,
                'reorder_level' => $level,
                'store' => $store
            );
            $insert_item = new add_data('inventory', $inventory_data);
            $insert_item->create_data();
        }

        //delete from transfer
        $delete = new deletes();
        $delete->delete_item('transfers', 'transfer_id', $transfer);
        if($delete){

?>
<!-- display items with same invoice number -->
<div class="notify"><p><span><?php echo $name?></span> Removed from Transfer order</p></div>
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
                $details = $get_items->fetch_details_2cond('transfers', 'from_store', 'invoice', $store, $invoice);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
                    $get_ind = new selects();
                    $alls = $get_ind->fetch_details_cond('items', 'item_id', $detail->item);
                    foreach($alls as $all){
                        $cost_price = $all->cost_price;
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
                    <a style="color:red; font-size:1rem" href="javascript:void(0) "title="delete purchase" onclick="deleteTransfer('<?php echo $detail->transfer_id?>', <?php echo $detail->item?>)"><i class="fas fa-trash"></i></a>
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
        if(gettype($details) === 'array'){
            $get_total = new selects();
            $amounts = $get_total->fetch_sum_2con('transfers', 'cost_price', 'quantity', 'from_store', 'invoice', $store, $invoice);
            foreach($amounts as $amount){
                $total_amount = $amount->total;
            }
            // $total_worth = $total_amount * $total_qty;
            echo "<p class='total_amount' style='color:red'>Total Cost: ₦".number_format($total_amount, 2)."</p>";
        ?>
            <button onclick="postTransfer('<?php echo $invoice?>')" style="background:green; padding:8px; border-radius:5px;">Post transfer <i class="fas fa-upload"></i></button>
        </div>
    <?php }?>
</div> 
<?php
            }            
        
    // }
?>