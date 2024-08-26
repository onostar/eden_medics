<?php
    session_start();
    $input= htmlspecialchars(stripslashes($_GET['input']));
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    $get_customer = new selects();
    $rows = $get_customer->fetch_details_like2Cond('customers', 'customer', 'phone_numbers', $input);
     if(gettype($rows) == 'array'){
        foreach($rows as $row):
        
    ?>

    <option onclick="showPage('edit_customer.php?customer=<?php echo $row->customer_id?>')">
        <?php echo $row->customer?>
    </option>
    
<?php
    endforeach;
     }else{
        echo "No resullt found";
     }
?>