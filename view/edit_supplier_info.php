<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        // echo $user_id;

?>
<div id="edit_customer">
<div id="sales_form" class="displays all_details">
    
    
    <div class="add_user_form" style="width:50%; margin:10px 0;">
        <h3 style="background:var(--otherColor); color:#fff; text-align:left!important;">Edit vendor details</h3>
        
            <!-- search forms -->
        <!-- <form method="POST" id="addUserForm"> -->
            <section class="addUserForm">
                <div class="inputs">
                    <!-- bar items form -->
                    <div class="data" style="width:100%; position:relative;">
                    <label for="customer" style="color:var(--primaryColor)">Search supplier</label>
                        <input type="text" name="vendor" id="vendor" oninput="getVendorEdit(this.value)" placeholder="Enter upplier name">
                        <div class="search_results" id="search_results">

                        </div>
                    </div>
                    
                    
                </div>
            </section>
            
        </div>
    </div>

</div>
<?php
    }else{
        header("Location: ../index.php");
    }
?>
</div>