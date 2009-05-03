<?php
class Entry extends Controller
{
	
	function Entry()
	{
		parent::Controller();

		$this->load->model('Entry_model');
	}
	
	
	function View($id)
	{
		$this->load->model(array('Team_model', 'Event_registration_model'));
		$data = array();
		$data['entry'] = $this->Entry_model->get_entry($id);
		$data['team'] = $this->Team_model->get_team($data['entry']->team);
		$data['events'] = $this->Event_registration_model->get_events_for_entry($id);

		$this->load->view('view_header');
		if (count($data['entry']) > 0)		
			$this->load->view('view_entry', $data);
		else
			$this->load->view('view_error', array('error' => 'That entry does not exist'));
		$this->load->view('view_footer');	
	}
}
?>