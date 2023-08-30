<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Distributors_model extends CI_Model {
		
		// Data Table
		public $tbl_distributors            = "tbl_distributors";
		public $tbl_distributor_outlet_list = "tbl_distributor_outlet_list";

		// Distributors Details
		// ***************************************************

		//Distributors Create
		public function distributors_insert($data)
		{
			$this->db->insert($this->tbl_distributors, $data);		
			return $this->db->insert_id();
		}

		//Distributors Detail
		public function getDistributors($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='',$where_in = array())
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
			if(is_array($like) && count($like) > 0){
				if(isset($like['name']))
				{
					$this->db->like('company_name',$like['name']);
					$this->db->or_like('mobile',$like['name']);
					$this->db->or_like('email',$like['name']);
					$this->db->or_like('contact_name',$like['name']);
				}
			}
			if (is_array($where_in) && count($where_in)>0){
				if(isset($where_in['state_id']))
				{
					$this->db->where_in('state_id', $where_in['state_id'], FALSE);	
				}
				if(isset($where_in['city_id']))
				{
					$this->db->where_in('city_id', $where_in['city_id'], FALSE);	
				}
				if(isset($where_in['status']))
				{
					$this->db->where_in('status', $where_in['status'], FALSE);	
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				$order_by   = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_distributors);
		
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Distributors Detail
		public function getDistributorsJoin($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
		{					
			$this->db->select($column); 			
			$this->db->from('tbl_distributors A');
			$this->db->join('tbl_state B', 'B.id = A.state_id', 'left');
			$this->db->join('tbl_city C', 'C.id = A.city_id', 'left');

			if (is_array($param) && count($param)>0){
				$this->db->where($param);		
			}

			if (is_array($where_or) && count($where_or)>0){
				$this->db->or_where($where_or);		
			}
			
			if($limit !=0 && $offset >=0){
		       $this->db->limit($limit, $offset);
		    }
			if(is_array($like) && count($like) > 0){
				if(isset($like['name']))
				{
					$this->db->like('company_name',$like['name']);
					$this->db->or_like('mobile',$like['name']);
					$this->db->or_like('email',$like['name']);
					$this->db->or_like('contact_name',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				$order_by   = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
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

		//Distributors Update
		public function distributors_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_distributors, $data);
	    }

	    //Distributors Delete
		public function distributors_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_distributors, $data);
	    }

	    // Distributor Outlet 
		// ***************************************************

		//Distributor Outlet Create
		public function distributorOutlet_insert($data)
		{
			$this->db->insert($this->tbl_distributor_outlet_list, $data);		
			return $this->db->insert_id();
		}

		//Distributor Outlet Detail
		public function getDistributorOutlet($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
			$result = $this->db->get($this->tbl_distributor_outlet_list);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Distributor Outlet Update
		public function distributorOutlet_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_distributor_outlet_list, $data);
	    }

	    //Distributor Outlet Delete
		public function distributorOutlet_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_distributor_outlet_list, $data);
	    }
	}

?>