<? session_start(); ?>
<!-- AvailableShifts View Css -->	
<link href = "<?= assetUrl(); ?>css/AvailableShifts_Styles.css" rel="stylesheet" type="text/css"/> 
<script type = "text/javascript">	
	var AllShiftsThisUserHas; 
	var ShiftsWithNoEmployeesARR;
	var EmployeeID = <?= $_SESSION['EmployeeID']; ?>;  
	var certs = {Supervisor:	<?= $_SESSION['Supervisor']; ?>,Lifeguard:<?= $_SESSION['Lifeguard'] ; ?>,Instructor: <?= $_SESSION['Instructor']; ?>,Headguard:	<?= $_SESSION['Headguard']; ?>}	
	$(document).ready(function() {										
		LoadShiftsUpForGrabs(  <?= $SubSlipsAvailable;  ?>  );						
		function getAllsubslipsAvialableForEmployee() {			
			var inputarr = {};
			inputarr['ID'] = EmployeeID;	
			inputarr['Supervisor'] = certs['Supervisor'];
			inputarr['Lifeguard'] = certs['Lifeguard'];
			inputarr['Instructor'] = certs['Instructor'];
			inputarr['Headguard'] = certs['Headguard'];
			$.post('<?= base_Url(); ?>index.php/AvailableShifts/GetAvailableSubSlips', inputarr, function(data) {				
				AllSubSlipsThatHaventBeenTaken	= JSON.parse(data);
				LoadShiftsUpForGrabs(AllSubSlipsThatHaventBeenTaken);
			});
		}/* End of function getAllsubslipsAvialableForEmployee */
		function LoadShiftsUpForGrabs(AllSubSlipsThatHaventBeenTaken) {
			var MSG = "";			
			var count = 0;							
			for( var subslip in AllSubSlipsThatHaventBeenTaken) {
				var TypeofShift = "";
				var ShiftClass = "";
				var Position = AllSubSlipsThatHaventBeenTaken[subslip]["Position"];							
				var displayShift = false; 
				if( Position == 1) {
					if ( certs[ "Lifeguard" ] == 1 ) {  displayShift = true;    TypeofShift = "Lifeguard";    ShiftClass = "Lifeguard";  }
				}
				else if( Position == 2) {
					if ( certs[ "Instructor" ] == 1 ) {  displayShift = true;    TypeofShift = "Instructor";    ShiftClass = "Instructor";  }
				}
				else if( Position == 3) {
					if ( certs[ "Headguard" ] == 1 ) {  displayShift = true;    TypeofShift = "Headguard";    ShiftClass = "Headguard";  }
				}
				else if( Position == 4) {
					if ( certs[ "Supervisor" ] == 1 ) {  displayShift = true;    TypeofShift = "Supervisor";    ShiftClass = "Supervisor";  }
				}							
				var NotUsersShift = true;
				var CreatorID = AllSubSlipsThatHaventBeenTaken[subslip]["CreatorID"];				
				if(	CreatorID == EmployeeID	)
				{ NotUsersShift = false; }	
				if ( displayShift == true && NotUsersShift == true && AllSubSlipsThatHaventBeenTaken[subslip]["Conflict"] == false) {																					
					var ShiftDate = AllSubSlipsThatHaventBeenTaken[subslip]["ShiftDate"];
					var displayDate = AllSubSlipsThatHaventBeenTaken[subslip]["DisplayDate"];
					var name = AllSubSlipsThatHaventBeenTaken[subslip]["Firstname"]+" "+AllSubSlipsThatHaventBeenTaken[subslip]["Lastname"];
					var Time = AllSubSlipsThatHaventBeenTaken[subslip]["ShiftTime"];
					var subslipID = AllSubSlipsThatHaventBeenTaken[subslip]["subslipID"];
					var ownerID = AllSubSlipsThatHaventBeenTaken[subslip]["CreatorID"];
					var ShiftID = AllSubSlipsThatHaventBeenTaken[subslip]["ShiftID"];
					var shReason = AllSubSlipsThatHaventBeenTaken[subslip]["Reason"];											
					count++;
					/* Make a Subslip */
					MSG += "<div id = \"SubSlipCell\" class = \""+ShiftClass+"\"><div id = \"InnerCellText\"class = \"InnerCellText\"><p>";		
					MSG += "<div id = \"ApproveSubslipID\"  >"+subslipID+"</div>";
					MSG += "<div id = \"ApproveOwnerID\"  >"+ownerID+"</div>";	
					MSG += "<div id = \"SshiftID\">"+ShiftID+"</div> ";
					MSG += "<span id = \"SubSlipTitle\" class = \"bold\">SubSlip</span><br>";
					MSG += "Position: <span id = \"SshiftPosition\" class = \"bold\">"+TypeofShift+"</span><br>";
					MSG += "Date: <span id = \"SshiftDate\" class = \"bold\">"+displayDate+"</span><br>";
					MSG += "Time: <span id = \"SshiftTime\" class = \"bold\" >"+Time+"</span><br>";						
					MSG += "Name: <span id = \"SshiftName\" class = \"bold\" >"+name+"</span><br>";
					MSG += "Reason: \"<span class=\"italics\">"+shReason+"</span>\"<br>";
					MSG += " <input type = \"button\" value = \"Sign SubSlip\" id = \"TakeShiftButton\">";	
					MSG += "</p></div></div>";
				}			
			}				
			if (count == 0) {
				MSG += "<div id = \"SubSlipCell\" class = \"Headguard\"><h2>No Subslips Available!</h2><p></p></div>"; 
			}												
			$('#InnerShiftsUpForGrabs').empty().append(MSG);
		}/* End of Function LoadShiftsUpForGrabs */		
		$('#InnerShiftsUpForGrabs').on('click', '#TakeShiftButton', function() {			
			var Position = $(this).siblings('#SshiftPosition').text();
			var theDate = $(this).siblings('#SshiftDate').text();
			var Time = $(this).siblings('#SshiftTime').text();
			var Name = $(this).siblings('#SshiftName').text();			
			var ShiftID = $(this).siblings('#SshiftID').text();
			var subslipID = $(this).siblings('#ApproveSubslipID').text();
			var ownerID = $(this).siblings('#ApproveOwnerID').text();
			var TakerID = EmployeeID;			
			if (confirm(" Are you sure you wish to take this shift?  \n Position: "+Position+"\n Date: "+theDate+"\n Time: "+Time+" \n  Current owner: "+Name+"") == true) {
				var inputarr = {};
				inputarr['takerID'] = TakerID;		
				inputarr['ownerID'] = ownerID;
				inputarr['subslipID'] = subslipID; 
				inputarr['ShiftID'] = ShiftID;
				$.post('<?= base_Url(); ?>index.php/AvailableShifts/TakeSubSlip', inputarr, function(data) {				
					if( data == 1) {
						getAllsubslipsAvialableForEmployee();						
						alert( "You have signed that subslip. Subslip now needs to be approved by the supervisor. You will be notified when it gets approved." );					
					}					
				});
			}
		}); /* End of when InnerShiftsUpForGrabs gets clicked.*/		
		
		
		
	}); /* End of jQuery's Document dot on load. */
</script>
<div id = "AvailableShifts">
	<h1>Available Shifts</h1>	
	<div id = "ShiftsUpForGrabs"> 
		<h3>Subslips that you can take, if you have the certification and don't already have a shift at that time.</h3>
		<div id = "InnerShiftsUpForGrabs"></div>
	</div>		
		
</div>