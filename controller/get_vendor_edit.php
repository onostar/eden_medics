<?php
    session_start();
    $input= htmlspecialchars(stripslashes($_GET['input']));
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    $get_customer = new selects();
    $rows = $get_customer->fetch_details_like2Cond('vendors', 'vendor', 'phone', $input);
     if(gettype($rows) == 'array'){
        foreach($rows as $row):
        
    ?>

    <option onclick="showPage('edit_vendor.php?vendor=<?php echo $row->vendor_id?>')">
        <?php echo $row->vendor?>
    </option>
    
<?php
    endforeach;
     }else{
        echo "No resullt found";
     }
?>