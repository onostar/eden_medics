<style>
    .sales_receipt{
    padding:10px;
}
.sales_receipt h2{
    font-size:.9rem;
}
.sales_receipt h2, .sales_receipt p{
    text-align:center;
    font-size:.8rem;
    padding:0;
    margin:0;
}
.receipt_head{
    margin:5px;
}
.sales_receipt .receipt_head{
    display:flex;
    justify-content: center;
    gap:.5rem;
    margin:2px 0;
}
.sales_receipt .total_amount{
    text-align: right;
    font-size:.8rem;
    margin:5px 0;
}

.sales_receipt .sold_by{
    text-align: left;
    font-size:.8rem;

}
.sales_receipt table{
    width:100%!important;
    margin:10px auto!important;
    box-shadow:none;
    border:1px solid #222;
    border-collapse: collapse;
}
.sales_receipt table thead tr td{
    font-size:.8rem;
    padding:2px;

}
.sales_receipt table td{
    border:1px solid #222;
    padding:2px;
}
.item_categories{
    padding:20px;
}
</style>
<?php
    
// session_start();
// instantiate class
include "../classes/dbh.php";
include "../classes/select.php";
    session_start();
    if(isset($_GET['receipt'])){
        $user = $_SESSION['user_id'];
        $invoice = $_GET['receipt'];
?>
<div class="receipt_head">
<h2><?php echo $_SESSION['company'];?></h2>
        <?php
        //get store
        $get_date = new selects();
        $dt = $get_date->fetch_details_group('transfers', 'post_date', 'invoice', $invoice);
        ?>
    <p>Date: <?php echo date("d-m-Y", strtotime($dt->post_date))?>, <?php echo date("h:m:ia", strtotime($dt->post_date))?></p>
        <p><?php echo $invoice?></p>
    <?php
        //get store
        $get_store = new selects();
        $strs = $get_store->fetch_details_group('transfers', 'to_store', 'invoice', $invoice);
        $get_str_name = new selects();
        $str_name = $get_str_name->fetch_details_group('stores', 'store', 'store_id', $strs->to_store);
    ?>
</div>
<div class="displays allResults sales_receipt">
    
    <h4>Items transferred to <?php echo $str_name->store?></h4>
    <table id="postsales_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Item</td>
                <td>Qty</td>
                <td>Rate</td>
                <td>Amount</td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_items = new selects();
                $details = $get_items->fetch_details_cond('transfers','invoice', $invoice);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr style="font-size:.9rem">
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td style="color:var(--moreClor);">
                    <?php
                        //get category name
                        $get_item_name = new selects();
                        $item_name = $get_item_name->fetch_details_group('items', 'item_name', 'item_id', $detail->item);
                        echo $item_name->item_name;
                    ?>
                </td>
                <td style="text-align:center; color:red;"><?php echo $detail->quantity?>
                    
                </td>
                <td>
                    <?php
                        //get item price
                        $get_item_price = new selects();
                        $item_price = $get_item_name->fetch_details_group('items', 'sales_price', 'item_id', $detail->item);
                        echo number_format($item_price->sales_price);
                    ?>
                </td>
                <td>
                    <?php
                        //get total amount
                        $get_item_price = new selects();
                        $item_price = $get_item_name->fetch_details_group('items', 'sales_price', 'item_id', $detail->item);
                        $total_amount = $item_price->sales_price * $detail->quantity;
                        echo number_format($total_amount);
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
        //get total goods
        $get_goods = new selects();
        $goods = $get_goods->fetch_sum_single('transfers', 'quantity', 'invoice', $invoice);
        foreach($goods as $good){
            $total_goods = $good->total;
        }
        echo "<p class='total_amount' style='color:green'>Total product: $total_goods</p>";
        // get sum;
        $get_total = new selects();
        $amounts = $get_total->fetch_sum_con('transfers', 'sales_price', 'quantity', 'invoice', $invoice);
        foreach($amounts as $amount){
            $total_amount = $amount->total;
        }
        echo "<p class='total_amount' style='color:green'>Total amount: ₦".number_format($total_amount, 2)."</p>";
/*
        // get amount paid from payments;
        $get_paid = new selects();
        $amt_paids = $get_paid->fetch_sum_single('payments', 'amount_paid', 'invoice', $invoice);
        foreach($amt_paids as $amt){
            $amount_paid = $amt->total;
        }
        $rows = $get_paid->fetch_details_cond('payments', 'invoice', $invoice);
        foreach($rows as $row){
            $amount_paid = $row->amount_paid;
            $amount_due = $row->amount_due;
            $discount = $row->discount;
            $balance = $amount_due - $amount_paid - $discount;
            //amount due
            echo "<p class='total_amount' style='color:green'>Amount due: ₦".number_format($total_amount, 2)."</p>";
            //amount paid
            echo "<p class='total_amount' style='color:green'>Amount Paid: ₦".number_format($amount_paid, 2)."</p>";
            //discount
            echo "<p class='total_amount' style='color:green'>Discount: ₦".number_format($discount, 2)."</p>";
            //balance
            echo "<p class='total_amount' style='color:green'>Debit Balance: ₦".number_format($balance, 2)."</p>";
        } */
        //get amount due
        /* if($pay_mode == "Credit"){
            echo "<p class='total_amount' style='color:green'>Amount due: ₦".number_format($total_amount, 2)."</p>";
            //amount paid
            echo "<p class='total_amount' style='color:green'>Amount Paid: ₦".number_format($amount_paid, 2)."</p>";
        }else{
            //amount due
            if($discount != 0){
                echo "<p class='total_amount' style='color:green'>Amount due: ₦".number_format($total_amount, 2)."</p>";
            }
            
            //amount paid
            echo "<p class='total_amount' style='color:green'>Amount Paid: ₦".number_format($amount_paid, 2)."</p>";

            //discount
            if($discount != 0){
                echo "<p class='total_amount' style='color:green'>Discount: ₦".number_format($discount, 2)."</p>";

            }
        } */
        //sold by
        $get_seller = new selects();
        $row = $get_seller->fetch_details_group('users', 'full_name', 'user_id', $user);
        echo ucwords("<p class='sold_by'>Transferred by: <strong>$row->full_name</strong></p>");
    ?>
    <!-- <p style="margin-top:20px;text-align:center"><strong>Thanks for your patronage!</strong></p> -->
</div> 
   
<?php
    echo "<script>window.print();
    window.close();</script>";
                    // }
                }
            // }
        
    // }
?>