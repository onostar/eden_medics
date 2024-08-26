<?php
   
        $item = $_GET['item'];
        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/delete.php";

        //get item details in inventory
        $get_qty = new selects();
        $rows = $get_qty->fetch_details_cond('inventory', 'item', $item);
        if(gettype($rows) == "array"){
            echo "<p style='background:red; color:#fff; padding:5px'>Item could not be deleted. Item already has transactions <i class='fas fa-thumbs-down'></i></p>";
        }else{
        //delete item
            $delete = new deletes();
            $delete->delete_item('items', 'item_id', $item);
            if($delete){
                echo "<div class='success'><p>Item deleted successfully! <i class='fas fa-thumbs-up'></i></p></div>";
            }
        }
?>
