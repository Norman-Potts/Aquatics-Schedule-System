<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/// The AvailableShifts controller.
	class AvailableShifts extends CI_Controller {		
		var $TPL;
		///Contructor 
		public function __construct()
		{ 	parent::__construct();	}		
		/// Index
		public function index() {				

			$this->TPL['SubSlipsAvailable'] = $this->InitalGetAvailableSubSlips();
			$this->TPL['Notifications'] = $this->notifications->GetNotifications();		
			$this->template->show('AvailableShifts_view', $this->TPL);
		}
		
		/// Functions
		
		
		public function TakeSubSlip() {
			$takerID = $_REQUEST['takerID'];
			$ownerID = $_REQUEST['ownerID'];			
			$subslipID = $_REQUEST['subslipID'];
			$ShiftID = $_REQUEST['ShiftID'];
			$instructions = 0;			
			if( is_numeric($takerID ) == true && is_numeric( $subslipID) == true && is_numeric($ownerID ) == true ) {
				$AutoApprove = false; //Check status of approve subslips.				
				try { $AutoApprove = $this->approvecontrol->getAutoAppove(); }
				catch(Exception $e)
				{	$AutoApprove = false;	}//TODO: write to error log...				
				$TakenDateAndTime = date("Y-m-d H:i:s");
				try {			
					$SELECTSTATEMENT = "SELECT * FROM `Shifts` WHERE `ShiftID` = ".$ShiftID.";";
					$CI =& get_instance(); //TODO: FIX security.
					$query = $CI->db->query($SELECTSTATEMENT);
					$list = $query->result_array();								
					$shiftDate = $list[0]["date"];
					$startTime = $list[0]["startTime"];
					$endTime = $list[0]["endTime"];
					$Position = $list[0]["Position"];
					switch( $Position ) {
						case 1:
							$STRposition = "Lifeguarding";
							break;
						case 2:
							$STRposition = "Instructoring";
							break;
						case 3:
							$STRposition = "HeadGuarding";
							break;
						case 4:
							$STRposition = "Supervisoring";
							break;
						default:
							$STRposition = " ";							
					}				
					$SELECTSTATEMENT = "SELECT * FROM `Employees` WHERE `employeeID` = ".$ownerID.";";
					$CI =& get_instance(); /// TODO: FIX security.
					$query = $CI->db->query($SELECTSTATEMENT);
					$list = $query->result_array();			
					$Firstname = $list[0]["Firstname"];				
					$SELECTSTATEMENT = "SELECT * FROM `Employees` WHERE `employeeID` = ".$takerID.";";
					$CI =& get_instance(); /// TODO: FIX security.
					$query = $CI->db->query($SELECTSTATEMENT);
					$list = $query->result_array();			
					$TakerFirstname = $list[0]["Firstname"];				
					$CI =& get_instance();												
					$UPDATESTATEMENT = "";		
					if( $AutoApprove == false ) { /// Update the subslip to taken and set the takerID										
						$UPDATESTATEMENT = "UPDATE `SubSlips` SET  `TakenDateAndTime` = '".$TakenDateAndTime."' , `TakenTrueorFalse` = '1' ,  `TakerID` = '".$takerID."' WHERE `subslipID` = '".$subslipID."' ;";						
						$query = $CI->db->query($UPDATESTATEMENT);	
					} else {													
						/// Update subslip set the taken date and time, and takerID and declare			
						/// that the subslip has been completed.											
						$UPDATESTATEMENT = "UPDATE `SubSlips` SET `TakenDateAndTime` = '".$TakenDateAndTime."',  `TakenTrueorFalse` = '1' , `TakerID` = '".$takerID."', `completed` = '1' WHERE `subslipID` = '".$subslipID."' ;";
						$query = $CI->db->query($UPDATESTATEMENT);								
						/// Update the shift and set the current owner employeeID to the employeeID
						/// of the employee taking the shift.
						
						
						
						
						
						
						
						
						
						$CI =& get_instance();												
						$SELECTSTATEMENT = "SELECT `historyArr` FROM `Shifts` WHERE `ShiftID` = ".$ShiftID.";";
						$CI =& get_instance(); /// TODO: FIX security.
						$query = $CI->db->query($SELECTSTATEMENT);
						$list = $query->result_array();	
						$strArr = $list[0]["historyArr"];
						
						$HistoryArr = json_decode($strArr);
						$AddArr = array($takerID, $subslipID );
						array_push($HistoryArr, $AddArr);
						$jsonHistoryArr = json_encode($HistoryArr);
						
						
					
						$UPDATE_shift_STATEMENT = "UPDATE `Shifts` SET   `CurrentOwnerEmployeeID` = '".$takerID."', `AssignedSubSlipID`= NULL, `historyArr` = '".$jsonHistoryArr."'  WHERE `ShiftID` = '".$ShiftID."' ;";																
						$query = $CI->db->query($UPDATE_shift_STATEMENT);						
						
						
						
					
						/// Make notification...
						/// Make notifications for yourSubslipwasApproved
						$this->notifications->yourSubslipwasApproved($shiftDate, $startTime, $endTime, $STRposition, $TakerFirstname, $ownerID);													
						
						

						
					}/// End of else for if auto-approve false.					
					/// Make notification for yourSubSlipwastaken.					
					$this->notifications->yourSubSlipwastaken( $shiftDate, $startTime, $endTime, $STRposition, $TakerFirstname, $ownerID );															
					$instructions = 1;
				} catch(Exception $e) { /* TODO: write to error log. */ $instructions = 0; }
			}							
			echo $instructions;
		}///  End of function takeSubSlip		
		
		
		
		
		
		
		
		
		public function InitalGetAvailableSubSlips() {
			session_start();
			$EmployeeID   = $_SESSION['EmployeeID'];
			$Lifeguard    = $_SESSION['Lifeguard'];
			$Instructor   = $_SESSION['Instructor'];
			$Headguard    = $_SESSION['Headguard'];
			$Supervisor    = $_SESSION['Supervisor'];
			$arr = $this->GetSubSlips( $EmployeeID , $Lifeguard, $Instructor, $Headguard, $Supervisor  );
			return $arr;
		}				
		public function GetAvailableSubSlips() {
			$EmployeeID   = $_REQUEST['ID'];
			$Lifeguard    = $_REQUEST['Lifeguard'];
			$Instructor   = $_REQUEST['Instructor'];
			$Headguard    = $_REQUEST['Headguard'];
			$Supervisor    = $_REQUEST['Supervisor'];
			echo $this->GetSubSlips( $EmployeeID , $Lifeguard, $Instructor, $Headguard, $Supervisor  );
		}		
		public function GetSubSlips( $EmployeeID , $Lifeguard, $Instructor, $Headguard, $Supervisor  ) {			
			$CI =& get_instance();			
			$TodayDate = $this->theschedule->YYYMMDDTodayplz();	
			/*  Make a SQL Select Statement that gets the necessary information from SubSlip table and 
				Employee table. The current Logic structure below adds "position equals digit" depending 
				on the certifications passed by the $_REQUEST array.  */
			$strlife ="";
			if( $Lifeguard == true	)
			{ $strlife = "Position = 1 OR ";	 }
			else
			{ $strlife = ""; }
			$strIns = "";
			if($Instructor == true)
			{ $strIns = "Position = 2 OR "; }
			else
			{ $strIns = ""; }
			$strHe = "";
			if($Headguard == true)
			{ $strHe = "Position = 3 OR "; }
			else
			{ $strHe = ""; }
			$strSup = "";
			if($Supervisor == true)
			{ $strSup = "Position = 4 OR "; } 
			else
			{ $strSup = ""; }		
			$posStr = " ".$strlife." ".$strIns." ".$strHe." ".$strSup." Position = 10";						
			$SELECTSTATEMENT = "SELECT S.CreatorID, S.ShiftID, S.subslipID, S.ShiftDate, S.startTime, S.endTime, S.Position, S.Reason, S.CreatedDateAndTime, E.Firstname,  E.Lastname, E.Instructor, E.Lifeguard, E.Headguard, E.Supervisor FROM `SubSlips` S  JOIN `Employees` E ON S.CreatorID = E.employeeID  WHERE `TakenTrueorFalse` = '0' AND ShiftDate >  '".$TodayDate."' AND (".$posStr.");";
			$query = $CI->db->query(	$SELECTSTATEMENT	);
			$ALL_SubSlipsthisEmployeeHasCertsFor = $query->result_array();		
			/*  Need a way to remove subslips that this employee cannot take based on the shifts the  employee already has.  */			
			foreach( $ALL_SubSlipsthisEmployeeHasCertsFor as $key => $SubSlip ) {															
				$STime = $ALL_SubSlipsthisEmployeeHasCertsFor[$key]["startTime"];
				$ETime = $ALL_SubSlipsthisEmployeeHasCertsFor[$key]["endTime"];	
				$date =  $ALL_SubSlipsthisEmployeeHasCertsFor[$key]["ShiftDate"];							
				/* SHIFT CONFLICT SQL HERE */
				$SELECTSTATEMENT = "SELECT count(*)  FROM Shifts  WHERE `CurrentOwnerEmployeeID` = '".$EmployeeID."' AND `date` = '".$date."'  AND(	 `startTime` <= '".$STime."' AND  '".$STime."' < `endTime` OR `endTime` >  '".$STime."'  AND '".$ETime."' >  `startTime` );";				
				$query = $CI->db->query(	$SELECTSTATEMENT	);
				$ConflictCount = $query->result_array(); 
				if ( $ConflictCount[0]["count(*)"]  == '0') {															
					$ShiftTime = "";
					$ST = $ALL_SubSlipsthisEmployeeHasCertsFor[$key]["startTime"];
					$ET = $ALL_SubSlipsthisEmployeeHasCertsFor[$key]["endTime"];			
					$CST = $this->theschedule->convertTimeToDisplayTime($ST);
					$EST = $this->theschedule->convertTimeToDisplayTime($ET);				
					$ShiftTime .= "".$CST." - ".$EST."";						
					$ALL_SubSlipsthisEmployeeHasCertsFor[$key]["ShiftTime"] = $ShiftTime;//<-- Making a New Key for ShiftTime...
					$ALL_SubSlipsthisEmployeeHasCertsFor[$key]["Conflict"] = false;
					$ALL_SubSlipsthisEmployeeHasCertsFor[$key]["DisplayDate"] =  makeDisplayDate( $date );					
				} else
				{	$ALL_SubSlipsthisEmployeeHasCertsFor[$key]["Conflict"] = true;   }			
			}	
			
			return json_encode($ALL_SubSlipsthisEmployeeHasCertsFor);		
		}/* End of function GetAvailableSubSlips */
	
	
	
	

		


		
	}//// End of class AvailableShifts
?>