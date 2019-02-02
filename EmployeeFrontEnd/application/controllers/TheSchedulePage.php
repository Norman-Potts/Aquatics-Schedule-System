<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TheSchedulePage extends CI_Controller {	
	/* This manages the Schedule page.  */			
	var $TPL;
	public function __construct() {  parent::__construct();  }
	public function index() {    		
		$this->dayDate = $this->theschedule->GetFormatTodayDate();
		$this->TPL['TodaysSchedule'] = $this->theschedule->QueryByGivenDay( $this->dayDate );			
		$this->TPL['Notifications'] = $this->notifications->GetNotifications();
		$this->TPL['TodayDisplayDate'] = $this->theschedule->GetTodaysDate();
		$this->TPL['TodayYyyymmdd'] = $this->theschedule->YYYMMDDTodayplz();
		$this->template->show('schedule_view', $this->TPL);
	}	
	
	
	/** Function reloadTheScheduleArraybyGivenDate()
		purpose: reloads the schedule by a date given in js function reloadTheScheduleArray()		
		returns echo $data			
	*/
	public function reloadTheScheduleArraybyGivenDate() {		
		$GivenDate = $_REQUEST['GivenDate']; 
		$data = $this->theschedule->QueryByGivenDay( $GivenDate );		
		echo  $data;
	}//End of reloadTheScheduleArraybyGivenDate function
	
	
	
	
	public function getShiftHistoryArr() {
		$ID = $_REQUEST["ShiftID"];			
		$ShiftID = (int) $ID;
		/// Recived ShiftID. TODO Imporve security...
		
		$CI =& get_instance();												
		$SELECTSTATEMENT = "SELECT `historyArr` FROM `Shifts` WHERE `ShiftID` = '".$ShiftID."';";	
		$query = $CI->db->query($SELECTSTATEMENT);
		$list = $query->result_array();	
		$strArr = $list[0]["historyArr"];
		
		$HistoryArr = json_decode($strArr);
		
		$package = [];
		
		foreach( $HistoryArr as $key => $val ){			
			$employeeID = (int) $HistoryArr[$key][0];														
			$SELECTSTATEMENT = "SELECT `Firstname`, `Lastname` FROM `Employees` WHERE `employeeID` = '".$employeeID."';";	
			$query = $CI->db->query($SELECTSTATEMENT);
			$list = $query->result_array();															
			$f = $list[0]["Firstname"];
			$l = $list[0]["Lastname"];
			$name = "".$f." ".$l."";			
			array_push( $package, $name);
		}		
		
		echo json_encode($package);
	}
	
  
}/// End of TheSchedulePage
?>
