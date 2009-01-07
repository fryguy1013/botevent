<?php

class Event_model extends Model
{
	function Event_model()
	{
		parent::Model();
		
	}
	
	function reset()
	{
		$this->db->empty_table('entry');
		$this->db->empty_table('person');
		$this->db->empty_table('team');
		$this->db->empty_table('event_entries');
		$this->db->empty_table('event_people');
		$this->db->empty_table('event_registrations');
		
	}
	
	function get_events()
	{
		return $this->db->get('event', 10)->result();
	}
	
	function get_event($id)
	{
		return $this->db->get_where('event', array('id'=>$id), 1)->row();
	}
	
	function get_event_divisions($id)
	{
		$this->db->select('divisions.id as id, divisions.name as name, event, division, description, ruleurl, maxentries');
		$this->db->from('event_divisions');
		$this->db->join('divisions', 'divisions.id = event_divisions.division');
		$this->db->where('event', $id);
		return $this->db->get()->result();
	}
	
	function get_event_divisions_with_counts($event)
	{
		return $this->db
			->select('event_divisions.id as id, divisions.name as name, event, division, description, ruleurl, maxentries')
			->select('COALESCE(event_division_entries.ct, 0) as ct', FALSE)
			->from('event_divisions')
			->join('divisions', 'divisions.id = event_divisions.division')
			->join('event_division_entries', 'event_division_entries.event_division = event_divisions.id', 'left')
			->where('event_divisions.event', $event)
			->get()->result();
		
	//COALESCE(
		/*
		$this->db->select('count(event_entries.id) as ct', FALSE);
		$this->db->select('event_divisions.id as id, divisions.name as name, event_divisions.event, event_divisions.description, event_divisions.ruleurl, event_divisions.maxentries');
		$this->db->from('event_entries');
		$this->db->join('event_divisions', 'event_divisions.id = event_entries.event_division');
		$this->db->join('divisions', 'divisions.id = event_divisions.division');
		$this->db->where('event_divisions.event', $event);
		$this->db->group_by('event_entries.event_division');
		return $this->db->get()->result();
		*/
	}
	
	function get_event_entries($id, $division)
	{
		$this->db
			->select('entry.id, entry.name, entry.description, entry.thumbnail, team.name as teamname, team.id as teamid, event_registrations.status')
			->from('entry')
			->join('event_entries', 'event_entries.entry = entry.id')
			->join('event_registrations', 'event_entries.event_registration = event_registrations.id')
			->join('team', 'team.id = entry.team')
			->where('event_entries.event_division', $division);
		return $this->db->get()->result();
	}
	
	function get_event_divisions_as_id_desc($id)
	{
		$ret = array();
		foreach ($this->get_event_divisions($id) as $row)
		{
			$ret[$row->id] = $row->name;
		}
		return $ret;
	}
	
	function get_division_info($id)
	{
		return $this->db
			->select('divisions.name')
			->from('divisions')
			->join('event_divisions', 'event_divisions.division = divisions.id')
			->where('event_divisions.id', $id)
			->get()->row();
	}


	function create_registration($event_id, $team_id, $registration_people, $registration_entries)
	{
		$registration_id = $this->add_new_registration($event_id, $team_id);
		
		foreach ($registration_people as $person)
			$this->add_person_to_registration($registration_id, $person['id']);

		foreach ($registration_entries as $entry)
			$this->add_entry_to_registration($registration_id, $entry['id'], $entry['division']);
	}
	
	function add_new_registration($event_id, $team_id)
	{
		$data = array(
			'team' => $team_id,
			'event' => $event_id,
			'status' => 'new',
		);
		
		$this->db->insert('event_registrations', $data);
		return $this->db->insert_id();
	}
	
	function add_person_to_registration($registration_id, $person)
	{
		$data = array(
			'person' => $person,
			'event_registration' => $registration_id
		);
		$this->db->insert('event_people', $data);
		return $this->db->insert_id();
	}

	function add_entry_to_registration($registration_id, $entry, $event_division)
	{
		$data = array(
			'entry' => $entry,
			'event_division' => $event_division,
			'event_registration' => $registration_id
		);
		$this->db->insert('event_entries', $data);
		return $this->db->insert_id();
	}
	
}
