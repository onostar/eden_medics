<?php
    session_start();
    $store = $_SESSION['store_id'];
    $from = htmlspecialchars(stripslashes($_POST['from_date']));
    $to = htmlspecialchars(stripslashes($_POST['to_date']));

    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_revenue = new selects();
    $details = $get_revenue->fetch_salesOrderDate($from, $to, $store);
    $n = 1;  
?>
<h2>Sales Order between '<?php echo date("jS M, Y", strtotime($from)) . "' and '" . date("jS M, Y", strtotime($to))?>'</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRevenue" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Sales order list between <?php echo date ('jS M, Y', strtotime($from))?> and <?php echo date ('jS M, Y', strtotime($to))?>')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
        <tr style="background:var(--otherColor)">
        <td>S/N</td>
                <td>Invoice</td>
                <td>Date Posted</td>
                <td>Post Time</td>
                <td>Amount</td>
                <td>Total Items</td>
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
                <td><a style="color:green" href="javascript:void(0)" title="View invoice details" onclick="showPage('invoice_details.php?payment_id=<?php echo $detail->invoice?>')"><?php echo $detail->invoice?></a></td>
                <td style="color:var(--moreColor)"><?php echo date("d-m-Y", strtotime($detail->post_date));?></td>
                <td style="color:var(--moreColor)"><?php echo date("H:i:sa", strtotime($detail->post_date));?></td>
                <td><?php echo "₦".number_format($detail->total, 2)?></td>
                <td style="text-align:center">
                    <?php
                        //get items in invoice;
                        $get_items = new selects();
                        $items = $get_items->fetch_count_cond('sales', 'invoice', $detail->invoice);
                        echo $items;
                    ?>
                </td>
                <td>
                    <?php
                        //get posted by
                        $get_posted_by = new selects();
                        $checkedin_by = $get_posted_by->fetch_details_group('users', 'full_name', 'user_id', $detail->posted_by);
                        echo $checkedin_by->full_name;
                    ?>
                </td>
                <td>
                    <a style="background:green; color:#fff; padding:5px 10px; border-radius:5px;" href="javascript:void(0)" title="View invoice details" onclick="showPage('sales_details.php?invoice=<?php echo $detail->invoice?>')"><i class="fas fa-eye"></i> View</a>
                </td>
                
            </tr>
            <?php $n++; }}?>
        </tbody>
    </table>
<?php
    if(gettype($details) == "string"){
        echo "<p class='no_result'>'$details'</p>";
    }
    
?>
