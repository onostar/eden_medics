<?php
    session_start();
    $user = $_SESSION['user_id'];
    $store = $_SESSION['store_id'];

    if (isset($_GET['sales_id'])){
        $sales = $_GET['sales_id'];
    

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    //get user role
    $get_role = new selects();
    $rowss = $get_role->fetch_details_group('users', 'user_role', 'user_id', $user);
    $role = $rowss->user_role;
 
    //get item
    $get_item = new selects();
    $rows = $get_item->fetch_details_cond('sales', 'sales_id', $sales);
     if(gettype($rows) == 'array'){
        foreach($rows as $row):
        //get item details from item table
        $get_item = new selects();
        $details = $get_item->fetch_details_cond('items', 'item_id', $row->item);
        foreach($details as $detail){
            // $item_price = $detail->sales_price;
            // $dept = $detail->department;
            $name = $detail->item_name;
            $markup = $detail->carton_role;
            $pack_size = $detail->carton_size;
            $cost = $detail->wholesale_cost;
            
        }
        $pack_price = $cost + ($cost * ($markup/100));
        $get_qty = new selects();
        $qtys = $get_qty->fetch_sum_double('inventory', 'quantity', 'store', $store, 'item', $row->item);
        foreach($qtys as $qty){
            $item_qty = $qty->total;
        }
        $total = $pack_price * $pack_size;
        if($pack_price == 0 || $pack_size == 0){
            echo "<script>alert('Carton/role price or carton size not set for this item! Can not proceed!')</script>";
        }else{
    ?>
    <div class="add_user_form priceForm" style="width:90%; padding:0!important">
        
        <section class="addUserForm" style="text-align:left; padding:0 0 5px 0; margin:0; width:100%;">
        <h3 style="background:var(--secondaryColor);">Sell role/carton for <?php echo strtoupper($name)?></h3>
            <div class="inputs">
                <div class="data item_head" style="width:auto;background:green">
                    <h4 title="available quantity"><?php echo $item_qty?></h4>
                    <input type="hidden" name="sales_id" id="sales_id" value="<?php echo $row->sales_id?>" required>
                    <input type="hidden" name="inv_qty" id="inv_qty" value="<?php echo $item_qty?>" required>
                </div>
                <div class="data" style="width:17%">
                    <label for="pack_qty">Carton/role Qty</label>
                    <input type="text" name="pack_qty" id="pack_qty" value="1">
                </div>
                <div class="data" style="width:17%">
                    <label for="qty">carton/role size</label>
                    <input type="text" name="pack_size" id="pack_size" value="<?php echo $pack_size?>" readonly style="background:#c4c4c4">
                </div>
                <div class="data" style="width:17%">
                    <label for="price">Markup (%)</label>
                   
                    <input type="text" name="pack_price" id="pack_price" value="<?php echo $markup?>">
                </div>
                <div class="data" style="width:17%">
                    <label for="total_amount">Total Amount (NGN)</label>
                    <input type="text" name="total_amount" id="total_amount" value="<?php echo $total?>" readonly style="background:#c4c4c4">
                </div>
                <div class="data" style="width:17%">
                    <button type="submit" id="change_price" name="change_price" onclick="sellPack('sell_carton.php')">Update </button>
                    <a href="javascript:void(0)" title="close form" style='background:red; padding:10px; border-radius:5px; color:#fff' onclick="closeForm()">Return</a>
                </div>
            </div>
        </section>   
    </div>
    
<?php
        }
    endforeach;
     }
    }    
?>