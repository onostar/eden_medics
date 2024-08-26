<?php
    session_start();
        $sales = $_GET['sales_id'];
        $item = $_GET['item_id'];
        $store = $_SESSION['store_id'];
        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/update.php";
        // check item current quantity in inventory
        $check_qty = new selects();
        $qtys = $check_qty->fetch_sum_double('inventory', 'quantity', 'store', $store,'item', $item);
        foreach($qtys as $qty){
            $current_qty = $qty->total;

        }
        // check item current quantity in sales order
        $check_salesqty = new selects();
        $qtys = $check_salesqty->fetch_details_group('sales', 'quantity', 'sales_id', $sales);
        $sales_qty = $qtys->quantity;
        //get invoice
        $get_invoice = new selects();
        $rows = $get_invoice->fetch_details_group('sales', 'invoice', 'sales_id', $sales);
        $invoice = $rows->invoice;
        // echo $sales_qty;
        if($sales_qty == $current_qty){
            echo "<script>alert('Available quantity is less than required!');
            </script>";
?>
<!-- display items with same invoice number -->

<?php 
    include "sales_details.php";           
        }else{
        //update quantity
        $update = new Update_table();
        $update->increase_qty(1, $sales);
        if($update){
        //update total amount
        // check item new quantity in sales order
        $check_itemqty = new selects();
        $shows = $check_itemqty->fetch_details_cond('sales', 'sales_id', $sales);
        foreach($shows as $show){
            $new_qty = $show->quantity;
            $unit_price = $show->price;
        }
        //get cost price from inventory
        $get_cost = new selects();
        $costs = $get_cost->fetch_details_group('items', 'cost_price', 'item_id', $item);
        $cost_price = $costs->cost_price;
        $total_price = $new_qty * $unit_price;
        $total_cost = $new_qty * $cost_price;
        $update_total = new Update_table();
        $update_total->update_double('sales', 'total_amount', $total_price, 'cost', $total_cost, 'sales_id', $sales);
        if($update_total){
?>
<!-- display items with same invoice number -->

<?php
    include "sales_details.php";           
            }            
        }
    }
?>