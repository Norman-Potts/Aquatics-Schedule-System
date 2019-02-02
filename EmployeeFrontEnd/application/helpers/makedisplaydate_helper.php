<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*Create display date*/
if (!function_exists('makeDisplayDate'))
{
    function makeDisplayDate($YYYYMMDD)
    {	

		 $monthNames = [
			"January", "February", "March",
			"April", "May", "June", "July",
			"August", "September", "October",
			"November", "December"
		  ];

		$m = substr( $YYYYMMDD, 5, 2);	
		$month; 		  

		switch ($m)	
		{
			case "01":
				$month = "January";
				break;
			case "02":
				$month = "February";
				break;
			case "03":
				$month = "March";
				break;
			case "04":
				$month = "April";
				break;
			case "05":
				$month = "May";
				break;
			case "06":
				$month = "June" ;
				break;
			case "07":
				$month = "July";
				break;
			case "08":
				$month = "August";
				break;
			case "09":
				$month = "September";
				break;
			case "10":
				$month = "October";
				break;
			case "11":
				$month = "November";
				break;
			case "12":
				$month = "December";					
		}/*End of switch */	  

		$day = substr( $YYYYMMDD,	8,2);		  
		$year = substr( $YYYYMMDD, 0 , 4 );

		$Display_date = " ".$year."-".$month."-".$day." ";
		return $Display_date;
		
    }
}

?>