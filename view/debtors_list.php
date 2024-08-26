<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";


?>
<div id="debtorsList" class="displays management">
    
<div class="displays allResults new_data" id="revenue_report" style="width:60%!important; margin:0 20px!important;">
    <h2>Debtors List</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchDebtors" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Sales report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--otherColor)">
                <td>S/N</td>
                <td>Customer</td>
                <td>Amount Due</td>
                <td></td>
                
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_condGroup('debtors', 'debt_status', 0, 'customer');
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td>
                    <?php
                        //get customer
                        $get_customer = new selects();
                        $client = $get_customer->fetch_details_group('customers', 'customer', 'customer_id', $detail->customer); 
                        echo $client->customer;
                    ?>
                </td>
                <td>
                    <?php 
                        $get_sum = new selects();
                        $sums = $get_sum->fetch_sum_double('debtors', 'amount', 'customer', $detail->customer, 'debt_status', 0);
                        foreach($sums as $sum){
                            echo "₦".number_format($sum->total, 2);
                        }
                    ?>
                </td>
            
                <td>
                    <a style="color:#fff;background:var(--primaryColor); padding:5px; border-radius:5px" href="javascript:void(0)" title="View invoice details" onclick="showPage('debt_details.php?customer=<?php echo $detail->customer?>')">View <i class="fas fa-eye"></i></a>
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
        $amounts = $get_total->fetch_sum_single('debtors', 'amount', 'debt_status', 0);
        foreach($amounts as $amount){
            echo "<p class='total_amount' style='color:green'>Total: ₦".number_format($amount->total, 2)."</p>";
        }
    ?>

</div>

<script src="../jquery.js"></script>
<script src="../script.js"></script>