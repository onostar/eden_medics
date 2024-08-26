<?php
    session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_company = new selects();
    $rows = $get_company->fetch_details('companies');
    foreach($rows as $row){
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="Inventory system, point of sales, inventory and sales management, retail, supermarket software, sales application">
    <meta name="description" content="An online/offline inventory and sales management software for retail and wholesale stores and pharmacies, stock register, etc">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales & Inventory Management | Change password</title>
    <link rel="icon" type="image/png" size="32x32" href="../images/logo.png">
    <link rel="stylesheet" href="../fontawesome-free-6.0.0-web/css/all.css">
    <link rel="stylesheet" href="../fontawesome-free-6.0.0-web/css/all.min.css">
    <link rel="stylesheet" href="../style.css">

</head>
<body>
    <main id="reg_body">
       <!--  <div class="header">
            <h1>
                <a href="index.php">
                    <img src="../images/logo.png" alt="logo">
                </a>
            </h1>
            <h3><?php echo $row->company?></h3>
            -->
        </div>
        <section class="reg_log">
            
            <div class="login_page">
                
                <!-- <h1>
                    <a href="../index.php">
                        <img src="../images/logo.png" alt="logo">
                    </a>
                </h1> -->
                <h3 class="mobile_company"><?php echo $row->company?></h3>
                <h2>Change your password</h2>
                <p></p>
                <?php
                    if(isset($_SESSION['success'])){
                        echo "<p class='success succeed'>" . $_SESSION['success']. "</p>
                        <script>
                            setTimeout(function(){
                                $('.succeed').hide();
                                window.open('../index.php', '_parent');
                            }, 5000);
                        </script>
                        ";
                        unset($_SESSION['success']);
                    }
                ?>
                
                <?php
                    if(isset($_SESSION['error'])){
                        echo "<p class='error succeed'>" . $_SESSION['error']. "</p>
                        <script>
                            setTimeout(function(){
                                $('.succeed').hide();
                            }, 5000)
                        </script>";
                        unset($_SESSION['error']);
                    }
                ?>

                <?php
                    if(isset($_SESSION['user'])){
                        $username = $_SESSION['user'];
                ?>
                <form action="../controller/reset_password.php" method="POST">
                    <div class="data">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" required value="<?php echo $username?>" readonly>
                        <!-- <input type="hidden" name="current_password" value="123"> -->
                        
                    </div>
                    <div class="data">
                        <label for="new_password">Enter new Password</label>
                        <input type="password" name="new_password" id="password" class="password" placeholder="*******" required>
                        <div class="show_password">
                            <a href="javascript:void(0)" onclick="togglePassword()"><span class="icon"><i class="fas fa-eye"></i></span> <span class="icon_txt">Show password</span></a>
                        </div>
                    </div>
                    <div class="data">
                        <label for="password">Confirm Password</label>
                        <input type="password" name="retype_password" id="retype_password" class="password" placeholder="*******" required>
                        <div class="show_password">
                            <a href="javascript:void(0)" onclick="togglePassword()"><span class="icon"><i class="fas fa-eye"></i></span> <span class="icon_txt">Show password</span></a>
                        </div>
                    </div>
                    <div class="data">
                        <button type="submit" id="change_password" name="change_password">Change Password <i class="fas fa-sign-in-alt"></i></button>

                    </div>
                    
                </form>
                <?php 
                
                    }else{
                        header("Location../index.php");
                    }
                
                ?>
                <div id="foot">
                    <p >&copy;<?php echo Date("Y");?> Dorthpro digitals. All Rights Reserved.</p>

                </div>

            </div>
            <!-- <div class="adds">
                <img src="images/bus.jpg" alt="morgue login banner">
            </div> -->
        </section>
    </main>
    <script src="../jquery.js"></script>
    <script src="../script.js"></script>
</body>
</html>
<?php }?>