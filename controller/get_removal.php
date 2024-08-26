<?php
    session_start();
    $store = $_SESSION['store_id'];
    if (isset($_GET['item_id'])){
        $id = $_GET['item_id'];
    

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_item = new selects();
    $rows = $get_item->fetch_details_cond('inventory', 'inventory_id', $id);
     if(gettype($rows) == 'array'){
        foreach($rows as $row):
            
        //get item name
        $get_name = new selects();
        $name = $get_name->fetch_details_group('items', 'item_name', 'item_id', $row->item);
    ?>
    <div class="add_user_form priceForm" style="width:100%; margin:5px 0;">
        <h3 style="background:var(--primaryColor); text-align:left">Remove quantity from <?php echo strtoupper($name->item_name)?><span style="color:red; font-weight:bold"> (<?php echo $row->quantity?>)</span></h3>
        <section class="addUserForm" style="text-align:left;">
            <div class="inputs">
                <!-- <div class="data item_head"> -->
                    <input type="hidden" name="item_id" id="item_id" value="<?php echo $id?>" required>
                <div class="data" style="width:20%">
                    <label for="sales_price">Remove Qty</label>
                    <input type="text" name="quantity" id="quantity" value="<?php echo $row->quantity?>">
                </div>
                <div class="data" style="width:23%">
                    <label for="sales_price">Reason</label>
                    <select name="remove_reason" id="remove_reason">
                        <option value=""selected>Select reason for removal</option>
                        <?php
                            $get_reason = new selects();
                            $rows = $get_reason->fetch_details('remove_reasons');
                            foreach($rows as $row){
                        ?>
                            <option value="<?php echo $row->reason?>"><?php echo $row->reason?></option>
                        <?php }?>
                    </select>
                </div>
                <div class="data" style="width:15%; display:flex;">
                    <button type="submit" id="adjust_qty" name="adjust_qty" onclick="removeQty()"><i class="fas fa-save"></i></button>
                    <a href="javascript:void(0)" title="close form" style='background:red; padding:8px; margin:0 5px;border-radius:5px; color:#fff' onclick="closeForm()"><i class='fas fa-close'></i></a>
                </div>
            </div>
        </section>   
    </div>
    
<?php
    endforeach;
     }
    }    
?>