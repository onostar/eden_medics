<?php
    $vendor_id = htmlspecialchars(stripslashes($_POST['vendor_id']));
    $vendor = ucwords(htmlspecialchars(stripslashes($_POST['vendor'])));
    $phone = htmlspecialchars(stripslashes($_POST['phone_number']));
    $contact = ucwords(htmlspecialchars(stripslashes(($_POST['contact']))));
    $email = htmlspecialchars(stripslashes(($_POST['email'])));

   
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/update.php";

       //update customer
       $update_data = new Update_table();
       $update_data->update_quadruple('vendors', 'vendor', $vendor, 'phone',$phone, 'contact_person', $contact, 'email_address', $email, 'vendor_id', $vendor_id);
       if($update_data){
           echo "<div class='success'><p>$vendor</span> details updated successfully! <i class='fas fa-thumbs-up'></i></p></div>";
       }
   