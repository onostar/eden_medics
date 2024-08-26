<aside class="main_menu" id="mobile_log">
    
    <nav>
        <h3><a href="users.php" title="Home"><i class="fas fa-home"></i> Main menus</a></h3>
        <?php
            //show mall menu fpr admin
            if($username == 'Sysadmin'){
        ?>
        <ul>
            <?php 
                //get menus
                $get_Admin_menus = new selects();
                $admin_menus = $get_Admin_menus->fetch_single_grouped('sub_menus', 'menu');
                if(gettype($admin_menus) == "array"){

                    //get menu name
                    foreach($admin_menus as $admin_men){
                    $get_admin_name = new selects();
                    $admin_menu_name = $get_admin_name->fetch_details_group('menus', 'menu', 'menu_id', $admin_men->menu);
            ?>
            <li>
                <a href="javascript:void(0);" onclick="toggleMenu('<?php echo $admin_menu_name->menu?>')" class="allMenus" title="<?php echo $admin_menu_name->menu?>"><span><i class="fas fa-gem"></i> <?php echo $admin_menu_name->menu?> </span><span class="second_icon"><i class="fas fa-chevron-down more_option"></i></span></a>
                    <!-- get all the sub menus inside each menu -->
                <ul class="subMenu" id="<?php echo $admin_menu_name->menu?>">
                    <?php
                        $get_admin_sub = new selects();
                        $admin_subs = $get_admin_sub->fetch_details_cond('sub_menus', 'menu', $admin_men->menu);
                        if(gettype($admin_subs) == "array"){
                            foreach($admin_subs as $admin_sub){
                                
                                $admin_sub_menuss = $admin_sub->sub_menu;
                                $admin_urls = $admin_sub->url;
                                
                    ?>
                    <li>
                        <a href="javascript:void(0);" title="<?php echo $admin_sub_menuss?>" class="page_navs" onclick="showPage('<?php echo $admin_urls?>.php')"><i class="fas fa-arrow-alt-circle-right"></i> <?php echo $admin_sub_menuss?></a>
                    </li>
                    <?php
                                
                            }
                        }
                    ?>
                </ul>
            </li>
                <?php } }?>
        </ul>
        <?php
            }else{
        ?>
        <ul>
            <?php 
                //get menus
                $get_menus = new selects();
                $menus = $get_menus->fetch_details_condGroup('rights', 'user', $user_id, 'menu');
                if(gettype($menus) == "array"){

                    //get menu name
                    foreach($menus as $men){
                    $get_menu_name = new selects();
                    $menu_name = $get_menu_name->fetch_details_group('menus', 'menu', 'menu_id', $men->menu);
            ?>
            <li>
                <a href="javascript:void(0);" onclick="toggleMenu('<?php echo $menu_name->menu?>')" class="allMenus" title="<?php echo $menu_name->menu?>"><span><i class="fas fa-gem"></i> <?php echo $menu_name->menu?> </span><span class="second_icon"><i class="fas fa-chevron-down more_option"></i></span></a>
                    <!-- get all the sub menus inside each menu -->
                <ul class="subMenu" id="<?php echo $menu_name->menu?>">
                    <?php
                        $get_sub = new selects();
                        $subs = $get_sub->fetch_details_2cond('rights', 'menu', 'user', $men->menu, $user_id);
                        if(gettype($subs) == "array"){
                            foreach($subs as $sub){
                                //get each submenu details
                                $sub_details = new selects();
                                $sub_dets = $sub_details->fetch_details_cond('sub_menus', 'sub_menu_id', $sub->sub_menu);
                                foreach($sub_dets as $sub_det){
                                    $sub_menuss = $sub_det->sub_menu;
                                    $urls = $sub_det->url;
                                
                    ?>
                    <li>
                        <a href="javascript:void(0);" title="<?php echo $sub_menuss?>" class="page_navs" onclick="showPage('<?php echo $urls?>.php')"><i class="fas fa-arrow-alt-circle-right"></i> <?php echo $sub_menuss?></a>
                    </li>
                    <?php
                                }
                            }
                        }
                    ?>
                </ul>
            </li>
                <?php } }?>
        </ul>
        <?php }?>
    </nav>
</aside>