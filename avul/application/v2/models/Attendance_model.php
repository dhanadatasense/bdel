<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Attendance_model extends CI_Model {

		// Data Table
		public $tbl_attendance           = "tbl_attendance";
		public $tbl_upload_files         = "tbl_upload_files";
		public $tbl_outlet_stock_details = "tbl_outlet_stock_details";
		public $tbl_attendance_details   = "tbl_attendance_details";
		public $tbl_check_point          = "tbl_check_point";

		// Attendance 
		// ***************************************************

		//Attendance Create
		public function attendance_insert($data)
		{
			$this->db->insert($this->tbl_attendance, $data);		
			return $this->db->insert_id();
		} 

		//Attendance Detail
		public function getAttendance($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(), $orderby = array(), $other =TRUE, $column='', $groupby ='', $where_in=array())
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
			if (is_array($where_in) && count($where_in)>0){
				if(isset($where_in['emp_id']))
				{
					$this->db->where_in('emp_id', $where_in['emp_id'], FALSE);	
				}
				if(isset($where_in['state_id']))
					{
						$this->db->where_in('state_id', $where_in['state_id'], FALSE);	
					}
					if(isset($where_in['city_id']))
					{
						$this->db->where_in('city_id', $where_in['city_id'], FALSE);	
					}
					if(isset($where_in['zone_id']))
					{
						$this->db->where_in('zone_id', $where_in['zone_id'], FALSE);	
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
			$result = $this->db->get($this->tbl_attendance);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Attendance Detail
		public function getAttendanceJoin($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(), $orderby = array(), $other =TRUE, $column='', $groupby ='')
		{					
			$this->db->select($column);
			$this->db->from('tbl_attendance A');
			$this->db->join('tbl_employee B','B.id = A.emp_id','left');
			$this->db->join('tbl_outlets C','C.id = A.store_id','left');
			$this->db->join('tbl_zone D','D.id = C.zone_id','left');
			$this->db->join('tbl_order E','E.att_id = A.id','left');
			$this->db->join('tbl_invoice F','F.id = A.invoice_id','left');

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
					$this->db->like('A.emp_name',$like['name']);
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

				//Attendance Detail
		public function getAttendanceJoinMg($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(), $orderby = array(), $other =TRUE, $column='', $groupby ='')
		{					
			$this->db->select($column);
			$this->db->from('tbl_attendance A');
			$this->db->join('tbl_posting B','B.employee_id = A.emp_id','inner');
			
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
					$this->db->like('A.emp_name',$like['name']);
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

		//Attendance Update
		public function attendance_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_attendance, $data);
	    }

	    //Attendance Delete
		public function attendance_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_attendance, $data);
	    }
		public function attendance_details_insert($data)
		{
			$this->db->insert($this->tbl_attendance_details, $data);		
			return $this->db->insert_id();
		} 

		//Attendance Detail
		public function getAttendance_details($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(), $orderby = array(), $other =TRUE, $column='', $groupby ='', $where_in=array())
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
			if (is_array($where_in) && count($where_in)>0){
				if(isset($where_in['emp_id']))
				{
					$this->db->where_in('emp_id', $where_in['emp_id'], FALSE);	
				}
				if(isset($where_in['state_id']))
				{
					$this->db->where_in('state_id', $where_in['state_id'], FALSE);	
				}
				if(isset($where_in['city_id']))
				{
					$this->db->where_in('city_id', $where_in['city_id'], FALSE);	
				}
				if(isset($where_in['zone_id']))
				{
					$this->db->where_in('zone_id', $where_in['zone_id'], FALSE);	
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
			$result = $this->db->get($this->tbl_attendance_details);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Attendance Detail
		public function getAttendanceDetailsJoin($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(), $orderby = array(), $other =TRUE, $column='', $groupby ='')
		{					
			$this->db->select($column);
			$this->db->from('tbl_attendance A');
			$this->db->join('tbl_employee B','B.id = A.emp_id','left');
			$this->db->join('tbl_outlets C','C.id = A.store_id','left');
			$this->db->join('tbl_zone D','D.id = C.zone_id','left');
			$this->db->join('tbl_order E','E.att_id = A.id','left');
			$this->db->join('tbl_invoice F','F.id = A.invoice_id','left');

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
					$this->db->like('A.emp_name',$like['name']);
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

		//Attendance Detail
		public function getAttendanceDetailsJoinMg($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(), $orderby = array(), $other =TRUE, $column='', $groupby ='')
		{					
			$this->db->select($column);
			$this->db->from('tbl_attendance A');
			$this->db->join('tbl_posting B','B.employee_id = A.emp_id','inner');
			
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
					$this->db->like('A.emp_name',$like['name']);
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

		//Attendance Update
		public function attendance_details_update($data, $where = array())
		{
			if (count($where) > 0)
				$this->db->where($where);
			return $this->db->update($this->tbl_attendance_details, $data);
		}

		//Attendance Delete
		public function attendance_details_delete($data, $where = array())
		{
			if (count($where) > 0)
				$this->db->where($where);
			return $this->db->update($this->tbl_attendance_details, $data);
		}

	    //File upload insert batch
		public function file_insert_batch($data)
		{
			$this->db->insert_batch($this->tbl_upload_files, $data);		
			return TRUE;
		}

		public function getFileDetails($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='',$groupby ='')
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
					$this->db->like('id',$like['name']);
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
			$result = $this->db->get($this->tbl_upload_files);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Upload file detail
		public function getUploadFilesDetails($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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

		// Outlet Stock Details
		// ***************************************************

		//Outlet Stock Create
		public function outletStock_insert($data)
		{
			$this->db->insert($this->tbl_outlet_stock_details, $data);		
			return $this->db->insert_id();
		}

		//Outlet stock insert batch
		public function outletStock_insertBatch($data)
		{
			$this->db->insert_batch($this->tbl_outlet_stock_details, $data);		
			return TRUE;
		}

		//Outlet Stock Detail
		public function getOutletStock($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
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
			$result = $this->db->get($this->tbl_outlet_stock_details);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//Outlet Stock Detail
		public function getOutletStockData($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
		{					
			$this->db->select('DATE_FORMAT(entry_date, "%Y-%m-%d") AS formatted_date', false);
			$this->db->from('tbl_outlet_stock_details');
			$this->db->where('MONTH(entry_date)', '06');
			$this->db->where('YEAR(entry_date)', '2023');
			$query = $this->db->get();
			$results = $query->result();

			return $results;
		}

		//Outlet Stock Detail
		public function getOutletStockJoin($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(),$orderby = array(),$other =TRUE,$column='')
		{					
			$this->db->select($column);
			$this->db->from('tbl_outlet_payment_details A');
			$this->db->join('tbl_product_type B','B.id = A.type_id','right');
			$this->db->join('tbl_outlets C','C.id = A.outlet_id','right');
			$this->db->join('tbl_state D','D.id = C.state_id','right');
			$this->db->join('tbl_city E','E.id = C.city_id','right');
			$this->db->join('tbl_outlets C','C.id = A.outlet_id','right');

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
			$result = $this->db->get();
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}


		//Outlet Stock Update
		public function outletStock_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_outlet_stock_details, $data);
	    }

	    //Outlet Stock Delete
		public function outletStock_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_outlet_stock_details, $data);
	    }

	    // CheckPoint 
		// ***************************************************

		//CheckPoint Create
		public function checkPoint_insert($data)
		{
			$this->db->insert($this->tbl_check_point, $data);		
			return $this->db->insert_id();
		} 

		//CheckPoint Detail
		public function getCheckPoint($param=array(),$limit=0,$offset=0,$option="result",$like=array(),$where_or =array(), $orderby = array(), $other =TRUE, $column='', $groupby ='', $where_in=array())
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
			if (is_array($where_in) && count($where_in)>0){
				if(isset($where_in['emp_id']))
				{
					$this->db->where_in('emp_id', $where_in['emp_id'], FALSE);	
				}
				if(isset($where_in['state_id']))
					{
						$this->db->where_in('state_id', $where_in['state_id'], FALSE);	
					}
					if(isset($where_in['city_id']))
					{
						$this->db->where_in('city_id', $where_in['city_id'], FALSE);	
					}
					if(isset($where_in['zone_id']))
					{
						$this->db->where_in('zone_id', $where_in['zone_id'], FALSE);	
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
			$result = $this->db->get($this->tbl_check_point);
			
			if ($result != FALSE && $result->num_rows()>0){

				$result =  $result->$option();
				
				$aResponse = $result;
				return $aResponse;
			}
			return FALSE;
		}

		//CheckPoint Update
		public function checkPoint_update($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_check_point, $data);
	    }

	    //CheckPoint Delete
		public function checkPoint_delete($data, $where = array())
	    {
	        if (count($where) > 0)
	            $this->db->where($where);
	        return $this->db->update($this->tbl_check_point, $data);
	    }
	}
?>