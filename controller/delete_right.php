<?php
    
        $right = $_GET['right'];
        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/delete.php";
        
        //delete sales
        $delete = new deletes();
        $delete->delete_item('rights', 'right_id', $right);
        if($delete){
            echo "<p>Right removed from user!</p>";
        }            
        
    // }
?>