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
		$data = array();
		$data['entry'] = $this->Entry_model->get_entry($id);

		$this->load->view('view_header');
		if (count($data['entry']) > 0)		
			$this->load->view('view_entry', $data);
		else
			$this->load->view('view_error', array('error' => 'That entry does not exist'));
		$this->load->view('view_footer');		
	}
}
?>