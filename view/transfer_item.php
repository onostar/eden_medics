<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
?>

<div id="stockin" class="displays">
    <?php
        //generate receipt invoice
        //get current date
        $todays_date = date("dmyhi");
        $ran_num ="";
        for($i = 0; $i < 5; $i++){
            $random_num = random_int(0, 9);
            $ran_num .= $random_num;
        }
        $invoice = "TR".$ran_num.$store.$todays_date.$user_id;
        $_SESSION['invoice'] = $invoice;
    ?>
    <div class="add_user_form" style="width:50%; margin:10px 0;">
        <h3 style="background:var(--moreColor); text-align:left!important;" >Transfer items to store -> <?php echo $invoice?></h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs">
                
                <div class="data">
                    <input type="hidden" name="invoice" id="invoice" value="<?php echo $invoice?>">
                    <label for="vendor">Select store</label>
                    <select name="store_to" id="store_to">
                        <option value=""selected required>Select store</option>
                        <?php
                            $get_stores = new selects();
                            $rows = $get_stores->fetch_details_negCond1('stores', 'store_id', $store);
                            foreach($rows as $row){
                        ?>
                        <option value="<?php echo $row->store_id?>"><?php echo $row->store?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="data" style="width:100%; margin:10px 0">
                    <label for="">Enter item name</label>
                    <input type="text" name="item" id="item" required placeholder="Input item name or barcode" onkeyup="getItemTransfer(this.value)">
                        <div id="sales_item">
                            
                        </div>
                    
                </div>
            </div>
        </section>
    </div>
    <div class="info" style="width:100%; margin:0"></div>
    <div class="stocked_in"></div>
</div>
<?php
    }else{
        header("Location: ../index.php");
    }
?>