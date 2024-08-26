<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";


?>
<div id="stockAdjustReport" class="displays management" >
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
            <button type="submit" name="search_date" id="search_date" onclick="search('search_adjustments.php')">Search <i class="fas fa-search"></i></button>
    </section>
    </div>
<div class="displays allResults new_data" style="width:70%!important; margin: 0 50px!important;">
    <h2>Stock Adjustment Report for today</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchCheckout" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Stock adjustment report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Item</td>
                <td>Previous Qty</td>
                <td>Adjusted to</td>
                <td>Time</td>
                <td>Adjusted by</td>
                
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_curdateCon('stock_adjustments', 'date(adjust_date)', 'store', $store);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
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
                <td style="color:green; text-align:center"><?php echo $detail->new_qty;?></td>
                <td><?php echo date("H:i:sa", strtotime($detail->adjust_date));?></td>
                <td>
                    <?php
                        //get posted by
                        $get_adjust_by = new selects();
                        $adjusted_by = $get_adjust_by->fetch_details_group('users', 'full_name', 'user_id', $detail->adjusted_by);
                        echo $adjusted_by->full_name;
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