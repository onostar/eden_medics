<?php
date_default_timezone_set("Africa/Lagos");
    session_start();
    include "classes/dbh.php";
    include "classes/select.php";
    if(isset($_SESSION['user'])){
        header("Location: view/users.php");
    }else{
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
    <title>Sales & Inventory Management | Login</title>
    <link rel="icon" type="image/png" size="32x32" href="images/icon.png">
    <link rel="stylesheet" href="fontawesome-free-6.0.0-web/css/all.css">
    <link rel="stylesheet" href="fontawesome-free-6.0.0-web/css/all.min.css">
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <!-- show package soon to expire -->
    <?php
            //get date to shut down
            $reg_date = $row->date_created;
            $expiration = date("Y-m-d", strtotime("+1 year", strtotime($reg_date)));
            $current_date = date("Y-m-d");
            $interval = abs(strtotime($expiration) - strtotime($current_date));
            $days = $interval/86400;
           
            if($days < 30){
        ?>
    <div class="about_expire">
        
        <marquee behavior="smooth" direction="left">
            <?php echo "This software will expire in $days day(s), kindly contact your service provider to renew your package"?>
        </marquee>
    </div>
    <?php }
        if(strtotime($current_date) < strtotime($expiration)){
    ?>
    <main id="reg_body">
        
        <!-- <div class="header">
            <h1>
                <a href="index.php">
                    <img src="images/logo.png" alt="logo">
                </a>
            </h1>
            <h3><?php echo $row->company?></h3>
           
        </div> -->
        <section class="reg_log">
            <!-- <div class="adds">
                <img src="images/software.jpg" alt="login banner">
            </div> -->
            <div class="login_page">
                
                <!-- <h3 class="mobile_company"><?php echo $row->company?></h3> -->
                <div class="company_logo">
                    <img src="<?php echo 'images/'.$row->logo?>" alt="<?php echo $row->company?>">
                </div>
                <!-- <h2>Welcome User!</h2> -->
                <p style="#222"><?php echo $row->company?></p>
                <?php
                    if(isset($_SESSION['success'])){
                        echo "<p class='success succeed' style='color:green'>" . $_SESSION['success']. "</p>
                        <script>
                            setTimeout(function(){
                                $('.succeed').hide();
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
                <form action="controller/login.php" method="POST">
                    <div class="data">
                        <label for="username">Enter username</label>
                        <input type="text" name="username" id="username" placeholder="username" required value="<?php if(isset($_SESSION['email'])){
                            echo $_SESSION['email'];
                            unset($_SESSION['email']);
                        }?>">
                        
                    </div>
                    <div class="data">
                        <div class="pass">
                            <label for="password">Password</label>
                            <!-- <a href="views/forgot_password.php" title="Recover your password">Forgot password?</a> -->
                        </div>
                        <input type="password" name="password" id="password" class="password" placeholder="*******" required><br>
                        <div class="show_password">
                            <a href="javascript:void(0)" onclick="togglePassword()"><span class='icon'><i class="fas fa-eye"></i></span> <span class='icon_txt'>Show password</span></a>
                        </div>
                        
                    </div>
                    <div class="data">
                        <button type="submit" id="submit_login" name="submit_login">Sign in <i class="fas fa-sign-in-alt"></i></button>

                    </div>
                    
                </form>
                <div class="software_logo">
                    <img src="images/logo.png" alt="logo">
                </div>
                <div id="foot">
                    <p >&copy;<?php echo Date("Y");?> Dorthpro Digitals. All Rights Reserved.</p>

                </div>

            </div>
            
        </section>
    </main>
    <script src="jquery.js"></script>
    <script src="script.js"></script>
</body>
</html>
<?php 
            }else{
?>
            <div class="expired_package">
                <p>Your software package has expired.<br>Kindly contact your service provider for more details</p>
            </div>
<?php
    }
}}?>