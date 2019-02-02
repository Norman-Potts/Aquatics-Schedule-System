<? session_start(); ?>
<script type = "text/javascript">
	/* JS Global variables */
	
	var EmployeeID = <?= $_SESSION['EmployeeID']; ?>; 
	var certs = { Supervisor:	<?= $_SESSION['Supervisor']; ?>, Lifeguard:<?= $_SESSION['Lifeguard'] ; ?>, Instructor: <?= $_SESSION['Instructor']; ?>, Headguard:	<?= $_SESSION['Headguard']; ?> }				
	$(document).ready(function() {			
		
		
		LoadStepOne(  <?= $Shifts; ?> );		
		$('#reason').prop('disabled', true);	
		function SetNavNotificationCount() {						
			$.post('<?= base_Url(); ?>index.php/Home/GetNewNotifications', 0, function(data) {	
				var NotificationCount = data;
				$('#NavNotificationCount').empty().append(NotificationCount );			
			});
		}
		function Load_AllShiftsThisUserHas() {		
			$.post('<?= base_Url(); ?>index.php/MakeASubSlip/GetThisEmployeesShifts', 0, function(data) {	
				var shifts = JSON.parse(data) ; 						
				LoadStepOne( shifts );			
			}); 			
		}
		function LoadStepOne( AllShiftsThisUserHas ) {
			var MSG = "";
			var count =0;			
			for( var Shift in AllShiftsThisUserHas ) {									
				var Position = AllShiftsThisUserHas[Shift]["Position"];
				var ShiftID;
				var displayDate = AllShiftsThisUserHas[Shift]["DisplayDate"];
				var Time = AllShiftsThisUserHas[Shift]["Time"];
				var TypeofShift = AllShiftsThisUserHas[Shift]["TypeofShift"]; // Will hold the word name of Position																	
				var Position = AllShiftsThisUserHas[Shift]["Position"];
				var ShiftID = AllShiftsThisUserHas[Shift]["ShiftID"];
				count++;
				/*Making a shift on the subslipform !*/
				MSG += "<div id = \"ShiftCell\" class = \""+TypeofShift+"\"  >";
				MSG += "<div id = \"InnerCellText\" class = \"InnerCellText\"><p>";																			
				MSG += "<div id = \"SshiftID\">"+ShiftID+"</div> ";					
				MSG += "<span id = \"ShiftTitle\" class=\"bold\">Shift</span><br>";
				MSG += "Position: <span id = \"SshiftPosition\" class = \"bold\">"+TypeofShift+"</span><br>";
				MSG += "Date: <span id = \"SshiftDate\" class = \"bold\">"+displayDate+"</span><br>";
				MSG += "Time: <span id = \"SshiftTime\" class = \"bold\" >"+Time+"</span><br>";						
				MSG += "</p>";
				MSG += "<button id = \"SelectShift\">Select Shift</button>";
				MSG += "</div></div>";
			}									
			//If no shifts to display...
			if (count == 0) {
				MSG += "<div id = \"ShiftCell\" class = \"Headguard\"  >";
				MSG += "<div id = \"InnerCellText\" class = \"InnerCellText \"><p>";
				MSG += "<h3>No shifts to submit subslips for</h3></p>"
				MSG += "</div></div>";
			}			
			$('#ShiftsOnDateSelect').empty().append( MSG);
		}/* End of Function LoadStepOne */				
		var SelectedShiftID = 0; 
		$('#ShiftsOnDateSelect').on('click', '#SelectShift', function() {
			var ShiftID;
			var employeeID = EmployeeID;
			var ShiftID = $(this).siblings('#SshiftID').text();
			$('.SELECTEDInnerCellText #SelectShift').text('Select Shift');
			$('.SELECTEDInnerCellText #ShiftTitle').text('Shift');						
			$('#reason').val('');
			$('#reason').prop('disabled', true);
			$('.SELECTEDInnerCellText').removeClass('SELECTEDInnerCellText').addClass('InnerCellText');
			if( ShiftID == SelectedShiftID ) {			
				SelectedShiftID = 0;											
			} else {	
				$(this).parent('#InnerCellText').removeClass('InnerCellText').addClass('SELECTEDInnerCellText');
				SelectedShiftID = ShiftID;
				$(this).text('Selected Shift');
				$(this).siblings('#ShiftTitle').text('Selected Shift');
				$('#reason').prop('disabled', false);
			}								
		});
		$('#HandInSubslipSubmit').click(function() {
			var Reason = $('#reason').val();			
			if ( 	SelectedShiftID  == 0	) {	
				alert("Please select a shift.");
			} else if ( Reason == '' || Reason == '...' ) {
				alert("You have to write a reason.");
			} else {											
				var inputArr = {};	
				inputArr['EmployeeID'] = "";  inputArr['Reason'] = "";  inputArr['ShiftID'] = "";																
				var ShiftID = SelectedShiftID;				 				
				inputArr['EmployeeID'] = EmployeeID;  inputArr['ShiftID'] = ShiftID; inputArr['Reason'] = Reason;											
				$.post('<?= base_Url(); ?>index.php/MakeASubSlip/MakeThisSubSlip', inputArr, function(data) {						 				
					var MSG = JSON.parse(data);														
					var Instructions = MSG["Instructions"];	
					switch (Instructions){
						case 0:
							alert("Hand in SubSlip failed because of an error."); 
							break;
						case 1:
							Load_AllShiftsThisUserHas()							
							alert( "SubSlip was submitted. You will be notified if your shift gets taken.");							
							break;
						case 2:
							alert("You have already submitted a SubSlip for this shift.");
							break;
						case 5:
							alert("Notification updated failed.");
							break;
						default:
							alert("Hand in SubSlip failed because of an error.");
					}/* End of switch */
					SetNavNotificationCount();					
					$('#reason').val('');
					$('#reason').prop('disabled', true);
				});/*End of Ajax post*/														
			}	
		});
	}); //End of Jquery's Document dot on load.
</script>
<div id ="MakeASubSlipPage">
	<form id="SubSlipForm" name = "SubSlipForm"  accept-charset= "utf-8"  onSubmit ="return false;"  >		
		<h2>SubSlip Form</h2>		
		<div id = "innerForm">												
			<div id = "SelectShiftsDiv" >
				<p>	<strong>First</strong>, choose one of your shift. </p>
				<div id = "ShiftsOnDateSelect" name = "ShiftsOnDateSelect" >
				</div>
			</div> 					
			<div id = "SubSlipFormSecondStep">
				<p><strong>Second</strong>, write the reason you cannot work this shift. </p>				
				<textarea required id = "reason" form_id = "" name="reason" placeholder = "..." maxlength = "200"   form="SubSlipForm" > </textarea>
			</div>			
			<div id = "HandInSubslip">
				<h3>Finally,</h3>
				<input type = "button" value = "Hand in Subslip" name = "HandInSubslipSubmit" id = "HandInSubslipSubmit" size = "200" >		
			</div>			
		</div>		
	</form>
</div>