<?php
    // $store = $_SESSION['store_id'];
    $customer = $_SESSION['customer'];
?>
<div class="displays allResults" id="stocked_items">
    <!-- <h2>Items in sales order</h2> -->
    <table id="addsales_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Item name</td>
                <td>Quantity</td>
                <td>Unit sales</td>
                <td>Amount</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_items = new selects();
                $details = $get_items->fetch_details_cond('sales','invoice', $invoice);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td style="color:var(--moreClor);">
                    <?php
                        //get category name
                        $get_item_name = new selects();
                        $item_name = $get_item_name->fetch_details_group('items', 'item_name', 'item_id', $detail->item);
                        echo $item_name->item_name;
                    ?>
                </td>
                <td style="text-align:center; color:red;font-size:1,1rem">
                    <span style="font-size:1.2rem; margin:0 2px"><?php echo $detail->quantity?></span>
                    <a style="color:#fff; background:green;border-radius:4px;padding:5px 8px;" href="javascript:void(0)" title="increase quantity" onclick="increaseQtyRepsale('<?php echo $detail->sales_id?>', '<?php echo $detail->item?>')"><i class="fas fa-arrow-up"></i></a>
                    <a style="color:#fff; background:var(--primaryColor);border-radius:4px;padding:5px 8px;" href="javascript:void(0)" title="decrease quantity" onclick="reduceQtyRepsale('<?php echo $detail->sales_id?>')"><i class="fas fa-arrow-down"></i></a>
                    <a style="color:#fff; background:var(--otherColor);border-radius:4px;padding:5px 8px;" href="javascript:void(0)" title="show more options" onclick="showMoreRepsale('<?php echo $detail->sales_id?>')"><i class="fas fa-pen"></i></a>
                    <!-- <a style="color:#fff; background:var(--secondaryColor);border-radius:4px;padding:5px 8px;" href="javascript:void(0)" title="sell item in pack" onclick="getWholesalePack('<?php echo $detail->sales_id?>')"><i class="fas fa-box-open"></i> pack</a> -->
                    <a style="color:#fff; background:brown;border-radius:4px;padding:5px 8px;" href="javascript:void(0)" title="sell item in role or carton" onclick="getCartonRole('<?php echo $detail->sales_id?>')"><i class="fas fa-box-open"></i> ctn</a>
                </td>
                <td>
                    <?php 
                        echo "₦".number_format($detail->price, 2);
                    ?>
                </td>
                <td>
                    <?php 
                        echo "₦".number_format($detail->total_amount, 2);
                    ?>
                </td>
                <td>
                    <a style="color:red; font-size:1rem" href="javascript:void(0) "title="delete purchase" onclick="deleteRepsale('<?php echo $detail->sales_id?>', '<?php echo $detail->item?>')"><i class="fas fa-trash"></i></a>
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
        $amounts = $get_total->fetch_sum_con('sales', 'price', 'quantity', 'invoice', $invoice);
        foreach($amounts as $amount){
            $total_amount = $amount->total;
        }
        // $total_worth = $total_amount * $total_qty;
        echo "<p class='total_amount' style='background:red; color:#fff; text-decoration:none; width:auto; float:right; padding:10px;font-size:1.1rem;'>Total Due: ₦".number_format($total_amount, 2)."</p>";
    ?>
    <!-- discount -->
    <section style="float:right;margin:5px;">
        <!-- <label for="discount" style="color:red;">Discount</label><br> -->
        <input type="hidden" name="discount" id="discount" style="padding:5px;border-radius:5px;" value="0">
    </section>
    <?php
        if(gettype($details) == "array"){
    ?>
    <div class="close_stockin add_user_form" style="width:50%; margin:0;">
        <section class="addUserForm">
            <div class="inputs" style="display:flex;flex-wrap:wrap">
                <input type="hidden" name="total_amount" id="total_amount" value="<?php echo $total_amount?>">
                <input type="hidden" name="sales_invoice" id="sales_invoice" value="<?php echo $invoice?>">
                <input type="hidden" name="store" id="store" value="<?php echo $store?>">
                <input type="hidden" name="customer_id" id="customer_id" value="<?php echo $customer?>">
                <div class="data">
                    <label for="payment_type">Payment options</label>
                    <select name="payment_type" id="payment_type" onchange="checkRepMode(this.value)">
                        <option value="" selected>Select payment type</option>
                        <option value="Cash">CASH</option>
                        <option value="POS">POS</option>
                        <option value="Transfer">TRANSFER</option>
                        <option value="Credit">CREDIT</option>
                        <option value="Multiple">MULTIPLE PAYMENT</option>
                        <option value="Wallet">WALLET</option>
                        <option value="Deposit">DEPOSIT</option>
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
                            $rows = $get_bank->fetch_details('banks', 10, 10);
                            foreach($rows as $row):
                        ?>
                        <option value="<?php echo $row->bank_id?>"><?php echo $row->bank?>(<?php echo $row->account_number?>)</option>
                        <?php endforeach?>
                    </select>
                </div>
                <div class="data" id="account_balance">
                    <?php
                        //get wallet balance
                        $get_bal = new selects();
                        $bal = $get_bal->fetch_details_group('customers', 'wallet_balance', 'customer_id', $customer);
                        $wallet = $bal->wallet_balance;
                    ?>
                    <label for="wallet">Wallet balance</label>
                    <input type="hidden" name="wallet" id="wallet" value="<?php echo $wallet?>">
                    <input type="text" value="<?php echo "₦".number_format($wallet, 2)?>">

                </div>
                <div class="data" id="deposited">
                    <label for="deposit">Deposit amount</label>
                    <input type="number" name="deposit" id="deposit" value="0">
                </div>
                <div class="data">
                    <button onclick="postRepsale()" style="background:green; padding:8px; border-radius:5px;font-size:.9rem;">Save and Print <i class="fas fa-print"></i></button>
                </div>
            </div>
        </section>
    </div>
    <?php }?>
</div>    
