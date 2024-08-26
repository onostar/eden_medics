<?php

    $room = htmlspecialchars(stripslashes($_POST['check_in_room']));

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_category = new selects();
    $row = $get_category->fetch_details_group('rooms', 'category', 'room_id', $room);
     $category = $row->category;
    
    $prices = $get_category->fetch_details_group('categories', 'price', 'category_id', $category);
?>
    <input type="hidden" name="room_fee" id="room_fee" value="<?php echo $prices->price?>">
<?php
        
?>