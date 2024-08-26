<div id="edit_item_price">
<?php
    session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/update.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        // echo $user_id;
    
    if(isset($_GET['item'])){
        $item = $_GET['item'];
        
        
        //get item details
        $get_item = new selects();
    $rows = $get_item->fetch_details_cond('items', 'item_id', $item);
     if(gettype($rows) == 'array'){
        foreach($rows as $row):
?>
    <div class="add_user_form priceForm">
        <h3 style="background:var(--primaryColor)">Edit percentage markup for <?php echo strtoupper($row->item_name)?></h3>
        <section class="addUserForm" style="text-align:left;">
            <div class="inputs" style="flex-wrap:wrap; gap:1rem;">
                <!-- <div class="data item_head"> -->
                    <input type="hidden" name="item_id" id="item_id" value="<?php echo $row->item_id?>" required>
                <div class="data" style="width:30%">
                    <label for="cost_price">Cost price (NGN)</label>
                    <input type="text" name="cost_price" id="cost_price" value="<?php echo $row->wholesale_cost?>">
                </div>
                <div class="data" style="width:30%">
                    <label for="markup">Unit Markup (%)</label>
                    <input type="text" name="markup" id="markup" value="<?php echo $row->markup?>">
                </div>
                <div class="data" style="width:30%">
                    <label for="pack_price">Carton markup (%)</label>
                    <input type="text" name="carton_role" id="carton_role" value="<?php echo $row->carton_role?>">
                </div>
                <div class="data" style="width:30%">
                    <label for="sales_price">Carton Qty</label>
                    <input type="text" name="carton_size" id="carton_size" value="<?php echo $row->carton_size?>">
                </div>
                <div class="data" style="width:30%">
                    <button type="submit" id="change_price" name="change_price" onclick="changeMarkup()">Save <i class="fas fa-save"></i></button>
                    <a href="javascript:void(0)" title="close form" style='background:red; padding:10px; border-radius:5px; color:#fff' onclick="showPage('manage_markup.php')">Return <i class='fas fa-angle-double-left'></i></a>
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
</div>