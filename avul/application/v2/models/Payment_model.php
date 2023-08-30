<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Payment_model extends CI_Model {

		// Data Table
		public $tbl_distributor_payment     = "tbl_distributor_payment";
		public $tbl_distributor_payment_det = "tbl_distributor_payment_details";
		public $tbl_outlet_payment          = "tbl_outlet_payment";
		public $tbl_outlet_payment_det      = "tbl_outlet_payment_details";
		public $tbl_vendor_payment          = "tbl_vendor_payment";
		public $tbl_vendor_payment_det      = "tbl_vendor_payment_details";

		// Distributor Payment 
		// ***************************************************

		//Distributor Payment Create
		public function distributorPayment_insert($data)
		{
			$this->db->insert($this->tbl_distributor_payment, $data);		
			return $this->db->insert_id();
		}

		//Distributor Payment Detail
		public function getDistributorPayment($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
		{					
			if($column !=''){
				$this->db->select($column);
			}else{
				$this->db->select('*');
			}
			if (is_array($param) && count($param)>0){
				$this->db->where($param);		
			}

			if (is_array($where_or) && count($where_or)>0){
				$this->db->or_where($where_or);		
			}
			
			if($limit !=0 && $offset >=0){
		       $this->db->limit($limit, $offset);
		    }
		    if(is_array($like)){
				if(isset($like['name']))
				{
					$this->db->like('po_no',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_distributor_payment);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Distributor Payment Detail
		public function getDistributorPaymentImplode($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
		{					
			if($column !=''){
				$this->db->select($column);
			}else{
				$this->db->select('*');
			}
			if (is_array($param) && count($param)>0){
				// $this->db->where($param);		

				if(isset($param['amt_type']))
				{
					$this->db->where_in('amt_type', $param['amt_type'], FALSE);
				}
				if(isset($param['bill_code']))
				{
					$this->db->where('bill_code',$param['bill_code']);
				}
				if(isset($param['distributor_id']))
				{
					$this->db->where('distributor_id',$param['distributor_id']);
				}
				if(isset($param['date']))
				{
					$this->db->where('date',$param['date']);
				}
				if(isset($param['collection_status']))
				{
					$this->db->where('collection_status',$param['collection_status']);
				}
				if(isset($param['published']))
				{
					$this->db->where('published',$param['published']);
				}
			}

			if (is_array($where_or) && count($where_or)>0){
				$this->db->or_where($where_or);		
			}
			
			if($limit !=0 && $offset >=0){
		       $this->db->limit($limit, $offset);
		    }
		    if(is_array($like)){
				if(isset($like['name']))
				{
					$this->db->like('po_no',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_distributor_payment);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Distributor Payment Update
		public function distributorPayment_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_distributor_payment, $data);
	    }

	    //Distributor Payment Delete
		public function distributorPayment_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_distributor_payment, $data);
	    }

	    // Distributor Payment Details
		// ***************************************************

		//Distributor Payment Details Create
		public function distributorPaymentDetails_insert($data)
		{
			$this->db->insert($this->tbl_distributor_payment_det, $data);		
			return $this->db->insert_id();
		}

		//Distributor Payment Details Detail
		public function getDistributorPaymentDetails($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
		{					
			if($column !=''){
				$this->db->select($column);
			}else{
				$this->db->select('*');
			}
			if (is_array($param) && count($param)>0){
				$this->db->where($param);		
			}

			if (is_array($where_or) && count($where_or)>0){
				$this->db->or_where($where_or);		
			}
			
			if($limit !=0 && $offset >=0){
		       $this->db->limit($limit, $offset);
		    }
		    if(is_array($like)){
				if(isset($like['name']))
				{
					$this->db->like('po_no',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_distributor_payment_det);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Distributor Payment Details Update
		public function distributorPaymentDetails_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_distributor_payment_det, $data);
	    }

	    //Distributor Payment Details Delete
		public function distributorPaymentDetails_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_distributor_payment_det, $data);
	    }

	    // Outlet Payment 
		// ***************************************************

		//Outlet Payment Create
		public function outletPayment_insert($data)
		{
			$this->db->insert($this->tbl_outlet_payment, $data);		
			return $this->db->insert_id();
		}

		//Outlet Payment Detail
		public function getOutletPayment($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
		{					
			if($column !=''){
				$this->db->select($column);
			}else{
				$this->db->select('*');
			}
			if (is_array($param) && count($param)>0){
				$this->db->where($param);		
			}

			if (is_array($where_or) && count($where_or)>0){
				$this->db->or_where($where_or);		
			}
			
			if($limit !=0 && $offset >=0){
		       $this->db->limit($limit, $offset);
		    }
		    if(is_array($like)){
				if(isset($like['name']))
				{
					$this->db->like('po_no',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_outlet_payment);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Outlet Payment Detail
		public function getOutletPaymentImplode($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
		{					
			if($column !=''){
				$this->db->select($column);
			}else{
				$this->db->select('*');
			}
			if (is_array($param) && count($param)>0){
				// $this->db->where($param);		

				if(isset($param['amt_type']))
				{
					$this->db->where_in('amt_type', $param['amt_type'], FALSE);
				}
				if(isset($param['bill_code']))
				{
					$this->db->where('bill_code',$param['bill_code']);
				}
				if(isset($param['date']))
				{
					$this->db->where('date',$param['date']);
				}
				if(isset($param['collection_status']))
				{
					$this->db->where('collection_status',$param['collection_status']);
				}
				if(isset($param['published']))
				{
					$this->db->where('published',$param['published']);
				}
			}

			if (is_array($where_or) && count($where_or)>0){
				$this->db->or_where($where_or);		
			}
			
			if($limit !=0 && $offset >=0){
		       $this->db->limit($limit, $offset);
		    }
		    if(is_array($like)){
				if(isset($like['name']))
				{
					$this->db->like('po_no',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_outlet_payment);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		public function getOutletPaymentJoin($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
		{					
			$this->db->select($column);
			$this->db->from('tbl_outlet_payment A');
			$this->db->join('tbl_employee B','B.id = A.employee_id','left');
			$this->db->join('tbl_outlets C','C.id = A.outlet_id','left');
			$this->db->join('tbl_zone D','D.id = C.zone_id','left');
			$this->db->join('tbl_invoice E','E.order_id = A.bill_id','left');

			if (is_array($param) && count($param)>0){
				$this->db->where($param);		
			}

			if (is_array($where_or) && count($where_or)>0){
				$this->db->or_where($where_or);		
			}
			
			if($limit !=0 && $offset >=0){
		       $this->db->limit($limit, $offset);
		    }
		    if(is_array($like)){
				if(isset($like['name']))
				{
					$this->db->like('po_no',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get();
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Outlet Payment Update
		public function outletPayment_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_outlet_payment, $data);
	    }

	    //Outlet Payment Delete
		public function outletPayment_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_outlet_payment, $data);
	    }

	    // Outlet Payment Details 
		// ***************************************************

		//Outlet Payment Details Create
		public function outletPaymentDetails_insert($data)
		{
			$this->db->insert($this->tbl_outlet_payment_det, $data);		
			return $this->db->insert_id();
		}

		//Outlet Payment Details List
		public function getOutletPaymentDetails($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
		{					
			if($column !=''){
				$this->db->select($column);
			}else{
				$this->db->select('*');
			}
			if (is_array($param) && count($param)>0){
				$this->db->where($param);		
			}

			if (is_array($where_or) && count($where_or)>0){
				$this->db->or_where($where_or);		
			}
			
			if($limit !=0 && $offset >=0){
		       $this->db->limit($limit, $offset);
		    }
		    if(is_array($like)){
				if(isset($like['name']))
				{
					$this->db->like('po_no',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_outlet_payment_det);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Outlet Payment Details List
		public function getOutletPaymentJoinDetails($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='', $where_in=array())
		{					
			$this->db->select($column); 			
			$this->db->from('tbl_outlet_payment_details A');
			$this->db->join('tbl_distributors B','B.id = A.distributor_id','left');
			$this->db->join('tbl_outlets C','C.id = A.outlet_id','inner');

			if (is_array($param) && count($param)>0){
				$this->db->where($param);		
			}

			if (is_array($where_or) && count($where_or)>0){
				$this->db->or_where($where_or);		
			}

			if (!empty($where_in)){
				$this->db->where_in('C.zone_id', $where_in, FALSE);
			}
			
			if($limit !=0 && $offset >=0){
		       $this->db->limit($limit, $offset);
		    }
		    if(is_array($like)){
				if(isset($like['name']))
				{
					$this->db->like('A.bill_no',$like['name']);
				}
				else if(isset($like['str_name']))
				{
					$this->db->like('C.company_name',$like['str_name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get();
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Outlet Payment Details Update
		public function outletPaymentDetails_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_outlet_payment_det, $data);
	    }

	    //Outlet Payment Details Delete
		public function outletPaymentDetails_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_outlet_payment_det, $data);
	    }

	    // Vendor Payment 
		// ***************************************************

		//Vendor Payment Create
		public function vendorPayment_insert($data)
		{
			$this->db->insert($this->tbl_vendor_payment, $data);		
			return $this->db->insert_id();
		}

		//Vendor Payment Detail
		public function getVendorPayment($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
		{					
			if($column !=''){
				$this->db->select($column);
			}else{
				$this->db->select('*');
			}
			if (is_array($param) && count($param)>0){
				$this->db->where($param);		
			}

			if (is_array($where_or) && count($where_or)>0){
				$this->db->or_where($where_or);		
			}
			
			if($limit !=0 && $offset >=0){
		       $this->db->limit($limit, $offset);
		    }
		    if(is_array($like)){
				if(isset($like['name']))
				{
					$this->db->like('po_no',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_vendor_payment);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Vendor Payment Detail
		public function getVendorPaymentImplode($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
		{					
			if($column !=''){
				$this->db->select($column);
			}else{
				$this->db->select('*');
			}
			if (is_array($param) && count($param)>0){
				// $this->db->where($param);		
				if(isset($param['amt_type']))
				{
					$this->db->where_in('amt_type', $param['amt_type'], FALSE);
				}
				if(isset($param['bill_code']))
				{
					$this->db->where('bill_code',$param['bill_code']);
				}
				if(isset($param['date']))
				{
					$this->db->where('date',$param['date']);
				}
				if(isset($param['collection_status']))
				{
					$this->db->where('collection_status',$param['collection_status']);
				}
				if(isset($param['published']))
				{
					$this->db->where('published',$param['published']);
				}
			}

			if (is_array($where_or) && count($where_or)>0){
				$this->db->or_where($where_or);		
			}
			
			if($limit !=0 && $offset >=0){
		       $this->db->limit($limit, $offset);
		    }
		    if(is_array($like)){
				if(isset($like['name']))
				{
					$this->db->like('po_no',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_vendor_payment);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Vendor Payment Update
		public function vendorPayment_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_vendor_payment, $data);
	    }

	    //Vendor Payment Delete
		public function vendorPayment_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_vendor_payment, $data);
	    }

	    // Vendor Payment Details
		// ***************************************************

		//Vendor Payment Details Create
		public function vendorPaymentDetails_insert($data)
		{
			$this->db->insert($this->tbl_vendor_payment_det, $data);		
			return $this->db->insert_id();
		}

		//Vendor Payment Details Detail
		public function getVendorPaymentDetails($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
		{
			if($column !=''){
				$this->db->select($column);
			}else{
				$this->db->select('*');
			}
			if (is_array($param) && count($param)>0){
				$this->db->where($param);		
			}

			if (is_array($where_or) && count($where_or)>0){
				$this->db->or_where($where_or);		
			}
			
			if($limit !=0 && $offset >=0){
		       $this->db->limit($limit, $offset);
		    }
		    if(is_array($like)){
				if(isset($like['name']))
				{
					$this->db->like('po_no',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_vendor_payment_det);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Vendor Payment Details Update
		public function vendorPaymentDetails_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_vendor_payment_det, $data);
	    }

	    //Vendor Payment Details Delete
		public function vendorPaymentDetails_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_vendor_payment_det, $data);
	    }
	}
?>