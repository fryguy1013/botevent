<?php

class Event_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		
	}
	
	function get_events()
	{
		return $this->db->get('event')->result();
	}

	function get_future_events()
	{
		return $this->db
			->from('event')
			->where('startdate > NOW()', NULL, FALSE)
			->order_by('startdate', 'asc')
			->get()->result();
	}
	
	function get_past_events()
	{
		return $this->db
			->from('event')
			->where('startdate < NOW()', NULL, FALSE)
			->order_by('startdate', 'desc')
			->get()->result();
	}	
	
	function get_event($id)
	{
		return $this->db->get_where('event', array('id'=>$id), 1)->row();
	}
    
    function get_owners_of_event($event_id)
    {
        return $this->db
            ->select('person.fullname, person.email')
            ->from('event_owner')
            ->join('person', 'person.id = event_owner.person')
            ->where('event', $event_id)
            ->get()->result();
    }

    function is_person_owner_of_event($event_id, $person_id)
    {
        return $this->db
            ->select('id')
            ->from('event_owner')
            ->where('event', $event_id)
            ->where('person', $person_id)
            ->get()->row();
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
			->where('event_registrations.status !=', 'rejected')
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
			->select('entry.id, entry.name, entry.description, entry.thumbnail_url, team.name as teamname, team.id as teamid, team.country as teamcountry, event_registrations.status')
			->from('entry')
			->join('event_entries', 'event_entries.entry = entry.id')
			->join('event_registrations', 'event_entries.event_registration = event_registrations.id')			
			->join('team', 'team.id = entry.team')
			->where('event_entries.event_division', $division)
			->where('event_registrations.status !=', 'withdrawn')
			->where('event_registrations.status !=', 'rejected')
			->get()->result();
	}
	
	function get_event_entries_grouped($id)
	{
		$entries = $this->db
			->select('entry.id, entry.name, entry.description, entry.thumbnail_url, event_registration, divisions.name as divisionname, event_driver.fullname as driver')
			->from('entry')
			->join('event_entries', 'event_entries.entry = entry.id')
			->join('event_registrations', 'event_entries.event_registration = event_registrations.id')
			->join('event_divisions', 'event_divisions.id = event_entries.event_division')
			->join('divisions', 'divisions.id = event_divisions.division')
			->join('person as event_driver', 'event_driver.id = event_entries.driver')
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
			->select('divisions.name, event_divisions.description')
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
			$this->add_division_to_event($eventid, $div);
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
