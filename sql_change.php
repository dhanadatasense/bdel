<?php
	
	function urlSlug($text, string $divider = '-')
	{
	    // replace non letter or digits by divider
	    $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

	    // transliterate
	    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

	    // remove unwanted characters
	    $text = preg_replace('~[^-\w]+~', '', $text);

	    // trim
	    $text = trim($text, $divider);

	    // remove duplicate divider
	    $text = preg_replace('~-+~', $divider, $text);

	    // lowercase
	    $text = strtolower($text);

	    if (empty($text)) {
	        return 'n-a';
	    }

	    return $text;
	}

	$conn = mysqli_connect("localhost","root","","beta_hoisst");

	if(isset($_POST['submit']))
	{
		$input_type = !empty($_POST['input_type'])?$_POST['input_type']:'';

		// Admin Purchase List
		if($input_type == 1)
		{
			$sel_1 = mysqli_query($conn, "SELECT `id`, `vendor_id` FROM `tbl_purchase`");
			$cou_1 = mysqli_num_rows($sel_1);

			if($cou_1 > 0) 
			{
				foreach ($sel_1 as $key => $val_1) {
						
					$po_id     = !empty($val_1['id'])?$val_1['id']:'';
    				$vendor_id = !empty($val_1['vendor_id'])?$val_1['vendor_id']:'';

    				// Vendor Details
    				$sel_2 = mysqli_fetch_object(mysqli_query($conn, "SELECT `company_name` FROM `tbl_vendors` WHERE `id` = '".$vendor_id."'"));

    				$ven_name = !empty($sel_2->company_name)?$sel_2->company_name:'';

    				// Update Vendor Name
    				$upt_1 = mysqli_query($conn, "UPDATE `tbl_purchase` SET `vendor_name` = '".$ven_name."' WHERE `id` = '".$po_id."'");
				}

				echo "Success";
			}
		}

		else if($input_type == 2)
		{
			$sel_1 = mysqli_query($conn, "SELECT `id`, `vendor_id` FROM `tbl_purchase_return`");
			$cou_1 = mysqli_num_rows($sel_1);

			if($cou_1 > 0) 
			{
				foreach ($sel_1 as $key => $val_1) {
						
					$po_id     = !empty($val_1['id'])?$val_1['id']:'';
    				$vendor_id = !empty($val_1['vendor_id'])?$val_1['vendor_id']:'';

    				// Vendor Details
    				$sel_2 = mysqli_fetch_object(mysqli_query($conn, "SELECT `company_name` FROM `tbl_vendors` WHERE `id` = '".$vendor_id."'"));

    				$ven_name = !empty($sel_2->company_name)?$sel_2->company_name:'';

    				// Update Vendor Name
    				$upt_1 = mysqli_query($conn, "UPDATE `tbl_purchase_return` SET `vendor_name` = '".$ven_name."' WHERE `id` = '".$po_id."'");
				}

				echo "Success";
			}
		}

		else if($input_type == 3)
		{
			$sel_1 = mysqli_query($conn, "SELECT `id`, `distributor_id` FROM `tbl_dis_purchase`");
			$cou_1 = mysqli_num_rows($sel_1);

			if($cou_1 > 0) 
			{
				foreach ($sel_1 as $key => $val_1) {
						
					$po_id  = !empty($val_1['id'])?$val_1['id']:'';
    				$dis_id = !empty($val_1['distributor_id'])?$val_1['distributor_id']:'';

    				// Vendor Details
    				$sel_2 = mysqli_fetch_object(mysqli_query($conn, "SELECT `company_name` FROM `tbl_distributors` WHERE `id` = '".$dis_id."'"));

    				$dis_name = !empty($sel_2->company_name)?$sel_2->company_name:'';

    				// Update Vendor Name
    				$upt_1 = mysqli_query($conn, "UPDATE `tbl_dis_purchase` SET `distributor_name` = '".$dis_name."' WHERE `id` = '".$po_id."'");
				}

				echo "Success";
			}
		}

		else if($input_type == 4)
		{
			$sel_1 = mysqli_query($conn, "SELECT `id`, `bill_no` FROM `tbl_distributor_payment`");
			$cou_1 = mysqli_num_rows($sel_1);

			if($cou_1 > 0) 
			{
				foreach ($sel_1 as $key => $val_1) {
						
					$bill_id = !empty($val_1['id'])?$val_1['id']:'';	
					$inv_no  = !empty($val_1['bill_no'])?$val_1['bill_no']:'';

					$sel_2  = mysqli_fetch_object(mysqli_query($conn, "SELECT `id` FROM `tbl_distributor_invoice` WHERE `invoice_no` = '".$inv_no."' "));

					$inv_id = !empty($sel_2->id)?$sel_2->id:'';

    				// Update Vendor Name
    				$upt_1 = mysqli_query($conn, "UPDATE `tbl_distributor_payment` SET `bill_id` = '".$inv_id."' WHERE `id` = '".$bill_id."'");

				}

				echo "success";
			}
		}

		else if($input_type == 5)
		{
			$sel_1 = mysqli_query($conn, "SELECT `id`, `bill_no` FROM `tbl_distributor_payment_details` WHERE `bill_code` = 'INV' ");
			$cou_1 = mysqli_num_rows($sel_1);

			if($cou_1 > 0) 
			{
				foreach ($sel_1 as $key => $val_1) {
						
					$bill_id = !empty($val_1['id'])?$val_1['id']:'';	
					$inv_no  = !empty($val_1['bill_no'])?$val_1['bill_no']:'';

					$sel_2  = mysqli_fetch_object(mysqli_query($conn, "SELECT `id` FROM `tbl_distributor_invoice` WHERE `invoice_no` = '".$inv_no."' "));

					$inv_id = !empty($sel_2->id)?$sel_2->id:'';

    				// Update Vendor Name
    				$upt_1 = mysqli_query($conn, "UPDATE `tbl_distributor_payment_details` SET `bill_id` = '".$inv_id."' WHERE `id` = '".$bill_id."'");

				}

				echo "success";
			}
		}

		else if($input_type == 6)
		{
			$sel_1 = mysqli_query($conn, "SELECT `id`, `bill_no` FROM `tbl_vendor_payment`");
			$cou_1 = mysqli_num_rows($sel_1);

			if($cou_1 > 0) 
			{
				foreach ($sel_1 as $key => $val_1) {
						
					$bill_id = !empty($val_1['id'])?$val_1['id']:'';	
					$inv_no  = !empty($val_1['bill_no'])?$val_1['bill_no']:'';

					$sel_2  = mysqli_fetch_object(mysqli_query($conn, "SELECT `id` FROM `tbl_vendor_invoice` WHERE `invoice_no` = '".$inv_no."' "));

					$inv_id = !empty($sel_2->id)?$sel_2->id:'';

    				// Update Vendor Name
    				$upt_1 = mysqli_query($conn, "UPDATE `tbl_vendor_payment` SET `bill_id` = '".$inv_id."' WHERE `id` = '".$bill_id."'");

				}

				echo "success";
			}
		}

		else if($input_type == 7)
		{
			$sel_1 = mysqli_query($conn, "SELECT `id`, `bill_no` FROM `tbl_vendor_payment_details` WHERE `bill_code` = 'INV' ");
			$cou_1 = mysqli_num_rows($sel_1);

			if($cou_1 > 0) 
			{
				foreach ($sel_1 as $key => $val_1) {
						
					$bill_id = !empty($val_1['id'])?$val_1['id']:'';	
					$inv_no  = !empty($val_1['bill_no'])?$val_1['bill_no']:'';

					$sel_2  = mysqli_fetch_object(mysqli_query($conn, "SELECT `id` FROM `tbl_vendor_invoice` WHERE `invoice_no` = '".$inv_no."' "));

					$inv_id = !empty($sel_2->id)?$sel_2->id:'';

    				// Update Vendor Name
    				$upt_1 = mysqli_query($conn, "UPDATE `tbl_vendor_payment_details` SET `bill_id` = '".$inv_id."' WHERE `id` = '".$bill_id."'");

				}

				echo "success";
			}
		}

		else if($input_type == 8)
		{
			$sel_1 = mysqli_query($conn, "SELECT `id`, `invoice_value` FROM `tbl_order_details` WHERE `invoice_value` != '' ");
			$cou_1 = mysqli_num_rows($sel_1);

			if($cou_1 > 0) 
			{
				foreach ($sel_1 as $key => $val_1) {
					$inv_id  = !empty($val_1['id'])?$val_1['id']:'';
    				$inv_val = !empty($val_1['invoice_value'])?$val_1['invoice_value']:'';

    				// Vendor Details
    				$sel_2 = mysqli_fetch_object(mysqli_query($conn, "SELECT `invoice_no` FROM `tbl_invoice` WHERE `random_value` = '".$inv_val."'"));

    				$inv_no = !empty($sel_2->invoice_no)?$sel_2->invoice_no:'';

    				// Update Vendor Name
    				echo "UPDATE `tbl_order_details` SET `invoice_num` = '".$inv_no."' WHERE `invoice_value` = '".$inv_val."'".';<br>';
    				// $upt_1 = mysqli_query($conn, "UPDATE `tbl_order_details` SET `invoice_num` = '".$inv_no."' WHERE `id` = '".$inv_id."'");
				}

				echo "success";	
			}
		}

		else if($input_type == 9)
		{
			$sel_1 = mysqli_query($conn, "SELECT `id`, `invoice_id`, `invoice_no` FROM `tbl_dis_purchase` WHERE `invoice_id` != 0 ");
			$cou_1 = mysqli_num_rows($sel_1);

			if($cou_1 > 0) 
			{
				foreach ($sel_1 as $key => $val_1) {

					$po_id  = !empty($val_1['id'])?$val_1['id']:'';
					$inv_id = !empty($val_1['invoice_id'])?$val_1['invoice_id']:'';
    				$inv_no = !empty($val_1['invoice_no'])?$val_1['invoice_no']:'';

    				// Invoice Details
    				$sel_2 = mysqli_fetch_object(mysqli_query($conn, "SELECT `random_value` FROM `tbl_distributor_invoice` WHERE `order_id` = '".$po_id."'"));

    				$inv_val = !empty($sel_2->random_value)?$sel_2->random_value:'';

    				// Update Invoice Name
    				$upt_1 = mysqli_query($conn, "UPDATE `tbl_dis_purchase` SET `invoice_random` = '".$inv_val."' WHERE `id` = '".$po_id."'");
				}

				echo "Success";
			}
		}
		
		else if($input_type == 10)
		{
			$sel_1 = mysqli_query($conn, "SELECT `id`, `type_id`, `product_unit` FROM `tbl_assign_product_details`");
			$cou_1 = mysqli_num_rows($sel_1);

			if($cou_1 > 0) 
			{
				foreach ($sel_1 as $key => $val_1) 
				{
					$auto_id  = !empty($val_1['id'])?$val_1['id']:'';
				    $type_id  = !empty($val_1['type_id'])?$val_1['type_id']:'';

				    $sel_2 = mysqli_fetch_object(mysqli_query($conn, "SELECT `product_unit` FROM `tbl_product_type` WHERE `id` = '".$type_id."'"));

				    $pdt_unit = !empty($sel_2->product_unit)?$sel_2->product_unit:'';

				    $upt_1 = mysqli_query($conn, "UPDATE `tbl_assign_product_details` SET `product_unit` = '".$pdt_unit."' WHERE `id` = '".$auto_id."'");
				}

				echo "Success";
			}
		}

		else if($input_type == 11)
		{
			$sel_1 = mysqli_query($conn, "SELECT `id`, `createdate` FROM `tbl_outlets`");
			$cou_1 = mysqli_num_rows($sel_1);

			if($cou_1 > 0) 
			{
				foreach ($sel_1 as $key => $val_1) 
				{
					$auto_id = !empty($val_1['id'])?$val_1['id']:'';
				    $c_date  = !empty($val_1['createdate'])?date('Y-m-d', strtotime($val_1['createdate'])):'';

				    $upt_1 = mysqli_query($conn, "UPDATE `tbl_outlets` SET `date` = '".$c_date."' WHERE `id` = '".$auto_id."'");
				}

				echo "Success";
			}
		}

		else if($input_type == 12)
		{
			$sel_1 = mysqli_query($conn, "SELECT `id`, `distributor_id`, `store_id` FROM `tbl_outlet_return`");
			$cou_1 = mysqli_num_rows($sel_1);

			if($cou_1 > 0) 
			{
				foreach ($sel_1 as $key => $val_1) 
				{
					$auto_id = !empty($val_1['id'])?$val_1['id']:'';
					$dis_id  = !empty($val_1['distributor_id'])?$val_1['distributor_id']:'';
					$str_id  = !empty($val_1['store_id'])?$val_1['store_id']:'';

					$upt_1 = mysqli_query($conn, "UPDATE `tbl_outlet_return_details` SET `distributor_id` = '".$dis_id."', `outlet_id` = '".$str_id."' WHERE `return_id` = '".$auto_id."'");
				}
			}
		}

		else if($input_type == 13)
		{
			$sel_1 = mysqli_query($conn, "SELECT `id`, `zone_id`, `store_id` FROM `tbl_invoice`");
			$cou_1 = mysqli_num_rows($sel_1);

			if($cou_1 > 0) 
			{
				foreach ($sel_1 as $key => $val_1)
				{
					$auto_id  = !empty($val_1['id'])?$val_1['id']:'';
					$zone_id  = !empty($val_1['zone_id'])?$val_1['zone_id']:'';
					$store_id = !empty($val_1['store_id'])?$val_1['store_id']:'';

					// Outlet Details
					$sel_2 = mysqli_fetch_object(mysqli_query($conn, "SELECT `id`, `state_id`, `city_id`, `zone_id` FROM `tbl_outlets` WHERE `id` = '".$store_id."'"));

					$state_id = !empty($sel_2->state_id)?$sel_2->state_id:'';
					$city_id  = !empty($sel_2->city_id)?$sel_2->city_id:'';
					$zone_id  = !empty($sel_2->zone_id)?$sel_2->zone_id:'';

					$upt_1 = mysqli_query($conn, "UPDATE `tbl_invoice` SET `state_id` = '".$state_id."', `city_id` = '".$city_id."', `zone_id` = '".$zone_id."' WHERE `id` = '".$auto_id."'");
				}
			}
		}

		else if($input_type == 14)
		{
			$sel_1 = mysqli_query($conn, "SELECT `id`, `bill_no` FROM `tbl_outlet_payment` WHERE `bill_no` LIKE '%INV%'");
			$cou_1 = mysqli_num_rows($sel_1);

			if($cou_1 > 0) 
			{
				foreach ($sel_1 as $key => $val_1) 
				{
					$auto_id = !empty($val_1['id'])?$val_1['id']:'';
					$bill_no = !empty($val_1['bill_no'])?$val_1['bill_no']:'';

					$upt_1 = mysqli_query($conn, "UPDATE `tbl_outlet_payment` SET `bill_code` = 'INV' WHERE `id` = '".$auto_id."'");
				}
			}
		}

		else if($input_type == 15)
		{
			$sel_1 = mysqli_query($conn, "SELECT `id`, `invoice_id` FROM `tbl_outlet_return`");
			$cou_1 = mysqli_num_rows($sel_1);

			if($cou_1 > 0) 
			{
				foreach ($sel_1 as $key => $val_1)
				{
					$auto_id    = !empty($val_1['id'])?$val_1['id']:'';
    				$invoice_id = !empty($val_1['invoice_id'])?$val_1['invoice_id']:'';

    				// Payment Table
    				$sel_2 = mysqli_fetch_object(mysqli_query($conn, "SELECT `bill_id`, `bill_no` FROM `tbl_outlet_payment` WHERE `id` = '".$invoice_id."' "));

    				$bill_id = !empty($sel_2->bill_id)?$sel_2->bill_id:'';
    				$bill_no = !empty($sel_2->bill_no)?$sel_2->bill_no:'';

    				// Invoice Details
    				$sel_3 = mysqli_fetch_object(mysqli_query($conn, "SELECT `id` FROM `tbl_invoice` WHERE `order_id` = '".$bill_id."' AND `invoice_no` = '".$bill_no."'"));

    				$inv_id = !empty($sel_3->id)?$sel_3->id:'';

    				// Update Return
    				$upt_1 = mysqli_query($conn, "UPDATE `tbl_outlet_return` SET `invoice_id` = '".$inv_id."' WHERE `id` = '".$auto_id."'");

    				$upt_2 = mysqli_query($conn, "UPDATE `tbl_outlet_return_details` SET `invoice_id` = '".$inv_id."' WHERE `return_id` = '".$auto_id."'");
				}

				echo "success";
			}
		}

		else if($input_type == 16)
		{
			// Distributor Return
			$sel_1 = mysqli_query($conn, "SELECT `id`, `invoice_id`, `distributor_id` FROM `tbl_dis_return`");
			$cou_1 = mysqli_num_rows($sel_1);

			if($cou_1 > 0) 
			{
				foreach ($sel_1 as $key => $val_1) {
					$auto_id = !empty($val_1['id'])?$val_1['id']:'';
				    $inv_id  = !empty($val_1['invoice_id'])?$val_1['invoice_id']:'';
				    $dis_id  = !empty($val_1['distributor_id'])?$val_1['distributor_id']:'';

				    $sel_2 = mysqli_fetch_object(mysqli_query($conn, "SELECT `id` FROM `tbl_distributor_invoice` WHERE `order_id` = '".$inv_id."' AND `distributor_id` = '".$dis_id."' "));

				    print_r($sel_2);
				}
			}
		}

		else if($input_type == 17)
		{
			$sel_1 = mysqli_query($conn, "SELECT `id`, `company_name` FROM `tbl_outlets` LIMIT 7134, 2000");
			$cou_1 = mysqli_num_rows($sel_1);

			if($cou_1 > 0) 
			{
				foreach ($sel_1 as $key => $val_1) {

					$outlet_id  = !empty($val_1['id'])?$val_1['id']:'';
    				$store_name = !empty($val_1['company_name'])?$val_1['company_name']:'';

    				$upt_1 = mysqli_query($conn, "UPDATE `tbl_outlets` SET `short_code` = '".urlSlug($store_name)."' WHERE `id` = '".$outlet_id."'");
				}

				echo "Success";
			}
		}

		else if($input_type == 18)
		{
			// Distributor Return
			$sel_1 = mysqli_query($conn, "SELECT `id`, `receive_qty` FROM `tbl_purchase_details`");
			$cou_1 = mysqli_num_rows($sel_1);

			if($cou_1 > 0) 
			{
				foreach ($sel_1 as $key => $val_1) {
					$auto_id     = !empty($val_1['id'])?$val_1['id']:'';
    				$receive_qty = !empty($val_1['receive_qty'])?$val_1['receive_qty']:'0';
					
					if($receive_qty == 0)
					{
						$upt_1 = mysqli_query($conn, "UPDATE `tbl_purchase_details` SET `receive_qty` = '0' WHERE `id` = '".$auto_id."'");
					}
				}

				echo "Success";
			}
		}

		else if($input_type == 19)
		{
			$sel_1 = mysqli_query($conn, "SELECT `id` FROM `tbl_order_details` WHERE `order_status` = '3' AND `invoice_num` != '';");
			$cou_1 = mysqli_num_rows($sel_1);

			if($cou_1 > 0) 
			{
				foreach ($sel_1 as $key => $val_1) {

					$auto_id  = !empty($val_1['id'])?$val_1['id']:'';

    				$upt_1 = mysqli_query($conn, "UPDATE `tbl_order_details` SET `order_status` = '5', `product_process` = '5' WHERE `id` = '".$auto_id."'");
				}

				echo "Success";
			}
		}

		else if($input_type == 20)
		{
			$sel_1 = mysqli_query($conn, "SELECT `id` FROM `tbl_order_details` WHERE `order_status` = '4' AND `invoice_num` != '';");
			$cou_1 = mysqli_num_rows($sel_1);

			if($cou_1 > 0) 
			{
				foreach ($sel_1 as $key => $val_1) {

					$auto_id  = !empty($val_1['id'])?$val_1['id']:'';

    				$upt_1 = mysqli_query($conn, "UPDATE `tbl_order_details` SET `order_status` = '5', `product_process` = '5' WHERE `id` = '".$auto_id."'");
				}

				echo "Success";
			}
		}

		else if($input_type == 21)
		{
			$sel_1  = mysqli_query($conn, "SELECT GROUP_CONCAT(`id`) as `outlet_id` FROM `tbl_outlets` WHERE `zone_id` = '142' AND `published` = '1' ORDER BY `id` DESC");
			$cou_1  = mysqli_num_rows($sel_1);
			$res_1  = mysqli_fetch_object($sel_1);
			$str_id = !empty($res_1->outlet_id)?$res_1->outlet_id:'';

			$sel_2 = mysqli_query($conn, "SELECT `id`, `category_id`, `product_id`, `type_id`, `product_price`, `date` FROM `tbl_distributor_price` WHERE `distributor_id` = '25' AND `published` = '1' ORDER BY `date` DESC");
			$cou_2 = mysqli_num_rows($sel_2);

			// echo $cou_2; exit;

			if($cou_2 > 0) 
			{
				foreach ($sel_2 as $key => $val_2) {

					$category_id   = !empty($val_2['category_id'])?$val_2['category_id']:'';
				    $product_id    = !empty($val_2['product_id'])?$val_2['product_id']:'';
				    $type_id       = !empty($val_2['type_id'])?$val_2['type_id']:'';
				    $product_price = !empty($val_2['product_price'])?$val_2['product_price']:'0';
				    $date          = !empty($val_2['date'])?$val_2['date']:'';

				    $str_value = explode(',', $str_id);
				    $str_count = count($str_value);

				    for ($i=0; $i < $str_count; $i++) { 
				    	$sel_3 = mysqli_fetch_object(mysqli_query($conn, "SELECT `company_name` FROM `tbl_outlets` WHERE `id` = '".$str_value[$i]."' AND `published` = '1'"));

				    	$str_name = !empty($sel_3->company_name)?$sel_3->company_name:'';

				    	$ins_1 = mysqli_query($conn, "INSERT INTO `tbl_outlet_price`(`outlet_id`, `outlet_name`, `category_id`, `product_id`, `type_id`, `product_price`, `date`, `createdate`) VALUES ('".$str_value[$i]."', '".$str_name."','".$category_id."','".$product_id."','".$type_id."','".$product_price."','".$date."','".date('Y-m-d H:i:s')."')");
				    }
				}

				echo "Success";
			}
		}

		else
		{
			echo "Error";
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SQL Upload</title>
</head>
<body>
	<form method="post">
		<input type="text" name="input_type">
		<input type="submit" name="submit" value="submit">
	</form>
</body>
</html>