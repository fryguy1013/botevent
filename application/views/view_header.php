<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>RoboGames - Robot Event Registration<? if (isset($title)) { echo $title; } ?></title>

<link href="<?=site_url('/css/site.css')?>?2" media="screen" rel="stylesheet" type="text/css"/>
<link href="<?=site_url('/css/openid.css')?>" media="screen" rel="stylesheet" type="text/css"/> 
 
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>

</head>
<body>

<div id="mainheading">
	<div id="mainheadingleft"><strong><a href="<?=site_url('')?>">RoboGames - Event Registration</a></strong></div>
	<div id="mainheadingright">
	<? if ($this->session->userdata('userid') === false): ?>
		<a href="<?=site_url('login/logout')?>">Login</a>
	<? else: ?>
		Logged in as <a href="<?=site_url('person/view/'.$this->session->userdata('userid'))?>"><?=htmlentities($this->session->userdata('fullname'))?></a> | <a href="<?=site_url('login/logout')?>">Logout</a>
	<? endif; ?>
	</div>
	<div style="clear: both;"></div>
</div>

<div id="maincontainer">


