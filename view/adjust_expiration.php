<div id="adjust_expiration">
<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    
    if(isset($_SESSION['success'])){
        echo $_SESSION['success'];
    }

    
?>

    <div class="info" style="width:80%; margin:auto"></div>
    <div class="displays allResults" style="width:70%!important; margin:0 0 0 50px!important">
        <h2>Adjust Item expiration date</h2>
        <hr>
        <div class="search">
            <input type="search" id="searchGuestPayment" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        </div>
        <table id="priceTable" class="searchTable">
            <thead>
                <tr style="background:var(--otherColor)">
                    <td>S/N</td>
                    <td>Category</td>
                    <td>item</td>
                    <td>Quantity</td>
                    <!-- <td>Expiry date</td> -->
                    <td></td>
                </tr>
            </thead>
            <tbody>
            <?php
                $n = 1;
                $select_cat = new selects();
                $rows = $select_cat->fetch_details_negCondgroup('inventory', 'quantity', 0, 'store', $store, 'item');
                if(gettype($rows) == "array"){
                foreach($rows as $row):
            ?>
                <tr>
                    <td style="text-align:center;"><?php echo $n?></td>
                    
                <td>
                    <?php
                        //get item category first
                        $get_cat = new selects();
                        $item_cat = $get_cat->fetch_details_group('items', 'department', 'item_id', $row->item);
                        //get department name
                        $get_cat_name = new selects();
                        $cat_name = $get_cat_name->fetch_details_group('departments', 'department', 'department_id', $item_cat->department);
                        echo $cat_name->department;
                    ?>
                </td>
                <td style="color:var(--otherColor)"><?php 
                    //get item name
                    $get_name = new selects();
                    $name = $get_name->fetch_details_group('items', 'item_name', 'item_id', $row->item);
                    echo $name->item_name;
                ?></td>
                    <td style="text-align:center">
                        <?php 
                            //get totalbatch quantity
                            $get_qty = new selects();
                            $qtys = $get_qty->fetch_sum_double('inventory', 'quantity', 'item', $row->item, 'store', $store);
                            foreach($qtys as $qty){
                                echo $qty->total;
                            }     
                        ?>
                    </td>
                    <!-- <td>
                        <?php echo date("d-m-Y", strtotime($row->expiration_date));?>
                    </td> -->
                    <td class="prices">
                    <a style="padding:5px; border-radius:15px;background:var(--tertiaryColor);color:#fff;"href="javascript:void(0)" onclick="showPage('view_exp_batch.php?item=<?php echo $row->item?>')" title="view batches">view <i class="fas fa-eye"></i></a>
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