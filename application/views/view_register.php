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
    
    $countries = array(
        "USA"=>"USA",
        "Australia"=>"Australia",
        "Brazil"=>"Brazil",
        "Canada"=>"Canada",
        "China"=>"China",
        "Colombia"=>"Colombia",
        "Egypt"=>"Egypt",
        "Estonia"=>"Estonia",
        "France"=>"France",
        "Germany"=>"Germany",
        "Hong Kong"=>"Hong Kong",
        "India"=>"India",
        "Indonesia"=>"Indonesia",
        "Japan"=>"Japan",
        "Korea"=>"Korea",
        "Latvia"=>"Latvia",
        "Lithuania"=>"Lithuania",
        "Mexico"=>"Mexico",
        "Poland"=>"Poland",
        "Spain"=>"Spain",
        "Taiwan"=>"Taiwan",
        "UK"=>"UK",
        ""=>"-----",
        "Afghanistan"=>"Afghanistan",
        "Albania"=>"Albania",
        "Algeria"=>"Algeria",
        "American Samoa"=>"American Samoa",
        "Andorra"=>"Andorra",
        "Angola"=>"Angola",
        "Anguilla"=>"Anguilla",
        "Antarctica"=>"Antarctica",
        "Antigua and Barbuda"=>"Antigua and Barbuda",
        "Argentina"=>"Argentina",
        "Armenia"=>"Armenia",
        "Aruba"=>"Aruba",
        "Austria"=>"Austria",
        "Azerbaijan"=>"Azerbaijan",
        "Bahamas"=>"Bahamas",
        "Bahrain"=>"Bahrain",
        "Bangladesh"=>"Bangladesh",
        "Barbados"=>"Barbados",
        "Belarus"=>"Belarus",
        "Belgium"=>"Belgium",
        "Belize"=>"Belize",
        "Benin"=>"Benin",
        "Bermuda"=>"Bermuda",
        "Bhutan"=>"Bhutan",
        "Bolivia"=>"Bolivia",
        "Bosnia and Herzegovina"=>"Bosnia and Herzegovina",
        "Botswana"=>"Botswana",
        "Bouvet Island"=>"Bouvet Island",
        "British Indian Ocean Territory"=>"British Indian Ocean Territory",
        "British Virgin Islands"=>"British Virgin Islands",
        "Brunei"=>"Brunei",
        "Bulgaria"=>"Bulgaria",
        "Burkina Faso"=>"Burkina Faso",
        "Burma"=>"Burma",
        "Burundi"=>"Burundi",
        "Cambodia"=>"Cambodia",
        "Cameroon"=>"Cameroon",
        "Cape Verde"=>"Cape Verde",
        "Cayman Islands"=>"Cayman Islands",
        "Central African Republic"=>"Central African Republic",
        "Chad"=>"Chad",
        "Chile"=>"Chile",
        "Christmas Island"=>"Christmas Island",
        "Cocos Islands"=>"Cocos Islands",
        "Comoros"=>"Comoros",
        "Congo"=>"Congo",
        "Cook Islands"=>"Cook Islands",
        "Costa Rica"=>"Costa Rica",
        "Cote d'Ivoire"=>"Cote d'Ivoire",
        "Croatia"=>"Croatia",
        "Cuba"=>"Cuba",
        "Curacao"=>"Curacao",
        "Cyprus"=>"Cyprus",
        "Czech Republic"=>"Czech Republic",
        "Denmark"=>"Denmark",
        "Djibouti"=>"Djibouti",
        "Dominica"=>"Dominica",
        "Dominican Republic"=>"Dominican Republic",
        "Ecuador"=>"Ecuador",
        "El Salvador"=>"El Salvador",
        "Equatorial Guinea"=>"Equatorial Guinea",
        "Eritrea"=>"Eritrea",
        "Ethiopia"=>"Ethiopia",
        "Falkland Islands"=>"Falkland Islands",
        "Faroe Islands"=>"Faroe Islands",
        "Fiji"=>"Fiji",
        "Finland"=>"Finland",
        "French Guiana"=>"French Guiana",
        "French Polynesia"=>"French Polynesia",
        "Gabon"=>"Gabon",
        "Gambia"=>"Gambia",
        "Gaza Strip"=>"Gaza Strip",
        "Georgia"=>"Georgia",
        "Ghana"=>"Ghana",
        "Gibraltar"=>"Gibraltar",
        "Greece"=>"Greece",
        "Greenland"=>"Greenland",
        "Grenada"=>"Grenada",
        "Guadeloupe"=>"Guadeloupe",
        "Guam"=>"Guam",
        "Guatemala"=>"Guatemala",
        "Guernsey"=>"Guernsey",
        "Guinea"=>"Guinea",
        "Guinea-Bissau"=>"Guinea-Bissau",
        "Guyana"=>"Guyana",
        "Haiti"=>"Haiti",
        "Heard Island and McDonald Islands"=>"Heard Island and McDonald Islands",
        "Vatican City"=>"Vatican City",
        "Honduras"=>"Honduras",
        "Hungary"=>"Hungary",
        "Iceland"=>"Iceland",
        "Iran"=>"Iran",
        "Iraq"=>"Iraq",
        "Ireland"=>"Ireland",
        "Isle of Man"=>"Isle of Man",
        "Israel"=>"Israel",
        "Italy"=>"Italy",
        "Jamaica"=>"Jamaica",
        "Jersey"=>"Jersey",
        "Jordan"=>"Jordan",
        "Kazakhstan"=>"Kazakhstan",
        "Kenya"=>"Kenya",
        "Kiribati"=>"Kiribati",
        "Kosovo"=>"Kosovo",
        "Kuwait"=>"Kuwait",
        "Kyrgyzstan"=>"Kyrgyzstan",
        "Laos"=>"Laos",
        "Lebanon"=>"Lebanon",
        "Lesotho"=>"Lesotho",
        "Liberia"=>"Liberia",
        "Libya"=>"Libya",
        "Liechtenstein"=>"Liechtenstein",
        "Luxembourg"=>"Luxembourg",
        "Macau"=>"Macau",
        "Macedonia"=>"Macedonia",
        "Madagascar"=>"Madagascar",
        "Malawi"=>"Malawi",
        "Malaysia"=>"Malaysia",
        "Maldives"=>"Maldives",
        "Mali"=>"Mali",
        "Malta"=>"Malta",
        "Marshall Islands"=>"Marshall Islands",
        "Martinique"=>"Martinique",
        "Mauritania"=>"Mauritania",
        "Mauritius"=>"Mauritius",
        "Mayotte"=>"Mayotte",
        "Micronesia"=>"Micronesia",
        "Moldova"=>"Moldova",
        "Monaco"=>"Monaco",
        "Mongolia"=>"Mongolia",
        "Montenegro"=>"Montenegro",
        "Montserrat"=>"Montserrat",
        "Morocco"=>"Morocco",
        "Mozambique"=>"Mozambique",
        "Namibia"=>"Namibia",
        "Nauru"=>"Nauru",
        "Nepal"=>"Nepal",
        "Netherlands"=>"Netherlands",
        "New Caledonia"=>"New Caledonia",
        "New Zealand"=>"New Zealand",
        "Nicaragua"=>"Nicaragua",
        "Niger"=>"Niger",
        "Nigeria"=>"Nigeria",
        "Niue"=>"Niue",
        "Norfolk Island"=>"Norfolk Island",
        "Northern Mariana Islands"=>"Northern Mariana Islands",
        "Norway"=>"Norway",
        "Oman"=>"Oman",
        "Pakistan"=>"Pakistan",
        "Palau"=>"Palau",
        "Panama"=>"Panama",
        "Papua New Guinea"=>"Papua New Guinea",
        "Paraguay"=>"Paraguay",
        "Peru"=>"Peru",
        "Philippines"=>"Philippines",
        "Pitcairn Islands"=>"Pitcairn Islands",
        "Portugal"=>"Portugal",
        "Puerto Rico"=>"Puerto Rico",
        "Qatar"=>"Qatar",
        "Reunion"=>"Reunion",
        "Romania"=>"Romania",
        "Russia"=>"Russia",
        "Rwanda"=>"Rwanda",
        "Saint Barthelemy"=>"Saint Barthelemy",
        "Saint Helena"=>"Saint Helena",
        "Saint Kitts"=>"Saint Kitts",
        "Saint Lucia"=>"Saint Lucia",
        "Saint Martin"=>"Saint Martin",
        "Saint Pierre"=>"Saint Pierre",
        "Saint Vincent"=>"Saint Vincent",
        "Samoa"=>"Samoa",
        "San Marino"=>"San Marino",
        "Sao Tome and Principe"=>"Sao Tome and Principe",
        "Saudi Arabia"=>"Saudi Arabia",
        "Senegal"=>"Senegal",
        "Serbia"=>"Serbia",
        "Seychelles"=>"Seychelles",
        "Sierra Leone"=>"Sierra Leone",
        "Singapore"=>"Singapore",
        "Sint Maarten"=>"Sint Maarten",
        "Slovakia"=>"Slovakia",
        "Slovenia"=>"Slovenia",
        "Solomon Islands"=>"Solomon Islands",
        "Somalia"=>"Somalia",
        "South Africa"=>"South Africa",
        "South Georgia and the Islands"=>"South Georgia and the Islands",
        "South Sudan"=>"South Sudan",
        "Sri Lanka"=>"Sri Lanka",
        "Sudan"=>"Sudan",
        "Suriname"=>"Suriname",
        "Svalbard"=>"Svalbard",
        "Swaziland"=>"Swaziland",
        "Sweden"=>"Sweden",
        "Switzerland"=>"Switzerland",
        "Syria"=>"Syria",
        "Tajikistan"=>"Tajikistan",
        "Tanzania"=>"Tanzania",
        "Thailand"=>"Thailand",
        "Timor-Leste"=>"Timor-Leste",
        "Togo"=>"Togo",
        "Tokelau"=>"Tokelau",
        "Tonga"=>"Tonga",
        "Trinidad and Tobago"=>"Trinidad and Tobago",
        "Tunisia"=>"Tunisia",
        "Turkey"=>"Turkey",
        "Turkmenistan"=>"Turkmenistan",
        "Turks and Caicos Islands"=>"Turks and Caicos Islands",
        "Tuvalu"=>"Tuvalu",
        "Uganda"=>"Uganda",
        "Ukraine"=>"Ukraine",
        "United Arab Emirates"=>"United Arab Emirates",
        "Uruguay"=>"Uruguay",
        "Uzbekistan"=>"Uzbekistan",
        "Vanuatu"=>"Vanuatu",
        "Venezuela"=>"Venezuela",
        "Vietnam"=>"Vietnam",
        "Virgin Islands"=>"Virgin Islands",
        "Wallis and Futuna"=>"Wallis and Futuna",
        "West Bank"=>"West Bank",
        "Western Sahara"=>"Western Sahara",
        "Yemen"=>"Yemen",
        "Zambia"=>"Zambia",
        "Zimbabwe"=>"Zimbabwe");
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

