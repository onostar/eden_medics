<?php
    session_start();
    
        $guest = $_POST['guest_id'];
        $user = $_POST['user_id'];
        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/update.php";
        include "../classes/select.php";

        $check_out = new Update_table();
        $check_out->checkOut('status', 'checked_out_by', 'guest_id', 2, $user, $guest);
        if($check_out){
        // get room
        $get_room = new selects();
        $rooms = $get_room->fetch_details_group('check_ins', 'room', 'guest_id', $guest);
        $the_room = $rooms->room;
        //update room status
        $update_room = new Update_table();
        $update_room->update('rooms', 'room_status', 'room_id', 0, $the_room);
        if($update_room){
            echo "<div class='succeed'><p><i class='fas fa-thumbs-up'></i></p><p>Guest Checked out successfully!</p></div>";
            // header("Location: ../view/users.php");
        }else{
            echo "<p>Failed to update room! <i class='fas fa-thumbs-up'></i></p>";
            // header("Location: ../view/users.php");
        }
    }