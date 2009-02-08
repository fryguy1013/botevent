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
		return $this->db
			->select('divisions.id as id, divisions.name as name, event, division, description, ruleurl, maxentries')
			->from('event_divisions')
			->join('divisions', 'divisions.id = event_divisions.division')
			->where('event', $id)
			->get()->result();
	}
	
	function get_event_divisions_with_counts($event)
	{
		return $this->db
			->select('event_divisions.id as id, divisions.name as name, event, division, description, ruleurl, maxentries, price')
			->select('COALESCE(event_division_entries.ct, 0) as ct', FALSE)
			->from('event_divisions')
			->join('divisions', 'divisions.id = event_divisions.division')
			->join('event_division_entries', 'event_division_entries.event_division = event_divisions.id', 'left')
			->where('event_divisions.event', $event)
			->get()->result();
	}
	
	function get_event_entries($id, $division)
	{
		return $this->db
			->select('entry.id, entry.name, entry.description, entry.thumbnail_url, team.name as teamname, team.id as teamid, event_registrations.status')
			->from('entry')
			->join('event_entries', 'event_entries.entry = entry.id')
			->join('event_registrations', 'event_entries.event_registration = event_registrations.id')
			->join('team', 'team.id = entry.team')
			->where('event_entries.event_division', $division)
			->get()->result();
	}
	
	function get_event_entries_grouped($id)
	{
		$entries = $this->db
			->select('entry.id, entry.name, entry.description, entry.thumbnail_url, event_registration, divisions.name as divisionname')
			->from('entry')
			->join('event_entries', 'event_entries.entry = entry.id')
			->join('event_registrations', 'event_entries.event_registration = event_registrations.id')
			->join('event_divisions', 'event_divisions.id = event_entries.event_division')
			->join('divisions', 'divisions.id = event_divisions.division')
			->where('event_registrations.event', $id)
			->get()->result();
		
		$ret = array();
		foreach ($entries as $entry)
		{
			if (!isset($ret[$entry->event_registration]))
				$ret[$entry->event_registration] = array();
			$ret[$entry->event_registration][] = $entry;
		}
		return $ret;
	}

	function get_event_people_grouped($id)
	{
		$entries = $this->db
			->select('person.id, person.fullname, person.picture_url, event_registration')
			->from('person')
			->join('event_people', 'event_people.person = person.id')
			->join('event_registrations', 'event_people.event_registration = event_registrations.id')
			->where('event_registrations.event', $id)
			->get()->result();
		
		$ret = array();
		foreach ($entries as $entry)
		{
			if (!isset($ret[$entry->event_registration]))
				$ret[$entry->event_registration] = array();
			$ret[$entry->event_registration][] = $entry;
		}
		return $ret;
	}
	
	function get_event_registration_by_team($teamid)
	{
		return $this->db
			->select('event_registrations.id, event_registrations.team, event_registrations.status, team.name as teamname, team.id as teamid, event_registrations.event as event')
			->from('event_registrations')
			->join('team', 'team.id = event_registrations.team')
			->where('event_registrations.team', $teamid)
			->get()->row();
	}
	
	function get_event_registration($id)
	{
		return $this->db
			->select('event_registrations.id, event_registrations.team, event_registrations.status, team.name as teamname, team.id as teamid, event_registrations.event as event')
			->from('event_registrations')
			->join('team', 'team.id = event_registrations.team')
			->where('event_registrations.id', $id)
			->get()->row();
	}	

	function get_registration_entries($id)
	{
		return $this->db
			->select('entry.id, entry.name, entry.description, entry.thumbnail_url, event_registration, divisions.name as divisionname')
			->from('entry')
			->join('event_entries', 'event_entries.entry = entry.id')
			->join('event_registrations', 'event_entries.event_registration = event_registrations.id')
			->join('event_divisions', 'event_divisions.id = event_entries.event_division')
			->join('divisions', 'divisions.id = event_divisions.division')
			->where('event_registrations.id', $id)
			->get()->result();
	}	
	function get_registration_people($id)
	{
		return $this->db
			->select('person.id, person.fullname, person.picture_url, event_registration, person.thumbnail_url')
			->from('person')
			->join('event_people', 'event_people.person = person.id')
			->where('event_registration', $id)
			->get()->result();	
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

	function get_event_registrations($id)
	{
		return $this->db
			->select('event_registrations.id, event_registrations.team, event_registrations.status, team.name as teamname, team.id as teamid')
			->from('event_registrations')
			->join('team', 'team.id = event_registrations.team')
			->where('event', $id)
			->get()->result();
	}



	function create_registration($event_id, $team_id, $captain, $registration_people, $registration_entries)
	{
		$registration_id = $this->add_new_registration($event_id, $team_id, $captain);
		
		foreach ($registration_people as $person)
			$this->add_person_to_registration($registration_id, $person['id']);

		foreach ($registration_entries as $entry)
			$this->add_entry_to_registration($registration_id, $entry['id'], $entry['division']);
			
		return $registration_id;
	}
	
	function add_new_registration($event_id, $team_id, $captain)
	{
		$data = array(
			'team' => $team_id,
			'event' => $event_id,
			'status' => 'new',
			'captain' => $captain
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
	
	function update_reg_status($registration_id, $status)
	{
		$data = array(
			'status' => $status
		);
		
		$this->db
			->where('id', $registration_id)
			->update('event_registrations', $data);
	}
	
}
