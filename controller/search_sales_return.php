<?php
    session_start();
    $store = $_SESSION['store_id'];
    $from = htmlspecialchars(stripslashes($_POST['from_date']));
    $to = htmlspecialchars(stripslashes($_POST['to_date']));

    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_revenue = new selects();
    $details = $get_revenue->fetch_details_2date2Con('sales', 'date(post_date)', $from, $to, 'sales_status', 2, 'store', $store);
    $n = 1;  
?>
<h2>Return sales between '<?php echo date("jS M, Y", strtotime($from)) . "' and '" . date("jS M, Y", strtotime($to))?>'</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRevenue" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Sales return list')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
        <tr style="background:var(--primaryColor)">
        <td>S/N</td>
                <td>Invoice</td>
                <td>Item</td>
                <td>Qty</td>
                <td>Amount</td>
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
                <td><?php echo $detail->invoice?></td>
                <td style="color:green">
                    <?php
                        //get item name
                        $get_item = new selects();
                        $names = $get_item->fetch_details_group('items', 'item_name', 'item_id', $detail->item);
                        echo $names->item_name;
                    ?>
                </td>
                <td style="text-align:center"><?php echo $detail->quantity?></td>
                <td><?php echo "₦".number_format($detail->total_amount, 2)?></td>
                <td style="color:var(--moreColor)"><?php echo date("jS M, Y", strtotime($detail->post_date));?></td>
                <td style="color:var(--moreColor)"><?php echo date("H:i:sa", strtotime($detail->post_date));?></td>
                <td>
                    <?php
                        //get posted by
                        $get_posted_by = new selects();
                        $checkedin_by = $get_posted_by->fetch_details_group('users', 'full_name', 'user_id', $detail->posted_by);
                        echo $checkedin_by->full_name;
                    ?>
                </td>
                <td style="color:var(--otherColor);"><a href="javascript:void(0)" title="Click to Return sales" onclick="displaySales('<?php echo $detail->sales_id?>')">Return <i class="fas fa-pen"></i></a></td>
                
            </tr>
            <?php $n++; }?>
        </tbody>
    </table>
<?php
    }
?>
