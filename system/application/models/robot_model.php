<?php

class Robot_model extends Model
{
	function Robot_model()
	{
		parent::Model();
		
	}
	
	function get_robots()
	{
		return $this->db->get('robot', 10)->result();
	}

	function get_robot($id)
	{
		return $this->db->get_where('robot', array('id'=>$id), 1)->row();
	}	
}
