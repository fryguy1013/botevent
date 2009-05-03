<?php

class Event_model extends Model
{
	function Event_model()
	{
		parent::Model();
		
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
			->select('event_divisions.id as event_division, divisions.id as id, divisions.name as name, event, division, description, ruleurl, maxentries, price')
			->from('event_divisions')
			->join('divisions', 'divisions.id = event_divisions.division')
			->where('event', $id)
			->order_by('divisions.name')
			->get()->result();
	}
	
	function get_event_divisions_counts($event)
	{
		//select `${prefix}event_entries`.`event_division` AS `event_division`, count(0) AS `ct`
		//from `${prefix}event_entries`
		//group by `${prefix}event_entries`.`event_division`
		$cts = $this->db
			->select('event_entries.event_division, COUNT(*) as ct', FALSE)
			->from('event_entries')
			->join('event_divisions', 'event_divisions.id = event_division')
			->join('event_registrations', 'event_entries.event_registration = event_registrations.id')
			->where('event_divisions.event', $event)
			->where('event_registrations.status !=', 'withdrawn')
			->group_by('event_division')
			->get()->result();
		$ret = array();
		foreach ($cts as $ct)
		{
			$ret[$ct->event_division] = $ct->ct;
		}
		return $ret;
	}

	
	function get_event_entries($division)
	{
		return $this->db
			->select('entry.id, entry.name, entry.description, entry.thumbnail_url, team.name as teamname, team.id as teamid, event_registrations.status')
			->from('entry')
			->join('event_entries', 'event_entries.entry = entry.id')
			->join('event_registrations', 'event_entries.event_registration = event_registrations.id')			
			->join('team', 'team.id = entry.team')
			->where('event_entries.event_division', $division)
			->where('event_registrations.status !=', 'withdrawn')
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
			->select('person.id, person.fullname, person.picture_url, person.thumbnail_url, event_registration')
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
	

	
	function get_event_divisions_as_id_desc($id)
	{
		$ret = array();
		foreach ($this->get_event_divisions($id) as $row)
		{
			$ret[$row->event_division] = $row->name;
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

	function get_raw_division_from_event_division($event_division)
	{
		$row = $this->db->select('division')->from('event_divisions')->where('id', $event_division)->get()->row();
		return $row->division;
	}



	


	function create_event($data, $divisions)
	{	
		$this->db->insert('event', $data);
		$eventid = $this->db->insert_id();
		
		foreach ($divisions as $div)
		{
			add_division_to_event($eventid, $div);
		}
	}
	function add_division_to_event($eventid, $div)
	{
		$data = array(
			'event' => $eventid,
			'division' => $div['division'],
			'description' => $div['description'],
			'ruleurl' => $div['ruleurl'],
			'maxentries' => $div['maxentries'],
			'price' => $div['price']
		);
		$this->db->insert('event_divisions', $data);
	}
	
	function create_division($name)
	{		
		$this->db->insert('divisions', array('name' => $name));
		return $this->db->insert_id();
	}
	
}
