<?php

class Install_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();

		$this->load->dbforge();
		$this->load->model('Event_model');
	}
	
	function div_helper($name, $price, $max_entries, $max_per_team, $free_per_team)
	{
		return array(
			'division' => $this->Event_model->create_division($name),
			'price' => $price,
			'maxentries' => $max_entries,
			'description' => '',
			'ruleurl' => '',
			'maxpersonperteam' => $max_per_team,
			'freepersonperteam' => $free_per_team
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
		
		$this->dbforge->add_field("id int(10) unsigned NOT NULL auto_increment");
		$this->dbforge->add_field("team int(10) unsigned NOT NULL");
		$this->dbforge->add_field("event int(10) unsigned NOT NULL");
		$this->dbforge->add_field("paid varchar(255) NOT NULL");
		$this->dbforge->add_key("id", TRUE);
		$this->dbforge->add_key(array('team', 'event'));
		$this->dbforge->create_table("event_payments");
		
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
		$this->dbforge->drop_table("event_payments");
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
	
	function commit_3()
	{
		$this->db->update('version', array('version' => 3));

		$this->dbforge->add_field("id int(10) unsigned NOT NULL auto_increment");
		$this->dbforge->add_field("userid int(10) NOT NULL");
		$this->dbforge->add_field("hash varchar(255) NOT NULL");
		$this->dbforge->add_field("generated varchar(255) NOT NULL");
		$this->dbforge->add_key("id", TRUE);
		$this->dbforge->add_key(array('userid', 'hash'));
		$this->dbforge->create_table("login_code");
	}
	
	function rollback_3()
	{
		if ($this->config->item('development_environment') !== TRUE)
			return;
			
		$this->db->update('version', array('version' => 2));
		
		$this->dbforge->drop_table("login_code");
	}
	
	// adding max entries and such
	function commit_4()
	{
		$this->db->update('version', array('version' => 4));
		
		$this->dbforge->add_column('event_divisions', array('maxpersonperteam' => array('type' => 'int', 'default' => '-1')));
		$this->dbforge->add_column('event_divisions', array('freepersonperteam' => array('type' => 'int', 'default' => '-1')));
		
		$this->dbforge->add_column('event', array('feeperperson' => array('type' => 'int', 'default' => '0')));
	}
	
	function rollback_4()
	{
		if ($this->config->item('development_environment') !== TRUE)
			return;
			
		$this->db->update('version', array('version' => 3));
		
		$this->dbforge->drop_column('event_divisions', 'maxpersonperteam');
		$this->dbforge->drop_column('event_divisions', 'freepersonperteam');
		
		$this->dbforge->drop_column('event', 'feeperperson');
	}

	// adding max entries and such
	function commit_5()
	{
		$this->db->update('version', array('version' => 5));
		
		$this->dbforge->add_column('event_entries', array('driver' => array('type' => 'int', 'default' => '-1')));
	}
	
	function rollback_5()
	{
		if ($this->config->item('development_environment') !== TRUE)
			return;
			
		$this->db->update('version', array('version' => 4));
		
		$this->dbforge->drop_column('event_entries', 'driver');
	}

	// add driver phone number
	function commit_6()
	{
		$this->db->update('version', array('version' => 6));
		
		$this->dbforge->add_column('person', array('phonenum' => array('type' => 'varchar(255)', 'default' => '')));
	}
	
	function rollback_6()
	{
		if ($this->config->item('development_environment') !== TRUE)
			return;
			
		$this->db->update('version', array('version' => 5));
		
		$this->dbforge->drop_column('person', 'phonenum');
	}
    
    function commit_7()
    {
        $this->db->update('version', array('version' => 7));
        
		$this->dbforge->add_column('login_code', array('dest_url' => array('type' => 'varchar(255)', 'default' => '')));
	}
	
	function rollback_7()
	{
		if ($this->config->item('development_environment') !== TRUE)
			return;
        
		$this->db->update('version', array('version' => 6));
		
		$this->dbforge->drop_column('login_code', 'dest_url');
	}
    
	function reset()
	{
		if ($this->config->item('development_environment') !== TRUE)
			return;
		
		$this->db->empty_table('divisions');
		$this->db->empty_table('entry');
		$this->db->empty_table('event');
		$this->db->empty_table('event_divisions');
		$this->db->empty_table('event_entries');
		$this->db->empty_table('event_people');
		$this->db->empty_table('event_registrations');
		$this->db->empty_table('person');
		$this->db->empty_table('team');
		$this->db->empty_table('team_members');
		
		$divisions = array();
		$divisions[] = $this->div_helper('Combat - 340 lbs / 154.5 kg', 340, 0, 4, 2);
		$divisions[] = $this->div_helper('Combat - 220 lbs / 100 kg', 220, 0, 4, 2);
		$divisions[] = $this->div_helper('Combat - 120 lbs / 54.5 kg', 120, 0, 4, 2);
		$divisions[] = $this->div_helper('Combat - 60 lbs / 27.3 kg', 60, 0, 4, 2);
		$divisions[] = $this->div_helper('Combat - 30 lbs / 13.6 kg', 35, 0, 4, 2);
		$divisions[] = $this->div_helper('Combat - 3 lbs / 1.4 kg', 35, 0, 4, 2);
		$divisions[] = $this->div_helper('Combat - 3 lbs (auton)', 0, 0, 4, 2);
		$divisions[] = $this->div_helper('Combat - 1 lb / 454g', 35, 0, 4, 2);
		$divisions[] = $this->div_helper('Combat - 1 lb (auton)', 0, 0, 4, 2);
		$divisions[] = $this->div_helper('Combat - 5.3 oz / 150g', 35, 0, 4, 2);
		$divisions[] = $this->div_helper('Hockey - 12 lbs', 35, 0, 4, 2);
		
		$this->Event_model->create_event(array(
			'name' => 'Sample Event',
			'image' => '/images/events/robogames.gif',
			'smallimage' => '/images/events/robogames.gif',
			'description' => 'This is a sample event',
			'startdate' => '2013-04-14 00:00:00',
			'enddate' => '2013-04-17 00:00:00',
			'registrationends' => '2013-04-01 00:00:00',
			'websiteurl' => 'http://www.robogames.net/',
			'location' => 'San Francisco, CA',
			'feeperperson' => 40
		), $divisions);
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
