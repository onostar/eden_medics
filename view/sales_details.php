<div id="sales_details">
<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/update.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        // echo $user_id;
    
    if(isset($_GET['invoice'])){
        $invoice = $_GET['invoice'];
       
        //get invoice details

?>


<div class="displays all_details">
    <!-- <div class="info"></div> -->
    <button class="page_navs" id="back" onclick="showPage('post_sales_order.php')"><i class="fas fa-angle-double-left"></i> Back</button>
    <div class="guest_name">
        <div class="displays allResults" id="payment_det">
        
            <div class="payment_details" style="width:70%;">
                <h3 style="background:var(--otherColor); color:#fff; padding:10px;">Items on Invoice No. <?php echo $invoice?></h3>
                <table id="guest_payment_table" class="searchTable">
                    <thead>
                        <tr>
                            <td>S/N</td>
                            <td>Item</td>
                            <td>Quantity</td>
                            <td>Unit price</td>
                            <td>Amount</td>
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
                            <td><?php echo number_format($row->total_amount, 2)?></td>
                            
                        </tr>
                        
                        <?php $n++; }?>
                    </tbody>
                </table>
            </div>
            <div class="amount_due" style="width:70%;">
                <section>
                    <label for="discount" style="color:red;">Discount</label><br>
                    <input type="text" name="discount" id="discount" style="padding:5px;border-radius:5px;" value="0">
                </section>
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
            <div class="close_stockin add_user_form" style="width:50%; margin:0;">
            <section class="addUserForm">
                <div class="inputs" style="display:flex; flex-wrap:wrap">
                    <input type="hidden" name="total_amount" id="total_amount" value="<?php echo $detail->total?>">
                    <input type="hidden" name="sales_invoice" id="sales_invoice" value="<?php echo $invoice?>">
                    <input type="hidden" name="store" id="store" value="<?php echo $store?>">
                    <div class="data">
                        <label for="payment_type">Payment options</label>
                        <select name="payment_type" id="payment_type" onchange="checkMode(this.value)">
                            <option value="" selected>Select payment type</option>
                            <option value="Cash">CASH</option>
                            <option value="POS">POS</option>
                            <option value="Transfer">TRANSFER</option>
                            <option value="Multiple">MULTIPLE PAYMENT</option>

                        </select>
                    </div>
                    <div class="inputs" id="multiples">
                    <div class="data">
                        <label for="">Cash paid</label>
                        <input type="text" name="multi_cash" id="multi_cash" value="0">
                    </div>
                    <div class="data">
                        <label for="">POS</label>
                        <input type="text" name="multi_pos" id="multi_pos" value="0">
                    </div>
                    <div class="data">
                        <label for="">Transfer</label>
                        <input type="text" name="multi_transfer" id="multi_transfer" value="0">
                    </div>
                </div>
                    <div class="data" id="selectBank">
                        <select name="bank" id="bank">
                            <option value=""selected>Select Bank</option>
                            <?php
                                $get_bank = new selects();
                                $rows = $get_bank->fetch_details('banks');
                                foreach($rows as $row):
                            ?>
                            <option value="<?php echo $row->bank_id?>"><?php echo $row->bank?>(<?php echo $row->account_number?>)</option>
                            <?php endforeach?>
                        </select>
                    </div>
                    <div class="data">
                        <button onclick="postSalesOrder()" style="background:green; padding:8px; border-radius:5px;font-size:.9rem;">Post and Print <i class="fas fa-print"></i></button>
                    </div>
                </div>
            </section>
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