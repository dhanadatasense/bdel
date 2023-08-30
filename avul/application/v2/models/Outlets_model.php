<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Outlets_model extends CI_Model {
		// Data Table
		public $tbl_outlets            = "tbl_outlets";
		public $tbl_distributor_outlet = "tbl_distributor_outlet_list";

		// Outlets Details
		// ***************************************************

		//Outlets Create
		public function outlets_insert($data)
		{
			$this->db->insert($this->tbl_outlets, $data);		
			return $this->db->insert_id();
		}

		//Outlets Create
		public function outlets_insert_batch($data)
		{
			$this->db->insert_batch($this->tbl_outlets, $data);		
			return TRUE;
		}

		//Outlets Detail
		public function getOutlets($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
			$result = $this->db->get($this->tbl_outlets);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Outlets Detail
		public function getOutletsJoin($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
		{					
			$this->db->select($column);

			$this->db->from('tbl_outlets A');
			$this->db->join('tbl_state B','B.id = A.state_id','left');
			$this->db->join('tbl_city C','C.id = A.city_id','left');
			$this->db->join('tbl_zone D','D.id = A.zone_id','left');
			$this->db->join('tbl_outlet_category E','E.id = A.outlet_category','left');

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
					$this->db->like('A.company_name',$like['name'], 'both');
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

		//Outlets Update
		public function outlets_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_outlets, $data);
	    }

	    //Outlets Delete
		public function outlets_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_outlets, $data);
	    }

	    //Outlets Detail
		public function getOutletsList($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
		{
			if($column !=''){
				$this->db->select($column);
			}else{
				$this->db->select('*');
			}
			if (is_array($param) && count($param)>0){	
				if(isset($param['distributor_id']))
				{
					$this->db->where('distributor_id', $param['distributor_id']);	
				}
				if(isset($param['date']))
				{
					$this->db->where('date', $param['date']);	
				}
				if(isset($param['start_value']))
				{
					$this->db->where('createdate >=', $param['start_value']);	
				}
				if(isset($param['end_value']))
				{
					$this->db->where('createdate <=', $param['end_value']);	
				}
				if(isset($param['published']))
				{
					$this->db->where('published', $param['published']);	
				}
				if(isset($param['status']))
				{
					$this->db->where('status', $param['status']);	
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
					$this->db->like('company_name',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by   = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_outlets);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		// Distributor Outlets Details
		// ***************************************************

		//Distributor Outlets Create
		public function distributorOutlets_insert($data)
		{
			$this->db->insert($this->tbl_distributor_outlet, $data);		
			return $this->db->insert_id();
		}

		//Distributor Outlets Detail
		public function getDistributorOutlets($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
				
				 $order_by   = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				 $disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_distributor_outlet);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Distributor Outlets Detail
		public function getDistributorOutletsJoin($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
		{					
			$this->db->select($column);

			$this->db->from('tbl_distributor_outlet_list A');
			$this->db->join('tbl_outlets B','B.id = A.outlet_id','left');
			$this->db->join('tbl_state C','C.id = B.state_id','inner');
			$this->db->join('tbl_city D','D.id = B.city_id','inner');
			$this->db->join('tbl_zone E','E.id = B.zone_id','inner');

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
					$this->db->like('A.outlet_name',$like['name']);
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

		//Distributor Outlets Update
		public function distributorOutlets_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_distributor_outlet, $data);
	    }

	    //Distributor Outlets Delete
		public function distributorOutlets_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_distributor_outlet, $data);
	    }
	}

?>