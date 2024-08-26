<?php

    include "../classes/dbh.php";
    include "../classes/select.php";


?>
    <div class="info"></div>
<div class="displays allResults" id="check_out_guest">
    <h2>Guest due for checkout</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchCheckout" placeholder="Enter keyword" onkeyup="searchData(this.value)">
    </div>
    <table id="check_out_table" class="searchTable">
        <thead>
            <tr>
                <td>S/N</td>
                <td>Full Name</td>
                <td>Room Category</td>
                <td>Room</td>
                <td>Checked In</td>
                <td></td>
                
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_dateCond('check_ins', 'status', 1);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><a style="box-shadow:2px 2px 2px #c4c4c4; padding:5px 10px;" href="javascript:void(0)" title="Disable user account" onclick="checkOut('<?php echo $detail->check_id?>')"><?php echo $n?></a></td>
                <td><?php echo $detail->last_name . " ". $detail->first_name;?></td>
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
                <td><?php echo date("jS M, Y", strtotime($detail->check_in_date));?></td>
                <td style="text-align:center"><span style="font-weight:bold; background:skyblue; border-radius:5px; text-align:Center; width:auto;padding:5px 10px;"><a href="javascript:void(0)" class="page_navs" title="View guest details" style="color:#fff" onclick="showPage('guest_details.php?guest_id=<?php echo $detail->guest_id?>')">Details</a></span></td>
                
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