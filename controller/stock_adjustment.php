<?php
    session_start();
    $trans_type = "adjust";  
    $adjusted_by = $_SESSION['user_id'];
    $store = $_SESSION['store_id'];
    // if(isset($_POST['change_prize'])){
        $item = htmlspecialchars(stripslashes($_POST['item_id']));
        $quantity = htmlspecialchars(stripslashes($_POST['quantity']));
        $expiration = htmlspecialchars(stripslashes($_POST['expiration']));

        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/update.php";
        include "../classes/select.php";
        include "../classes/inserts.php";
        
        //get batch quantity in inventory;
        $get_inv = new selects();
        $inv_qtys = $get_inv->fetch_details_cond('inventory', 'inventory_id', $item);
        foreach($inv_qtys as $inv_qty){
            $prev_qty = $inv_qty->quantity;
            $items = $inv_qty->item;
        }
        //get general item quantity;
        $get_qty = new selects();
        $sums = $get_qty->fetch_sum_double('inventory', 'quantity', 'store', $store, 'item', $items);
        foreach($sums as $sum){
            $previous_qty = $sum->total;
        }
        
        //get item details
        $get_name = new selects();
        $rows = $get_name->fetch_details_cond('items', 'item_id', $items);
        foreach($rows as $row){
            $item_name = $row->item_name;
        }
        //update quantity in inventory
        $change_qty = new Update_table();
        $change_qty->update_double('inventory', 'quantity', $quantity, 'expiration_date', $expiration, 'inventory_id', $item);
        if($change_qty){
            //get new total quantity
            $new_qty = new selects();
            $new_sums = $new_qty->fetch_sum_double('inventory', 'quantity', 'store', $store, 'item', $items);
            foreach($new_sums as $new_sum){
                $new_quantity = $new_sum->total;
            }   
            //data to insert in stock adjustment
            $data = array(
                'item' => $items,
                'adjusted_by' => $adjusted_by,
                'previous_qty' => $previous_qty,
                'new_qty' => $new_quantity,
                'store' => $store
            );
            //data to insert in audit trail
            $data2 = array(
                'transaction' => $trans_type,
                'item' => $items,
                'posted_by' => $adjusted_by,
                'previous_qty' => $previous_qty,
                'quantity' => $new_quantity,
                'store' => $store
            );
            //insert into audit trail
            $add_data2 = new add_data('audit_trail', $data2);
            $add_data2->create_data();
            //insert into stock adjustment table
            $add_data = new add_data('stock_adjustments', $data);
            $add_data->create_data();
            if($add_data){
                echo "<div class='success'><p>$item_name quantity adjusted successfully! <i class='fas fa-thumbs-up'></i></p></div>";
            }
        }
    // }