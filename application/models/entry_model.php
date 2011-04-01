<?php

class Entry_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();

	}

	function get_entry($id)
	{
		return $this->db->get_where('entry', array('id'=>$id), 1)->row();
	}

	function add_entry($name, $div, $team, $picture_url)
	{
		$data = array(
				'name' => $name,
				'team' => $team,
				'default_division' => $div,
				'picture_url' => $picture_url,
				'description' => '',
				'thumbnail_url' => '',
				);

		if (!empty($picture_url))
		{
			$thumbnail_url = $this->_make_thumbnail($picture_url);
			if ($thumbnail_url !== FALSE)
				$data['thumbnail_url'] = $thumbnail_url;
		}

		$this->db->insert('entry', $data);
		return $this->db->insert_id();
	}

	function edit_entry($entry_id, $name, $div, $picture_url)
	{
		$data = array(
			'name' => $name,
			'default_division' => $div,
			'picture_url' => $picture_url,
		);

		if (!empty($picture_url))
		{
			$thumbnail_url = $this->_make_thumbnail($picture_url);
			if ($thumbnail_url !== FALSE)
				$data['thumbnail_url'] = $thumbnail_url;
		}

		$this->db
			->where('id', $entry_id)
			->update('entry', $data);
		return $this->db->insert_id();
	}

	function _make_thumbnail($picture_url)
	{
		$dest_file = '/images/uploads/'. md5(uniqid(mt_rand())) . '.jpg';
		$config = array();
		$config['image_library'] = 'gd2';
		$config['source_image'] = "./$picture_url";
		$config['new_image'] = "./$dest_file";
		$config['width'] = 125;
		$config['height'] = 125;
		$config['maintain_ratio'] = FALSE;
		$this->load->library('image_lib', $config);
		if ($this->image_lib->resize() === TRUE)
			return $dest_file;
		return FALSE;
	}
}
