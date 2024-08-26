<?php
    session_start();
    $store = $_SESSION['store_id'];
    $from = htmlspecialchars(stripslashes($_POST['from_date']));
    $to = htmlspecialchars(stripslashes($_POST['to_date']));

    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_revenue = new selects();
    $details = $get_revenue->fetch_revenue_catDate($from, $to, $store);
    $n = 1;  
?>
<h2>Revenue by category between '<?php echo date("jS M, Y", strtotime($from)) . "' and '" . date("jS M, Y", strtotime($to))?>'</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRevenue" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Revenue by category')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--primaryColor)">
                <td>S/N</td>
                <td>Category</td>
                <td>Revenue</td>
                <td>Cost of sales</td>
                <td>Profit</td>
                
            </tr>
        </thead>
        <tbody>
<?php
    if(gettype($details) === 'array'){
    foreach($details as $detail){

?>
            <tr>
            <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td><a style="color:#222;" href="javascript:void(0)" title="View items" onclick="viewItemsDate('<?php echo $from?>', '<?php echo $to?>', '<?php echo $detail->department?>')"><?php 
                    $get_cat = new Selects();
                    $row = $get_cat->fetch_details_group('departments', 'department', 'department_id', $detail->department);
                    echo $row->department?></a></td>
                <td><?php echo "₦".number_format($detail->total, 2)?></td>
                <td style="color:red"><?php echo "₦".number_format($detail->total_cost, 2)?></td>
                <td style="text-align:center; color:green;">
                    <?php
                        $profit = $detail->total - $detail->total_cost;
                        echo "₦".number_format($profit, 2)
                    ?>
                        
                </td>
                
            </tr>
            <?php $n++; }?>
        </tbody>
    </table>
<?php
    }else{
        echo "<p class='no_result'>'$details'</p>";
    }
    // get sum
    $get_total = new selects();
    $amounts = $get_total->fetch_revenueDate($from, $to, $store);
    foreach($amounts as $amount){
        $revenue = $amount->total;
        $costSales = $amount->total_cost;
        $total_profit = $revenue - $costSales;
        echo "<p class='total_amount' style='background:var(--moreColor); color:#fff; text-decoration:none; padding:10px; width:30%; float:right'>Gross Profit: ₦".number_format($total_profit, 2)."</p>";
    }
?>
