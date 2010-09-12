<?php

class Install extends Controller {

	function Install()
	{
		parent::Controller();
		
		$this->load->model('Install_model');
	}
	
	// Index
	function index()
	{
		$data = array();
		$data['dbversion'] = $this->Install_model->get_database_version();
		
		$i=0;
		while (method_exists($this->Install_model, 'commit_'.($i+1)) &&
			method_exists($this->Install_model, 'rollback_'.($i+1)))
			$i++;
		$data['maxupdateversion'] = $i;

		$this->load->view('view_header');
		$this->load->view('view_install', $data);
		$this->load->view('view_footer');
	}
	
	function commit($rev)
	{
		$cur_version = $this->Install_model->get_database_version();

		if ($rev != $cur_version + 1)
		{
		    $this->load->view('view_header');
			$this->load->view('view_error', array('error' => "Unable to commit to the wrong version (now $cur_version. Going to $rev)"));
			$this->load->view('view_footer');
			return;
        }
        
        $fn = 'commit_'.$rev;
		$this->Install_model->$fn();
		redirect(site_url(array('install')));
	}

	function rollback($rev)
	{
		if ($this->config->item('development_environment') !== TRUE)
		{
			show_error('This action requires a development build');
			die();
		}
		
		$cur_version = $this->Install_model->get_database_version();

		if ($rev != $cur_version - 1)
		{
		    $this->load->view('view_header');
			$this->load->view('view_error', array('error' => "Unable to rollback to the wrong version (now $cur_version. Going to $rev)"));
			$this->load->view('view_footer');
			return;
        }

        $fn = 'rollback_'.($rev+1);
		$this->Install_model->$fn();
		redirect(site_url(array('install')));
	}

	
	function Reset()
	{
		if ($this->config->item('development_environment') !== TRUE)
		{
			show_error('This action requires a development build');
			die();
		}

		$this->load->model('Install_model');
		$this->Install_model->reset();
		redirect(site_url(array('install')));
	}
}

