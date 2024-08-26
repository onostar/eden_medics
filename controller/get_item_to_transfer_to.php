<?php
    session_start();
    $store = $_SESSION['store_id'];
    $item = htmlspecialchars(stripslashes($_POST['item_to']));
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_item = new selects();
    $rows = $get_item->fetch_details_like2Cond('items', 'item_name', 'barcode', $item);
     if(gettype($rows) == 'array'){
        foreach($rows as $row):
            //get item quantity from inventory
            $get_qty = new selects();
            $qtys = $get_qty->fetch_details_2cond('inventory', 'store', 'item', $store, $row->item_id);
            if(gettype($qtys) == 'array'){
                foreach($qtys as $qty){
                    $quantity = $qty->quantity;
                }
            }
            if(gettype($qtys) == 'string'){
                $quantity = 0;
            }
        
    ?>
    <option onclick="addItemToTransferTo('<?php echo $row->item_id?>', '<?php echo $row->item_name?>')">
        <?php echo $row->item_name." (Quantity => ".$quantity.")"?>
    </option>
    
<?php
    endforeach;
     }else{
        echo "No resullt found";
     }
?>