<?php

class Entry_model extends Model
{
	function Entry_model()
	{
		parent::Model();
		
	}
	
	function get_entry($id)
	{
		return $this->db->get_where('entry', array('id'=>$id), 1)->row();
	}
	
	function add_entry($name, $team, $picture_url)
	{
		$data = array(
				'name' => $name,
				'team' => $team,
				'picture_url' => $picture_url
				);
				
		if (!empty($picture_url))
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
				$data['thumbnail_url'] = $dest_file;
		}				
		
		$this->db->insert('entry', $data);
		return $this->db->insert_id();
	}
}
