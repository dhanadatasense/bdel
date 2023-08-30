<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Loyalty_model extends CI_Model {

		// Data Table
		public $tbl_product_loyalty         = "tbl_product_loyalty";
		public $tbl_product_loyalty_details = "tbl_product_loyalty_details";
		public $tbl_order_loyalty           = "tbl_order_loyalty";

		// ProductLoyalty 
		// ***************************************************

		//Product Loyalty Create
		public function productLoyalty_insert($data)
		{
			$this->db->insert($this->tbl_product_loyalty, $data);		
			return $this->db->insert_id();
		}

		//Product Loyalty Detail
		public function getProductLoyalty($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
					$this->db->like('description',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_product_loyalty);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Product Loyalty Detail
		public function getProductLoyaltyMerge($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
		    	$this->db->group_start();
				if(!empty($like['start_val']) && !empty($like['end_val']))
				{
					$this->db->like('loyalty_date',$like['start_val']);
					$this->db->or_like('loyalty_date',$like['end_val']);
				}
				$this->db->group_end();
			}

			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_product_loyalty);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Product Loyalty Update
		public function productLoyalty_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_product_loyalty, $data);
	    }

	    //Product Loyalty Delete
		public function productLoyalty_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_product_loyalty, $data);
	    }

	    //Product Loyalty Create
		public function productLoyaltyDetails_insert($data)
		{
			$this->db->insert($this->tbl_product_loyalty_details, $data);		
			return $this->db->insert_id();
		}

		//Product Loyalty Detail
		public function getProductLoyaltyDetails($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='', $between_val =array())
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

			if(is_array($between_val)){
				if(isset($between_val['column_1']))
				{
					$this->db->where($between_val['column_1']." BETWEEN  '".$between_val['start_val']."'  AND '".$between_val['end_val']."'");
				}
				if(isset($between_val['column_2']))
				{
					$this->db->or_where($between_val['column_2']." BETWEEN  '".$between_val['start_val']."'  AND '".$between_val['end_val']."'");
				}
			}

			if($limit !=0 && $offset >=0){
		       $this->db->limit($limit, $offset);
		    }

		    if(is_array($like)){
				if(!empty($like['start_val']) && !empty($like['end_val']))
				{
					$this->db->like('loyalty_date',$like['start_val']);
					$this->db->or_like('loyalty_date',$like['end_val']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_product_loyalty_details);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Product Loyalty Detail
		public function getProductLoyaltyDetailsJoin($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
		{
			$this->db->select($column); 			
			$this->db->from('tbl_product_loyalty_details A');
			$this->db->join('tbl_product_type B','B.id = A.type_id','left');

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
					$this->db->like('outlet_name',$like['name']);
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

		//Product Loyalty Update
		public function productLoyaltyDetails_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_product_loyalty_details, $data);
	    }

	    //Product Loyalty Delete
		public function productLoyaltyDetails_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_product_loyalty_details, $data);
	    }

	    //Outlet Loyalty Create
		public function outletLoyalty_insert($data)
		{
			$this->db->insert($this->tbl_order_loyalty, $data);		
			return $this->db->insert_id();
		}

		//Outlet Loyalty Detail
		public function getOutletLoyalty($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
					$this->db->like('outlet_name',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_order_loyalty);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Outlet Loyalty Update
		public function outletLoyalty_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_order_loyalty, $data);
	    }

	    //Outlet Loyalty Delete
		public function outletLoyalty_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_order_loyalty, $data);
	    }
	}

?>
