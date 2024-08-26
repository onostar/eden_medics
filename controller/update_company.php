<?php
    session_start();
    $comp_id = $_SESSION['company_id'];
    include "../classes/dbh.php";
    include "../classes/update.php";

    if(isset($_POST['update_comp'])){
        $company = ucwords(htmlspecialchars(stripslashes($_POST['company'])));
        //logo details
        $logo = $_FILES['logo']['name'];
        $logo_folder = "../images/".$logo;
        $logo_size = $_FILES['logo']['size'];
        $allowed_ext = array("jpeg", "jpg", "png", "webp");
        $logo_ext = explode('.', $logo);
        $logo_ext = strtolower(end($logo_ext));

        if(in_array($logo_ext, $allowed_ext)){
            if($logo_size <= 500000){
                if(move_uploaded_file($_FILES['logo']['tmp_name'], $logo_folder)){
                    $update_comp = new Update_table();
                    $update_comp->update_double('companies', 'company', $company, 'logo', $logo, 'company_id', $comp_id);
                    if($update_comp){
                        $_SESSION['success'] = "<p>Company details updated successfully</p>";
                        header("Location: ../view/users.php");
                    }
                }else{
                    $_SESSION['error'] = "Could not save file";
                    header("Location: ../view/users.php");
                }
            }else{
                $_SESSION['error'] = "Error! File size too large";
                header("Location: ../view/users.php");
            }
        }else{
            $_SESSION['error'] = "Error! File extension not supported";
            header("Location: ../view/users.php");
        }
    }

?>