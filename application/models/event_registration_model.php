<?php

class Event_registration_model extends Model
{
	function Event_registration_model()
	{
		parent::Model();
		
	}
	

	function get_event_registrations($event_id)
	{
		return $this->db
			->select('event_registrations.id, event_registrations.team, event_registrations.status, team.name as teamname, team.id as teamid')
			->from('event_registrations')
			->join('team', 'team.id = event_registrations.team')
			->where('event', $event_id)
			->where('event_registrations.status !=', 'withdrawn')
			->order_by('team.name')
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
			->where('event_registrations.status !=', 'withdrawn')
			->get()->row();
	}
	
	function get_event_registration($registration_id)
	{
		return $this->db
			->select('event_registrations.id, event_registrations.team, event_registrations.status, team.name as teamname, team.id as teamid, event_registrations.event as event')
			->from('event_registrations')
			->join('team', 'team.id = event_registrations.team')
			->where('event_registrations.id', $registration_id)
			->get()->row();
	}	

	function get_registration_entries($registration_id)
	{
		return $this->db
			->select('entry.id, entry.name, entry.description, entry.thumbnail_url, event_registration, divisions.name as divisionname')
			->from('entry')
			->join('event_entries', 'event_entries.entry = entry.id')
			->join('event_registrations', 'event_entries.event_registration = event_registrations.id')
			->join('event_divisions', 'event_divisions.id = event_entries.event_division')
			->join('divisions', 'divisions.id = event_divisions.division')
			->where('event_registrations.id', $registration_id)
			->get()->result();
	}	
	function get_registration_people($registration_id)
	{
		return $this->db
			->select('person.id, person.fullname, person.picture_url, event_registration, person.thumbnail_url')
			->from('person')
			->join('event_people', 'event_people.person = person.id')
			->where('event_registration', $registration_id)
			->get()->result();	
	}
	function get_events_for_entry($entry_id)
	{
		return $this->db
			->select('event.id, event.name, event.startdate as date, divisions.name as division, event_entries.event_division, event_registrations.status')
			->from('event_entries')
			->join('event_divisions', 'event_divisions.id = event_entries.event_division')
			->join('divisions', 'event_divisions.division = divisions.id')
			->join('event', 'event_divisions.event = event.id')
			->join('event_registrations', 'event_registrations.id = event_entries.event_registration')
			->where('event_entries.entry', $entry_id)
			->where('event_registrations.status !=', 'withdrawn')
			->orderby('event.startdate desc')
			->get()->result();	
	}
	function get_events_for_team($team_id)
	{
		$rows = $this->db
			->select('event.id as eventid, event.name eventname, event.startdate as date, divisions.name as division, event_entries.event_division, event_registrations.status,
					  entry.id, entry.name')
			->from('event_entries')
			->join('entry', 'entry.id = event_entries.entry')
			->join('event_divisions', 'event_divisions.id = event_entries.event_division')
			->join('divisions', 'event_divisions.division = divisions.id')
			->join('event', 'event_divisions.event = event.id')
			->join('event_registrations', 'event_registrations.id = event_entries.event_registration')
			->where('event_registrations.team', $team_id)
			->where('event_registrations.status !=', 'withdrawn')
			->orderby('event.startdate desc, event.id')
			->get()->result();
		$ret = array();
		foreach ($rows as $row)
		{
			if (!isset($ret[$row->eventid]))
				$ret[$row->eventid] = array(
					'id' => $row->eventid,
					'name' => $row->eventname,
					'date' => $row->date,
					'status' => $row->status,
					'entries' => array(),
				);
			$ret[$row->eventid]['entries'][] = $row;
		}
		return $ret;
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
		// we must remove the other registrations, first
		$this->db
			->where('team', $team_id)
			->update('event_registrations', array('status' => 'withdrawn'));
	
		// now add the new one
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
