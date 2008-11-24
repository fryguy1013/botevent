<?php
class Robot extends Controller
{
	
	function Robot()
	{
		parent::Controller();

		$this->load->model('Robot_model');
		
		//$this->load->Scaffolding('robot');
	}
	
	
	function View($id)
	{
		$data = array();
		$data['robots'] = $this->Robot_model->get_robots();
				
		$this->load->view('robotview', $data);
		
	}
}
?>