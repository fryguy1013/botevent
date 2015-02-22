<?
	$months = array(
		1 => "January",
		2 => "February",
		3 => "March",
		4 => "April",
		5 => "May",
		6 => "June",
		7 => "July",
		8 => "August",
		9 => "September",
		10 => "October",
		11 => "November",
		12 => "December"
	);
	$days = array();
	for ($i=1; $i<=31; $i++) $days[$i] = $i;
	$years = array();
	for ($i=2009; $i>=1920; $i--) $years[$i] = $i;
    
    $countries = array("USA",
        "Australia",
        "Brazil",
        "Canada",
        "China",
        "Colombia",
        "Egypt",
        "Estonia",
        "France",
        "Germany",
        "Hong Kong",
        "India",
        "Indonesia",
        "Japan",
        "Korea",
        "Latvia",
        "Lithuania",
        "Mexico",
        "Poland",
        "Spain",
        "Taiwan",
        "UK",
        ""=>"-----",
        "Afghanistan",
        "Albania",
        "Algeria",
        "American Samoa",
        "Andorra",
        "Angola",
        "Anguilla",
        "Antarctica",
        "Antigua and Barbuda",
        "Argentina",
        "Armenia",
        "Aruba",
        "Austria",
        "Azerbaijan",
        "Bahamas",
        "Bahrain",
        "Bangladesh",
        "Barbados",
        "Belarus",
        "Belgium",
        "Belize",
        "Benin",
        "Bermuda",
        "Bhutan",
        "Bolivia",
        "Bosnia and Herzegovina",
        "Botswana",
        "Bouvet Island",
        "British Indian Ocean Territory",
        "British Virgin Islands",
        "Brunei",
        "Bulgaria",
        "Burkina Faso",
        "Burma",
        "Burundi",
        "Cambodia",
        "Cameroon",
        "Cape Verde",
        "Cayman Islands",
        "Central African Republic",
        "Chad",
        "Chile",
        "Christmas Island",
        "Cocos Islands",
        "Comoros",
        "Congo",
        "Cook Islands",
        "Costa Rica",
        "Cote d'Ivoire",
        "Croatia",
        "Cuba",
        "Curacao",
        "Cyprus",
        "Czech Republic",
        "Denmark",
        "Djibouti",
        "Dominica",
        "Dominican Republic",
        "Ecuador",
        "El Salvador",
        "Equatorial Guinea",
        "Eritrea",
        "Ethiopia",
        "Falkland Islands",
        "Faroe Islands",
        "Fiji",
        "Finland",
        "French Guiana",
        "French Polynesia",
        "Gabon",
        "Gambia",
        "Gaza Strip",
        "Georgia",
        "Ghana",
        "Gibraltar",
        "Greece",
        "Greenland",
        "Grenada",
        "Guadeloupe",
        "Guam",
        "Guatemala",
        "Guernsey",
        "Guinea",
        "Guinea-Bissau",
        "Guyana",
        "Haiti",
        "Heard Island and McDonald Islands",
        "Vatican City",
        "Honduras",
        "Hungary",
        "Iceland",
        "Iran",
        "Iraq",
        "Ireland",
        "Isle of Man",
        "Israel",
        "Italy",
        "Jamaica",
        "Jersey",
        "Jordan",
        "Kazakhstan",
        "Kenya",
        "Kiribati",
        "Kosovo",
        "Kuwait",
        "Kyrgyzstan",
        "Laos",
        "Lebanon",
        "Lesotho",
        "Liberia",
        "Libya",
        "Liechtenstein",
        "Luxembourg",
        "Macau",
        "Macedonia",
        "Madagascar",
        "Malawi",
        "Malaysia",
        "Maldives",
        "Mali",
        "Malta",
        "Marshall Islands",
        "Martinique",
        "Mauritania",
        "Mauritius",
        "Mayotte",
        "Micronesia",
        "Moldova",
        "Monaco",
        "Mongolia",
        "Montenegro",
        "Montserrat",
        "Morocco",
        "Mozambique",
        "Namibia",
        "Nauru",
        "Nepal",
        "Netherlands",
        "New Caledonia",
        "New Zealand",
        "Nicaragua",
        "Niger",
        "Nigeria",
        "Niue",
        "Norfolk Island",
        "Northern Mariana Islands",
        "Norway",
        "Oman",
        "Pakistan",
        "Palau",
        "Panama",
        "Papua New Guinea",
        "Paraguay",
        "Peru",
        "Philippines",
        "Pitcairn Islands",
        "Portugal",
        "Puerto Rico",
        "Qatar",
        "Reunion",
        "Romania",
        "Russia",
        "Rwanda",
        "Saint Barthelemy",
        "Saint Helena",
        "Saint Kitts",
        "Saint Lucia",
        "Saint Martin",
        "Saint Pierre",
        "Saint Vincent",
        "Samoa",
        "San Marino",
        "Sao Tome and Principe",
        "Saudi Arabia",
        "Senegal",
        "Serbia",
        "Seychelles",
        "Sierra Leone",
        "Singapore",
        "Sint Maarten",
        "Slovakia",
        "Slovenia",
        "Solomon Islands",
        "Somalia",
        "South Africa",
        "South Georgia and the Islands",
        "South Sudan",
        "Sri Lanka",
        "Sudan",
        "Suriname",
        "Svalbard",
        "Swaziland",
        "Sweden",
        "Switzerland",
        "Syria",
        "Tajikistan",
        "Tanzania",
        "Thailand",
        "Timor-Leste",
        "Togo",
        "Tokelau",
        "Tonga",
        "Trinidad and Tobago",
        "Tunisia",
        "Turkey",
        "Turkmenistan",
        "Turks and Caicos Islands",
        "Tuvalu",
        "Uganda",
        "Ukraine",
        "United Arab Emirates",
        "Uruguay",
        "Uzbekistan",
        "Vanuatu",
        "Venezuela",
        "Vietnam",
        "Virgin Islands",
        "Wallis and Futuna",
        "West Bank",
        "Western Sahara",
        "Yemen",
        "Zambia",
        "Zimbabwe");
