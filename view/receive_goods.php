<?php
    session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
?>

<div id="stockin" class="displays">
    <div class="add_user_form" style="width:50%; margin:10px 0;">
        <h3 style="background:var(--tertiaryColor); text-align:left!important;" >Stockin Purchases to warehouse</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs">
                
                <div class="data">
                    <label for="Invoice">Invoice Number</label>
                    <input type="text" name="invoice" id="invoice" required>
                </div>
                <div class="data">
                    <label for="vendor">Supplier</label>
                    <select name="vendor" id="vendor">
                        <option value=""selected required>Select supplier</option>
                        <?php
                            $get_dep = new selects();
                            $rows = $get_dep->fetch_details('vendors');
                            foreach($rows as $row){
                        ?>
                        <option value="<?php echo $row->vendor_id?>"><?php echo $row->vendor?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="data" style="width:100%; margin:10px 0">
                    <input type="text" name="item" id="item" required placeholder="Input item name or barcode" onkeyup="getItemStockin(this.value, 'get_warehouse_stockin.php')">
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