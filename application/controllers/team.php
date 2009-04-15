<?php
class Team extends Controller
{
	
	function Team()
	{
		parent::Controller();

		$this->load->model('Team_model');		
	}
	
	
	function View($id)
	{
		$this->load->model(array('Entry_model', 'Event_registration_model'));
		$data = array();
		$data['team'] = $this->Team_model->get_team($id);

		$this->load->view('view_header');
		if (count($data['team']) > 0)		
			$this->load->view('view_team', $data);
		else
			$this->load->view('view_error', array('error' => 'That team does not exist'));
		$this->load->view('view_footer');

	}
}
?>