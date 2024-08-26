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
            <button type="submit" name="search_date" id="search_date" onclick="search('search_cashier.php')">Search <i class="fas fa-search"></i></button>
</section>
    </div>
<div class="displays allResults new_data" id="revenue_report">
    <h2>Cashier report for today</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchCheckout" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Cashier report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--primaryColor)">
                <td>S/N</td>
                <td>Cashier</td>
                <td>Cash</td>
                <td>POS</td>
                <td>Transfer</td>
                <td>Wallet</td>
                <td>Credit</td>
                <td>Total</td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_curdateGro1con('payments', 'post_date', 'store', $store, 'posted_by');
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td style="color:var(--otherColor)">
                    <?php
                        //get posted by
                        $get_posted_by = new selects();
                        $checkedin_by = $get_posted_by->fetch_details_group('users', 'full_name', 'user_id', $detail->posted_by);
                        echo $checkedin_by->full_name;
                    ?>
                </td>
                <td>
                    <?php
                        $get_cash = new selects();
                        $cashs = $get_cash->fetch_sum_curdate2Con('payments', 'amount_paid', 'date(post_date)', 'payment_mode', 'Cash', 'posted_by', $detail->posted_by);
                        foreach($cashs as $cash){
                            echo "₦".number_format($cash->total, 2);
                        }
                    ?>
                </td>
                <td>
                    <?php
                        $get_pos = new selects();
                        $poss = $get_pos->fetch_sum_curdate2Con('payments', 'amount_paid', 'date(post_date)', 'payment_mode', 'POS', 'posted_by', $detail->posted_by);
                        foreach($poss as $pos){
                            echo "₦".number_format($pos->total, 2);
                        }
                    ?>
                </td>
                <td>
                    <?php
                        $get_tf = new selects();
                        $trfs = $get_tf->fetch_sum_curdate2Con('payments', 'amount_paid', 'date(post_date)', 'payment_mode', 'Transfer', 'posted_by', $detail->posted_by);
                        foreach($trfs as $trf){
                            echo "₦".number_format($trf->total, 2);
                        }
                    ?>
                </td>
                <td>
                    <?php
                        $get_tf = new selects();
                        $trfs = $get_tf->fetch_sum_curdate2Con('payments', 'amount_paid', 'date(post_date)', 'payment_mode', 'Wallet', 'posted_by', $detail->posted_by);
                        foreach($trfs as $trf){
                            echo "₦".number_format($trf->total, 2);
                        }
                    ?>
                </td>
                <td>
                    <?php
                        $get_tf = new selects();
                        $trfs = $get_tf->fetch_sum_curdate2Con('payments', 'amount_due', 'date(post_date)', 'payment_mode', 'Credit', 'posted_by', $detail->posted_by);
                        foreach($trfs as $trf){
                            echo "₦".number_format($trf->total, 2);
                        }
                    ?>
                </td>
                <td style="color:red;">
                    <?php
                        // get sum
                        $get_total = new selects();
                        $amounts = $get_total->fetch_sum_curdateCon('payments', 'amount_paid', 'post_date', 'posted_by', $detail->posted_by);
                        foreach($amounts as $amount){
                            $paid_amount = $amount->total;
                            
                        }
                        //if credit was sold
                        $get_credit = new selects();
                        $credits = $get_credit->fetch_sum_curdate2Con('payments', 'amount_due', 'post_date', 'payment_mode', 'Credit', 'posted_by', $detail->posted_by);
                        if(gettype($credits) === "array"){
                            foreach($credits as $credit){
                                $owed_amount = $credit->total;
                            }
                            $total_revenue = $owed_amount + $paid_amount;
                            echo "₦".number_format($total_revenue, 2);

                        }
                        //if no credit sales
                        if(gettype($credits) == "string"){
                            echo "₦".number_format($paid_amount, 2);
                            
                        }
                    ?>
                </td>
                
                
            </tr>
            <?php $n++; endforeach;}?>
            <?php
                if(gettype($details) == "array"){
            ?>
            <tr>
                <td></td>
                <td style="color:green; font-size:1rem;">Total</td>
                <td style="color:green; font-size:1rem;">
                    <?php
                        $get_total = new selects();
                        $totals = $get_cash->fetch_sum_curdate2Con('payments', 'amount_paid', 'post_date', 'payment_mode', 'Cash', 'store', $store);
                        foreach($totals as $total){
                            echo "₦".number_format($total->total, 2);
                        }
                    ?>
                </td>
                <td style="color:green; font-size:1rem;">
                    <?php
                        $get_total = new selects();
                        $totals = $get_cash->fetch_sum_curdate2Con('payments', 'amount_paid', 'post_date', 'payment_mode', 'POS', 'store', $store);
                        foreach($totals as $total){
                            echo "₦".number_format($total->total, 2);
                        }
                    ?>
                </td>
                <td style="color:green; font-size:1rem;">
                    <?php
                        $get_total = new selects();
                        $totals = $get_cash->fetch_sum_curdate2Con('payments', 'amount_paid', 'post_date', 'payment_mode', 'Transfer', 'store', $store);
                        foreach($totals as $total){
                            echo "₦".number_format($total->total, 2);
                        }
                    ?>
                </td>
                <td style="color:green; font-size:1rem;">
                    <?php
                        $get_total = new selects();
                        $totals = $get_cash->fetch_sum_curdate2Con('payments', 'amount_paid', 'post_date', 'payment_mode', 'Wallet', 'store', $store);
                        foreach($totals as $total){
                            echo "₦".number_format($total->total, 2);
                        }
                    ?>
                </td>
                <td style="color:green; font-size:1rem;">
                    <?php
                        $get_total = new selects();
                        $totals = $get_cash->fetch_sum_curdate2Con('payments', 'amount_due', 'post_date', 'payment_mode', 'Credit', 'store', $store);
                        foreach($totals as $total){
                            echo "₦".number_format($total->total, 2);
                        }
                    ?>
                </td>
                <td style="color:red; font-size:1rem;">
                    <?php
                        // get sum
                        $get_total = new selects();
                        $amounts = $get_total->fetch_sum_curdateCon('payments', 'amount_paid', 'post_date', 'store', $store);
                        foreach($amounts as $amount){
                            $paid_amount = $amount->total;
                            
                        }
                        //if credit was sold
                        $get_credit = new selects();
                        $credits = $get_credit->fetch_sum_curdate2Con('payments', 'amount_due', 'post_date', 'payment_mode', 'Credit', 'store', $store);
                        if(gettype($credits) === "array"){
                            foreach($credits as $credit){
                                $owed_amount = $credit->total;
                            }
                            $total_revenue = $owed_amount + $paid_amount;
                            echo "₦".number_format($total_revenue, 2);

                        }
                        //if no credit sales
                        if(gettype($credits) == "string"){
                            echo "₦".number_format($paid_amount, 2);
                            
                        }
                    ?>
                </td>
            </tr>
            <?php }?>
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