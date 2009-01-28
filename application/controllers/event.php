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
		$data['event_divisions'] = $this->Event_model->get_event_divisions_with_counts($id);

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
		$data['event_entries'] = $this->Event_model->get_event_entries($id, $division);

		$this->load->view('view_header');
		$this->load->view('view_event_header', $data);
		$this->load->view('view_event_entries', $data);
		$this->load->view('view_footer');
	}
	
	function Register($id)
	{
		$this->load->library(array('session', 'form_validation'));
		$this->load->model(array('Person_model', 'Team_model', 'Entry_model'));
		
		if ($this->session->userdata('userurl') === false)
		{
			$this->session->set_userdata('onloginurl', "event/register/$id");
			redirect('login');
			return;
		}

		$people = array(
				"you"=>"Please enter information about yourself.",
				"1"=>"Please enter information about your teammates",
				"2"=>"",
				"3"=>"",
				"4"=>"",
				"5"=>"",
				"6"=>"",
				"7"=>"",
				"8"=>"",
				);
		

		foreach ($people as $key=>$heading)
		{
			$this->form_validation->set_rules("person[$key][fullname]", "Full Name", 'trim');
			$this->form_validation->set_rules("person[$key][email]", "Email Address", 'trim|valid_email_or_blank');
			$this->form_validation->set_rules("person[$key][picturepath]", "Picture Path", '');
			$this->form_validation->set_rules("person[$key][picturecropx]", "Crop X", '');
			$this->form_validation->set_rules("person[$key][picturecropy]", "Crop Y", '');
			$this->form_validation->set_rules("person[$key][picturecropwidth]", "Crop Width", '');
			$this->form_validation->set_rules("person[$key][picturecropheight]", "Crop Height", '');
		}
		$this->form_validation->set_rules('person[you][fullname]', 'Full Name', 'trim|required');
		$this->form_validation->set_rules('person[you][email]', 'Email Address', 'trim|required|valid_email');
		
		$this->form_validation->set_rules('team[name]', 'Team Name', 'trim|required');
		$this->form_validation->set_rules('team[website]', 'Team Website', 'trim');

		$this->form_validation->set_rules('entry[1][name]', 'Entry Name', 'trim|required');
		$this->form_validation->set_rules('entry[1][division]', 'Entry Division', 'trim');	
		
		if ($this->form_validation->run() != FALSE)
		{
			// insert team
			$team = $this->input->post('team');
			$team_id = $this->Team_model->add_team($team['name'], $team['website']);
			echo "<p>Added team: $team_id</p>";
			
			// insert people
			$people = $this->input->post('person');
			$people['you']['idurl'] = $this->session->userdata('userurl');			

			foreach ($people as $pid=>$person)
			{
				if (empty($person['fullname']) || empty($person['email']))
					continue;
			
				$people[$pid]['id'] = $this->Person_model->add_person(
						$person['fullname'],
						$person['email'],
						isset($person['is_adult']),
						$person['picturepath'],
						isset($person['idurl']) ? $person['idurl'] : '',
						$team_id);
				
				echo '<p>Added person: '.$people[$pid]['id'].'</p>';
			}
			
			// set captain
			$this->Team_model->set_team_captain($team_id, $people['you']['id']);
			
			// insert entries
			$entries = $this->input->post('entry');
			print_r($entries);
			foreach ($entries as $eid=>$entry)
			{
				if (empty($entry['name']) || !isset($entry['division']))
					continue;
				
				$entries[$eid]['id'] = $this->Entry_model->add_entry($entry['name'], $team_id);
										
				echo "<p>Added entry: ".$entries[$eid]['id']."</p>";				
			}
			
			
			// now that everything is added, create the registration
			$registration_people = array();
			foreach ($people as $pid=>$person)
				if (!empty($person['id']))
					$registration_people[] = array('id' => $person['id']);
				
			$registration_entries = array();
			foreach ($entries as $eid=>$entry)
				if (!empty($entry['id']))
					$registration_entries[] = array('id' => $entry['id'], 'division' => $entry['division']);
				
			$registration_id = $this->Event_model->create_registration($id, $team_id, $registration_people, $registration_entries);
			
						
			//redirect(array('event', 'viewregistration', $id));
			return;			
		}
		
		$data = array();		
		$data['event'] = $this->Event_model->get_event($id);
		$data['event_divisions'] = $this->Event_model->get_event_divisions_as_id_desc($id);

		// reload the data they put into the form
		foreach ($people as $key=>$value)
		{
			$data['person'][$key]['heading'] = $value;
			$data['person'][$key]['fullname'] = set_value("person[$key][fullname]", '');
			$data['person'][$key]['email'] = set_value("person[$key][email]", '');
			$data['person'][$key]['picture'] = set_value("person[$key][picture]", site_url('images/nopicture.png'));
			$data['person'][$key]['adult'] = set_value("person[$key][adult]");
			$data['person'][$key]['picturepath'] = set_value("person[$key][picturepath]");
			$data['person'][$key]['picturecropx'] = set_value("person[$key][picturecropx]");
			$data['person'][$key]['picturecropy'] = set_value("person[$key][picturecropy]");
			$data['person'][$key]['picturecropwidth'] = set_value("person[$key][picturecropwidth]");
			$data['person'][$key]['picturecropheight'] = set_value("person[$key][picturecropheight]");
		}

		if ($this->session->userdata('openid_fullname') !== FALSE && empty($data['person']['you']['fullname']))
			$data['person']['you']['fullname'] = $this->session->userdata('openid_fullname');
		if ($this->session->userdata('openid_email') !== FALSE && empty($data['person']['you']['email']))
			$data['person']['you']['email'] = $this->session->userdata('openid_email');
		
		$data['team']['name'] = set_value("team[name]");
		$data['team']['website'] = set_value("team[website]");
		
		for ($key=1; $key<=6; $key++)
		{
		  $data['entries'][$key]['name'] = set_value("person[$key][name]");
		  $data['entries'][$key]['division'] = set_value("person[$key][division]");
		}
		
		$this->load->view('view_header');	
		$this->load->view('view_event_header', $data);
		$this->load->view('view_event_register', $data);
		$this->load->view('view_footer');
	}
	
	
	function Uploadpicture()
	{
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
}
