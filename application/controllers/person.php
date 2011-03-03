<?php
class Person extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('Person_model');

		if ($this->config->item('requires_login') === TRUE)
			$this->Person_model->check_login();

	}
	
	
	function View($id)
	{
		$data = array();
		$data['person'] = $this->Person_model->get_person_by_id($id);

		$this->load->view('view_header');
		if (count($data['person']) > 0)		
			$this->load->view('view_person', $data);
		else
			$this->load->view('view_error', array('error' => 'That person does not exist'));
		$this->load->view('view_footer');		
	}
}