<?php

	defined('BASEPATH') OR exit('No direct script access allowed');

	class MyProfile extends CI_Controller {

	var $TPL;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form');

	}

	public function index()
	{			
		$this->TPL['Notifications'] = $this->notifications->GetNotifications();
		$this->TPL['Availability'] = $this->GetAvailability();
		$this->TPL['Shifts'] = $this->GetShifts();
		$this->TPL['SubSlips'] = $this->GetSubslips();
		$this->TPL['YourTakenSubSlips'] = $this->GetYourTakenSubSlips();
		$this->TPL['YourApprovedSubSlips'] = $this->GetYourApprovedSubSlips();
		$this->TPL['SlipsYouHaveSigned'] = $this->GetSlipsYouHaveSigned();
		$this->TPL['ApprovedSlipsYouHaveSigned'] = $this->GetApprovedSlipsYouHaveSigned();
		$this->template->show('myprofile_view', $this->TPL);
	}
	
	/** function redirect()
		purpose: allows page to be re directed to myprofile page, with andy variables.
	*/
    public function redirect($page) 
    {
        header("Location: ".$page);
        exit();
    }
    
	
	
	/** Function GetAvailability
	*/
	public function GetAvailability( )
	{
		session_start();		
		$ID = $_SESSION['EmployeeID'];

		$CI =& get_instance(); //TODO: FIX security.
		$query = $CI->db->query("SELECT Availability FROM `Employees` WHERE  employeeID =".$ID.";");
		$list = $query->result_array();			
		$Availability = $list[0]['Availability'];
		/*if(json_decode($Availability) == null )
		{
			$Availability = json_encode($Availability);
		}*/
		$Availability = json_decode($Availability) ;
		return $Availability;
	}/*End of Function GetAvailability*/
	
	
			
	/** Function RemoveSubSlip
	*/
	public function RemoveSubSlip( )
	{
		$response = 3;
		session_start();		
		$subslipID = $_REQUEST['subslipID'];				
		try {		
			$CI =& get_instance(); //TODO: FIX security.	SELECT * FROM `SubSlips` WHERE `subslipID` = '157' AND `TakenTrueorFalse` = 0				
			$SELECTSTATEMENT = "SELECT * FROM `SubSlips` WHERE  `subslipID` = '".$subslipID."' AND `TakenTrueorFalse` = '1';";		
			$query = $CI->db->query($SELECTSTATEMENT);			
			$list = $query->result_array();		
			if( count($list ) > 0 )
			{
				$response = 2;
			}
			else{			
				$CI =& get_instance(); 			
				$deleteStatement = "DELETE FROM `SubSlips` WHERE `subslipID` = '".$subslipID ."'; ";			
				$obj =	$CI->db->query($deleteStatement );					
				if( $obj == true ) {
					$response = 1;
				}						
			}																			
		}catch( Exception $e ) {			
			$response = 3;
		}	
		echo json_encode($response);
	}/*End of Function RemoveSubSlip*/
	
	
		
		
	/** Function GetShifts
		
	*/
	public function GetShifts( )
	{
		session_start();		
		$ID = $_SESSION['EmployeeID'];
		$Today = $this->theschedule->YYYMMDDTodayplz();
		$CI =& get_instance(); //TODO: FIX security.
		$query = $CI->db->query("SELECT * FROM `Shifts` WHERE  CurrentOwnerEmployeeID =".$ID." AND `date` >= '".$Today."'  ORDER BY `date` ASC;");
		$list = $query->result_array();							
		foreach($list as $i => $shift)
		{
			$p = convertnumbtoposition($shift['Position']);
			$list[$i]['Position'] = $p;
			$st = convertTimeToDisplayTime($shift['startTime']);
			$list[$i]['startTime'] = $st;
			$et = convertTimeToDisplayTime($shift['endTime']);
			$list[$i]['endTime'] = $et;
			$d = makeDisplayDate($shift['date']);
			$list[$i]['date'] = $d;
		}
		$Shifts = json_encode($list);		
		return $Shifts;
	}/*End of Function GetShifts*/
		

		
	public function GetSlipsYouHaveSigned() 
	{
		session_start();		
		$ID = $_SESSION['EmployeeID'];
		$Today = $this->theschedule->YYYMMDDTodayplz();
		$CI =& get_instance(); //TODO: FIX security.
		$query = $CI->db->query("SELECT * FROM `SubSlips` WHERE  `TakerID`  =".$ID." AND `ShiftDate` >= '".$Today."'  AND `completed` =  '0'  ORDER BY `ShiftDate` ASC;");
		$list = $query->result_array();							
		foreach($list as $i => $slip) {
			$p = convertnumbtoposition($slip['Position']);
			$list[$i]['Position'] = $p;
			$st = convertTimeToDisplayTime($slip['startTime']);
			$list[$i]['startTime'] = $st;
			$et = convertTimeToDisplayTime($slip['endTime']);
			$list[$i]['endTime'] = $et;
			$d = makeDisplayDate($slip['ShiftDate']);
			$list[$i]['ShiftDate'] = $d;					
			$ID = $list[$i]['CreatorID'];
			$CI =& get_instance(); //TODO: FIX security.
			$query = $CI->db->query("SELECT Firstname, Lastname FROM `Employees` WHERE `employeeID`  =".$ID.";");
			$FL = $query->result_array();	
			$fname = $FL[0]['Firstname'];
			$lname = $FL[0]['Lastname'];
			$NAME = "".$fname." ".$lname."";
			$list[$i]['Name'] = $NAME ;				
			
		}
		$Slips = json_encode($list);		
		return $Slips;
	}
		
		
		
	public function GetApprovedSlipsYouHaveSigned() 
	{
		session_start();		
		$ID = $_SESSION['EmployeeID'];
		$Today = $this->theschedule->YYYMMDDTodayplz();
		$CI =& get_instance(); //TODO: FIX security.
		$query = $CI->db->query("SELECT * FROM `SubSlips` WHERE  `TakerID`  =".$ID." AND `ShiftDate` >= '".$Today."'  AND `completed` =  '1'  ORDER BY `ShiftDate` ASC;");
		$list = $query->result_array();							
		foreach($list as $i => $slip) {
			$p = convertnumbtoposition($slip['Position']);
			$list[$i]['Position'] = $p;
			$st = convertTimeToDisplayTime($slip['startTime']);
			$list[$i]['startTime'] = $st;
			$et = convertTimeToDisplayTime($slip['endTime']);
			$list[$i]['endTime'] = $et;
			$d = makeDisplayDate($slip['ShiftDate']);
			$list[$i]['ShiftDate'] = $d;				
			$ID = $list[$i]['CreatorID'];
			$CI =& get_instance(); //TODO: FIX security.
			$query = $CI->db->query("SELECT Firstname, Lastname FROM `Employees` WHERE `employeeID`  =".$ID.";");
			$FL = $query->result_array();	
			$fname = $FL[0]['Firstname'];
			$lname = $FL[0]['Lastname'];
			$NAME = "".$fname." ".$lname."";
			$list[$i]['Name'] = $NAME ;				
	
		}
		$Slips = json_encode($list);		
		return $Slips;
	}
		
		
		
	public function GetYourApprovedSubSlips()
	{		
		session_start();		
		$ID = $_SESSION['EmployeeID'];
		$Today = $this->theschedule->YYYMMDDTodayplz();
		$CI =& get_instance(); //TODO: FIX security.
		$query = $CI->db->query("SELECT * FROM `SubSlips` WHERE  `CreatorID`  =".$ID." AND `ShiftDate` >= '".$Today."' AND `TakenTrueorFalse` = '1' AND `completed` =  '1'  ORDER BY `ShiftDate` ASC;");
		$list = $query->result_array();							
		foreach($list as $i => $slip)
		{
			$p = convertnumbtoposition($slip['Position']);
			$list[$i]['Position'] = $p;
			$st = convertTimeToDisplayTime($slip['startTime']);
			$list[$i]['startTime'] = $st;
			$et = convertTimeToDisplayTime($slip['endTime']);
			$list[$i]['endTime'] = $et;
			$d = makeDisplayDate($slip['ShiftDate']);
			$list[$i]['ShiftDate'] = $d;
			
			if($list[$i]['TakerID'] != null)
			{
				$ID = $list[$i]['TakerID'];
				$CI =& get_instance(); //TODO: FIX security.
				$query = $CI->db->query("SELECT Firstname, Lastname FROM `Employees` WHERE `employeeID`  =".$ID.";");
				$FL = $query->result_array();	
				$fname = $FL[0]['Firstname'];
				$lname = $FL[0]['Lastname'];
				$NAME = "".$fname." ".$lname."";
				$list[$i]['Name'] = $NAME ;
				
			}
			
		}
		$Slips = json_encode($list);		
		return $Slips;
	}
				
	public function GetYourTakenSubSlips( )
	{
		session_start();		
		$ID = $_SESSION['EmployeeID'];
		$Today = $this->theschedule->YYYMMDDTodayplz();
		$CI =& get_instance(); //TODO: FIX security.
		$query = $CI->db->query("SELECT * FROM `SubSlips` WHERE  `CreatorID`  =".$ID." AND `ShiftDate` >= '".$Today."' AND `TakenTrueorFalse` = '1' AND `completed` =  '0'  ORDER BY `ShiftDate` ASC;");
		$list = $query->result_array();							
		foreach($list as $i => $slip)
		{
			$p = convertnumbtoposition($slip['Position']);
			$list[$i]['Position'] = $p;
			$st = convertTimeToDisplayTime($slip['startTime']);
			$list[$i]['startTime'] = $st;
			$et = convertTimeToDisplayTime($slip['endTime']);
			$list[$i]['endTime'] = $et;
			$d = makeDisplayDate($slip['ShiftDate']);
			$list[$i]['ShiftDate'] = $d;
			
			if($list[$i]['TakerID'] != null)
			{
				$ID = $list[$i]['TakerID'];
				$CI =& get_instance(); //TODO: FIX security.
				$query = $CI->db->query("SELECT Firstname, Lastname FROM `Employees` WHERE `employeeID`  =".$ID.";");
				$FL = $query->result_array();	
				$fname = $FL[0]['Firstname'];
				$lname = $FL[0]['Lastname'];
				$NAME = "".$fname." ".$lname."";
				$list[$i]['Name'] = $NAME ;
				
			}
			
		}
		$Slips = json_encode($list);		
		return $Slips;
	}/// End of Function GetSubslips
		
		
			
	public function GetSubslips( )
	{
		session_start();		
		$ID = $_SESSION['EmployeeID'];
		$Today = $this->theschedule->YYYMMDDTodayplz();
		$CI =& get_instance(); //TODO: FIX security.
		$query = $CI->db->query("SELECT * FROM `SubSlips` WHERE  `CreatorID`  =".$ID." AND `ShiftDate` >= '".$Today."'  AND `TakenTrueorFalse` = '0'  ORDER BY `ShiftDate` ASC;");
		$list = $query->result_array();							
		foreach($list as $i => $slip)
		{
			$p = convertnumbtoposition($slip['Position']);
			$list[$i]['Position'] = $p;
			$st = convertTimeToDisplayTime($slip['startTime']);
			$list[$i]['startTime'] = $st;
			$et = convertTimeToDisplayTime($slip['endTime']);
			$list[$i]['endTime'] = $et;
			$d = makeDisplayDate($slip['ShiftDate']);
			$list[$i]['ShiftDate'] = $d;
			
			if($list[$i]['TakerID'] != null)
			{
				$ID = $list[$i]['TakerID'];
				$CI =& get_instance(); //TODO: FIX security.
				$query = $CI->db->query("SELECT Firstname, Lastname FROM `Employees` WHERE `employeeID`  =".$ID.";");
				$FL = $query->result_array();	
				$fname = $FL[0]['Firstname'];
				$lname = $FL[0]['Lastname'];
				$NAME = "".$fname." ".$lname."";
				$list[$i]['Name'] = $NAME ;
				
			}
			
		}
		$Slips = json_encode($list);		
		return $Slips;
	}/// End of Function GetSubslips
		
  
}

?>

