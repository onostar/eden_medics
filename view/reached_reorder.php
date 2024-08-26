<?php
    session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
    $store = $_SESSION['store_id'];

?>
    <div class="info"></div>
<div class="displays allResults" id="bar_items">
    <h2>Items that have reached reorder level</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Items reached reorder level')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Category</td>
                <td>Item name</td>
                <td>Quantity</td>
                <td>Reorder level</td>
                <td>Unit cost</td>
                <td>Total cost</td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_items = new selects();
                $details = $get_items->fetch_reorder_level($store);
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
                <td style="text-align:center"><?php echo $detail->total_quantity?></td>
                <td style="text-align:center; color:red"><?php echo $detail->reorder_level?></td>
                <td>
                    <?php 
                        echo "₦".number_format($detail->cost_price, 2);
                    ?>
                </td>
                <td>
                    <?php 
                        $total_cost = $detail->cost_price * $detail->total_quantity;
                        echo "₦".number_format($total_cost, 2);
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

        // get sum
        $get_total = new selects();
        $amounts = $get_total->fetch_lesser_SumCon('inventory', 'quantity', 'reorder_level', 'store', $store, 'quantity', 'cost_price');
        foreach($amounts as $amount){
            $total_amount = $amount->total;
        }
        // $total_worth = $total_amount * $total_qty;
        echo "<p class='total_amount' style='color:#fff; background:var(--primaryColor); float:right; padding:10px; text-decoration:none;'>Total Worth: ₦".number_format($total_amount, 2)."</p>";
    ?>
</div>