<?php

    include "../classes/dbh.php";
    include "../classes/select.php";


?>
    <div class="info"></div>
<div class="displays allResults" id="room_list" style="width:50%!important; margin:10px 50px!important;">
    <h2>List of Banks and Account Number</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('bank_list_table', 'Bank list')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="bank_list_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Bank Name</td>
                <td>Account Number</td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_details = new selects();
                $details = $get_details->fetch_details('banks');
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td><?php echo $detail->bank?></td>
                <td><?php echo $detail->account_number?></td>
                
                
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