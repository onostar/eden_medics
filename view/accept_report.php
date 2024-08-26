<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    //get store name
    $get_store = new selects();
    $strs = $get_store->fetch_details_group('stores', 'store', 'store_id', $store);
    $store_name = $strs->store;

?>
<div id="transfer_report" class="displays management" style="width:100%!important;">
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
            <button type="submit" name="search_date" id="search_date" onclick="search('search_accept_report.php')">Search <i class="fas fa-search"></i></button>
        </section>
    </div>
<div class="displays allResults new_data" id="revenue_report">
    <h2>Items accepted to <?php echo $store_name?> Today</h2>
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
                <td>From</td>
                <td>Post time</td>
                <td>Transferred by</td>
                <td>Accepted by</td>
                
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_date2Cond('transfers', 'date(post_date)', 'to_store', $store, 'transfer_status', 2);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
                    $get_ind = new selects();
                    $alls = $get_ind->fetch_details_cond('items', 'item_id', $detail->item);
                    foreach($alls as $all){
                        $cost_price = $all->cost_price;
                        $sales_price = $all->sales_price;
                        $itemname = $all->item_name;
                    }
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td style="color:var(--otherColor)"><?php echo $detail->invoice?></td>
                <td><?php echo $itemname;?></td>
                <td style="text-align:center; color:green"><?php echo $detail->quantity?></td>
                <td style="color:green;">
                    <?php 
                        //get total items with that invoice
                        $get_sum = new selects();
                        $sums = $get_sum->fetch_details_group('stores', 'store', 'store_id', $detail->from_store);
                        echo $sums->store;
                    ?>
                </td>
                
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
                    <?php
                        //get posted by
                        $get_posted_by = new selects();
                        $checkedin_by = $get_posted_by->fetch_details_group('users', 'full_name', 'user_id', $detail->accept_by);
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
    ?>
    

</div>

<script src="../jquery.js"></script>
<script src="../script.js"></script>