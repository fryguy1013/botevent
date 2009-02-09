<?php
class Person extends Controller
{
	
	function Person()
	{
		parent::Controller();

		$this->load->model('Person_model');
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
?>