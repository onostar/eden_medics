<?php
    session_start();
    // $comp_id = $_SESSION['company_id'];
    include "../classes/dbh.php";
    include "../classes/update.php";

    if(isset($_POST['update_store'])){
        $store = ucwords(htmlspecialchars(stripslashes($_POST['store'])));
        $store_id = htmlspecialchars(stripslashes($_POST['store_id']));
        $address = htmlspecialchars(stripslashes($_POST['store_address']));
        $phone = htmlspecialchars(stripslashes($_POST['phone']));

        $update_store = new Update_table();
        $update_store->update_tripple('stores', 'store', $store, 'store_address', $address, 'phone_number', $phone, 'store_id', $store_id);
        if($update_store){
            $_SESSION['success'] = "<p>Store details updated succesfully</p>";
            header("Location: ../view/users.php");
        }
    }
?>