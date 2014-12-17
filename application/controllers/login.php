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
		
        if ($this->input->post('email_addr') !== FALSE)
        {
            $this->form_validation->set_rules('email_addr', 'Email Address', 'trim|required|valid_email');
            if ($this->form_validation->run() != FALSE)
            {
                $person = $this->Person_model->get_person_by_email($this->input->post('email_addr'));
                $email_addr = $this->input->post('email_addr');
                if (count($person) == 0)
                {
                    $data['error'] = 'There is no account with that username.';
                }
                else if ($this->Person_model->check_password($person, $this->input->post('password')) === FALSE)
                {
                    $data['error'] = 'That password is incorrect.';
                }
                else
                {
                    $this->session->set_userdata('userid', $person->id);
                    $this->session->set_userdata('fullname', $person->fullname);
                    redirect($this->session->userdata('onloginurl'));
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
    
    function forgot_password()
    {
		$data = array();

        if ($this->input->post('email_addr') !== FALSE)
        {
            $this->form_validation->set_rules('email_addr', 'Email Address', 'trim|required|valid_email');
            if ($this->form_validation->run() != FALSE)
            {
                $person = $this->Person_model->get_person_by_email($this->input->post('email_addr'));
                $email_addr = $this->input->post('email_addr');
                if (count($person) == 0)
                {
                    $data['error'] = 'There is no account with that username.';
                }
                else
                {
                    $code = $this->Person_model->create_login_code($person->id, site_url(array('login', 'change_password')));
                    $email_content = $this->load->view('email_login_code', array(
                        'userid'=>$person->id,
                        'code'=>$code),
                        TRUE);
                    
                    $email = $this->_get_email();
                    $email->to($this->input->post('email_addr'));
                    $email->subject('Reset your Robogames password');
                    $email->message($email_content);
                    $email->send();
                        
                    $data['success'] = 'An email has been sent to your email address containing instructions on how to reset your password.';
                }
            }	
            else
            {
                $data['error'] = validation_errors();
            }
        }

        $this->load->view('view_header', $data);
        $this->load->view('view_forgot_password', $data);
        $this->load->view('view_footer', $data);
    }
	
	function validate_code($id, $code)
	{
		$code_info = $this->Person_model->get_login_code_info($id, $code);
		$person = $this->Person_model->get_person_by_id($id);
		if (count($code_info) == 0 || count($person) == 0)
		{
			$data = array('error' => 'That login code is invalid.');
			
			$this->load->view('view_header', $data);
			$this->load->view('view_login', $data);
			$this->load->view('view_footer', $data);
			return;
		}

        $this->session->set_userdata('code', $code);
		$this->session->set_userdata('userid', $person->id);
		$this->session->set_userdata('fullname', $person->fullname);
		redirect($code_info->dest_url);
	}
    
    function change_password()
    {
		$data = array();
        
        if ($this->input->post('password') !== FALSE)
        {
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
            $this->form_validation->set_rules('password2', 'Password', 'trim|required');
            if ($this->form_validation->run() != FALSE)
            {
                if ($this->input->post('password') != $this->input->post('password2'))
                {
                    $data['error'] = 'Passwords do not match';
                }
                else
                {
                    $id = $this->session->userdata('userid');
                    $person = $this->Person_model->get_person_by_id($id);
                    $ok_to_change_password = FALSE;
                    
                    if (count($person) == 0)
                    {
                        $data['error'] = 'There is no account with that username.';
                    }
                    else if ($this->input->post('password_orig') !== FALSE)
                    {
                        if ($this->Person_model->check_password($person, $this->input->post('password')) === TRUE)
                        {
                            $ok_to_change_password = TRUE;
                        }
                        else
                        {
                            $data['error'] = 'Original password incorrect';
                        }
                    }
                    else if ($this->session->userdata('code') !== FALSE)
                    {
                        $code_info = $this->Person_model->get_login_code_info($id, $this->session->userdata('code'));
                        if (count($code_info) != 0)
                        {
                            $ok_to_change_password = TRUE;
                        }
                        else
                        {
                            $data['error'] = 'The email you clicked has expired.';
                        }
                    }
                    
                    if ($ok_to_change_password)
                    {
                        $this->Person_model->update_password($id, $this->input->post('password'));
                        $data['success'] = 'Password changed successfully';
                    }
                }
            }	
            else
            {
                $data['error'] = validation_errors();
            }
        }
        
        if ($this->session->userdata('code') !== FALSE)
        {
            $data['needs_password'] = FALSE;
        }
        
        $this->load->view('view_header', $data);
		$this->load->view('view_change_password', $data);
		$this->load->view('view_footer', $data);		
    }

	function register()
	{
		$data = array();
		$this->load->model('Team_model');

		$data['email_addr'] = $this->session->userdata('email');

		if (empty($data['email_addr']))
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
    
    function _get_email()
    {
        $this->load->library('email');
		if ($this->config->item('use_postmark') === TRUE)
		{
			$this->load->library('postmark');
			return $this->postmark;
		}
		else
		{
			$this->email->from('registration@robogames.net', 'RoboGames Registration');
            return $this->email;
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

