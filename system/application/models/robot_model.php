<?php

class Robot_model extends Model
{
	function Robot()
	{
		parent::Model();
		
	}
	
	function get_robots()
	{
		return $this->db->get('robot', 10)->result();
	}
	
}

?>