<?php

    include "../classes/dbh.php";
    include "../classes/select.php";
    
    if(isset($_SESSION['success'])){
        echo $_SESSION['success'];
    }

?>
    <div class="info"></div>
<div class="displays allResults" id="guest_payment">
    <h2>Current Checked in Guests</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchGuestPayment" placeholder="Enter keyword" onkeyup="searchData(this.value)">
    </div>
    <table id="guest_payment_table" class="searchTable">
        <thead>
            <tr>
                <td>S/N</td>
                <td>Full Name</td>
                <td>Room Category</td>
                <td>Room</td>
                <td>Checked in</td>
                <td>Contact Person</td>
                <td>Phone Number</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_cond('check_ins', 'status', 1);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td style="color:green;"><?php echo $detail->last_name . " ". $detail->first_name;?></td>
                <td>
                    <?php 
                        $get_cat = new selects();
                        $categories = $get_cat->fetch_details_group('rooms', 'category', 'room_id', $detail->room);
                        $category_id = $categories->category;
                        //get category name
                        $get_cat_name = new selects();
                        $cat_name = $get_cat_name->fetch_details_group('categories', 'category', 'category_id', $category_id);
                        echo $cat_name->category;


                    ?>
                </td>
                <td>
                    <?php 
                        $get_room = new selects();
                        $rooms = $get_room->fetch_details_group('rooms', 'room', 'room_id', $detail->room);
                        echo $rooms->room;
                    ?>
                </td>
                <td><?php echo date("jS M, Y", strtotime($detail->check_in_date))?></td>
                <td><?php echo $detail->contact_person?></td>
                <td><?php echo $detail->contact_phone?></td>
                <td style="text-align:center"><span style="font-weight:bold; background:skyblue; border-radius:5px; text-align:Center; width:auto;padding:5px 10px;"><a href="javascript:void(0)" class="page_navs" title="View guest details" style="color:#fff" onclick="showPage('guest_details.php?guest_id=<?php echo $detail->guest_id?>')">View Bill</a></span></td>
            </tr>
            
            <?php $n++; endforeach;}?>
        </tbody>
    </table>
    
    <?php
        if(gettype($details) == "string"){
            echo "<p class='no_result'>'$details'</p>";
        }
    ?>
</div>