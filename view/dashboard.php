<div id="general_dashboard">
<div class="dashboard_all">
    <h3><i class="fas fa-home"></i> Dashboard for <span style="color:var(--secondaryColor);font-size:1rem"><?php echo $store?></span></h3>
    <?php 
        if($role === "Admin"){
    ?>
    
    <div id="dashboard">
        <div class="cards" id="card4">
            <a href="javascript:void(0)" onclick="showPage('revenue_report.php')">
                <div class="infos">
                    <p><i class="fas fa-coins"></i> Daily Revenue</p>
                    <p>
                    <?php
                        $get_sales = new selects();
                        $rows = $get_sales->fetch_sum_curdatecon('payments', 'amount_paid', 'post_date', 'store', $store_id);
                        foreach($rows as $row){
                            $amount = $row->total;
                        }
                        //if credit was sold
                        $get_credit = new selects();
                        $credits = $get_credit->fetch_sum_curdate2Con('payments', 'amount_due', 'post_date', 'payment_mode', 'Credit', 'store', $store_id);
                        if(gettype($credits) === "array"){
                            foreach($credits as $credit){
                                $owed_amount = $credit->total;
                            }
                            $total_revenue = $owed_amount + $amount;
                            echo "₦".number_format($total_revenue, 2);

                        }
                        //if no credit sales
                        if(gettype($credits) == "string"){
                            echo "₦".number_format($amount, 2);
                            
                        }
                    ?>
                    </p>
                </div>
            </a>
        </div> 
        <div class="cards" id="card1">
            <a href="javascript:void(0)" class="page_navs">
                <div class="infos">
                    <p><i class="fas fa-users"></i> Cost of sales</p>
                    <p>
                    <?php
                        $get_cost = new selects();
                        $costs = $get_cost->fetch_sum_curdate2Con('sales', 'cost', 'date(post_date)', 'sales_status', 2, 'store', $store_id);
                        foreach($costs as $cost){
                            echo "₦".number_format($cost->total, 2);
                        }
                    ?>
                    </p>
                </div>
            </a>
        </div> 
        <div class="cards" id="card2" style="background: var(--moreColor)">
            <a href="javascript:void(0)" class="page_navs" onclick="showPage('expense_report.php')">
                <div class="infos">
                    <p><i class="fas fa-hand-holding-dollar"></i> Daily Expense</p>
                    <p>
                    <?php
                        $get_exp = new selects();
                        $exps = $get_exp->fetch_sum_curdateCon('expenses', 'amount', 'date(expense_date)', 'store', $store_id);
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
                        $rows = $get_sales->fetch_sum_curdateCon('payments', 'amount_paid', 'post_date', 'store', $store_id);
                        foreach($rows as $row){
                            $sales = $row->total;
                        }
                        //get cost of sales
                        $get_cost = new selects();
                        $costs = $get_cost->fetch_sum_curdate2Con('sales', 'cost', 'date(post_date)', 'sales_status', 2, 'store', $store_id);
                        foreach($costs as $cost){   $sales_cost = $cost->total;
                        }
                        //get expenses
                        $get_exp = new selects();
                        $exps = $get_exp->fetch_sum_curdateCon('expenses', 'amount', 'date(expense_date)', 'store', $store_id);
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
            <!-- <a href="javascript:void(0)" class="page_navs" onclick="showPage('pay_debt.php')">
                <div class="infos">
                    <p><i class="fas fa-calendar"></i> Receivables</p>
                    <p>
                    <?php
                        $receivables = new selects();
                        $dues = $receivables->fetch_sum_double('debtors', 'amount', 'debt_status', 0, 'store', $store_id);
                        foreach($dues as $due){
                            echo "₦".number_format($due->total);
                        }
                    ?>
                    </p>
                </div>
            </a> -->
        </div> 
        
        
    </div>
    <?php
        }else{
    ?>
    <div id="dashboard">
    <div class="cards" id="card0">
            <a href="javascript:void(0)" class="page_navs">
                <div class="infos">
                    <p><i class="fas fa-users"></i> Customers</p>
                    <p>
                    <?php
                        //get total customers
                       $get_cus = new selects();
                       $customers =  $get_cus->fetch_count_2condDateGro('sales', 'sales_status', 2, 'posted_by', $user_id, 'post_date', 'invoice');
                       echo $customers;
                    ?>
                    </p>
                </div>
            </a>
        </div> 
        <div class="cards" id="card4">
            <a href="javascript:void(0)" onclick="showPage('expire_soon.php')">
                <div class="infos">
                    <p><i class="fas fa-coins"></i> Soon to expire</p>
                    <p>
                        <?php
                            $get_soon_expired = new selects();
                            $soon_expired = $get_soon_expired->fetch_expire_soon('inventory', 'expiration_date', 'quantity', 'store', $store_id);
                            echo $soon_expired;
                        ?>
                    </p>
                </div>
            </a>
        </div> 
        <div class="cards" id="card3">
            <a href="javascript:void(0)" onclick="showPage('expired_items.php')" class="page_navs">
                <div class="infos">
                    <p><i class="fas fa-money-check"></i> Expired items</p>
                    <p>
                        <?php
                            $get_expired = new selects();
                            $expired = $get_expired->fetch_expired('inventory', 'expiration_date', 'quantity', 'store', $store_id);
                            echo $expired;
                        ?>
                    </p>
                </div>
            </a>
        </div> 
        <div class="cards" id="card2" style="background: var(--moreColor)">
            <a href="javascript:void(0)" class="page_navs" onclick="showPage('reached_reorder.php')">
                <div class="infos">
                    <p><i class="fas fa-hand-holding-dollar"></i> Out of stock</p>
                    <p>
                        <?php
                            $out_stock = new selects();
                            $stock = $out_stock->fetch_count_2cond('inventory', 'quantity', 0, 'store', $store_id);
                            echo $stock;
                        ?>
                    </p>
                </div>
            </a>
        </div> 
            
    </div>
    <?php }?>
