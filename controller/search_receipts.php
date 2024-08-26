<?php
    session_start();
    $store = $_SESSION['store_id'];
    $from = htmlspecialchars(stripslashes($_POST['from_date']));
    $to = htmlspecialchars(stripslashes($_POST['to_date']));

    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_revenue = new selects();
    $details = $get_revenue->fetch_details_dateGro1con('payments', 'date(post_date)', $from, $to, 'store', $store, 'invoice');
    $n = 1;  
?>
<h2>Reprint sales receipt between '<?php echo date("jS M, Y", strtotime($from)) . "' and '" . date("jS M, Y", strtotime($to))?>'</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRevenue" placeholder="Enter keyword" onkeyup="searchData(this.value)">
    </div>
    <table id="Revenue_table" class="searchTable">
        <thead>
        <tr style="background:var(--primaryColor)">
                <td>S/N</td>
                <td>Invoice</td>
                <td>Amount</td>
                <td>Payment Mode</td>
                <td>Date</td>
                <td>Post Time</td>
                <td>Posted by</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
<?php
    if(gettype($details) === 'array'){
    foreach($details as $detail){

?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td><a style="color:green" href="javascript:void(0)" title="View invoice details" onclick="showPage('invoice_details.php?payment_id=<?php echo $detail->payment_id?>')"><?php echo $detail->invoice?></a></td>
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
                <td style="color:var(--otherColor)"><?php echo date("jS M, Y", strtotime($detail->post_date));?></td>
                <td style="color:var(--moreColor)"><?php echo date("H:i:sa", strtotime($detail->post_date));?></td>
                <td>
                    <?php
                        //get posted by
                        $get_posted_by = new selects();
                        $checkedin_by = $get_posted_by->fetch_details_group('users', 'full_name', 'user_id', $detail->posted_by);
                        echo $checkedin_by->full_name;
                    ?>
                </td>
                <td><button style="box-shadow:none;border-radius:4px; background:#c4c4c4; color:#000; padding:2px 5px;" title="Print receipt" onclick="printReceipt('<?php echo $detail->invoice?>');"><i class="fas fa-print"></i> Print</button></td>
            </tr>
            <?php $n++; }?>
        </tbody>
    </table>
<?php
    }else{
        echo "<p class='no_result'>'$details'</p>";
    }
?>
