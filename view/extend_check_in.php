<?php
    session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        // echo $user_id;
        if(isset($_GET['guest_id'])){
            $guest = $_GET['guest_id'];
            $get_user = new selects();
            $details = $get_user->fetch_details_cond('check_ins', 'guest_id', $guest);
            foreach($details as $detail){
?>

<div id="check_in" class="displays">
    <button class="page_navs" id="back" onclick="showPage('extend_stay.php')"><i class="fas fa-angle-double-left"></i> Back</button>
    <div class="info"></div>
    <div class="add_user_form">
        <h3>Extend guest stay</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <form class="addUserForm" method="POST" action="../controller/extend_stay.php">
                <input type="hidden" name="posted_by" id="posted_by" value="<?php echo $user_id?>">
                <input type="hidden" name="guest" id="guest" value="<?php echo $guest?>">
            
            <div class="inputs">
                <div class="data" style="width:48%">
                    <label for="check_in_date">Check in date</label>
                    <input type="date" name="check_in_date" id="check_in_date" value="<?php echo date("Y-m-d")?>"required readonly>
                </div>
                <div class="data" class="data" style="width:48%">
                    <label for="check_out_date">Check out date</label>
                    <input type="date" name="check_out_date" id="check_out_date" required oninput="calculateDays()">
                </div>
                
                
            </div>
            <div class="inputs">
                <div class="data">
                    <label for="amount_due" style="color:red" >Amount due(NGN): </label>
                    <div id="amount">

                    </div>
                    <div id="fee">
                        <?php
                            //get room price
                            $get_cat = new selects();
                            $categories = $get_cat->fetch_details_group('rooms', 'category', 'room_id', $detail->room);
                            $category_id = $categories->category;
                            //get category price
                            $get_price = new selects();
                            $cat_price = $get_price->fetch_details_group('categories', 'price', 'category_id', $category_id);
                            $room_price =  $cat_price->price;


                        ?>
                        <input type="hidden" name="room_fee" id="room_fee" value="<?php echo $room_price?>">
                    </div>
                </div>
                <div class="data" id="days">

                </div>
                <div class="data">
                    <button type="submit" id="extend" name="extend" onclick="checkIn()">Extend stay <i class="fas fa-gauge"></i></button>
                </div>
                
            </div>
        </form>    
    </div>
</div>
<?php
            }
        }
    }else{
        header("Location: ../index.php");

    }
?>