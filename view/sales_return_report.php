<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";


?>
<div id="checkReport" class="displays management">
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
            <button type="submit" name="search_date" id="search_date" onclick="search('search_return_reports.php')">Search <i class="fas fa-search"></i></button>
</section>
    </div>
<div class="displays allResults new_data" id="check_in_report">
    <h2>Sales Return Report for today</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchCheckout" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Sales return report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Invoice</td>
                <td>Item</td>
                <td>Qty</td>
                <td>Amount</td>
                <td>Reason</td>
                <td>Post Time</td>
                <td>Returned by</td>
                
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_curdateCon('sales_returns', 'date(return_date)', 'store', $store);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
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
            <?php $n++; endforeach;}?>
        </tbody>
    </table>
    
    <?php
        if(gettype($details) == "string"){
            echo "<p class='no_result'>'$details'</p>";
        }

        // get sum
        $get_total = new selects();
        $amounts = $get_total->fetch_sum_curdateCon('sales_returns', 'amount', 'date(return_date)', 'store', $store);
        foreach($amounts as $amount){
            echo "<p class='total_amount' style='color:green; text-align:center'>Total: ₦".number_format($amount->total, 2)."</p>";
        }
    ?>
</div>

<script src="../jquery.js"></script>
<script src="../script.js"></script>