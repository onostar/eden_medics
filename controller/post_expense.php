<?php

    $user = htmlspecialchars(stripslashes($_POST['posted']));
    $store = htmlspecialchars(stripslashes($_POST['store']));
    $date = htmlspecialchars(stripslashes($_POST['exp_date']));
    $head = htmlspecialchars(stripslashes(($_POST['exp_head'])));
    $amount = htmlspecialchars(stripslashes(($_POST['amount'])));
    $details = ucwords(htmlspecialchars(stripslashes(($_POST['details']))));

    $data = array(
        'posted_by' => $user,
        'expense_date' => $date,
        'expense_head' => $head,
        'amount' => $amount,
        'details' => $details,
        'store' => $store
    );
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";

    //post expense
    $add_data = new add_data('expenses', $data);
    $add_data->create_data();
    if($add_data){
        echo "<p>Expense Posted successfully!</p>";
    }