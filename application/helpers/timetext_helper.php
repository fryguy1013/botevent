<?php

function timeuntil($duration)
{
	if ($duration < 48*60*60)
		return floor(($duration)/60/60)." hours";
	else if ($duration < 60*60)
		return floor(($duration)/60)." minutes";
	else
		return floor(($duration)/60/60/24)." days";
}

?>