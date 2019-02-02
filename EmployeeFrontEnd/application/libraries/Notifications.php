<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/** Notifications class

	The new Notification.
	1. SubSlip was taken.
	2. Your submitted subslip was approved.
	3. Your submitted subslip  was rejected.
 	4. The subslip you took was approved.
	5. The subslip you took was rejected.


*/
class Notifications { 	
		
    function __construct() 
    {
				error_reporting(E_ALL & ~E_NOTICE);
    }
		
		
		
		
		/** Function notificationForCreatingAShift
				Notification... 
		    Make Notification for creating a shift...
				No longer in use.
		*/
		public function notificationForCreatingAShift($ShiftDate, $startTime, $endTime, $STRposition, $takerID)
		{			
			$NoteShiftDate = makeDisplayDate($shiftDate);								
			$st = convertTimeToDisplayTime($startTime);
			$et = convertTimeToDisplayTime($endTime);
			
			$DisplayMessageB = "You were assigned a Shift for ".$NoteShiftDate." from ".$st." to ".$et." as ".$STRposition.".";				
			$data = array ( 					
					'employeeID' => $takerID,
					'type' => 5,
					'message' => $DisplayMessageB,
					'readOrUnread' => 0,
					'CreatedDateAndTime' => date("Y-m-d H:i:s"),			
			);				
			$CI =& get_instance(); //TODO: FIX security.			
			$sql = $CI->db->insert( 'Notifications'  , $data );							
						
    }// End of function notificationForCreatingAShift	

		
		
		
		
		/**	Function yourSubSlipwastaken
				Notification...				
			 	1. Your SubSlip was taken.	
		*/
    public function yourSubSlipwastaken( $ShiftDate, $startTime, $endTime, $STRposition, $TakerFirstname, $ownerID )
    {
			$NoteShiftDate = makeDisplayDate($shiftDate);			
			$st = convertTimeToDisplayTime($startTime);
			$et = convertTimeToDisplayTime($endTime);
			
			$DisplayMessage = "Your subslip was taken for ".$NoteShiftDate." from ".$st." to ".$et." as ".$STRposition.". The Subslip was taken by ".$TakerFirstname.".";				
			$data = array ( 					
					'employeeID' => $ownerID,
					'type' => 1,
					'message' => $DisplayMessage,
					'readOrUnread' => 0,
					'CreatedDateAndTime' => date("Y-m-d H:i:s"),									
			);						
			$CI =& get_instance(); //TODO: FIX security.			
			$sql = $CI->db->insert( 'Notifications'  , $data );	//Insert the shift into the database.					
			
		}/// End of function yourSubSlipwastaken

		
		
		
		/** Function yourSubslipwasApproved
		  	Notification...		
				2. Your subslip was approved...
		 */
    public function yourSubslipwasApproved($shiftDate, $startTime, $endTime, $STRposition, $takerFirstname, $ownerID)
    {			
			$NoteShiftDate = makeDisplayDate($shiftDate);								
			$st = convertTimeToDisplayTime($startTime);
			$et = convertTimeToDisplayTime($endTime);
		
			$DisplayMessage = "Your subslip with ".$TakerFirstname." for ".$NoteShiftDate." from ".$st." to ".$et." as ".$STRposition.", Has been APPROVED!";				
			$data = array ( 					
					'employeeID' => $ownerID,
					'type' => 2,
					'message' => $DisplayMessage,
					'readOrUnread' => 0,
					'CreatedDateAndTime' => date("Y-m-d H:i:s"),
			);			
			$CI =& get_instance(); //TODO: FIX security.			
			$sql = $CI->db->insert( 'Notifications'  , $data );				
			
		}///End of function yourSubslipwasApproved
		
	
		
		/**	Function yourSubslipwasRejected($
				Notification...				
				3. Your submitted subslip  was rejected.
		 */					
		public function yourSubslipwasRejected($TakerFirstname, $shiftDate, $startTime, $endTime, $STRposition, $reason, $ownerID )
		{
			$NoteShiftDate = makeDisplayDate($shiftDate);			
			$st = convertTimeToDisplayTime($startTime);
			$et = convertTimeToDisplayTime($endTime);
			
			$DisplayMessage = "Your subslip with ".$TakerFirstname." for ".$NoteShiftDate." from ".$st." to ".$et." as ".$STRposition.", Has been rejected by a supervisor. The reason why was ".$reason." ";				
			
			
			$data = array ( 					
					'employeeID' => (int)$ownerID,
					'type' => 3,
					'message' => $DisplayMessage,
					'readOrUnread' => 0,
					'CreatedDateAndTime' => date("Y-m-d H:i:s"),				
			);				
			$CI =& get_instance(); //TODO: FIX security.			
			$sql = $CI->db->insert( 'Notifications'  , $data );					
			
		}// End of function yourSubslipwasRejected
		
		
		
