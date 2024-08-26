<?php
    session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
?>

<div id="stockin" class="displays">
    <div class="add_user_form" style="width:50%; margin:10px 0;">
        <h3 style="background:var(--moreColor); text-align:left!important;" >Purchase history per vendors</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs" style="align-items:flex-end;">
                
                <div class="data">
                    <label for="fromDate">From Date</label>
                    <input type="date" name="fromDate" id="fromDate" required>
                </div>
                <div class="data">
                    <label for="toDate">To Date</label>
                    <input type="date" name="toDate" id="toDate" required>
                </div>
                <div class="data" style="margin:10px 0 0">
                    <label for="">select a Vendor</label>
                    <select name="vendor" id="vendor">
                        <option value=""selected>choose vendor</option>
                        <?php
                            //get vendors
                            $get_vendors = new selects();
                            $rows = $get_vendors->fetch_details('vendors');
                            foreach($rows as $row){
                        ?>
                        <option value="<?php echo $row->vendor_id?>"><?php echo $row->vendor?></option>
                        <?php }?>
                    </select>
                </div>
                <div class="data">
                    <button style="background:var(--otherColor);"onclick="vendorHistory()">Search <i class="fas fa-search"></i></button>
                </div>
            </div>
        </section>
    </div>
    <div class="allResults new_data" style="width:100%; margin:0"></div>
    
</div>
<?php
    }else{
        header("Location: ../index.php");
    }
?>