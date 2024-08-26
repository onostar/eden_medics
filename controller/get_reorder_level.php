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
    <div class="add_user_form priceForm" style="width:55%; margin:5px 0;">
        <h3 style="background:var(--primaryColor); text-align:left">Adjust reorder level for <?php echo strtoupper($row->item_name)?><span style="color:red; font-weight:bold"> (<?php echo $row->reorder_level?>)</span></h3>
        <section class="addUserForm" style="text-align:left;">
            <div class="inputs">
                <!-- <div class="data item_head"> -->
                    <input type="hidden" name="item_id" id="item_id" value="<?php echo $row->item_id?>" required>
                <div class="data" style="width:30%">
                    <label for="rol">Reorder Level</label>
                    <input type="text" name="rol" id="rol" value="<?php echo $row->reorder_level?>">
                </div>
                <div class="data adjust_btn" style="width:40%; display:flex;">
                    <button type="submit" id="adjust_rol" name="adjust_rol" onclick="adjustReorderLevel()"> Update</button>
                    <a href="javascript:void(0)" title="close form" style='background:red; padding:10px; border-radius:5px; color:#fff' onclick="closeForm()">Return </a>
                </div>
            </div>
        </section>   
    </div>
    
<?php
    endforeach;
     }
    }    
?>