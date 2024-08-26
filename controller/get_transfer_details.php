<?php
    session_start();
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        $store_from = $_SESSION['store_id'];
        if(isset($_GET['item'])){
            $item = $_GET['item'];
        
    
    $invoice = $_SESSION['invoice'];
    $store_to = $_SESSION['store_to'];
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    //get item name
    //get item id first from inventory
    $get_id = new selects();
    $ids = $get_id->fetch_details_cond('inventory', 'inventory_id', $item);
    foreach($ids as $id){
        $item_id = $id->item;
        $expiration = $id->expiration_date;
        $quantity = $id->quantity;
    }
    $get_name = new selects();
    $item_names = $get_name->fetch_details_group('items', 'item_name', 'item_id', $item_id);
    $name = $item_names->item_name;
    //get item in inventory
    $get_item = new selects();
    $rows = $get_item->fetch_details_2cond('inventory', 'inventory_id', 'store', $item, $store_from);
     if(gettype($rows) == 'array'){
        foreach($rows as $row):
           
    ?>
    <div class="add_user_form" style="width:50%!important; margin:0!important">
        <h3 style="background:var(--secondaryColor); text-align:left;"><?php echo strtoupper($name). " (".$row->quantity.")"?></h3>
        <section class="addUserForm" style="text-align:left!important;">
            <div class="inputs" style="flex-wrap:wrap;gap:.8rem 1.2rem;justify-content:start;">
                <!-- <div class="data item_head"> -->
                    <input type="hidden" name="posted_by" id="posted_by" value="<?php echo $user_id?>" required>
                    <input type="hidden" name="store_from" id="store_from" value="<?php echo $store_from?>" required>
                    <input type="hidden" name="store_to" id="store_to" value="<?php echo $store_to?>" required>
                    <input type="hidden" name="invoice" id="invoice" value="<?php echo $invoice?>" required>
                    <input type="hidden" name="item_id" id="item_id" value="<?php echo $item?>" required>
                    <input type="hidden" name="expiration" id="expiration" value="<?php echo $item?>" required>
                <div class="data" style="width:40%; margin:5px;">
                    <label for="quantity">Quantity</label>
                    <input type="number" name="quantity" id="quantity">
                </div>
                
                <div class="data" style="width:auto; margin:5px;">
                    <button type="submit" id="stockin" name="stockin" title="stockin item" onclick="transfer()"><i class="fas fa-plus"></i></button>
                </div>
            </div>
        </section>   
    </div>
    
<?php
            
    endforeach;
     }
     if(gettype($rows) === "string"){
        echo "<div class='notify' style='padding:4px!important'><p style='color:#fff!important'><span>$name</span> has zero quantity! Cannot proceed</p>";
     }
    }
    }else{
        header("Location: ../index.php");
    } 
?>