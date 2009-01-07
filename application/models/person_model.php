<?php

class Person_model extends Model
{
	function Person_model()
	{
		parent::Model();		
	}
	
	function get_person_by_id($id)
	{
		return $this->db->get_where('person', array('id'=>$id), 1)->row();
	}	

	function get_person_by_url($url)
	{
		return $this->db->get_where('person', array('idurl'=>$id), 1)->row();
	}
	
	function add_person($fullname, $email, $is_adult, $picture_url, $idurl, $teamid)
	{
		$data = array(
			'fullname' => $fullname,
			'email' => $email,
			'is_adult' => $is_adult,
			'picture_url' => $picture_url,
			'idurl' => $idurl,
			'teamid' => $teamid
		);
		
		$this->db->insert('person', $data);
		return $this->db->insert_id();
	}


}
