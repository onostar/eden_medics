<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        // echo $user_id;
        if(isset($_GET['customer'])){
            $customer = $_GET['customer'];
            //get customer name
            $get_customer = new selects();
            $rows = $get_customer->fetch_details_group('customers', 'customer', 'customer_id', $customer);
            $customer_name = $rows->customer;

?>
<div id="direct_sales">
<div id="sales_form" class="displays all_details">
    <?php
    
        //generate receipt invoice
        //get current date
        $todays_date = date("dmyh");
        $ran_num ="";
        for($i = 0; $i < 5; $i++){
            $random_num = random_int(0, 9);
            $ran_num .= $random_num;
        }
        $invoice = "SR".$store.$todays_date.$ran_num.$user_id;
        // $_SESSION['invoice'] = $invoice;
    ?>
    
    <div class="add_user_form" style="width:50%; margin:10px 0; box-shadow:none">
        <h3 style="background:var(--otherColor); color:#ff; text-align:left!important;" >Rep sales order for <?php echo $customer_name. " (".$invoice.")"?></h3>
        
            <!-- search forms -->
        <!-- <form method="POST" id="addUserForm"> -->
            <section class="addUserForm">
                <div class="inputs">
                    <!-- bar items form -->
                    <div class="data" id="bar_items" style="width:100%; margin:2px 0">
                        <label for="item"> Search Items</label>
                        <input type="hidden" name="customer" id="customer" value="<?php echo $customer?>">
                        <input type="hidden" name="invoice" id="invoice" value="<?php echo $invoice?>">
                        <input type="text" name="item" id="item" required placeholder="Input item name or barcode" onkeyup="getWholesaleItems(this.value)">
                        <div id="sales_item">
                            
                        </div>
                    </div>
                    
                </div>
            </section>
            
        </div>
    </div>

</div>
<!-- for editing item quantitiy and price -->
<div class="show_more"></div>
<!-- showing all items in the sales order -->
<div class="sales_order"></div>
<?php
        }
    }else{
        header("Location: ../index.php");
    }
?>
</div>