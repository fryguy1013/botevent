<?php

class Event_registration_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		
	}
	

	function get_event_registrations($event_id)
	{
		return $this->db
			->select('event_registrations.id, event_registrations.team, event_registrations.status,
			          team.name as teamname, team.id as teamid, team.country as teamcountry, event_registrations.due,
					  event_payments.paid')
			->from('event_registrations')
			->join('team', 'team.id = event_registrations.team')
			->join('event_payments', 'event_payments.team = event_registrations.team and event_payments.event = event_registrations.event', 'left')
			->where('event_registrations.event', $event_id)
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
			->select('event_registrations.id, event_registrations.team, event_registrations.status, team.name as teamname,
					  team.id as teamid, event_registrations.event as event, event_registrations.due')
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
	
	function get_registration_captain_email($reg_id)
	{
		$row = $this->db
			->select('email')
			->from('person')
			->join('event_people', 'event_people.person = person.id')
			->where('event_people.event_registration', $reg_id)
			->get()->row();
		return $row->email;
	}
		
	function get_safety_of_registration($event_id, $team_id, $registration_entries)
	{
		$allowed_db = $this->db
			->select('event_divisions.id, event_divisions.maxentries')
			->from('event_divisions')
			->where('event_divisions.event', $event_id)
			->get()->result();
		
		$cts = $this->db
			->select('event_entries.event_division, COUNT(*) as ct', FALSE)
			->from('event_entries')
			->join('event_divisions', 'event_divisions.id = event_division')
			->join('event_registrations', 'event_entries.event_registration = event_registrations.id')
			->where('event_divisions.event', $event_id)
			->where('event_registrations.status !=', 'withdrawn')
			->where('event_registrations.team !=', $team_id)
			->group_by('event_entries.event_division')
			->get()->result();
		$allowed = array();
		foreach ($allowed_db as $a)
			$allowed[$a->id] = $a->maxentries == 0 ? 999999 : $a->maxentries;
		
		foreach ($cts as $ct)
			$allowed[$ct->event_division] -= $ct->ct;
		
		$ret = array('safe'=>true, 'fulldivisions'=>array());
		foreach ($registration_entries as $entry)
		{
			if ($allowed[$entry['division']] <= 0)
			{
				$ret['safe'] = FALSE;
				$ret['fulldivisions'][] = $entry['division'];
			}
			
			$allowed[$entry['division']]--;
		}
		
		return $ret;
	}


	function create_registration($event_id, $team_id, $captain, $registration_people, $registration_entries)
	{
		// we must remove the other registrations, first
		$this->unregister_team_for_event($event_id, $team_id);

		// make sure that we can safely register
		$safety = $this->get_safety_of_registration($event_id, $team_id, $registration_entries);
		if (!$safety['safe'])
			return FALSE;
		
		$registration_id = $this->add_new_registration($event_id, $team_id, $captain);
		
		foreach ($registration_people as $person)
			$this->add_person_to_registration($registration_id, $person['id']);

		foreach ($registration_entries as $entry)
			$this->add_entry_to_registration($registration_id, $entry['id'], $entry['division']);
			
		return $registration_id;
	}
	
	function unregister_team_for_event($event_id, $team_id)
	{
		$this->db
			->where('team', $team_id)
			->where('event', $event_id)
			->update('event_registrations', array('status' => 'withdrawn'));
	}
	
	function add_new_registration($event_id, $team_id, $captain)
	{
		// now add the new one
		$data = array(
			'team' => $team_id,
			'event' => $event_id,
			'status' => 'new',
			'captain' => $captain,
			'due' => 0
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
		$ret = $this->db->insert_id();
		
		// now add to the cost
		$event_division = $this->db->get_where('event_divisions', array('id' => $event_division))->row();
		$this->db
			->set('due', 'due + '.$event_division->price, false)
			->update('event_registrations', array(), array('id' => $registration_id));
		
		return $ret;
	}
	
	function update_reg_status($registration_id, $status, $amount_due)
	{
		$data = array(
			'status' => $status,
			'due' => $amount_due
		);
		
		$this->db
			->where('id', $registration_id)
			->update('event_registrations', $data);
	}
	
	function update_payment($event_id, $team_id, $amount_paid)
	{
		$this->db
			->where('event', $event_id)
			->where('team', $team_id)
			->delete('event_payments');
			
		$this->db->insert('event_payments', array(
			'event' => $event_id,
			'team' => $team_id,
			'paid' => $amount_paid,
		));
	}
	
}
