<div id="edit_customer">
<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        // echo $user_id;
        if(isset($_GET['vendor'])){
            $customer = $_GET['vendor'];
            //get customer name
            $get_customer = new selects();
            $rows = $get_customer->fetch_details_cond('vendors', 'vendor_id', $customer);
            foreach($rows as $row){

?>
    <div class="add_user_form" style="width:70%">
        <h3>Edit <?php echo $row->vendor?> details</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs" style="gap:.5rem;">
                <div class="data" style="width:30%">
                    <label for="customer">Vendor Name</label>
                    <input type="text" name="vendor" id="vendor" placeholder="Enter vendor name" value="<?php echo $row->vendor?>" required>
                    <input type="hidden" name="vendor_id" id="vendor_id" value="<?php echo $row->vendor_id?>" required>
                </div>
                <div class="data" style="width:30%">
                    <label for="phone_number">Phone number</label>
                    <input type="text" name="phone_number" id="phone_number" required value="<?php echo $row->phone?>">
                </div>
                <div class="data" style="width:30%">
                    <label for="Address">Contact person</label>
                    <input type="text" name="contact" id="contact" required value="<?php echo $row->contact_person?>">
                </div>
                <div class="data" style="width:30%">
                    <label for="email">Email address</label>
                    <input type="text" name="email" id="email" required value="<?php echo $row->email_address?>">
                </div>
                <div class="data" style="width:30%">
                    <button type="submit" id="update_customer" name="update_customer" onclick="updateVendor()">Update details <i class="fas fa-save"></i></button>
                </div>
            </div>
        </section>    
    </div>

<?php
            }
        }
    }else{
        header("Location: ../index.php");
    }
?>
</div>
