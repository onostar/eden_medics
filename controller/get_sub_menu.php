<?php

    if (isset($_GET['item_id'])){
        $id = $_GET['item_id'];
    

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_item = new selects();
    $rows = $get_item->fetch_details_cond('sub_menus', 'sub_menu_id', $id);
     if(gettype($rows) == 'array'){
        foreach($rows as $row):
        //get current menu name of item
        $get_menu = new selects();
        $menuss = $get_menu->fetch_details_group('menus', 'menu', 'menu_id', $row->menu);
        $menu_name = $menuss->menu;
    ?>
    <div class="add_user_form priceForm" style="width:100%">
        <h3 style="background:var(--primaryColor)">Update <?php echo strtoupper($row->sub_menu)?> details</h3>
        <section class="addUserForm" style="text-align:left">
            <div class="inputs">
                <!-- <div class="data item_head"> -->
                    <input type="hidden" name="sub_menu_id" id="sub_menu_id" value="<?php echo $row->sub_menu_id?>" required>
                <div class="data" style="width:30%">
                    <label for="menu" style="text-align:left!important;">Select menu</label>
                    <select name="menu" id="menu" required>
                        <option value="<?php echo $row->menu?>"selected required><?php echo $menu_name?></option>
                        <?php
                            $get_dep = new selects();
                            $all_menus = $get_dep->fetch_details('menus');
                            foreach($all_menus as $all_men){
                        ?>
                        <option value="<?php echo $all_men->menu_id?>"><?php echo $all_men->menu?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="data" style="width:30%">
                    <label for="sub-menu"> Sub-menu</label>
                    <input type="text" name="sub_menu" id="sub_menu" value="<?php echo $row->sub_menu?>">
                </div>
                <div class="data" style="width:30%">
                    <label for="url"> Sub-menu Url</label>
                    <input type="text" name="url" id="url" value="<?php echo $row->url?>">
                </div>
                <div class="data" style="width:30%">
                    <label for="status"> Status</label>
                    <input type="text" name="status" id="status" value="<?php echo $row->status?>">
                </div>
                <div class="data">
                    <button type="submit" id="change_category" name="change_category" style="font-size:.8rem"onclick="updateSubMenu()">Update <i class="fas fa-save"></i></button>
                    <a href="javascript:void(0)" title="close form" style='background:red; padding:10px; border-radius:5px; color:#fff' onclick="closeForm()">Return <i class='fas fa-angle-double-left'></i></a>
                </div>
            </div>
        </section>   
    </div>
    
<?php
    endforeach;
     }
    }    
?>