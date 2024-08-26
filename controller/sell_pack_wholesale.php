<?php   
        session_start();
        $store = $_SESSION['store_id'];
        $sales = htmlspecialchars(stripslashes($_POST['sales_id']));
        $qty = htmlspecialchars(stripslashes($_POST['pack_qty']));
        $pack_price = htmlspecialchars(stripslashes($_POST['pack_price']));
        $pack_size = htmlspecialchars(stripslashes($_POST['pack_size']));
        $total_qty = $qty * $pack_size;
        $new_amount = $qty * $pack_size * $pack_price;

        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/update.php";
        //get invoice
        $get_invoice = new selects();
        $rows = $get_invoice->fetch_details_group('sales', 'invoice', 'sales_id', $sales);
        $invoice = $rows->invoice;
        //get item quantity from inventory
        //first get the item from sales order
        $get_item = new selects();
        $item = $get_item->fetch_details_group('sales', 'item', 'sales_id', $sales);
        $item_id = $item->item;
        $get_qty = new selects();
        $item_qtys = $get_qty->fetch_sum_double('inventory', 'quantity', 'store', $store, 'item', $item_id);
        foreach($item_qtys as $item_qty){
            $inv_qty = $item_qty->total;
        }
        if($total_qty > $inv_qty){
            echo "<script>alert('Available Quantity is less than required! Can not proceed!')</script>";
        }else{
        //get cost price
        $get_cost = new selects();
        $costs = $get_cost->fetch_details_group('items', 'cost_price', 'item_id', $item_id);
        $cost_price = $costs->cost_price;
        $total_cost = $total_qty * $cost_price;
        //update quantity and price
        $update = new Update_table();
        $update->update_tripple('sales', 'quantity', $total_qty, 'price', $pack_price, 'total_amount', $new_amount, 'sales_id', $sales);
        $update_cost = new Update_table();
        $update_cost->update('sales', 'cost', 'sales_id', $total_cost, $sales);
        // if($update){
        }
?>
<!-- display items with same invoice number -->

<?php
    include "wholesale_details.php";
            // }            
        // }
    // }
?>