<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ManageSchedule extends CI_Controller {   /* This manages the ManageSchedule page.  *//*DefaultOwnerEmployeeID removed from table*/
	var $TPL;
	public function __construct() {   parent::__construct();   }
	public function index() {			
		$this->todayDate = $this->theschedule->YYYMMDDTodayplz(); 
		$this->TPL['TodayDisplayDate'] =  $this->theschedule->GetTodaysDate(); 						
		$this->TPL['TodayYyyymmdd'] = $this->todayDate;		
		$this->TPL['TodaysScheduleArray'] = $this->theschedule->QueryByGivenDay( $this->todayDate ); 
		$this->TPL['DefaultEmployeeArr'] = $this->getEmployeeArr( $this->todayDate, "00:00:00", "00:30:00", 1	);		
		$this->template->show('ManageSchedule_view', $this->TPL);
	}		
	
	
	public function reloadEmployeeSelectBox() {   		
		$yyyymmdd = $_REQUEST['yyyymmdd'];	 $StartTime = $_REQUEST['StartTime']; $EndTime = $_REQUEST['EndTime']; $ShiftType = $_REQUEST['ShiftType'];				
		$arr = 0;//// Todo Set of security test to check variables.
		try {		
			$arr = $this->getEmployeeArr( $yyyymmdd, $StartTime,  $EndTime, $ShiftType	);			
		} catch(Exception $e) { $arr = 0; }			
		echo $arr;
	}
		
						
	public function getEmployeeArr( $date, $STime,  $ETime, $ShiftType ) {						
		$arr = 0;
		try {		
			$CI =& get_instance(); 														
			$queryForShiftType = ""; 			
			switch( $ShiftType ) {
				case "1":
					$queryForShiftType = "AND `Lifeguard` IS true";
					break;
				case "2": $queryForShiftType = "AND `Instructor` IS true";
					break;
				case "3":
					$queryForShiftType = "AND `HeadGuard` IS true";
					break;
				case "4":
					$queryForShiftType = "AND `Supervisor` IS true";
					break;				
				default:
					$queryForShiftType = " ";			
			}			
$SELECTSTATEMENT = "SELECT Firstname, Lastname, employeeID, Lifeguard, Instructor, Headguard, Supervisor, Availability  FROM `Employees` WHERE employeeID ";
$SELECTSTATEMENT .= "NOT IN( SELECT CurrentOwnerEmployeeID  FROM Shifts  WHERE `date` = '".$date."'";
$SELECTSTATEMENT .= "AND ( `startTime` <= '".$STime."' AND  '".$STime."' < `endTime` OR `endTime` >  '".$STime."' ";
$SELECTSTATEMENT .= " AND '".$ETime."' >  `startTime` ))".$queryForShiftType.";";							
			try {  ///// RECORDS SELECT STATEMENTS! YAAY
				$filename = "/var/www/html/WebPortfolio/Aquatics_Schedule_System/SupervisorsControls/application/controllers/SupervisorControllers/selectstatements_for_get_employee_arr.txt";
				file_put_contents($filename, " ---> ||".$SELECTSTATEMENT."||\n", FILE_APPEND);
			}catch(Exception $e)
			{echo $e;}						
			$query = $CI->db->query(	$SELECTSTATEMENT	);
			$list = $query->result_array();	


			//// Determine this weeks saturday n sundayy  
			$time = strtotime($date); //// Parse the date variable into a Unix timestamp
			$start = ''; $end = '';	  /// Declare start and end variables.
			if( date('D', $time) == "Sun" ) /* If Date time is equal to Sun then start is the given date. Other wise start is the last sunday. */
			{ $start =  $time; }
			else 
			{ $start = strtotime('last sunday', $time); }				
			if( date('D', $time) == "Sat" ) /* If date time is is saturday than end is the given date. Other wise, end is the next sunday. */
			{ $end = $time; } 
			else
			{ $end = strtotime('next saturday', $time); }
			$format = 'Y\-m\-d'; 
			$Sunday = date($format, $start); $Saturday = date($format, $end);	/// Use the unix time stamps for the saturday and sunday to produce yyyymmdd dates for them.
			//// Saturday and sunday are determined.
			
			
			
			
			
			$EmployeIDlist = array_column($list, 'employeeID'); 
			$ids = "'".join("','",$EmployeIDlist)."'";		
			
			
			
			
			
			//// Get the shifts between this week's dates
			$SELECTSTATEMENT = "SELECT `CurrentOwnerEmployeeID`, `startTime`, `endTime`  FROM `Shifts`";
			$SELECTSTATEMENT .= "WHERE  `date` >= '".$Sunday."' AND   `date` <= '".$Satuday."' AND `CurrentOwnerEmployeeID` IN (".$ids.");";												
			$query = $CI->db->query(	$SELECTSTATEMENT	);
			$shiftsBetweenDates = $query->result_array();				
			$mysqlTimeArray =  array( "00:00:00", "00:15:00", "00:30:00", "00:45:00", "01:00:00", "01:15:00","01:30:00", "01:45:00", "02:00:00", "02:15:00", "02:30:00", "02:45:00", "03:00:00", "03:15:00","03:30:00", "03:45:00", "04:00:00", "04:15:00", "04:30:00", "04:45:00","05:00:00", "05:15:00", "05:30:00", "05:45:00", "06:00:00", "06:15:00", "06:30:00", "06:45:00", "07:00:00", "07:15:00", "07:30:00", "07:45:00", "08:00:00", "08:15:00", "08:30:00", "08:45:00","09:00:00", "09:15:00", "09:30:00", "09:45:00", "10:00:00", "10:15:00", "10:30:00", "10:45:00", "11:00:00", "11:15:00", "11:30:00", "11:45:00", "12:00:00", "12:15:00", "12:30:00", "12:45:00","13:00:00", "13:15:00", "13:30:00", "13:45:00", "14:00:00", "14:15:00", "14:30:00", "14:45:00", "15:00:00", "15:15:00", "15:30:00", "15:45:00", "16:00:00", "16:15:00", "16:30:00", "16:45:00", "17:00:00", "17:15:00", "17:30:00", "17:45:00", "18:00:00", "18:15:00", "18:30:00", "18:45:00", "19:00:00", "19:15:00", "19:30:00", "19:45:00", "20:00:00", "20:15:00", "20:30:00", "20:45:00","21:00:00", "21:15:00", "21:30:00", "21:45:00", "22:00:00", "22:15:00", "22:30:00", "22:45:00","23:00:00", "23:15:00", "23:30:00", "23:45:00" );						
			$ShiftstartTime = ''; $ShiftstartTime = '';		
			$IDswithShifts = array_column($shiftsBetweenDates, 'CurrentOwnerEmployeeID');						
			foreach( $list as $em  => $Employee ) {
				$Av = $list[$em]["Availability"];
				$Availability = json_decode($Av);			
				$list[$em]["Availability"] = $Availability;											
				$DisEmployeeID = $list[$em]["employeeID"];			
				$key = array_search( $DisEmployeeID, $IDswithShifts );						
				if( $key === false ) 
				{  $list[$em]["HoursThisWeek"]	= 0;  }
				else {				
					$accumhours = 0;
					foreach( $shiftsBetweenDates as $SF => $Shift ) {
						if( $DisEmployeeID == $Shift["CurrentOwnerEmployeeID"]) {
							$Shiftstart = $Shift['startTime']; $Shiftend = $Shift['endTime'];
							$Sindex = array_search( $Shiftstart, $mysqlTimeArray );
							$Eindex = array_search( $Shiftend, $mysqlTimeArray );					
							$accumhours +=  ( $Eindex - $Sindex ) /4;
						}
					}
					$list[$em]["HoursThisWeek"]	= $accumhours;
				}			 				
			}				
			shuffle($list);						
			$arr = json_encode($list);				
		} catch(Exception $e) { $arr = 0; }					
		return  $arr;
	}
	
		
	public function reloadTheScheduleArraybyGivenDate() {		
		$GivenDate = $_REQUEST['GivenDate']; 
		$data = 0;				
		try {			
			if(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$GivenDate ))
			{ $data = $this->theschedule->QueryByGivenDay( $GivenDate ); }
			else
			{ $data = 0; }			
		} catch(Exception $e)
		{ $data = 0; }		
		echo  $data;
	} 


	public function deleteShift() {
		$ShiftID = $_REQUEST['ShiftID'];
		$response = 0;	
		try {
			$response = 0;		
			if(is_numeric($ShiftID)) {			
				$CI =& get_instance();  $ID = (int) ($ShiftID);			
				$data = array ( 'ShiftID' => $ID, );		
				$sql = $CI->db->delete( 'Shifts'  , $data );
				if($sql == true) { $response = 1; } else  { $response = 0; }			
			}
			else{ $response = 0; }			
		} catch(Exception $e) {
			$response = 0;	
		} finally {
			echo $response;	
		}				
	}/// End of deleteShift.	
	
	
	
	
	
	
	public function CreateTheShift( ) {
		$FeedBack = 0;
		try {
			$date = $_REQUEST['date']; $STime = $_REQUEST['StartTime'];	 $ETime = $_REQUEST['EndTime'];	 $ShiftType = $_REQUEST['ShiftType']; $emID  = $_REQUEST['ID'];	
			$ID = (int) $emID;
			$ShiftID = null;  $Position = (int) $ShiftType; 	
			//// TODO: Safety check Request variables....
			$FeedBack = 0;
			$FeedBack = $this->doCreateTheShift($date, $STime, $ETime, $ID, $ShiftID, $Position  );
		}catch(Exception $e){ $FeedBack = 0; }		
		echo $FeedBack;	
	}//// End of CreateTheShift
	public function doCreateTheShift( $date, $STime, $ETime, $ID, $ShiftID, $Position  ) {		
		$FeedBack = 0; 								
		try {			
			$SELECTSTATEMENT = "";												
			$CI =& get_instance(); 							
			//// Check for shift at same time.
			$SELECTSTATEMENT = " SELECT count(*) FROM Shifts  WHERE `CurrentOwnerEmployeeID` = '".$ID."'  AND `date` = '".$date."'  AND(	 `startTime` <= '".$STime."' AND  '".$STime."' < `endTime` OR  `endTime` >  '".$STime."'  AND '".$ETime."' >  `startTime`	 );";								
			$query = $CI->db->query(	$SELECTSTATEMENT	);
			$ShiftsAtSameTime = $query->result_array();																			
			if ( $ShiftsAtSameTime[0]["count(*)"]  == '0') {				
				$pos = ""; 
				switch( $Position ) {
					case 1: $pos = "Lifeguard";  break;
					case 2: $pos = "Instructor"; break;
					case 3: $pos = "Headguard";  break;
					case 4: $pos = "Supervisor"; break;											
				}	
				//// Confirm employee has this cerification for specified position.
				$SELECTSTATEMENT = "SELECT ".$pos."  FROM `Employees`	WHERE employeeID = ".$ID.";";
				$query = $CI->db->query(	$SELECTSTATEMENT	);
				$poslist = $query->result_array();		
				if (  $poslist[0][$pos] == '1') {					
					//// Preform insert.
					$HistoryArr = [];
					$InitialArr = array( $ID, null );
					array_push($HistoryArr, $InitialArr);
					$jsonHistoryArr = json_encode($HistoryArr);
					$data = array (  'CurrentOwnerEmployeeID' => $ID, 'ShiftID' => $ShiftID, 'startTime' => $STime, 'endTime' => $ETime, 'date' =>  $date, 'Position' => $Position, 'historyArr' => $jsonHistoryArr, );										
					$sql = $CI->db->insert( 'Shifts'  , $data );															
					///// Write notification.
					switch( $Position ) { 						
						case 1: $STRposition = "Lifeguarding"; break;
						case 2: $STRposition = "Instructoring"; break;
						case 3: $STRposition = "HeadGuarding";  break;
						case 4: $STRposition = "Supervisoring"; break;						
					}
					$DisplayMessage = "You were assigned a Shift for ".$date." from ".$STime." to ".$ETime." as ".$STRposition.".";				
					$data = array (  'employeeID' => $ID, 'type' => 5, 'message' => $DisplayMessage, 'readOrUnread' => 0, 'CreatedDateAndTime' => date("Y-m-d H:i:s"), );					
					$sql = $CI->db->insert( 'Notifications'  , $data );	
					$FeedBack = 1;			
				}
				else
				{ $FeedBack = 0; }
			} 
			else
			{ $FeedBack = 0; }					
		}
		catch(Exception $e) { $FeedBack = 0; }			
		return $FeedBack;	
	}//// End of doCreateTheShift
	
	

	
}/// End of class ManageSchedule
?>
