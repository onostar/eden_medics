<?php
// session_start();
// instantiate class
include "../classes/dbh.php";
include "../classes/update.php";
include "../classes/select.php";
include "../classes/inserts.php";
    session_start();
    if(isset($_SESSION['user_id'])){
        $trans_type = "sales";
            $user = $_SESSION['user_id'];
            $invoice = $_POST['sales_invoice'];
            $payment_type = htmlspecialchars(stripslashes($_POST['payment_type']));
            $bank = htmlspecialchars(stripslashes($_POST['bank']));
            $cash = htmlspecialchars(stripslashes($_POST['multi_cash']));
            $pos = htmlspecialchars(stripslashes($_POST['multi_pos']));
            $transfer = htmlspecialchars(stripslashes($_POST['multi_transfer']));
            $discount = htmlspecialchars(stripslashes($_POST['discount']));
            $store = htmlspecialchars(stripslashes($_POST['store']));
            $type = "Wholesale";
            $wallet = htmlspecialchars(stripslashes($_POST['wallet']));
            $deposit = htmlspecialchars(stripslashes($_POST['deposit']));
            $customer = htmlspecialchars(stripslashes($_POST['customer_id']));
            $date = date("Y-m-d H:i:s");

            //insert into audit trail
            //get items and quantity sold in the invoice
            $get_item = new selects();
            $items = $get_item->fetch_details_cond('sales', 'invoice', $invoice);
            foreach($items as $item){
                $all_item = $item->item;
                $sold_qty = $item->quantity;
                //get item previous quantity in inventory
                $get_qty = new selects();
                $prev_qtys = $get_qty->fetch_sum_double('inventory', 'quantity', 'store', $store, 'item', $all_item);
                foreach($prev_qtys as $prev_qty){    
                    //insert into audit trail
                    $inser_trail = new audit_trail($all_item, $trans_type, $prev_qty->total, $sold_qty, $user, $store);
                    $inser_trail->audit_trail();
                
                }
            }
            
        //check if mode is multiple payment
        /* $get_mode = new selects();
        $mode = $get_mode->fetch_details_group('payments', 'payment_mode', 'invoice', $invoice);
        $paymode = $mode->payment_mode; */

        //update all items with this invoice
        $update_invoice = new Update_table();
        $update_invoice->update('sales', 'sales_status', 'invoice', 2, $invoice);
        //update quantity of the items in inventory
        //get all items first in the invoice
        $get_items = new selects();
        $rows = $get_items->fetch_details_cond('sales', 'invoice', $invoice);
        
        foreach($rows as $row){
            //update individual quantity in inventory
            
            $update_qty = new Update_table();
            $update_qty->update_inv_qty2($row->quantity, $row->item, $store);
            
        }
            if($update_invoice){
                //insert payment details into payment table
                //get invoice total amount
                $get_inv_total = new selects();
                $results = $get_inv_total->fetch_sum_single('sales', 'total_amount', 'invoice', $invoice);
                foreach($results as $result){
                    $inv_amount = $result->total;
                }
                //get amount paid
                if($payment_type == "Credit"){
                    $amount_paid = 0;
                }elseif($payment_type == "Deposit"){
                    $amount_paid = $deposit;

                }else{
                    $amount_paid = $inv_amount - $discount;
                }
                //insert payments
                if($payment_type == "Multiple"){
                    //insert into payments
                    if($cash !== '0'){
                        $insert_payment = new payments($user, 'Cash', $bank, $inv_amount, $cash, $discount, $invoice, $store, $type, $customer, $date);
                        $insert_payment->payment();
                    }
                    if($pos !== '0'){
                        $insert_payment = new payments($user, 'POS', $bank, $inv_amount, $pos, $discount, $invoice, $store, $type, $customer, $date);
                        $insert_payment->payment();
                    }
                    if($transfer !== '0'){
                        $insert_payment = new payments($user, 'Transfer', $bank, $inv_amount, $transfer, $discount, $invoice, $store, $type, $customer, $date);
                        $insert_payment->payment();
                    }
                    //
                    $insert_multi = new multiple_payment($user, $invoice, $cash, $pos, $transfer, $bank, $store, $date);
                    $insert_multi->multi_pay();
                }else{
                    $insert_payment = new payments($user, $payment_type, $bank, $inv_amount, $amount_paid, $discount, $invoice, $store, $type, $customer, $date);
                    $insert_payment->payment();
                }
                if($payment_type == "Wallet"){
                    //update wallet balance
                    $new_balance = $wallet - $amount_paid;
                    $update_wallet = new Update_table();
                    $update_wallet->update('customers', 'wallet_balance', 'customer_id', $new_balance, $customer);
                }
                if($insert_payment){
                
                
                //check if payment is credit and insert into customer trail and debtors list
                if($payment_type == "Credit"){
                    //insert to customer_trail
                    $insert_credit = new customer_trail($customer, $store, 'Credit sales', $inv_amount, $user);
                    $insert_credit->add_trail();
                    //insert to debtors list
                    $debt_data = array(
                        'customer' => $customer,
                        'invoice' => $invoice,
                        'amount' => $inv_amount,
                        'posted_by' => $user,
                        'store' => $store
                    );
                    $add_debt = new add_data('debtors', $debt_data);
                    $add_debt->create_data();
                }
                if($payment_type == "Deposit"){
                    //insert to customer_trail
                    $balance_payment = $inv_amount - $deposit;
                    $insert_credit = new customer_trail($customer, $store, 'Credit sales', $balance_payment, $user);
                    $insert_credit->add_trail();
                    //insert to debtors list
                    $debt_data = array(
                        'customer' => $customer,
                        'invoice' => $invoice,
                        'amount' => $balance_payment,
                        'posted_by' => $user,
                        'store' => $store
                    );
                    $add_debt = new add_data('debtors', $debt_data);
                    $add_debt->create_data();
                }
                
?>
<div id="printBtn">
    <button onclick="printSalesReceipt('<?php echo $invoice?>')">Print Receipt <i class="fas fa-print"></i></button>
</div>
<!--  -->
   
<?php
    // echo "<script>window.print();</script>";
                    // }
                }
            }
        
    }else{
        header("Location: ../index.php");
    } 
?>