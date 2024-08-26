<?php
    session_start();
    $store = $_SESSION['store_id'];
    $from = htmlspecialchars(stripslashes($_POST['from_date']));
    $to = htmlspecialchars(stripslashes($_POST['to_date']));

    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_checkIns = new selects();
    $details = $get_checkIns->fetch_details_2dateCon('remove_items', 'store', 'date(removed_date)', $from, $to, $store);
    $n = 1;  
?>
<h2>Items removed from inventory between '<?php echo date("jS M, Y", strtotime($from)) . "' and '" . date("jS M, Y", strtotime($to))?>'</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchCheckout" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Removed item report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Item</td>
                <td>Previous Qty</td>
                <td>Qty removed</td>
                <td>Date</td>
                <td>Time</td>
                <td>Removed by</td>
                
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
                        $get_item = new selects();
                        $row = $get_item->fetch_details_group('items', 'item_name', 'item_id', $detail->item);
                        $item_name = $row->item_name;
                        echo $item_name;

                    ?>
                </td>
                <td style="color:var(--moreColor); text-align:center"><?php echo $detail->previous_qty;?></td>
                <td style="color:green; text-align:center"><?php echo $detail->quantity;?></td>
                <td><?php echo date("m-d-Y", strtotime($detail->removed_date));?></td>
                <td><?php echo date("H:i:sa", strtotime($detail->removed_date));?></td>
                <td>
                    <?php
                        //get posted by
                        $get_adjust_by = new selects();
                        $adjusted_by = $get_adjust_by->fetch_details_group('users', 'full_name', 'user_id', $detail->removed_by);
                        echo $adjusted_by->full_name;
                    ?>
                </td>
                
            </tr>
            <?php $n++; } }?>
        </tbody>
    </table>
<?php
    
    if(gettype($details) == 'string'){
        echo "<p class='no_result'>'$details'</p>";
    }
?>
