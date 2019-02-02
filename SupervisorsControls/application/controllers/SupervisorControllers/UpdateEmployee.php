<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class UpdateEmployee extends CI_Controller {
  var $TPL;
	public function __construct() { parent::__construct(); }
	public function index() {	
	
		$this->TPL['initalList'] = $this->getList();		
			
		$this->template->show('UpdateEmployee_view', $this->TPL);
	}

	public function loadList() {
		
		echo $this->getList();
	}
	
  
	public function getList() {
		$CI =& get_instance(); 
		$query = $CI->db->query("SELECT Firstname, Lastname, employeeID, Username, Password, Instructor, Lifeguard, Headguard, Supervisor  FROM `Employees`   ");
		$list = $query->result_array();	 
		return json_encode($list );		
	}// End of function getList

	
	 
	 
	 
	public function LoadForm( $ID  ) {
		$CI =& get_instance(); 
		$query = $CI->db->query("SELECT Username, Firstname, Lastname, Password, Lifeguard, Instructor, Headguard, Supervisor, employeeID FROM `Employees` WHERE employeeID =".$ID."" );
		$list = $query->result_array();
		if ( $query->num_rows() > 1 )
		{ $this->load->view('ErrorPage'); }
		else {
			$this->TPL['employeeList'] = $list[0];		
			$this->template->show('UpdateEmployee_view', $this->TPL);
		}		
	}//End of function LoadForm









	 
	public function UpdateEmployee() {    
		$InputEmployeeID = ""; 		
		$InputFirstname = ""; 		
		$InputLastname = ""; 		
		$InputPassword = ""; 		
		$InputLifeguard = false; $InputInstructor = false; 	$InputHeadguard = false;$InputSupervisor = false;  	
		$InputEmployeeID = $_REQUEST['ID'];
		$InputLastname = $_REQUEST['lastname']; $InputFirstname = $_REQUEST['firstname'];
		$InputUsername = "".$InputFirstname.".".$InputLastname."";
		$InputPassword = $_REQUEST['password'];
		$InputLifeguard = $_REQUEST['lifeguard'];
		$InputInstructor = $_REQUEST['instructor'];
		$InputHeadguard = $_REQUEST['headguard'];
		$InputSupervisor = $_REQUEST['supervisor'];			
		$MessageBack = [];
		$MessageBack["Instructions"] = "";  
		$NotSafe = false; 
		$NotSafe = $this->Data_Safety_Test($InputEmployeeID, $InputFirstname, $InputLastname, $InputPassword, $InputLifeguard, $InputInstructor, $InputHeadguard, $InputSupervisor);						
		if ( $Notsafe == true ) 
		{ $MessageBack["Instructions"] = 1; }
		else {	 
			$InputArr = [];				
			$InputArr['Firstname'] = $InputFirstname; 
			$InputArr['Lastname']  = $InputLastname;		
			$InputArr['Password']  = $InputPassword;
			$Lifeguard = $InputLifeguard;
			$Instructor = $InputInstructor;
			$Headguard = $InputHeadguard;
			$Supervisor	= $InputSupervisor;				
			$EmployeeID = $InputEmployeeID;																
			if(  $Lifeguard == "true" )
			{ $InputArr['Lifeguard'] = 1;	 $InputLifeguard = 1; }
			else
			{ $InputArr['Lifeguard'] = 0; $InputLifeguard = 0; }
			if($Instructor == "true")
			{ $InputArr['Instructor'] = 1; $InputInstructor = 1; }
			else
			{ $InputArr['Instructor'] = 0; $InputInstructor = 0; }
			if( $Headguard == "true" )
			{ $InputArr['Headguard'] = 1; $InputHeadguard = 1; }
			else
			{ $InputArr['Headguard'] = 0; $InputHeadguard = 0; }
			if($Supervisor == "true" )
			{	$InputArr['Supervisor'] = 1; $InputSupervisor = 1; }
			else
			{	$InputArr['Supervisor'] = 0; $InputSupervisor = 0; }						
			$NotLogicial = false;
			$NotLogicial = $this->Buisness_Logic_Test($InputArr);  
			if ( $NotLogicical == true ) { 	 	
				$MessageBack["Instructions"] = 2; 
				$MessageBack["BuisnessLogic"] = $NotLogicial; 
			} else {	 				
				try {				
					$CI =& get_instance(); 					
					$UPDATESTATEMENT = "UPDATE `Employees` SET"
						."`Firstname`= '".$InputFirstname."',"
						."`Lastname`= '".$InputLastname."',"
						."`Username`= '".$InputUsername."',"
						."`Password`= '".$InputPassword."',"
						."`Instructor`= '".$InputInstructor."',"
						."`Lifeguard`= '".$InputLifeguard."',"
						."`Headguard`= '".$InputHeadguard."',"
						."`Supervisor`= '".$InputSupervisor."',"
						."`Availability`= 'Nothing yet'"
						." WHERE `employeeID` = '".$InputEmployeeID."';";					
					$query = $CI->db->query($UPDATESTATEMENT);										
					$MessageBack["Instructions"] =  4; 				
				} catch(Exception $e)
				{ $MessageBack["Instructions"] = 3; }				
			} 		
		} 					
		echo json_encode( $MessageBack );
	}//End of function UpdateEmployee
	
	
	 
	 
	 
	function Data_Safety_Test( $EmployeeID, $Firstname, $Lastname, $Password, $Lifeguard, $Instructor, $Headguard, $Supervisor ) { 
		$TestEmployeeID = false; $TestFirstname = false; $TestLastname = false; $TestPassword = false; $TestLifeguard = false;
		$TestInstructor = false; $TestHeadguard = false; $TestSupervisor = false; $SafteyPass = true;  
		if(  is_numeric( $EmployeeID ) ){ $TestEmployeeID = true; }else{ $TestEmployeeID = false; }	
		$size = strlen($Firstname);			 
		if ( $size <= 21 && $size > 0) {							
			$pattCapital = '/^[A-Z][a-z]+$/';					 
			$firstletterCapital = false;						 
			$ansFirstCapital = preg_match($pattCapital, $Firstname );	
			if ( $ansFirstCapital == true ) { $TestFirstname = true; } else { $TestFirstname = false; }			
		}
		else
		{	$TestFirstname = false;	}				
		$size = strlen($Lastname);			 
		if ( $size <= 21 && $size > 0) {													
			$pattCapital = '/^[A-Z][a-z]+$/';		 
			$firstletterCapital = false;			 
			$ansFirstCapital = preg_match( $pattCapital, $Lastname );	
			if ( $ansFirstCapital == true ) { $TestLastname = true; } else { $TestLastname = false;	}
		} else
		{	$TestLastname = false;	}
		$Size = strlen($Password);				
		if ( $Size >= 6 && $Size <= 12) {	 
			$pattPassword = '/^[a-zA-Z0-9]+$/';							 
			$passAcceptableCharacters = false;								 
			$passAcceptableCharacters  = preg_match($pattPassword, $Password) ; 	 
			if ( $passAcceptableCharacters == true ) { $TestPassword = true; } else { $TestPassword = false; }								
		}else
		{	$TestPassword = false;	} 
		if ( $TestEmployeeID == true && $TestFirstname == true && $TestLastname == true && $TestPassword  == true)
		{	$SafteyPass = false; }		
		return $SafteyPass;				
	}// End of function Data_Safety_Test()
	
	

	
	
	function Buisness_Logic_Test( $InputArr ) {
		$BuisnessLogicOkay = true;
		$reasons = array();						
		$username = "".$InputArr["Fistname"].".".$InputArr["Lastname"]."";
		$hasSameName = $this->doesHaveSameName( $username );
		if( $hasSameName == true ) {
			$reasons = array_push( $reasons, 1 );		
			$BuisnessLogicOkay = false;
		}		
		if( $BuisnessLogicOkay == true )
		{ $reasons = false;	 }
		return $reasons;
	}//End of Buisness_Logic_Test()
	
	
	
	
	 
	function doesHaveSameName( $Username ) {
		$hasname = false;		
		$CI =& get_instance(); 					
		$query = $CI->db->query("SELECT * FROM `Employees` WHERE `Username` =  '".$Username."'  ;");										
		$list = $query->result_array();
		if ( count($list) > 0 ) {	$hasname = false;	} else {	$hasname = true;	} 
		return $hasname;	
	}// End of DoesHaveSameName
	
	
}//End of UPDATE Employee class.

?>


