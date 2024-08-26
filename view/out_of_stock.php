<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
?>
    <div class="info"></div>
<div class="displays allResults" id="out_stock" style="width:60%!important; margin:5px 50px!important;">
    <h2>Items with zero quantity</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Out of stock list')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--otherColor)">
                <td>S/N</td>
                <td>Category</td>
                <td>Item name</td>
                <td>Quantity</td>
                <td>Unit cost</td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_items = new selects();
                $details = $get_items->fetch_details_2cond('inventory', 'store', 'quantity', $store, 0);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td>
                    <?php
                        //get item category first
                        $get_cat = new selects();
                        $item_cat = $get_cat->fetch_details_group('items', 'department', 'item_id', $detail->item);
                        //get department name
                        $get_cat_name = new selects();
                        $cat_name = $get_cat_name->fetch_details_group('departments', 'department', 'department_id', $item_cat->department);
                        echo $cat_name->department;
                    ?>
                </td>
                <td style="color:var(--otherColor)"><?php 
                    //get item name
                    $get_name = new selects();
                    $name = $get_name->fetch_details_group('items', 'item_name', 'item_id', $detail->item);
                    echo $name->item_name;
                ?></td>
                <td style="text-align:center; color:red"><?php echo $detail->quantity?></td>
                <td>
                    <?php 
                        echo "₦".number_format($detail->cost_price, 2);
                    ?>
                </td>
                
                
            </tr>
            
            <?php $n++; endforeach;}?>
        </tbody>
    </table>

    
    <?php
        if(gettype($details) == "string"){
            echo "<p class='no_result'>'$details'</p>";
        }

        
    ?>
</div>