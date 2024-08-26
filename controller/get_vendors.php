<?php

    $vendor = htmlspecialchars(stripslashes($_POST['vendor']));

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_vendor = new selects();
    $rows = $get_vendor->fetch_details_like('vendors', 'vendor', $vendor);
?>
    <!-- <option value=""selected>Select a room</option> -->
<?php
    if(gettype($rows) == 'array'){
        foreach ($rows as $row) {
            

?>
    <option value="<?php echo $row->vendor_id?>"><?php echo $row->vendor?></option>
<?php
        }   
    }else{
        echo "<option value=''selected>Please enter a value</option>";
    }
?>