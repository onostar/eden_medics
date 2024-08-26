<?php

    $store = htmlspecialchars(stripslashes($_POST['store']));
    /* $from = htmlspecialchars(stripslashes($_POST['from_date']));
    $to = htmlspecialchars(stripslashes($_POST['to_date'])); */

    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";
    
    //get store name
    $get_store = new selects();
    $str = $get_store->fetch_details_group('stores', 'store', 'store_id', $store);
    $store_name = $str->store;

    /* show store details */
?>
<div class="dashboard_all">
    <h3><i class="fas fa-home"></i> Dashboard for <span style="color:var(--secondaryColor);font-size:1rem"><?php echo $store_name?></span></h3>
    <!-- check other stores dashboard -->
    <div class="select_date">
        <!-- <form method="POST"> -->
        <section>
            <div class="from_to_date">
                <!-- <label>Select store</label><br> -->
                <select name="store" id="store" required>
                    <option value="<?php echo $store?>"><?php echo $store_name?></option>
                    <!-- get stores -->
                    <?php
                        $get_store = new selects();
                        $strs = $get_store->fetch_details('stores');
                        foreach($strs as $str){
                    ?>
                    <option value="<?php echo $str->store_id?>"><?php echo $str->store?></option>
                    <?php }?>
                </select>
            </div>
            <!-- <div class="from_to_date">
                <label>Select From Date</label><br>
                <input type="date" name="from_date" id="from_date"><br>
            </div>
            <div class="from_to_date">
                <label>Select to Date</label><br>
                <input type="date" name="to_date" id="to_date"><br>
            </div> -->
            <button type="submit" name="search_date" id="search_date" onclick="searchDashboard()" style="background:var(--primaryColor);width:auto!important;font-size:.9rem">View <i class="fas fa-eye"></i></button>
        </section>
    </div>
    <div id="dashboard">
        <div class="cards" id="card4">
            <a href="javascript:void(0)">
                <div class="infos">
                    <p><i class="fas fa-coins"></i> Daily Revenue</p>
                    <p>
                    <?php
                        $get_sales = new selects();
                        $rows = $get_sales->fetch_sum_curdatecon('payments', 'amount_paid', 'post_date', 'store', $store);
                        foreach($rows as $row){
                            echo "₦".number_format($row->total);
                        }
                    ?>
                    </p>
                </div>
            </a>
        </div> 
        <div class="cards" id="card3">
            <a href="javascript:void(0)" class="page_navs">
                <div class="infos">
                    <p><i class="fas fa-users"></i> Cost of sales</p>
                    <p>
                    <?php
                        $get_cost = new selects();
                        $costs = $get_cost->fetch_sum_curdate2Con('sales', 'cost', 'date(post_date)', 'sales_status', 2, 'store', $store);
                        foreach($costs as $cost){
                            echo "₦".number_format($cost->total, 2);
                        }
                    ?>
                    </p>
                </div>
            </a>
        </div> 
        <div class="cards" id="card2" style="background: var(--moreColor)">
            <a href="javascript:void(0)" class="page_navs">
                <div class="infos">
                    <p><i class="fas fa-hand-holding-dollar"></i> Daily Expense</p>
                    <p>
                    <?php
                        $get_exp = new selects();
                        $exps = $get_exp->fetch_sum_curdateCon('expenses', 'amount', 'date(expense_date)', 'store', $store);
                        foreach($exps as $exp){
                            echo "₦".number_format($exp->total, 2);
                        }
                    ?>
                    </p>
                </div>
            </a>
        </div> 
        <div class="cards" id="card0">
            <a href="javascript:void(0)" class="page_navs">
                <div class="infos">
                    <p><i class="fas fa-money-check"></i> Net Profit</p>
                    <p>
                    <?php
                        //get total sales
                        $get_sales = new selects();
                        $rows = $get_sales->fetch_sum_curdateCon('payments', 'amount_paid', 'post_date', 'store', $store);
                        foreach($rows as $row){
                            $sales = $row->total;
                        }
                        //get cost of sales
                        $get_cost = new selects();
                        $costs = $get_cost->fetch_sum_curdate2Con('sales', 'cost', 'date(post_date)', 'sales_status', 2, 'store', $store);
                        foreach($costs as $cost){   $sales_cost = $cost->total;
                        }
                        //get expenses
                        $get_exp = new selects();
                        $exps = $get_exp->fetch_sum_curdateCon('expenses', 'amount', 'date(expense_date)', 'store', $store);
                        foreach($exps as $exp){
                            $expense = $exp->total;
                        }

                        //profit
                        $profit = $sales - ($sales_cost + $expense);
                        echo "₦".number_format($profit, 2);
                    ?>
                    </p>
                </div>
            </a>
        </div> 
        
        
    </div>
    
<!-- management summary -->
<div id="paid_receipt" class="management">
    <hr>
    <div class="daily_monthly">
        <!-- daily revenue summary -->
        <div class="daily_report allResults">
            <h3 style="background:var(--otherColor);width:100%">Daily Encounters</h3>
            <table>
                <thead>
                    <tr>
                        <td>S/N</td>
                        <td>Date</td>
                        <td>Customers</td>
                        <td>Revenue</td>
                    </tr>
                </thead>
                <?php
                    $n = 1;
                    $get_daily = new selects();
                    $dailys = $get_daily->fetch_daily_sales($store);
                    if(gettype($dailys) == "array"){
                    foreach($dailys as $daily):

                ?>
                <tbody>
                    <tr>
                        <td><?php echo $n?></td>
                        <td><?php echo date("jS M, Y",strtotime($daily->post_date))?></td>  
                        <td style="text-align:center; color:var(--otherColor)"><?php echo $daily->customers?></td>
                        <td style="color:green;"><?php echo "₦".number_format($daily->revenue)?></td>
                    </tr>
                </tbody>
                <?php $n++; endforeach; }?>

                
            </table>
            <?php
                if(gettype($dailys) == "string"){
                    echo "<p class='no_result'>'$details'</p>";
                }
            ?>
        </div>
        <!-- monthly revenue summary -->
        <div class="monthly_report allResults">
            <h3 style="width:100%">Monthly Encounters</h3>
            <table>
                <thead>
                    <tr>
                        <td>S/N</td>
                        <td>Month</td>
                        <td>Customers</td>
                        <td>Amount</td>
                        <td>Daily Average</td>
                    </tr>
                </thead>
                <?php
                    $n =1;
                    $get_monthly = new selects();
                    $monthlys = $get_monthly->fetch_monthly_sales($store);
                    if(gettype($monthlys) == "array"){
                    foreach($monthlys as $monthly):

                ?>
                <tbody>
                    <tr>
                        <td><?php echo $n?></td>
                        <td><?php echo date("M, Y", strtotime($monthly->post_date))?></td>
                        <td style="text-align:center; color:var(--otherColor"><?php echo $monthly->customers?></td>
                        <td style="text-align:center; color:green"><?php echo "₦".number_format($monthly->revenue)?></td>
                        <td style="text-align:center; color:red"><?php
                            $average = $monthly->revenue/$monthly->daily_average;
                            echo "₦".number_format($average, 2);
                        ?></td>
                    </tr>
                </tbody>
                <?php $n++; endforeach; }?>

                
            </table>
            <?php 
                if(gettype($monthlys) == "string"){
                    echo "<p class='no_result'>'$monthlys'</p>";
                }
            ?>
        </div>
        
    </div>
</div>