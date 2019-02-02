<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Home extends CI_Controller {

		var $TPL;

		public function __construct()
		{
			parent::__construct();
			
		}

		public function index()
		{
			$this->TPL['Notifications'] = $this->notifications->GetNotifications();
			$this->TPL['LISTofDATES'] =  $this->theschedule->CreateSelectArray(); //Creates the select array variables.
			$this->TPL['CurrentYear'] = $this->theschedule->GetThisYear();
			$this->TPL['CurrentMonth'] = $this->theschedule->GetThisMonth();
			$this->TPL['CurrentDay'] = $this->theschedule->GetThisDay();				
			$this->TPL['board'] = $this->ReloadBulletionBoard();	
			$this->template->show('home_view', $this->TPL);			
		}

 
		/// Clears the notifications of the given ids
		public function ClearNewNotifications( )
		{
			session_start();	
			$ID = $_SESSION['EmployeeID'];
			if(is_numeric($ID) && $ID != null)
			{
				$this->notifications->ClearNotifications( $ID );	
			}
			echo " ";
			
		}/*End of Function ClearNewNotifications*/
		
 
		/** Function GetNewNotifications
			Purpose: 
		*/
		public function GetNewNotifications( )
		{		
			$newNotifications = $this->availability->GetNewNotifications();		
			echo  $newNotifications;			
		}/* End of Function GetNewNotifications. */
	
 
		/** Function PostToChatBox
			Purpose: Submits post to chat box table.
		*/
		public function PostToChatBox( )
		{
			$instructions = 0;
			try {
				$CI =& get_instance(); //TODO: FIX security.
				$ChatMessage = $_REQUEST['ChatMessage'];
				$EmployeeID = $_REQUEST['employeeID'];
				$DateAndTime = date("Y-m-d H:i:s");					
				$data = array ( 					
						'employeeID' => $EmployeeID,
						'Message' => $ChatMessage,
						'CreatedDateAndTime' => $DateAndTime ,			
				);					
				$sql = $CI->db->insert( 'ChatBoxMessages'  , $data );	
				$instructions = 1;
			}
			catch(Exception $e)
			{
				$instructions = 0;
			}
			
			return $instructions;
			
		}/*End of Function PostToChatBox*/
		
		
		
		
		
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
			if (function_exists('date_compare')){
				usort($list, 'date_compare');					
			}
			else
			{
				function date_compare($a, $b) {
					$time1 = strtotime($a['CreatedDateAndTime']);
					$time2 = strtotime($b['CreatedDateAndTime']);
					return $time2 - $time1;
				} 
				usort($list, 'date_compare');
			}
			
		
															
			return json_encode($list);			
			
		}/*End of Function ReloadBulletionBoard*/
		
		
		
		
		
		
		
		
		/** Function ReloadChatBox
			Purpose: Gets all the chat messages and sorts them in order.
		*/
		public function ReloadChatBox( )
		{			
			$CI =& get_instance();			
			$SELECTSTATEMENT = "SELECT * FROM `ChatBoxMessages`;";
			$query = $CI->db->query(	$SELECTSTATEMENT	);
			$list = $query->result_array();
			

			foreach( $list as $key => $item)
			{
				$ID = $list[$key]["employeeID"];
							
				$SELECTSTATEMENT = "SELECT * FROM `Employees` WHERE `employeeID` = '".$ID."';";				
				$query = $CI->db->query($SELECTSTATEMENT);
				$el = $query->result_array();			
				$Firstname = $el[0]["Firstname"];
				$Lastname = $el[0]["Lastname"];
				$DisplayName = $Firstname." ".$Lastname;
			
				$list[$key]["Name"] = $DisplayName;
		
			}
			 
			/* Sort by date and time, Newest first.*/
			function date_compare($a, $b)	
			{
				$time1 = strtotime($a['CreatedDateAndTime']);
				$time2 = strtotime($b['CreatedDateAndTime']);
				return $time2 - $time1;
			}   
			usort($list, 'date_compare');
			
			echo json_encode($list);
			
			
		}/*End of Function ReloadChatBox*/
		
		
		public function logout()
		{
			$this->userauth->logout();
		}
	
	}
	
	
	
?>