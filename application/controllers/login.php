<?php

class Login extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->library(array('session', 'form_validation'));
		$this->load->model('Person_model');

		$this->upload_errors = "";

		//$this->output->enable_profiler(TRUE);
	}

	function index()
	{
		$data = array();
		
		if ($this->input->post('action') == 'send_login_code')
		{
			$this->form_validation->set_rules('email_addr', 'Email Address', 'trim|required|valid_email');
			if ($this->form_validation->run() != FALSE)
			{
				$person = $this->Person_model->get_person_by_email($this->input->post('email_addr'));
				$email_addr = $this->input->post('email_addr');
				if (count($person) == 0)
				{
					$this->session->set_userdata('email', $this->input->post('email_addr'));
					redirect(site_url(array('login','register')));
				}
				else
				{
					$code = $this->Person_model->create_login_code($person->id);
					
					$email_content = $this->load->view('email_login_code', array('userid'=>$person->id, 'code'=>$code), TRUE);

					if ($this->config->item('use_postmark') === TRUE)
					{
						$this->load->library('postmark');
						$this->postmark->to($person->email);
						$this->postmark->subject("Robogames registraion login information");
						$this->postmark->message_plain($email_content);
						$this->postmark->send();
					}
					else
					{
						$this->load->library('email');
						$this->email->from('registration@robogames.net', 'RoboGames Registration');
						$this->email->to($person->email);
						$this->email->subject("Robogames registraion login information");
						$this->email->message($email_content);
						$this->email->send();
					}
					
					$data['success'] = 'Verification code has been sent to your email address.';
				}
			}	
			else
			{
				$data['error'] = validation_errors();
			}
		}

		$this->load->view('view_header', $data);
		$this->load->view('view_login', $data);
		$this->load->view('view_footer', $data);		
	}
	
	function logout()
	{
		$this->session->sess_destroy();
		redirect('login');
	}
	
	function validate_code($id, $code)
	{
		$is_valid_code = $this->Person_model->check_login_code($id, $code);
		$person = $this->Person_model->get_person_by_id($id);
		if (!$is_valid_code || count($person) == 0)
		{
			$data = array('error' => 'That login code is invalid.');
			
			$this->load->view('view_header', $data);
			$this->load->view('view_login', $data);
			$this->load->view('view_footer', $data);
			return;
		}
		
		$this->session->set_userdata('userid', $person->id);
		$this->session->set_userdata('fullname', $person->fullname);
		redirect($this->session->userdata('onloginurl'));
	}

	function register()
	{
		$data = array();
		$this->load->model('Team_model');

		$data['email_addr'] = $this->session->userdata('email');
		$data['userurl'] = $this->session->userdata('userurl');

		if (empty($data['email_addr']) && empty($data['userurl']))
		{
			redirect('login');
			exit();
		}

		if ($this->input->post('action') == 'register')
		{
			$this->form_validation->set_rules('team_name', 'Team Name', 'trim|required');
			$this->form_validation->set_rules('team_website', 'Web Site', 'trim');
			$this->form_validation->set_rules('dob_month', 'DOB Month', 'trim|required');
			$this->form_validation->set_rules('dob_day', 'DOB Day', 'trim|required');
			$this->form_validation->set_rules('dob_year', 'DOB Year', 'trim|required');
			$this->form_validation->set_rules('team_addr1', 'Address', 'trim|required');
			$this->form_validation->set_rules('team_addr2', 'Address 2', 'trim');
			$this->form_validation->set_rules('team_city', 'City', 'trim|required');
			$this->form_validation->set_rules('team_state', 'State/Province', 'trim|required');
			$this->form_validation->set_rules('team_zip', 'Zip/Post Code', 'trim|required');
			$this->form_validation->set_rules('team_country', 'Country', 'trim|required');
			$personid = 0;

			$dob = sprintf("%s/%s/%s", $this->input->post('dob_month'), $this->input->post('dob_day'), $this->input->post('dob_year'));
			$file_upload = $this->_do_upload('badge_photo');

			$this->form_validation->set_rules('fullname', 'Full Name', 'trim|required');

			if ($this->form_validation->run() != FALSE && $file_upload !== FALSE)
			{
				$personid = $this->Person_model->add_person(
					$this->input->post('fullname'),
					$dob,
					$this->session->userdata('email'),
					$file_upload);
			}
			else
			{
				$data['error'] = $this->upload_errors.validation_errors();
			}

			if ($personid != 0)
			{
				$teamid = $this->Team_model->add_team(
					$this->input->post('team_name'),
					$this->input->post('team_website'),
					$this->input->post('team_addr1'),
					$this->input->post('team_addr2'),
					$this->input->post('team_city'),
					$this->input->post('team_state'),
					$this->input->post('team_zip'),
					$this->input->post('team_country')
				);

				$this->Team_model->add_team_member($teamid, $personid);

				$this->session->set_userdata('userid', $personid);
				$this->session->set_userdata('fullname', $this->input->post('fullname'));
				$this->session->set_flashdata('teamid', $teamid);
				redirect($this->session->userdata('onloginurl'));
			}
		}


		$this->load->view('view_header', $data);
		$this->load->view('view_register', $data);
		$this->load->view('view_footer', $data);
	}

	// set message
	function _set_message($msg, $val = '', $sub = '%s')
	{
		return str_replace($sub, $val, $this->lang->line($msg));
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

	function unique_email($email)
	{
		$current = $this->Person_model->get_person_by_email($email);
		if (!empty($email) && isset($current->id))
		{
			$this->form_validation->set_message('unique_email', 'The email address already exists as a user. Please enter another, or log in using that account.');
			return FALSE;
		}
		else
			return TRUE;
	}

}

