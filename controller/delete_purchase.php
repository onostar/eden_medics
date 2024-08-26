<?php
    session_start();
    $store = $_SESSION['store_id'];
    // if(isset($_GET['id'])){
    //     $id = $_GET['id'];
        $purchase = $_GET['purchase_id'];
        $item = $_GET['item_id'];
        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/update.php";
        include "../classes/delete.php";
// echo $item;
        //get item details
        $get_qty = new selects();
        $rows = $get_qty->fetch_details_cond('purchases', 'purchase_id', $purchase);
        foreach($rows as $row){
            $qty = $row->quantity;
            $invoice = $row->invoice;
            $supplier = $row->vendor;
            $expiration = $row->expiration_date;
        }
        // get exact item from inventory and update
        $get_inv = new selects();
        $invs = $get_inv->fetch_details_3cond('inventory', 'store', 'item', 'expiration_date', $store, $item, $expiration);
        foreach($invs as $inv){
            $inventory_id = $inv->inventory_id;
        }

        //update quantity on items table
        $update_qty = new Update_table();
        $update_qty->subtract_quantity($qty, $inventory_id, $store);
        if($update_qty){
            //delete purcahse
            $delete = new deletes();
            $delete->delete_item('purchases', 'purchase_id', $purchase);
            if($delete){
?>
<!-- display stockins for items with same invoice number -->
<div class="displays allResults" id="stocked_items">
    <h2>Items stocked in with invoice <?php echo $invoice?></h2>
    <table id="stock_items_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Item name</td>
                <td>Quantity</td>
                <td>Unit cost</td>
                <td>Unit sales</td>
                <td>Expiration</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_items = new selects();
                $details = $get_items->fetch_details_2cond('purchases', 'vendor', 'invoice', $supplier, $invoice);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td style="color:var(--moreClor);">
                    <?php
                        //get category name
                        $get_item_name = new selects();
                        $item_name = $get_item_name->fetch_details_group('items', 'item_name', 'item_id', $detail->item);
                        echo $item_name->item_name;
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
                        echo "₦".number_format($detail->sales_price, 2);
                    ?>
                </td>
                <td><?php echo $detail->expiration_date?></td>
                <td>
                    <a style="color:red; font-size:1rem" href="javascript:void(0) "title="delete purchase" onclick="deletePurchase('<?php echo $detail->purchase_id?>', <?php echo $detail->item?>)"><i class="fas fa-trash"></i></a>
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
        $amounts = $get_total->fetch_sum_2con('purchases', 'cost_price', 'quantity', 'vendor', 'invoice', $supplier, $invoice);
        foreach($amounts as $amount){
            $total_amount = $amount->total;
        }
        // $total_worth = $total_amount * $total_qty;
        echo "<p class='total_amount' style='color:red'>Total Cost: ₦".number_format($total_amount, 2)."</p>";
    ?>
    <div class="close_stockin">
        <button onclick="closeStockin()" style="background:red; padding:8px; border-radius:5px;">Close stockin <i class="fas fa-power-off"></i></button>
    </div>
</div>
<?php
            }            
        }
    // }
?>