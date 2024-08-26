<?php

    $user = htmlspecialchars(stripslashes($_POST['user']));
    // echo $menu;
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    //get user full name
    $get_user = new selects();
    $name = $get_user->fetch_details_group('users', 'full_name', 'user_id', $user);
    $user_name = $name->full_name;

    //get user rights
    $get_rights = new selects();
    $rows = $get_rights->fetch_details_cond('rights', 'user', $user);
    $n = 1;
?>

<h3 style="background:var(--otherColor); color:#fff; padding:5px; text-align:center"><?php echo $user_name?>'s rights</h3>

    <table>
        <thead>
            <tr>
                <td>S/N</td>
                <td>Right</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
        <?php
            if(gettype($rows) == 'array'){
                foreach ($rows as $row) {
                    

        ?>
            <tr>
                <td><?php echo $n?></td>
                <td style="color:green;">
                    <i class="fas fa-key"></i>
                    <?php 
                        //get sub menu name;
                        $get_sub = new selects();
                        $sub = $get_sub->fetch_details_group('sub_menus', 'sub_menu', 'sub_menu_id', $row->sub_menu);
                        echo $sub->sub_menu;
                    ?>
                </td>
                <td>
                    <div class="each_right">
                        <a href="javascript:void(0)" title="Remove right" onclick="removeRight('<?php echo $row->right_id?>', '<?php echo $user?>')"><i class="fas fa-trash"></i></a>
                    </div>
                </td>
            </tr>
            <?php $n++; }?>
        </tbody>
        
    </table>
    
<?php
        }else{
        echo "<p style='color:var(--secondaryColor); text-align:center; font-size:1.1rem; margin:20px;'>No rights</p>";
    }
?>