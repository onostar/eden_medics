<?php

    $category = htmlspecialchars(stripslashes($_POST['check_in_category']));

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_room = new selects();
    $rows = $get_room->fetch_details_2cond('rooms', 'category', 'room_status', $category, 0);
?>
    <option value=""selected>Select a room</option>
<?php
    if(gettype($rows) == 'array'){
        foreach ($rows as $row) {
            

?>
    <option value="<?php echo $row->room_id?>"><?php echo $row->room?></option>
<?php
        }   
    }else{
        echo "<option value=''selected>Please select a category</option>";
    }
?>