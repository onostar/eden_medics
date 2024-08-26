<?php

    $name = ucwords(htmlspecialchars(stripslashes($_POST['staff_name'])));
    $phone = htmlspecialchars(stripslashes($_POST['phone_number']));
    $address = ucwords(htmlspecialchars(stripslashes(($_POST['home_address']))));

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/inserts.php";

    $add_staff = new add_staff($name, $phone, $address);
    $add_staff->create_staff();