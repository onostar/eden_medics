<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";


?>
<div id="accept_item" class="displays management" style="width:100%!important;">
<div class="displays allResults new_data" id="revenue_report">
    <h2>List of transferred items returned to store</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchCheckout" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Sales report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--primaryColor)">
                <td>S/N</td>
                <td>Invoice</td>
                <td>Item</td>
                <td>Quantity</td>
                <td>Returned from</td>
                <td>Date</td>
                <td>Returned by</td>
                <td></td>
                
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_2cond('transfers', 'from_store', 'transfer_status', $store, -1);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
                    //get item details
                    $get_item = new selects();
                    $sums = $get_item->fetch_details_cond('items', 'item_id', $detail->item);
                    foreach($sums as $sum){
                        $name = $sum->item_name;
                        $cost_price = $sum->cost_price;
                        $sales_price = $sum->sales_price;
                    }
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td style="color:var(--otherColor)"><?php echo $detail->invoice?></td>
                <td><?php echo $name?></td>
                <td style="color:green;text-align:center"><?php echo $detail->quantity?></td>
                <td>
                    <?php
                        //get store details
                        $get_store = new selects();
                        $strs = $get_store->fetch_details_group('stores', 'store', 'store_id', $detail->to_store);
                        echo $strs->store;
                    ?>
                </td>
                <td style="color:var(--moreColor)"><?php echo date("jS M, Y", strtotime($detail->post_date));?></td>
                <td>
                    <?php
                        //get posted by
                        $get_posted_by = new selects();
                        $checkedin_by = $get_posted_by->fetch_details_group('users', 'full_name', 'user_id', $detail->accept_by);
                        echo $checkedin_by->full_name;
                    ?>
                </td>
                <td>
                    <a style="color:green; background:green; padding:5px 8px; border-radius:5px; color:#fff" href="javascript:void(0)" title="Accept item" onclick="acceptItem('<?php echo $detail->transfer_id?>')"> <i class="fas fa-check"></i></a>
                </td>
                
            </tr>
            <?php $n++; endforeach;}?>
        </tbody>
    </table>
    <?php
        if(gettype($details) == "string"){
            echo "<p class='no_result'>'$details'</p>";
        }
    ?>
    

</div>

<script src="../jquery.js"></script>
<script src="../script.js"></script>