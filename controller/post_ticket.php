<?php
// session_start();
// instantiate class
include "../classes/dbh.php";
include "../classes/update.php";
include "../classes/select.php";
include "../classes/inserts.php";
    session_start();
    if(isset($_SESSION['user_id'])){
        $user = $_SESSION['user_id'];
            $invoice = $_POST['sales_invoice'];
           
        
        //update all items with this invoice
        $update_invoice = new Update_table();
        $update_invoice->update('sales', 'sales_status', 'invoice', 1, $invoice);
            if($update_invoice){
                
?>
<div id="printBtn">
    <button onclick="printSalesTicket('<?php echo $invoice?>')">Print Ticket <i class="fas fa-print"></i></button>
</div>
<!--  -->
   
<?php
    // echo "<script>window.print();</script>";
                    // }
                }
            
        
    }else{
        header("Location: ../index.php");
    } 
?>