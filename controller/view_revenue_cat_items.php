<?php
    session_start();
    $store = $_SESSION['store_id'];
    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";
    if (isset($_GET['department'])){
        $department = $_GET['department'];
    // get department name
    $get_dep = new selects();
    $deps = $get_dep->fetch_details_group('departments', 'department', 'department_id', $department);
    $get_revenue = new selects();
    $details = $get_revenue->fetch_revenue_cat_items($department, $store);
    $n = 1;  
?>
<h2>Items sold for  '<?php echo $deps->department?>'</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRevenue" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Revenue reports for <?php echo $deps->department?>')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
        <tr style="background:var(--primaryColor)">
                <td>S/N</td>
                <td>Invoice</td>
                <td>Item</td>
                <td>Qty</td>
                <td>Amount</td>
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
                <td><a style="color:var(--otherColor)" href="javascript:void(0)" title="View invoice details" onclick="showPage('invoice_details.php?payment_id=<?php echo $detail->payment_id?>')"><?php echo $detail->invoice?></a></td>
                <td><?php echo $detail->item_name;?></td>
                <td style="text-align:center; color:green"><?php echo $detail->quantity;?></td>
                <td><?php echo "₦".number_format($detail->total_amount, 2)?></td>
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
            <?php $n++; }?>
        </tbody>
    </table>
<?php
    }else{
        echo "<p class='no_result'>'$details'</p>";
    }
    // get sum
    /* $get_total = new selects();
    $amounts = $get_total->fetch_sum_2date('payments', 'amount_paid', 'date(post_date)', $from, $to);
    foreach($amounts as $amount){
        echo "<p class='total_amount' style='color:green'>Total: ₦".number_format($amount->total, 2)."</p>";
    } */
}
?>
