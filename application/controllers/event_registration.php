<?php

class Event_registration extends Controller
{
	
	function Event_registration()
	{
		parent::Controller();
		
		$this->load->model(array('Event_model', 'Event_registration_model'));
	}
	
	function View($registration_id)
	{
		$data = array();
		
		$data['registration'] = $this->Event_registration_model->get_event_registration($registration_id);
		

		$this->load->view('view_header');
		if (count($data['registration']) > 0)
		{
			$data['event'] = $this->Event_model->get_event($data['registration']->event);
			$data['entries'] = $this->Event_registration_model->get_registration_entries($registration_id);
			$data['people'] = $this->Event_registration_model->get_registration_people($registration_id);
			$this->load->view('view_event_header', $data);
			$this->load->view('view_event_registration', $data);		
		}
		else
		{
			$this->load->view('view_error', array('error' => 'That registration does not exist'));
		}
		$this->load->view('view_footer');
	}
	
	function Withdraw($registration_id)
	{
		$registration = $this->Event_registration_model->get_event_registration($registration_id);
		$team_members = $this->Event_registration_model->get_registration_people($registration_id);
		$personid = $this->session->userdata('userid');
		
		$good = FALSE;
		foreach ($team_members as $member)
			if ($member->id == $personid)
				$good = TRUE;
				
		if ($good)
		{
			$this->Event_registration_model->update_reg_status($registration_id, 'withdrawn');
			redirect(array('event', 'view', $registration->event));
			return;
		}

		$this->load->view('view_header');
		$this->load->view('view_error', array('error' => 'You are not allowed to withdrawn from that event'));
		$this->load->view('view_footer');

	}
		
}
