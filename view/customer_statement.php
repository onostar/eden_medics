<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
?>

<div id="stockin" class="displays" style="width:90%!important">
    <div class="add_user_form" style="width:50%; margin:10px 0;">
        <h3 style="background:var(--moreColor); text-align:left!important;" >Customer statement</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs">
                
                <div class="data">
                    <label for="fromDate">From Date</label>
                    <input type="date" name="fromDate" id="fromDate" required>
                </div>
                <div class="data">
                    <label for="toDate">To Date</label>
                    <input type="date" name="toDate" id="toDate" required>
                </div>
                <div class="data" style="width:100%; margin:10px 0">
                    <input type="text" name="customer" id="customer" required placeholder="Input customer name" onkeyup="getCustomer(this.value)">
                        <div id="sales_item">
                            
                        </div>
                    
                </div>
            </div>
        </section>
    </div>
    <div class="allResults new_data" style="width:100%; margin:0"></div>
    
</div>
<?php
    }else{
        header("Location: ../index.php");
    }
?>