<div id="add_bank" class="displays">
    <div class="info"></div>
    <div class="add_user_form" style="width:50%">
        <h3>Create Banks for payment</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <form class="addUserForm">
            <div class="inputs">
                <div class="data">
                    <label for="bank">Bank</label>
                    <input type="text" name="bank" id="bank" placeholder="Enter Bank Name" required>
                </div>
                <div class="data">
                    <label for="account_num">Account Number</label>
                    <input type="text" name="account_num" id="account_num" placeholder="0033421100" required>
                </div>
            </div>
            <div class="inputs">
                <button type="submit" id="add_bank" name="add_bank" onclick="addBank()">Add Bank <i class="fas fa-layer-bank"></i></button>
            </div>
        </form>    
    </div>
</div>
