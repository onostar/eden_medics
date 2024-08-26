<?php

    $department = htmlspecialchars(stripslashes($_POST['department']));

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_cat = new selects();
    $rows = $get_cat->fetch_details_cond('categories', 'department', $department);
?>
<?php
    if(gettype($rows) == 'array'){
        foreach ($rows as $row) {
            

?>
    <option value="<?php echo $row->category_id?>"><?php echo $row->category?></option>
<?php
        }   
    }else{
        echo "<option value=''selected>No category available</option>";
    }
?>