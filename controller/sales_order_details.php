<?php
    $store = $_SESSION['store_id'];
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
                    <a style="color:#fff; background:green;border-radius:4px;padding:5px 8px;" href="javascript:void(0)" title="increase quantity" onclick="increaseQtyOrder('<?php echo $detail->sales_id?>', '<?php echo $detail->item?>')"><i class="fas fa-arrow-up"></i></a>
                    <a style="color:#fff; background:var(--primaryColor);border-radius:4px;padding:5px 8px;" href="javascript:void(0)" title="decrease quantity" onclick="reduceQtyOrder('<?php echo $detail->sales_id?>')"><i class="fas fa-arrow-down"></i></a>
                    <a style="color:#fff; background:var(--otherColor);border-radius:4px;padding:5px 8px;" href="javascript:void(0)" title="show more options" onclick="showMoreOrder('<?php echo $detail->sales_id?>')"><i class="fas fa-chevron-up"></i></a>
                    <a style="color:#fff; background:var(--secondaryColor);border-radius:4px;padding:5px 8px;" href="javascript:void(0)" title="sell item in pack" onclick="getPackSo('<?php echo $detail->sales_id?>')"><i class="fas fa-box"></i> pack</a>
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
                    <a style="color:red; font-size:1rem" href="javascript:void(0) "title="delete purchase" onclick="deleteSalesOrder('<?php echo $detail->sales_id?>', '<?php echo $detail->item?>')"><i class="fas fa-trash"></i></a>
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
        echo "<p class='total_amount' style='background:red; color:#fff; text-decoration:none; width:auto; float:right; padding:10px;'>Total Due: ₦".number_format($total_amount, 2)."</p>";
    ?>
    <?php
        if(gettype($details) == "array"){
    ?>
    <div class="close_stockin add_user_form" style="width:20%; margin:0; box-shadow:none;background:none;">
        <section class="addUserForm">
            <div class="inputs">
                <input type="hidden" name="sales_invoice" id="sales_invoice" value="<?php echo $invoice?>">
                
                <div class="data" style="width:100%">
                    <button onclick="printSalesOrder()" style="background:green; padding:10px; border-radius:5px;font-size:.9rem; box-shadow:2px 2px 2px #c4c4c4;">Post to cashier <i class="fas fa-print"></i></button>
                </div>
            </div>
        </section>
    </div>
    <?php }?>
</div>