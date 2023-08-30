<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Invoice_model extends CI_Model {

		// Data Table
		public $tbl_invoice                 = "tbl_invoice";
		public $tbl_invoice_det             = "tbl_invoice_details";
		public $tbl_vendor_invoice          = "tbl_vendor_invoice";
		public $tbl_vendor_invoice_det      = "tbl_vendor_invoice_details";
		public $tbl_distributor_invoice     = "tbl_distributor_invoice";
		public $tbl_distributor_invoice_det = "tbl_distributor_invoice_details";

		// Invoice 
		// ***************************************************

		//Invoice Create
		public function invoice_insert($data)
		{
			$this->db->insert($this->tbl_invoice, $data);		
			return $this->db->insert_id();
		}

		//Invoice Detail
		public function getInvoice($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='', $groupby ='')
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
					$this->db->like('invoice_no',$like['name']);
					$this->db->or_like('store_name',$like['name']);
				}
			}
			if($groupby !=''){
				$this->db->group_by($groupby);
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_invoice);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Invoice Detail
		public function getInvoiceImplode($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
		{					
			if($column !=''){
				$this->db->select($column);
			}else{
				$this->db->select('*');
			}
			if (is_array($param) && count($param)>0){
				// $this->db->where($param);	

				if(isset($param['distributor_id']))
				{
					$this->db->where('distributor_id', $param['distributor_id']);	
				}
				if(isset($param['published']))
				{
					$this->db->where('published', $param['published']);	
				}
				if(isset($param['status']))
				{
					$this->db->where('status', $param['status']);	
				}
				if(isset($param['maximum_date']))
				{
					$this->db->where('delivery_date <=', $param['maximum_date']);	
				}
				if(isset($param['delivery_status']))
				{
					$this->db->where('delivery_status', $param['delivery_status']);	
				}
				if(isset($param['invoice_status']))
				{
					$this->db->where('invoice_status', $param['invoice_status']);	
				}
				if(isset($param['cancel_status']))
				{
					$this->db->where('cancel_status', $param['cancel_status']);	
				}
				if(isset($param['invoice_id']))
				{
					$this->db->where_in('id', $param['invoice_id'], FALSE);
				}
				if(isset($param['order_id']))
				{
					$this->db->where_in('order_id', $param['order_id'], FALSE);
				}
				if(isset($param['zone_id']))
				{
					$this->db->where_in('zone_id', $param['zone_id'], FALSE);
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
					$this->db->like('invoice_no',$like['name']);
					$this->db->or_like('store_name',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_invoice);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Invoice Detail
		public function getInvoiceJoin($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='', $groupby ='')
		{					
			$this->db->select($column); 			
			$this->db->from('tbl_invoice A');
			$this->db->join('tbl_order B','B.id = A.order_id','left');
			$this->db->join('tbl_city C','C.id = A.city_id','inner');
			$this->db->join('tbl_zone D','D.id = A.zone_id','inner');
			$this->db->join('tbl_invoice_details E','E.invoice_id = A.id','inner');
			$this->db->join('tbl_distributors F','F.id = A.distributor_id','inner');

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
					$this->db->like('invoice_no',$like['name']);
					$this->db->or_like('store_name',$like['name']);
				}
			}
			if($groupby !=''){
				$this->db->group_by($groupby);
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

		//Invoice Detail
		public function getInvoiceMerge($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='',$groupby ='')
		{					
			$this->db->select($column);  
			$this->db->from('tbl_invoice A');
			$this->db->join('tbl_invoice_details B', 'B.invoice_id = A.id', 'right');
			$this->db->join('tbl_product_type C', 'C.id = B.type_id', 'right');
			$this->db->join('tbl_unit D', 'D.id = C.product_unit', 'right');

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
					$this->db->like('A.invoice_no',$like['name']);
				}
			}
			if($groupby !=''){
				$this->db->group_by($groupby);
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

		//Invoice Detail
		public function getOutletInvoiceJoin($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='', $groupby ='')
		{					
			$this->db->select($column); 			
			$this->db->from('tbl_invoice A');
			$this->db->join('tbl_order B', 'B.id = A.order_id', 'left');
			$this->db->join('tbl_outlets C', 'C.id = A.store_id', 'left');
			$this->db->join('tbl_state D', 'D.id = C.state_id', 'left');
			$this->db->join('tbl_city E', 'E.id = C.city_id', 'left');

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
					$this->db->like('invoice_no',$like['name']);
					$this->db->or_like('store_name',$like['name']);
				}
			}
			if($groupby !=''){
				$this->db->group_by($groupby);
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

		//Invoice Update
		public function invoice_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_invoice, $data);
	    }

	    //Invoice Delete
		public function invoice_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_invoice, $data);
	    }

	    // Invoice Details
		// ***************************************************

		//Invoice Create
		public function invoiceDetails_insert($data)
		{
			$this->db->insert($this->tbl_invoice_det, $data);		
			return $this->db->insert_id();
		}

		//Invoice Detail
		public function getInvoiceDetails($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='',$groupby ='')
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
					$this->db->like('invoice_no',$like['name']);
				}
			}
			if($groupby !=''){
				$this->db->group_by($groupby);
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_invoice_det);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Outlet Invoice Detail
		public function getOutletInvoiceDetJoin($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='',$groupby ='')
		{					
			$this->db->select($column);  
			$this->db->from('tbl_invoice_details A');
			$this->db->join('tbl_product B', 'B.id = A.product_id', 'left');
			$this->db->join('tbl_product_type C', 'C.id = A.type_id', 'left');

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
					$this->db->like('invoice_no',$like['name']);
				}
			}
			if($groupby !=''){
				$this->db->group_by($groupby);
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

		//Invoice Detail
		public function getInvoiceDetailsJoin($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='',$groupby ='')
		{					
			$this->db->select($column);  
			$this->db->from('tbl_invoice_details A');
			$this->db->join('tbl_product_type B', 'B.id = A.type_id', 'right');
			$this->db->join('tbl_unit C', 'C.id = B.product_unit', 'right');

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
					$this->db->like('A.invoice_no',$like['name']);
				}
			}
			if($groupby !=''){
				$this->db->group_by($groupby);
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

		//Production Order Details
		public function getOutletInvoiceDetails($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='', $groupby ='')
		{
			$this->db->select($column);  
			$this->db->from('tbl_invoice_details');
			$this->db->join('tbl_invoice', 'tbl_invoice.id = tbl_invoice_details.invoice_id');

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
					$this->db->like('emp_name',$like['name']);
				}
			}
			if($groupby !=''){
				$this->db->group_by($groupby);
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

		//Invoice Update
		public function invoiceDetails_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_invoice_det, $data);
	    }

	    //Invoice Delete
		public function invoiceDetails_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_invoice_det, $data);
	    }

	    // Vendor Invoice 
		// ***************************************************

		//Vendor Invoice Create
		public function vendorInvoice_insert($data)
		{
			$this->db->insert($this->tbl_vendor_invoice, $data);		
			return $this->db->insert_id();
		}

		//Vendor Invoice Detail
		public function getVendorInvoice($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
					$this->db->like('invoice_no',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_vendor_invoice);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Vendor Invoice Update
		public function vendorInvoice_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_vendor_invoice, $data);
	    }

	    //Vendor Invoice Delete
		public function vendorInvoice_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_vendor_invoice, $data);
	    }

	    //Vendor Invoice Details
		// ***************************************************

		//Vendor Invoice Create
		public function vendorInvoiceDetails_insert($data)
		{
			$this->db->insert($this->tbl_vendor_invoice_det, $data);		
			return $this->db->insert_id();
		}

		//Vendor Invoice Detail
		public function getVendInvoiceDetails($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='',$groupby ='')
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
					$this->db->like('invoice_no',$like['name']);
				}
			}
			if($groupby !=''){
				$this->db->group_by($groupby);
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_vendor_invoice_det);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Vendor Invoice Update
		public function vendorInvoiceDetails_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_vendor_invoice_det, $data);
	    }

	    //Vendor Invoice Delete
		public function vendorInvoiceDetails_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_vendor_invoice_det, $data);
	    }


	    // Distributor Invoice 
		// ***************************************************

		//Distributor Invoice Create
		public function distributorInvoice_insert($data)
		{
			$this->db->insert($this->tbl_distributor_invoice, $data);		
			return $this->db->insert_id();
		}

		//Distributor Invoice Detail
		public function getDistributorInvoice($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
					$this->db->like('invoice_no',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_distributor_invoice);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Distributor Invoice Detail
		public function getDistributorInvoiceJoin($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
		{					
			$this->db->select($column); 			
			$this->db->from('tbl_distributor_invoice A');
			$this->db->join('tbl_distributors B', 'B.id = A.distributor_id', 'left');
			$this->db->join('tbl_dis_purchase C', 'C.id = A.order_id', 'left');
			$this->db->join('tbl_state D', 'D.id = B.state_id', 'left');

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
					$this->db->like('invoice_no',$like['name']);
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

		//Distributor Invoice Detail
		public function getDistributorInvoiceDetJoin($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
		{					
			$this->db->select($column); 			
			$this->db->from('tbl_distributor_invoice_details A');
			$this->db->join('tbl_product B', 'B.id = A.product_id', 'left');
			$this->db->join('tbl_product_type C', 'C.id = A.type_id', 'left');

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
					$this->db->like('invoice_no',$like['name']);
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

		//Distributor Invoice Update
		public function distributorInvoice_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_distributor_invoice, $data);
	    }

	    //Distributor Invoice Delete
		public function distributorInvoice_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_distributor_invoice, $data);
	    }

	    //Distributor Invoice Details
		// ***************************************************

		//Distributor Invoice Create
		public function distributorInvoiceDetails_insert($data)
		{
			$this->db->insert($this->tbl_distributor_invoice_det, $data);		
			return $this->db->insert_id();
		}

		//Distributor Invoice Detail
		public function getDistributorInvoiceDetails($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='',$groupby ='')
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
					$this->db->like('invoice_no',$like['name']);
				}
			}
			if($groupby !=''){
				$this->db->group_by($groupby);
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_distributor_invoice_det);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Distributor Invoice Update
		public function distributorInvoiceDetails_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_distributor_invoice_det, $data);
	    }

	    //Distributor Invoice Delete
		public function distributorInvoiceDetails_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_distributor_invoice_det, $data);
	    }
	}
?>