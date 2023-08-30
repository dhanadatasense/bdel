<?php

    class DB {
        private $dbHost     = "localhost";
        private $dbUsername = "root";
        private $dbPassword = "!2Data@2050$";
        private $dbName     = "hoisst_live";

        public function __construct(){
            if(!isset($this->db)){
                // Connect to the database
                $conn = new mysqli($this->dbHost, $this->dbUsername, $this->dbPassword, $this->dbName);
                if($conn->connect_error){
                    die("Failed to connect with MySQL: " . $conn->connect_error);
                }else{
                    $this->db = $conn;
                }
            }
        }

        // Helper function
        public function empty_check($value)
        {
            return !empty($value)?$value:'';
        }

        public function employee_check($value)
        {
            return !empty($value)?$value:'Admin';
        }

        public function distributor_check($value)
        {
            return !empty($value)?$value:'Empty';
        }

        public function null_check($value)
        {
            return !empty($value)?$value:NULL;
        }

        public function zero_check($value)
        {
            return !empty($value)?$value:'0';
        }

        public function view_date($value)
        {
            if(!empty($value))
            {
                $result = date('d-m-Y', strtotime($value));
            }
            else
            {
                $result = null;
            }

            return $result;
        }

        public function view_time($value)
        {
            if(!empty($value))
            {
                $result = date('h:i A', strtotime($value));
            }
            else
            {
                $result = null;
            }

            return $result;
        }

        public function digit_val($value = '', $digit = '2')
        {
            return number_format((float)$value, $digit, '.', '');
        }

        public function employee_type($value)
        {
            if(!empty($value == 1))
            {
                $result = 'Delivery man';
            }
            else
            {
                $result = 'Sales executive';
            }

            return $result;
        }

        public function status_type($value)
        {
            if(!empty($value == 1))
            {
                $result = 'Active';
            }
            else
            {
                $result = 'Inactive';
            }

            return $result;
        }

        public function number_to_alphabet($number) {
            $number = intval($number);
            if ($number <= 0) {
                return '';
            }

            $alphabet = '';
            while($number != 0) {
                $p = ($number - 1) % 26;
                $number = intval(($number - $p) / 26);
                $alphabet = chr(65 + $p) . $alphabet;
            }

            return $alphabet;
        }
        
        // Token access
        public function is_table_empty() {
            $result = $this->db->query("SELECT id FROM google_oauth WHERE provider = 'google'");
            if($result->num_rows) {
                return false;
            }
      
            return true;
        }
      
        public function get_access_token() {
            $sql = $this->db->query("SELECT provider_value FROM google_oauth WHERE provider = 'google'");
            $result = $sql->fetch_assoc();
            return json_decode($result['provider_value']);
        }
      
        public function get_refersh_token() {
            $result = $this->get_access_token();
            return $result->refresh_token;
        }
      
        public function update_access_token($token) {
            if($this->is_table_empty()) {
                $this->db->query("INSERT INTO google_oauth(provider, provider_value) VALUES('google', '$token')");
            } else {
                $this->db->query("UPDATE google_oauth SET provider_value = '$token' WHERE provider = 'google'");
            }
        }

        // Report 1
        public function get_open_sales_orders() {

            $start_date = date('Y-m-d').' 00:00:00';
            $end_date   = date('Y-m-d').' 23:59:59';

            // $start_date = '2022-11-25 00:00:00';
            // $end_date   = '2022-12-15 23:59:59';

            $sql = $this->db->query("SELECT  `A`.`order_no`, `A`.`_ordered`, `B`.`company_name` AS `distributor_name`, `C`.`name` AS `beat_name`, `D`.`emp_name`, `D`.`store_name`, `E`.`description` AS `product_name`, `A`.`order_qty`, SUM(`A`.`order_qty`) - SUM(`A`.`receive_qty`) AS `balance_qty`, `E`.`product_stock` AS `admin_stock`, `F`.`stock` AS `supply_stock`, `A`.`price`, SUM(`A`.`order_qty` * `A`.`price`) AS `order_value`, SUM(`A`.`order_qty` * `A`.`price`) - SUM(`A`.`receive_qty` * `A`.`price`) AS `balance_value` FROM `tbl_order_details` `A` LEFT JOIN `tbl_distributors` `B` ON `A`.`distributor_id` = `B`.`id` INNER JOIN `tbl_zone` `C` ON `A`.`zone_id` = `C`.`id` INNER JOIN `tbl_order` `D` ON `A`.`order_id` = `D`.`id` INNER JOIN `tbl_product_type` `E` ON `A`.`type_id` = `E`.`id` INNER JOIN `tbl_assign_product_details` `F` ON `A`.`type_id` = `F`.`type_id` AND `B`.`id` = `F`.`distributor_id` WHERE `A`.`_packing` >= '".$start_date."' AND `A`.`_packing` <= '".$end_date."' AND `A`.`delete_status` = '1' AND `A`.`published`='1' GROUP BY `A`.`id`");

            $data = [];
            if($sql)
            {
                $num = 0;
                foreach ($sql as $key => $val) {

                    $data[] = array(
                        $this->empty_check($val['order_no']),
                        $this->view_date($val['_ordered']),
                        $this->null_check($val['distributor_name']),
                        $this->null_check($val['beat_name']),
                        $this->employee_check($val['emp_name']),
                        $this->null_check($val['store_name']),
                        $this->null_check($val['product_name']),
                        $this->zero_check($val['order_qty']),
                        $this->zero_check($val['balance_qty']),
                        $this->zero_check($val['admin_stock']),
                        $this->zero_check($val['supply_stock']),
                        $this->digit_val($this->zero_check($val['price'])),
                        $this->digit_val($this->zero_check($val['order_value'])),
                        $this->digit_val($this->zero_check($val['balance_value'])),
                    );
                    $num + 1;
                }
            }

            $result = array(
                'spreadsheetId' => '17MziHu8pjqJOic6wQLbWt-nGT6RceO2m2X328RBlX4E',
                'sheet_data'    => $data,
            );

            return $result;
        }

        // Report 2
        public function get_open_deliveries() {

            $start_date = date('Y-m-d').' 00:00:00';
            $end_date   = date('Y-m-d').' 23:59:59';

            // $start_date = '2022-11-25 00:00:00';
            // $end_date   = '2022-12-15 23:59:59';

            $sql = $this->db->query("SELECT  `A`.`invoice_no`, `B`.`createdate` AS `invoice_date`, `C`.`order_no`, `C`.`createdate` AS `order_date`, `D`.`company_name` AS `distributor_name`, `E`.`name` AS `beat_name`, `C`.`emp_name`, `C`.`store_name`, `F`.`description` AS `product_name`, `A`.`receive_qty`, `A`.`price`, SUM(`A`.`receive_qty` * `A`.`price`) AS `bill_value` FROM `tbl_invoice_details` `A` LEFT JOIN `tbl_invoice` `B` ON `A`.`invoice_id` = `B`.`id` INNER JOIN `tbl_order` `C` ON `B`.`order_id` = `C`.`id` INNER JOIN `tbl_distributors` `D` ON `B`.`distributor_id` = `D`.`id` INNER JOIN `tbl_zone` `E` ON `B`.`zone_id` = `E`.`id` INNER JOIN `tbl_product_type` `F` ON `A`.`type_id` = `F`.`id` WHERE `A`.`createdate` >= '".$start_date."' AND `A`.`createdate` <= '".$end_date."' AND `A`.`cancel_status` = '1' AND `B`.`delivery_status` = '1' AND `A`.`published`='1' GROUP BY `A`.`id`");

            $data = [];
            if($sql)
            {
                $num = 0;
                foreach ($sql as $key => $val) {
                    $data[] = array(
                        $this->empty_check($val['invoice_no']),
                        $this->view_date($val['invoice_date']),
                        $this->null_check($val['order_no']),
                        $this->view_date($val['order_date']),
                        $this->null_check($val['distributor_name']),
                        $this->null_check($val['beat_name']),
                        $this->employee_check($val['emp_name']),
                        $this->null_check($val['store_name']),
                        $this->null_check($val['product_name']),
                        $this->zero_check($val['receive_qty']),
                        $this->digit_val($this->zero_check($val['price'])),
                        $this->digit_val($this->zero_check($val['bill_value'])),
                    );
                }
            }

            $result = array(
                'spreadsheetId' => '1mRPeDUh4_N1q74YyFSTmiRjVePrHepXASZbz_pN-QOA',
                'sheet_data'    => $data,
            );

            return $result;
        }   

        // Report 3
        public function get_open_invoices() {

            $start_date = date('Y-m-d').' 00:00:00';
            $end_date   = date('Y-m-d').' 23:59:59';

            // $start_date = '2022-11-25 00:00:00';
            // $end_date   = '2022-12-15 23:59:59'; 

            $sql = $this->db->query("SELECT  `A`.`bill_no` AS `invoice_no`, `A`.`createdate` AS `invoice_date`, `B`.`company_name` AS `distributor_name`, `D`.`name` AS `beat_name`, `E`.`emp_name`, `C`.`store_name`, `A`.`amount`, `A`.`bal_amt` FROM `tbl_outlet_payment_details` `A` LEFT JOIN `tbl_distributors` `B` ON `A`.`distributor_id` = `B`.`id` INNER JOIN `tbl_invoice` `C` ON `A`.`outlet_id` = `C`.`store_id` AND `A`.`distributor_id` = `C`.`distributor_id` AND `A`.`bill_id` = `C`.`order_id` INNER JOIN `tbl_zone` `D` ON `C`.`zone_id` = `D`.`id` INNER JOIN `tbl_order` `E` ON `C`.`order_id` = `E`.`id` WHERE `A`.`createdate` >= '".$start_date."' AND `A`.`createdate` <= '".$end_date."' AND  `A`.`published`='1' AND `C`.`cancel_status` = '1' AND `C`.`delivery_status` = '1' GROUP BY `A`.`id`");

            $data = [];
            if($sql)
            {
                $num = 0;
                foreach ($sql as $key => $val) {
                    $data[] = array(
                        $this->empty_check($val['invoice_no']),
                        $this->view_date($val['invoice_date']),
                        $this->null_check($val['distributor_name']),
                        $this->null_check($val['beat_name']),
                        $this->employee_check($val['emp_name']),
                        $this->null_check($val['store_name']),
                        $this->digit_val($this->empty_check($val['amount'])),
                        $this->digit_val($this->empty_check($val['bal_amt'])),
                    );
                }
            }

            $result = array(
                'spreadsheetId' => '19D_dRg9gTpWdCY0w5A4ATq_xVWRHKbiLlj8HmN1io18',
                'sheet_data'    => $data,
            );

            return $result;
        }

        // Report 4
        public function get_open_internal_po() {

            $start_date = date('Y-m-d').' 00:00:00';
            $end_date   = date('Y-m-d').' 23:59:59';

            // $start_date = '2022-11-25 00:00:00';
            // $end_date   = '2022-12-15 23:59:59';

            $sql = $this->db->query("SELECT `B`.`po_no`, `B`.`createdate` AS `po_date`, `C`.`company_name` AS `distributor_name`, `D`.`description` AS `product_name`, `A`.`product_qty` AS `order_qty`, SUM(`A`.`product_qty`) - SUM(`A`.`receive_qty`) AS `balance_qty`, `E`.`stock` AS `supply_stock`, `D`.`product_stock` AS `admin_stock`, `A`.`product_price`, SUM(`A`.`product_qty` * `A`.`product_price`) AS `order_value`, SUM(`A`.`product_qty` * `A`.`product_price`) - SUM(`A`.`receive_qty` * `A`.`product_price`) AS `balance_value` FROM `tbl_dis_purchase_details` `A` LEFT JOIN `tbl_dis_purchase` `B` ON `A`.`po_id` = `B`.`id` INNER JOIN `tbl_distributors` `C` ON `B`.`distributor_id` = `C`.`id` INNER JOIN `tbl_product_type` `D` ON `A`.`type_id` = `D`.`id` INNER JOIN `tbl_assign_product_details` `E` ON `A`.`type_id` = `E`.`type_id` AND `B`.`distributor_id` = `E`.`distributor_id` WHERE `A`.`createdate` >= '".$start_date."' AND `A`.`createdate` <= '".$end_date."' AND `A`.`order_status`!='8' AND `A`.`published`='1' GROUP BY `A`.`id`");

            $data = [];
            if($sql)
            {
                $num = 0;
                foreach ($sql as $key => $val) {
                    $data[] = array(
                        $this->empty_check($val['po_no']),
                        $this->view_date($val['po_date']),
                        $this->null_check($val['distributor_name']),
                        $this->null_check($val['product_name']),
                        $this->zero_check($val['order_qty']),
                        $this->zero_check($val['balance_qty']),
                        $this->zero_check($val['supply_stock']),
                        $this->zero_check($val['admin_stock']),
                        $this->digit_val($this->empty_check($val['product_price'])),
                        $this->digit_val($this->empty_check($val['order_value'])),
                        $this->digit_val($this->empty_check($val['balance_value'])),
                    );
                }
            }

            $result = array(
                'spreadsheetId' => '1jShtsam2CP5zQVHuilzt051n0XKF9kkzY_foA90obrw',
                'sheet_data'    => $data,
            );

            return $result;
        }

        // Report 5
        public function get_open_external_po() {

            $start_date = date('Y-m-d').' 00:00:00';
            $end_date   = date('Y-m-d').' 23:59:59'; 

            // $start_date = '2022-11-25 00:00:00';
            // $end_date   = '2022-12-15 23:59:59';

            $sql = $this->db->query("SELECT  `B`.`po_no`, `B`.`createdate` AS `po_date`, `C`.`company_name` AS `manufacturer_name`, `D`.`description` AS `product_name`, `A`.`product_qty` AS `order_qty`, SUM(`A`.`product_qty`) - SUM(`A`.`receive_qty`) AS `balance_qty`, `D`.`product_stock` AS `admin_stock`, `A`.`product_price`, SUM(`A`.`product_qty` * `A`.`product_price`) AS `order_value` FROM `tbl_purchase_details` `A` LEFT JOIN `tbl_purchase` `B` ON `A`.`po_id` = `B`.`id` INNER JOIN `tbl_vendors` `C` ON `B`.`vendor_id` = `C`.`id` INNER JOIN `tbl_product_type` `D` ON `A`.`type_id` = `D`.`id` WHERE `A`.`createdate` >= '".$start_date."' AND `A`.`createdate` <= '".$end_date."' AND `A`.`delete_status` = '1' AND `A`.`published`='1' GROUP BY `A`.`id`");

            $data = [];
            if($sql)
            {
                $num = 0;
                foreach ($sql as $key => $val) {
                    $data[] = array(
                        $this->empty_check($val['po_no']),
                        $this->view_date($val['po_date']),
                        $this->null_check($val['manufacturer_name']),
                        $this->null_check($val['product_name']),
                        $this->zero_check($val['order_qty']),
                        $this->zero_check($val['balance_qty']),
                        $this->zero_check($val['admin_stock']),
                        $this->digit_val($this->zero_check($val['product_price'])),
                        $this->digit_val($this->zero_check($val['order_value'])),
                    );
                }
            }

            $result = array(
                'spreadsheetId' => '1sbYJU8d-Pdg5xtUwdFuhITgt158fvFvm_67Hagquldg',
                'sheet_data'    => $data,
            );

            return $result;
        }

        // Report 6
        public function get_delivery_peport() {

            $start_date = date('Y-m-d').' 00:00:00';
            $end_date   = date('Y-m-d').' 23:59:59';

            // $start_date = '2022-11-25 00:00:00';
            // $end_date   = '2022-12-15 23:59:59';

            $sql = $this->db->query("SELECT  `A`.`invoice_no`, `B`.`createdate` AS `invoice_date`, `C`.`order_no`, `C`.`createdate` AS `order_date`, `C`.`emp_name`, `D`.`company_name` AS `distributor_name`, `E`.`name` AS `beat_name`, `C`.`store_name`, `F`.`description` AS `product_name`, `A`.`order_qty` AS `invoice_qty`, `A`.`receive_qty` AS `delivered_qty`, `B`.`delivery_date` FROM `tbl_invoice_details` `A` LEFT JOIN `tbl_invoice` `B` ON `A`.`invoice_id` = `B`.`id` INNER JOIN `tbl_order` `C` ON `B`.`order_id` = `C`.`id` INNER JOIN `tbl_distributors` `D` ON `B`.`distributor_id` = `D`.`id` INNER JOIN `tbl_zone` `E` ON `B`.`zone_id` = `E`.`id` INNER JOIN `tbl_product_type` `F` ON `A`.`type_id` = `F`.`id` WHERE `A`.`createdate` >= '".$start_date."' AND `A`.`createdate` <= '".$end_date."' AND `B`.`cancel_status` = '1' AND `B`.`delivery_status` = '2' AND `A`.`published`='1' GROUP BY `A`.`id`");

            $data = [];
            if($sql)
            {
                $num = 0;
                foreach ($sql as $key => $val) {
                    $data[] = array(
                        $this->empty_check($val['invoice_no']),
                        $this->view_date($val['invoice_date']),
                        $this->view_time($val['invoice_date']),
                        $this->empty_check($val['order_no']),
                        $this->view_date($val['order_date']),
                        $this->employee_check($val['emp_name']),
                        $this->null_check($val['distributor_name']),
                        $this->null_check($val['beat_name']),
                        $this->null_check($val['store_name']),
                        $this->null_check($val['product_name']),
                        $this->zero_check($val['invoice_qty']),
                        $this->zero_check($val['delivered_qty']),
                        $this->view_date($val['delivery_date']),
                        $this->view_time($val['delivery_date']),
                    );
                }
            }

            $result = array(
                'spreadsheetId' => '1S7EuUlaarrEWNsMXj5TBw7q-s3f1o2tAy_rSQS1cYsw',
                'sheet_data'    => $data,
            );

            return $result;
        }

        // Report 7
        public function store_sheet_name($param1='', $param2='')
        {
            $this->db->query("INSERT INTO `google_sheet`(`sheet_name`, `sheet_id`) VALUES('".$param1."', '".$param2."')");   
        }

        public function get_distributor_list() {

            // Get sheet ID
            $sql_1    = $this->db->query("SELECT `sheet_id` FROM `google_sheet` ORDER BY `id` DESC LIMIT 0,1");
            $val_1    = $sql_1->fetch_assoc();
            $sheet_id = $this->zero_check($val_1['sheet_id']);

            $sql = $this->db->query("SELECT  `A`.`dis_code` AS `dis_code` FROM `tbl_distributors` `A`  WHERE `A`.`status`='1' AND `A`.`published`='1' GROUP BY `A`.`id`");

            $data = [];
            if($sql)
            {
                $data[] = array('name' => 'ITEMS');
                $data[] = array('name' => 'MANUFACTURER');
                $data[] = array('name' => 'HOISST GODOWN');

                $num = 0;
                foreach ($sql as $key => $val) {

                    $data[] = array(
                        'name' => $this->empty_check($val['dis_code']),
                    );
                }
            }

            // $data[]   = array('name' => 'Hoisst');
            $data_res = array_column($data, 'name');
            $data_cnt = count($data); 
            $data_val = $this->number_to_alphabet($data_cnt);

            $result = array(
                'spreadsheetId' => $sheet_id,
                'sheet_range'   => 'A1:'.$data_val.'1',
                'sheet_data'    => array($data_res),
            );

            return $result;
        }

        public function get_current_stock_report() {

            // Get sheet ID
            $sql      = $this->db->query("SELECT `sheet_id` FROM `google_sheet` ORDER BY `id` DESC LIMIT 0,1");
            $val      = $sql->fetch_assoc();
            $sheet_id = $this->zero_check($val['sheet_id']);

            $sql_1  = $this->db->query("SELECT GROUP_CONCAT(`A`.`id`) as `distributor_id` FROM `tbl_distributors` `A` WHERE `A`.`status`='1' AND `A`.`published`='1'");

            $val_1   = $sql_1->fetch_assoc();
            $dis_id  = $val_1['distributor_id'];
            $dis_exp = explode(',', $dis_id);
            $dis_cnt = sizeof($dis_exp);

            $sql_2 = $this->db->query("SELECT  `A`.`id` AS `product_id`, `A`.`description` AS `product_name`, `A`.`product_stock` AS `admin_stk`, `A`.`type_stock` AS `manufacturer_stk` FROM `tbl_product_type` `A`  WHERE `A`.`status`='1' AND `A`.`published`='1' LIMIT 0,145");

            $data   = [];
            $val_1  = [];
            $val_2  = [];
            if($sql_2)
            {
                $num = 0;
                foreach ($sql_2 as $key => $val_2) {

                    $product_id = $this->empty_check($val_2['product_id']);

                    $val_1 = array(
                        $this->empty_check($val_2['product_name']),
                        $this->zero_check($val_2['manufacturer_stk']),
                        $this->zero_check($val_2['admin_stk']),
                    );

                    for ($i=0; $i < $dis_cnt; $i++) { 

                        $sql_3 = $this->db->query("SELECT `B`.`id` AS `distributor_id`, `A`.`stock` AS `stock` FROM `tbl_assign_product_details` `A` INNER JOIN `tbl_distributors` `B` ON `A`.`distributor_id` = `B`.`id` WHERE `A`.`type_id` = '".$product_id."' AND `A`.`distributor_id` = '".$dis_exp[$i]."' AND `A`.`status`='1' AND `A`.`published`='1'");

                        if($sql_3->num_rows == 1)
                        {
                            $val_3   = $sql_3->fetch_assoc();
                            $stk_val = $this->zero_check($val_3['stock']);
                        }
                        else
                        {
                            $stk_val = '0';
                        }

                        $val_1[] = $stk_val;
                    }

                    array_push($data, $val_1);
                }
            }  

            // $data_res = array_column($data, 'name');
            $data_cnt = count($data[0]); 
            $data_val = $this->number_to_alphabet($data_cnt);        

            $result = array(
                'spreadsheetId' => $sheet_id,
                'sheet_range'   => 'A1:'.$data_val.'1',
                'sheet_data'    => $data,
            );

            return $result;
        }

        // Report 8
        public function get_list_of_retailers() {

            $start_date = date('Y-m-d').' 00:00:00';
            $end_date   = date('Y-m-d').' 23:59:59';

            // $start_date = '2022-11-25 00:00:00';
            // $end_date   = '2022-12-15 23:59:59';

            $sql = $this->db->query("SELECT  `A`.`company_name` AS `store_name`, `B`.`name` AS `beat_name` FROM `tbl_outlets` `A` INNER JOIN `tbl_zone` `B` ON `A`.`zone_id` = `B`.`id`  WHERE `A`.`createdate` >= '".$start_date."' AND `A`.`createdate` <= '".$end_date."' AND `A`.`published`='1' GROUP BY `A`.`id`");

            $data = [];
            if($sql)
            {
                $num = 0;
                foreach ($sql as $key => $val) {
                    $data[] = array(
                        $this->empty_check($val['store_name']),
                        $this->empty_check($val['beat_name']),
                    );
                }
            }

            $result = array(
                'spreadsheetId' => '1Ym_GieXIouLP_AKJdYirrAnQfAjsuQOfnA9oCkcLZmY',
                'sheet_data'    => $data,
            );

            return $result;
        }

        // Report 9
        public function get_list_of_items() {

            $start_date = date('Y-m-d').' 00:00:00';
            $end_date   = date('Y-m-d').' 23:59:59';

            // $start_date = '2022-11-25 00:00:00';
            // $end_date   = '2022-12-15 23:59:59';
            
            $sql = $this->db->query("SELECT  `A`.`description` AS `product_name`, `B`.`company_name` AS `manufacturer`, `A`.`product_price` AS `mrp_price`, `A`.`ven_price` AS `manufacturer_price`, `A`.`dis_price` AS `distributor_price` FROM `tbl_product_type` `A` INNER JOIN `tbl_vendors` `B` ON `A`.`vendor_id` = `B`.`id`  WHERE `A`.`createdate` >= '".$start_date."' AND `A`.`createdate` <= '".$end_date."' AND `A`.`published`='1' GROUP BY `A`.`id`");

            $data = [];
            if($sql)
            {
                $num = 0;
                foreach ($sql as $key => $val) {

                    $data[] = array(
                        $this->empty_check($val['product_name']),
                        $this->empty_check($val['manufacturer']),
                        $this->empty_check($val['mrp_price']),
                        $this->digit_val($this->empty_check($val['manufacturer_price'])),
                        $this->digit_val($this->empty_check($val['distributor_price'])),
                    );
                }
            }

            $result = array(
                'spreadsheetId' => '1Ym_GieXIouLP_AKJdYirrAnQfAjsuQOfnA9oCkcLZmY',
                'sheet_data'    => $data,
            );

            return $result; 
        }

        // Report 10
        public function get_list_of_employees() {

            $start_date = date('Y-m-d').' 00:00:00';
            $end_date   = date('Y-m-d').' 23:59:59';

            // $start_date = '2022-11-25 00:00:00';
            // $end_date   = '2022-12-15 23:59:59';
            
            $sql = $this->db->query("SELECT  `A`.`username` AS `emp_name`, `A`.`log_type` AS `Designation`, `A`.`address` AS `location`, `A`.`status`, `A`.`createdate` AS `join_date` FROM `tbl_employee` `A`  WHERE `A`.`createdate` >= '".$start_date."' AND `A`.`createdate` <= '".$end_date."' AND `A`.`published`='1' GROUP BY `A`.`id`");

            $data = [];
            if($sql)
            {
                $num = 0;
                foreach ($sql as $key => $val) {
                    $data[] = array(
                        $this->null_check($val['emp_name']),
                        $this->employee_type($val['Designation']),
                        $this->null_check($val['location']),
                        $this->status_type($val['status']),
                        $this->view_date($val['join_date']),
                    );
                }
            }

            $result = array(
                'spreadsheetId' => '1Ym_GieXIouLP_AKJdYirrAnQfAjsuQOfnA9oCkcLZmY',
                'sheet_data'    => $data,
            );

            return $result; 
        }

        // Report 11
        public function get_bde_movements() {

            $start_date = date('Y-m-d').' 00:00:00';
            $end_date   = date('Y-m-d').' 23:59:59';

            // $start_date = '2022-11-25 00:00:00';
            // $end_date   = '2022-12-15 23:59:59';

            $sql = $this->db->query("SELECT  `A`.`c_date` AS `date`, `A`.`in_time` AS `from_time`, `A`.`out_time` AS `to_time`, `A`.`emp_name`, `C`.`name` AS `beat_name`, `A`.`store_name` FROM `tbl_attendance` `A` LEFT JOIN `tbl_outlets` `B` ON `A`.`store_id` = `B`.`id` INNER JOIN `tbl_zone` `C` ON `B`.`zone_id` = `C`.`id` WHERE `A`.`createdate` >= '".$start_date."' AND `A`.`createdate` <= '".$end_date."' AND `A`.`published`='1' GROUP BY `A`.`id`");

            $data = [];
            if($sql)
            {
                $num = 0;
                foreach ($sql as $key => $val) {
                    if(!empty($val['to_time']))
                    {
                        $data[] = array(
                            $this->view_date($val['date']),
                            $this->view_time($val['from_time']),
                            $this->view_time($val['to_time']),
                            $this->employee_check($val['emp_name']),
                            $this->null_check($val['beat_name']),
                            $this->null_check($val['store_name']),
                        );
                    }
                    else
                    {
                        $data[] = array(
                            $this->view_date($val['date']),
                            $this->view_time($val['from_time']),
                            'Not update',
                            $this->employee_check($val['emp_name']),
                            $this->null_check($val['beat_name']),
                            $this->null_check($val['store_name']),
                        );
                    }
                }
            }

            $result = array(
                'spreadsheetId' => '1O_ZhDvn5LG-nWw45MpkWlokArWE-0pvRs1Kr6jk_xmw',
                'sheet_data'    => $data,
            );

            return $result;
        }

        // Report 12
        public function get_attendance() {

            $start_date = date('Y-m-d').' 00:00:00';
            $end_date   = date('Y-m-d').' 23:59:59';

            // $start_date = '2022-11-25 00:00:00';
            // $end_date   = '2022-12-15 23:59:59';

            $sql_1 = $this->db->query("SELECT  `A`.`emp_id`, `A`.`emp_name`, `A`.`c_date` AS `date`, `A`.`in_time` AS `clock_in` FROM `tbl_attendance` `A` WHERE `A`.`createdate` >= '".$start_date."' AND `A`.`createdate` <= '".$end_date."' AND `A`.`published`='1' GROUP BY `A`.`emp_id`");

            $data = [];
            if($sql_1)
            {
                $num = 0;
                foreach ($sql_1 as $key => $val_1) {

                    $emp_id = $this->empty_check($val_1['emp_id']);

                    // // Get last value
                    $sql_2 = $this->db->query("SELECT  `A`.`out_time` AS `clock_out` FROM `tbl_attendance` `A` WHERE `A`.`emp_id` = '".$emp_id."' AND `A`.`createdate` >= '".$start_date."' AND `A`.`createdate` <= '".$end_date."' AND `A`.`published`='1' ORDER BY `A`.`id` DESC LIMIT 0,1");

                    $val_2 = $sql_2->fetch_assoc();

                    $data[] = array(
                        $this->employee_check($val_1['emp_name']),
                        $this->view_date($val_1['date']),
                        $this->view_time($val_1['clock_in']),
                        $this->view_time($val_2['clock_out']),
                    );
                }
            }

            $result = array(
                'spreadsheetId' => '11hthm6nSQLUBVr6VN6V9rDiuZKGbgI7lCle6cqCY_Wg',
                'sheet_data'    => $data,
            );

            return $result;
        }

        // Report 13
        public function get_all_sales_orders() {

            $start_date = date('Y-m-d').' 00:00:00';
            $end_date   = date('Y-m-d').' 23:59:59';

            // $start_date = '2022-11-25 00:00:00';
            // $end_date   = '2022-12-15 23:59:59';

            $sql_1 = $this->db->query("SELECT  `A`.`order_no`, `A`.`_ordered` AS `order_date`, `A`.`zone_id` AS `beat_id`, `C`.`name` AS `beat_name`, `B`.`emp_name`, `B`.`store_name`, `A`.`type_id` AS `product_id`, `D`.`description` AS `product_name`, `A`.`order_qty`, `A`.`price`, SUM(`A`.`order_qty` * `A`.`price`) AS `bill_value` FROM `tbl_order_details` `A` INNER JOIN `tbl_order` `B` ON `A`.`order_id` = `B`.`id` INNER JOIN `tbl_zone` `C` ON `B`.`zone_id` = `C`.`id` INNER JOIN `tbl_product_type` `D` ON `A`.`type_id` = `D`.`id` WHERE `A`.`_processing` >= '".$start_date."' AND `A`.`_processing` <= '".$end_date."' AND `A`.`delete_status` = '1' AND `A`.`published`='1' GROUP BY `A`.`id`");

            $data = [];
            if($sql_1)
            {
                $num = 0;
                foreach ($sql_1 as $key => $val_1) {

                    $beat_id    = $this->empty_check($val_1['beat_id']);
                    $product_id = $this->empty_check($val_1['product_id']);

                    // Distributor details
                    $sql_2 = $this->db->query("SELECT  `B`.`company_name` AS `distributor_name` FROM `tbl_assign_product_details` `A` INNER JOIN `tbl_distributors` `B` ON `A`.`distributor_id` = `B`.`id` WHERE `A`.`zone_id` LIKE '%,".$beat_id.",%' AND `A`.`type_id` = '".$product_id."' AND `A`.`published`='1'");

                    $val_2 = $sql_2->fetch_assoc();
                    
                    $data[] = array(
                        $this->null_check($val_1['order_no']),
                        $this->view_date($val_1['order_date']),
                        $this->distributor_check($val_2['distributor_name']),
                        $this->null_check($val_1['beat_name']),
                        $this->employee_check($val_1['emp_name']),
                        $this->null_check($val_1['store_name']),
                        $this->null_check($val_1['product_name']),
                        $this->zero_check($val_1['order_qty']),
                        $this->digit_val($this->zero_check($val_1['price'])),
                        $this->digit_val($this->zero_check($val_1['bill_value'])),
                    );
                }
            }

            $result = array(
                'spreadsheetId' => '1WwkBMudh4bOQawAxrcCWp9kww4aaXEX1y6aZxeEKxRQ',
                'sheet_data'    => $data,
            );

            return $result;
        }

        // Report 14
        public function get_all_deliveries() {

            $start_date = date('Y-m-d').' 00:00:00';
            $end_date   = date('Y-m-d').' 23:59:59';

            // $start_date = '2022-11-25 00:00:00';
            // $end_date   = '2022-12-15 23:59:59';

            $sql = $this->db->query("SELECT  `A`.`invoice_no`, `B`.`createdate` AS `invoice_date`, `C`.`order_no`, `C`.`createdate` AS `order_date`, `D`.`company_name` AS `distributor_name`, `E`.`name` AS `beat_name`, `C`.`emp_name`, `C`.`store_name`, `F`.`description` AS `product_name`, `A`.`order_qty` AS `invoice_qty`, `A`.`price` AS `unit_value`, SUM(`A`.`order_qty` * `A`.`price`) AS `bill_value` FROM `tbl_invoice_details` `A` LEFT JOIN `tbl_invoice` `B` ON `A`.`invoice_id` = `B`.`id` INNER JOIN `tbl_order` `C` ON `B`.`order_id` = `C`.`id` INNER JOIN `tbl_distributors` `D` ON `B`.`distributor_id` = `D`.`id` INNER JOIN `tbl_zone` `E` ON `B`.`zone_id` = `E`.`id` INNER JOIN `tbl_product_type` `F` ON `A`.`type_id` = `F`.`id` WHERE `A`.`createdate` >= '".$start_date."' AND `A`.`createdate` <= '".$end_date."' AND `B`.`cancel_status` = '1' AND `B`.`delivery_status` = '2' AND `A`.`published`='1' GROUP BY `A`.`id`");

            $data = [];
            if($sql)
            {
                $num = 0;
                foreach ($sql as $key => $val) {
                    $data[] = array(
                        $this->null_check($val['invoice_no']),
                        $this->view_date($val['invoice_date']),
                        $this->null_check($val['order_no']),
                        $this->view_date($val['order_date']),
                        $this->null_check($val['distributor_name']),
                        $this->null_check($val['beat_name']),
                        $this->employee_check($val['emp_name']),
                        $this->null_check($val['store_name']),
                        $this->null_check($val['product_name']),
                        $this->zero_check($val['invoice_qty']),
                        $this->digit_val($this->zero_check($val['unit_value'])),
                        $this->digit_val($this->zero_check($val['bill_value'])),
                    );
                }
            }

            $result = array(
                'spreadsheetId' => '1l9sJD3ELy3cElr40Npn4mQwb6_7t3DWHrQYA5QGjvbM',
                'sheet_data'    => $data,
            );

            return $result;
        }

        // Report 15
        public function get_all_invoices() {

            $start_date = date('Y-m-d').' 00:00:00';
            $end_date   = date('Y-m-d').' 23:59:59';

            // $start_date = '2022-11-25 00:00:00';
            // $end_date   = '2022-12-15 23:59:59';

            $sql = $this->db->query("SELECT  `A`.`invoice_no`, `A`.`createdate` AS `invoice_date`, `B`.`company_name` AS `distributor_name`, `C`.`name` AS `beat_name`, `D`.`username` AS `emp_name`, `A`.`store_name`, SUM(`E`.`receive_qty` * `E`.`price`) AS `bill_value` FROM `tbl_invoice` `A` INNER JOIN `tbl_distributors` `B` ON `A`.`distributor_id` = `B`.`id` INNER JOIN `tbl_zone` `C` ON `A`.`zone_id` = `C`.`id` INNER JOIN `tbl_employee` `D` ON `A`.`sales_employee` = `D`.`id` INNER JOIN `tbl_invoice_details` `E` ON `A`.`id` = `E`.`invoice_id` WHERE `A`.`createdate` >= '".$start_date."' AND `A`.`createdate` <= '".$end_date."' AND `A`.`cancel_status` = '1' AND `A`.`published`='1' GROUP BY `A`.`id`");

            $data = [];
            if($sql)
            {
                $num = 0;
                foreach ($sql as $key => $val) {
                    $data[] = array(
                        $this->null_check($val['invoice_no']),
                        $this->view_date($val['invoice_date']),
                        $this->null_check($val['distributor_name']),
                        $this->null_check($val['beat_name']),
                        $this->employee_check($val['emp_name']),
                        $this->null_check($val['store_name']),
                        $this->digit_val($this->zero_check($val['bill_value'])),
                    );
                }
            }

            $result = array(
                'spreadsheetId' => '1CuPEPZxj6rsFIgeU5kc08nmNjUQoI7PHdgXyt8uZ-so',
                'sheet_data'    => $data,
            );

            return $result;
        }

        // Report 16
        public function get_all_internal_po() {

            $start_date = date('Y-m-d').' 00:00:00';
            $end_date   = date('Y-m-d').' 23:59:59';

            // $start_date = '2022-11-25 00:00:00';
            // $end_date   = '2022-12-15 23:59:59';

            $sql = $this->db->query("SELECT `A`.`po_no`, `B`.`createdate` AS `po_date`, `C`.`company_name` AS `distributor`, `D`.`description` AS `product_name`, `B`.`product_qty` AS `order_qty`, `B`.`product_price` AS `price`, SUM(`B`.`product_qty` * `B`.`product_price`) AS `item_value` FROM `tbl_dis_purchase` `A` INNER JOIN `tbl_dis_purchase_details` `B` ON `A`.`id` = `B`.`po_id` INNER JOIN `tbl_distributors` `C` ON `A`.`distributor_id` = `C`.`id` INNER JOIN `tbl_product_type` `D` ON `B`.`type_id` = `D`.`id` WHERE `A`.`_processing` >= '".$start_date."' AND `A`.`_processing` <= '".$end_date."' AND `A`.`published`='1' GROUP BY `B`.`id`");

            $data = [];
            if($sql)
            {
                $num = 0;
                foreach ($sql as $key => $val) {
                    $data[] = array(
                        $this->null_check($val['po_no']),
                        $this->view_date($val['po_date']),
                        $this->null_check($val['distributor']),
                        $this->null_check($val['product_name']),
                        $this->zero_check($val['order_qty']),
                        $this->digit_val($this->zero_check($val['price'])),
                        $this->digit_val($this->zero_check($val['item_value'])),
                    );
                }
            }

            $result = array(
                'spreadsheetId' => '1Y0m_Z1HWk11JNgs0QUmM1WGY93OEt4nRUokauf43KSw',
                'sheet_data'    => $data,
            );

            return $result;
        }

        // Report 17
        public function get_all_external_po() {

            $start_date = date('Y-m-d').' 00:00:00';
            $end_date   = date('Y-m-d').' 23:59:59';

            // $start_date = '2022-11-25 00:00:00';
            // $end_date   = '2022-12-15 23:59:59';

            $sql = $this->db->query("SELECT `A`.`po_no`, `B`.`createdate` AS `po_date`, `C`.`company_name` AS `manufacturer`, `D`.`description` AS `product_name`, `B`.`product_qty` AS `order_qty`, `B`.`product_price` AS `price`, SUM(`B`.`product_qty` * `B`.`product_price`) AS `item_value` FROM `tbl_purchase` `A` INNER JOIN `tbl_purchase_details` `B` ON `A`.`id` = `B`.`po_id` INNER JOIN `tbl_vendors` `C` ON `A`.`vendor_id` = `C`.`id` INNER JOIN `tbl_product_type` `D` ON `B`.`type_id` = `D`.`id` WHERE `A`.`_processing` >= '".$start_date."' AND `A`.`_processing` <= '".$end_date."' AND `A`.`published`='1' GROUP BY `B`.`id`");

            $data = [];
            if($sql)
            {
                $num = 0;
                foreach ($sql as $key => $val) {
                    $data[] = array(
                        $this->null_check($val['po_no']),
                        $this->view_date($val['po_date']),
                        $this->null_check($val['manufacturer']),
                        $this->null_check($val['product_name']),
                        $this->zero_check($val['order_qty']),
                        $this->digit_val($this->zero_check($val['price'])),
                        $this->digit_val($this->zero_check($val['item_value'])),
                    );
                }
            }

            $result = array(
                'spreadsheetId' => '1kl3Iyc0LigJdLnYUTLTTKZB-Q_qNcIkBmk6kzj5VgGA',
                'sheet_data'    => $data,
            );

            return $result;
        }
    }

?>