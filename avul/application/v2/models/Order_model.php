<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Order_model extends CI_Model {

		// Data Table
		public $tbl_order           = "tbl_order";
		public $tbl_order_det       = "tbl_order_details";
		public $tbl_order_stock_det = "tbl_order_stock_details";
		public $tbl_zone = "tbl_zone";
		
		// Order 
		// ***************************************************

		//Order Create
		public function order_insert($data)
		{
			$this->db->insert($this->tbl_order, $data);		
			return $this->db->insert_id();
		}
		
		//Order Detail
		public function getOrder($param=array(), $limit=0, $offset=0, $option="result", $like=array(), $where_or=array(), $orderby=array(), $other =TRUE, $column='', $groupby ='')
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
					$this->db->or_like('store_name',$like['name']);
					$this->db->or_like('emp_name',$like['name']);
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
			$result = $this->db->get($this->tbl_order);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Order Detail
		public function getOrderMerge($param=array(), $limit=0, $offset=0, $option="result", $like=array(), $where_or=array(), $orderby=array(), $other =TRUE, $column='', $groupby ='')
		{					
			$this->db->select($column);

			$this->db->from('tbl_order A');
			$this->db->join('tbl_order_details B','B.order_id = A.id','left');
			$this->db->join('tbl_employee C','C.id = A.emp_id','left');
			$this->db->join('tbl_outlets D','D.id = A.store_id','left');
			$this->db->join('tbl_outlet_category E','E.id = D.outlet_category','left');

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
					$this->db->like('A.order_no',$like['name'], 'both');
					$this->db->or_like('A.store_name',$like['name'], 'both');
					$this->db->or_like('C.first_name',$like['name'], 'both');
					$this->db->or_like('C.last_name',$like['name'], 'both');
					$this->db->or_like('E.name',$like['name'], 'both');
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

		public function getOrderJoinbyZone($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='', $groupby ='')
		{					
			$this->db->select($column); 			
			$this->db->from('tbl_order A');
			$this->db->join('tbl_zone B','B.id = A.zone_id','inner');
			
			
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
					$this->db->like('invoice_no',$like['name']);
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
			$result = $this->db->get();
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Order Detail
		public function getOrderPostingJoin($param=array(), $limit=0, $offset=0, $option="result", $like=array(), $where_or=array(), $orderby=array(), $other =TRUE, $column='', $groupby ='', $where_in=array())
		{					
			$this->db->select($column); 			
			$this->db->from('tbl_order A');
			$this->db->join('tbl_zone B','B.id = A.zone_id','inner');
			

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
					$this->db->like('A.order_no',$like['name']);
					$this->db->or_like('A.store_name',$like['name']);
					$this->db->or_like('A.emp_name',$like['name']);
				}
			}

			if (is_array($where_in) && count($where_in)>0){
				if(isset($where_in['B.state_id']))
				{
					$this->db->where_in('B.state_id', $where_in['B.state_id'], FALSE);	
						
				}
				if(isset($where_in['B.id']))
				{
					$this->db->where_in('B.id', $where_in['B.id'], FALSE);
				}
				if(isset($where_in['B.city_id']))
				{
					$this->db->where_in('B.city_id', $where_in['B.city_id'], FALSE);
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

		public function getOrderDetailsJoinbyZone($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='', $groupby ='')
		{					
			$this->db->select($column); 			
			$this->db->from('tbl_order A');
			$this->db->join('tbl_zone B','B.id = A.zone_id','inner');
			$this->db->join('tbl_order_details C','C.order_id = A.id','inner');
			
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
					$this->db->like('invoice_no',$like['name']);
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
			$result = $this->db->get();
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Order Detail
		public function getOrderJoin($param=array(), $limit=0, $offset=0, $option="result", $like=array(), $where_or=array(), $orderby=array(), $other =TRUE, $column='', $groupby ='')
		{					
			$this->db->select($column); 			
			$this->db->from('tbl_order A');
			$this->db->join('tbl_order_details B','B.order_id = A.id','left');

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
					$this->db->like('A.order_no',$like['name']);
					$this->db->or_like('A.store_name',$like['name']);
					$this->db->or_like('A.emp_name',$like['name']);
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

		//Order Detail
		public function getOrderOverallJoin($param=array(), $limit=0, $offset=0, $option="result", $like=array(), $where_or=array(), $orderby=array(), $other =TRUE, $column='', $groupby ='', $where_in='')
		{					
			$this->db->select($column); 			
			$this->db->from('tbl_order A');
			$this->db->join('tbl_order_details B','B.order_id = A.id','left');
			$this->db->join('tbl_product C','C.id = B.product_id','inner');
			$this->db->join('tbl_product_type D','D.id = B.type_id','inner');
			$this->db->join('tbl_outlets E','E.id = A.store_id','inner');
			$this->db->join('tbl_state F','F.id = E.state_id','inner');
			$this->db->join('tbl_city G','G.id = E.city_id','inner');
			$this->db->join('tbl_zone H','H.id = E.zone_id','inner');

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
					$this->db->like('A.order_no',$like['name']);
					$this->db->or_like('A.store_name',$like['name']);
					$this->db->or_like('A.emp_name',$like['name']);
				}
			}

			if (is_array($where_in) && count($where_in)>0){
				if(isset($where_in['D.category_id']))
				{
					$this->db->where_in('D.category_id', $where_in['D.category_id'], FALSE);	
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


		//Order Detail
		public function getEmployeeOrder($param=array(), $limit=0, $offset=0, $option="result", $like=array(), $where_or=array(), $orderby=array(), $other =TRUE, $column='', $groupby ='')
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

		    if(isset($param['emp_id']))
		    {
		    	$this->db->having(array('emp_id' => $param['emp_id']));
		    }

		    if(is_array($like)){
				if(isset($like['name']))
				{
					$this->db->like('order_no',$like['name']);
					$this->db->or_like('store_name',$like['name']);
					$this->db->or_like('emp_name',$like['name']);
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
			$result = $this->db->get($this->tbl_order);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Order Update
		public function order_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_order, $data);
	    }

	    //Order Delete
		public function order_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_order, $data);
	    }

	    // Order Details 
		// ***************************************************

		//Order Details Create
		public function orderDetails_insert($data)
		{
			$this->db->insert($this->tbl_order_det, $data);		
			return $this->db->insert_id();
		}

		//Order Details Detail
		public function getOrderDetails($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='', $groupby ='',$where_in=array())
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
					$this->db->like('emp_name',$like['name']);
				}
			}
			if($groupby !=''){
				$this->db->group_by($groupby);
			}
			if (is_array($where_in) && count($where_in)>0){
				if(isset($where_in['zone_id']))
				{
					$this->db->where_in('zone_id', $where_in['zone_id'], FALSE);	
				}
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_order_det);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Order Details Detail
		public function getOrderDetailsJoin($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='', $groupby ='')
		{					
			$this->db->select($column); 			
			$this->db->from('tbl_order_details A');
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
					$this->db->like('emp_name',$like['name']);
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

		//Order Details List
		public function getOrderListDetails($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='', $groupby ='')
		{					
			if($column !=''){
				$this->db->select($column);
			}else{
				$this->db->select('*');
			}
			if (is_array($param) && count($param)>0){
				if(isset($param['published']))
				{
					$this->db->where('published', $param['published']);	
				}
				if(isset($param['status']))
				{
					$this->db->where('status', $param['status']);	
				}
				if(isset($param['order_id']))
				{
					$this->db->where_in('order_id', $param['order_id'], FALSE);
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
					$this->db->like('emp_name',$like['name']);
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
			$result = $this->db->get($this->tbl_order_det);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Production Order Details
		public function getProductionOrderDetails($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='', $groupby ='')
		{					
			$this->db->select($column);  
			$this->db->from('tbl_order_details');
			$this->db->join('tbl_order', 'tbl_order.id = tbl_order_details.order_id');

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
					$this->db->like('emp_name',$like['name']);
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

		//Order Details Update
		public function orderDetails_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_order_det, $data);
	    }

	    //Order Details Delete
		public function orderDetails_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_order_det, $data);
	    }

	    // Order Stock Details 
		// ***************************************************

		//Order Stock Details Create
		public function orderStockDetails_insert($data)
		{
			$this->db->insert($this->tbl_order_stock_det, $data);		
			return $this->db->insert_id();
		}

		//Order Stock Details Detail
		public function getOrderStockDetails($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='', $groupby ='')
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
					$this->db->like('emp_name',$like['name']);
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
			$result = $this->db->get($this->tbl_order_stock_det);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Order Stock Details Update
		public function orderStockDetails_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_order_stock_det, $data);
	    }

	    //Order Stock Details Delete
		public function orderStockDetails_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_order_stock_det, $data);
	    }

	    // Vendor Order List
	    public function getVendorOrder($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='', $groupby ='')
	    {
	    	if($column !=''){
				$this->db->select($column);
				$this->db->from('tbl_order_details');
				$this->db->join('tbl_order', 'tbl_order.id = tbl_order_details.order_id');
			}else{
				$this->db->select('*');
			}
			if (is_array($param) && count($param)>0){
				// $this->db->where($param);		
				if(isset($param['tbl_order.id']))
				{
					$this->db->where_in('tbl_order.id', $param['tbl_order.id'], FALSE);
				}
				if(isset($param['tbl_order_details.vendor_id']))
				{
					$this->db->where('tbl_order_details.vendor_id',$param['tbl_order_details.vendor_id']);
				}
				if(isset($param['tbl_order.financial_year']))
				{
					$this->db->where('tbl_order.financial_year', $param['tbl_order.financial_year']);	
				}
				if(isset($param['tbl_order.published']))
				{
					$this->db->where('tbl_order.published', $param['tbl_order.published']);	
				}
				if(isset($param['tbl_order_details.published']))
				{
					$this->db->where('tbl_order_details.published', $param['tbl_order_details.published']);	
				}
				if(isset($param['tbl_order.status']))
				{
					$this->db->where('tbl_order.status', $param['tbl_order.status']);	
				}
				if(isset($param['tbl_order_details.order_status']))
				{
					$this->db->where_in('tbl_order_details.order_status', $param['tbl_order_details.order_status'], FALSE);
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
					$this->db->like('order_no',$like['name']);
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

	    // Distributor Order List
	    public function getDistributorOrder($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='', $groupby ='')
	    {
	    	if($column !=''){
				$this->db->select($column);
				$this->db->from('tbl_order_details');
				$this->db->join('tbl_order', 'tbl_order.id = tbl_order_details.order_id');
			}
			else{
				$this->db->select('*');
				$this->db->from('tbl_order_details');
			}
			if (is_array($param) && count($param)>0){
				if(isset($param['tbl_order.published']))
				{
					$this->db->where('tbl_order.published', $param['tbl_order.published']);	
				}
				if(isset($param['tbl_order_details.delete_status']))
				{
					$this->db->where('tbl_order_details.delete_status', $param['tbl_order_details.delete_status']);	
				}
				if(isset($param['tbl_order_details.pack_status']))
				{
					$this->db->where('tbl_order_details.pack_status', $param['tbl_order_details.pack_status']);	
				}
				if(isset($param['tbl_order_details.published']))
				{
					$this->db->where('tbl_order_details.published', $param['tbl_order_details.published']);	
				}
				if(isset($param['tbl_order_details.order_id']))
				{
					$this->db->where('tbl_order_details.order_id', $param['tbl_order_details.order_id']);
				}
				if(isset($param['tbl_order_details.order_status']))
				{
					$this->db->where_in('tbl_order_details.order_status', $param['tbl_order_details.order_status'], FALSE);
				}
				if(isset($param['tbl_order.id']))
				{
					$this->db->where_in('tbl_order.id', $param['tbl_order.id'], FALSE);
				}
				if(isset($param['tbl_order_details.id']))
				{
					$this->db->where_in('tbl_order_details.id', $param['tbl_order_details.id'], FALSE);
				}
				if(isset($param['tbl_order.financial_year']))
				{
					$this->db->where('tbl_order.financial_year', $param['tbl_order.financial_year']);	
				}
				if(isset($param['tbl_order_details.vendor_type']))
				{
					$this->db->where('tbl_order_details.vendor_type', $param['tbl_order_details.vendor_type']);	
				}
				if(isset($param['tbl_order.zone_id']))
				{
					$this->db->where_in('tbl_order.zone_id', $param['tbl_order.zone_id'], FALSE);
				}
				if(isset($param['tbl_order_details.type_id']))
				{
					$this->db->where_in('tbl_order_details.type_id', $param['tbl_order_details.type_id'], FALSE);
				}
				if(isset($param['tbl_order_details.product_process']))
				{
					$this->db->where('tbl_order_details.product_process', $param['tbl_order_details.product_process']);	
				}
				if(isset($param['tbl_order_details.production_status']))
				{
					$this->db->where('tbl_order_details.production_status', $param['tbl_order_details.production_status']);	
				}
				if(isset($param['tbl_order_details.start_date']))
				{
					$this->db->where('tbl_order_details.createdate >=', $param['tbl_order_details.start_date']);
				}
				if(isset($param['tbl_order_details.end_date']))
				{
					$this->db->where('tbl_order_details.createdate <=', $param['tbl_order_details.end_date']);
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
					$this->db->like('tbl_order.order_no',$like['name'], 'both');
					$this->db->or_like('tbl_order.store_name',$like['name'], 'both');
					$this->db->or_like('tbl_order.emp_name',$like['name'], 'both');
					$this->db->or_like('tbl_order_details.invoice_num',$like['name'], 'both');
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

	    // Distributor Order Status Details
	    public function getDistributorOrderStatus($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='', $groupby ='')
	    {
	    	if($column !=''){
				$this->db->select($column);
				$this->db->from('tbl_order_details');
				$this->db->join('tbl_order', 'tbl_order.id = tbl_order_details.order_id');
			}else{
				$this->db->select('*');
			}
			if (is_array($param) && count($param)>0){
				if(isset($param['tbl_order.id']))
				{
					$this->db->where('tbl_order.id', $param['tbl_order.id']);	
				}
				if(isset($param['tbl_order_details.id']))
				{
					$this->db->where('tbl_order_details.id', $param['tbl_order_details.id']);	
				}
				if(isset($param['tbl_order_details.order_id']))
				{
					$this->db->where('tbl_order_details.order_id',$param['tbl_order_details.order_id']);
				}
				if(isset($param['tbl_order_details.item_status']))
				{
					$this->db->where('tbl_order_details.item_status',$param['tbl_order_details.item_status']);
				}
				if(isset($param['tbl_order_details.published']))
				{
					$this->db->where('tbl_order_details.published', $param['tbl_order_details.published']);	
				}
				if(isset($param['tbl_order_details.status']))
				{
					$this->db->where('tbl_order_details.status', $param['tbl_order_details.status']);	
				}
				if(isset($param['tbl_order_details.production_status']))
				{
					$this->db->where('tbl_order_details.production_status', $param['tbl_order_details.production_status']);	
				}
				if(isset($param['tbl_order.zone_id']))
				{
					$this->db->where_in('tbl_order.zone_id', $param['tbl_order.zone_id'], FALSE);
				}
				if(isset($param['tbl_order_details.type_id']))
				{
					$this->db->where_in('tbl_order_details.type_id', $param['tbl_order_details.type_id'], FALSE);
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
					$this->db->like('emp_name',$like['name']);
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

	    //Order Details Detail
		public function getDistributorOrderDetails($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='', $groupby ='')
		{					
			if($column !=''){
				$this->db->select($column);
			}else{
				$this->db->select('*');
			}
			if (is_array($param) && count($param)>0){
				if(isset($param['published']))
				{
					$this->db->where('published', $param['published']);	
				}
				if(isset($param['order_id']))
				{
					$this->db->where('order_id', $param['order_id']);	
				}
				if(isset($param['type_id']))
				{
					$this->db->where_in('type_id', $param['type_id'], FALSE);
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
					$this->db->like('emp_name',$like['name']);
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
			$result = $this->db->get($this->tbl_order_det);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}
	}
?>