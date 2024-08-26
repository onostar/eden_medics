<?php
    session_start();
    $store = $_SESSION['store_id'];
    $from = htmlspecialchars(stripslashes($_POST['from_date']));
    $to = htmlspecialchars(stripslashes($_POST['to_date']));

    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_returns = new selects();
    $details = $get_returns->fetch_details_date2Con('sales_returns', 'date(return_date)', $from, $to, 'store', $store);
    $n = 1;  
?>
<h2>Sales return Report between <?php echo date("jS M, Y", strtotime($from)) . " and " . date("jS M, Y", strtotime($to))?></h2>
    <hr>
    <div class="search">
        <input type="search" id="searchCheckout" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('sales_return_table', 'Sales return report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="sales_return_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Invoice</td>
                <td>Item</td>
                <td>Qty</td>
                <td>Amount</td>
                <td>Reason</td>
                <td>Date</td>
                <td>Post Time</td>
                <td>Returned by</td>
                
            </tr>
        </thead>
        <tbody>
<?php
    if(gettype($details) === 'array'){
    foreach($details as $detail){

?>
    <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td><?php echo $detail->invoice?></td>
                <td style="color:green">
                    <?php
                        //get item name
                        $get_item = new selects();
                        $names = $get_item->fetch_details_group('items', 'item_name', 'item_id', $detail->item);
                        echo $names->item_name;
                    ?>
                </td>
                <td style="text-align:center; color:red"><?php echo $detail->quantity?></td>
                <td><?php echo "₦".number_format($detail->amount, 2)?></td>
                <td><?php echo $detail->reason?></td>
                <td style="color:var(--moreColor)"><?php echo date("jS M, Y", strtotime($detail->return_date));?></td>
                <td style="color:var(--moreColor)"><?php echo date("H:i:sa", strtotime($detail->return_date));?></td>
                <td>
                    <?php
                        //get posted by
                        $get_posted_by = new selects();
                        $checkedin_by = $get_posted_by->fetch_details_group('users', 'full_name', 'user_id', $detail->returned_by);
                        echo $checkedin_by->full_name;
                    ?>
                </td>
                
            </tr>
            <?php $n++; }?>
        </tbody>
    </table>
<?php
    }else{
        echo "<p class='no_result'>'$details'</p>";
    }
    // get sum
    $get_total = new selects();
    $amounts = $get_total->fetch_sum_2dateCond('sales_returns', 'amount', 'store', 'date(return_date)', $from, $to, $store);
    foreach($amounts as $amount){
        echo "<p class='total_amount' style='color:green; text-align:center'>Total: ₦".number_format($amount->total, 2)."</p>";
    }
?>
