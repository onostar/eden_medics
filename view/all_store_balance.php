<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    $role = $_SESSION['role'];


?>
    <div class="info"></div>
<div class="displays allResults" id="bar_items" style="width:70%!important;margin:20px 50px!important">
    <div class="select_date">
        <!-- <form method="POST"> -->
        <section style="margin:0">    
            <div class="from_to_date" style="width:40%">
                <label>Select a store to view</label><br>
                <select name="store" id="store" onchange="getStockBalance(this.value)">
                    <option value=""selected>Select store</option>
                    <?php
                        //get stores
                        $get_store = new selects();
                        $strs = $get_store->fetch_details('stores');
                        foreach($strs as $str){
                    ?>
                        <option value="<?php echo $str->store_id?>"><?php echo $str->store?></option>
                    <?php }?>
                </select>
            </div>
        </section>
    </div>
    <div class="store_balance">
        <h2>All Store stock balance</h2>
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
                    <td>Unit cost</td>
                    <td>Total cost</td>
                </tr>
            </thead>
            <tbody>
                <?php
                    $n = 1;
                    $get_items = new selects();
                    $details = $get_items->fetch_AllStock();
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
                    <td style="text-align:center;color:green"><?php echo $detail->total?></td>
                    <td>
                        <?php 
                            echo "₦".number_format($detail->cost_price, 2);
                        ?>
                    </td>
                    <td>
                        <?php 
                            $total_cost = $detail->cost_price * $detail->total;
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
            if($role == "Admin"){
                // get sum
                $get_total = new selects();
                $amounts = $get_total->fetch_sum_2col('inventory', 'cost_price', 'quantity');
                foreach($amounts as $amount){
                    $total_amount = $amount->total;
                }
                // $total_worth = $total_amount * $total_qty;
                echo "<p class='total_amount'>Total Store worth: ₦".number_format($total_amount, 2)."</p>";
            }

            
        ?>
    </div>
</div>