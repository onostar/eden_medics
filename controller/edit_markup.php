<?php
    session_start();    
    $store = $_SESSION['store_id'];
    
    // if(isset($_POST['change_prize'])){
        $item = htmlspecialchars(stripslashes($_POST['item_id']));
        $cost_price = htmlspecialchars(stripslashes($_POST['cost_price']));
        $markup = htmlspecialchars(stripslashes($_POST['markup']));
        $carton_size = htmlspecialchars(stripslashes($_POST['carton_size']));
        $carton_price = htmlspecialchars(stripslashes($_POST['carton_role']));

        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/update.php";
        //get store
    /* $get_store = new selects();
    $strs = $get_store->fetch_details_group('stores', 'store', 'store_id', $store);
    $store_name = $strs->store; */
        $change_price = new Update_table();
        $change_price->update_quadruple('items', 'wholesale_cost', $cost_price, 'markup', $markup, /* 'sales_price', $sales_price, 'pack_price', $pack_price, 'pack_size', $pack_size, 'wholesale', $wholesale, 'wholesale_pack', $wholesale_pack, */'carton_role', $carton_price, 'carton_size', $carton_size, 'item_id', $item);
        $update_price = new update_table();
        $update_price->update2cond('inventory', 'cost_price', 'item', 'store', $cost_price, $item, $store);
        if($change_price){
             echo "<div class='success'><p>Price changed successfully! <i class='fas fa-thumbs-up'></i></p></div>";
        }else{
            echo "<p style='background:red; color:#fff; padding:5px'>Filed to change price <i class='fas fa-thumbs-down'></i></p>";
        }
    // }