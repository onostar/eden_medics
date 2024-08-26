<?php
    include "../classes/dbh.php";
    include "../classes/select.php";
?>
<div id="add_rights" class="displays">
    <div class="add_user_form" style="width:30%; margin:10px;">
        <h3>Delete user rights</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <form class="addUserForm">
            <div class="inputs">
                <div class="data" style="width:100%; margin:10px 0;">
                    <label for="user">Select User</label>
                    <select name="user" id="user" onchange="getRights(this.value)">
                        <option value=""selected required>Select user</option>
                        <?php
                            $get_user = new selects();
                            $rows = $get_user->fetch_details_negCond1('users', 'user_role', 'admin');
                            foreach($rows as $row){
                        ?>
                        <option value="<?php echo $row->user_id?>"><?php echo $row->full_name?></option>
                        <?php } ?>
                    </select>
                </div>
                
                
            </div>
        </form>   
        <div class="info"></div>
    </div>
    <!-- all rights for selected user -->
    <div class="rights allResults">

    </div>
</div>
