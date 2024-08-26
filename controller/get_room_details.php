<?php

    if (isset($_GET['item_id'])){
        $id = $_GET['item_id'];
    

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_item = new selects();
    $rows = $get_item->fetch_details_cond('categories', 'category_id', $id);
     if(gettype($rows) == 'array'){
        foreach($rows as $row):
            
        
    ?>
    <div class="add_user_form priceForm" style="width:60%;">
        <h3 style='background:var(--otherColor);'>Edit <?php echo strtoupper($row->category)?> price</h3>
        <section class="addUserForm" style="text-align:left">
            <div class="inputs">
                    <input type="hidden" name="item_id" id="item_id" value="<?php echo $row->category_id?>" required>
                <!-- </div> -->
                
                <div class="data" style="width:auto!important; text-align:left; margin-right:5px">
                    <label for="sales_price">Amount (NGN)</label>
                    <input type="text" name="price" id="price" value="<?php echo $row->price?>">
                </div>
                <div class="data">
                    <button type="submit" id="change_price" name="change_price" onclick="changeRoomPrice()">Save <i class="fas fa-save"></i></button>
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