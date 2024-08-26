<div id="edit_item_price">
<?php

    include "../classes/dbh.php";
    include "../classes/select.php";
    
    if(isset($_SESSION['success'])){
        echo $_SESSION['success'];
    }

?>

    <div class="info" style="width:100%; margin:0!important"></div>
    <div class="displays allResults" style="width:80%;">
        <h2>Manage item prices</h2>
        <hr>
        <!-- <div class="search">
            <input type="search" id="searchGuestPayment" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        </div>
        <table id="priceTable" class="searchTable">
            <thead>
                <tr style="background:var(--otherColor)">
                    <td>S/N</td>
                    <td>item</td>
                    <td>pack size</td>
                    <td>Cost Price</td>
                    <td>Retail Price</td>
                    <td>Retail pack</td>
                    <td>Wholesale</td>
                    <td>Wholesale Pack</td>
                    <td></td>
                </tr>
            </thead>

            <tbody>
            <?php
                $n = 1;
                $select_cat = new selects();
                $rows = $select_cat->fetch_details('items');
                if(gettype($rows) == "array"){
                foreach($rows as $row):
            ?>
                <tr>
                    <td style="text-align:center;"><?php echo $n?></td>
                    
                  
                    <td><?php echo $row->item_name?></td>
                    <td style="color:otherColor; text-align:center"><?php echo $row->pack_size?></td>
                    <td>
                        <?php echo "₦ ". number_format($row->cost_price);?>
                    </td>
                    <td>
                        <?php echo "₦ ". number_format($row->sales_price);?>
                    </td>
                    <td>
                        <?php echo "₦ ". number_format($row->pack_price);?>
                    </td>
                    <td>
                        <?php echo "₦ ". number_format($row->wholesale);?>
                    </td>
                    <td>
                        <?php echo "₦ ". number_format($row->wholesale_pack);?>
                    </td>
                    <td class="prices">
                        <a style="background:var(--moreColor)!important; color:#fff!important; padding:5px 8px; border-radius:5px;" href="javascript:void(0)" title="modify price" data-form="check<?php echo $row->item_id?>" class="each_prices" onclick="getForm('<?php echo $row->item_id?>', 'get_item_details.php');"><i class="fas fa-pen"></i></a>
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
    </div> -->
            <section class="addUserForm">
                <div class="add_user_form" style="width:50%; margin:10px 0; box-shadow:none">
                    <h3 style="background:var(--primaryColor); color:#fff; text-align:left!important;" >Change item price </h3>
                    <div class="inputs">
                        <!-- bar items form -->
                        <div class="data" id="bar_items" style="width:100%; margin:2px 0">
                            <!-- <label for="item"> Search Items</label> -->
                            <input type="text" name="item" id="item" required placeholder="Search item" onkeyup="getItemDetails(this.value, 'get_items_price.php')">
                            <div id="sales_item">
                                
                            </div>
                        </div>
                    
                    </div>
                </div>
            </section>
</div>