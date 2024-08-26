<?php
    session_start();
    if(isset($_SESSION['user'])){
        $username = $_SESSION['user'];
    }
?>
<div id="update_password" class="displays">
    <!-- <h3>Update Password</h3>
    <hr> -->
    <!-- change password -->
    <div class="change_password">
        <div class="info" id="info"></div>
        <section method="POST">
            <h3>Change your password</h3>
            <input type="hidden" name="username" id="username" value="<?php echo $username?>">
            <label for="currPwd">Current password</label><br>
            <input type="password" name="current_password" id="current_password" required><br>
            <label for="newPwd">New Password</label><br>
            <input type="password" name="new_password" id="new_password" required><br>
            <label for="rePwd">Retype Password</label><br>
            <input type="password" name="retype_password" id="retype_password" required><br>
            <button type="submit" name="change_passwords" id="change_passwords" onclick="updatePassword()">Update</button>
</section>
    </div>
</div>