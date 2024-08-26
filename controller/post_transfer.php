<?php

    include "../classes/dbh.php";
    include "../classes/update.php";

    if(isset($_GET['invoice'])){
        $invoice = $_GET['invoice'];
        
        //update all transferred items with this invoice
        $update = new Update_table();
        $update->update('transfers', 'transfer_status', 'invoice', 1, $invoice);

        if($update){
?>
            <div id="printBtn">
                <button onclick="printTransferReceipt('<?php echo $invoice?>')">Print Receipt <i class="fas fa-print"></i></button>
            </div>
            <div class="notify"><p><i class="fas fa-thumbs-up"></i> Items transferred successfully!</p></div>
<?php
        }
    }
    