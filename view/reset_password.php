<?php

    include "../classes/dbh.php";
    include "../classes/select.php";


?>
    <div class="info"></div>
<div class="displays allResults" id="reset_password">
    <h2>Reset user password</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchActivate" placeholder="Enter keyword" onkeyup="searchData(this.value)">
    </div>
    <table id="activate_table" class="searchTable">
        <thead>
            <tr style="background:var(--otherColor)">
                <td>S/N</td>
                <td>Full Name</td>
                <td>Username</td>
                <td>User role</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_negCond1('users', 'user_role', 'admin');
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td><?php echo $detail->full_name;?></td>
                <td><?php echo $detail->username;?></td>
                <td><?php echo $detail->user_role;?></td>
                <td><a style="padding:5px 10px; background:var(--primaryColor); text-align:center; color:#fff; border-radius:5px;" href="javascript:void(0)" title="Activate user account" onclick="resetPassword('<?php echo $detail->user_id?>')">Reset</a></td>
            </tr>
            <?php $n++; endforeach;}?>
        </tbody>
    </table>
    
    <?php
        if(gettype($details) == "string"){
            echo "<p class='no_result'>'$details'</p>";
        }
    ?>
</div>