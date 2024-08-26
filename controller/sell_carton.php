<?php   
        session_start();
        $store = $_SESSION['store_id'];
        $sales = htmlspecialchars(stripslashes($_POST['sales_id']));
        $qty = htmlspecialchars(stripslashes($_POST['pack_qty']));
        $markup = htmlspecialchars(stripslashes($_POST['pack_price']));
        $pack_size = htmlspecialchars(stripslashes($_POST['pack_size']));
        

        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/update.php";
        //get invoice
        $get_invoice = new selects();
        $rows = $get_invoice->fetch_details_cond('sales', 'sales_id', $sales);
        foreach($rows as $row){
            $invoice = $row->invoice;
            $item = $row->item;
        }
        // get item
        //get cost price
        $get_cost = new selects();
        $costs = $get_cost->fetch_details_group('items', 'wholesale_cost', 'item_id', $item);
        $cost_price = $costs->wholesale_cost;

        $total_qty = $qty * $pack_size;
        $price = $cost_price + ($cost_price * ($markup/100));
        $new_amount = $total_qty * $price;
        //get item quantity from inventory
        // $item_id = $item->item;
        $get_qty = new selects();
        $item_qtys = $get_qty->fetch_sum_double('inventory', 'quantity', 'store', $store, 'item', $item);
        foreach($item_qtys as $item_qty){
            $inv_qty = $item_qty->total;
        }
        if($total_qty > $inv_qty){
            echo "<script>alert('Available Quantity is less than required! Can not proceed!')</script>";
        }else{
        
        $total_cost = $total_qty * $cost_price;
        //update quantity and price
        $update = new Update_table();
        $update->update_multiple('sales', 'quantity', $total_qty, 'price', $price, 'total_amount', $new_amount, 'markup', $markup, 'cost', $total_cost, 'sales_id', $sales);
        /* $update_cost = new Update_table();
        $update_cost->update('sales', 'cost', 'sales_id', $total_cost, $sales); */
        // if($update){
        }
?>
<!-- display items with same invoice number -->

<?php
    include "rep_details.php";
            // }            
        // }
    // }
?>