<?php

    $menu = htmlspecialchars(stripslashes($_POST['menu']));
    // echo $menu;
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_cat = new selects();
    $rows = $get_cat->fetch_details_negCond('sub_menus', 'status', 1, 'menu', $menu);
?>
<option value=""selected>Select sub-menu</option>
<?php
    if(gettype($rows) == 'array'){
        foreach ($rows as $row) {
            

?>
    <option value="<?php echo $row->sub_menu_id?>"><?php echo $row->sub_menu?></option>
<?php
        }   
    }else{
        echo "<option value=''selected>$menu</option>";
    }
?>