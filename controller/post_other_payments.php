<?php
    session_start();
    $store = $_SESSION['store_id'];
    $detail = "Deposit";
    
    $posted_by = htmlspecialchars(stripslashes($_POST['posted']));
    $customer = htmlspecialchars(stripslashes($_POST['customer']));
    $mode = htmlspecialchars(stripslashes($_POST['mode']));
    $invoice = ucwords(htmlspecialchars(stripslashes($_POST['invoice'])));
    $amount = htmlspecialchars(stripslashes($_POST['amount']));
    //generate invoice


    //instantiate classes
    include "../classes/dbh.php";
    include "../classes/inserts.php";
    include "../classes/select.php";
    include "../classes/update.php";
    $post_payment = new other_payments($posted_by, $mode, $amount, $invoice, $customer);

    $post_payment->other_payment();
    if($post_payment){
        //insert into customer trails
        $insert_trail = new customer_trail($customer, $store, $detail, $amount, $posted_by);
        $insert_trail->add_trail();
        //update debtor
        $update_debt = new Update_table();
        $update_debt->update('debtors', 'debt_status', 'invoice', 1, $invoice);
        echo "<div class='success'><p>Payment posted successfully! <i class='fas fa-thumbs-up'></i></p></div>";
    }
    