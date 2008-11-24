<?php
class Event extends Controller
{
	
	function Event()
	{
		parent::Controller();
		
		$this->load->Scaffolding('event');
	}
	
	
	function View($id)
	{
		$robots = $this->Robot_model->get_robots();
			
		$this->load->view('robotview');		
	}
}
?>