<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
?>

<div id="transfer_details" class="displays" style="width:80%!important; margin:20px!important">
    <?php
        //get invoice
        if(isset($_GET['invoice'])){
            $invoice = $_GET['invoice'];
            $_SESSION['invoice'] = $invoice;
            //get store

        }
    ?>
    <button class="page_navs" id="back" onclick="showPage('transfer_report.php')"><i class="fas fa-angle-double-left"></i> Back</button>

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
                        <td>Status</td>
                        <td>Accepted by</td>
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
                        <td style="text-align:center; color:green"><?php echo $detail->quantity?></td>
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
                            <?php
                                //get status
                                if($detail->transfer_status == 2){
                                    echo "<p style='color:green'><i class='fas fa-check'></i> Accepted</p>";
                                }elseif($detail->transfer_status == 1){
                                    echo "<p style='color:var(--otherColor)'><i class='fas fa-spinner'></i> Pending</p>";
                                }elseif($detail->transfer_status == -2){
                                    echo "<p style='color:var(--secondaryColor)'><i class='fas fa-undo'></i> Accepted return</p>";
                                
                                }else{
                                    echo "<p style='color:red'><i class='fas fa-cancel'></i> Returned</p>";
                                }
                            ?>
                        </td>
                        <td>
                    <?php
                        
                        if($detail->transfer_status == 1){
                            echo "";
                        }else{
                            //get posted by
                            $get_posted_by = new selects();
                            $post_by = $get_posted_by->fetch_details_group('users', 'full_name', 'user_id', $detail->accept_by);
                            echo $post_by->full_name;

                        }
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
            <?php }?>
        </div> 
</div>
<?php
    }else{
        header("Location: ../index.php");
    }
?>