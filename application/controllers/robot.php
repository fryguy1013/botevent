<?php
class Robot extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('Robot_model');
	}
	
	
	function View($id)
	{
		$data = array();
		$data['robot'] = $this->Robot_model->get_robot($id);
				
		$this->load->view('view_robot', $data);
		
	}
}
?>