<?php
    include "../classes/dbh.php";
    include "../classes/select.php";
?>

<div id="add_room" class="displays">
    <div class="info"></div>
    <div class="add_user_form" style="width:35%">
        <h3 style="background:var(--moreColor)">Create items</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs">
                <div class="data" style="width:100%; margin:10px 0;">
                    <label for="department">Category</label>
                    <select name="department" id="department" onchange="getCategory(this.value)">
                        <option value=""selected required>Select item category</option>
                        <?php
                            $get_dep = new selects();
                            $rows = $get_dep->fetch_details('departments');
                            foreach($rows as $row){
                        ?>
                        <option value="<?php echo $row->department_id?>"><?php echo $row->department?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="data" style="width:100%; margin:10px 0">
                    <label for="item_category"> Sub-Category</label>
                    <select name="item_category" id="item_category">
                        <option value=""selected required>Select item sub-category</option>
                        
                    </select>
                </div>
                <div class="data" style="width:100%; margin:10px 0">
                    <label for="item"> Item Name</label>
                    <input type="text" name="item" id="item" required placeholder="Input item name">
                </div>
                <div class="data" style="width:100%; margin:10px 0">
                    <label for="barcode"> Barcode</label>
                    <input type="text" name="barcode" id="barcode" required placeholder="Input item barcode">
                </div>
            </div>
            <div class="inputs">
                <div class="data">
                    <button type="submit" id="add_item" name="add_item" onclick="addItem()">Save record <i class="fas fa-save"></i></button>
                </div>
            </div>
        </section>    
    </div>
</div>
