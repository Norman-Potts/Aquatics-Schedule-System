<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*Create display date*/
if (!function_exists('convertTimeToDisplayTime'))
{
	/** function convertTimeToDisplayTime
				
			purpose: converts 24 Time To AmPm time
			
			parameters: twentFourHourTime  in HH:MM:SS
			
			return Time;
	*/
    function convertTimeToDisplayTime($HHMMSS)
    {	
		$d = explode(":", $HHMMSS);
		$HH = $d[0];
		$MM = $d[1];
		$SS = $d[2];
		$AMPM = "";			
		$hour = "";			
		$HH = (int)$HH;			
			if ( $HH > 12)
			{
				$hour = $HH - 12;
				$AMPM = "PM";
			}
			if ( $HH == 12 )
			{
				$hour = 12;
				$AMPM = "PM";
			}
			if ( $HH == 0 )
			{
				$hour = 12;
				$AMPM = "AM";
			}
			if( $HH < 12 && $HH > 0)
			{
				$hour = $HH;
				$AMPM = "AM";
			}
			$m = $MM;			
			$s = $SS;			
			$time = "".$hour.":".$m." ".$AMPM."";
			
			return $time;					
    }/*End of convertTimeToDisplayTime*/
}

?>