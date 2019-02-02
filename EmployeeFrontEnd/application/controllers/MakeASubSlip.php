<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/// The MakeASubSlip controller.
	class MakeASubSlip extends CI_Controller {		
		var $TPL;
		///Contructor 
		public function __construct()
		{ 	parent::__construct();	}		
		/// Index
		public function index() {				
			$this->TPL['Shifts'] = $this->GetShifts();		
			$this->TPL['Notifications'] = $this->notifications->GetNotifications();		
			$this->template->show('MakeASubSlip_view', $this->TPL);
		}
		
		/// Functions
		
		function GetThisEmployeesShifts()
		{
			$arr = $this->GetShifts();
			echo $arr;
		}
		
		/**  Function GetShifts			
				Purpose: Gets all the Shift's of this employee that they don't already have a subslip
				         and is is today or ahead.						 
				
				Returns an object with booleans for ShiftHasASubSlip DateOfShiftHasAlreadyPast			
				Parameters: $ID The id of the employee being queryed.
		*/
		public function GetShifts(  )
		{
			$CI =& get_instance();						
			session_start();
			$ID   = $_SESSION['EmployeeID'];									
			$TodayDate = $this->theschedule->YYYMMDDTodayplz();			
			$SELECTSTATEMENT = "SELECT * FROM `Shifts` WHERE `CurrentOwnerEmployeeID` = '".$ID."' AND   `AssignedSubSlipID` is NULL AND `date` >= '".$TodayDate."';";
			$query = $CI->db->query(	$SELECTSTATEMENT	);
			$list = $query->result_array();								
		
			$size = count($list);
			$AllShiftsThisUserHas;
			if ($size > 0) 	
			{
				$AllShiftsThisUserHas = array($size);			
				for( $key = 0; $key < $size; $key++ )
				{
							
					$AllShiftsThisUserHas[$key] = [];//Make an array for this elements key
					
					$AllShiftsThisUserHas[$key]["CurrentOwnerEmployeeID"] = $list[$key]["CurrentOwnerEmployeeID"];
					$AllShiftsThisUserHas[$key]["DefaultOwnerEmployeeID"] = $list[$key]["DefaultOwnerEmployeeID"];
					$AllShiftsThisUserHas[$key]["date"] = $list[$key]["date"];				
					$AllShiftsThisUserHas[$key]["startTime"]= $list[$key]["startTime"];
					$AllShiftsThisUserHas[$key]["endTime"]= $list[$key]["endTime"];				
					$AllShiftsThisUserHas[$key]["Position"]= $list[$key]["Position"];
					$AllShiftsThisUserHas[$key]["ShiftID"] = $list[$key]["ShiftID"];

					/// Add the display times to the returned array. 
					$st = convertTimeToDisplayTime($list[$key]["startTime"]);
					$et = convertTimeToDisplayTime($list[$key]["endTime"]);
					$AllShiftsThisUserHas[$key]["Time"] = $st." to ".$et;
					$AllShiftsThisUserHas[$key]["DisplayDate"] = makeDisplayDate( $list[$key]["date"] );
								
					$TypeofShift = "";
					$ShiftClass = "";				
						
					///Set CSS style class
					$Position =  $list[$key]["Position"];
					if( $Position == 1)
					{								
						$TypeofShift = "Lifeguard";
						$ShiftClass = "LifeguardShift";							
					}
					else if( $Position == 2)				
					{								
						$TypeofShift = "Instructor";
						$ShiftClass = "InstructorShift";							
					}
					else if( $Position == 3)
					{								
						$TypeofShift = "Headguard";
						$ShiftClass = "HeadGuardShift";							
					}
					else if( $Position == 4)				
					{							
						$TypeofShift = "Supervisor";
						$ShiftClass = "SupervisorShift";					
					}
					
					$AllShiftsThisUserHas[$key]["TypeofShift"] = $TypeofShift;
					$AllShiftsThisUserHas[$key]["ShiftClass"] = $ShiftClass;																								
				}
			}
			else
			{
				$AllShiftsThisUserHas = $size;
			}
			
			return json_encode($AllShiftsThisUserHas);			
			
		}//End of function GetShifts
		
		
		/** Function MakeThisSubSlip			
				Purpose: Checks SubSlip data, inserts subslips and returns a success, fail or 
				         error message.
				TODO: Evaulate seqcurity.
			echo MessageBack;
		*/
		public function MakeThisSubSlip()
		{
			$MessageBack = []; // Will hold an appropriate message and instructions for JS.			
			$MessageBack["Instructions"] = "";	// JS instructions.					
			//Get input array.
			$EmployeeID = $_REQUEST['EmployeeID'];
			$Reason = $_REQUEST['Reason'];
			$ShiftID = $_REQUEST['ShiftID'];
			$EmployeeID = (int) $EmployeeID;
			$ShifID = (int) $ShifID;						
			
			if(	$Reason == null || $Reason == "" || is_numeric($ShiftID) == false || is_numeric($EmployeeID) == false  )
			{	$MessageBack["Instructions"] = 3;	}
			else
			{
				$CI =& get_instance();						
				
					try 
					{
						//Get Shift info.
						$SELECTSTATEMENT = "SELECT * FROM `Shifts` WHERE `ShiftID` = '".$ShiftID."';";												
						$query = $CI->db->query(	$SELECTSTATEMENT	);
						$Shift = $query->result_array();												
						$ShiftDate = $Shift[0]["date"];
						$startTime = $Shift[0]["startTime"];
						$endTime = $Shift[0]["endTime"];
						$Position = $Shift[0]["Position"];
						$CreatedDateAndTime = date("Y-m-d H:i:s"); //Record the date and time this sub slip was created.										
						//Prepare to insert subslip.
						$myInsertArr = array(
							'CreatorID' => $EmployeeID,
							'TakerID' => NULL,
							'ShiftDate' => $ShiftDate,
							'startTime' => $startTime,
							'endTime'=> $endTime,
							'Position' =>$Position,
							'Reason' => $Reason,
							'TakenTrueorFalse' => 0,
							'TakenDateAndTime' => NULL,
							'CreatedDateAndTime'=> $CreatedDateAndTime,
							'ShiftID' => $ShiftID,
							'completed' => 0
						);						
						//Insert the subslip.
						$InsertStatement = $this->db->set($myInsertArr)->get_compiled_insert('SubSlips');																										
						$query = $this->db->query(	$InsertStatement	);														
						
						
								
							
							//SubSlip insert was a complete success.
							$MessageBack["Instructions"] = 1; //Display message. Reload SubSlip form Step 2.						
									
						
						///Update the Shift with the sub slip id of this shift.
						$SELECTSTATEMENT = "SELECT `subslipID` FROM `SubSlips` WHERE `ShiftID` = '".$ShiftID."';";												
						$query = $CI->db->query(	$SELECTSTATEMENT	);
						$list = $query->result_array();												
						$AssignedSubSlipID = $list[0]["subslipID"];												
						/// Update Statement
						$UpdateStatement = "UPDATE `Shifts` SET `AssignedSubSlipID`= '".$AssignedSubSlipID."' WHERE `ShiftID` = '".$ShiftID."';";
						$query = $CI->db->query(	$UpdateStatement	);
						
					}
					catch(Exception $e) 
					{    $MessageBack["Instructions"] = 5;	}														
			}				
			echo json_encode(  $MessageBack	); //Return message with instructions.
		}/* End of function makethissubslip*/
		
		
	}
?>