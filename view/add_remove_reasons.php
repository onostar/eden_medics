<div id="remove_reason" class="displays">
    <div class="info" style="width:50%; margin:0"></div>
    <div class="add_user_form" style="width:50%; margin:0">
        <h3>Add reasons for removal</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <form class="addUserForm">
            <div class="inputs">
                <div class="data">
                    <label for="reason">Reason</label>
                    <input type="text" name="reason" id="reason" placeholder="Input reason for removal" required>
                </div>
                <div class="data">
                    <button type="submit" id="add_reason" name="add_reason" onclick="addReason()">Save record <i class="fas fa-layer-group"></i></button>
                </div>
            </div>
        </form>    
    </div>
</div>
