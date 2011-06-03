<?php

class Event extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		
		$this->load->model('Event_model');
		$this->load->model('Person_model');
		$this->upload_errors = "";
		
		if ($this->config->item('requires_login') === TRUE)
			$this->Person_model->check_login();
	}
	
	function index()
	{
		$this->All();
	}
	
	function All()
	{
		$data = array();
		$data['future_events'] = $this->Event_model->get_future_events();
		$data['past_events'] = $this->Event_model->get_past_events();
		
		$this->load->view('view_header');
		$this->load->view('view_event_all', $data);		
		$this->load->view('view_footer');
	}
	
	function View($id)
	{
		$this->load->model(array('Team_model', 'Event_registration_model'));
		
		$data = array();
		$data['event'] = $event = $this->Event_model->get_event($id);
		$data['registration_available'] = (strtotime($event->registrationends) > time());
		$data['event_divisions'] = $this->Event_model->get_event_divisions($id);
		$data['event_division_counts'] = $this->Event_model->get_event_divisions_counts($id);

		$personid = $this->session->userdata('userid');
		if ($personid !== false)
		{
			$teamid = $this->Team_model->get_teams_for_person($personid);
			if (count($teamid))
				$data['registration_status'] = $this->Event_registration_model->get_event_registration_by_team($id, $teamid[0]->id);
		}

		$this->load->view('view_header');		
		$this->load->view('view_event', $data);
		$this->load->view('view_footer');		
	}
	
	function Entries($id, $division)
	{
		$data = array();
		$data['event'] = $this->Event_model->get_event($id);
		$data['event_division'] = $this->Event_model->get_division_info($division);
		$data['event_entries'] = $this->Event_model->get_event_entries($division);

		$this->load->view('view_header');
		$this->load->view('view_event_entries', $data);
		$this->load->view('view_footer');
	}
	
	function Entries_xml($id)
	{
		$divisions = $this->Event_model->get_event_divisions($id);
		
		$xml = new SimpleXMLElement('<event></event>');
		foreach ($divisions as $division)
		{
			$xdiv = $xml->addChild('division');
			
			$xdiv->addAttribute('name', $division->name);
			$xdiv->addAttribute('division', $division->division);
			$xdiv->addAttribute('description', $division->description);
			$xdiv->addAttribute('ruleurl', $division->ruleurl);
			$xdiv->addAttribute('maxentries', $division->maxentries);
			$xdiv->addAttribute('price', $division->price);

			$entries = $this->Event_model->get_event_entries($division->event_division);
			
			foreach ($entries as $entry)
			{
				$xentry = $xdiv->addChild('entry');
				$xentry->addAttribute('name', $entry->name);
				$xentry->addAttribute('description', $entry->description);
				$xentry->addAttribute('thumbnail_url', site_url($entry->thumbnail_url));
				$xentry->addAttribute('teamname', $entry->teamname);
				$xentry->addAttribute('teamid', $entry->teamid);
				$xentry->addAttribute('status', $entry->status);
			}			
		}
		
		header('Content-Type: text/xml');
		echo $xml->asXML();
	}

	function Register($id, $extra = '')
	{
		$this->load->library(array('session', 'form_validation'));
		$this->load->model(array('Person_model', 'Team_model', 'Entry_model', 'Event_registration_model'));

		$data = array();
		$data['event'] = $event = $this->Event_model->get_event($id);
		$data['event_divisions'] = $this->Event_model->get_event_divisions_as_id_desc($id);
		
		$personid = $this->session->userdata('userid');
		$this->Person_model->check_login();
		
		$teamid = $this->session->flashdata('teamid');
		// TODO: In later versions, this should give them a choice to select a
		// team they are on, or make a new one.
		if (empty($teamid))
		{
			$teamid = $this->Team_model->get_teams_for_person($personid);
			$teamid = $teamid[0]->id;
		}
		
		// first check if they are already registered, and if they are, redirect them
		$team_registration = $this->Event_registration_model->get_event_registration_by_team($id, $teamid);
		//if (count($team_registration) != 0)
		//{
		//	redirect(array('event_registration', 'view', $team_registration->id));
		//	return;		
		//}

		// get them out of registration if it's closed, but not of they're already registered (so they can change it)
		if (time() > strtotime($event->registrationends) && count($team_registration) == 0)
		{
			redirect(site_url(array('event', 'view', $id)));
			return;
		}

		if ($this->input->post('submit') == 'Add Member' || $this->input->post('submit') == 'Edit Member')
		{			
			$this->form_validation->set_rules("fullname", "Full Name", 'trim|required');
			$this->form_validation->set_rules("email_addr", "Email Address", 'trim|callback_valid_email_or_blank|callback_unique_email');
			$this->form_validation->set_rules('dob_month', 'DOB Month', 'trim|required');
			$this->form_validation->set_rules('dob_day', 'DOB Day', 'trim|required');
			$this->form_validation->set_rules('dob_year', 'DOB Year', 'trim|required');			
			
			$dob = sprintf("%s/%s/%s", $this->input->post('dob_month'), $this->input->post('dob_day'), $this->input->post('dob_year'));
			$file_upload = $this->_do_upload('badge_photo');
			if ($this->form_validation->run() != FALSE && $file_upload !== FALSE)
			{
				if ($this->input->post('submit') == 'Add Member')
				{
					$p = $this->Person_model->add_person(
							$this->input->post('fullname'),
							$dob,
							$this->input->post('email_addr'),
							$file_upload,
							'');
				
					if ($p !== FALSE)
					{
						$this->Team_model->add_team_member($teamid, $p);
					}
					else
					{
						$data['show_add_member'] = TRUE;
						$data['show_edit_member'] = FALSE;
						$data['add_member_errors'] = "You must enter a unique email address. Leave the email field blank if there is no email address.";
					}
				}
				else
				{									
					$this->Person_model->edit_person(
							$this->input->post('person_id'),
							$this->input->post('fullname'),
							$dob,
							$this->input->post('email_addr'),
							$file_upload);					
				}
			}
			else
			{
				$data['show_add_member'] = TRUE;
				$data['show_edit_member'] = $this->input->post('submit') == 'Edit Member';
				$data['add_member_errors'] =  $this->upload_errors.validation_errors();
			}			
		}
		else if ($this->input->post('submit') == 'Add Entry' || $this->input->post('submit') == 'Edit Entry')
		{
			$this->form_validation->set_rules("entry_name", "Entry Name", 'trim|required');
			$file_upload = $this->_do_upload('entry_photo');
			$raw_division = $this->Event_model->get_raw_division_from_event_division($this->input->post('entry_div'));
			if ($this->form_validation->run() != FALSE && ($file_upload !== FALSE || empty($_FILES['entry_photo']['name'])))
			{
				if ($this->input->post('submit') == 'Add Entry')
				{
					$e = $this->Entry_model->add_entry(
							$this->input->post('entry_name'),
							$raw_division,
							$teamid,
							$file_upload);
				}
				else
				{
					$this->Entry_model->edit_entry(
							$this->input->post('entry_id'),
							$this->input->post('entry_name'),						
							$raw_division,
							$file_upload);
				}
			}
			else
			{
				$data['show_add_entry'] = TRUE;
				$data['add_entry_errors'] = $this->upload_errors.validation_errors();
			}			
		}
		else if ($this->input->post('submit') == 'Register')
		{
			$this->form_validation->set_rules('person[]', 'List of people attending', 'required');
			$this->form_validation->set_rules('entry[]', 'List of entries attending', 'required');

			if ($this->form_validation->run() != FALSE)
			{
				$entry_division = $this->input->post("entry_division");
			
				$registration_people = array();
				foreach ($this->input->post('person') as $pid)
				{
					$registration_people[] = array('id' => $pid);
				}
					
				$registration_entries = array();
				foreach ($this->input->post('entry') as $eid)
				{
					$registration_entries[] = array(
						'id' => $eid,
						'division' => $entry_division[$eid]
					);
				}
				
				$safe_to_register = $this->Event_registration_model->get_safety_of_registration(
					$id,
					$teamid,
					$registration_entries);
					
				if ($safe_to_register['safe'])
				{
				
					$registration_id = $this->Event_registration_model->create_registration(
						$id,
						$teamid,
						$personid,
						$registration_people,
						$registration_entries);
	
					// send email to EO
					$team = $this->Team_model->get_team($teamid);
					$this->load->library('email');
					$captain_email = $this->Event_registration_model->get_registration_captain_email($registration_id);		
					$this->email->from('registration@robogames.net', 'RoboGames Registration');
					$this->email->reply_to($captain_email);
					$this->email->to("David Calkins <dcalkins@robotics-society.org>");
					//$this->email->to("Kevin Hjelden <fryguy@burntpopcorn.net>");		
					$this->email->subject($team->name." has registered for ".$event->name);			
					$this->email->message(
						$team->name." has registered ".$event->name."\n\n" .
						"You can view the registration here:\n" .
						site_url(array('event_registration', 'view', $registration_id))
					);
					$this->email->send();				
					
					$this->session->set_flashdata('registration_success', TRUE);
					redirect(site_url(array('event_registration', 'view', $registration_id)));
					return;
				}
				else
				{
					$errorstr = '';
					foreach ($safe_to_register['fulldivisions'] as $full_division)
					{
						$errorstr .= 'Unable to register because '.$data['event_divisions'][$full_division]." is full.\r\n";
					}
					$data['registration_errors'] = $errorstr;
				}
			}
			else
			{
				$data['registration_errors'] = validation_errors();
			}
		}


		$data['team_members'] = $this->Team_model->get_team_members($teamid);
		$data['team_entries'] = $this->Team_model->get_team_entries_with_event_division($teamid, $id);
		$data['form_person'] = $this->input->post('person');
		$data['form_entry'] = $this->input->post('entry');
		$data['form_entry_division'] = $this->input->post('entry_division');
		$data['form_entry_division_base'] = $this->Team_model->get_team_entry_event_divisions($teamid, $id);
		
		$this->load->view('view_header');
		if (count($team_registration) != 0 && !$this->input->post('hide_registration') && $extra != 'update')
		{
			redirect(site_url(array('event_registration', 'view', $team_registration->id)));
		}
		$this->load->view('view_event_register', $data);
		$this->load->view('view_footer');		
	}

	
	function Manage($id)
	{
		$this->load->model('Event_registration_model');
	
		$data = array();
		$data['event'] = $this->Event_model->get_event($id);
		$data['event_registrations'] = $this->Event_registration_model->get_event_registrations($id);
		$data['event_entries'] = $this->Event_model->get_event_entries_grouped($id);
		$data['event_people'] = $this->Event_model->get_event_people_grouped($id);

		$this->load->view('view_header');		
		$this->load->view('view_event_manage', $data);
		$this->load->view('view_footer');
	}
	
	function Updatestatus($regid)
	{
		$this->load->model('Event_registration_model');
		$status = $this->input->post('status');
		$message = $this->input->post('message');
		$amount_due = $this->input->post('amount_due');
		$this->Event_registration_model->update_reg_status($regid, $status, $amount_due);
		$captain_email = $this->Event_registration_model->get_registration_captain_email($regid);
		
		$this->load->library('email');		
		$this->email->from('registration@robogames.net', 'RoboGames Registration');
		$this->email->to($captain_email);
		
		$this->email->subject('Registration Status has been updated');
		
		$email_message =
"Registration changed to: $status

You can view the status of your entry, or make a payment here:
".site_url(array('event_registration', 'view', $regid));

		if (!empty($message))
		{
			$email_message .=
"
The event organizer has left the following message:
$message";
		}
		
		$this->email->message($email_message);
		$this->email->send();
		
		$this->output->set_output($status);
	}
	
	function Updatepayment($regid)
	{
		$this->load->model('Event_registration_model');
		$amount_paid = $this->input->post('amount_paid');
		$registration = $this->Event_registration_model->get_event_registration($regid);
		$this->Event_registration_model->update_payment($registration->event, $registration->team, $amount_paid);
	
		$this->output->set_output($amount_paid);
	}	
	
	function valid_email_or_blank($email)
	{
		if (empty($email))
			return TRUE;
		$this->form_validation->set_message('valid_email_or_blank', 'The email address must be valid, or blank');
		return $this->form_validation->valid_email($email);
	}	
	function unique_email($str)
	{
		if (empty($str))
			return TRUE;
		$this->load->model('Person_model');
		$person = $this->Person_model->get_person_by_email($str);
		$this->form_validation->set_message('unique_email', 'That email address is already in use. Choose another.');		
		return TRUE; //count($person) == 0;
	}	
	
	function _do_upload($field) 
	{ 
		$config = array(); 
		$config['upload_path'] = './images/uploads/'; 
		$config['allowed_types'] = 'gif|jpg|png'; 
		$config['max_size']	= '0';
		$config['encrypt_name'] = TRUE; 
		//$config['max_width']  = '1024'; 
		//$config['max_height']  = '768';
		 
		$this->load->library('upload', $config); 
		 
		if (!$this->upload->do_upload($field)) 
		{ 
			$this->upload_errors = $this->upload->display_errors(); 
			return FALSE;			 
		}	 
		else 
		{ 
			$data = $this->upload->data(); 
			return "/images/uploads/".$data['file_name']; 
		}	 
	}
	
}
