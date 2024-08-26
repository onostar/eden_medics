<div id="reorder_levels">
<?php

    include "../classes/dbh.php";
    include "../classes/select.php";
    
    if(isset($_SESSION['success'])){
        echo $_SESSION['success'];
    }

?>

    <div class="info"></div>
    <div class="displays allResults">
        <h2>Adjust item reorder levels</h2>
        <hr>
        <div class="search">
            <input type="search" id="searchReorder level" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        </div>
        <table id="reorderTable" class="searchTable">
            <thead>
                <tr style="background:var(--otherColor)">
                    <td>S/N</td>
                    <td>Item code</td>
                    <td>item</td>
                    <td>reorder level</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
            <?php
                $n = 1;
                $select_cat = new selects();
                $rows = $select_cat->fetch_details('items');
                if(gettype($rows) == "array"){
                foreach($rows as $row):
            ?>
                <tr>
                    <td style="text-align:center;"><?php echo $n?></td>
                    
                    <td>
                        <?php 
                            echo "00".$row->item_id;
                        ?>
                    </td>
                    <td><?php echo $row->item_name?></td>
                    <td style="text-align:center">
                        <?php echo $row->reorder_level;?>
                    </td>
                    <td class="prices">
                        <a style="background:var(--moreColor)!important; color:#fff!important; padding:5px 8px; border-radius:5px;" href="javascript:void(0)" data-form="check<?php echo $row->item_id?>" class="each_prices" onclick="getForm('<?php echo $row->item_id?>', 'get_reorder_level.php');"><i class="fas fa-pen"></i></a>
                    </td>
                </tr>
            <?php $n++; endforeach; }?>
            </tbody>

        </table>
        
        <?php
            if(gettype($rows) == "string"){
                echo "<p class='no_result'>'$rows'</p>";
            }
        ?>
    </div>
</div>