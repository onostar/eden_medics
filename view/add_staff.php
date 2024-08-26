<?php
    include "../classes/dbh.php";
    include "../classes/select.php";
?>

<div id="add_room" class="displays">
    <div class="info" style="width:50%; margin:5px 0;"></div>
    <div class="add_user_form" style="width:35%; margin:5px 0;">
        <h3 style="background:var(--moreColor)">Add Staffs/ Sales Rep</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs">
                <div class="data" style="width:100%; margin:10px 0;">
                    <label for="staff_name">Staff Full Name</label>
                    <input type="text" name="staff_name" id="staff_name" required>
                </div>
                <div class="data" style="width:100%; margin:10px 0;">
                    <label for="phone_number">Phone Number</label>
                    <input type="tel" name="phone_number" id="phone_number" required>
                </div>
                <div class="data" style="width:100%; margin:10px 0">
                    <label for="home_address">Residential Address</label>
                    <input type="text" name="home_address" id="home_address" required>
                </div>
            </div>
            <div class="inputs">
                <div class="data">
                    <button type="submit" id="add_staff" name="add_staff" onclick="addStaff()">Save record <i class="fas fa-save"></i></button>
                </div>
            </div>
        </section>    
    </div>
</div>
