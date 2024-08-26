<?php
    session_start();
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        $store = $_SESSION['store_id'];
        if(isset($_GET['item'])){
            $item = $_GET['item'];
            // $date = "05-09-2025";
    
    $invoice = $_SESSION['purchase_invoice'];
    $vendor = $_SESSION['vendor'];
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_item = new selects();
    $rows = $get_item->fetch_details_cond('items', 'item_id', $item);
     if(gettype($rows) == 'array'){
        foreach($rows as $row):
    ?>
    <div class="add_user_form" style="width:100%!important; margin:0!important">
        <h3 style="background:var(--otherColor); text-align:left;">Stockin <?php echo strtoupper($row->item_name)?></h3>
        <section class="addUserForm" style="text-align:left!important;">
            <div class="inputs" style="flex-wrap:wrap;gap:.8rem 1.2rem;justify-content:start;">
                <!-- <div class="data item_head"> -->
                    <input type="hidden" name="posted_by" id="posted_by" value="<?php echo $user_id?>" required>
                    <input type="hidden" name="store" id="store" value="<?php echo $store?>" required>
                    <input type="hidden" name="invoice_number" id="invoice_number" value="<?php echo $invoice?>" required>
                    <input type="hidden" name="supplier" id="supplier" value="<?php echo $vendor?>" required>
                    <input type="hidden" name="item_id" id="item_id" value="<?php echo $row->item_id?>" required>
                <div class="data" style="width:21%; margin:5px;">
                    <label for="quantity">Quantity</label>
                    <input type="number" name="quantity" id="quantity">
                </div>
                <div class="data" style="width:21%; margin:5px;">
                    <label for="cost_price">Cost price (NGN)</label>
                    <input type="text" name="cost_price" id="cost_price" value="<?php echo $row->cost_price?>">
                </div>
                <div class="data" style="width:21%; margin:5px;">
                    <label for="sales_price">Retail price (NGN)</label>
                    <input type="text" name="sales_price" id="sales_price" value="<?php echo $row->sales_price?>">
                </div>
                <div class="data" style="width:21%; margin:5px;display:none">
                    <label for="sales_price">Retail Pack price (NGN)</label>
                    <input type="text" name="pack_price" id="pack_price" value="<?php echo $row->pack_price?>">
                </div>
                <div class="data" style="width:22%; margin:5px;">
                    <label for="sales_price">Wholesale price (NGN)</label>
                    <input type="text" name="wholesale_price" id="wholesale_price" value="<?php echo $row->wholesale?>">
                </div>
                <div class="data" style="width:22%; margin:5px;">
                    <label for="pack_price">Wholesale Pack (NGN)</label>
                    <input type="text" name="wholesale_pack" id="wholesale_pack" value="<?php echo $row->wholesale_pack?>">
                </div>
                <div class="data" style="width:22%; margin:5px;">
                    <label for="sales_price">Pack size</label>
                    <input type="text" name="pack_size" id="pack_size" value="<?php echo $row->pack_size?>">
                </div>
                <div class="data" style="width:22%; margin:5px;">
                    <label for="expiration_date">Expiration date</label>
                    <input type="date" name="expiration_date" id="expiration_date" required>
                </div>
                <div class="data" style="width:auto; margin:5px;">
                    <button type="submit" id="stockin" name="stockin" title="stockin item" onclick="stockin()"><i class="fas fa-check"></i></button>
                </div>
            </div>
        </section>   
    </div>
    
<?php
    endforeach;
     }
    }
    }else{
        header("Location: ../index.php");
    } 
?>