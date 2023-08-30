<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Distributorpurchase_model extends CI_Model {

		// Data Table
		public $tbl_dis_purchase            = "tbl_dis_purchase";
		public $tbl_dis_purchase_det        = "tbl_dis_purchase_details";
		public $tbl_dis_purchase_stock_det  = "tbl_dis_purchase_stock_details";
		public $tbl_dis_purchase_return     = "tbl_dis_purchase_return";
		public $tbl_dis_purchase_return_det = "tbl_dis_purchase_return_details";

		public $tbl_dis_delivery            = "tbl_dis_delivery";
		public $tbl_dis_delivery_det        = "tbl_dis_delivery_details";
		public $tbl_dis_delivery_stock_det  = "tbl_dis_delivery_stock_details";

		// Distributor Purchase 
		// ***************************************************

		//Distributor Purchase Create
		public function distributorPurchase_insert($data)
		{
			$this->db->insert($this->tbl_dis_purchase, $data);		
			return $this->db->insert_id();
		}

		//Distributor Purchase Detail
		public function getDistributorPurchase($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
					$this->db->or_like('distributor_name',$like['name']);
					$this->db->or_like('invoice_no',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_dis_purchase);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Distributor Purchase Update
		public function distributorPurchase_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_dis_purchase, $data);
	    }

	    //Distributor Purchase Delete
		public function distributorPurchase_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_dis_purchase, $data);
	    }

	    // Distributor Purchase Details
		// ***************************************************

		//Distributor Purchase Create
		public function distributorPurchaseDetails_insert($data)
		{
			$this->db->insert($this->tbl_dis_purchase_det, $data);		
			return $this->db->insert_id();
		}

		//Distributor Purchase Detail
		public function getDistributorPurchaseDetails($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='',$groupby ='')
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
			if($groupby !=''){
				$this->db->group_by($groupby);
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_dis_purchase_det);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Distributor Purchase Update
		public function distributorPurchaseDetails_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_dis_purchase_det, $data);
	    }

	    //Distributor Purchase Delete
		public function distributorPurchaseDetails_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_dis_purchase_det, $data);
	    }

	    // Distributor Purchase Stock Details
		// ***************************************************

		//Distributor Purchase Stock Create
		public function distributorPurchaseStkDetails_insert($data)
		{
			$this->db->insert($this->tbl_dis_purchase_stock_det, $data);		
			return $this->db->insert_id();
		}

		//Distributor Purchase Stock Detail
		public function getDistributorPurchaseStkDetails($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
			$result = $this->db->get($this->tbl_dis_purchase_stock_det);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Distributor Purchase Stock Update
		public function distributorPurchaseStkDetails_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_dis_purchase_stock_det, $data);
	    }

	    //Distributor Purchase Stock Delete
		public function distributorPurchaseStkDetails_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_dis_purchase_stock_det, $data);
	    }

	    // Distributor Purchase Return 
		// ***************************************************

		//Distributor Purchase Return Create
		public function distributorPurchaseReturn_insert($data)
		{
			$this->db->insert($this->tbl_dis_purchase_return, $data);		
			return $this->db->insert_id();
		}

		//Distributor Purchase Return Detail
		public function getDistributorPurchaseReturn($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
					$this->db->like('order_no',$like['name']);
					$this->db->or_like('distributor_name',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_dis_purchase_return);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Distributor Purchase Return Update
		public function distributorPurchaseReturn_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_dis_purchase_return, $data);
	    }

	    //Distributor Purchase Return Delete
		public function distributorPurchaseReturn_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_dis_purchase_return, $data);
	    }

	    // Distributor Purchase Details
		// ***************************************************

		//Distributor Purchase Create
		public function distributorPurchaseReturnDetails_insert($data)
		{
			$this->db->insert($this->tbl_dis_purchase_return_det, $data);		
			return $this->db->insert_id();
		}

		//Distributor Purchase Detail
		public function getDistributorPurchaseReturnDetails($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='',$groupby ='')
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
			if($groupby !=''){
				$this->db->group_by($groupby);
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_dis_purchase_return_det);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Distributor Purchase Update
		public function distributorPurchaseReturnDetails_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_dis_purchase_return_det, $data);
	    }

	    //Distributor Purchase Delete
		public function distributorPurchaseReturnDetails_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_dis_purchase_return_det, $data);
	    }

	    // Distributor Delivery challan
		// ***************************************************

		//Distributor Purchase Create
		public function distributorDelivery_insert($data)
		{
			$this->db->insert($this->tbl_dis_delivery, $data);		
			return $this->db->insert_id();
		}

		//Distributor Purchase Detail
		public function getDistributorDelivery($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
					$this->db->or_like('distributor_name',$like['name']);
					$this->db->or_like('invoice_no',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_dis_delivery);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Distributor Purchase Update
		public function distributorDelivery_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_dis_delivery, $data);
	    }

	    //Distributor Purchase Delete
		public function distributorDelivery_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_dis_delivery, $data);
	    }

	    // Distributor Purchase Details
		// ***************************************************

		//Distributor Purchase Create
		public function distributorDeliveryDetails_insert($data)
		{
			$this->db->insert($this->tbl_dis_delivery_det, $data);		
			return $this->db->insert_id();
		}

		//Distributor Purchase Detail
		public function getDistributorDeliveryDetails($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='',$groupby ='')
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
			if($groupby !=''){
				$this->db->group_by($groupby);
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_dis_delivery_det);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Distributor Purchase Update
		public function distributorDeliveryDetails_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_dis_delivery_det, $data);
	    }

	    //Distributor Purchase Delete
		public function distributorDeliveryDetails_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_dis_delivery_det, $data);
	    }

	    // Distributor Purchase Stock Details
		// ***************************************************

		//Distributor Purchase Stock Create
		public function distributorDeliveryStkDetails_insert($data)
		{
			$this->db->insert($this->tbl_dis_delivery_stock_det, $data);		
			return $this->db->insert_id();
		}

		//Distributor Purchase Stock Detail
		public function getDistributorDeliveryStkDetails($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
			$result = $this->db->get($this->tbl_dis_delivery_stock_det);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Distributor Purchase Stock Update
		public function distributorDeliveryStkDetails_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_dis_delivery_stock_det, $data);
	    }

	    //Distributor Purchase Stock Delete
		public function distributorDeliveryStkDetails_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_dis_delivery_stock_det, $data);
	    }
	}
?>