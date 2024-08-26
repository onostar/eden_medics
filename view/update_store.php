<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";

    //get store details
    $get_store = new selects();
    $rows = $get_store->fetch_details_cond('stores', 'store_id', $store);
    foreach($rows as $row){
?>

<div id="manage_profile" class="displays">
    <div class="info"></div>
    <div class="add_user_form" style="width:50%; margin:10px 50px">
        <h3 style="background:var(--otherColor)">Manage store details</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <form action="../controller/update_store.php" method="POST" class="addUserForm">
            <div class="inputs">
                <div class="data">
                    <input type="hidden" name="store_id" id="store_id" value="<?php echo $row->store_id?>">
                    <label for="store">store</label>
                    <input type="text" name="store" id="store" value="<?php echo $row->store?>" required>
                </div>
                <div class="data">
                    <label for="store_address">Address</label>
                    <input type="text" name="store_address" id="store_address" value="<?php echo $row->store_address?>">
                </div>
            </div>
            <div class="inputs">
                <div class="data">
                    <label for="phone_number">Phone Number</label>
                    <input type="tel" name="phone" id="phone" value="<?php echo $row->phone_number?>">
                </div>
                <div class="data">
                    <button type="submit" id="update_store" name="update_store">Update <i class="fas fa-save"></i></button>
                </div>
            </div>
        </section>    
    </div>
</div>
<?php }?>
