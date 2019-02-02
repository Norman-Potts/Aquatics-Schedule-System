<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class DeleteEmployee extends CI_Controller {

  var $TPL;

	public function __construct()
	{
		parent::__construct();		
	    $this->TPL['EmList']= $this->loadList();
	}

	public function index()
	{
		$this->TPL['EmList'] = $this->loadList();
		$this->template->show('DeleteEmployee_view', $this->TPL);
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
		$query = $CI->db->query("SELECT Firstname, Lastname, employeeID FROM `Employees`   ");
		$list = $query->result_array();
		session_start();		
		
		$id = $_SESSION['EmployeeID'];
		
		//Remove the current session user from the list.
		$key = array_search($id, array_column($list, 'employeeID'));
		unset($list[$key]);
		
		sort($list);
		return $list;		
	}/* End of Function loadList */
	
 
	/** Function LoadForm
		Purpose: Loads the delete form after an employee gets selected.
	*/
	public function LoadForm( $ID )			
	{						
		//Query db for ID
		$CI =& get_instance(); 
		$query = $CI->db->query("SELECT Username, Firstname, Lastname, Lifeguard, Instructor, Headguard, Supervisor, employeeID FROM `Employees` WHERE employeeID =".$ID."" );
		$list = $query->result_array();
		
		//Get shifts of employee
		$ShiftsQuery = $CI->db->query("SELECT DATE, startTime, endTime, Position  FROM `Shifts` s JOIN  `Employees` e ON e.employeeID = s.CurrentOwnerEmployeeID WHERE s.CurrentOwnerEmployeeID = '".$ID."';" );
		$ShiftsList = $ShiftsQuery->result_array();
				
		if ( $query->num_rows() > 1 )
		{
			//throw error there is two accounts with the same employeeID
			$this->load->view('ErrorPage');
			//TODO: Write to error log.		
		}else{
			$this->TPL['ShiftsThisEmployeeHas'] = $ShiftsList;
			$this->TPL['EmForm'] = $list[0];		
			$this->template->show('DeleteEmployee_view', $this->TPL);					
		}				
	}/* End of Function LoadForm */
	
	
	
	
	public function loadShifts()
	{
		$ID = $_REQUEST['eID'];
		if( is_numeric($ID) )
		{
			//Get shifts of employee
			$SELECTSTATEMENT = "SELECT `date`, `startTime`, `endTime`, `Position` FROM `Shifts` WHERE `date` >= CURDATE() AND `CurrentOwnerEmployeeID` = '".$ID."';" ;			
			
			
			$CI =& get_instance(); 
			$ShiftsQuery = $CI->db->query($SELECTSTATEMENT);
			$ShiftsList = $ShiftsQuery->result_array();
			echo json_encode( $ShiftsList );
		}
		else{
			var_dump($_REQUEST['eID']);
		}
	}
	
	
	
	
	
	
	/* DeleteThisEmployee
		
		This function deletes the employee given a employeeID.
		
	*/
	public function DeleteThisEmployee( )
	{
		$Request = $_REQUEST['eID'];
		$CI =& get_instance();
		//$query = $CI->db->query("SELECT `Firstname`, `Lastname` FROM `Employees` WHERE `employeeID` = '".$ID."';" );
		//$list = $query->result_array();
		
		$feedback = 1;		
		
		
		try {  			
		
			$ID = (int) $Request; 
		
			$DELETESTATEMENT = "DELETE FROM `SubSlips` WHERE `CreatorID` = '".$ID."' or `TakerID` = '".$ID."';";
			
			$query = $CI->db->query($DELETESTATEMENT );			
			
			$DELETESTATEMENT = "DELETE FROM `Shifts` WHERE `CurrentOwnerEmployeeID` = '".$ID."';";
		    
			$query = $CI->db->query($DELETESTATEMENT);
			
			$DELETESTATEMENT = "DELETE FROM `Notifications` WHERE `employeeID` = '".$ID."';";
			
			$query = $CI->db->query($DELETESTATEMENT); //Delete all notifications for this id. 		
			
			$DELETESTATEMENT = "DELETE FROM `Employees` WHERE `employeeID` = '".$ID."';";
			
			$query = $CI->db->query($DELETESTATEMENT); //Delete them from the database.
				
		
		} catch(Exception $e) {
			$feedback = 0;
		}
		
		echo 	$feedback;
						
	}/*End of Function DeleteThisEmployee */
	
	
	
	
}


?>