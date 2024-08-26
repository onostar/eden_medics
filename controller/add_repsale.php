<?php
// instantiate class
include "../classes/dbh.php";
include "../classes/select.php";
include "../classes/inserts.php";
    session_start();
    $store = $_SESSION['store_id'];
    $sales_type = "Repsale";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        if(isset($_GET['invoice'])){
            $invoice = $_GET['invoice'];
        }
        if(isset($_GET['sales_item'])){
            $item = $_GET['sales_item'];
            $customer = $_GET['customer'];
            
        }
    $_SESSION['customer'] = $customer;
    $quantity = 1;
    $date = date("Y-m-d H:i:s");
    
    //get selling price
    $get_item = new selects();
    $rows = $get_item->fetch_details_cond('items', 'item_id', $item);
     if(gettype($rows) == 'array'){
        foreach($rows as $row){
            $price = ($row->wholesale_cost * ($row->markup/100)) + $row->wholesale_cost;
            $name = $row->item_name;
            $cost = $row->wholesale_cost;
            $markup = $row->markup;
            // $department = $row->department;
        }
        //get quantity from inventory
        $get_qty = new selects();
        $qtyss = $get_qty->fetch_details_2cond('inventory', 'store', 'item', $store, $item);
        if(gettype($qtyss) == 'array'){
            $get_qtys = new selects();
            $sums = $get_qtys->fetch_sum_double('inventory', 'quantity', 'store', $store, 'item', $row->item_id);
            foreach($sums as $sum){
                $qty = $sum->total;
            }
        }
        
        if(gettype($qtyss) == 'string'){
            $qty = 0;
        }
        $sales_cost = $quantity * $cost;
        /* $sales_data = array(
            'item' => $item,
            'invoice' => $invoice,
            'store' => $store,
            'sales-type' => $sales_type,
            'customer' => $customer,
            'quantity' => $quantity,
            'markup' => $markup,
            'price' => $price,
            'total_amount' => $price,
            'cost' => $sales_cost,
            'posted_by' => $user_id,
            'post_date' => $date
        ); */
            if($qty == 0){
                echo "<script>
                    alert('$name has zero quantity! Cannot proceed');
                    </script>";
                    include "rep_details.php";
            }else if($price == 0){
                echo "<div class='notify'><p><span>$name</span> does not have selling price! Cannot proceed</p></div>";
            }else{
                
                //insert into sales order
                /* $sell_item = new add_data('sales', $sales_data);
                $sell_item->create_data(); */
                $sell_item = new post_sales($item, $invoice, $quantity, $price, $price, $user_id, $sales_cost, $store, $sales_type, $customer, $date, $markup);
                $sell_item->add_sales();
                if($sell_item){

        ?>
<!-- display sales for this invoice number -->
<div class="notify"><p><span><?php echo $name?></span> added to sales order</p></div>
<?php
    include "rep_details.php";
                }
            }
?>
   
    
<?php
         }
    }else{
        header("Location: ../index.php");
    } 
?>