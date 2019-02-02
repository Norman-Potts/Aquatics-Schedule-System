<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class EmployeeAvailability extends CI_Controller {

		var $TPL;

		public function __construct()
		{
			parent::__construct();
			
			$this->TPL['EmList']= $this->loadList();
		}

		public function index()
		{							
			$this->template->show('EmployeeAvailability_view', $this->TPL);
		}

		

		/** Function loadList
			Purpose: select all employees from db
			         removes session current user.
			         returns array with firstname lastname and employeeID
		*/
		public function loadList()
		{
			//Query database for names and id
			$CI =& get_instance(); 
			$query = $CI->db->query("SELECT Firstname, Lastname, employeeID, Lifeguard, Instructor, Headguard, Supervisor FROM `Employees`   ");
			$list = $query->result_array();
			
			//list now holds the availability for each employee!								
			sort($list); //Sort Alphabetically
			return json_encode($list);

		}/*End of Function loadList */
		
		
		/** Function GetAvailability
			Purpose: Gets the availability of an employee.
		*/
		public function GetAvailability( )
		{	$ID = $_REQUEST['ID'];
			
			$CI =& get_instance(); //TODO: FIX security.
			$query = $CI->db->query("SELECT Availability FROM `Employees` WHERE  employeeID =".$ID.";");
			$list = $query->result_array();
				
			$Availability = $list[0]['Availability'];
			if(json_decode($Availability) == null )
			{
				$Availability = json_encode($Availability);
			}
			
			echo $Availability;
				
		}/*End of Function GetAvailability*/
		
		
		/** Function LoadForm
			Purpose: Loads the availability form with the current availability of the employeeID.		
		*/
		public function LoadForm( $ID )
		{ 				
			//Query db for employee's certs and name.
			$CI =& get_instance(); 
			$query = $CI->db->query("SELECT Username, Firstname, Lastname, Lifeguard, Instructor, Headguard, Supervisor, employeeID FROM `Employees` WHERE employeeID =".$ID."" );
			$list = $query->result_array();
			
			//Get their Availability...
			$Availability = "Availability";
			$query = $CI->db->query("SELECT Availability FROM `Employees` WHERE employeeID =".$ID."" );
			$JSON_str_Availability = $query->result_array();
			/*	
				The Availability will be saved as a json string in the database.
			*/
			$Jstr = $JSON_str_Availability[0];
			$Availability = json_decode($Jst);
			
			if ( $query->num_rows() > 1 )
			{
				//throw error there is two accounts with the same employeeID
				$this->load->view('ErrorPage');
				//TODO: Write to error log.		
			}else{
				$this->TPL['Availability'] = $Availability;
				$this->TPL['EmForm'] = $list[0];		
				$this->template->show('EmployeeAvailability_view', $this->TPL);					
			}							
		}/*End of Function LoadForm */
		
		
		
		
		/** Function SubmitForm
			Purpose: Saves the availability of this employee in the table.
		*/
		public function SubmitForm( )
		{ 				
		}/*End of Function SubmitForm */
		
		/** Note to self created new macro Alt Shift F. create function. */
		
		/** Function SetAvailability
			Purpose: Inserts the availability string for the given ID.
		*/
		public function SetAvailability( )
		{	
			$ID = $_REQUEST["ID"];
			$AvailabilityStr = $_REQUEST["AvailabilityStr"];
			$MessageBack = 0;
			/*Update Employee on this ID the availability string.*/
			$myUpdateArr = array(											
							'Availability' => $AvailabilityStr
						);
			try {
				$this->db->update('Employees', $myUpdateArr, "employeeID = ".$ID); 
				$MessageBack = 1;
			}catch(Exception $e){ $MessageBack = 0;}
			
			echo $MessageBack;
		}/*End of Function SetAvailability*/
		
	}/*End of EmployeeAvailability*/



?>