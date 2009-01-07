<?php

class Team_model extends Model
{
	function Team_model()
	{
		parent::Model();
		
	}
	
	function get_teams()
	{
		return $this->db->get('team', 10)->result();
	}
	
	function get_team($id)
	{
		return $this->db->get_where('team', array('id'=>$id), 1)->row();
	}
	
	function add_team($name, $website)
	{
		$data = array(
				'name' => $name,
				'url' => $website,
				);

		$this->db->set('created', 'now()', FALSE);
		$this->db->insert('team', $data);
		return $this->db->insert_id();
	}
	
	function set_team_captain($team, $captain)
	{
		$data = array(
			'captain' => $captain,
		);
    	$this->db->update('team', $data, array('id' => $team));
	}
}