?>

<?=form_open_multipart("login/register")?>
<input type="hidden" name="action" value="register" />
<div class="login_frame">
	<?php if (isset($error)) { echo "<div class=\"error\">$error</div>"; } ?>
	
	<h2>Finish creating account</h2>
	
	<div>
		<h4>My Full Name:</h4>
		<input name="fullname" type="text" value="<?=set_value('fullname', '')?>" size="25" />
	</div>
	
	<div>
		<h4>Email address:</h4>
		<input name="email_addr" type="text" value="<?=set_value('email_addr', '')?>" size="25" />
	</div>


	<div>
		<h4>Password:</h4>
		<input name="password" type="password" value="" size="30" />
	</div>

	<div>
		<h4>Password (again):</h4>
		<input name="password2" type="password" value="" size="30" />
	</div>

	<div>
		<h4>Date of Birth</h4>
		<?=form_dropdown('dob_month', $months, set_value('dob_month'))?>
		<?=form_dropdown('dob_day', $days, set_value('dob_day'))?>
		<?=form_dropdown('dob_year', $years, set_value('dob_year', 1984))?>
	</div>
	    
	<div>
		<h4>Phone Number:</h4>
		<?=form_input("phonenum", set_value('phonenum', ''), 'size="30"')?>
	</div>

	<div>
		<h4>Badge Photo Picture:</h4>
		<?=form_upload('badge_photo')?>
		<div class="badge_photo_guidelines">Note: Follow the
		<a href="http://robogames.net/badges.php" target="_blank">badge photo guidelines</a>
		to ensure your registration will be accepted.</div>
	</div>	
</div>

<div class="login_frame">
	<h2>Create your team</h2>
	<p>
		<div class="event_register_heading">Team Name:</div>
		<?=form_input("team_name", set_value('team_name', ''))?>
	</p>

	<p>
		<div class="event_register_heading">Website: (optional)</div>
		<?=form_input("team_website", set_value('team_website', ''), 'size="35"')?>
	</p>
	
	<p>
		<div class="event_register_heading">Contact Address:</div>
		<div><?=form_input("team_addr1", set_value('team_addr1', ''), 'size="30"')?></div>
		<div><?=form_input("team_addr2", set_value('team_addr2', ''), 'size="30"')?></div>
	</p>
	
	<p>
		<div class="event_register_heading">City:</div>
		<?=form_input("team_city", set_value('team_city', ''), 'size="15"')?>
	</p>
	
	<p>
		<div class="event_register_heading">State/Province:</div>
		<?=form_input("team_state", set_value('team_state', ''), 'size="4"')?>
	</p>
	
	<p>
		<div class="event_register_heading">Post Code:</div>
		<?=form_input("team_zip", set_value('team_zip', ''), 'size="15"')?>
	</p>
	
	<p>
		<div class="event_register_heading">Nationality:</div>
		<?=form_dropdown("team_country", $countries, set_value('team_country', 'USA'))?>
	</p>
    
</div>

<div class="login_frame">
	<input type="submit" value="Create Account"/>
</div>
</form>

