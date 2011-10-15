<?php

class Person_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();		
	}
	
	function get_person_by_id($id)
	{
		return $this->db->get_where('person', array('id'=>$id), 1)->row();
	}	

	function get_person_by_url($url)
	{
		// this fixes a bug introduced by the older version of the site where it didn't record the fragments in the database.
		$parts = explode("#", $url);
		if (count($parts) > 1)
		{
			$found_bare = $this->db->get_where('person', array('idurl'=>$parts[0]), 1)->row();
			if (!empty($found_bare))
			{
				$this->db->where('idurl', $parts[0])
					->update('person', array('idurl' => $url));
			}
		}
		
		return $this->db->get_where('person', array('idurl'=>$url), 1)->row();
	}
	
	function get_person_by_email($email)
	{
		return $this->db->get_where('person', array('email'=>$email), 1)->row();
	}
	
	function add_person($fullname, $dob, $email, $picture_url, $idurl, $password = '')
	{
		$this->load->helper('string');
		
		$current = $this->get_person_by_email($email);
		if (!empty($email) && isset($current->id))
			return FALSE;
		
		$data = array(
			'fullname' => $fullname,
			'email' => $email,
			'picture_url' => $picture_url,
			'idurl' => $idurl,
			'dob' => $dob,
			'password' => '',
			'passwordsalt' => '',
		);			
		
		if (!empty($password))
		{
			$data['passwordsalt'] = random_string('alnum', 20);
			$data['password'] = $this->generate_password($password, $email, $data['passwordsalt']);
		}
		
		if (!empty($picture_url))
		{
			$thumbnail_url = $this->_make_thumbnail($picture_url);
			if ($thumbnail_url !== FALSE)
				$data['thumbnail_url'] = $thumbnail_url;
		}
		
		$this->db->insert('person', $data);
		return $this->db->insert_id();
	}
	
	function edit_person($person_id, $fullname, $dob, $email, $picture_url)
	{
		$data = array(
			'fullname' => $fullname,
			'email' => $email,
			'picture_url' => $picture_url,
			'dob' => $dob
		);

		if (!empty($picture_url))
		{
			$thumbnail_url = $this->_make_thumbnail($picture_url);
			if ($thumbnail_url !== FALSE)
				$data['thumbnail_url'] = $thumbnail_url;
		}
		
		$this->db
			->where('id', $person_id)
			->update('person', $data);
	}

	function generate_password($password, $username, $salt)
	{
		return sha1("$password:$username:$salt");
	}
	
	function _make_thumbnail($picture_url)
	{
		$dest_file = '/images/uploads/'. md5(uniqid(mt_rand())) . '.jpg';
		$config = array();
		$config['image_library'] = 'gd2';
		$config['source_image'] = "./$picture_url";
		$config['new_image'] = "./$dest_file";			
		$config['width'] = 100;
		$config['height'] = 125;
		$config['maintain_ratio'] = FALSE;
		$this->load->library('image_lib', $config);
		if ($this->image_lib->resize() === TRUE)
			return $dest_file;
		return FALSE;	
	}
	
	function check_login()
	{
		$personid = $this->session->userdata('userid');
		if ($personid === false)
		{
			$this->session->set_userdata('onloginurl', $this->input->server('QUERY_STRING'));
			redirect(site_url('login'));
			die();
		}
	}
}
