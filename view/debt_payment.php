<div id="pay_debts" class="displays" style="width:80%!important;margin:0 20px!important">
<?php
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/update.php";
    
?>

<?php
    if(isset($_GET['customer'])){
        $customer = $_GET['customer'];
       
        //get customer details
        $get_customer = new selects();
        $custs = $get_customer->fetch_details_cond('customers', 'customer_id', $customer);
        foreach($custs as $cust){
            $client = $cust->customer;
            $balance = $cust->wallet_balance;
        }

?>
    <div class="top_view">
        <button class="page_navs" id="back" onclick="showPage('pay_debt.php')"><i class="fas fa-angle-double-left"></i> Back</button>

        <?php
            //wallet balance
            echo "<p style='color:green;font-size:1.1rem;'>Wallet Balance: ₦".number_format($balance, 2)."</p>";
            
        ?>
    </div>

    <div id="debt_payment" class="all_details">
        <!-- <div class="info"></div> -->
            <div class="displays allResults" id="payment_det">
            
                <div class="payment_details">
                    <h3 style="width:100%; background:var(--otherColor); color:#fff;padding:5px;font-size:1rem">Showing all unpaid invoices for <?php echo $client?></h3>
                    <table id="data_table" class="searchTable">
                    <thead>
                    <tr style="background:var(--primaryColor)">
                            <td>S/N</td>
                            <td>Invoice</td>
                            <td>Items</td>
                            <td>Amount</td>
                            <td>Date</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            //get transaction history
                            $get_transactions = new selects();
                            $details = $get_transactions->fetch_details_2cond('debtors', 'customer', 'debt_status', $customer, 0);
                            $n = 1;
                            if(gettype($details) === 'array'){
                            foreach($details as $detail){

                        ?>
                        <tr>
                            <td style="text-align:center; color:red;"><?php echo $n?></td>
                            <td><a style="color:green" href="javascript:void(0)" title="View invoice details" onclick="viewCustomerInvoice('<?php echo $detail->invoice?>')"><?php echo $detail->invoice?></a></td>  
                            <td style="text-align:center">
                                <?php
                                    //get items in invoice;
                                    $get_items = new selects();
                                    $items = $get_items->fetch_count_cond('sales', 'invoice', $detail->invoice);
                                    echo $items;
                                ?>
                            </td>   
                            <td>
                                <?php 
                                    //get sum of invoice
                                    $get_sum = new selects();
                                    $sums = $get_sum->fetch_details_group('debtors', 'amount', 'invoice', $detail->invoice);
                                        $invoice_total = $sums->amount;
                                        echo "₦".number_format($invoice_total, 2);
                                ?>
                            </td>
                            <td style="color:var(--moreColor)"><?php echo date("d-m-Y", strtotime($detail->post_date));?></td>
                            <td>
                                <a style="color:#fff;background:var(--primaryColor); padding:5px; border-radius:5px" href="javascript:void(0)" title="View invoice details" onclick="payDebt('<?php echo $detail->invoice?>', '<?php echo $customer?>', '<?php echo $balance?>', '<?php echo $invoice_total?>')">Pay <i class="fas fa-hand-holding-usd"></i></a>
                            </td>
                        </tr>
                        <?php $n++; }}?>
                    </tbody>
                </table>
                <?php
                    if(gettype($details) == "string"){
                        echo "<p class='no_result'>'$details'</p>";
                    }
                    // get sum
                    $get_total = new selects();
                    $amounts = $get_total->fetch_sum_double('debtors', 'amount', 'customer', $customer, 'debt_status', 0);
                    foreach($amounts as $amount){
                        echo "<p class='total_amount' style='color:red; font-size:1rem;'>Total Due: ₦".number_format($amount->total, 2)."</p>";
                    }
                ?>
                </div>
                
        <div id="customer_invoices">

        </div>
    </div>
<?php
            }
        
   
?>
</div>
