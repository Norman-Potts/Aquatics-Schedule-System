<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
	Provide the asset url path when requested.
*/
if (!function_exists('convertnumbtoposition'))
{
    function convertnumbtoposition( $Position_Number)
    {
		$Position_Word = "";						
		if($Position_Number == 1)
		{
			$Position_Word = "Lifeguard";
		}
		else if ($Position_Number == 2)
		{
			$Position_Word = "Instructor";
		}
		else if ($Position_Number == 3)
		{
			$Position_Word = "Headguard";
		}
		else if ($Position_Number == 4)
		{
			$Position_Word = "Supervisor";
		}
		else
		{
			$Position_Word = "ERROR: position is not a correct number";			
		}
		return $Position_Word;
    }
}

?>