<?php
// instantiate class
include "../classes/dbh.php";
include "../classes/update.php";
include "../classes/select.php";
include "../classes/inserts.php";
session_start();
    if(isset($_SESSION['user_id'])){
        $trans_type ="sales";
        $user = $_SESSION['user_id'];
            $invoice = $_POST['sales_invoice'];
            $payment_type = htmlspecialchars(stripslashes($_POST['payment_type']));
            $bank = htmlspecialchars(stripslashes($_POST['bank']));
            $cash = htmlspecialchars(stripslashes($_POST['multi_cash']));
            $pos = htmlspecialchars(stripslashes($_POST['multi_pos']));
            $store = htmlspecialchars(stripslashes($_POST['store']));
            $transfer = htmlspecialchars(stripslashes($_POST['multi_transfer']));
            $discount = htmlspecialchars(stripslashes($_POST['discount']));
            $type = "Retail";
            $customer = 0;
        //insert into audit trail
            //get items and quantity sold in the invoice
            $get_item = new selects();
            $items = $get_item->fetch_details_cond('sales', 'invoice', $invoice);
            foreach($items as $item){
                $all_item = $item->item;
                $sold_qty = $item->quantity;
                //get item previous quantity in inventory
                $get_qty = new selects();
                $prev_qtys = $get_qty->fetch_details_2cond('inventory', 'store', 'item', $store, $all_item);
                foreach($prev_qtys as $prev_qty){    
                    //insert into audit trail
                    $inser_trail = new audit_trail($all_item, $trans_type, $prev_qty->quantity, $sold_qty, $user, $store);
                    $inser_trail->audit_trail();
                
                }
            }
            
        //update all items with this invoice
        $update_invoice = new Update_table();
        $update_invoice->update('sales', 'sales_status', 'invoice', 2, $invoice);
            if($update_invoice){
                //insert payment details into payment table
                //get invoice total amount
                $get_inv_total = new selects();
                $results = $get_inv_total->fetch_sum_single('sales', 'total_amount', 'invoice', $invoice);
                foreach($results as $result){
                    $inv_amount = $result->total;
                }
                //get amount paid
                $amount_paid = $inv_amount - $discount;
                if($payment_type == "Multiple"){
                    //insert into payments
                    if($cash !== '0'){
                        $insert_payment = new payments($user, 'Cash', $bank, $inv_amount, $cash, $discount, $invoice, $store, $type, $customer);
                        $insert_payment->payment();
                    }
                    if($pos !== '0'){
                        $insert_payment = new payments($user, 'POS', $bank, $inv_amount, $pos, $discount, $invoice, $store, $type, $customer);
                        $insert_payment->payment();
                    }
                    if($transfer !== '0'){
                        $insert_payment = new payments($user, 'Transfer', $bank, $inv_amount, $transfer, $discount, $invoice, $store, $type, $customer);
                        $insert_payment->payment();
                    }
                    //
                    $insert_multi = new multiple_payment($user, $invoice, $cash, $pos, $transfer, $bank, $store);
                    $insert_multi->multi_pay();
                }else{
                    $insert_payment = new payments($user, $payment_type, $bank, $inv_amount, $amount_paid, $discount, $invoice, $store, $type, $customer);
                    $insert_payment->payment();
                }
                
                if($insert_payment){
                
                //update quantity of the items in inventory
                //get all items first in the invoice
                $get_items = new selects();
                $rows = $get_items->fetch_details_cond('sales', 'invoice', $invoice);
                
                foreach($rows as $row){
                    //update individual quantity in inventory
                    $update_qty = new Update_table();
                    $update_qty->update_inv_qty($row->quantity, $row->item, $store);
                    
                }
?>
<div id="printBtn">
    <button onclick="printSalesOrderReceipt('<?php echo $invoice?>')">Print Receipt <i class="fas fa-print"></i></button>
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