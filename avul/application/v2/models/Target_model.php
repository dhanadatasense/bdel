<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Target_model extends CI_Model {

		// Data Table
		public $tbl_target             = "tbl_target";
		public $tbl_target_det         = "tbl_target_details";
		public $tbl_beat_target        = "tbl_beat_target";
		public $tbl_beat_target_det    = "tbl_beat_target_details";
		public $tbl_product_target     = "tbl_product_target";
		public $tbl_product_target_det = "tbl_product_target_details";
		public $tbl_product_assign     = "tbl_product_assign";
		public $tbl_product_template_details  = "tbl_product_template_details";

		// Target 
		// ***************************************************

		//Target Create
		public function target_insert($data)
		{
			$this->db->insert($this->tbl_target, $data);		
			return $this->db->insert_id();
		}

		//Target Detail
		public function getTarget($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(), $orderby = array(), $other =TRUE, $column='', $groupby ='')
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
				// if(isset($like['name']))
				// {
				// 	$this->db->like('year_value',$like['name']);
				// }
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_target);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Target Update
		public function target_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_target, $data);
	    }

	    //Target Delete
		public function target_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_target, $data);
	    }

	    // Target Details 
		// ***************************************************

		//Target Details Create
		public function targetDetails_insert($data)
		{
			$this->db->insert($this->tbl_target_det, $data);		
			return $this->db->insert_id();
		}

		//Target Details Detail
		public function getTargetDetails($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(), $orderby = array(), $other =TRUE, $column='', $groupby ='',$where_in=array())
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
			$result = $this->db->get($this->tbl_target_det);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Target Details Update
		public function targetDetails_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_target_det, $data);
	    }

	    //Target Details Delete
		public function targetDetails_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_target_det, $data);
	    }

	    // Beat Target 
		// ***************************************************

		//Beat Target Create
		public function beatTarget_insert($data)
		{
			$this->db->insert($this->tbl_beat_target, $data);		
			return $this->db->insert_id();
		}

		//Beat Target Detail
		public function getBeatTarget($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(), $orderby = array(), $other =TRUE, $column='', $groupby ='')
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
					$this->db->or_like('year_value',$like['name']);
					$this->db->or_like('emp_name',$like['name']);
				}
				// if(isset($like['name']))
				// {
				// 	$this->db->like('year_value',$like['name']);
				// }
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_beat_target);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Beat Target Update
		public function beatTarget_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_beat_target, $data);
	    }

	    //Beat Target Delete
		public function beatTarget_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_beat_target, $data);
	    }

	    // Beat Target Details 
		// ***************************************************

		//Beat Target Details Create
		public function beatTargetDetails_insert($data)
		{
			$this->db->insert($this->tbl_beat_target_det, $data);		
			return $this->db->insert_id();
		}

		//Beat Target Details Detail
		public function getBeatTargetDetails($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(), $orderby = array(), $other =TRUE, $column='', $groupby ='')
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
			$result = $this->db->get($this->tbl_beat_target_det);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Beat Target Details Update
		public function beatTargetDetails_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_beat_target_det, $data);
	    }

	    //Beat Target Details Delete
		public function beatTargetDetails_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_beat_target_det, $data);
	    }

	    // Product Target 
		// ***************************************************

		//Product Target Create
		public function ProductTarget_insert($data)
		{
			$this->db->insert($this->tbl_product_target, $data);		
			return $this->db->insert_id();
		}

		//Product Target Detail
		public function getProductTarget($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(), $orderby = array(), $other =TRUE, $column='', $groupby ='')
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
					$this->db->or_like('year_name',$like['name']);
					$this->db->or_like('emp_name',$like['name']);
					$this->db->or_like('category_name',$like['name']);
				}
				// if(isset($like['name']))
				// {
				// 	$this->db->like('year_value',$like['name']);
				// }
			}
			if(is_array($orderby) && count($orderby) > 0){
				
				$order_by = isset($orderby['order_by']) ? $orderby['order_by'] : FALSE;
				$disp_order = isset($orderby['disp_order']) ? $orderby['disp_order'] : FALSE;
				$this->db->order_by($order_by, $disp_order); 
			}	
			$result = $this->db->get($this->tbl_product_target);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Product Target Update
		public function ProductTarget_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_product_target, $data);
	    }

	    //Product Target Delete
		public function ProductTarget_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_product_target, $data);
	    }

	    // Product Target Details 
		// ***************************************************

		//Product Target Details Create
		public function ProductTargetDetails_insert($data)
		{
			$this->db->insert($this->tbl_product_target_det, $data);		
			return $this->db->insert_id();
		}

		//Product Target Details Detail
		public function getProductTargetDetails($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(), $orderby = array(), $other =TRUE, $column='', $groupby ='')
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
			$result = $this->db->get($this->tbl_product_target_det);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Product Target Details Update
		public function ProductTargetDetails_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_product_target_det, $data);
	    }

	    //Product Target Details Delete
		public function ProductTargetDetails_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_product_target_det, $data);
	    }

		// Product assign Target Details 
		// ***************************************************

		//Product assign Details Create
		public function ProductAssignTarget_insert($data)
		{
			$this->db->insert($this->tbl_product_assign, $data);		
			return $this->db->insert_id();
		}

		//Product assign Details Detail
		public function getProductAssignTarget($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(), $orderby = array(), $other =TRUE, $column='', $groupby ='')
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
			$result = $this->db->get($this->tbl_product_assign);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Product assign Details Update
		public function ProductAssignTarget_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_product_assign, $data);
	    }

	    //Product assign Details Delete
		public function ProductAssignTarget_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_product_assign, $data);
	    }

		// Product tempalte details Target  
		// ***************************************************

		//Product tempalte Details Create
		public function ProducttempalteTarget_insert($data)
		{
			$this->db->insert($this->tbl_product_template_details, $data);		
			return $this->db->insert_id();
		}

		//Product tempalte Details Detail
		public function getProducttempalteTarget($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(), $orderby = array(), $other =TRUE, $column='', $groupby ='')
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
			$result = $this->db->get($this->tbl_product_template_details);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Product tempalte Details Update
		public function ProducttempalteTarget_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_product_template_details, $data);
	    }

	    //Product tempalte Details Delete
		public function ProducttempalteTarget_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_product_template_details, $data);
	    }
	}
?>