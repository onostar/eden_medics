<?php

    $user = htmlspecialchars(stripslashes($_POST['posted']));
    $receipt = htmlspecialchars(stripslashes($_POST['invoice']));
    $customer = htmlspecialchars(stripslashes($_POST['customer']));
    $store = htmlspecialchars(stripslashes($_POST['store']));
    $mode = htmlspecialchars(stripslashes($_POST['payment_mode']));
    $amount = htmlspecialchars(stripslashes(($_POST['amount'])));
    $details = ucwords(htmlspecialchars(stripslashes(($_POST['details']))));
    $trans_type = "Deposit";
    $type = "Deposit";

    $data = array(
        'posted_by' => $user,
        'customer' => $customer,
        'payment_mode' => $mode,
        'amount' => $amount,
        'details' => $details,
        'invoice' => $receipt,
        'store' => $store
    );
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    include "../classes/update.php";

    //post deposit
    $add_data = new add_data('deposits', $data);
    $add_data->create_data();
    if($add_data){
        //insert into customer trails
        $insert_trail = new customer_trail($customer, $store, $trans_type, $amount, $user);
        $insert_trail->add_trail();
        //update wallet balance
        //get cutomer wallet first
        $get_balance = new selects();
        $bals = $get_balance->fetch_details_group('customers', 'wallet_balance', 'customer_id', $customer);
        $balance = $bals->wallet_balance;
        $new_balance = $amount + $balance;

        $update_wallet = new Update_table();
        $update_wallet->update('customers', 'wallet_balance', 'customer_id', $new_balance, $customer);
        if($update_wallet){
?>
    <div id="printBtn">
        <button onclick="printDepositReceipt('<?php echo $receipt?>')">Print Receipt <i class="fas fa-print"></i></button>
    </div>
<?php

        echo "<p style='color:green; margin:5px 50px'>Account funded successfully!</p>";
    }
}