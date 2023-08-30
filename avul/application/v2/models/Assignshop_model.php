<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Assignshop_model extends CI_Model {

		// Data Table
		public $tbl_assign_shop         = "tbl_assign_shop";
		public $tbl_assign_shop_details = "tbl_assign_shop_details";

		// Assign shop 
		// ***************************************************

		//Assign shop Create
		public function assignshop_insert($data)
		{
			$this->db->insert($this->tbl_assign_shop, $data);		
			return $this->db->insert_id();
		}

		//Assign shop Detail
		public function getAssignshop($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='',$where_in=array())
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
					$this->db->like('employee_name',$like['name']);
				}
			}
			if (is_array($where_in) && count($where_in)>0){
				if(isset($where_in['employee_id']))
				{
					$this->db->where_in('employee_id', $where_in['employee_id'], FALSE);	
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_assign_shop);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Assign shop Update
		public function assignshop_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_assign_shop, $data);
	    }

	    //Assign shop Delete
		public function assignshop_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_assign_shop, $data);
	    }

	    // Assign shop details 
		// ***************************************************

		//Assign shop details Create
		public function assignshopDetails_insert($data)
		{
			$this->db->insert($this->tbl_assign_shop_details, $data);		
			return $this->db->insert_id();
		}

		//Assign shop Detail
		public function getAssignshopDetails($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
					$this->db->like('employee_name',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_assign_shop_details);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Assign shop details Update
		public function assignshopDetails_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_assign_shop_details, $data);
	    }

	    //Assign shop details Delete
		public function assignshopDetails_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_assign_shop_details, $data);
	    }
	}
?>