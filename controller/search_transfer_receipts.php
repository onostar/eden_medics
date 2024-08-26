<?php
    session_start();
    $store = $_SESSION['store_id'];
    $from = htmlspecialchars(stripslashes($_POST['from_date']));
    $to = htmlspecialchars(stripslashes($_POST['to_date']));

    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_receipts = new selects();
    $details = $get_receipts->fetch_details_2condNeg2DateGroup('transfers', 'from_store', 'transfer_status', $store, 0, 'date(post_date)', $from, $to, 'invoice');
    $n = 1;  
?>
<h2>Reprint transfer receipt between '<?php echo date("jS M, Y", strtotime($from)) . "' and '" . date("jS M, Y", strtotime($to))?>'</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRevenue" placeholder="Enter keyword" onkeyup="searchData(this.value)">
    </div>
    <table id="Revenue_table" class="searchTable">
        <thead>
        <tr style="background:var(--primaryColor)">
                <td>S/N</td>
                <td>Invoice</td>
                <td>Total items</td>
                <td>Store</td>
                <td>Date</td>
                <td>Post time</td>
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
                <td style="color:var(--otherColor)"><?php echo $detail->invoice?></td>
                <td style="color:green; text-align:Center">
                    <?php 
                        //get total items with that invoice
                        $get_sum = new selects();
                        $sums = $get_sum->fetch_count_cond('transfers', 'invoice', $detail->invoice);
                        echo $sums;
                    ?>
                </td>
                <td style="color:green;">
                    <?php 
                        //get total items with that invoice
                        $get_sum = new selects();
                        $sums = $get_sum->fetch_details_group('stores', 'store', 'store_id', $detail->to_store);
                        echo $sums->store;
                    ?>
                </td>
                
                <td style="color:var(--moreColor)"><?php echo date("d-M-Y", strtotime($detail->post_date));?></td>
                <td style="color:var(--moreColor)"><?php echo date("H:m:ia", strtotime($detail->post_date));?></td>
                <td>
                    <?php
                        //get posted by
                        $get_posted_by = new selects();
                        $checkedin_by = $get_posted_by->fetch_details_group('users', 'full_name', 'user_id', $detail->posted_by);
                        echo $checkedin_by->full_name;
                    ?>
                </td>
                <td>
                    <a style="color:green; background:var(--otherColor); padding:5px; border-radius:5px; color:#fff" href="javascript:void(0)" title="View invoice details" onclick="showPage('transfer_details.php?invoice=<?php echo $detail->invoice?>')"> <i class="fas fa-eye"></i> View</a>
                    <button style="box-shadow:none;border-radius:4px; background:#c4c4c4; color:#000; padding:2px 5px;" title="Print receipt" onclick="printTransferReceipt('<?php echo $detail->invoice?>');"><i class="fas fa-print"></i> Print</button>
                </td>
            </tr>
            <?php $n++; }?>
        </tbody>
    </table>
<?php
    }else{
        echo "<p class='no_result'>'$details'</p>";
    }
?>
