<?php
    include "../classes/dbh.php";
    include "../classes/select.php";
?>

<div id="add_room" class="displays">
    <div class="info" style="width:50%; margin:5px 0;"></div>
    <div class="add_user_form" style="width:50%; margin:5px 0;">
        <h3 style="background:var(--moreColor)">Create Suppliers</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs">
                <div class="data" style="width:49%; margin:5px 0;">
                    <label for="supplier">Supplier name</label>
                    <input type="text" name="supplier" id="supplier" required placeholder="XYZ limited">
                </div>
                <div class="data" style="width:49%; margin:5px 0;">
                    <label for="contact_person">Contact Person</label>
                    <input type="text" name="contact_person" id="contact_person" required placeholder="James John">
                </div>
                
                <div class="data" style="width:49%; margin:5px 0">
                    <label for="phone"> Phone number</label>
                    <input type="text" name="phone" id="phone" required placeholder="+234702345678">
                </div>
                <div class="data" style="width:49%; margin:5px 0">
                    <label for="email"> Email Address</label>
                    <input type="text" name="email" id="email" placeholder="mail@example.com">
                </div>
            </div>
            <div class="inputs">
                <div class="data">
                    <button type="submit" id="add_supplier" name="add_supplier" onclick="addSupplier()">Save record <i class="fas fa-save"></i></button>
                </div>
            </div>
        </section>    
    </div>
</div>
