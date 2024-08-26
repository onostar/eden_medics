<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";


?>
<div id="highestSelling" class="displays management">
    <div class="select_date">
        <!-- <form method="POST"> -->
        <section>    
            <div class="from_to_date">
                <label>Select From Date</label><br>
                <input type="date" name="from_date" id="from_date"><br>
            </div>
            <div class="from_to_date">
                <label>Select to Date</label><br>
                <input type="date" name="to_date" id="to_date"><br>
            </div>
            <button type="submit" name="search_date" id="search_date" onclick="search('search_fastest.php')">Search <i class="fas fa-search"></i></button>
</section>
    </div>
<div class="displays allResults new_data" id="revenue_report" style="width:60%!important; margin:0 50px!important">
    <h2>Today's Fast selling items (Quantity)</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchCheckout" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Fast Selling item')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--otherColor)">
                <td>S/N</td>
                <td>Item</td>
                <td>Quantity</td>
                <td>Amount</td>
                <td>Category</td>
                
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_curdateGroMany1c('sales', 'item', 'quantity', 'total_amount', 'date(post_date)', 'sales_status', 2, 'store', $store, 'item', 'SUM(quantity)');
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td>
                    <?php
                        //get item name
                        $get_name = new selects();
                        $names = $get_name->fetch_details_group('items', 'item_name', 'item_id', $detail->item);
                            echo $names->item_name;
                    ?>
                </td>
                <td style="color:var(--primaryColor); text-align:center"><?php echo $detail->column1?></td>
                <td style="color:green"><?php echo "₦".number_format($detail->column2, 2)?></td>
                <td>
                    <?php
                        //get category
                        $get_cat = new selects();
                        $row_id = $get_cat->fetch_details_group('items', 'department', 'item_id', $detail->item);
                        $cat_id = $row_id->department;
                        $get_cat_name = new selects();
                        $cat_name = $get_cat->fetch_details_group('departments', 'department', 'department_id', $cat_id);
                        echo $cat_name->department;

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

<script src="../jquery.js"></script>
<script src="../script.js"></script>