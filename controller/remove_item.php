<?php
    session_start();  
    $trans_type = "remove";  
    $removed_by = $_SESSION['user_id'];
    $store = $_SESSION['store_id'];
    // if(isset($_POST['change_prize'])){
        $item = htmlspecialchars(stripslashes($_POST['item_id']));
        $quantity = htmlspecialchars(stripslashes($_POST['quantity']));
        $reason = ucwords(htmlspecialchars(stripslashes($_POST['remove_reason'])));

        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/update.php";
        include "../classes/select.php";
        include "../classes/inserts.php";
        include "../classes/delete.php";

        //get batch quantity in inventory;
        $get_inv = new selects();
        $inv_qtys = $get_inv->fetch_details_cond('inventory', 'inventory_id', $item);
        foreach($inv_qtys as $inv_qty){
            $prev_qty = $inv_qty->quantity;
            $items = $inv_qty->item;
        }
        //get item details
        $get_name = new selects();
        $rows = $get_name->fetch_details_cond('items', 'item_id', $items);
        foreach($rows as $row){
            $item_name = $row->item_name;
        }
       //get general item quantity;
       $get_qty = new selects();
       $sums = $get_qty->fetch_sum_double('inventory', 'quantity', 'store', $store, 'item', $items);
       foreach($sums as $sum){
           $previous_qty = $sum->total;
       }
        if($quantity > $prev_qty){
            echo "<script>alert('Error! You cannot remove more than available quantity');
            </script>";
        }else{
            //data to insert into remove item table
            $data = array(
                'item' => $items,
                'quantity' => $quantity,
                'reason' => $reason,
                'removed_by' => $removed_by,
                'previous_qty' => $previous_qty,
                'store' => $store
            );
            //data to insert into audit trail
            $data2 = array(
                'item' => $items,
                'transaction' => $trans_type,
                'quantity' => $quantity,
                'posted_by' => $removed_by,
                'previous_qty' => $previous_qty,
                'store' => $store
            );
            //insert into audit trail
            $add_data2 = new add_data('audit_trail', $data2);
            $add_data2->create_data();
            //update quantity in inventory
            $new_qty = $prev_qty - $quantity;
            $change_qty = new Update_table();
            $change_qty->update('inventory', 'quantity', 'inventory_id', $new_qty, $item);
            if($change_qty){
                $add_data = new add_data('remove_items', $data);
                $add_data->create_data();
                if($add_data){
                    //remove item if quantity is 0
                    if($prev_qty == $quantity){
                        $delete = new deletes();
                        $delete->delete_item('inventory', 'inventory_id', $item);
                    }
                    echo "<div class='success'><p>$quantity $item_name removed from inventory successfully! <i class='fas fa-thumbs-up'></i></p></div>";
                }
            }else{
                echo "<p style='background:red; color:#fff; padding:5px'>Failed to remove quantity <i class='fas fa-thumbs-down'></i></p>";
            }
        }