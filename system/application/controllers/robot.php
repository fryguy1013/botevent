<?php
class Robot extends Controller
{
	
	function Robot()
	{
		parent::Controller();

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