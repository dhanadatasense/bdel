<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Assignproduct_model extends CI_Model {

		// Data Table
		public $tbl_assign_product         = "tbl_assign_product";
		public $tbl_assign_product_details = "tbl_assign_product_details";

		// Assign Product 
		// ***************************************************

		//Assign Product Create
		public function assignproduct_insert($data)
		{
			$this->db->insert($this->tbl_assign_product, $data);		
			return $this->db->insert_id();
		}

		//Assign Product Detail
		public function getAssignproduct($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
					$this->db->like('distributor_name',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_assign_product);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Assign Product Update
		public function assignproduct_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_assign_product, $data);
	    }

	    //Assign Product Delete
		public function assignproduct_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_assign_product, $data);
	    }

	    // Assign shop details 
		// ***************************************************

		//Assign shop details Create
		public function assignProductDetails_insert($data)
		{
			$this->db->insert($this->tbl_assign_product_details, $data);		
			return $this->db->insert_id();
		}

		//Assign shop details Create
		public function assignProductDetails_insert_batch($data)
		{
			$this->db->insert_batch($this->tbl_assign_product_details, $data);		
			return TRUE;
		}

		//Assign shop Detail
		public function getAssignProductDetails($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
		{	
			if($column !=''){
				$this->db->select($column);
			}else{
				$this->db->select('*');
			}
			if (is_array($param) && count($param)>0){
				// $this->db->where($param);		
				if(isset($param['id']))
				{
					$this->db->where('id', $param['id']);	
				}
				if(isset($param['distributor_id']))
				{
					$this->db->where('distributor_id', $param['distributor_id']);	
				}
				if(isset($param['category_id']))
				{
					$this->db->where('category_id', $param['category_id']);	
				}
				if(isset($param['category_res']))
				{
					$this->db->where_in('category_id', $param['category_res'], FALSE);
				}
				if(isset($param['product_id']))
				{
					$this->db->where('product_id', $param['product_id']);	
				}
				if(isset($param['type_id']))
				{
					$this->db->where('type_id', $param['type_id']);	
				}
				if(isset($param['zone_id']))
				{
					$this->db->where("FIND_IN_SET(".$param['zone_id'].",zone_id) !=", 0);
				}
				if(isset($param['published']))
				{
					$this->db->where('published', $param['published']);	
				}
				if(isset($param['status']))
				{
					$this->db->where('status', $param['status']);	
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
					$this->db->like('description',$like['name']);
				}
				if(isset($like['zone_id']))
				{
					$this->db->like('zone_id',$like['zone_id']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_assign_product_details);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}
		public function getAssignSubProductDetails($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
					$this->db->like('city_name',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				 $order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				 $disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_assign_product_details);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}
		public function getAssigDistributorProductDetailsRef($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
				if(isset($like['zone_id']))
				{
					$this->db->like('zone_id',$like['zone_id']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				 $order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				 $disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_assign_product_details);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Assign shop Detail
		public function getAssignProductAddtionalDetails($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
		{	
			if($column !=''){
				$this->db->select($column);
			}else{
				$this->db->select('*');
			}
			if (is_array($param) && count($param)>0){
				// $this->db->where($param);		
				if(isset($param['id']))
				{
					$this->db->where('id != ', $param['id']);	
				}
				if(isset($param['distributor_id']))
				{
					$this->db->where('distributor_id != ', $param['distributor_id']);	
				}
				if(isset($param['category_id']))
				{
					$this->db->where('category_id', $param['category_id']);	
				}
				if(isset($param['ref_id']))
				{
					$this->db->where('ref_id', $param['ref_id']);	
				}
				if(isset($param['type_id']))
				{
					$this->db->where('type_id', $param['type_id']);	
				}
				if(isset($param['zone_id']))
				{
					$this->db->where("FIND_IN_SET(".$param['zone_id'].",zone_id) !=", 0);
				}
				if(isset($param['published']))
				{
					$this->db->where('published', $param['published']);	
				}
				if(isset($param['status']))
				{
					$this->db->where('status', $param['status']);	
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
					$this->db->like('description',$like['name']);
				}
				if(isset($like['zone_id']))
				{
					$this->db->like('zone_id',$like['zone_id']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_assign_product_details);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}
		
		//Assign shop details Update
		public function assignProductDetails_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_assign_product_details, $data);
	    }
	
	    //Assign shop details Delete
		public function assignProductDetails_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_assign_product_details, $data);
	    }
	}
?>