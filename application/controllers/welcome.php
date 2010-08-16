<?php

class Welcome extends Controller {

	function Welcome()
	{
		parent::Controller();	
	}
	
	function index()
	{
		redirect('event/all');
	}
	
	function version()
	{
	    phpinfo();
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */