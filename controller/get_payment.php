<?php
    session_start();
    if(isset($_SESSION['user_id'])){
        $posted = $_SESSION['user_id'];
    }
    if (isset($_GET['invoice'])){
        $invoice = $_GET['invoice'];
    

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_item = new selects();
    $rows = $get_item->fetch_details_cond('debtors', 'invoice', $invoice);
     if(gettype($rows) == 'array'){
        foreach($rows as $row):
            
    ?>
    <div class="add_user_form priceForm">
        <h3 style="background:var(--primaryColor)">Post payment for <?php echo strtoupper($row->invoice)?></h3>
        <section class="addUserForm" style="text-align:left">
            <div class="inputs">
                <!-- <div class="data item_head"> -->
                    <input type="hidden" name="posted" id="posted" value="<?php echo $posted?>" required>
                    <input type="hidden" name="customer" id="customer" value="<?php echo $row->customer?>" required>
                    <input type="hidden" name="invoice" id="invoice" value="<?php echo $row->invoice?>" required>
                    <input type="hidden" name="amount" id="amount" value="<?php echo $row->amount?>" required>
                <div class="data">
                    <label for="">Select Payment mode</label>
                    <select name="mode" id="mode">
                        <option value=""selected>Select payment mode</option>
                        <option value="Cash">Cash</option>
                        <option value="POS">POS</option>
                        <option value="Transfer">Transfer</option>
                    </select>
                </div>
                <div class="data">
                    <button type="submit" id="post_pay" name="post_pay" onclick="postOtherPayment()">Post <i class="fas fa-save"></i></button>
                </div>
                
            </div>
        </section>   
    </div>
    
<?php
    endforeach;
     }
    }    
?>