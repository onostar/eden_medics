<?php
    session_start();
    $store = $_SESSION['store_id'];
    // if(isset($_SESSION('user_id'))){
        $user = $_SESSION['user_id'];
    if (isset($_GET['sales_id'])){
        $id = $_GET['sales_id'];
    

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_item = new selects();
    $rows = $get_item->fetch_details_cond('sales', 'sales_id', $id);
     if(gettype($rows) == 'array'){
        foreach($rows as $row){
            //ge item name
            $get_name = new selects();
            $names = $get_name->fetch_details_group('items', 'item_name', 'item_id', $row->item);
            $name = $names->item_name;
            
        
    ?>
    <div class="add_user_form priceForm" style="width:100%; margin:5px 0;">
        <h3 style="background:var(--primaryColor); text-align:left">Return <?php echo strtoupper($name)?> sales from Invoice (<?php echo $row->invoice?>)</span></h3>
        <section class="addUserForm" style="text-align:left; width:100%;margin:0">
            <div class="inputs" style="gap:0; justify-content:none">
                <div class="data item_head" style="width:15%;">
                    <h4>Qty sold => <?php  echo $row->quantity?></h4>
                </div>
                <input type="hidden" name="sold_qty" id="sold_qty" value="<?php echo $row->quantity?>">
                <input type="hidden" name="user_id" id="user_id" value="<?php echo $user?>" required>
                <input type="hidden" name="store" id="store" value="<?php echo $store?>">
                <input type="hidden" name="sales_id" id="sales_id" value="<?php echo $row->sales_id?>" required>
                <input type="hidden" name="item" id="item" value="<?php echo $row->item?>" required>
                <div class="data" style="width:20%; margin:0;">
                    <label for="qty">Return Quantity</label>
                    <input type="text" name="quantity" id="quantity" value="<?php echo $row->quantity?>">
                </div>
                <div class="data" style="width:20%; margin:0;">
                    <label for="expiration">Expiry date</label>
                    <input type="date" name="expiration" id="expiration">
                </div>
                <div class="data" style="width:20%">
                    <label for="reason">Reason for return</label>
                    <input type="text" name="reason" id="reason" placeholder="Input reason for return">
                </div>
                <div class="data" style="width:15%;  margin:0;">
                    <button type="submit" id="return_sales" name="return_sales" onclick="returnSales()" style="background:green;text-align:center;padding:8px;font-size:.8rem;margin:2px"><i class="fas fa-arrow-right-rotate"></i></button>
                    <a href="javascript:void(0)" title="close form" style='background:red; padding:10px; border-radius:5px; color:#fff' onclick="closeForm()">CLose <i class='fas fa-angle-double-left'></i></a>
                </div>
            </div>
        </section>   
    </div>
    
<?php
    }
     }
    } 
// }
?>