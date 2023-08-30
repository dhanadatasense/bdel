<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Master extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->library('session');
			$this->load->library('encryption');
			$this->load->helper('url');
			$this->load->model('app_model/master/state_model');
			$this->load->model('app_model/master/city_model');
		}

		public function index($param1="", $param2="", $param3="")
		{
			header("Access-Control-Allow-Origin: *");
	    	$this->load->view('app_view/login');
		}
		public function state($param1="", $param2="", $param3="")
		{
			header('Content-Type: application/json');
			header("Access-Control-Allow-Origin: *");
			$stateData = $this->state_model->get();
			if(!empty($stateData))
			{
				$response['status']=1;
			    $response['message']='Success';
			    $response['data']=$stateData;
			    echo json_encode($response);
			    return;
			}
			else
			{
				$response['status']=0;
			    $response['message']='Success';
			    $response['data']=[];
			    echo json_encode($response);
		    return;
			}
		}
		public function city($param1="", $param2="", $param3="")
		{
			header('Content-Type: application/json');
			header("Access-Control-Allow-Origin: *");
			$state_id = $this->input->post('state_id');
			if($state_id !='')
			{
				$whr =array('fldState_id' =>$state_id);
				$cityData = $this->city_model->get();
				if(!empty($cityData))
				{
					$response['status']=1;
				    $response['message']='Success';
				    $response['data']=$cityData;
				    echo json_encode($response);
				    return;
				}
				else
				{
					$response['status']=0;
				    $response['message']='Not Found';
				    $response['data']=[];
				    echo json_encode($response);
			    return;
				}
			}
			else
			{
				$response['status']=0;
			    $response['message']='State id is required';
			    $response['data']=[];
			    echo json_encode($response);
		    	return;
			}
		}
	}
?>
