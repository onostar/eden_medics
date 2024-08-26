<?php

    include "../classes/dbh.php";
    include "../classes/select.php";


?>
    <div class="info"></div>
<div class="displays allResults" id="room_list">
    <h2>List of suppliers</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('vendor_list_table', 'List of suppliers')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="vendor_list_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Company name</td>
                <td>Contact Person</td>
                <td>Phone number</td>
                <td>Email address</td>
                <td>Joined</td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_details = new selects();
                $details = $get_details->fetch_details('vendors');
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td><?php echo $detail->vendor?></td>
                <td><?php echo $detail->contact_person?></td>
                <td><?php echo $detail->phone?></td>
                <td><?php echo $detail->email_address?></td>
                <td><?php echo date("jS M, Y", strtotime($detail->created_date))?></td>
                
                
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