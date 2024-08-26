<?php
    session_start();
    $store = $_SESSION['store_id'];
    $from = htmlspecialchars(stripslashes($_POST['from_date']));
    $to = htmlspecialchars(stripslashes($_POST['to_date']));
    $item = htmlspecialchars(stripslashes($_POST['history_item']));

    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";
    // get item name
    $get_name = new selects();
    $rows = $get_name->fetch_details_group('items', 'item_name', 'item_id', $item);
    $item_name = $rows->item_name;

    $get_history = new selects();
    $details = $get_history->fetch_item_history($from, $to, $item, $store);
    $n = 1;  
?>
<div class="search">
    <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Bincard report for <?php echo $item_name?>')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
</div>
<h2 style="background:var(--otherColor); color:#fff;padding:5px">Bincard report for <?php echo strtoupper($item_name)?> between '<?php echo date("jS M, Y", strtotime($from)) . "' and '" . date("jS M, Y", strtotime($to))?>'</h2>

    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Opening Balance</td>
                <td>Purchased</td>
                <td>Sold</td>
                <td>Sales return</td>
                <td>Adjusted to</td>
                <td>Removed</td>
                <td>Transferred</td>
                <td>Accepted</td>
                <td>Date</td>
                <td>Time</td>
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
                <td style="text-align:center; color:green;">
                    <?php echo  $detail->previous_qty;

                    ?>
                </td>
                <td style="color:var(--moreColor); text-align:center">
                    <?php 
                        //get transaction type;
                        if($detail->transaction == "purchase"){
                            echo $detail->quantity;
                        }else{
                            echo "0";
                        }
                    ?>
                </td>
                <td style="color:var(--moreColor); text-align:center">
                    <?php 
                        //get transaction type;
                        if($detail->transaction == "sales"){
                            echo $detail->quantity;
                        }else{
                            echo "0";
                        }
                    ?>
                </td>
                <td style="color:var(--moreColor); text-align:center">
                    <?php 
                        //get transaction type;
                        if($detail->transaction == "sales_return"){
                            echo $detail->quantity;
                        }else{
                            echo "0";
                        }
                    ?>
                </td>
                <td style="color:var(--moreColor); text-align:center">
                    <?php 
                        //get transaction type;
                        if($detail->transaction == "adjust"){
                            echo $detail->quantity;
                        }else{
                            echo "0";
                        }
                    ?>
                </td>
                <td style="color:var(--moreColor); text-align:center">
                    <?php 
                        //get transaction type;
                        if($detail->transaction == "remove"){
                            echo $detail->quantity;
                        }else{
                            echo "0";
                        }
                    ?>
                </td>
                <td style="color:var(--otherColor); text-align:center">
                    <?php 
                        //get transaction type;
                        if($detail->transaction == "transfer"){
                            echo $detail->quantity;
                        }else{
                            echo "0";
                        }
                    ?>
                </td>
                <td style="color:var(--otherColor); text-align:center">
                    <?php 
                        //get transaction type;
                        if($detail->transaction == "accept"){
                            echo $detail->quantity;
                        }else{
                            echo "0";
                        }
                    ?>
                </td>
                <td><?php echo date("m-d-Y", strtotime($detail->post_date));?></td>
                <td><?php echo date("H:i:sa", strtotime($detail->post_date));?></td>
                <td>
                    <?php
                        //get posted by
                        $get_posted_by = new selects();
                        $posted_by = $get_posted_by->fetch_details_group('users', 'full_name', 'user_id', $detail->posted_by);
                        echo $posted_by->full_name;
                    ?>
                </td>
                
            </tr>
            <?php $n++; }}?>
        </tbody>
    </table>
<?php
    //get total quantity
    if(gettype($details) == "array"){
    $get_qty = new selects();
    $totals = $get_qty->fetch_details_2cond('inventory', 'item', 'store', $item, $store);
    foreach($totals as $total){
        echo "<p class='total_amount' style='color:green; text-align:center; text-decoration:underline'>Current quantity = ".$total->quantity."</p>";

    }
    }
    if(gettype($details) == "string"){
        echo "<p class='no_result'>'$details'</p>";
    }
?>