		/** Function subSlipyousignedwasApproved
				Notification...				
				4. The subSlip you signed was approved. 
		 */	
		public function subSlipyousignedwasApproved( $Firstname, $shiftDate, $STRposition, $startTime, $endTime, $TakerID )
		{
			$NoteShiftDate = makeDisplayDate($shiftDate);			
			$st = convertTimeToDisplayTime($startTime);
			$et = convertTimeToDisplayTime($endTime);
			
			$DisplayMessage = "The subslip you signed  with ".$Firstname." for a ".$STRposition." shift on ".$NoteShiftDate." from ".$st." to ".$et.", Has been APPROVED!";				
		
			$data = array ( 					
					'employeeID' => (int)$TakerID,
					'type' => 8,
					'message' => $DisplayMessage,
					'readOrUnread' => 0,
					'CreatedDateAndTime' => date("Y-m-d H:i:s"),	
			);	
			$CI =& get_instance(); //TODO: FIX security.			
			$sql = $CI->db->insert( 'Notifications'  , $data );	
		
		}// End of function subSlipyousignedwasApproved
		
		

		
		
		/** Function theSubslipyouTookwasRejected
				Notification...				
				5. The subslip you took was rejected.
		 */					
		public function theSubslipyouTookwasRejected($Firstname, $STRposition, $shiftDate, $startTime, $endTime, $reason, $takerID )
		{
				$NoteShiftDate = makeDisplayDate($shiftDate);			
				$st = convertTimeToDisplayTime($startTime);
				$et = convertTimeToDisplayTime($endTime);
				
				$DisplayMessage = "The subslip you signed  with ".$Firstname." for a ".$STRposition." shift on ".$NoteShiftDate." from ".$st." to ".$et.", Has been rejected by a supervisor. The reason why was ".$reason." ";				
					
				$data = array ( 					
						'employeeID' => (int)$takerID,
						'type' => 9,
						'message' => $DisplayMessage,
						'readOrUnread' => 0,
						'CreatedDateAndTime' => date("Y-m-d H:i:s"),			
				);
				
				$CI =& get_instance(); //TODO: FIX security.
				$sql = $CI->db->insert( 'Notifications'  , $data );	
				
		}// End of function theSubslipyouTookwasRejected


		
		
		/** Function GetNotifications
			Purpose: Gets all the notifications for the currently logged in employee, and changes 
			         all there unread status to read.
					 
					OLD and CURRENT Types of  Notification messages.
					 	1. SubSlip was taken.
					 	2. SubSlip was approved.
					 	3. SubSlip was rejected.
					 	4. You were removed from a shift by a supervisor. <--- REMOVED
					 	5. You were assigned a shift by a supervisor.     <--- REMOVED
						6. You submitted a subslip.                       <--- REMOVED
						7. You took someone's SubSlip.                    <--- REMOVED
						8. The subSlip you took was approved.
						9. The Subslip you took was rejected.					
					 New types of notifications.
						1. SubSlip was taken.
						2. Your submitted subslip was approved.
						3. Your submitted subslip  was rejected.
						4. The subslip you took was approved.
						5. The subslip you took was rejected.
					 
		*/
		public function GetNotifications( ) {		
			session_start();		
			$ID = $_SESSION['EmployeeID'];										 				
			$CI =& get_instance(); //TODO: FIX security.
			$query = $CI->db->query("SELECT * FROM `Notifications` WHERE  employeeID =".$ID.";");
			$list = $query->result_array();								
			/* Sort by date and time, Newest first.*/
			function date_compare($a, $b) {
				$time1 = strtotime($a['CreatedDateAndTime']);
				$time2 = strtotime($b['CreatedDateAndTime']);
				return $time2 - $time1;
			}   
			usort($list, 'date_compare');			
			///$query = $CI->db->query("UPDATE `Notifications` SET `readOrUnread`= '1' WHERE `employeeID` = ".$ID.";");									
			/*Now delete the oldest notifications if the size is greater than 50*/
			if(  sizeof($list) > 50 ) {
				$lstSize = sizeof($list);
				$Subtrack = $lstSize - 50;	
				/*Start at the 30th oldest notification and delete all after that.*/
				for( $i = 30; $i < $lstSize; $i++ ) {
					$Nid = $list[$i]["NotificationID"];
					$query = $CI->db->query("DELETE FROM `Notifications` WHERE NotificationID = ".$Nid.";");
				}
			}									
			return json_encode($list, JSON_HEX_APOS); // JSON_HEX_APOS fixes single quotes problem						 
		}/*End of Function GetNotifications*/
		
		
		
		/// Sets nottfications to Old
		public function ClearNotifications( $ID )
		{
			if(is_numeric($ID) && $ID != null)
			{ 
				$CI =& get_instance(); //TODO: FIX security.
				$query = $CI->db->query("UPDATE `Notifications` SET `readOrUnread`= '1' WHERE `employeeID` = ".$ID.";");									
			}
		}/*End of Function ClearNotifications*/
		
}/*End of Notifications library */
?>