<?php
    session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";

    //get company details
    $get_comp = new selects();
    $rows = $get_comp->fetch_details("companies");
    foreach($rows as $row){
?>

<div id="manage_profile" class="displays">
    <div class="info"></div>
    <div class="add_user_form" style="width:50%; margin:10px 50px">
        <h3 style="background:var(--otherColor)">Manage company details</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <form action="../controller/update_company.php" method="POST" enctype="multipart/form-data" class="addUserForm">
            <div class="inputs">
                <div class="data">
                    <label for="logo">Company logo</label>
                    <div class="comp_logo">
                        <img src="<?php echo '../images/'.$row->logo?>" alt="Company logo">
                    </div>
                    <input type="file" name="logo" id="logo">
                </div>
                <div class="data">
                    <label for="company">Company name</label>
                    <input type="text" name="company" id="company" value="<?php echo $row->company?>">
                </div>
            </div>
            <div class="inputs">
                <div class="data">
                    <button type="submit" id="update_comp" name="update_comp">Update <i class="fas fa-save"></i></button>
                </div>
            </div>
        </section>    
    </div>
</div>
<?php }?>