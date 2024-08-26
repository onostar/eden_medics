<div id="all_payments">
<?php
    session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        // echo $user_id;
    
    if(isset($_GET['guest_id'])){
        $guest = $_GET['guest_id'];
        $get_user = new selects();
        $details = $get_user->fetch_details_cond('check_ins', 'guest_id', $guest);
        foreach($details as $detail){
?>
<div id="post_payment" class="displays all_details">
    <!-- <div class="info"></div> -->
    <button class="page_navs" id="back" onclick="showPage('other_payment.php')"><i class="fas fa-angle-double-left"></i> Back</button>
    <h2>Payment Details</h2>
    <div class="guest_name">
        <h4>
            <?php 
                if($detail->gender == "Male"){
                    echo "Mr. ". $detail->last_name . " ". $detail->first_name . " | Guest00". $detail->guest_id;
                }else if($detail->gender == "Female" && $detail->age <= 24){
                    echo "Ms. ". $detail->last_name . " ". $detail->first_name . " | Guest00". $detail->guest_id;
                }else{
                    echo "Mrs. ". $detail->last_name . " ". $detail->first_name . " | Guest00". $detail->guest_id;
                }
            ?> 
        </h4>
        <div class="displays allResults" id="payment_det">
            <table id="guest_payment_table" class="searchTable">
                <thead>
                    <tr>
                        <td>S/N</td>
                        <td>Room Category</td>
                        <td>Check in date</td>
                        <td>Check out date</td>
                        <td>Days</td>
                        <td>Amount Due</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $n = 1;
                    ?>
                    <tr>
                        <td style="text-align:center; color:red;"><?php echo $n?></td>
                        <td>
                            <?php 
                                $get_cat = new selects();
                                $categories = $get_cat->fetch_details_group('rooms', 'category', 'room_id', $detail->room);
                                $category_id = $categories->category;
                                //get category name
                                $get_cat_name = new selects();
                                $cat_name = $get_cat_name->fetch_details_group('categories', 'category', 'category_id', $category_id);
                                echo $cat_name->category;


                            ?>
                        </td>
                        <td><?php echo date("jS M, Y", strtotime($detail->check_in_date));?></td>
                        <td><?php echo date("jS M, Y", strtotime($detail->check_out_date));?></td>
                        <td style="text-align:center">
                            <?php 
                                $in_date = strtotime($detail->check_in_date);
                                $out_date = strtotime($detail->check_out_date);
                                $date_diff = $out_date - $in_date;
                                $days = round($date_diff / (60 * 60 * 24));
                                echo $days;
                            ?>
                        </td>
                        <td style="text-align:center"><?php echo number_format($detail->amount_due, 2)?></td>
                    </tr>
                    
                    <?php $n++; ?>
                </tbody>
            </table>
            <div class="amount_due">
                <h2><?php echo "₦".number_format($detail->amount_due, 2)?></h2>
                <!-- payment mode options -->
                <div class="payment_mode">
                    <h3>mode of payment</h3>
                    <a href="javascript:void(0)" class="modes" onclick="showCash()">Cash <i class="fas fa-money-check"></i></a>
                    <a href="javascript:void(0)" class="modes"onclick="showPos()">POS <i class="fas fa-coins"></i></a>
                    <a href="javascript:void(0)" class="modes"onclick="showTransfer()">Transfer <i class="fas fa-wifi"></i></a>
                    
                    
                </div>
                
            </div>
            <!-- paymend mode forms -->
            <!-- cash payment form -->
            <div class="add_user_form payment_form" id="cash" style="width:100%">
                <h3 style="text-align:left; background:var(--moreColor)">Post Cash payment for <?php echo $detail->last_name. " ". $detail->first_name?></h3>
                <section class="addUserForm" >
                    <div class="inputs">
                        <input type="hidden" name="posted_by" id="posted_by" value="<?php echo $user_id?>">
                        <input type="hidden" name="guest" id="guest" value="<?php echo $detail->guest_id?>">
                        <input type="hidden" name="payment_mode" id="payment_mode" value="cash">
                        <input type="hidden" name="bank_paid" id="bank_paid" value="0">
                        <input type="hidden" name="sender" id="sender" value="self">
                        <div class="data">
                            <label for="guest_amount">Amount due</label>
                            <input type="text" name="guest_amount" id="guest_amount" value="<?php echo $detail->amount_due?>" readonly>
                        </div>
                        <div class="data">
                            <label for="amount_paid">Amount Paid</label>
                            <input type="number" name="amount_paid" id="amount_paid" placeholder="₦50,000" required>
                        </div>
                    </div>
                    <div class="inputs" style="justify-content:unset">
                        <button type="submit" id="payment" name="payment" onclick="postCash()">Post <i class="fas fa-paper-plane"></i></button>

                    </div>
                </section>    
            </div>
            <!-- POS payment form -->
            <div class="info"></div>
            <div class="add_user_form payment_form" id="pos" style="width:100%">
                <h3 style="text-align:left; background:var(--moreColor)">Post POS payment for <?php echo $detail->last_name. " ". $detail->first_name?></h3>
                <section class="addUserForm">
                    <div class="inputs">
                        <input type="hidden" name="guest" id="guest" value="<?php echo $guest?>">
                        <input type="hidden" name="posted_by" id="posted_by" value="<?php echo $user_id?>">
                        <input type="hidden" name="pos_mode" id="pos_mode" value="pos">
                        <input type="hidden" name="sender" id="sender" value="Self">
                        <div class="data">
                            <label for="bank">Bank</label>
                            <select name="pos_bank_paid" id="pos_bank_paid" required>
                                <option value=""selected>Select Bank</option>
                                <?php
                                    $get_bank = new selects();
                                    $rows = $get_bank->fetch_details('banks');
                                    foreach($rows as $row):
                                ?>
                                <option value="<?php echo $row->bank_id?>"><?php echo $row->bank." (".$row->account_number.")"?></option>
                                <?php endforeach?>
                            </select>
                        </div>
                        <div class="data" >
                            <label for="guest_amount">Amount due</label>
                            <input type="text" name="guest_amount" id="guest_amount" value="<?php echo $detail->amount_due?>" readonly>
                        </div>
                        <div class="data">
                            <label for="amount_paid">Amount Paid</label>
                            <input type="number" name="pos_amount_paid" id="pos_amount_paid" placeholder="₦50,000" required>
                        </div>
                    </div>
                    <div class="inputs" style="justify-content:unset">
                        <button type="submit" id="payment" name="payment" onclick="postPos()">Post <i class="fas fa-paper-plane"></i></button>

                    </div>
                </section>    
            </div>
            <!-- transfer payment form -->
            <div class="add_user_form payment_form" id="transfer" style="width:100%">
                <h3 style="text-align:left; background:var(--moreColor)">Post Transfer payment for <?php echo $detail->last_name. " ". $detail->first_name?></h3>
                <section class="addUserForm">
                    <div class="inputs">
                        <input type="hidden" name="posted_by" id="posted_by" value="<?php echo $user_id?>">
                        <input type="hidden" name="guest" id="guest" value="<?php echo $guest?>">
                        <input type="hidden" name="transfer_mode" id="transfer_mode" value="transfer">
                        <div class="data">
                            <label for="bank">Bank</label>
                            <select name="transfer_bank_paid" id="transfer_bank_paid" required>
                                <option value=""selected>Select Bank</option>
                                <?php
                                    $get_bank = new selects();
                                    $rows = $get_bank->fetch_details('banks');
                                    foreach($rows as $row):
                                ?>
                                <option value="<?php echo $row->bank_id?>"><?php echo $row->bank." (".$row->account_number.")"?></option>
                                <?php endforeach?>
                            </select>
                        </div>
                        <div class="data">
                            <label for="sender">Sender Name</label>
                            <input type="text" name="transfer_sender" id="transfer_sender" placeholder="Jacob Murphy" required>
                        </div>
                        <div class="data">
                            <label for="guest_amount">Amount due</label>
                            <input type="text" name="guest_amount" id="guest_amount" value="<?php echo $detail->amount_due?>" readonly>
                        </div>
                        <div class="data">
                            <label for="amount_paid">Amount Paid</label>
                            <input type="number" name="transfer_amount" id="transfer_amount" placeholder="₦50,000" required>
                        </div>
                        <div class="data">
                            <button type="submit" id="payment" name="payment" onclick="postTransfer()">Post <i class="fas fa-paper-plane"></i></button>
                        </div>
                    </div>
                </section>    
            </div>
            <?php
                if(gettype($details) == "string"){
                    echo "<p class='no_result'>'$details'</p>";
                }
            ?>
        </div>
    </div>
    
</div>
<?php
            }
        }
    }else{
        header("Location: ../index.php");
    }
?>
</div>