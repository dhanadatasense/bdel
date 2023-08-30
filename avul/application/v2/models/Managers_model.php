<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');

	class managers_model extends CI_Model {
		
		// Data Table
		public $tbl_employee            = "tbl_employee";
		public $tbl_posting = "tbl_posting";

		// managers Details
		// ***************************************************
		public function getAssignStateDetails($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='',$where_in='')
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
				if(isset($like['ctrl_state_id']))
				{
					$this->db->like('ctrl_state_id',$like['ctrl_state_id']);
				}
				if(isset($like['ctrl_city_id']))
				{
					$this->db->like('ctrl_city_id',$like['ctrl_city_id']);
				}
				if(isset($like['ctrl_zone_id']))
				{
					$this->db->like('ctrl_zone_id',$like['ctrl_zone_id']);
				}
			}
			
			if (is_array($where_in) && count($where_in)>0){
				if(isset($where_in['designation_code']))
				{
					$this->db->where_in('designation_code', $where_in['designation_code'], FALSE);	
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				 $order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				 $disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_posting);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}
		//managers Create
		public function managers_insert($data)
		{
			$this->db->insert($this->tbl_posting, $data);		
			return $this->db->insert_id();
		}

		//managers Detail
		public function getManagers($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
					$this->db->like('company_name',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				$order_by   = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_posting);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//managers Update
		public function managers_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_posting, $data);
	    }

	    //managers Delete
		public function managers_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_posting, $data);
	    }

		public function getManagersOverallJoin($param=array(), $limit=0, $offset=0, $option="result", $like=array(), $where_or=array(), $orderby=array(), $other =TRUE, $column='', $groupby ='', $where_in='')
		{					
			$this->db->select($column); 			
			$this->db->from('tbl_posting A');
			$this->db->join('tbl_employee B','B.id = A.employee_id','inner');
			

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
				if(isset($like['A.ctrl_city_id']))
				{
					$this->db->like('A.ctrl_city_id',$like['A.ctrl_city_id']);
				}
				if(isset($like['A.ctrl_state_id']))
				{
					$this->db->like('A.ctrl_state_id',$like['A.ctrl_state_id']);
				}
				if(isset($like['A.ctrl_zone_id']))
				{
					$this->db->like('A.ctrl_zone_id',$like['A.ctrl_zone_id']);
				}
			}

			if (is_array($where_in) && count($where_in)>0){
				if(isset($where_in['D.category_id']))
				{
					$this->db->where_in('A.employee_id', $where_in['A.employee_id'], FALSE);	
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

	 
	}

?> 