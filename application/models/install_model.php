<?php

class Install_model extends Model
{
	function Install_model()
	{
		parent::Model();
		
	}
	
	function div_helper($name, $price, $max)
	{
		$this->load->model('Event_model');
		return array(
			'division' => $this->Event_model->create_division($name),
			'price' => $price,
			'maxentries' => $max,
			'description' => '',
			'ruleurl' => ''
		);
	}		
	function reset()
	{
		/*
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

		$this->Event_model->create_event(array(
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
		*/
		/*$this->db->empty_table('entry');
		$this->db->empty_table('person');
		$this->db->empty_table('team');
		$this->db->empty_table('event_entries');
		$this->db->empty_table('event_people');
		$this->db->empty_table('event_registrations');
		*/
		
		/*
		$this->db->where('name', 'Humanoid - MechWars')->update('divisions', array('name' => 'Humanoid - MechWars (LW)'));		
		
		$this->Event_model->add_division_to_event(4, $this->div_helper('Humanoid - MechWars (MW)', 35, 0));
		$this->Event_model->add_division_to_event(4, $this->div_helper('Open - Shooting Gallery', 35, 0));
		*/
		
		/*
		$salt = "6yQ4m4499HoplgP5CBqe";
		$userid = "mwinders@me.com";
		$this->db
			->where('id', 177)
			//->update('person', array('idurl' => 'https://www.google.com/accounts/o8/id?id=AItOawn2FYK0rhRlMus7ncSxkQxggFeXp5F2bw0'));
			//->update('person', array('idurl' => 'http://www.burntpopcorn.net/'));
			->update('person', array(
				'password' => "65c09f8b2f686ce379e1d5ba216179633c12dc14",//sha1("test:$userid:$salt"),
				'passwordsalt' => $salt,
				));
		*/	
		
		/*$users = $this->db
			->select('*')
			->from('person')
			->get()->result();
		echo "<pre>";
		print_r($users);
		*/
		
		$this->refresh_amount_due();
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
