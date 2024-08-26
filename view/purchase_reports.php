<?php
    session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
    $store = $_SESSION['store_id'];

?>
<div id="purchaseReport" class="displays management">
    <div class="select_date">
        <!-- <form method="POST"> -->
        <section>    
            <div class="from_to_date">
                <label>Select From Date</label><br>
                <input type="date" name="from_date" id="from_date"><br>
            </div>
            <div class="from_to_date">
                <label>Select to Date</label><br>
                <input type="date" name="to_date" id="to_date"><br>
            </div>
            <button type="submit" name="search_date" id="search_date" onclick="search('search_purchase.php')">Search <i class="fas fa-search"></i></button>
</section>
    </div>
<div class="displays allResults new_data">
    <h2>Purchase Register for today</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchCheckout" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Purchase report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--primaryColor)">
                <td>S/N</td>
                <td>Invoice</td>
                <td>Vendor</td>
                <td>Item</td>
                <td>Quantity</td>
                <td>Unit Cost</td>
                <td>Post Time</td>
                <td>Received by</td>
                
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_curdateCon('purchases', 'post_date', 'store', $store);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td><?php echo $detail->invoice?></td>
                <td>
                    <?php
                        $get_guest = new selects();
                        $rows = $get_guest->fetch_details_group('vendors', 'vendor', 'vendor_id', $detail->vendor);
                        echo $rows->vendor;
                    ?>
                </td>
                <td>
                    <?php
                        $get_guest = new selects();
                        $rows = $get_guest->fetch_details_group('items', 'item_name', 'item_id', $detail->item);
                        echo $rows->item_name;
                    ?>
                </td>
                <td style="text-align:center; color:green;"><?php echo $detail->quantity;?></td>
                <td><?php echo "₦".number_format($detail->cost_price, 2)?></td>
                <td style="color:var(--moreColor)"><?php echo date("H:i:sa", strtotime($detail->post_date));?></td>
                <td>
                    <?php
                        //get posted by
                        $get_posted_by = new selects();
                        $posted_by = $get_posted_by->fetch_details_group('users', 'full_name', 'user_id', $detail->posted_by);
                        echo $posted_by->full_name;
                    ?>
                </td>
                
            </tr>
            <?php $n++; endforeach;}?>
        </tbody>
    </table>
    
    <?php
        if(gettype($details) == "string"){
            echo "<p class='no_result'>'$details'</p>";
        }
        $get_total = new selects();
        $amounts = $get_total->fetch_sum_2colCurDate1Con('purchases', 'cost_price', 'quantity', 'date(post_date)', 'store', $store);
        foreach($amounts as $amount){
            echo "<p class='total_amount' style='color:green; text-align:center;'>Total: ₦".number_format($amount->total, 2)."</p>";
        }
    ?>

</div>

<script src="../jquery.js"></script>
<script src="../script.js"></script>