<?php

class Login extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		parse_str($_SERVER['QUERY_STRING'],$_GET);

		$this->load->library(array('session', 'form_validation', 'openid'));
		$this->load->model('Person_model');

		$this->upload_errors = "";

		//$this->output->enable_profiler(TRUE);

	}

	// Index
	function index()
	{
		$data = array();

		$user_id = $this->input->post('url');
		if (!empty($user_id))
		{
			$this->_check_openid($user_id);
		}

		if ($this->input->post('action') == 'email_password')
		{
			$data['show_email_only'] = TRUE;

			$this->form_validation->set_rules('email_addr', 'Email Address', 'trim|required|valid_email');
			$this->form_validation->set_rules('login_type', 'Login Type', 'trim|required');
			$this->form_validation->set_rules('email_password', 'Password', 'trim');

			if ($this->form_validation->run() != FALSE)
			{
				$person = $this->Person_model->get_person_by_email($this->input->post('email_addr'));
				if ($this->input->post('login_type') == 'new_member')
				{
					if (count($person) == 0)
					{
						$this->session->set_userdata('openid_email', $this->input->post('email_addr'));
						redirect(site_url(array('login','register')));
						return;
					}
					else
					{
						$data['error'] = "That email address is already in use. Did you mean to login instead?";
					}
				}
				else
				{
					$email_addr = $this->input->post('email_addr');
					$password = $this->input->post('email_password');
					if (count($person) == 0)
					{
						$data['error'] = "That email address does not exist. Did you mean to create a new account?";
					}
					else if (empty($person->password) && !empty($person->idurl))
					{
						$this->_check_openid($person->idurl);
					}
					else if (empty($person->password))
					{
						$data['error'] = "Your email address is associated with a member of a team. In the future, you will be able to log in to this account.";
						$data['show_email_only'] = FALSE;
					}
					else if ($person->password != $this->Person_model->generate_password($password, $email_addr, $person->passwordsalt))
					{
						$data['error'] = "The password you have typed does not exist. Try again.";
					}
					else
					{
						$this->session->set_userdata('userid', $person->id);
						$this->session->set_userdata('fullname', $person->fullname);
						redirect($this->session->userdata('onloginurl'));
						die();
					}
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

	function register()
	{
		$data = array();
		$this->load->model('Team_model');

		$data['email_addr'] = $this->session->userdata('openid_email');
		$data['userurl'] = $this->session->userdata('userurl');
		$data['openid_fullname'] = $this->session->userdata('openid_fullname');
		$data['openid_email'] = $this->session->userdata('openid_email');

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
			if (empty($data['userurl']))
			{
				// NO OPENID
				$this->form_validation->set_rules('fullname', 'Full Name', 'trim|required');
				$this->form_validation->set_rules('password1', 'Password', 'trim|required|matches[password2]');
				$this->form_validation->set_rules('password2', 'Password Confirmation', 'trim|required');

				if ($this->form_validation->run() != FALSE && $file_upload !== FALSE)
				{
					$personid = $this->Person_model->add_person(
						$this->input->post('fullname'),
						$dob,
						$this->session->userdata('openid_email'),
						$file_upload,
						"mailto:".$this->session->userdata('openid_email'),
						$this->input->post('password1'));
				}
				else
				{
					$data['error'] = $this->upload_errors.validation_errors();
				}
			}
			else
			{
				// HAS OPENID
				$this->form_validation->set_rules('fullname', 'Full Name', 'trim|required');
				$this->form_validation->set_rules('email_addr', 'Email Address', 'trim|required|valid_email|callback_unique_email');

				if ($this->form_validation->run() != FALSE && $file_upload !== FALSE)
				{
					$personid = $this->Person_model->add_person(
						$this->input->post('fullname'),
						$dob,
						$this->input->post('email_addr'),
						$file_upload,
						$this->session->userdata('userurl'),
						'');
				}
				else
				{
					$data['error'] = $this->upload_errors.validation_errors();
				}
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

	// Policy
	function policy()
	{
		$this->load->view('view_policy');
	}

	// set message
	function _set_message($msg, $val = '', $sub = '%s')
	{
		return str_replace($sub, $val, $this->lang->line($msg));
	}

	// Check
	function check()
	{
		if (!$this->openid->mode)
		{
			$data['msg'] = "Unknown";
		}
		else if ($this->openid->mode == 'cancel')
		{
			// cancelled authenticating
			$data['error'] = "Cancelled openid authentication";
		}
		else if ($this->openid->validate())
		{
			// success
			$person = $this->Person_model->get_person_by_url($this->openid->identity);

			if (count($person) > 0)
			{
				// they have logged into an account, so set the session var
				$this->session->set_userdata('userid', $person->id);
				$this->session->set_userdata('fullname', $person->fullname);

				redirect($this->session->userdata('onloginurl'));
				return;
			}
			else
			{
				// there isn't an account, so make them register first
				$this->session->set_userdata('userurl', $this->openid->identity);

				$attributes = $this->openid->getAttributes();
				if (isset($attributes['namePerson']))
					$this->session->set_userdata('openid_fullname', $attributes['namePerson']);
				else if (isset($attributes['namePerson/first']) && isset($attributes['namePerson/last']))
					$this->session->set_userdata('openid_fullname', $attributes['namePerson/first']." ".$attributes['namePerson/last']);
				if (isset($attributes['contact/email']))
					$this->session->set_userdata('openid_email', $attributes['contact/email']);

				//$this->output->set_output("invalid user: $openid");
				redirect(site_url(array('login', 'register')));
				return;
			}
		}
		else
		{
			// failure
			$data['error'] = "Failed authenticating to " . $this->openid->identity;
		}


		$this->load->view('view_header', $data);
		$this->load->view('view_login', $data);
		$this->load->view('view_footer', $data);
	}

	function _check_openid($url)
	{
		$this->session->set_userdata('userurl', '');
		$this->session->set_userdata('userid', '');
		$this->session->set_userdata('openid_fullname', '');
		$this->session->set_userdata('openid_email', '');

		try
		{
			if ($url == "https://www.google.com/accounts/o8/id")
				$this->openid->required = array('namePerson/first', 'namePerson/last', 'contact/email');
			else
				$this->openid->required = array('namePerson', 'contact/email');
			$this->openid->identity = $url;
			$this->openid->returnUrl = site_url(array('login', 'check'));
			$this->openid->trustRoot = base_url();
			redirect($this->openid->authUrl());
			return;
		}
		catch (ErrorException $e)
		{
			echo $e->getMessage();
			return;
		}
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

