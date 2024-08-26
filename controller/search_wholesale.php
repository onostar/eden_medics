<?php
    session_start();
    $store = $_SESSION['store_id'];
    $from = htmlspecialchars(stripslashes($_POST['from_date']));
    $to = htmlspecialchars(stripslashes($_POST['to_date']));

    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_revenue = new selects();
    $details = $get_revenue->fetch_details_dateGro2con('payments', 'date(post_date)', $from, $to, 'store', $store, 'sales_type', 'Wholesale', 'invoice');
    $n = 1;
?>
<h2>Retail Sales Report between '<?php echo date("jS M, Y", strtotime($from)) . "' and '" . date("jS M, Y", strtotime($to))?>'</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRevenue" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Retail Sales report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
        <tr style="background:var(--primaryColor)">
                <td>S/N</td>
                <td>Customer</td>
                <td>Invoice</td>
                <td>Items</td>
                <td>Amount due</td>
                <td>Amount paid</td>
                <td>Discount</td>
                <td>Payment Mode</td>
                <td>Date</td>
                <td>Post Time</td>
                <td>Posted by</td>
                
            </tr>
        </thead>
        <tbody>
<?php
    if(gettype($details) === 'array'){
    foreach($details as $detail){

?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td>
                    <?php 
                        //get customer name
                        $get_cust = new selects();
                        $client = $get_cust->fetch_details_group('customers', 'customer', 'customer_id', $detail->customer);
                        echo $client->customer;
                    ?>
                </td>
                <td><a style="color:green" href="javascript:void(0)" title="View invoice details" onclick="showPage('invoice_details.php?payment_id=<?php echo $detail->payment_id?>')"><?php echo $detail->invoice?></a></td>
                <td style="text-align:Center">
                    <?php
                        //get items in invoice;
                        $get_items = new selects();
                        $items = $get_items->fetch_count_cond('sales', 'invoice', $detail->invoice);
                        echo $items;
                    ?>
                </td>
                <td>
                    <?php echo "₦".number_format($detail->amount_due, 2);?>
                </td>
                <td>
                    <?php 
                        //get sum of invoice
                        $get_sum = new selects();
                        $sums = $get_sum->fetch_sum_single('payments', 'amount_paid', 'invoice', $detail->invoice);
                        foreach($sums as $sum){
                            echo "₦".number_format($sum->total, 2);

                        }
                    ?>
                </td>
                <td style="color:red">
                    <?php echo "₦".number_format($detail->discount, 2);?>
                </td>
                <td>
                <?php 
                        //get payment mode
                        $get_mode = new selects();
                        $rows = $get_mode->fetch_count_cond('payments', 'invoice', $detail->invoice);
                        if($rows >= 2){
                            echo "Multiple payment";
                        }else{
                        echo $detail->payment_mode;
                        }
                     ?>
                </td>
                <td style="color:var(--otherColor)"><?php echo date("d-m-y", strtotime($detail->post_date));?></td>
                <td style="color:var(--moreColor)"><?php echo date("H:i:sa", strtotime($detail->post_date));?></td>
                <td>
                    <?php
                        //get posted by
                        $get_posted_by = new selects();
                        $checkedin_by = $get_posted_by->fetch_details_group('users', 'full_name', 'user_id', $detail->posted_by);
                        echo $checkedin_by->full_name;
                    ?>
                </td>
                
            </tr>
            <?php $n++; }}?>
        </tbody>
    </table>
<?php
    if(gettype($details) == "string"){
        echo "<p class='no_result'>'$details'</p>";
    }
    // get sum
    $get_total = new selects();
    $amounts = $get_total->fetch_sum_2date2Cond('payments', 'amount_paid', 'date(post_date)', 'store', 'sales_type', $from, $to, $store, 'Wholesale');
    foreach($amounts as $amount){
        $paid_amount = $amount->total;

    }
    // if credit was sold
    $get_credit = new selects();
    $credits = $get_credit->fetch_sum_2date3Cond('payments', 'amount_due', 'date(post_date)', 'payment_mode', 'store', 'sales_type', $from, $to, 'Credit', $store, 'Wholesale');
    if(gettype($credits) === "array"){
        foreach($credits as $credit){
            $owed_amount = $credit->total;
        }
        $total_revenue = $owed_amount + $paid_amount;
        echo "<p class='total_amount' style='color:green'>Total: ₦".number_format($total_revenue, 2)."</p>";

    }
    //if no credit sales
    if(gettype($credits) == "string"){
        echo "<p class='total_amount' style='color:green'>Total: ₦".number_format($paid_amount, 2)."</p>";
        
    }
?>
