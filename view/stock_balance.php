<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    $role = $_SESSION['role'];
    //get store
    $get_store = new selects();
    $strs = $get_store->fetch_details_group('stores', 'store', 'store_id', $store);
    $store_name = $strs->store;

?>
    <div class="info"></div>
<div class="displays allResults" id="bar_items">
    <h2>Store stock balance</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Stock balance')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Category</td>
                <td>Item name</td>
                <td>Quantity</td>
                <?php if($role == "Admin"){?>
                <td>Unit cost</td>
                <td>Total cost</td>
                <?php }?>
                <!-- <td>Expiry date</td> -->
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_items = new selects();
                $details = $get_items->fetch_details_negCondgroup('inventory', 'quantity', 0, 'store', $store, 'item');
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td style="color:var(--moreClor);">
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
                <td style="text-align:center;color:green">
                    <?php
                        //get totalbatch quantity
                        $get_qty = new selects();
                        $qtys = $get_qty->fetch_sum_double('inventory', 'quantity', 'item', $detail->item, 'store', $store);
                        foreach($qtys as $qty){
                            echo $qty->total;
                        }                        
                    ?>
            </td>
                <?php if($role == "Admin"){?>
                <td>
                    <?php 

                        echo "₦".number_format($detail->cost_price, 2);
                    ?>
                </td>
                <td>
                    <?php 
                        //get totalbatch quantity
                        $get_qty = new selects();
                        $qtys = $get_qty->fetch_sum_double('inventory', 'quantity', 'item', $detail->item, 'store', $store);
                        foreach($qtys as $qty){
                            $total_qty = $qty->total;
                        }   
                        $total_cost = $detail->cost_price * $total_qty;
                        echo "₦".number_format($total_cost, 2);
                    ?>
                </td>
                <?php }?>
                <!-- <td>
                    <?php
                        echo date("d-m-Y", strtotime($detail->expiration_date));
                    ?>
                </td> -->
                <td>
                    <a style="padding:5px; border-radius:15px;background:var(--tertiaryColor);color:#fff;"href="javascript:void(0)" onclick="showPage('view_item_batch.php?item=<?php echo $detail->item?>')" title="view batches">view <i class="fas fa-eye"></i></a>
                </td>
            </tr>
            
            <?php $n++; endforeach;}?>
        </tbody>
    </table>

    
    <?php
        if(gettype($details) == "string"){
            echo "<p class='no_result'>'$details'</p>";
            
        }
        if($role == "Admin"){
            // get sum
            $get_total = new selects();
            $amounts = $get_total->fetch_sum_2colCond('inventory', 'cost_price', 'quantity', 'store', $store);
            foreach($amounts as $amount){
                $total_amount = $amount->total;
            }
            // $total_worth = $total_amount * $total_qty;
            echo "<p class='total_amount'>Store worth: ₦".number_format($total_amount, 2)."</p>";
        }

        
    ?>
</div>