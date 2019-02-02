<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


if (!function_exists('converthalfhourtimetohhmmss'))
{
    function converthalfhourtimetohhmmss($number)
    {	
		$StartTime = $number;
		$Hours; $Minutes; ///Two variable to help with calculation.
		$mod24hour = $StartTime%2;
		if ($mod24hour == 1)
		{ $Hours = ($StartTime/2) - 0.5; $Minutes = "30"; }
		else
		{ $Hours = ( $StartTime/2 ); $Minutes = "00"; }
		if( $Hours < 10 )
		{ $Hours = "0".$Hours.""; }
		$STime = "".$Hours.":".$Minutes.":00";
		
		return $STime;
		
    }
}

?>