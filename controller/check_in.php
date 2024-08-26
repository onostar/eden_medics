<?php

    
    $posted = htmlspecialchars(stripslashes($_POST['posted_by']));
    $room = htmlspecialchars(stripslashes($_POST['check_in_room']));
    $last_name = ucwords(htmlspecialchars(stripslashes($_POST['last_name'])));
    $first_name = ucwords(htmlspecialchars(stripslashes($_POST['first_name'])));
    $age = ucwords(htmlspecialchars(stripslashes($_POST['age'])));
    $gender = ucwords(htmlspecialchars(stripslashes($_POST['gender'])));
    $contact_person = ucwords(htmlspecialchars(stripslashes($_POST['contact_person'])));
    $contact_address = ucwords(htmlspecialchars(stripslashes($_POST['contact_address'])));
    $contact_phone = ucwords(htmlspecialchars(stripslashes($_POST['contact_phone'])));
    $relationship = ucwords(htmlspecialchars(stripslashes($_POST['relationship'])));
    $cause = ucwords(htmlspecialchars(stripslashes($_POST['death_cause'])));
    $check_in_date = htmlspecialchars(stripslashes($_POST['check_in_date']));
    $check_out_date = htmlspecialchars(stripslashes($_POST['check_out_date']));
    $amount = htmlspecialchars(stripslashes($_POST['amount_due']));
    // $guest_id = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

    //instantiate classes
    include "../classes/dbh.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    $check_in = new check_in($posted, $room, $last_name, $first_name, $age, $gender, $contact_person, $contact_phone, $contact_address, $relationship, $cause, $amount, $check_in_date, $check_out_date);

    $check_in->check_in();
    if($check_in){
        $update_room = new Update_table();
        $update_room->update('rooms', 'room_status', 'room_id', 1, $room);
    }