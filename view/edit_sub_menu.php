<div id="change_sub_menu" style="width:80%;">
<?php

    include "../classes/dbh.php";
    include "../classes/select.php";
    
    if(isset($_SESSION['success'])){
        echo $_SESSION['success'];
    }

?>

    <div class="info" style="width:80%"></div>
    <div class="displays allResults">
        <h2>Update sub-menu details</h2>
        <hr>
        <div class="search">
            <input type="search" id="searchSubmenu" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        </div>
        <table id="subMenuTable" class="searchTable">
            <thead>
                <tr style="background:var(--otherColor)">
                    <td>S/N</td>
                    <td>Menu</td>
                    <td>Sub-menu</td>
                    <td>Url</td>
                    <td>status</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
            <?php
                $n = 1;
                $select_cat = new selects();
                $rows = $select_cat->fetch_details('sub_menus');
                if(gettype($rows) == "array"){
                foreach($rows as $row):
            ?>
                <tr>
                    <td style="text-align:center;"><?php echo $n?></td>
                    
                    <td>
                        <?php 
                            //get menu
                            $get_dep = new selects();
                            $detail = $get_dep->fetch_details_group('menus', 'menu', 'menu_id', $row->menu);
                            echo $detail->menu;
                        ?>
                    </td>
                    <td>
                        <?php
                            echo $row->sub_menu;
                            
                        ?>
                    </td>
                    <td><?php echo $row->url?>.php</td>
                    <td>
                        <?php
                            if($row->status == 1){
                                echo "<p style='color:red'>Inactive</p>";
                            }else{
                                echo "<p style='color:green'>Activated</p>";
                            }
                        ?>
                    </td>
                    <td class="prices">
                        <a style="background:var(--moreColor)!important; color:#fff!important; padding:5px 6px; border-radius:5px;" href="javascript:void(0)"class="each_prices" onclick="getForm('<?php echo $row->sub_menu_id?>', 'get_sub_menu.php');"><i class="fas fa-pen"></i></a>
                    </td>
                </tr>
            <?php $n++; endforeach; }?>

            </tbody>

        </table>
        
        <?php
            if(gettype($rows) == "string"){
                echo "<p class='no_result'>'$rows'</p>";
            }
        ?>
    </div>
</div>