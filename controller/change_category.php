<?php
    session_start();    
    // if(isset($_POST['change_prize'])){
        $item = htmlspecialchars(stripslashes($_POST['item_id']));
        $department = htmlspecialchars(stripslashes($_POST['department']));
        $category = htmlspecialchars(stripslashes($_POST['item_category']));

        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/update.php";

        $change_category = new Update_table();
        $change_category->update_double('items', 'department', $department, 'category', $category, 'item_id', $item);
        if($change_category){
             echo "<div class='success'><p>Item category changed successfully! <i class='fas fa-thumbs-up'></i></p></div>";
        }else{
            echo "<p style='background:red; color:#fff; padding:5px'>Failed to change item category <i class='fas fa-thumbs-down'></i></p>";
        }
    // }