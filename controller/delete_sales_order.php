<?php
        session_start();
    // if(isset($_GET['id'])){
    //     $id = $_GET['id'];
        $sales = $_GET['sales_id'];
        $item = $_GET['item_id'];
        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/delete.php";
// echo $item;
        //get item details
        $get_item = new selects();
        $row = $get_item->fetch_details_group('items', 'item_name', 'item_id', $item);
        $name = $row->item_name;
        //get invoice
        $get_invoice = new selects();
        $rows = $get_invoice->fetch_details_group('sales', 'invoice', 'sales_id', $sales);
        $invoice = $rows->invoice;
        //delete sales
        $delete = new deletes();
        $delete->delete_item('sales', 'sales_id', $sales);
        if($delete){
?>
<!-- display items with same invoice number -->
<div class="notify"><p><span><?php echo $name?></span> Removed from sales order</p></div>

<?php
include "sales_order_details.php";

            }            
        
    // }
?>