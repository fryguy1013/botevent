<?php
class Team extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();

		$this->load->model(array('Team_model', 'Person_model'));

		if ($this->config->item('requires_login') === TRUE)
			$this->Person_model->check_login();
	}
	
	
	function View($id)
	{
		$this->load->model(array('Entry_model', 'Event_registration_model'));
		$data = array();
		$data['team'] = $this->Team_model->get_team($id);
		$data['members'] = $this->Team_model->get_team_members($id);
		$data['entries'] = $this->Team_model->get_team_entries($id);
		$data['events'] = $this->Event_registration_model->get_events_for_team($id);

		$this->load->view('view_header');
		if (count($data['team']) > 0)		
			$this->load->view('view_team', $data);
		else
			$this->load->view('view_error', array('error' => 'That team does not exist'));
		$this->load->view('view_footer');

	}
}
?>