<?php

class Event extends Controller
{
	
	function Event()
	{
		parent::Controller();
		
		$this->load->model('Event_model');
	}
	
	function index()
	{
		$this->All();
	}
	
	function View($id)
	{
		$data = array();
		$data['event'] = $this->Event_model->get_event($id);

		$this->load->view('view_event', $data);
	}

	function All()
	{
		$data = array();
		$data['events'] = $this->Event_model->get_events();
		
		$this->load->view('view_event_all', $data);		
	}
	
}
