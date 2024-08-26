<?php

    include "../classes/dbh.php";
    include "../classes/select.php";


?>
<div id="checkReport" class="displays management" style="margin:auto;">
<h2 style="text-transform:capitalize">Check item history</h2>
<hr>
    <div class="select_date">
        <!-- <form method="POST"> -->
        <section style="align-items:flex-end">    
            <div class="from_to_date">
                <label>Select From Date</label><br>
                <input type="date" name="from_date" id="from_date"><br>
            </div>
            <div class="from_to_date">
                <label>Select to Date</label><br>
                <input type="date" name="to_date" id="to_date"><br>
            </div>
            <div class="data" style="position:relative;">
                <label for="history_item">Select item</label>
                <input type="text" name="history_item" id="history_item" required placeholder="Input item name or barcode" onkeyup="getHistoryItems(this.value)">
                <div id="sales_item" style="position:absolute; width:100%">
                    
                </div>
            </div>
            <!-- <button type="submit" name="search_date" id="search_date" onclick="getItemHistory()">Search <i class="fas fa-search"></i></button> -->
</section>
    </div>
<div class="displays allResults new_data" style="width:100%!important">
    
</div>

<script src="../jquery.js"></script>
<script src="../script.js"></script>