<?php
    session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        // echo $user_id;
    
?>

<div id="check_in" class="displays">
    <div class="info"></div>
    <div class="add_user_form">
        <h3>Check in a Guest</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs">
                <input type="hidden" name="posted_by" id="posted_by" value="<?php echo $user_id?>">
                <div class="data" style="width:49%">
                    <label for="check_in_category">Select room category</label>
                    <select name="check_in_category" id="check_in_category" onchange="getRooms(this.value)">
                        <option value=""selected required>Select category</option>
                        <?php
                            $get_category = new selects();
                            $rows = $get_category->fetch_details('categories');
                            foreach($rows as $row){
                        ?>
                        <option value="<?php echo $row->category_id?>"><?php echo $row->category?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="data" style="width:49%">
                    <label for="check_in_room">Select room</label>
                    <select name="check_in_room" id="check_in_room" onchange="getPrice(this.value)">
                        <option value=""selected required>Select room</option>
                        
                    </select>
                </div>
            </div>
            <div class="inputs">
                <div class="data">
                    <label for="last_name">Last name</label>
                    <input type="text" name="last_name" id="last_name" placeholder="Enter surname" required>
                </div>
                <div class="data">
                    <label for="first_name">First name</label>
                    <input type="text" name="first_name" id="first_name" placeholder="Enter first name" required>
                </div>
                <div class="data">
                    <label for="age">Age</label>
                    <input type="text" name="age" id="age" placeholder="35" required>
                </div>
            </div>
            <div class="inputs">
                <div class="data">
                    <label for="gender">Gender</label>
                    <select name="gender" id="gender" required>
                        <option value="" selected>Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="data">
                    <label for="contact_address">Residential Address</label>
                    <input type="text" name="contact_address" id="contact_address" placeholder="contact residential address" required>
                </div>
                <div class="data">
                    <label for="contact_phone">Contact Phone</label>
                    <input type="text" name="contact_phone" id="contact_phone" placeholder="+2347012345678" required>
                </div>
            </div>                
            <div class="inputs">
                <div class="data" style="width:48%">
                    <label for="check_in_date">Check in date</label>
                    <input type="date" name="check_in_date" id="check_in_date" required>
                </div>
                <div class="data" class="data" style="width:48%">
                    <label for="check_out_date">Check out date</label>
                    <input type="date" name="check_out_date" id="check_out_date" required oninput="calculateDays()">
                </div>
                
                
            </div>
            <div class="inputs">
                <div class="data">
                    <label for='amount_due' style='color:red' >Amount due(NGN): </label>
                    <div id="amount">

                    </div>
                    <div id="fee">

                    </div>
                </div>
                <div class="data" id="days">

                </div>
                <div class="data">
                    <button type="submit" id="check_in" name="check_in" onclick="checkIn()">Check in <i class="fas fa-check-double"></i></button>
                </div>
                
            </div>
        </section>    
    </div>
</div>
<?php
    }else{
        header("Location: ../index.php");

    }
?>