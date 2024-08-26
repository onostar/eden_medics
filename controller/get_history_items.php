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
            
        
    ?>
    <option onclick="getItemHistory('<?php echo $row->item_id?>')" name="<?php echo $row->item_id?>" value="<?php echo $row->item_id?>">
        <?php echo $row->item_name;?>
    </option>
    
<?php
    endforeach;
    }else{
        echo "No resullt found";
    }
?>