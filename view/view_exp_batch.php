<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
?>

<div id="adjust_expiration" class="displays" style="width:70%!important; margin:20px!important">
    <?php
        //get store
        $get_store = new selects();
        $strs = $get_store->fetch_details_group('stores', 'store', 'store_id', $store);
        $store_name = $strs->store;
        //get invoice
        if(isset($_GET['item'])){
            $item = $_GET['item'];
            //get item details
            $get_name = new selects();
            $names = $get_name->fetch_details_cond('items', 'item_id', $item);
            foreach($names as $name){
                $item_name = $name->item_name;
                // $cost = $name->cost_price;
            }
        }
    ?>
    <button class="page_navs" id="back" onclick="showPage('adjust_expiration.php')"><i class="fas fa-angle-double-left"></i> Back</button>
        <div class="info" style="width:90%; margin-left:40px"></div>
        <div class="displays allResults" id="stocked_items">
            <h3 style="background:var(--otherColor);color:#fff;text-align:center;padding:5px;"><?php echo $item_name?> batches in <?php echo $store_name?></h3>
            <table id="stock_items_table" class="searchTable">
                <thead>
                    <tr style="background:var(--moreColor)">
                        <td>S/N</td>
                        <!-- <td>Batch</td> -->
                        <td>Expiration</td>
                        <td>Quantity</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $n = 1;
                        $get_items = new selects();
                        $details = $get_items->fetch_details_2cond('inventory', 'store', 'item', $store, $item);
                        if(gettype($details) === 'array'){
                        foreach($details as $detail):
                    ?>
                    <tr>
                        <td style="text-align:center; color:red;"><?php echo $n?></td>
                        <!-- <td style="color:var(--moreClor);">
                            <?php
                                echo $detail->batch_number;
                            ?>
                        </td> -->
                        <td><?php echo date("jS M, Y", strtotime($detail->expiration_date))?></td>
                        <td style="text-align:center; color:green"><?php echo $detail->quantity?></td>
                        <td>
                            <a style="background:var(--moreColor)!important; color:#fff!important; padding:5px 8px; border-radius:5px;" href="javascript:void(0)"class="each_prices" onclick="getForm('<?php echo $detail->inventory_id?>', 'get_expiration.php');"><i class="fas fa-pen"></i></a>
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
                if(gettype($details) === 'array'){
                    $get_total = new selects();
                    $amounts = $get_total->fetch_sum_2con('inventory', 'cost_price', 'quantity', 'store', 'item', $store, $item);
                    foreach($amounts as $amount){
                        $total_amount = $amount->total;
                    }
                    // $total_worth = $total_amount * $total_qty;
                    echo "<p class='total_amount' style='color:red'>Total Cost: ₦".number_format($total_amount, 2)."</p>";
                ?>
            <?php }?>
        </div> 
</div>
<?php
    }else{
        header("Location: ../index.php");
    }
?>