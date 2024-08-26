<?php
date_default_timezone_set("Africa/Lagos");
    class inserts extends Dbh{

        //new class to insert any number of data
        protected function add_data($table, $data){
            $add_data = $this->connectdb()->prepare("INSERT INTO $table (" . implode(', ', array_keys($data)) .") VALUES (:" . implode(', :', array_keys($data)). ")");
            foreach($data as $column => $value){
                $add_data->bindValue($column, $value);
            }
            $add_data->execute();
            
        }
        
        
        
        //Insert into audit trail
        protected function audit($item, $trans, $prev_qty, $qty, $posted, $store){
            
            $audit = $this->connectdb()->prepare("INSERT INTO audit_trail (item, transaction, previous_qty, quantity, posted_by, store) VALUES (:item, :transaction, :previous_qty, :quantity, :posted_by, :store)");
            $audit->bindValue("item", $item);
            $audit->bindValue("transaction", $trans);
            $audit->bindValue("previous_qty", $prev_qty);
            $audit->bindValue("quantity", $qty);
            $audit->bindValue("posted_by", $posted);
            $audit->bindValue("store", $store);
            $audit->execute();
            
        
            
        }
        //Insert into customer trail
        protected function customer_trail($customer, $details, $amount, $posted, $store){
            $audit = $this->connectdb()->prepare("INSERT INTO customer_trail (customer, description, amount, posted_by, store) VALUES (:customer, :description, :amount, :posted_by, :store)");
            $audit->bindValue("customer", $customer);
            $audit->bindValue("description", $details);
            $audit->bindValue("amount", $amount);
            $audit->bindValue("posted_by", $posted);
            $audit->bindValue("store", $store);
            $audit->execute();
        }
        //Insert into debtors list
        protected function add_debtor($customer, $invoice, $amount, $posted, $store){
            $audit = $this->connectdb()->prepare("INSERT INTO debtors (customer, invoice, amount, posted_by, store) VALUES (:customer, :invoice, :amount, :posted_by, :store)");
            $audit->bindValue("customer", $customer);
            $audit->bindValue("invoice", $invoice);
            $audit->bindValue("amount", $amount);
            $audit->bindValue("posted_by", $posted);
            $audit->bindValue("store", $store);
            $audit->execute();   
        }
        
        //insert multiple payment
        protected function multiple_pay($posted, $invoice, $cash, $pos, $transfer, $bank, $store, $date){
            $check_in = $this->connectdb()->prepare("INSERT INTO multiple_payments (posted_by, invoice, cash, pos, transfer, bank, store, post_date) VALUES (:posted_by, :invoice, :cash, :pos, :transfer, :bank, :store, :post_date)");
            $check_in->bindvalue("invoice", $invoice);
            $check_in->bindvalue("cash", $cash);
            $check_in->bindvalue("pos", $pos);
            $check_in->bindvalue("transfer", $transfer);
            $check_in->bindvalue("bank", $bank);
            $check_in->bindvalue("store", $store);
            $check_in->bindvalue("posted_by",$posted);
            $check_in->bindvalue("post_date",$date);
            $check_in->execute();
            
        }
        //post payment
        protected function post_payment($posted, $mode, $bank, $amount_due, $amount_paid, $discount, $invoice, $store, $type, $customer, $date){
            
            $payment = $this->connectdb()->prepare("INSERT INTO payments (amount_due, amount_paid, discount, bank, payment_mode, posted_by, invoice, store, sales_type, customer, post_date) VALUES (:amount_due, :amount_paid, :discount, :bank, :payment_mode, :posted_by, :invoice, :store, :sales_type, :customer, :post_date)");
            $payment->bindValue("amount_due", $amount_due);
            $payment->bindValue("amount_paid", $amount_paid);
            $payment->bindValue("discount", $discount);
            $payment->bindValue("bank", $bank);
            $payment->bindValue("payment_mode", $mode);
            $payment->bindValue("posted_by", $posted);
            $payment->bindValue("invoice", $invoice);
            $payment->bindValue("store", $store);
            $payment->bindValue("sales_type", $type);
            $payment->bindValue("customer", $customer);
            $payment->bindValue("post_date", $date);
            $payment->execute();
            
        }
        //post other payments
        protected function post_otherpayment($posted, $mode, $amount, $invoice, $customer){
            
            $payment = $this->connectdb()->prepare("INSERT INTO other_payments (amount, payment_mode, posted_by, invoice, customer) VALUES (:amount, :payment_mode, :posted_by, :invoice, :customer)");
            $payment->bindValue("amount", $amount);
            $payment->bindValue("payment_mode", $mode);
            $payment->bindValue("posted_by", $posted);
            $payment->bindValue("invoice", $invoice);
            $payment->bindValue("customer", $customer);
            $payment->execute();
            
        }

        
        
        //add item to sales
        protected function post_sales($posted, $item, $invoice, $quantity, $price, $amount, $cost, $store, $type, $customer, $date, $markup){
            // check if item already exist
            $confirm_check  = $this->connectdb()->prepare("SELECT * FROM sales WHERE invoice = :invoice AND item = :item");
            $confirm_check->bindValue("invoice", $invoice);
            $confirm_check->bindValue("item", $item);
            $confirm_check->execute();
            if(!$confirm_check->rowCount() > 0){
                $add_sales = $this->connectdb()->prepare("INSERT INTO sales (item, invoice, price, total_amount, quantity, posted_by, cost, store, sales_type, customer, post_date, markup) VALUES (:item, :invoice, :price, :total_amount, :quantity, :posted_by, :cost, :store, :sales_type, :customer, :post_date, :markup)");
                $add_sales->bindvalue("item", $item);
                $add_sales->bindvalue("invoice", $invoice);
                $add_sales->bindvalue("price", $price);
                $add_sales->bindvalue("total_amount", $amount);
                $add_sales->bindvalue("quantity", $quantity);
                $add_sales->bindvalue("posted_by", $posted);
                $add_sales->bindvalue("cost", $cost);
                $add_sales->bindvalue("store", $store);
                $add_sales->bindvalue("sales_type", $type);
                $add_sales->bindvalue("customer", $customer);
                $add_sales->bindvalue("post_date", $date);
                $add_sales->bindvalue("markup", $markup);
                $add_sales->execute();
            }else{
                echo "<div class='notify'><p>Item already exists in sales order</p></div>";
            }
        }
        
    }

    
    //controller for adding any item in any data base
    class add_data extends inserts{
        private $table;
        private $data;

        public function __construct($table, $data)
        {
            $this->table = $table;
            $this->data = $data;
        }
        public function create_data(){
            $this->add_data($this->table, $this->data);
        }
    }
    
    
    //add audit trail controller
    class audit_trail extends inserts{
        private $item;
        private $trans;
        private $prev_qty;
        private $qty;
        private $posted;
        private $store;

        public function __construct($item, $trans, $prev_qty, $qty, $posted, $store)
        {
            $this->item = $item;
            $this->trans = $trans;
            $this->prev_qty = $prev_qty;
            $this->qty = $qty;
            $this->posted = $posted;
            $this->store = $store;
        }
        public function audit_trail(){
            $this->audit($this->item, $this->trans, $this->prev_qty, $this->qty, $this->posted, $this->store);
        }
    }
    

    //controller for multiple payment
    class multiple_payment extends inserts{
        private $posted;
        private $invoice;
        private $cash;
        private $pos;
        private $transfer;
        private $bank;
        private $store;
        private $date;
        public function __construct($posted, $invoice, $cash, $pos, $transfer, $bank, $store, $date)
        {
            $this->posted = $posted;
            $this->invoice = $invoice;
            $this->cash = $cash;
            $this->pos = $pos;
            $this->transfer = $transfer;
            $this->bank = $bank;
            $this->store = $store;
            $this->date = $date;
        }

        public function multi_pay(){
            $this->multiple_pay($this->posted, $this->invoice, $this->cash, $this->pos, $this->transfer, $this->bank, $this->store, $this->date);
        }
    }

    //controller for payments
    class payments extends inserts{
        private $posted;
        private $mode;
        private $bank;
        private $amount_due;
        private $amount_paid;
        private $discount;
        private $invoice;
        private $store;
        private $type;
        private $customer;
        private $date;

        public function __construct($posted, $mode, $bank, $amount_due, $amount_paid, $discount, $invoice, $store, $type, $customer, $date)
        {
            $this->posted = $posted;
            $this->mode = $mode;
            $this->bank = $bank;
            $this->amount_due = $amount_due;
            $this->amount_paid = $amount_paid;
            $this->discount = $discount;
            $this->invoice = $invoice;
            $this->store = $store;
            $this->type = $type;
            $this->customer = $customer;
            $this->date = $date;
        }

        public function payment(){
            $this->post_payment($this->posted, $this->mode, $this->bank, $this->amount_due, $this->amount_paid, $this->discount, $this->invoice, $this->store, $this->type, $this->customer, $this->date);
        }
    }
    //controller for payments
    class other_payments extends inserts{
        private $posted;
        private $mode;
        private $amount;
        private $invoice;
        private $customer;

        public function __construct($posted, $mode, $amount, $invoice, $customer)
        {
            $this->posted = $posted;
            $this->mode = $mode;
            $this->amount = $amount;
            $this->invoice = $invoice;
            $this->customer = $customer;
        }

        public function other_payment(){
            $this->post_otherpayment($this->posted, $this->mode,$this->amount, $this->invoice, $this->customer);
        }
    }

    // controller for posting customer trail
    class customer_trail extends inserts{
        private $customer;
        private $store;
        private $detail;
        private $amount;
        private $posted;

        public function __construct($customer, $store, $detail, $amount, $posted)
        {
            $this->customer = $customer;
            $this->store = $store;
            $this->detail = $detail;
            $this->amount = $amount;
            $this->posted = $posted;
        }
        public function add_trail(){
            $this->customer_trail($this->customer, $this->detail, $this->amount, $this->posted, $this->store);
        }
    }
    // controller for adding debtor
    class add_debtor extends inserts{
        private $customer;
        private $store;
        private $invoice;
        private $amount;
        private $posted;

        public function __construct($customer, $store, $invoice, $amount, $posted)
        {
            $this->customer = $customer;
            $this->store = $store;
            $this->invoice = $invoice;
            $this->amount = $amount;
            $this->posted = $posted;
        }
        public function add_debt(){
            $this->add_debtor($this->customer, $this->invoice, $this->amount, $this->posted, $this->store);
        }
    }

    
    //controller for adding sales
    class post_sales extends inserts{
        private $item;
        // private $staff;
        private $invoice;
        private $quantity;
        private $price;
        private $amount;
        private $posted;
        private $cost;
        private $store;
        private $type;
        private $customer;
        private $date;
        private $markup;

        public function __construct($item, $invoice, $quantity, $price, $amount, $posted, $cost, $store, $type, $customer, $date, $markup)
        {
            $this->item = $item;
            // $this->staff = $staff;
            $this->invoice = $invoice;
            $this->quantity = $quantity;
            $this->price = $price;
            $this->amount = $amount;
            $this->posted = $posted;
            $this->cost = $cost;
            $this->store = $store;
            $this->type = $type;
            $this->customer = $customer;
            $this->date = $date;
            $this->markup = $markup;
        }

        public function add_sales(){
            $this->post_sales($this->posted, $this->item, $this->invoice, $this->quantity, $this->price, $this->amount, $this->cost, $this->store, $this->type, $this->customer, $this->date, $this->markup);
        }
    }

    
