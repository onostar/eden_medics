<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
?>

<div id="stockin" class="displays">
    <?php
        //get invoice
        if(isset($_GET['invoice'])){
            $invoice = $_GET['invoice'];
            $_SESSION['invoice'] = $invoice;
            //get store
            $get_store = new selects();
            $strs = $get_store->fetch_details_group('transfers', 'to_store', 'invoice', $invoice);
            $to_store = $strs->to_store;
            //get store name
            $get_store_name = new selects();
            $store_names = $get_store_name->fetch_details_group('stores', 'store', 'store_id', $to_store);

        }
    ?>
    <button class="page_navs" id="back" onclick="showPage('pending_transfer.php')"><i class="fas fa-angle-double-left"></i> Back</button>
    <div class="add_user_form" style="width:50%; margin:10px 0;">
        <h3 style="background:var(--moreColor); text-align:left!important;" >Transfer items to store -> <?php echo $invoice?></h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs">
                
                <div class="data">
                    <input type="hidden" name="invoice" id="invoice" value="<?php echo $invoice?>">
                    <label for="vendor">Select store</label>
                    <select name="store_to" id="store_to">
                        <option value="<?php echo $to_store?>"selected required><?php echo $store_names->store?></option>
                        
                    </select>
                </div>
                <div class="data" style="width:100%; margin:10px 0">
                    <label for="">Enter item name</label>
                    <input type="text" name="item" id="item" required placeholder="Input item name or barcode" onkeyup="getItemTransfer(this.value)">
                        <div id="sales_item">
                            
                        </div>
                    
                </div>
            </div>
        </section>
    </div>
    <div class="info" style="width:100%; margin:0"></div>
    <div class="stocked_in">
        <div class="displays allResults" id="stocked_items">
            <h2>Items transfered with invoice <?php echo $invoice?></h2>
            <table id="stock_items_table" class="searchTable">
                <thead>
                    <tr style="background:var(--moreColor)">
                        <td>S/N</td>
                        <td>Item name</td>
                        <td>Quantity</td>
                        <td>Unit cost</td>
                        <td>Unit sales</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $n = 1;
                        $get_items = new selects();
                        $details = $get_items->fetch_details_2cond('transfers', 'from_store', 'invoice', $store, $invoice);
                        if(gettype($details) === 'array'){
                        foreach($details as $detail):
                            $get_ind = new selects();
                            $alls = $get_ind->fetch_details_cond('items', 'item_id', $detail->item);
                            foreach($alls as $all){
                                $cost_price = $all->cost_price;
                                $sales_price = $all->sales_price;
                                $itemname = $all->item_name;
                            }
                    ?>
                    <tr>
                        <td style="text-align:center; color:red;"><?php echo $n?></td>
                        <td style="color:var(--moreClor);">
                            <?php
                                echo $itemname;
                            ?>
                        </td>
                        <td style="text-align:center"><?php echo $detail->quantity?></td>
                        <td>
                            <?php 
                                echo "₦".number_format($detail->cost_price, 2);
                            ?>
                        </td>
                        <td>
                            <?php 
                                echo "₦".number_format($sales_price, 2);
                            ?>
                        </td>
                        <td>
                            <a style="color:red; font-size:1rem" href="javascript:void(0) "title="delete purchase" onclick="deleteTransfer('<?php echo $detail->transfer_id?>', <?php echo $detail->item?>)"><i class="fas fa-trash"></i></a>
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
                    $amounts = $get_total->fetch_sum_2con('transfers', 'cost_price', 'quantity', 'from_store', 'invoice', $store, $invoice);
                    foreach($amounts as $amount){
                        $total_amount = $amount->total;
                    }
                    // $total_worth = $total_amount * $total_qty;
                    echo "<p class='total_amount' style='color:red'>Total Cost: ₦".number_format($total_amount, 2)."</p>";
                ?>
                <div class="close_stockin">
                    <button onclick="postTransfer('<?php echo $invoice?>')" style="background:green; padding:8px; border-radius:5px;">Post transfer <i class="fas fa-upload"></i></button>
                </div>
            <?php }?>
        </div> 
    </div>
</div>
<?php
    }else{
        header("Location: ../index.php");
    }
?>