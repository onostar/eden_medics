<?php
date_default_timezone_set("Africa/Lagos");
    
    // if(isset($_POST['change_prize'])){
        $trans_type = "sales_return";
        $item = htmlspecialchars(stripslashes($_POST['item']));
        $user = htmlspecialchars(stripslashes($_POST['user_id']));
        $sales = htmlspecialchars(stripslashes($_POST['sales_id']));
        $quantity = htmlspecialchars(stripslashes($_POST['quantity']));
        $store = htmlspecialchars(stripslashes($_POST['store']));
        $expiration = htmlspecialchars(stripslashes($_POST['expiration']));
        $reason = ucwords(htmlspecialchars(stripslashes($_POST['reason'])));
        $date = date("Y-m-d H:i:s");
        
        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/update.php";
        include "../classes/inserts.php";
        include "../classes/delete.php";
        
        
        //get item details
        $get_name = new selects();
        $rows = $get_name->fetch_details_cond('items', 'item_id', $item);
        foreach($rows as $row){
            $item_name = $row->item_name;
            $cost = $row->cost_price;
            $reorder = $row->reorder_level;
        }
        //get sales details
        $get_sales = new selects();
        $rows = $get_sales->fetch_details_cond('sales', 'sales_id', $sales);
        foreach($rows as $row){
            $sales_qty = $row->quantity;
            $invoice = $row->invoice;
            $unit_price = $row->price;
            $amount = $row->total_amount;
        }
        $new_qty = $sales_qty - $quantity;
        $new_amount = $new_qty * $unit_price;
        $removed_amount = $quantity * $unit_price;
        $new_cost = $new_qty * $cost;
        
        //update sales quantity and amount

        $update_sales = new Update_table();
        $update_sales->update_tripple('sales', 'quantity', $new_qty, 'total_amount', $new_amount, 'cost', $new_cost, 'sales_id', $sales);

        //get item current quantity in inventory;
        $get_qty = new selects();
        $qtys = $get_qty->fetch_details_2cond('inventory', 'store', 'item', $store, $item);
        if(gettype($qtys) == 'array'){
            $sums = $get_qty->fetch_sum_double('inventory', 'quantity', 'store', $store, 'item', $item);
            foreach($sums as $sum){
                $inv_qty = $sum->total;
            }
        }
        if(gettype($qtys) == 'string'){
            $inv_qty = 0;
        }

        //update item quantity in inventory
        $get_qty = new selects();
        $qtys = $get_qty->fetch_details_3cond('inventory', 'store', 'item', 'expiration_date', $store, $item, $expiration);
        if(gettype($qtys) == 'array'){
            foreach($qtys as $qty){
                $cur_qty = $qty->quantity;
            }
            $new_inv_qty = $cur_qty + $quantity;
            $update_inventory = new Update_table();
            $update_inventory->update3cond('inventory', 'quantity', 'store', 'item', 'expiration_date', $new_inv_qty, $store, $item, $expiration);
        }
        //insert into inventory if batch is not found
        if(gettype($qtys) == 'string'){
            $insert_data = array(
                "item" => $item,
                "store" => $store,
                "cost_price" => $cost,
                "quantity" => $quantity,
                "batch_number" => 0,
                "expiration_date" => $expiration,
                "reorder_level" => $reorder
            );
            $add_inventory = new add_data('inventory', $insert_data);
            $add_inventory->create_data();
        }
        //insert into audit trail
        $inser_trail = new audit_trail($item, $trans_type, $inv_qty, $quantity, $user, $store);
        $inser_trail->audit_trail();
        //update invoice amount in payment table
        //get total invoice amount from payment table
        $get_amount = new selects();
        $amounts = $get_amount->fetch_details_cond('payments', 'invoice', $invoice);
        foreach($amounts as $amount){
            $invoice_amount = $amount->amount_paid;
            $invoice_due = $amount->amount_due;
            $payment_type = $amount->payment_mode;
            $customer = $amount->customer;
        }
       
        $new_inv_amount = $invoice_amount - $removed_amount;
        $new_inv_due = $invoice_due - $removed_amount;
        $update_payment = new Update_table();
        $update_payment->update_double('payments', 'amount_paid', $new_inv_amount, 'amount_due', $new_inv_due, 'invoice', $invoice);
        $data = array(
            'item' => $item,
            'quantity' => $quantity,
            'amount' => $removed_amount,
            'reason' => $reason,
            'returned_by' => $user,
            'invoice' => $invoice,
            'store' => $store,
            'return_date' => $date,
        );
        // if($update_payment){
            //insert into sales return table
            $sales_return = new add_data('sales_returns', $data);
            $sales_return->create_data();
            if($sales_return){
                //check if payment mode is wallet and add money back to wallet balance
                if($payment_type == "Wallet"){
                    //get wallet balance
                    $get_balance = new selects();
                    $balance = $get_balance->fetch_details_group('customers', 'wallet_balance', 'customer_id', $customer);
                    $wallet = $balance->wallet_balance;

                    //add returned money to wallet balance
                    $new_balance = $removed_amount + $wallet;
                    //update wallet balance
                    $update_wallet = new Update_table();
                    $update_wallet->update('customers', 'wallet_balance', 'customer_id', $new_balance, $customer);
                }
            //remove invoice from payments and sales if amount is = 0
            //get new payment amount for the invoice
            $get_new_amount = new selects();
            $new_pay_amount = $get_new_amount->fetch_details_group('payments', 'amount_paid', 'invoice', $invoice);
                if($new_pay_amount->amount_paid == 0){
                    //delete from sales
                    $delete_sales = new deletes();
                    $delete_sales->delete_item('sales', 'invoice', $invoice);
                    //delete from payments
                    $delete_payment = new deletes();
                    $delete_payment->delete_item('payments', 'invoice', $invoice);

                    
                }
                echo "<div class='success'><p>$item_name sales returned successfully! <i class='fas fa-thumbs-up'></i></p></div>";
            }else{
                echo "<p style='background:red; color:#fff; padding:5px'>Failed to insert sales return <i class='fas fa-thumbs-down'></i></p>";
            }
        /* }else{
            echo "<p style='background:red; color:#fff; padding:5px'>Failed to update  payment <i class='fas fa-thumbs-down'></i></p>";

        } */
    // }