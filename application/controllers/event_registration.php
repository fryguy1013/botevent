<?php

class Event_registration extends Controller
{
	
	function Event_registration()
	{
		parent::Controller();
		
		$this->load->model(array('Event_model', 'Event_registration_model'));
	}
	
	function View($id)
	{
		$data = array();
		
		$registration = $this->Event_registration_model->get_event_registration($id);

		$data['event'] = $this->Event_model->get_event($registration->event);
		$data['registration'] = $registration;
		$data['entries'] = $this->Event_registration_model->get_registration_entries($id);
		$data['people'] = $this->Event_registration_model->get_registration_people($id);

		$this->load->view('view_header');	
		$this->load->view('view_event_header', $data);
		$this->load->view('view_event_registration', $data);
		$this->load->view('view_footer');				
	}
	
		
}
