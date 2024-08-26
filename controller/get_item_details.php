<?php

    if (isset($_GET['item_id'])){
        $id = $_GET['item_id'];
    

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_item = new selects();
    $rows = $get_item->fetch_details_cond('items', 'item_id', $id);
     if(gettype($rows) == 'array'){
        foreach($rows as $row):
            
        
    ?>
    <div class="add_user_form priceForm">
        <h3 style="background:var(--primaryColor)">Edit price for <?php echo strtoupper($row->item_name)?></h3>
        <section class="addUserForm" style="text-align:left;">
            <div class="inputs" style="flex-wrap:wrap; gap:1rem;">
                <!-- <div class="data item_head"> -->
                    <input type="hidden" name="item_id" id="item_id" value="<?php echo $row->item_id?>" required>
                <div class="data" style="width:23%">
                    <label for="cost_price">Cost price (NGN)</label>
                    <input type="text" name="cost_price" id="cost_price" value="<?php echo $row->cost_price?>">
                </div>
                <div class="data" style="width:23%">
                    <label for="sales_price">Retail price (NGN)</label>
                    <input type="text" name="sales_price" id="sales_price" value="<?php echo $row->sales_price?>">
                </div>
                <div class="data" style="width:23%">
                    <label for="pack_price">Retail Pack Price (NGN)</label>
                    <input type="text" name="pack_price" id="pack_price" value="<?php echo $row->pack_price?>">
                </div>
                <div class="data" style="width:23%">
                    <label for="sales_price">Wholesale price (NGN)</label>
                    <input type="text" name="wholesale_price" id="wholesale_price" value="<?php echo $row->wholesale?>">
                </div>
                <div class="data" style="width:23%">
                    <label for="pack_price">Wholesale Pack Price (NGN)</label>
                    <input type="text" name="wholesale_pack" id="wholesale_pack" value="<?php echo $row->wholesale_pack?>">
                </div>
                <div class="data" style="width:20%">
                    <label for="sales_price">Pack size</label>
                    <input type="text" name="pack_size" id="pack_size" value="<?php echo $row->pack_size?>">
                </div>
                <div class="data" style="width:auto">
                    <button type="submit" id="change_price" name="change_price" onclick="changeItemPrice()">Save <i class="fas fa-save"></i></button>
                    <a href="javascript:void(0)" title="close form" style='background:red; padding:10px; border-radius:5px; color:#fff' onclick="closeForm()">Return <i class='fas fa-angle-double-left'></i></a>
                </div>
                
            </div>
        </section>   
    </div>
    
<?php
    endforeach;
     }
    }    
?>