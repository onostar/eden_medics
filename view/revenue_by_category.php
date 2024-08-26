<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";


?>
<div id="revenueReport" class="displays management">
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
            <button type="submit" name="search_date" id="search_date" onclick="search('search_revenue_cat.php')">Search <i class="fas fa-search"></i></button>
</section>
    </div>
<div class="displays allResults new_data" id="revenue_report" style="width:60%">
    <h2>Revenue by category for today</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchCheckout" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Revenue by category')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--otherColor)">
                <td>S/N</td>
                <td>Category</td>
                <td>Revenue</td>
                <td>Cost of sales</td>
                <td>Profit</td>
                
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_revenue_cat($store);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td><a style="color:#222;" href="javascript:void(0)" title="View items" onclick="viewItems('<?php echo $detail->department?>')"><?php 
                    $get_cat = new Selects();
                    $row = $get_cat->fetch_details_group('departments', 'department', 'department_id', $detail->department);
                    echo $row->department?></a></td>
                <td><?php echo "₦".number_format($detail->total, 2)?></td>
                <td style="color:red"><?php echo "₦".number_format($detail->total_cost, 2)?></td>
                <td style="color:green;">
                    <?php
                        $profit = $detail->total - $detail->total_cost;
                        echo "₦".number_format($profit, 2)
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
        $amounts = $get_total->fetch_revenue($store);
        foreach($amounts as $amount){
            $revenue = $amount->total;
            $costSales = $amount->total_cost;
            $total_profit = $revenue - $costSales;
            echo "<p class='total_amount' style='background:var(--moreColor); color:#fff; text-decoration:none; padding:10px; width:30%; float:right'>Gross Profit: ₦".number_format($total_profit, 2)."</p>";
        }
    ?>
    
</div>
<div class="category_info allResults">

</div>
<script src="../jquery.js"></script>
<script src="../script.js"></script>