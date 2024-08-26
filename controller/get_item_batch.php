<?php
    session_start();
    $store = $_SESSION['store_id'];
    $item = htmlspecialchars(stripslashes($_POST['item']));
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_item = new selects();
    $rows = $get_item->fetch_details_like2Cond('items', 'item_name', 'barcode', $item);
     if(gettype($rows) == 'array'){
        foreach($rows as $row):
            //get item quantity from inventory
            $get_qty = new selects();
            $qtys = $get_qty->fetch_sum_double('inventory', 'quantity', 'item', $item, 'store', $store);
            if(gettype($qtys) == 'array'){
                foreach($qtys as $qty){
                    $quantity = $qty->quantity;
                }
            }
            if(gettype($qtys) == 'string'){
                $quantity = 0;
            }
        
    ?>
    <div class="results">
        <a href="javascript:void(0)"  onclick="showPage('view_batch.php?item=<?php echo $item?>')" title="view batches"><?php echo $row->item_name." (Price => ₦".$row->sales_price.", Quantity => ".$quantity.")"?></a>
    </div>
    <!-- <option onclick="showPage('adjust_qty_form.php?item=<?php echo $row->item_id?>')">
        <?php echo $row->item_name." (Price => ₦".$row->sales_price.", Quantity => ".$quantity.")"?>
    </option> -->
    
<?php
    endforeach;
     }else{
        echo "No resullt found";
     }
?>