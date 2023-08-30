<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Employee_model extends CI_Model {
		// Data Table
		public $tbl_employee = "tbl_employee";
		public $tbl_designation ="tbl_designation";

		// Employee Details
		// ***************************************************

		//Employee Create
		public function employee_insert($data)
		{
			$this->db->insert($this->tbl_employee, $data);		
			return $this->db->insert_id();
		} 

		//Employee Detail
		public function getEmployee($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='',$where_in=array())
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
			
		    if(is_array($like) && count($like)>0){
				if(isset($like['username']))
				{
				$this->db->like('username',$like['username']);
				$this->db->or_like('mobile',$like['username']);
				$this->db->or_like('designation_code',$like['username']);
				}
			}
			if (is_array($where_in) && count($where_in)>0){
				if(isset($where_in['id']))
				{
					$this->db->where_in('id', $where_in['id'], FALSE);	
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				$order_by   = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_employee);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}


		//Employee Update
		public function employee_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_employee, $data);
	    }

	    //Employee Delete
		public function employee_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_employee, $data);
	    }
				//designation Create
				public function designation_insert($data)
				{
					$this->db->insert($this->tbl_designation, $data);		
					return $this->db->insert_id();
				}
		
				//designation Detail
				public function getdesignation($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='',$where_in=array())
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
					if(is_array($like) && count($like)>0){
						if(isset($like['name']))
						{
						$this->db->like('designation_name',$like['name']);
						$this->db->or_like('designation_code',$like['name']);
						$this->db->or_like('position_id',$like['name']);
						}
					}
					if (is_array($where_in) && count($where_in)>0){
						if(isset($where_in['designation_code']))
						{
							$this->db->where_in('designation_code', $where_in['designation_code'], FALSE);	
						}
					}
					if(is_array($orderby) && count($orderby) > 0){
						$order_by   = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
						$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
						$this->db->order_by($order_by, $disp_order); 
					}	
					$result = $this->db->get($this->tbl_designation);
					
					if ($result != FALSE && $result->num_rows()>0){
		
						$result =  $result->$option();
						
						$aResponse = $result;
						return $aResponse;
					}
					return FALSE;
				}
		
		
				//designation Update
				public function designation_update($data, $where = array())
				{
					if (count($where) > 0)
						$this->db->where($where);
					return $this->db->update($this->tbl_designation, $data);
				}
		
				//designation Delete
				public function designation_delete($data, $where = array())
				{
					if (count($where) > 0)
						$this->db->where($where);
					return $this->db->update($this->tbl_designation, $data);
				}
	}

?>