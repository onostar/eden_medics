<?php
    session_start();
    $item = htmlspecialchars(stripslashes($_POST['item']));
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    $toDate = htmlspecialchars(stripslashes($_POST['toDate']));
    $fromDate = htmlspecialchars(stripslashes($_POST['fromDate']));
    $_SESSION['toDate'] = $toDate;
    $_SESSION['fromDate'] = $fromDate;
    $get_item = new selects();
    $rows = $get_item->fetch_details_like2Cond('items', 'item_name', 'barcode', $item);
     if(gettype($rows) == 'array'){
        foreach($rows as $row):
        
    ?>

    <option onclick="checkStockinHistory('<?php echo $row->item_id?>')">
        <?php echo $row->item_name?>
    </option>
    
<?php
    endforeach;
     }else{
        echo "No resullt found";
     }
?>