<div id="guest_details">
<?php
    session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/update.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        // echo $user_id;
    
    if(isset($_GET['payment_id'])){
        $payment = $_GET['payment_id'];
        //get invoice;
        $get_invoice = new selects();
        $payment_invoices = $get_invoice->fetch_details_cond('payments', 'payment_id', $payment);
        foreach($payment_invoices as $payment_invoice){
            $invoice = $payment_invoice->invoice;
            $type = $payment_invoice->sales_type;
            $customer = $payment_invoice->customer;

        }
        //get invoice details

?>


<div class="displays all_details">
    <!-- <div class="info"></div> -->
    <button class="page_navs" id="back" onclick="showPage('revenue_report.php')"><i class="fas fa-angle-double-left"></i> Back</button>
    <div class="guest_name">
        <?php
            //check invoice sales type
            if($type == "Wholesale"){
                //get customer name
                $get_cust = new selects();
                $client = $get_cust->fetch_details_group('customers', 'customer', 'customer_id', $customer);
        ?>
        <h4>Items sold to <?php echo strtoupper($client->customer)?> (<?php echo $invoice?>)</h4>
        <?php }else{?>
        <h4>Items on Invoice => <?php echo $invoice?> </h4>
        <?php } ?>
        <div class="displays allResults" id="payment_det">
        
            <div class="payment_details">
                <h3>Invoice Details</h3>
                <table id="guest_payment_table" class="searchTable">
                    <thead>
                        <tr>
                            <td>S/N</td>
                            <td>Item</td>
                            <td>Quantity</td>
                            <td>Unit price</td>
                            <td>Discocunt</td>
                            <td>Amount</td>
                            <td>Total Discount</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $n = 1;
                            $get_items = new selects();
                            $rows = $get_items->fetch_details_cond('sales', 'invoice', $invoice);
                            foreach($rows as $row){
                        ?>
                        <tr>
                            <td style="text-align:center; color:red;"><?php echo $n?></td>
                            <td>
                                <?php 
                                    //get item name
                                    $get_name = new selects();
                                    $names = $get_name->fetch_details_group('items', 'item_name', 'item_id', $row->item);
                                    echo strtoupper($names->item_name);
                                ?>
                            </td>
                            <td style="text-align:center; color:var(--otherColor)"><?php echo $row->quantity?></td>
                            <td><?php echo number_format($row->price, 2);?></td>
                            <td style='color:red'><?php echo number_format($row->discount, 2);?></td>
                            <td><?php echo number_format($row->total_amount, 2)?></td>
                            <td style='color:red'><?php 
                                $total_disc = $row->discount * $row->quantity;
                            echo number_format($total_disc, 2)?></td>
                            
                        </tr>
                        
                        <?php $n++; }?>
                    </tbody>
                </table>
            </div>
            <div class="amount_due">
                <h2>Total Amount: 
                <?php
                    //get total amount
                    $get_total = new selects();
                    $details = $get_total->fetch_sum_single('sales', 'total_amount', 'invoice', $invoice);
                    foreach($details as $detail){
                        echo "₦".number_format($detail->total, 2);
                    }
                ?>
                </h2>

                
            </div>
            
    </div>
    
</div>
<?php
            }
        
    }else{
        header("Location: ../index.php");
    }
?>
</div>