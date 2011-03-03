<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	function __construct()
	{
		parent::__construct();	
	}
	
	function index()
	{
		//redirect('event/all');
		echo "hello world";
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */