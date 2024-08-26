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
    <div class="add_user_form priceForm" style="width:100%;">
        <h3 style="background:var(--primaryColor)">Update <?php echo strtoupper($row->item_name)?> barcode</h3>
        <section class="addUserForm" style="text-align:left">
            <div class="inputs">
                <!-- <div class="data item_head"> -->
                    <input type="hidden" name="item_id" id="item_id" value="<?php echo $row->item_id?>" required>
                <div class="data" style="width:30%!important">
                    <label for="barcode" style="text-align:left!important;">Enter barcode</label>
                    <input type="text" name="barcode" id="barcode" value="<?php echo $row->barcode?>">
                </div>
                
                <div class="data">
                    <button type="submit" title="save" onclick="updateBarcode()"><i class="fas fa-save"></i> Update</button>
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