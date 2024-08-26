<?php
    include "../classes/dbh.php";
    include "../classes/select.php";
?>
<div id="add_category" class="displays">
    <div class="info"></div>
    <div class="add_user_form" style="width:50%">
        <h3>Create item sub-categories</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <form class="addUserForm">
            <div class="inputs">
                <div class="data">
                    <label for="department">Select department</label>
                    <select name="department" id="department">
                        <option value=""selected required>Choose Category</option>
                        <?php
                            $get_dep = new selects();
                            $rows = $get_dep->fetch_details('departments');
                            foreach($rows as $row){
                        ?>
                        <option value="<?php echo $row->department_id?>"><?php echo $row->department?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="data">
                    <label for="category">Sub-category</label>
                    <input type="text" name="category" id="category" placeholder="Enter sub-category" required>
                </div>
                
            </div>
            <div class="inputs">
                <button type="submit" id="add_cat" name="add_cat" onclick="addCategory()">Save record <i class="fas fa-layer-group"></i></button>
            </div>
        </form>    
    </div>
</div>
