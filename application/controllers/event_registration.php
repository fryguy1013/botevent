<?php

class Event_registration extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		$this->load->model(array('Event_model', 'Event_registration_model', 'Team_model', 'Person_model'));

		if ($this->config->item('requires_login') === TRUE)
			$this->Person_model->check_login();
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

            $all_team_members = $this->Team_model->get_team_members_as_id_desc($data['registration']->teamid);
		    $personid = $this->session->userdata('userid');
            $data['is_member'] = isset($all_team_members[$personid]);

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

		// sanity check that the logged in person is on the team
        $all_team_members = $this->Team_model->get_team_members_as_id_desc($registration->teamid);

		if (isset($all_team_members[$personid]))
		{
			$this->Event_registration_model->update_reg_status($registration_id, 'withdrawn', 0);

			// send email to EO
			$team = $this->Team_model->get_team($registration->team);
			$event = $this->Event_model->get_event($registration->event);
			$captain_email = $this->Event_registration_model->get_registration_captain_email($registration_id);

			$this->load->library('email');
			$this->email->from('registration@robogames.net', 'RoboGames Registration');
			$this->email->reply_to($captain_email);
            $owners = $this->Event_model->get_owners_of_event($registration->event);
            $to = array();
            foreach ($owners as $owner)
            {
                $to[] = $owner->fullname." <".$owner->email.">";
            }
            $this->email->to($to);
			$this->email->subject($team->name.' has withdrawn from '.$event->name);
			$this->email->message(site_url(array('event_registration', 'view', $registration_id)));
			$this->email->send();

			redirect(site_url(array('event', 'view', $registration->event)));
			return;
		}

		$this->load->view('view_header');
		$this->load->view('view_error', array('error' => 'You are not allowed to withdrawn from that event'));
		$this->load->view('view_footer');
	}
}
