<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Return_model extends CI_Model {

		// Data Table
		public $tbl_outlet_return         = "tbl_outlet_return";
		public $tbl_outlet_return_details = "tbl_outlet_return_details";
		public $tbl_dis_return            = "tbl_dis_return";
		public $tbl_dis_return_details    = "tbl_dis_return_details";

		// Outlet Return 
		// ***************************************************

		//Outlet Return Create
		public function outletReturn_insert($data)
		{
			$this->db->insert($this->tbl_outlet_return, $data);		
			return $this->db->insert_id();
		}

		//Outlet Return Detail
		public function getOutletReturn($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='',$groupby='')
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
					$this->db->like('return_no',$like['name']);
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
			$result = $this->db->get($this->tbl_outlet_return);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Outlet Return Update
		public function outletReturn_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_outlet_return, $data);
	    }

	    //Outlet Return Delete
		public function outletReturn_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_outlet_return, $data);
	    }

	    // Outlet Return details 
		// ***************************************************

		//Outlet Return details Create
		public function outletReturnDetails_insert($data)
		{
			$this->db->insert($this->tbl_outlet_return_details, $data);		
			return $this->db->insert_id();
		}

		//Outlet Return Detail
		public function getOutletReturnDetails($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='',$groupby='')
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
			if($groupby !=''){
				$this->db->group_by($groupby);
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_outlet_return_details);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Outlet Return Detail
		public function getOutletReturnImplodeDetails($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
		{					
			if($column !=''){
				$this->db->select($column);
			}else{
				$this->db->select('*');
			}
			if (is_array($param) && count($param)>0){
				// $this->db->where($param);		
				if(isset($param['invoice_id']))
				{
					$this->db->where_in('invoice_id', $param['invoice_id'], FALSE);
				}
				if(isset($param['published']))
				{
					$this->db->where('published', $param['published']);	
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
					$this->db->like('employee_name',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_outlet_return_details);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Outlet Return details Update
		public function outletReturnDetails_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_outlet_return_details, $data);
	    }

	    //Outlet Return details Delete
		public function outletReturnDetails_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_outlet_return_details, $data);
	    }

	    // Distributor Return 
		// ***************************************************

		//Distributor Return Create
		public function distributorReturn_insert($data)
		{
			$this->db->insert($this->tbl_dis_return, $data);		
			return $this->db->insert_id();
		}

		//Distributor Return Detail
		public function getDistributorReturn($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
					$this->db->like('return_no',$like['name']);
					$this->db->or_like('distributor_name',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_dis_return);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Distributor Return Update
		public function distributorReturn_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_dis_return, $data);
	    }

	    //Distributor Return Delete
		public function distributorReturn_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_dis_return, $data);
	    }

	    // Distributor Return details 
		// ***************************************************

		//Distributor Return details Create
		public function distributorReturnDetails_insert($data)
		{
			$this->db->insert($this->tbl_dis_return_details, $data);		
			return $this->db->insert_id();
		}

		//Outlet Return Detail
		public function getDistributorReturnDetails($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='',$groupby='')
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
			$result = $this->db->get($this->tbl_dis_return_details);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Distributor Return Detail
		public function getDistributorReturnImplodeDetails($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
		{					
			if($column !=''){
				$this->db->select($column);
			}else{
				$this->db->select('*');
			}
			if (is_array($param) && count($param)>0){
				// $this->db->where($param);		
				if(isset($param['invoice_id']))
				{
					$this->db->where_in('invoice_id', $param['invoice_id'], FALSE);
				}
				if(isset($param['published']))
				{
					$this->db->where('published', $param['published']);	
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
					$this->db->like('employee_name',$like['name']);
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
			$result = $this->db->get($this->tbl_dis_return_details);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Distributor Return details Update
		public function distributorReturnDetails_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_dis_return_details, $data);
	    }

	    //Distributor Return details Delete
		public function distributorReturnDetails_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_dis_return_details, $data);
	    }
	}
?>