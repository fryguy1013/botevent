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
	
	function Register($id)
	{
		$this->load->library(array('session', 'form_validation'));
		$this->load->model(array('Person_model', 'Team_model', 'Entry_model'));
	
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
		$team_registration = $this->Event_model->get_event_registration_by_team($teamid);
		if (count($team_registration) != 0)
		{
			redirect(array('event', 'viewregistration', $team_registration->id));
			return;		
		}
		

		$data = array();
		$data['event'] = $this->Event_model->get_event($id);
		$data['event_divisions'] = $this->Event_model->get_event_divisions_as_id_desc($id);
		
		if ($this->input->post('submit') == 'Add Member')
		{
			$this->form_validation->set_rules("fullname", "Full Name", 'trim|required');
			$this->form_validation->set_rules("email_addr", "Email Address", 'trim|required|valid_email');
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
			if ($this->form_validation->run() != FALSE && ($file_upload === TRUE || empty($_FILES['entry_photo']['name'])))
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
				
				$registration_id = $this->Event_model->create_registration($id, $teamid, $personid, $registration_people, $registration_entries);
				
				$this->session->set_flashdata('registration_success', TRUE);
				redirect(array('event', 'viewregistration', $registration_id));
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
		$this->load->view('view_event_register', $data);
		$this->load->view('view_footer');		
	}
	
	function Viewregistration($id)
	{
		$data = array();
		
		$registration = $this->Event_model->get_event_registration($id);

		$data['event'] = $this->Event_model->get_event($registration->event);
		$data['registration'] = $registration;
		$data['entries'] = $this->Event_model->get_registration_entries($id);
		$data['people'] = $this->Event_model->get_registration_people($id);

		$this->load->view('view_header');	
		$this->load->view('view_event_header', $data);
		$this->load->view('view_event_registration', $data);
		$this->load->view('view_footer');				
	}

	
	function Manage($id)
	{
		$data = array();
		$data['event'] = $this->Event_model->get_event($id);
		$data['event_registrations'] = $this->Event_model->get_event_registrations($id);
		$data['event_entries'] = $this->Event_model->get_event_entries_grouped($id);
		$data['event_people'] = $this->Event_model->get_event_people_grouped($id);

		$this->load->view('view_header');		
		$this->load->view('view_event_header', $data);
		$this->load->view('view_event_manage', $data);
		$this->load->view('view_footer');
	}
	
	function Uploadpicture()
	{
		$config = array();
		$config['upload_path'] = './images/uploads/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '2000';
		$config['encrypt_name'] = TRUE;
		//$config['max_width']  = '1024';
		//$config['max_height']  = '768';
		
		$this->load->library('upload', $config);
		
		if (!$this->upload->do_upload('photo'))
		{
			$error = array('error' => $this->upload->display_errors());			
			$this->output->set_output("error: ". var_export($error));
		}	
		else
		{
			$data = $this->upload->data();
			$this->output->set_output("success: /images/uploads/".$data['file_name']);
		}		
	}
	
	function Updatestatus($regid)
	{
		$status = $this->input->post('status');
		$this->Event_model->update_reg_status($regid, $status);
		
		$this->output->set_output($status);
	}
	
	function valid_email_or_blank($str)
	{
		if (empty($str))
			return TRUE;		
		return valid_email($str);
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
