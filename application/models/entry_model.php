<?php

class Entry_model extends Model
{
	function Entry_model()
	{
		parent::Model();
		
	}
	
	function add_entry($name, $team)
	{
		$data = array(
				'name' => $name,
				'team' => $team,
				);
		
		$this->db->insert('entry', $data);
		return $this->db->insert_id();
	}
}
