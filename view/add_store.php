<?php
    include "../classes/dbh.php";
    include "../classes/select.php";
?>

<div id="add_store" class="displays">
    <div class="info" style="width:35%; margin:20px"></div>
    <div class="add_user_form" style="width:35%; margin:20px">
        <h3 style="background:var(--moreColor)">Add store details</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs">
                <div class="data" style="width:100%; margin:10px 0">
                    <label for="store"> Location</label>
                    <input type="text" name="store_name" id="store_name" required>
                </div>
                <div class="data" style="width:100%; margin:10px 0">
                    <label for="store_address"> Address</label>
                    <input type="text" name="store_address" id="store_address" required>
                </div>
                <div class="data" style="width:100%; margin:10px 0">
                    <label for="phone"> Phone numbers</label>
                    <input type="tel" name="phone" id="phone" required>
                </div>
            </div>
            <div class="inputs">
                <div class="data">
                    <button type="submit" id="add_store" name="add_store" onclick="addStore()">Create location <i class="fas fa-save"></i></button>
                </div>
            </div>
        </section>    
    </div>
</div>
