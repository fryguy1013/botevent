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
	
	function get_person_by_email($email)
	{
		return $this->db->get_where('person', array('email'=>$email), 1)->row();
	}
	
	function add_person($fullname, $dob, $email, $phonenum, $picture_url, $password)
	{
		$this->load->helper('string');
		
		$current = $this->get_person_by_email($email);
		if (!empty($email) && isset($current->id))
			return FALSE;
		
		$data = array(
			'fullname' => $fullname,
			'email' => $email,
            'phonenum' => $phonenum,
			'picture_url' => $picture_url,
			'dob' => $dob,
		);
        
        if (!empty($password))
        {
            $data['password'] = password_hash($password, PASSWORD_BCRYPT);
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
	
	function edit_person($person_id, $fullname, $dob, $email, $phonenum, $picture_url)
	{
		$data = array(
			'fullname' => $fullname,
			'email' => $email,
            'phonenum' => $phonenum,
			'dob' => $dob
		);

		if (!empty($picture_url))
		{
            $data['picture_url'] = $picture_url;
			$thumbnail_url = $this->_make_thumbnail($picture_url);
			if ($thumbnail_url !== FALSE)
				$data['thumbnail_url'] = $thumbnail_url;
		}
		
		$this->db
			->where('id', $person_id)
			->update('person', $data);
	}
    
    function update_password($person_id, $password)
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        
        $this->db
            ->where('id', $person_id)
            ->update('person', array(
                'password' => $hash,
                'passwordsalt' => ''
            ));
    }

    function check_password($person, $password)
    {
        if (!empty($person->passwordsalt))
        {
            // legacy password. need to upgrade
            $hash = $this->generate_password($password, $person->email, $person->passwordsalt);
            if ($hash === $person->password)
            {
                $this->update_password($person->id, $password);
                return TRUE;
            }
        }
        
        if (empty($person->password))
        {
            return FALSE;
        }
        
        return password_verify($password, $person->password);
    }
    
    function generate_password($password, $username, $salt)
    {
        return sha1("$password:$username:$salt");
    }
    
	function generate_login_code_hash($id, $code)
	{
		return sha1("$id:$code");
	}
	
	function create_login_code($id, $dest_url)
	{
		$code = random_string('alnum', 20);
		$hash = $this->generate_login_code_hash($id, $code);
		
		$this->db->set('userid', $id);
		$this->db->set('hash', $hash);
		$this->db->set('generated', 'now()', FALSE);
        $this->db->set('dest_url', $dest_url);
		$this->db->insert('login_code');
		
		return $code;
	}
	
	function get_login_code_info($id, $code)
	{
		$hash = $this->generate_login_code_hash($id, $code);
		
		return $this->db->get_where('login_code', array('userid'=>$id, 'hash'=>$hash), 1)->row();
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
            $this->session->set_userdata('onloginurl', current_url());
			redirect(site_url('login'));
			die();
		}
	}
}
