<?php

?> 

<?php
session_start();
    //instantiate classes
    include "../classes/dbh.php";
    include "../classes/inserts.php";
    // if(isset($_POST['upload_paid'])){
    
        // Allowed mime types
        $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
        
        // Validate whether selected file is a CSV file
        if(!empty($_FILES['items']['name']) && in_array($_FILES['items']['type'], $csvMimes)){
            
            // If the file is uploaded
            if(is_uploaded_file($_FILES['items']['tmp_name'])){
                
                // Open uploaded CSV file with read-only mode
                $csvFile = fopen($_FILES['items']['tmp_name'], 'r');
                
                // Skip the first line
                fgetcsv($csvFile);
                
                // Parse data from CSV file line by line
                while(($line = fgetcsv($csvFile)) !== FALSE){
                    // Get row data
                    $department   = $line[0];
                    $category = $line[1];
                    $item_name  = $line[2];
                    $cost_price  = $line[3];
                    $sales_price  = $line[4];
                    $pack_size  = $line[5];
                    $pack_price  = $line[6];
                    $wholesale  = $line[7];
                    $wholesale_pack  = $line[8];
                    $reorder_level  = $line[9];
                    $markup  = $line[10];
                    $barcode  = $line[11];
                    $item_status = $line[12];
                    
                    $data = array(
                        'department' => $department,
                        'category' => $category,
                        'item_name' => $item_name,
                        'cost_price' => $cost_price,
                        'sales_price' => $sales_price,
                        'pack_size' => $pack_size,
                        'pack_price' => $pack_price,
                        'wholesale' => $wholesale,
                        'wholesale_pack' => $wholesale_pack,
                        'reorder_level' => $reorder_level,
                        'markup' => $markup,
                        'barcode' => $barcode,
                        'item_status' => $item_status
                    );
                    $upload = new add_data('items', $data);
                    $upload->create_data();
                    // Check whether member already exists in the database with the same pcn number
                    
                       /*  // Insert member data in the database
                        $connectdb->query("INSERT INTO paid_members (pcn_number, first_name, last_name, whatsapp, res_state, user_email, gender, fellow) VALUES ('".$pcn_number."', '".$first_name."', '".$last_name."', '".$whatsapp."', '".$res_state."', '".$user_email."', '".$gender."', '".$fellow."')"); */
                       
                    
                }
                
                // Close opened CSV file
                fclose($csvFile);
                
                $_SESSION['success'] = "Items uploaded successfully!";
                header("Location: ../view/users.php");
            }else{
                $_SESSION['error'] = "Failed";
                header("Location: ../view/users.php");
            }
        }else{
            $_SESSION['error'] = "Invalid file";
                header("Location: ../view/users.php");
        }
    // }
    