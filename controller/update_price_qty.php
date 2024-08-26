<?php
        session_start();
        $store = $_SESSION['store_id'];
        $sales = htmlspecialchars(stripslashes($_POST['sales_id']));
        $qty = htmlspecialchars(stripslashes($_POST['qty']));
        $price = htmlspecialchars(stripslashes($_POST['price']));
        $new_amount = $qty * $price;

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
        //get item price
        $get_price = new selects();
        $unit_price = $get_price->fetch_details_group('items', 'sales_price', 'item_id', $item_id);
        $old_price = $unit_price->sales_price;
        $discount = $old_price - $price;
        $get_qty = new selects();
        $item_qtys = $get_qty->fetch_sum_double('inventory', 'quantity', 'store', $store, 'item', $item_id);
        foreach($item_qtys as $item_qty){
            $inv_qty = $item_qty->total;
        }
        if($qty > $inv_qty){
            echo "<script>alert('Available Quantity is less than required! Can not proceed!')</script>";
        }else{
        //get cost price
        $get_cost = new selects();
        $costs = $get_cost->fetch_details_group('items', 'cost_price', 'item_id', $item_id);
        $cost_price = $costs->cost_price;
        $total_cost = $qty * $cost_price;
        //update quantity and price
        $update = new Update_table();
        $update->update_quadruple('sales', 'quantity', $qty, 'price', $price, 'total_amount', $new_amount, 'discount', $discount, 'sales_id', $sales);
        $update_cost = new Update_table();
        $update_cost->update('sales', 'cost', 'sales_id', $total_cost, $sales);
        // if($update){
        }
?>
<!-- display items with same invoice number -->

<?php
    include "sales_details.php";
            // }            
        // }
    // }
?>