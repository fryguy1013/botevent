<?php

class Event extends Controller
{
	
	function Event()
	{
		parent::Controller();
		
		$this->load->model('Event_model');
		$this->upload_errors = "";
	}
	
	function index()
	{
		$this->All();
	}
	
	function Reset()
	{
		$this->Event_model->reset();
	}

	function All()
	{
		$data = array();
		$data['events'] = $this->Event_model->get_events();
		
		$this->load->view('view_header');
		$this->load->view('view_event_all', $data);		
		$this->load->view('view_footer');
	}
	
	function View($id)
	{
		$data = array();
		$data['event'] = $this->Event_model->get_event($id);
		$data['event_divisions'] = $this->Event_model->get_event_divisions($id);
		$data['event_division_counts'] = $this->Event_model->get_event_divisions_counts($id);

		$this->load->view('view_header');		
		$this->load->view('view_event_header', $data);
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
		$this->load->view('view_event_header', $data);
		$this->load->view('view_event_entries', $data);
		$this->load->view('view_footer');
	}
	
	function Register($id, $extra = '')
	{
		$this->load->library(array('session', 'form_validation'));
		$this->load->model(array('Person_model', 'Team_model', 'Entry_model', 'Event_registration_model'));
	
		$personid = $this->session->userdata('userid');
		if ($personid === false)
		{
			$this->session->set_userdata('onloginurl', "event/register/$id");
			redirect('login');
			return;
		}
		
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
		

		$data = array();
		$data['event'] = $this->Event_model->get_event($id);
		$data['event_divisions'] = $this->Event_model->get_event_divisions_as_id_desc($id);
		
		if ($this->input->post('submit') == 'Add Member')
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
				$p = $this->Person_model->add_person(
						$this->input->post('fullname'),
						$dob,
						$this->input->post('email_addr'),
						$file_upload,
						'');
				
				if ($p !== FALSE)
					$this->Team_model->add_team_member($teamid, $p);
				else
					$data['show_add_member'] = TRUE;
			}
			else
			{
				$data['show_add_member'] = TRUE;
				$data['add_member_errors'] =  $this->upload_errors.validation_errors();
			}			
		}
		else if ($this->input->post('submit') == 'Add Entry')
		{
			$this->form_validation->set_rules("entry_name", "Entry Name", 'trim|required');
			$file_upload = $this->_do_upload('entry_photo');
			if ($this->form_validation->run() != FALSE && ($file_upload !== FALSE || empty($_FILES['entry_photo']['name'])))
			{
				$e = $this->Entry_model->add_entry(
						$this->input->post('entry_name'),						
						$teamid,
						$file_upload);
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
				
				$registration_id = $this->Event_registration_model->create_registration(
					$id,
					$teamid,
					$personid,
					$registration_people,
					$registration_entries);
				
				$this->session->set_flashdata('registration_success', TRUE);
				redirect(array('event_registration', 'view', $registration_id));
				return;
			}
			else
			{
				$data['registration_errors'] = validation_errors();
			}		
		}


		$data['team_members'] = $this->Team_model->get_team_members($teamid);
		$data['team_entries'] = $this->Team_model->get_team_entries($teamid);
		$data['form_person'] = $this->input->post('person');
		$data['form_entry'] = $this->input->post('entry');
		$data['form_entry_division'] = $this->input->post('entry_division');
		
		$this->load->view('view_header');	
		$this->load->view('view_event_header', $data);
		if (count($team_registration) != 0 && !$this->input->post('hide_registration') && $extra != 'update')
		{
			$data2 = array();
			$data2['event'] = $data['event'];
			$data2['registration'] = $team_registration;
			$data2['entries'] = $this->Event_registration_model->get_registration_entries($team_registration->id);
			$data2['people'] = $this->Event_registration_model->get_registration_people($team_registration->id);
			$this->load->view('view_event_registration', $data2);
			$data['hide_form'] = TRUE;
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
		$this->load->view('view_event_header', $data);
		$this->load->view('view_event_manage', $data);
		$this->load->view('view_footer');
	}
	
	function Updatestatus($regid)
	{
		$status = $this->input->post('status');
		$this->Event_model->update_reg_status($regid, $status);
		
		$this->output->set_output($status);
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
		return count($person) == 0;
	}	
	
	function _do_upload($field) 
	{ 
		$config = array(); 
		$config['upload_path'] = './images/uploads/'; 
		$config['allowed_types'] = 'gif|jpg|png'; 
		$config['max_size']	= '2000'; 
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
