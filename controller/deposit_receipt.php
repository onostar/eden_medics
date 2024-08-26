
<?php
    include "receipt_style.php";
// session_start();
// instantiate class
include "../classes/dbh.php";
include "../classes/select.php";
    session_start();
    if(isset($_GET['receipt'])){
        $user = $_SESSION['user_id'];
        $invoice = $_GET['receipt'];
        $type = "Deposit";        
        //get customer
        $get_customer_id = new selects();
        $custs = $get_customer_id->fetch_details_cond('deposits', 'invoice', $invoice);
        foreach($custs as $cust){
            $customer = $cust->customer;
            $pay_mode = $cust->payment_mode;
            $paid_date = $cust->post_date;
        }
        //get store
        $get_store = new selects();
        $str = $get_store->fetch_details_group('deposits', 'store', 'invoice', $invoice);
        //get store name
        $get_store_name = new selects();
        $strss = $get_store_name->fetch_details_cond('stores', 'store_id', $str->store);
        foreach($strss as $strs){
            $store_name = $strs->store;
            $address = $strs->store_address;
            $phone = $strs->phone_number;

        }
?>
<div class="displays allResults sales_receipt">
    <?php include "receipt_header.php"?>
        <p><strong>(<?php echo strtoupper($pay_mode)?>)</strong></p>
        
    </div>
    <table id="postsales_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <!-- <td>S/N</td> -->
                <td>Item</td>
                <td>Amount</td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                //get payment details
                $get_payment = new selects();
                $payments = $get_payment->fetch_details_cond('deposits', 'invoice', $invoice);
                foreach($payments as $payment){
        
            ?>
            <tr style="font-size:.9rem">
                <!-- <td style="text-align:center; color:red;"><?php echo $n?></td> -->
                <td style="color:var(--moreClor);">
                    <?php
                        echo $payment->details;
                    ?>
                </td>
                <td>
                    <?php 
                        echo number_format($payment->amount);
                    ?>
                </td>
                
                
            </tr>
            
            <?php $n++; };}?>
        </tbody>
    </table>

    
    <?php
        if(gettype($payments) == "string"){
            echo "<p class='no_result'>'$payments'</p>";
        }
        // get sum
        
        
        //sold by
        $get_seller = new selects();
        $row = $get_seller->fetch_details_group('users', 'full_name', 'user_id', $user);
        echo ucwords("<p class='sold_by'>Printed by: <strong>$row->full_name</strong></p>");
    ?>
    <p style="margin-top:20px;text-align:center"><strong>Thanks for your patronage!</strong></p>
    
</div> 
   
<?php
    echo "<script>window.print();
    window.close();</script>";
                    // }
                
            // }
        
    // }
?>