<?php
    session_start();    
    $extended_by = htmlspecialchars(stripslashes($_POST['posted_by']));
    $guest = htmlspecialchars(stripslashes($_POST['guest']));
    $extend_date = htmlspecialchars(stripslashes($_POST['check_in_date']));
    $new_checkout = htmlspecialchars(stripslashes($_POST['check_out_date']));
    $new_amount = htmlspecialchars(stripslashes($_POST['amount_due']));

        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/update.php";
        include "../classes/select.php";

        //get old amount due
        $get_amount = new selects();
        $rows = $get_amount->fetch_details_cond('check_ins', 'guest_id', $guest);
        foreach($rows as $row){
            $old_amount = $row->amount_due;
            $full_name = $row->last_name." ".$row->first_name;
        }
        //get new balance
        $new_balance = $old_amount + $new_amount;

        //now extend stay
        $extend_stay = new Update_table();
        $extend_stay->update_multiple('check_ins', 'amount_due', $new_balance, 'date_extended', $extend_date, 'extended_by', $extended_by, 'check_out_date', $new_checkout, 'stay_extended', 1, 'guest_id', $guest);
        if($extend_stay){
            $_SESSION['success'] = "<p>$full_name Stay extended! <i class='fas fa-thumbs-up'></i></p>";
            header("Location: ../view/users.php");
        }
