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
		$data['event_header'] = $this->load->view('view_event_header', $data, true);

		$this->load->view('view_header');		
		$this->load->view('view_event', $data);
		$this->load->view('view_footer');		
	}
	
	function Register($id)
	{
		$this->load->library(array('session', 'form_validation'));
		$this->load->helper(array('form', 'html'));
		
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
				"3"=>""
				);
		

		foreach ($people as $key=>$heading)
		{
			$this->form_validation->set_rules("person[$key][fullname]", "Full Name", 'trim');
			$this->form_validation->set_rules("person[$key][email]", "Email Address", 'trim|valid_email');
		}
		$this->form_validation->set_rules('person[you][fullname]', 'Full Name', 'trim|required');
		$this->form_validation->set_rules('person[you][email]', 'Email Address', 'trim|required|valid_email');
		
		if ($this->form_validation->run() != FALSE)
		{
			$array = $this->input->post('person');
			var_dump($array);
			//redirect(array('event', 'viewregistration', $id));
			return;			
		}
		
		$data = array();		
		$data['event'] = $this->Event_model->get_event($id);
		$data['event_divisions'] = $this->Event_model->get_event_divisions($id);

		
		foreach ($people as $key=>$value)
		{
			$data['person'][$key]['heading'] = $value;
			$data['person'][$key]['fullname'] = set_value("person[$key][fullname]", '');
			$data['person'][$key]['email'] = set_value("person[$key][email]", '');
			$data['person'][$key]['picture'] = set_value("person[$key][picture]", site_url('images/nopicture.png'));
			$data['person'][$key]['adult'] = set_value("person[$key][adult]");
		}

		if ($this->session->userdata('openid_fullname') !== FALSE && empty($data['person']['you']['fullname']))
			$data['person']['you']['fullname'] = $this->session->userdata('openid_fullname');
		if ($this->session->userdata('openid_email') !== FALSE && empty($data['person']['you']['email']))
			$data['person']['you']['email'] = $this->session->userdata('openid_email');
		
		$data['event_header'] = $this->load->view('view_event_header', $data, true);
		$this->load->view('view_header');	
		$this->load->view('view_event_register', $data);
		$this->load->view('view_footer');
	}	
	
}
