<?php

class Team_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		
	}
	
	function get_teams()
	{
		return $this->db->get('team', 10)->result();
	}
	
	function get_team($id)
	{
		return $this->db->get_where('team', array('id'=>$id), 1)->row();
	}
	
	function get_teams_for_person($personid)
	{
		return $this->db
			->select('team.id, name, url')
			->from('team')
			->join('team_members', 'team.id = team_members.team')
			->where('team_members.person', $personid)
			->get()->result();
	}
	
	function get_team_members($team_id)
	{
		return $this->db
			->select('person.id, fullname, picture_url, thumbnail_url, email, dob')
			->from('person')
			->join('team_members', 'person.id = team_members.person')
			->where('team_members.team', $team_id)
			->get()->result();			
	}
	
	function get_team_members_as_id_desc($team_id)
	{
		$ret = array();
		foreach ($this->get_team_members($team_id) as $row)
		{
			$ret[$row->id] = $row->fullname;
		}
		return $ret;
	}

	
	function get_team_entries($team_id)
	{
		return $this->db
			->select('id, name, description, thumbnail_url, team')
			->from('entry')
			->where('team', $team_id)
			->get()->result();			
	}
	function get_team_entries_with_event_division($team_id, $eventid)
	{
		return $this->db
			->select('entry.id, name, entry.description, entry.thumbnail_url, entry.team, event_divisions.id as event_division')
			->from('entry')
			->join('event_divisions', 'entry.default_division = event_divisions.division and event_divisions.event = '.$eventid, 'left')
			->where('team', $team_id)
			->get()->result();			
	}
	
	
	function get_team_entry_event_divisions($team_id, $event_id)
	{
		$res = $this->db
			->select('entry.id as entry, event_divisions.id as event_division')
			->from('entry')
			->join('event_entries', 'event_entries.entry = entry.id')
			->join('event_divisions', 'event_divisions.id = event_entries.event_division')
			->where('entry.team', $team_id)
			->where('event_divisions.event', $event_id)
			->get()->result();
			
		$ret = array();
		foreach ($res as $row)
		{
			$ret[$row->entry] = $row->event_division;
		}
		return $ret; 
	}
	
	function get_team_entry_drivers($team_id, $event_id)
	{
		$res = $this->db
			->select('entry.id as entry, event_entries.driver as driver')
			->from('entry')
			->join('event_entries', 'event_entries.entry = entry.id')
			->join('event_divisions', 'event_divisions.id = event_entries.event_division')
			->where('entry.team', $team_id)
			->where('event_divisions.event', $event_id)
			->get()->result();
			
		$ret = array();
		foreach ($res as $row)
		{
			$ret[$row->entry] = $row->driver;
		}
		return $ret; 
	}
	
	function add_team($name, $website, $addr1, $addr2, $city, $state, $zip, $country)
	{
		$data = array(
				'name' => $name,
				'url' => $website,
				'addr1' => $addr1,
				'addr2' => $addr2,
				'city' => $city,
				'state' => $state,
				'zip' => $zip,
				'country' => $country,
				'description' => '',
				);

		$this->db->set('created', 'now()', FALSE);
		$this->db->insert('team', $data);
		return $this->db->insert_id();
	}
	
	function add_team_member($teamid, $personid)
	{
		$data = array(
			'team' => $teamid,
			'person' => $personid,
		);
		$this->db->insert('team_members', $data);
	}
}
