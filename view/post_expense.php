<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
    }
?>
<div id="post_expense" class="displays">
    <div class="info" style="width:50%; margin:5px 0;"></div>
    <div class="add_user_form" style="width:70%; margin:5px 0;">
        <h3 style="background:var(--otherColor); text-align:left">Post Daily Expense</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs">
                <input type="hidden" name="posted" id="posted" value="<?php echo $user_id?>">
                <input type="hidden" name="store" id="store" value="<?php echo $store?>">
                <div class="data" style="width:32%; margin:5px 0;">
                    <label for="exp_date">Transaction Date</label>
                    <input type="date" name="exp_date" id="exp_date" required>
                </div>
                <div class="data" style="width:32%; margin:5px 0;">
                    <label for="exp_head">Expense Head</label>
                    <select name="exp_head" id="exp_head">
                        <option value="" selected>Select expense head</option>
                        <?php
                            $get_heads = new selects();
                            $heads = $get_heads->fetch_details('expense_heads');
                            foreach($heads as $head){
                        ?>
                        <option value="<?php echo $head->exp_head_id?>"><?php echo $head->expense_head?></option>
                        <?php }?>
                    </select>
                </div>
                <div class="data" style="width:32%; margin:5px 0">
                    <label for="amount"> Amount</label>
                    <input type="text" name="amount" id="amount" required placeholder="5000">
                </div>
                <div class="data" style="width:60%; margin:5px 0">
                    <label for="details"> Description</label>
                    <textarea name="details" id="details" cols="30" rows="5" placeholder="Enter a detailed description of the transaction"></textarea>
                </div>
                <div class="data" style="width:30%; margin:5px 0">
                    <button type="submit" id="post_exp" name="post_exp" onclick="postExpense()">Post Expense <i class="fas fa-save"></i></button>
                </div>
            </div>
        </section>    
    </div>
</div>
