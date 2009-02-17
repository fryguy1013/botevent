<?php

class Event_registration_model extends Model
{
	function Event_registration_model()
	{
		parent::Model();
		
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
	
	function get_event_registration_by_team($eventid, $teamid)
	{
		return $this->db
			->select('event_registrations.id, event_registrations.team, event_registrations.status, team.name as teamname, team.id as teamid, event_registrations.event as event')
			->from('event_registrations')
			->join('team', 'team.id = event_registrations.team')
			->where('event_registrations.team', $teamid)
			->where('event_registrations.event', $eventid)
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
