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
        <h3 style="background:var(--otherColor); text-align:left">Adjust quantity for <?php echo strtoupper($name->item_name)?><span style="color:red; font-weight:bold"> (<?php echo $row->quantity?>)</span></h3>
        <section class="addUserForm" style="text-align:left;">
            <div class="inputs">
                <!-- <div class="data item_head"> -->
                    <input type="hidden" name="item_id" id="item_id" value="<?php echo $id?>" required>
                <div class="data" style="width:23%">
                    <label for="sales_price">Modify Quantity</label>
                    <input type="text" name="quantity" id="quantity" value="<?php echo $row->quantity?>">
                </div>
                <div class="data" style="width:23%">
                    <label for="sales_price">Expiration date</label>
                    <input type="date" name="expiration" id="expiration" value="<?php echo date("Y-m-d", strtotime($row->expiration_date))?>">
                </div>
                <div class="data" style="width:30%; display:flex;">
                    <button type="submit" id="adjust_qty" name="adjust_qty" onclick="adjustQty()"> Save <i class="fas fa-save"></i></button>
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