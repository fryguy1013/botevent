<?php

class Install_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();

		$this->load->dbforge();
		$this->load->model('Event_model');
	}
	
	function div_helper($name, $price, $max)
	{
		return array(
			'division' => $this->Event_model->create_division($name),
			'price' => $price,
			'maxentries' => $max,
			'description' => '',
			'ruleurl' => ''
		);
	}

	function get_database_version()
	{
	    if (!$this->db->table_exists('version'))
	    {
			// this is a relatively new thing, so version "1" is defined as
			// the release before I added it
			return $this->db->table_exists('divisions') ? 1 : 0;
	    }
	    $ret = $this->db->get('version')->row();
		return $ret->version;
	}
	
	function commit_1()
	{
		$this->dbforge->add_field("id int(10) unsigned NOT NULL auto_increment");
		$this->dbforge->add_field("name varchar(128) NOT NULL");
		$this->dbforge->add_key("id", TRUE);
		$this->dbforge->create_table("divisions");

		$this->dbforge->add_field("id int(11) NOT NULL auto_increment");
		$this->dbforge->add_field("name varchar(128) NOT NULL");
		$this->dbforge->add_field("description text NOT NULL");
		$this->dbforge->add_field("thumbnail_url varchar(200) NOT NULL");
		$this->dbforge->add_field("team int(11) NOT NULL");
		$this->dbforge->add_field("picture_url varchar(200) NOT NULL");
		$this->dbforge->add_field("default_division int(11) NOT NULL");
		$this->dbforge->add_key("id", TRUE);
		$this->dbforge->create_table("entry");

		$this->dbforge->add_field("id int(10) unsigned NOT NULL auto_increment");
		$this->dbforge->add_field("name varchar(128) NOT NULL");
		$this->dbforge->add_field("image varchar(128) NOT NULL");
		$this->dbforge->add_field("description text NOT NULL");
		$this->dbforge->add_field("startdate datetime NOT NULL");
		$this->dbforge->add_field("enddate datetime NOT NULL");
		$this->dbforge->add_field("registrationends datetime NOT NULL");
		$this->dbforge->add_field("websiteurl varchar(128) NOT NULL");
		$this->dbforge->add_field("smallimage varchar(128) NOT NULL");
		$this->dbforge->add_field("location varchar(128) NOT NULL");
		$this->dbforge->add_key("id", TRUE);
		$this->dbforge->create_table("event");

		$this->dbforge->add_field("id int(10) unsigned NOT NULL auto_increment");
		$this->dbforge->add_field("event int(10) unsigned NOT NULL");
		$this->dbforge->add_field("division int(10) unsigned NOT NULL");
		$this->dbforge->add_field("description text NOT NULL");
		$this->dbforge->add_field("ruleurl varchar(128) NOT NULL");
		$this->dbforge->add_field("maxentries int(10) unsigned NOT NULL");
		$this->dbforge->add_field("price decimal(10,0) NOT NULL");
		$this->dbforge->add_key("id", TRUE);
		$this->dbforge->add_key('event');
		$this->dbforge->create_table("event_divisions");

		$this->dbforge->add_field("id int(10) unsigned NOT NULL auto_increment");
		$this->dbforge->add_field("event_division int(10) unsigned NOT NULL");
		$this->dbforge->add_field("entry int(10) unsigned NOT NULL");
		$this->dbforge->add_field("event_registration int(10) unsigned NOT NULL");
		$this->dbforge->add_key("id", TRUE);
		$this->dbforge->create_table("event_entries");

		$this->dbforge->add_field("id int(10) unsigned NOT NULL auto_increment");
		$this->dbforge->add_field("person int(10) unsigned NOT NULL");
		$this->dbforge->add_field("event_registration int(10) unsigned NOT NULL");
		$this->dbforge->add_key("id", TRUE);
		$this->dbforge->create_table("event_people");

		$this->dbforge->add_field("id int(10) unsigned NOT NULL auto_increment");
		$this->dbforge->add_field("team int(10) unsigned NOT NULL");
		$this->dbforge->add_field("event int(10) unsigned NOT NULL");
		$this->dbforge->add_field("status varchar(45) NOT NULL");
		$this->dbforge->add_field("captain int(10) unsigned NOT NULL");
		$this->dbforge->add_field("due decimal(10,0) NOT NULL");
		$this->dbforge->add_key("id", TRUE);
		$this->dbforge->create_table("event_registrations");

		$this->dbforge->add_field("id int(10) unsigned NOT NULL auto_increment");
		$this->dbforge->add_field("fullname varchar(200) NOT NULL");
		$this->dbforge->add_field("email varchar(200) NOT NULL");
		$this->dbforge->add_field("dob varchar(200) NOT NULL");
		$this->dbforge->add_field("idurl varchar(200) default NULL");
		$this->dbforge->add_field("picture_url varchar(200) NOT NULL");
		$this->dbforge->add_field("password varchar(200) NOT NULL");
		$this->dbforge->add_field("passwordsalt varchar(200) NOT NULL");
		$this->dbforge->add_field("thumbnail_url varchar(200) NOT NULL");
		$this->dbforge->add_key("id", TRUE);
		$this->dbforge->add_key('idurl');
		$this->dbforge->add_key('email');
		$this->dbforge->create_table("person");

		$this->dbforge->add_field("id int(10) unsigned NOT NULL auto_increment");
		$this->dbforge->add_field("name varchar(128) NOT NULL");
		$this->dbforge->add_field("url varchar(128) NOT NULL");
		$this->dbforge->add_field("description text NOT NULL");
		$this->dbforge->add_field("created datetime NOT NULL");
		$this->dbforge->add_field("addr1 varchar(255) NOT NULL");
		$this->dbforge->add_field("addr2 varchar(255) NOT NULL");
		$this->dbforge->add_field("city varchar(255) NOT NULL");
		$this->dbforge->add_field("state varchar(255) NOT NULL");
		$this->dbforge->add_field("zip varchar(255) NOT NULL");
		$this->dbforge->add_field("country varchar(255) NOT NULL");
		$this->dbforge->add_key("id", TRUE);
		$this->dbforge->create_table("team");

		$this->dbforge->add_field("id int(10) unsigned NOT NULL auto_increment");
		$this->dbforge->add_field("person int(10) unsigned NOT NULL");
		$this->dbforge->add_field("team int(10) unsigned NOT NULL");
		$this->dbforge->add_key("id", TRUE);
		$this->dbforge->create_table("team_members");
		
		//$this->db->query("DROP VIEW IF EXISTS ${prefix}event_division_entries");
		//$this->db->query("
		//	CREATE VIEW ${prefix}event_division_entries AS select ${prefix}event_entries.event_division AS event_division,count(0) AS ct from ${prefix}event_entries group by ${prefix}event_entries.event_division
		//");
	}
	
	function rollback_1()
	{
		if ($this->config->item('development_environment') !== TRUE)
			return;

		$this->dbforge->drop_table("divisions");
		$this->dbforge->drop_table("entry");
		$this->dbforge->drop_table("event");
		$this->dbforge->drop_table("event_divisions");
		$this->dbforge->drop_table("event_entries");
		$this->dbforge->drop_table("event_people");
		$this->dbforge->drop_table("event_registrations");
		$this->dbforge->drop_table("person");
		$this->dbforge->drop_table("team");
		$this->dbforge->drop_table("team_members");
	}
	
	function commit_2()
	{
		$this->dbforge->add_field("version int(10) unsigned NOT NULL");
		$this->dbforge->create_table("version");
		
		$this->db->insert('version', array('version' => 2));
	}
	
	function rollback_2()
	{
		if ($this->config->item('development_environment') !== TRUE)
			return;
		
		$this->dbforge->drop_table("version");
	}

	function reset()
	{
		if ($this->config->item('development_environment') !== TRUE)
			return;
	
		$this->db->empty_table('divisions');
		$this->db->empty_table('entry');
		$this->db->empty_table('event');
		$this->db->empty_table('event_entries');
		$this->db->empty_table('event_people');
		$this->db->empty_table('event_registrations');
		$this->db->empty_table('person');
		$this->db->empty_table('team');
		$this->db->empty_table('team_members');

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

		$this->Event_model->create_event(array(
			'name' => 'Robogames 2011',
			'image' => '/images/events/robogames.gif',
			'smallimage' => '/images/events/robogames.gif',
			'description' => 'RoboGames, the world\'s largest robot competition, returns this summer, with teams from around the world competing in over 60 different events.  Register today.',
			'startdate' => '2011-04-14 00:00:00',
			'enddate' => '2011-04-17 00:00:00',
			'registrationends' => '2011-04-01 00:00:00',
			'websiteurl' => 'http://www.robogames.net/',
			'location' => 'San Francisco, CA'
		), $divisions);
		
		/*
		$this->db->where('name', 'Humanoid - MechWars')->update('divisions', array('name' => 'Humanoid - MechWars (LW)'));

		$this->Event_model->add_division_to_event(4, $this->div_helper('Humanoid - MechWars (MW)', 35, 0));
		$this->Event_model->add_division_to_event(4, $this->div_helper('Open - Shooting Gallery', 35, 0));
		*/

	}

	function backup()
	{
		// Load the DB utility class
		$this->load->dbutil();

		$tables = array(
			"divisions",
			"entry",
			"event",
			"event_divisions",
			"event_entries",
			"event_people",
			"event_registrations",
			"person",
			"team",
			"team_members",
		);

		$prefs = array(
			'tables'      => $tables,
			'format'      => 'txt',             // gzip, zip, txt
			'add_drop'    => TRUE,              // Whether to add DROP TABLE statements to backup file
			'add_insert'  => TRUE,              // Whether to add INSERT data to backup file
			'newline'     => "\n"               // Newline character used in backup file
		);

		// Backup your entire database and assign it to a variable
		$backup =& $this->dbutil->backup($prefs);
		
		header("Content-Type: text/plain; charset=UTF-8\r\n");
		return $backup;
	}


	function refresh_amount_due()
	{
		$prices = $this->db
			->select('event_entries.event_registration', FALSE)
			->select_sum('event_divisions.price', FALSE)
			->from('event_entries')
			->join('event_divisions', 'event_divisions.id = event_entries.event_division')
			->groupby('event_entries.event_registration')
			->get()->result();
		
		foreach ($prices as $price)
		{
			$this->db->update('event_registrations', array('due' => $price->price), array('id' => $price->event_registration));
		}
	}	
}
