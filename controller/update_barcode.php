<?php
    session_start();    
    // if(isset($_POST['change_prize'])){
        $item = htmlspecialchars(stripslashes($_POST['item_id']));
        $barcode = htmlspecialchars(stripslashes($_POST['barcode']));

        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/update.php";

        $change_barcode = new Update_table();
        $change_barcode->update('items', 'barcode', 'item_id', $barcode, $item);
        if($change_barcode){
             echo "<div class='success'><p>barcode modified successfully! <i class='fas fa-thumbs-up'></i></p></div>";
        }else{
            echo "<p style='background:red; color:#fff; padding:5px'>Failed to modify barcode <i class='fas fa-thumbs-down'></i></p>";
        }
    // }