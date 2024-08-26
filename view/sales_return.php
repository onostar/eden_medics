<div id="sales_return">
<?php
    session_start();
    $store = $_SESSION['store_id'];
    if(isset($_SESSION['user_id'])){
        $user = $_SESSION['user_id'];
        include "../classes/dbh.php";
        include "../classes/select.php";
    
    

?>
<div id="revenueReport" class="displays management">
    <div class="select_date" style="width:100%;">
        <!-- <form method="POST"> -->
        <section>    
            <div class="from_to_date" style="width:20%">
                <label>Select From Date</label><br>
                <input type="date" name="from_date" id="from_date"><br>
            </div>
            <div class="from_to_date" style="width:20%">
                <label>Select to Date</label><br>
                <input type="date" name="to_date" id="to_date"><br>
            </div>
            <button style="width:20%" type="submit" name="search_date" id="search_date" onclick="search('search_sales_return.php')">Search <i class="fas fa-search"></i></button>
        </section>
    </div>
<div class="displays allResults new_data" id="salesReturn">
    <h2>Return sales from today</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchCheckout" placeholder="Enter keyword" onkeyup="searchData(this.value)">
    </div>
    <table id="revenue_table" class="searchTable">
        <thead>
            <tr style="background:var(--otherColor)">
                <td>S/N</td>
                <td>Invoice</td>
                <td>Item</td>
                <td>Qty</td>
                <td>Amount</td>
                <td>Post Time</td>
                <td>Posted by</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_date2Cond('sales', 'date(post_date)', 'sales_status', 2, 'store', $store);
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
                <td style="text-align:center"><?php echo $detail->quantity?></td>
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
                <td style="color:var(--otherColor);"><a href="javascript:void(0)" title="Click to Return sales" onclick="displaySales('<?php echo $detail->sales_id?>')">Return <i class="fas fa-pen"></i></a></td>
            </tr>
            <?php $n++; endforeach;}?>
        </tbody>
    </table>
    
    

</div>
<?php
    }else{
        header("Location: ../index.php");
    }
?>
</div>
<script src="../jquery.js"></script>
<script src="../script.js"></script>