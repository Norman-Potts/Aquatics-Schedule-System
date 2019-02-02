<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class ApproveSubSlips extends CI_Controller { /* This manages the Approve SubSlip page.  */		
		var $TPL;
		public function __construct() { parent::__construct(); }
		public function index()
		{											
		
			$this->TPL['UnSignedSubslips'] = $this->GetUnsignedSubslips();	
		
			$this->template->show('ApproveSubSlips_view', $this->TPL);
		}
	
		public function GetUnsignedSubslips() {			
			$CI =& get_instance();												
			$theDate = $this->theschedule->YYYMMDDTodayplz();	
			$SELECTSTATEMENT = "SELECT e.Firstname, e.Lastname,  s.ShiftDate, s.startTime, s.endTime, s.Position, s.Reason,  s.CreatedDateAndTime  FROM `SubSlips` s JOIN `Employees` e ON s.CreatorID = e.employeeID WHERE `TakenTrueorFalse` = '0' AND `completed` = False AND `ShiftDate` > '".$theDate ."' ;";												
			$query = $CI->db->query($SELECTSTATEMENT);
			$list = $query->result_array();	
			$Slips = [];
			foreach( $list as $key  => $SubSlip) {				
				$Slip = [];			
				$name = ''.$SubSlip["Firstname"].' '.$SubSlip["Lastname"].'';								
				$reason =  $SubSlip["Reason"];	
				$startTime = $SubSlip["startTime"];	
				$endTime = $SubSlip["endTime"];	
				$d =  $SubSlip["ShiftDate"];	
				$position = $SubSlip["Position"];	
				$cdt =  $SubSlip["CreatedDateAndTime"];					
				$p = '';
				switch($position) {
					case (1):
						$p = "Lifeguard";
						break;
					case(2):
						$p = "Instructor";
						break;
					case(3): 
						$p = "Headguard";
						break;	
					case(4):
						$p =  "Supervisor";
						break;				
				}				
				$Slip = [
					"name" => $name,
					"Reason" =>  $reason,
					"startTime" => $startTime,
					"endTime" => $endTime,
					"Position" => $p,
					"cdt" => $cdt,
					"ShiftDate" => $d
				];				
				array_push( $Slips, $Slip );
			}			 
			/* Sort by date and time, Newest first.*/
			function date_compare($b, $a)	
			{
				$time1 = strtotime($a['ShiftDate']);
				$time2 = strtotime($b['ShiftDate']);
				return $time2 - $time1;
			}   
			usort($Slips, 'date_compare');			 			 
			return json_encode($Slips);
		}
		
		
		
		
		
		
		
		
		
		public function DoSubSlipSwitch() {
			$subslipID = $_REQUEST['subslipID'];
			$ownerID = $_REQUEST['ownerID'];
			$TakerID = $_REQUEST['TakerID'];
			$ShiftID = $_REQUEST['ShiftID'];			
			$subslipID = (int)$subslipID;
			$ownerID = (int)$ownerID;
			$TakerID = (int)$TakerID;
			$ShiftID = (int)$ShiftID;
			$instructions = 1;
			try {				
				$SELECTSTATEMENT = "SELECT * FROM `Shifts` WHERE `ShiftID` = '".$ShiftID."';";				
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
				$CI =& get_instance(); //TODO: FIX security.
				$query = $CI->db->query($SELECTSTATEMENT);
				$list = $query->result_array();			
				$Firstname = $list[0]["Firstname"];								
				$SELECTSTATEMENT = "SELECT * FROM `Employees` WHERE `employeeID` = ".$TakerID.";";
				$CI =& get_instance(); //TODO: FIX security.
				$query = $CI->db->query($SELECTSTATEMENT);
				$list = $query->result_array();			
				$TakerFirstname = $list[0]["Firstname"];		
				
				/** Notificcation...  2. you subslip was approved */
				$DisplayMessage = "Your subslip with ".$TakerFirstname." for ".$shiftDate." from ".$startTime." to ".$endTime." as ".$STRposition.", Has been APPROVED!";				
				$data = array ( 					
						'employeeID' => $ownerID,
						'type' => 2,
						'message' => $DisplayMessage,
						'readOrUnread' => 0,
						'CreatedDateAndTime' => date("Y-m-d H:i:s"),
									
				);					
				$sql = $CI->db->insert( 'Notifications'  , $data );	
				
				/** Notificcation...  8. The subSlip you signed was approved.  */				
				$DisplayMessage = "The subslip you signed  with ".$Firstname." for a ".$STRposition." shift on ".$shiftDate." from ".$startTime." to ".$endTime.", Has been APPROVED!";				
				$data = array ( 					
						'employeeID' => $TakerID,
						'type' => 8,
						'message' => $DisplayMessage,
						'readOrUnread' => 0,
						'CreatedDateAndTime' => date("Y-m-d H:i:s"),	
				);					
				$sql = $CI->db->insert( 'Notifications'  , $data );													
				$CI =& get_instance();		

											
				$SELECTSTATEMENT = "SELECT `historyArr` FROM `Shifts` WHERE `ShiftID` = ".$ShiftID.";";				
				$query = $CI->db->query($SELECTSTATEMENT);
				$Histlist = $query->result_array();	
				$strArr = $Histlist[0]["historyArr"];
				
				$HistoryArr = json_decode($strArr);
				$AddArr = array($TakerID, $subslipID );
				array_push($HistoryArr, $AddArr);
				$jsonHistoryArr = json_encode($HistoryArr);
		
				//// Update shifts DoSubSlipSwitch
				$UPDATE_shift_STATEMENT = "UPDATE `Shifts` SET  `CurrentOwnerEmployeeID` =  '".$TakerID."', `AssignedSubSlipID`= NULL, `historyArr` = '".$jsonHistoryArr."'  WHERE `ShiftID` = '".$ShiftID."' ;";		

				
				$UPDATE_subslip_STATEMENT = "UPDATE `SubSlips` SET  `completed` =  '1' WHERE `subslipID` = '".$subslipID."';";				
									
				$query = $CI->db->query($UPDATE_subslip_STATEMENT); 								
				$query = $CI->db->query($UPDATE_shift_STATEMENT); 										
			
														
			} catch(Execption $e)
			{ $instructions = 0; }			
			if( $instructions == 1)
			{	//Notify employees...
				//TODO: when notification system is set up finsih this.				
			}
			echo $instructions;//Reply to ajax front end...			
		}/* End of function DoSubSlipSwitch */
				
		
		
		
		
		
		
		
		function checkAutoApprove() {
			$onoff = $this->approvecontrol->getAutoAppove();
			echo $onoff;
		}
		
		
		function offAutoApprove() {
			$onoff = $this->approvecontrol->offAutoApprove();			
			echo $onoff;
		}
				
		function onAutoApprove() {
			$onoff = $this->approvecontrol->onAutoApprove();			
			echo $onoff;
		}
			
		public function GetALLSubSlips() {
			$CI =& get_instance();												
			$theDate = $this->theschedule->YYYMMDDTodayplz();					
			$SELECTSTATEMENT = "SELECT e.Firstname, e.Lastname, s.ShiftID, s.CreatorID, s.CreatorID, s.subslipID, s.TakerID, s.ShiftDate, s.startTime, s.endTime, s.Position, s.Reason, s.TakenTrueorFalse, s.TakenDateAndTime, s.CreatedDateAndTime  FROM `SubSlips` s JOIN `Employees` e ON s.CreatorID = e.employeeID WHERE `TakenTrueorFalse` = '1' AND `completed` = False AND `ShiftDate` > '".$theDate ."' ;";			
			$query = $CI->db->query($SELECTSTATEMENT);
			$list = $query->result_array();
			foreach( $list as $key  => $SubSlip) {
				$takerID = $SubSlip["TakerID"];
				$SELECTSTATEMENT = "SELECT `Firstname`, `Lastname` FROM `Employees` WHERE `employeeID` = ".$takerID.";";							
				$query = $CI->db->query($SELECTSTATEMENT);
				$TakerInfo = $query->result_array();
				$name = $TakerInfo[0]["Firstname"]." ".$TakerInfo[0]["Lastname"];
				$list[$key]["personTakingShift"] = $name;
			}							
			echo json_encode($list);
		}/// End of function GetALLSubSlips
		
		public function RejectSubSlip() {
			$subslipID = (int)$_REQUEST['subslipID'];
			$ownerID =(int) $_REQUEST['ownerID'];
			$reason = $_REQUEST['Rejectreason'];						
			$instructions = 0;
			$DELETESTATEMENT = "";
			//TODO: Tell owner of subslip their subslip got rejeted.
			if( is_numeric($subslipID) == true  && is_numeric($ownerID) == true ) {											
				$SELECTSTATEMENT = "SELECT * FROM `Employees` WHERE `employeeID` = ".$ownerID.";";
				$CI =& get_instance(); //TODO: FIX security.
				$query = $CI->db->query($SELECTSTATEMENT);
				$list = $query->result_array();			
				$Firstname = $list[0]["Firstname"];				
				$SELECTSTATEMENT = "SELECT `ShiftID`, `startTime`, `endTime`, `ShiftDate`, `Position`, `TakerID` FROM `SubSlips` WHERE `subslipID` = ".$subslipID.";";
				$CI =& get_instance(); //TODO: FIX security.
				$query = $CI->db->query($SELECTSTATEMENT);
				$list = $query->result_array();			
				$startTime = $list[0]["startTime"];
				$endTime = $list[0]["endTime"];
				$ShiftDate = $list[0]["ShiftDate"];
				$Position = $list[0]["Position"];
				$takerID  = $list[0]["TakerID"];
				$ShiftID = $list[0]["ShiftID"];
				$STRposition = "";				
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
				$SELECTSTATEMENT = "SELECT * FROM `Employees` WHERE `employeeID` = ".$takerID.";";
				$CI =& get_instance(); //TODO: FIX security.
				$query = $CI->db->query($SELECTSTATEMENT);
				$list = $query->result_array();			
				$TakerFirstname = $list[0]["Firstname"];							  
				$this->notifications->YourSubSlipwasRejected($TakerFirstname, $ShiftDate, $startTime, $endTime, $STRposition, $reason, $ownerID);																										                                         
				$this->notifications->TheSubSlipYouTookWasRejected($Firstname, $STRposition, $ShiftDate, $startTime, $endTime, $reason, $takerID);								
				$CI =& get_instance();		

				//// Update Shifts Reject
				$UpdateStatement = "UPDATE `Shifts` SET `AssignedSubSlipID`= NULL WHERE `ShiftID` = '".$ShiftID."';";
				$query = $CI->db->query(	$UpdateStatement	);								
				$DELETESTATEMENT = "DELETE FROM `SubSlips` WHERE `subslipID` = '".$subslipID."';";
				$query = $CI->db->query($DELETESTATEMENT);
				$instructions = 1;										
			} else 
			{ $instructions = 0; /* TODO: Write to error log.. Shouldnt happen. */ }								
			echo json_encode($instructions);//echo json_encode($instructions);			
		}		
	}
?>