<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        // echo $user_id;

?>
<div id="transfer_quantities">
<div id="sales_form" class="displays all_details">
    
    <div class="add_user_form" style="width:50%; margin:10px 0; box-shadow:none">
        <h3 style="background:var(--primaryColor); color:#fff; text-align:left!important;">Transfer quantity from an item</h3>
        
            <!-- search forms -->
        <!-- <form method="POST" id="addUserForm"> -->
            <section class="addUserForm">
                <div class="inputs">
                    <!-- bar items form -->
                    <div class="data" id="bar_items" style="width:100%; margin:2px 0">
                        <label for="item"> Search Item to transfer from</label>
                        <!-- <input type="hidden" name="sales_invoice" id="sales_invoice" value="<?php echo $invoice?>"> -->
                        <!-- <input type="hidden" name="staff" id="staff" value="<?php echo $staff?>"> -->
                        <input type="hidden" name="transfer_qty_from" id="transfer_qty_from">
                        <input type="text" name="item" id="item" required placeholder="Input item name or barcode" onkeyup="getItemsToTransfer(this.value)">
                        <div id="sales_item">
                            
                        </div>
                    </div>
                    <div class="data" id="bar_items" style="width:100%; margin:10px 0">
                        <label for="item"> Search Item to transfer quantity to</label>
                        <input type="hidden" name="transfer_qty_to" id="transfer_qty_to">
                        <input type="text" name="item_to" id="item_to" required placeholder="Input item name or barcode" onkeyup="getItemsToTransferTo(this.value)">
                        <div id="transfer_item">
                            
                        </div>
                    </div>
                    <div class="inputs">
                        <div class="data">
                            <label for="remove_qty">Quantity to remove</label>
                            <input type="number" name="remove_qty" id="remove_qty" value="0">
                        </div>
                        <div class="data">
                            <label for="add_qty">Quantity to add</label>
                            <input type="number" name="add_qty" id="add_qty" value="0">
                        </div>
                    </div>
                    <div class="inputs">
                        <div class="data">
                            <button onclick="transferQty()">Transfer quantity <i class="fas fa-arrows-left-right"></i></button>
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
    }else{
        header("Location: ../index.php");
    }
?>
</div>