</div>
<?php 
    if($role === "Admin"){
?>
<!-- management summary -->
<div id="paid_receipt" class="management">
    <hr>
    <div class="daily_monthly">
        <!-- daily revenue summary -->
        <div class="daily_report allResults">
            <h3 style="background:var(--otherColor)">Daily Sales Record</h3>
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
                    $dailys = $get_daily->fetch_daily_sales($store_id);
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
                    echo "<p class='no_result'>'$dailys'</p>";
                }
            ?>
        </div>
        <!-- monthly revenue summary -->
        <div class="monthly_report allResults">
            <div class="chart">
                <!-- chart for technical group -->
                <?php
                $get_monthly = new selects();
                $monthlys = $get_monthly->fetch_monthly_sales($store_id);
                if(gettype($monthlys) == "array"){
                    foreach($monthlys as $monthly){
                        $revenue[] = $monthly->revenue;
                        $month[] = date("M, Y", strtotime($monthly->post_date));
                    }
                }
                ?>
                <h3 style="background:var(--moreColor)">Monthly statistics</h3>
                <canvas id="chartjs_bar2"></canvas>
            </div>
            <div class="monthly_encounter">
                <h3>Monthly Sales Record</h3>
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
                        $monthlys = $get_monthly->fetch_monthly_sales($store_id);
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
</div>

<?php 
    }else{
?>
<div class="check_out_due">
    <hr>
    <div class="displays allResults" id="check_out_guest">
       
        <h3 style="background:var(--otherColor)">My Daily transactions</h3>
        <table id="check_out_table" class="searchTable" style="width:100%;">
            <thead>
                <tr style="background:var(--moreColor)">
                    <td>S/N</td>
                    <td>Invoice</td>
                    <td>Item</td>
                    <td>Qty</td>
                    <td>Unit sales</td>
                    <td>Amount</td>
                    <td>Payment mode</td>
                    <td>Time</td>
                    
                </tr>
            </thead>
            <tbody>
                <?php
                    $n = 1;
                    $get_users = new selects();
                    $details = $get_users->fetch_details_date2Cond('sales', 'date(post_date)', 'sales_status', 2, 'posted_by', $user_id);
                    if(gettype($details) === 'array'){
                    foreach($details as $detail):
                ?>
                <tr>
                    <td style="text-align:center; color:red;"><?php echo $n?></td>
                    <td style="color:green"><?php echo $detail->invoice?></td>
                    <td>
                        <?php
                            $get_name = new selects();
                            $name = $get_name->fetch_details_group('items', 'item_name', 'item_id', $detail->item);
                            echo $name->item_name;
                        ?>
                    </td>
                    <td style="text-align:center; color:var(--otherColor)"><?php echo $detail->quantity?></td>
                    <td><?php echo "₦".number_format($detail->price)?></td>
                    <td><?php echo "₦".number_format($detail->total_amount)?></td>
                    <td>
                        <?php
                            //get payment mode
                            $get_mode = new selects();
                            $mode = $get_mode->fetch_details_group('payments', 'payment_mode', 'invoice', $detail->invoice);
                            //check if invoice is more than 1
                            $get_mode_count = new selects();
                            $rows = $get_mode_count->fetch_count_cond('payments', 'invoice', $detail->invoice);
                                if($rows >= 2){
                                    echo "Multiple payment";
                                }else{
                                    echo $mode->payment_mode;

                                }
                            ?>
                    </td>
                    <td><?php echo date("h:i:sa", strtotime($detail->post_date))?></td>
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
</div>
<?php
    }
?>
</div>