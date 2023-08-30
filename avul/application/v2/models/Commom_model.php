<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Commom_model extends CI_Model {
		// Data Table
		public $tbl_financial       = "tbl_financial";
		public $tbl_state           = "tbl_state";
		public $tbl_city            = "tbl_city";
		public $tbl_zone            = "tbl_zone";
		public $tbl_unit            = "tbl_unit";
		public $tbl_privilege       = "tbl_privilege";
		public $tbl_month           = "tbl_month";
		public $tbl_variation       = "tbl_variation";
		public $tbl_message         = "tbl_message";
		public $tbl_year            = "tbl_year";
		public $tbl_expenses        = "tbl_expenses";
		public $tbl_log             = "tbl_log";
		public $tbl_expenses_entry  = "tbl_expenses_entry";
		public $tbl_outlet_category = "tbl_outlet_category";
		public $tbl_pincode_wise_data = 'tbl_pincode_wise_data';
		public $tbl_sub_category = 'tbl_sub_category';

		public $tbl_category       = "tbl_category";
		public $tbl_product        = "tbl_product";
		public $tbl_product_type   = "tbl_product_type";

		// Financial Details
		// ***************************************************

		//Financial Create
		public function financial_insert($data)
		{
			$this->db->insert($this->tbl_financial, $data);		
			return $this->db->insert_id();
		}

		//Financial Detail
		public function getFinancial($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
					$this->db->like('name',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				 $order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				 $disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_financial);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}


		//Financial Update
		public function financial_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_financial, $data);
	    }

	    //Financial Delete
		public function financial_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_financial, $data);
	    }

		// State Details
		// ***************************************************

		//State Create
		public function state_insert($data)
		{
			$this->db->insert($this->tbl_state, $data);		
			return $this->db->insert_id();
		}

		//State Detail
		public function getState($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='',$where_in=array())
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
					$this->db->like('state_name',$like['name']);
				}
			}
			if (is_array($where_in) && count($where_in)>0){
				if(isset($where_in['id']))
				{
					$this->db->where_in('id', $where_in['id'], FALSE);	
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				 $order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				 $disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_state);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		public function getStateSecond($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='',$where_in=array())
		{			
						
			if($column !=''){
				$this->db->select($column);
			}else{
				$this->db->select('*');
			}
		    
			if (is_array($where_in) && count($where_in)>0){
				
				if(isset($where_in['ctrl_state_id']))
				{
					$this->db->where_in('ctrl_state_id', $where_in['ctrl_state_id'], FALSE);
				}
				if(isset($where_in['id']))
				{
					$this->db->where_in('id', $where_in['id'], FALSE);	
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
					$this->db->like('name',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				 $order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				 $disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_state);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}
		//State Update
		public function state_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_state, $data);
	    }

	    //State Delete
		public function state_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_state, $data);
	    }

	    // city Details
		// ***************************************************

		//city Create
		public function city_insert($data)
		{
			$this->db->insert($this->tbl_city, $data);		
			return $this->db->insert_id();
		}

		//city Detail
		public function getCity($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='',$where_in=array())
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

			if (is_array($where_in) && count($where_in)>0){
				if(isset($where_in['id']))
				{
					$this->db->where_in('id', $where_in['id'], FALSE);	
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				 $order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				 $disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_city);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//city Update
		public function city_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_city, $data);
	    }

	    //city Delete
		public function city_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_city, $data);
	    }


	    // Zone Details
		// ***************************************************

		//Zone Create
		public function zone_insert($data)
		{
			$this->db->insert($this->tbl_zone, $data);		
			return $this->db->insert_id();
		}

		//Zone Create
		public function zone_insert_batch($data)
		{
			$this->db->insert_batch($this->tbl_zone, $data);		
			return TRUE;
		}

		//Zone Detail
		public function getZone($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='',$where_in=array())
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
					$this->db->like('name',$like['name']);
				}
			}
			if (is_array($where_in) && count($where_in)>0){
				if(isset($where_in['id']))
				{
					$this->db->where_in('id', $where_in['id'], FALSE);	
				}
				if(isset($where_in['city_id']))
				{
					$this->db->where_in('city_id', $where_in['city_id'], FALSE);	
				}
				if(isset($where_in['state_id']))
				{
					$this->db->where_in('state_id', $where_in['state_id'], FALSE);	
				}
			}
			
			if(is_array($orderby) && count($orderby) > 0){
				
				 $order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				 $disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_zone);
		
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Zone Detail
		public function getZoneJoin($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='',$where_in=array())
		{			
					
			$this->db->select($column);	
				
			$this->db->from('tbl_zone A');
			$this->db->join('tbl_state B','B.id = A.state_id','left');

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
					$this->db->like('name',$like['name']);
				}
			}
			if (is_array($where_in) && count($where_in)>0){
				if(isset($where_in['id']))
				{
					$this->db->where_in('id', $where_in['id'], FALSE);	
				}
				if(isset($where_in['city_id']))
				{
					$this->db->where_in('city_id', $where_in['city_id'], FALSE);	
				}
				if(isset($where_in['state_id']))
				{
					$this->db->where_in('state_id', $where_in['state_id'], FALSE);	
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


		//sathish
        //Zone Detail
		public function getZoneSecond($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='',$where_in=array())
		{			
						
			if($column !=''){
				$this->db->select($column);
			}else{
				$this->db->select('*');
			}
		    
			if (is_array($param) && count($param)>0){
				
				if(isset($param['city_id']))
				{
					$this->db->where_in('city_id', $param['city_id'], FALSE);
				}
				if(isset($param['id']))
				{
					$this->db->where_in('id', $param['id'], FALSE);
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
					$this->db->like('name',$like['name']);
				}
			}
			if (is_array($where_in) && count($where_in)>0){
				if(isset($where_in['id']))
				{
					$this->db->where_in('id', $where_in['id'], FALSE);	
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				 $order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				 $disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_zone);
		
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		// //getzone 2
		// public function getZoneSecond($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='',$where_in=array())
		// {			
						
		// 	if($column !=''){
		// 		$this->db->select($column);
		// 	}else{
		// 		$this->db->select('*');
		// 	}
		    
		// 	if (is_array($param) && count($param)>0){
				
		// 		if(isset($param['city_id']))
		// 		{
		// 			$this->db->where_in('city_id', $param['city_id'], FALSE);
		// 		}
		// 		if(isset($param['id']))
		// 		{
		// 			$this->db->where_in('id', $param['id'], FALSE);
		// 		}	
					
		// 	}
			
		// 	if (is_array($where_or) && count($where_or)>0){
		// 		$this->db->or_where($where_or);		
		// 	}
			
		// 	if($limit !=0 && $offset >=0){
		//        $this->db->limit($limit, $offset);
		//     }
		//     if(is_array($like)){
		// 		if(isset($like['name']))
		// 		{
		// 			$this->db->like('name',$like['name']);
		// 		}
		// 	}
		// 	if(is_array($orderby) && count($orderby) > 0){
				
		// 		 $order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
		// 		 $disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
		// 		$this->db->order_by($order_by, $disp_order); 
		// 	}	
		// 	$result = $this->db->get($this->tbl_zone);
			
		// 	if ($result != FALSE && $result->num_rows()>0){

		// 		$result =  $result->$option();
				
		// 		$aResponse = $result;
		// 		return $aResponse;
		// 	}
		// 	return FALSE;
		// }
		// Zone Details Implode
		public function getZoneImplode($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
		{			
						
			if($column !=''){
				$this->db->select($column);
			}else{
				$this->db->select('*');
			}
			if (is_array($param) && count($param)>0){
				// $this->db->where($param);
				if(isset($param['zone_id']))
				{
					$this->db->where_in('id', $param['zone_id'], FALSE);
				}	
				if(isset($param['state_id']))
				{
					$this->db->where('state_id', $param['state_id']);	
				}
				if(isset($param['city_id']))
				{
					$this->db->where_in('city_id', $param['city_id'], FALSE);
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
					$this->db->like('name',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				 $order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				 $disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_zone);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Distributor Zone List
		public function getDistributoeZone($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
					$this->db->where_in('id', $param['id'], FALSE);
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
					$this->db->like('name',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				 $order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				 $disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_zone);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Zone Update
		public function zone_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_zone, $data);
	    }

	    //Zone Delete
		public function zone_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_zone, $data);
	    }

	    // Unit Details
		// ***************************************************

		//Unit Create
		public function unit_insert($data)
		{
			$this->db->insert($this->tbl_unit, $data);		
			return $this->db->insert_id();
		}

		//Unit Detail
		public function getUnit($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
					$this->db->like('name',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				 $order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				 $disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_unit);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Unit Update
		public function unit_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_unit, $data);
	    }

	    //Unit Delete
		public function unit_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_unit, $data);
	    }

	    // Privilege Details
		// ***************************************************

		//Privilege Create
		public function privilege_insert($data)
		{
			$this->db->insert($this->tbl_privilege, $data);		
			return $this->db->insert_id();
		}

		//Privilege Detail
		public function getPrivilege($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
					$this->db->like('name',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				 $order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				 $disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_privilege);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Privilege Update
		public function privilege_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_privilege, $data);
	    }

	    //Privilege Delete
		public function privilege_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_privilege, $data);
	    }

	    // Variation Details
		// ***************************************************

		//Variation Create
		public function variation_insert($data)
		{
			$this->db->insert($this->tbl_variation, $data);		
			return $this->db->insert_id();
		}

		//Variation Detail
		public function getVariation($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
					$this->db->like('name',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				 $order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				 $disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_variation);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Variation Update
		public function variation_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_variation, $data);
	    }

	    //Variation Delete
		public function variation_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_variation, $data);
	    }

	    // Month Details
	    // ***************************************************

	    // Month Details
	    //Financial Detail
		public function getMonth($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
					$this->db->like('month_name',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				 $order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				 $disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_month);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		// Message Template Details
		// ***************************************************

		//Message Create
		public function message_insert($data)
		{
			$this->db->insert($this->tbl_message, $data);		
			return $this->db->insert_id();
		}

		//Message Detail
		public function getMessage($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
					$this->db->like('message',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				 $order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				 $disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_message);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Message Update
		public function message_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_message, $data);
	    }

	    //Message Delete
		public function message_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_message, $data);
	    }

	    // Year Details
		// ***************************************************

		//Year Create
		public function year_insert($data)
		{
			$this->db->insert($this->tbl_year, $data);		
			return $this->db->insert_id();
		}

		//Year Detail
		public function getYear($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
					$this->db->like('name',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				 $order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				 $disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_year);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		// Expenses Details
		// ***************************************************

		//Expenses Create
		public function expenses_insert($data)
		{
			$this->db->insert($this->tbl_expenses, $data);		
			return $this->db->insert_id();
		}

		//Expenses Detail
		public function getExpenses($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
					$this->db->like('name',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				 $order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				 $disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_expenses);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}


		//Expenses Update
		public function expenses_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_expenses, $data);
	    }

	    //Expenses Delete
		public function expenses_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_expenses, $data);
	    }

	    // Log Details
		// ***************************************************

		//Log Create
		public function log_insert($data)
		{
			$this->db->insert($this->tbl_log, $data);		
			return $this->db->insert_id();
		}

		//Log Detail
		public function getLog($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
					$this->db->like('name',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				 $order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				 $disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_log);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}


		//Log Update
		public function log_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_log, $data);
	    }

	    //Log Delete
		public function log_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_log, $data);
	    }

	    // Expenses Entry Details
		// ***************************************************

	    public function expensesEntry_join($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
		{			
			$this->db->select($column); 			
			$this->db->from('tbl_expenses_entry A');
			$this->db->join('tbl_expenses B','B.id = A.expense_id','left');
			$this->db->join('tbl_employee C','C.id = A.employee_id','left');

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
					$this->db->or_like('B.name',$like['name']);
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

		//Expenses Entry Create
		public function expensesEntry_insert($data)
		{
			$this->db->insert($this->tbl_expenses_entry, $data);		
			return $this->db->insert_id();
		}

		//Expenses Entry Detail
		public function getExpensesEntry($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
					$this->db->like('name',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				 $order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				 $disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_expenses_entry);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}


		//Expenses Entry Update
		public function expensesEntry_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_expenses_entry, $data);
	    }

	    //Expenses Entry Delete
		public function expensesEntry_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_expenses_entry, $data);
	    }

	    // Outlet Category Details
		// ***************************************************

		//Outlet Category Create
		public function outletCategory_insert($data)
		{
			$this->db->insert($this->tbl_outlet_category, $data);		
			return $this->db->insert_id();
		}

		//Outlet Category Detail
		public function getOutletCategory($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
					$this->db->like('name',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				 $order_by   = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				 $disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_outlet_category);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}


		//Outlet Category Update
		public function outletCategory_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_outlet_category, $data);
	    }

	    //Outlet Category Delete
		public function outletCategory_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_outlet_category, $data);
	    }

		// sub Category Details
		// ***************************************************

		//sub Category Create
		public function sub_category_insert($data)
		{
			$this->db->insert($this->tbl_sub_category, $data);		
			return $this->db->insert_id();
		}

		//sub Category Detail
		public function getSubCategory($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='',$where_in=array())
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
					$this->db->like('name',$like['name']);
				}
			}
			if (is_array($where_in) && count($where_in)>0){
				if(isset($where_in['id']))
				{
					$this->db->where_in('id', $where_in['id'], FALSE);	
				}
				if(isset($where_in['category_id']))
				{
					$this->db->where_in('category_id', $where_in['category_id'], FALSE);	
				}
				if(isset($where_in['state_id']))
				{
					$this->db->where_in('state_id', $where_in['state_id'], FALSE);	
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				 $order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				 $disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_sub_category);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//sub Category Detail
		public function getSubCategoryImplode($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
		{			
						 
			if($column !=''){
				$this->db->select($column);
			}else{
				$this->db->select('*');
			}
			if (is_array($param) && count($param)>0){
				if(isset($param['id']))
				{
					$this->db->where_in('id', $param['id'], FALSE);
				}				
				if(isset($param['category_id']))
				{
					$this->db->where_in('category_id', $param['category_id'], FALSE);
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
					$this->db->like('name',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				 $order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				 $disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_sub_category);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//sub Category Update
		public function sub_category_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_sub_category, $data);
	    }

	    //sub Category Delete
		public function sub_category_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_sub_category, $data);
	    }

	    // Category Details
		// ***************************************************

		//Category Create
		public function category_insert($data)
		{
			$this->db->insert($this->tbl_category, $data);		
			return $this->db->insert_id();
		}

		//Category Detail
		public function getCategory($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
					$this->db->like('name',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				 $order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				 $disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_category);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Category Detail
		public function getCategoryImplode($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
		{			
						
			if($column !=''){
				$this->db->select($column);
			}else{
				$this->db->select('*');
			}
			if (is_array($param) && count($param)>0){				
				if(isset($param['category_id']))
				{
					$this->db->where_in('id', $param['category_id'], FALSE);
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
					$this->db->like('name',$like['name']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				 $order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				 $disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_category);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Category Update
		public function category_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_category, $data);
	    }

	    //Category Delete
		public function category_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_category, $data);
	    }

	    // Product Details
		// ***************************************************

		//Product Create
		public function product_insert($data)
		{
			$this->db->insert($this->tbl_product, $data);		
			return $this->db->insert_id();
		}

		//Product Detail
		public function getProduct($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
					$this->db->like('name',$like['name']);
				}
				// if(isset($like['hsn_code']))
				// {
				// 	$this->db->like('hsn_code',$like['hsn_code']);
				// }
				// if(isset($like['price']))
				// {
				// 	$this->db->like('price',$like['price']);
				// }
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				 $order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				 $disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_product);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Product Detail
		public function getProductJoin($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='',$where_in=array())
		{			
						
			$this->db->select($column);

			$this->db->from('tbl_product A');
			$this->db->join('tbl_category B','B.id = A.category_id','left');
			$this->db->join('tbl_sub_category C','C.id = A.sub_cat_id','left');

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
					$this->db->like('name',$like['name']);
				}
			}
			if (is_array($where_in) && count($where_in)>0){
				if(isset($where_in['category_id']))
				{
					$this->db->where_in('A.category_id', $where_in['category_id'], FALSE);	
				}
				if(isset($where_in['sub_cat_id']))
				{
					$this->db->where_in('A.sub_cat_id', $where_in['sub_cat_id'], FALSE);	
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

		//Product Update
		public function product_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_product, $data);
	    }

	    //Product Delete
		public function product_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_product, $data);
	    }

	    // Product Type Details
		// ***************************************************

		//Product Type Create
		public function productType_insert($data)
		{
			$this->db->insert($this->tbl_product_type, $data);		
			return $this->db->insert_id();
		}

		//Product Type Detail
		public function getProductType($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='',$where_in = array())
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
			if (is_array($where_in) && count($where_in)>0){
				if(isset($where_in['id']))
				{
					$this->db->where_in('id', $where_in['id'], FALSE);	
				}
				
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_product_type);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Product Type Detail implode
		public function getProductTypeImplode($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
		{			
						
			if($column !=''){
				$this->db->select($column);
			}else{
				$this->db->select('*');
			}
			if (is_array($param) && count($param)>0){
				if(isset($param['vendor_id']))
				{
					$this->db->where('vendor_id', $param['vendor_id']);	
				}
				if(isset($param['category_id']))
				{
					$this->db->where_in('category_id', $param['category_id'], FALSE);
				}
				if(isset($param['sub_cat_id']))
				{
					$this->db->where_in('sub_cat_id', $param['sub_cat_id'], FALSE);
				}
				if(isset($param['type_id']))
				{
					$this->db->where_in('id', $param['type_id'], FALSE);
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

			if(is_array($like)){
				if(isset($like['name']))
				{
					$this->db->like('description',$like['name']);
				}
			}
			
			if($limit !=0 && $offset >=0){
		       $this->db->limit($limit, $offset);
		    }
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_product_type);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Product Type Detail
		public function getProductTypeJoin($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='',$where_in = array())
		{			
						
			$this->db->select($column);  
			$this->db->from('tbl_product_type A');
			$this->db->join('tbl_unit B', 'B.id = A.product_unit', 'right');

			if (is_array($param) && count($param)>0){
				
				$this->db->where($param);
			}

			if (is_array($where_or) && count($where_or)>0){
				$this->db->or_where($where_or);		
			}

			if(is_array($like)){
				if(isset($like['name']))
				{
					$this->db->like('A.description',$like['name'], 'both');
				}
			}
			
			if($limit !=0 && $offset >=0){
		       $this->db->limit($limit, $offset);
		    }
			if (is_array($where_in) && count($where_in)>0){
				if(isset($where_in['A.category_id']))
				{
					$this->db->where_in('A.category_id', $where_in['A.category_id'], FALSE);	
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

		//Product Type Update
		public function productType_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_product_type, $data);
	    }

	    //Product Type Delete
		public function productType_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_product_type, $data);
	    }

		public function getStateDistric($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
				if(isset($like['code']))
				{
					$this->db->like('c_pincode',$like['code']);
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				 $order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				 $disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_pincode_wise_data);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

	}
?>