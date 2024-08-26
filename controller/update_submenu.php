<?php
    session_start();    
    // if(isset($_POST['change_prize'])){
        $id = htmlspecialchars(stripslashes($_POST['sub_menu_id']));
        $menu = htmlspecialchars(stripslashes($_POST['menu']));
        $sub_menu = ucwords(htmlspecialchars(stripslashes($_POST['sub_menu'])));
        $url = htmlspecialchars(stripslashes($_POST['url']));
        $status = htmlspecialchars(stripslashes($_POST['status']));

        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/update.php";

        $update = new Update_table();
        $update->update_quadruple('sub_menus', 'menu', $menu, 'sub_menu', $sub_menu, 'url', $url, 'status', $status, 'sub_menu_id', $id);
        if($update){
             echo "<div class='success'><p>Sub-menu updated successfully! <i class='fas fa-thumbs-up'></i></p></div>";
        }else{
            echo "<p style='background:red; color:#fff; padding:5px'>Failed to update sub-menu <i class='fas fa-thumbs-down'></i></p>";
        }
    // }