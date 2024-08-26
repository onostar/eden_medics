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
        <h3 style="background:var(--primaryColor)">Modify <?php echo strtoupper($row->item_name)?> name</h3>
        <section class="addUserForm" style="text-align:left">
            <div class="inputs">
                <!-- <div class="data item_head"> -->
                    <input type="hidden" name="item_id" id="item_id" value="<?php echo $row->item_id?>" required>
                <div class="data">
                    <label for="item_name" style="text-align:left!important;">Edit item name</label>
                    <input type="text" name="item_name" id="item_name" value="<?php echo $row->item_name?>">
                </div>
                
                <div class="data">
                    <button type="submit" id="modify_item" name="modify_item" onclick="modifyItem()">Modify <i class="fas fa-save"></i></button>
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