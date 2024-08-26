<?php
    session_start();
    $store = $_SESSION['store_id'];
    $from = htmlspecialchars(stripslashes($_POST['from_date']));
    $to = htmlspecialchars(stripslashes($_POST['to_date']));

    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";

    
?>
<hr>
    <h2 style="background:var(--otherColor); color:#fff; padding:10px;">Profit and Loss statement between "<?php echo date("jS M, Y", strtotime($from))?>" AND "<?php echo date("jS M, Y", strtotime($to))?>"</h2>
    <div class="profitNloss">
        <?php
            // get accounts
            $get_revenue = new selects();
            $rows = $get_revenue->fetch_revenueDate($from, $to, $store);
            foreach($rows as $row){
        ?>
        <div class="prof_loss">
            <div class="prof">
                <h3><i class="fas fa-money-check"></i> Revenue</h3>
            </div>
            <div class="prof">
                <p><?php echo "₦".number_format($row->total, 2)?></p>
            </div>
        </div>
        <div class="prof_loss">
            <div class="prof">
                <h3><i class="fas fa-coins"></i> Cost of sales</h3>
            </div>
            <div class="prof">
                <p><?php echo "₦".number_format($row->total_cost, 2)?></p>
            </div>
        </div>
        <div class="prof_loss">
            <?php
                //get expense
                $get_exp = new selects();
                $exps = $get_exp->fetch_sum_2dateCond('expenses', 'amount', 'store', 'date(post_date)', $from, $to, $store);
                foreach($exps as $exp){
            ?>
            <div class="prof">
                <h3><i class="fas fa-hand-holding-dollar"></i> Expense</h3>
            </div>
            <div class="prof">
                <p><?php echo "₦".number_format($exp->total, 2)?></p>
            </div>
            <?php }?>
        </div>
        <?php }?>
    </div>
<?php
    // get sum
    $get_total = new selects();
    $amounts = $get_total->fetch_revenueDate($from, $to, $store);
    foreach($amounts as $amount){
        $revenue = $amount->total;
        $costSales = $amount->total_cost;
        $expense = $exp->total;
        $total_profit = $revenue - ($costSales + $expense);
        echo "<p class='total_amount' style='background:red; color:#fff; text-decoration:none; padding:10px; width:30%; float:right'>Net Profit: ₦".number_format($total_profit, 2)."</p>";
    }
?>
