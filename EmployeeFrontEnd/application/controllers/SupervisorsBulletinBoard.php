<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/// The SupervisorsBulletinBoard controller.
	class SupervisorsBulletinBoard extends CI_Controller {		
		var $TPL;
		///Contructor 
		public function __construct()
		{ 	parent::__construct();	}		
		/// Index
		public function index() {			
			$this->TPL['Notifications'] = $this->notifications->GetNotifications();		
			$this->template->show('SupervisorPages/SupervisorsBulletinBoard_view', $this->TPL);
		}
		
		/// Functions
		
		/** Function PostToBulletion
			Purpose: Allows supervisor to Post to the bulletin board.
		*/
		public function PostToBulletion( )
		{
			$EmployeeID = $_REQUEST["employeeID"];
			$BulletionPost = $_REQUEST["BulletionPost"];
			$feedback = 0; //When feedback is 1 post was successful other wise error.
			 
			///Confrim ID is numeric
			if( is_numeric($EmployeeID) )
			{
				/// Make sure ID has supervisor roll.
				/// Make helper for this...			
				$CI =& get_instance();
				$SELECTSTATEMENT = "SELECT `Supervisor` FROM `Employees` WHERE `employeeID` = '".$EmployeeID."';";
				$query = $CI->db->query($SELECTSTATEMENT);
				$list = $query->result_array();		
				if( $list[0]["Supervisor"] == 1 )
				{
					/// TODO: Safety check for data.					
					/// Insert...
					try {
						$CI =& get_instance(); /// TODO: FIX security.					
						$DateAndTime = date("Y-m-d H:i:s");					
						$data = array ( 					
								'employeeID' => $EmployeeID,
								'Message' => $BulletionPost,
								'CreatedDateAndTime' => $DateAndTime ,			
						);					
						$sql = $CI->db->insert( 'SupervisorBulletionBoard'  , $data );	
						$feedback = 1;
					}
					catch(Exception $e)
					{
						$feedback = 0;
					}
				}
				else
				{
					$feedback = 0;/// there was Incorrect Post data.
				}
			}///End of confirm ID is numeric		
			else
			{
				$feedback = 0;///There was Incorrect Post data.
			}
			echo $feedback;
		}/* End of Function PostToBulletion */
		
		
		
		/** Function ReloadBulletionBoard
			Purpose: Gets the bulletin board posts.
		*/
		public function ReloadBulletionBoard( )
		{
			$CI =& get_instance(); /// TODO: FIX security.					
			$SELECTSTATEMENT = "SELECT   s.employeeID, e.Firstname, e.Lastname, s.CreatedDateAndTime, s.MessageID, s.Message FROM SupervisorBulletionBoard s JOIN  Employees e on e.employeeID = s.employeeID;";
			$query = $CI->db->query($SELECTSTATEMENT);
			$list = $query->result_array();		
			
			
			
			/* Sort by date and time, Newest first.*/			
			function date_compare($a, $b)	
			{
				$time1 = strtotime($a['CreatedDateAndTime']);
				$time2 = strtotime($b['CreatedDateAndTime']);
				return $time2 - $time1;
			}   
			
			usort($list, 'date_compare');
								
			echo json_encode($list);			
			
		}/*End of Function ReloadBulletionBoard*/
		
		
		
		
		/** Function DeleteBulletionPost
			Purpose: deletes the bulletins out of the database.
		*/
		public function DeleteBulletionPost( )
		{
			$MessageID = $_REQUEST["MessageID"];
			$Feedback = 1;
			if( is_numeric( $MessageID ) ) 
			{
				try {
					$CI =& get_instance(); /// TODO: FIX security.					
					$DELETESTATEMENT = "DELETE FROM `SupervisorBulletionBoard` WHERE `MessageID` = '".$MessageID."';";
					$query = $CI->db->query( $DELETESTATEMENT );
					$Feedback = 0;
				}
				catch(Execption $e)
				{
					$Feedback =  1;
				}
			}
			else
			{ $Feedback = 1; }				
			echo $Feedback;
		}/*End of Function DeleteBulletionPost*/
		
		
		
		
	}/// End of class SupervisorsBulletinBoard
?>
