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
		
		
}/*End of Notifications library */
?>