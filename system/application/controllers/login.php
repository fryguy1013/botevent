<?php

class Login extends Controller {

	function Login()
	{
		parent::Controller();
		
		$this->lang->load('openid', 'english');
		$this->load->library('openid');
		//$this->output->enable_profiler(TRUE);
	}
	
	// Index
	function index()
	{
		if ($this->input->post('action') == 'verify')
		{
			$user_id = $this->input->post('openid_identifier');
			
			$this->config->load('openid');      
			$req = $this->config->item('openid_required');
			$opt = $this->config->item('openid_optional');
			$policy = site_url($this->config->item('openid_policy'));
			$request_to = site_url($this->config->item('openid_request_to'));
			
			$this->openid->set_request_to($request_to);
			$this->openid->set_trust_root(base_url());
			$this->openid->set_args(null);
			$this->openid->set_sreg(true, $req, $opt, $policy);
			$this->openid->set_pape(false);
			$this->openid->authenticate($user_id);
			exit();
		}

		$data = array();
		$this->load->view('view_login', $data);		
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
		$this->load->library('session');
		
		$data = array();
		$this->config->load('openid');
		$request_to = site_url($this->config->item('openid_request_to'));
		
		$this->openid->set_request_to($request_to);
		$response = $this->openid->getResponse();
		
		switch ($response->status)
		{
			case Auth_OpenID_CANCEL:
				$data['msg'] = $this->lang->line('openid_cancel');
				break;
			case Auth_OpenID_FAILURE:
				$data['error'] = $this->_set_message('openid_failure', $response->message);
				break;
			case Auth_OpenID_SUCCESS:
				$openid = $response->getDisplayIdentifier();

				$sreg_resp = Auth_OpenID_SRegResponse::fromSuccessResponse($response);
				$sreg = $sreg_resp->contents();				
				
				/*
				$esc_identity = htmlspecialchars($openid, ENT_QUOTES);
				$data['success'] = $this->_set_message('openid_success', array($esc_identity, $esc_identity), array('%s','%t'));
				
				if ($response->endpoint->canonicalID) {
					$data['success'] .= $this->_set_message('openid_canonical', $response->endpoint->canonicalID);
				}
				*/				
			
				/*
				foreach ($sreg as $key => $value)
				{
					$data['success'] .= $this->_set_message('openid_content', array($key, $value), array('%s','%t'));
				}
				*/
				
				$this->session->set_userdata('userurl', $openid);
				
				if (isset($sreg['fullname']))
					$this->session->set_userdata('openid_fullname', $sreg['fullname']);
				if (isset($sreg['email']))
					$this->session->set_userdata('openid_email', $sreg['email']);
				
				redirect($this->session->userdata('onloginurl'));
				break;
		}
				
		$this->load->view('view_login', $data);   
	}
}

