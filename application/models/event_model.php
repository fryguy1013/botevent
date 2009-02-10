<?php

class Event_model extends Model
{
	function Event_model()
	{
		parent::Model();
		
	}
	
	function div_helper($name, $price, $max)
	{
		return array(
			'division' => $this->create_division($name),
			'price' => $price,
			'maxentries' => $max,
			'description' => '',
			'ruleurl' => ''
		);
	}	
	function reset()
	{
		$prefix = $this->db->dbprefix;
		$this->db->query("DROP TABLE IF EXISTS `${prefix}divisions`");
		$this->db->query("
			CREATE TABLE `${prefix}divisions` (
			`id` int(10) unsigned NOT NULL auto_increment,
			`name` varchar(128) NOT NULL,
			PRIMARY KEY  (`id`)
			) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
		");
		$this->db->query("DROP TABLE IF EXISTS `${prefix}entry`");
		$this->db->query("
			CREATE TABLE `${prefix}entry` (
			  `id` int(11) NOT NULL auto_increment,
			  `name` varchar(128) NOT NULL,
			  `description` text NOT NULL,
			  `thumbnail_url` varchar(200) NOT NULL,
			  `team` int(11) NOT NULL,
			  `picture_url` varchar(200) NOT NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
		");
		$this->db->query("DROP TABLE IF EXISTS `${prefix}event`");
		$this->db->query("
			CREATE TABLE `${prefix}event` (
			  `id` int(10) unsigned NOT NULL auto_increment,
			  `name` varchar(128) NOT NULL,
			  `image` varchar(128) NOT NULL,
			  `description` text NOT NULL,
			  `startdate` datetime NOT NULL,
			  `enddate` datetime NOT NULL,
			  `registrationends` datetime NOT NULL,
			  `websiteurl` varchar(128) NOT NULL,
			  `smallimage` varchar(128) NOT NULL,
			  `location` varchar(128) NOT NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;		
		");
		$this->db->query("DROP TABLE IF EXISTS `${prefix}event_divisions`");
		$this->db->query("
			CREATE TABLE `${prefix}event_divisions` (
			  `id` int(10) unsigned NOT NULL auto_increment,
			  `event` int(10) unsigned NOT NULL,
			  `division` int(10) unsigned NOT NULL,
			  `description` text NOT NULL,
			  `ruleurl` varchar(128) NOT NULL,
			  `maxentries` int(10) unsigned NOT NULL,
			  `price` decimal(10,0) NOT NULL,
			  PRIMARY KEY  (`id`),
			  KEY `FK_eventclasses_1` USING BTREE (`event`)
			) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;		
		");
		$this->db->query("DROP TABLE IF EXISTS `${prefix}event_entries`");
		$this->db->query("
			CREATE TABLE `${prefix}event_entries` (
			  `id` int(10) unsigned NOT NULL auto_increment,
			  `event_division` int(10) unsigned NOT NULL,
			  `entry` int(10) unsigned NOT NULL,
			  `event_registration` int(10) unsigned NOT NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;
		");
		$this->db->query("DROP TABLE IF EXISTS `${prefix}event_people`");
		$this->db->query("
			CREATE TABLE `${prefix}event_people` (
			  `id` int(10) unsigned NOT NULL auto_increment,
			  `person` int(10) unsigned NOT NULL,
			  `event_registration` int(10) unsigned NOT NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;		
		");
		$this->db->query("DROP TABLE IF EXISTS `${prefix}event_registrations`");
		$this->db->query("
			CREATE TABLE `${prefix}event_registrations` (
			  `id` int(10) unsigned NOT NULL auto_increment,
			  `team` int(10) unsigned NOT NULL,
			  `event` int(10) unsigned NOT NULL,
			  `status` varchar(45) NOT NULL,
			  `captain` int(10) unsigned NOT NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;		
		");
		$this->db->query("DROP TABLE IF EXISTS `${prefix}person`");
		$this->db->query("
			CREATE TABLE `${prefix}person` (
			  `id` int(10) unsigned NOT NULL auto_increment,
			  `fullname` varchar(200) NOT NULL,
			  `email` varchar(200) NOT NULL,
			  `dob` varchar(200) NOT NULL,
			  `idurl` varchar(200) default NULL,
			  `picture_url` varchar(200) NOT NULL,
			  `password` varchar(200) NOT NULL,
			  `passwordsalt` varchar(200) NOT NULL,
			  `thumbnail_url` varchar(200) NOT NULL,
			  PRIMARY KEY  (`id`),
			  KEY `Index_2` (`idurl`),
			  KEY `Index_3` (`email`)
			) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
		");
		$this->db->query("DROP TABLE IF EXISTS `${prefix}team`");
		$this->db->query("
			CREATE TABLE `${prefix}team` (
			  `id` int(10) unsigned NOT NULL auto_increment,
			  `name` varchar(128) NOT NULL,
			  `url` varchar(128) NOT NULL,
			  `description` text NOT NULL,
			  `created` datetime NOT NULL,
			  `addr1` varchar(255) NOT NULL,
			  `addr2` varchar(255) NOT NULL,
			  `city` varchar(255) NOT NULL,
			  `state` varchar(255) NOT NULL,
			  `zip` varchar(255) NOT NULL,
			  `country` varchar(255) NOT NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
		");
		$this->db->query("DROP TABLE IF EXISTS `${prefix}team_members`");
		$this->db->query("
			CREATE TABLE `${prefix}team_members` (
			  `id` int(10) unsigned NOT NULL auto_increment,
			  `person` int(10) unsigned NOT NULL,
			  `team` int(10) unsigned NOT NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=latin1;
		");
		//$this->db->query("DROP VIEW IF EXISTS `${prefix}event_division_entries`");
		//$this->db->query("
		//	CREATE VIEW `${prefix}event_division_entries` AS select `${prefix}event_entries`.`event_division` AS `event_division`,count(0) AS `ct` from `${prefix}event_entries` group by `${prefix}event_entries`.`event_division`
		//");

		$divisions = array();
        $divisions[] = $this->div_helper('Humanoid - Kung-Fu (LightWt-R/C)', 35, 0);
        $divisions[] = $this->div_helper('Humanoid - Kung-Fu (MiddleWt-R/C)', 35, 0);
        $divisions[] = $this->div_helper('Humanoid - Freestyle/Acrobatics (R/C)', 35, 0);
        $divisions[] = $this->div_helper('Humanoid - Stair Climbing (R/C)', 35, 0);
        $divisions[] = $this->div_helper('Humanoid - Biped Race (R/C)', 35, 0);
        $divisions[] = $this->div_helper('Humanoid - Golf (R/C)', 35, 0);
        $divisions[] = $this->div_helper('Humanoid - Obstacle Course (R/C)', 35, 0);
        $divisions[] = $this->div_helper('Humanoid - Sumo (R/C)', 35, 0);
        $divisions[] = $this->div_helper('Humanoid - MechWars', 35, 0);
        $divisions[] = $this->div_helper('Humanoid - BasketBall (Auto)', 35, 0);
        $divisions[] = $this->div_helper('Humanoid - Weight Lifting (Auto)', 35, 0);
        $divisions[] = $this->div_helper('Humanoid - Lift and Carry (Auto)', 35, 0);
        $divisions[] = $this->div_helper('Humanoid - Marathon (Auto)', 35, 0);
        $divisions[] = $this->div_helper('Humanoid - Obstacle Run (Auto)', 35, 0);
        $divisions[] = $this->div_helper('Humanoid - Penalty Kick (Auto)', 35, 0);
        $divisions[] = $this->div_helper('Humanoid - Dash (Auto)', 35, 0);
        $divisions[] = $this->div_helper('Soccer - Biped Soccer 3:3 (R/C)', 100, 0);
        $divisions[] = $this->div_helper('Soccer - MiroSot 5:5 (auton)', 300, 0);
        $divisions[] = $this->div_helper('Soccer - MiroSot 11:11 (auton)', 300, 0);
        $divisions[] = $this->div_helper('Sumo - 3kg (R/C)', 35, 0);
        $divisions[] = $this->div_helper('Sumo - 3kg (auton)', 35, 0);
        $divisions[] = $this->div_helper('Sumo - 1kg - Lego (auton)', 35, 0);
        $divisions[] = $this->div_helper('Sumo - 500g (auton)', 35, 0);
        $divisions[] = $this->div_helper('Sumo - 100g (auton)', 35, 0);
        $divisions[] = $this->div_helper('Sumo - 25g (auton)', 35, 0);
        $divisions[] = $this->div_helper('Combat - 340 lbs / 154.5 kg', 340, 0);
        $divisions[] = $this->div_helper('Combat - 220 lbs / 100 kg', 220, 0);
        $divisions[] = $this->div_helper('Combat - 120 lbs / 54.5 kg', 120, 0);
        $divisions[] = $this->div_helper('Combat - 60 lbs / 27.3 kg', 60, 0);
        $divisions[] = $this->div_helper('Combat - 30 lbs / 13.6 kg', 35, 0);
        $divisions[] = $this->div_helper('Combat - 3 lbs / 1.4 kg', 35, 0);
        $divisions[] = $this->div_helper('Combat - 3 lbs (auton)', 0, 0);
        $divisions[] = $this->div_helper('Combat - 1 lb / 454g', 35, 0);
        $divisions[] = $this->div_helper('Combat - 1 lb (auton)', 0, 0);
        $divisions[] = $this->div_helper('Combat - 5.3 oz / 150g', 35, 0);
        $divisions[] = $this->div_helper('Hockey - 12 lbs', 35, 0);
        $divisions[] = $this->div_helper('Open - Best of Show', 35, 0);
        $divisions[] = $this->div_helper('Open - Line Follower (auton)', 35, 0);
        $divisions[] = $this->div_helper('Open - Maze/MicroMouse (auton)', 35, 0);
        $divisions[] = $this->div_helper('Open - Lego Open (auton)', 35, 0);
        $divisions[] = $this->div_helper('Open - Lego Challenge (auton)', 35, 0);
        $divisions[] = $this->div_helper('Open - Tetrix Challenge', 35, 0);
        $divisions[] = $this->div_helper('Open - Fire-Fighting (auton)', 35, 0);
        $divisions[] = $this->div_helper('Open - Ribbon Climber (auton)', 35, 0);
        $divisions[] = $this->div_helper('Open - Walker Challenge', 35, 0);
        $divisions[] = $this->div_helper('Open - Aibo Performer (auton)', 35, 0);
        $divisions[] = $this->div_helper('Open - Balancer Race (R/C)', 35, 0);
        $divisions[] = $this->div_helper('Open - Balancer Race (auton)', 35, 0);
        $divisions[] = $this->div_helper('Open - Table Top Nav (auton)', 35, 0);
        $divisions[] = $this->div_helper('Open - Vex Challenge', 35, 0);
        $divisions[] = $this->div_helper('Jr League - Woots & Snarks', 0, 0);
        $divisions[] = $this->div_helper('Jr League - Lego Challenge', 0, 0);
        $divisions[] = $this->div_helper('Jr League - Lego Magellan', 0, 0);
        $divisions[] = $this->div_helper('Jr League - Lego Open', 0, 0);
        $divisions[] = $this->div_helper('Jr League - Best of Show', 0, 0);
        $divisions[] = $this->div_helper('Jr League - 500 g Sumo', 0, 0);
        $divisions[] = $this->div_helper('Jr League - 120 lb combat', 0, 0);
        $divisions[] = $this->div_helper('Jr League - 1 lb Combat', 0, 0);
        $divisions[] = $this->div_helper('Jr League - BotsketBall', 0, 0);
        $divisions[] = $this->div_helper('Jr League - Tetrix Challenge', 35, 0);
        $divisions[] = $this->div_helper('Auto - Robomagellan', 35, 0);
        $divisions[] = $this->div_helper('Auto - NatCar (auton)', 35, 0);
        $divisions[] = $this->div_helper('Tetsujin - Weightlifting', 100, 0);
        $divisions[] = $this->div_helper('Tetsujin - Walking Race', 100, 0);
        $divisions[] = $this->div_helper('Art Bots - Static', 35, 0);
        $divisions[] = $this->div_helper('Art Bots - Kinetic', 35, 0);
        $divisions[] = $this->div_helper('Art Bots - Bartending', 35, 0);
        $divisions[] = $this->div_helper('Art Bots - Musical', 35, 0);
        $divisions[] = $this->div_helper('Art Bots - Painting', 35, 0);
        $divisions[] = $this->div_helper('BEAM - Speeder', 35, 0);
        $divisions[] = $this->div_helper('BEAM - Photovore', 35, 0);
        $divisions[] = $this->div_helper('BEAM - Robosapien', 35, 0);

		$this->create_event(array(
			'name' => 'Robogames 2009',
			'image' => '/images/events/robogames.gif',
			'smallimage' => '/images/events/robogames.gif',
			'description' => 'RoboGames, the world\'s largest robot competition, returns this summer, with teams from around the world competing in over 60 different events.  Register today.',
			'startdate' => '2009-06-12 00:00:00',
			'enddate' => '2009-06-14 00:00:00',
			'registrationends' => '2009-05-20 00:00:00',
			'websiteurl' => 'http://www.robogames.net/',
			'location' => 'San Francisco, CA'
		), $divisions);

		/*$this->db->empty_table('entry');
		$this->db->empty_table('person');
		$this->db->empty_table('team');
		$this->db->empty_table('event_entries');
		$this->db->empty_table('event_people');
		$this->db->empty_table('event_registrations');
		*/
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
			->where('event_divisions.event', $event)
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

	function create_event($data, $divisions)
	{	
		$this->db->insert('event', $data);
		$eventid = $this->db->insert_id();
		
		foreach ($divisions as $div)
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
	}
	
	function create_division($name)
	{		
		$this->db->insert('divisions', array('name' => $name));
		return $this->db->insert_id();
	}
	
}
