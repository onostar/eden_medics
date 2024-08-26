<div id="fund_account">
<?php
    session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/update.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        $store = $_SESSION['store_id'];
        // echo $user_id;
    
    if(isset($_GET['customer'])){
        $customer_id = $_GET['customer'];
        //get customer details;
        $get_details = new selects();
        $rows = $get_details->fetch_details_cond('customers', 'customer_id', $customer_id);
        foreach($rows as $row){
            $customer = $row->customer;
            $balance = $row->wallet_balance;

        }
        //generate deposit receipt
        //get current date
        $todays_date = date("dmyh");
        $random_num = random_int(1000, 9999);
        $receipt_id = "DEP".$todays_date.$store.$random_num.$user_id;

?>


<div id="deposit" class="displays">
    <div class="info" style="width:50%; margin:5px 0;"></div>
    <div class="add_user_form" style="width:70%; margin:5px 0;">
        <h3 style="background:var(--otherColor); text-align:left">Fund customer wallet</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <div class="details_forms" style="align-items:flex-start;">
            <section class="addUserForm">
                <div class="inputs" style="flex-wrap:wrap">
                    <input type="hidden" name="invoice" id="invoice" value="<?php echo $receipt_id?>">
                    <input type="hidden" name="posted" id="posted" value="<?php echo $user_id?>">
                    <input type="hidden" name="customer" id="customer" value="<?php echo $customer_id?>">
                    <input type="hidden" name="store" id="store" value="<?php echo $store?>">
                    
                    <div class="data" style="width:100%; margin:5px 0">
                        <label for="amount"> Amount paid</label>
                        <input type="text" name="amount" id="amount" required placeholder="000">
                    </div>
                    <div class="data" style="width:100%">
                        <label for="Payment_mode">Payment mode</label>
                        <select name="payment_mode" id="payment_mode">
                            <option value=""selected>Select payment option</option>
                            <option value="Cash">Cash</option>
                            <option value="POS">POS</option>
                            <option value="Transfer">Transfer</option>
                        </select>
                    </div>
                    <div class="data" style="width:100%; margin:5px 0">
                        <label for="details"> Description</label>
                        <textarea name="details" id="details" cols="30" rows="5" placeholder="Enter a detailed description of the transaction"></textarea>
                    </div>
                    <div class="data" style="width:50%; margin:5px 0">
                        <button type="submit" id="post_exp" name="post_exp" onclick="deposit()">Deposit <i class="fas fa-cash-register"></i></button>
                    </div>
                </div>
            </section>
            <section class="customer_details" style="height:100%;">
                <div class="inputs">
                    <div class="data">
                        <label for="customer_id">Customer ID:</label>
                        <input type="text" value="<?php echo "1001000".$customer_id?>">
                    </div>
                    <div class="data">
                        <label for="customer_name">Customer Name:</label>
                        <input type="text" value="<?php echo $customer?>">
                    </div>
                    <div class="data">
                        <label for="balance">Available balance:</label>
                        <input type="text" value="<?php echo "₦".number_format($balance, 2)?>" style="color:green;">
                    </div>
                    
                </div>
            </section> 
        </div>
    </div>
</div>
<?php
            }
        
    }else{
        header("Location: ../index.php");
    }
?>
</div>