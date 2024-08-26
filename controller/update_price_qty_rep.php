<?php
        session_start();
        $store = $_SESSION['store_id'];
        $sales = htmlspecialchars(stripslashes($_POST['sales_id']));
        $qty = htmlspecialchars(stripslashes($_POST['qty']));
        $markup = htmlspecialchars(stripslashes($_POST['markup']));
    

        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/update.php";
        
        //get item details
        $get_invoice = new selects();
        $rows = $get_invoice->fetch_details_cond('sales', 'sales_id', $sales);
        foreach($rows as $row){
            $invoice = $row->invoice;
            // $cost = $row->cost;
            $item = $row->item;
        }
         //get cost price
         $get_cost = new selects();
         $costs = $get_cost->fetch_details_group('items', 'wholesale_cost', 'item_id', $item);
         $cost_price = $costs->wholesale_cost;

        $price = ($cost_price * ($markup/100)) + $cost_price;
        $new_amount = $qty * $price;

        //get item quantity from inventory
        //first get the item from sales order
        /* $get_item = new selects();
        $item = $get_item->fetch_details_group('sales', 'item', 'sales_id', $sales);
        $item_id = $item->item; */
        //get item old markup
        $get_price = new selects();
        $unit_price = $get_price->fetch_details_group('items', 'markup', 'item_id', $item);
        $old_price = $cost_price + ($cost_price * ($unit_price->markup/100));
        $discount = $old_price - $price;
        $get_qty = new selects();
        $item_qtys = $get_qty->fetch_sum_double('inventory', 'quantity', 'store', $store, 'item', $item);
        foreach($item_qtys as $item_qty){
            $inv_qty = $item_qty->total;
        }
        if($qty > $inv_qty){
            echo "<script>alert('Available Quantity is less than required! Can not proceed!')</script>";
        }else{
       
        $total_cost = $qty * $cost_price;
        //update quantity and price
        $update = new Update_table();
        $update->update_six('sales', 'quantity', $qty, 'price', $price, 'total_amount', $new_amount, 'discount', $discount, 'markup', $markup, 'cost',$total_cost, 'sales_id', $sales);
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