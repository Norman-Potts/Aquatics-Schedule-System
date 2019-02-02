<link href = "<?= assetUrl(); ?>css/ApproveSubSlips_Styles.css" rel="stylesheet" type="text/css"/> 
<script type = "text/babel">		
	$(document).ready(function() {				
		getAllFutureSubSlips();							
		function getAllFutureSubSlips() {
			var inputarr = {};
			$.post('<?= base_Url(); ?>index.php/SupervisorControllers/ApproveSubSlips/GetALLSubSlips', inputarr, function(data) {	
				var AllSubSlips = JSON.parse( data );			
				loadSubmittedSubSlips(AllSubSlips);//Load them into the container...
			});					
		}//End of Function getAllFuturesSubSlips					
		function convert24HourTo12hr(time) {
			var twentyfourhr;			
			const AMPM12HourArray = [ "12:00am", "12:15am", "12:30am", "12:45am", "1:00am", "1:15am", "1:30am", "1:45am", "2:00am", "2:15am", "2:30am", "2:45am", "3:00am", "3:15am", "3:30am", "3:45am", "4:00am", "4:15am", "4:30am", "4:45am", "5:00am", "5:15am", "5:30am", "5:45am", "6:00am", "6:15am", "6:30am", "6:45am", "7:00am", "7:15am", "7:30am", "7:45am", "8:00am", "8:15am", "8:30am", "8:45am", "9:00am", "9:15am", "9:30am", "9:45am", "10:00am", "10:15am", "10:30am", "10:45am", "11:00am", "11:15am", "11:30am", "11:45am", "12:00pm", "12:15pm", "12:30pm", "12:45pm", "1:00pm", "1:15pm", "1:30pm", "1:45pm", "2:00pm", "2:15pm", "2:30pm", "2:45pm", "3:00pm", "3:15pm", "3:30pm", "3:45pm", "4:00pm", "4:15pm", "4:30pm", "4:45pm", "5:00pm", "5:15pm", "5:30pm", "5:45pm", "6:00pm", "6:15pm", "6:30pm", "6:45pm", "7:00pm", "7:15pm", "7:30pm", "7:45pm", "8:00pm", "8:15pm", "8:30pm", "8:45pm", "9:00pm", "9:30pm", "9:45pm", "10:00pm", "10:15pm", "10:30pm", "10:45pm", "11:00pm", "11:15pm", "11:30pm", "11:45pm" ];									
			const mysqlTimeArray =  [ "00:00:00", "00:15:00", "00:30:00", "00:45:00", "01:00:00", "01:15:00","01:30:00", "01:45:00", "02:00:00", "02:15:00", "02:30:00", "02:45:00", "03:00:00", "03:15:00","03:30:00", "03:45:00", "04:00:00", "04:15:00", "04:30:00", "04:45:00","05:00:00", "05:15:00", "05:30:00", "05:45:00", "06:00:00", "06:15:00", "06:30:00", "06:45:00", "07:00:00", "07:15:00", "07:30:00", "07:45:00", "08:00:00", "08:15:00", "08:30:00", "08:45:00","09:00:00", "09:15:00", "09:30:00", "09:45:00", "10:00:00", "10:15:00", "10:30:00", "10:45:00", "11:00:00", "11:15:00", "11:30:00", "11:45:00", "12:00:00", "12:15:00", "12:30:00", "12:45:00","13:00:00", "13:15:00", "13:30:00", "13:45:00", "14:00:00", "14:15:00", "14:30:00", "14:45:00", "15:00:00", "15:15:00", "15:30:00", "15:45:00", "16:00:00", "16:15:00", "16:30:00", "16:45:00", "17:00:00", "17:15:00", "17:30:00", "17:45:00", "18:00:00", "18:15:00", "18:30:00", "18:45:00", "19:00:00", "19:15:00", "19:30:00", "19:45:00", "20:00:00", "20:15:00", "20:30:00", "20:45:00","21:00:00", "21:15:00", "21:30:00", "21:45:00", "22:00:00", "22:15:00", "22:30:00", "22:45:00","23:00:00", "23:15:00", "23:30:00", "23:45:00" ];						
			var index = mysqlTimeArray.indexOf(time);
			twentyfourhr = AMPM12HourArray[index];			
			return twentyfourhr;
		}		
		function loadSubmittedSubSlips( AllSubSlips ) {
			var MSG = "";		
			var lengthi = 0;
			for(var Slip in AllSubSlips ) {		
				lengthi++;				
				var startTime = AllSubSlips[Slip]["startTime"];
				startTime = convert24HourTo12hr(startTime);                               				
				var endTime =  AllSubSlips[Slip]["endTime"];				
				endTime = convert24HourTo12hr(endTime);                                   
				var Firstname = AllSubSlips[Slip]["Firstname"];
				var Lastname = AllSubSlips[Slip]["Lastname"];
				var ownerID =  AllSubSlips[Slip]["CreatorID"];
				var ShiftID =  AllSubSlips[Slip]["ShiftID"];
				var TakerID = AllSubSlips[Slip]["TakerID"];
				var personTakingShift = AllSubSlips[Slip]["personTakingShift"];
				var OwnerOfShift = Firstname+" "+Lastname ;
				var ShiftDate = AllSubSlips[Slip]["ShiftDate"];																							
				var pp = AllSubSlips[Slip]["Position"];				
				var Position = "";
				console.log(pp);
				switch(pp){					
					case "1": 
						Position = "Lifeguard";
						break;
					case "2":
						Position = "Instructor";
						break;
					case "3":
						Position = "Headguard";
						break;
					case "4": 
						Position = "Supervisor";
						break;
				}												
				var cdt = AllSubSlips[Slip]["CreatedDateAndTime"];				
				var subslipID =  AllSubSlips[Slip]["subslipID"];
				var signedDate = AllSubSlips[Slip]["TakenDateAndTime"];				
				var Reason = AllSubSlips[Slip]["Reason"];				
				/// Build the div.
				MSG += "<div id = \"SubmittedSubSlip\" class = \""+Position+"\">";			
					MSG += "<div class = \"InnerCellText\">";
						MSG += "<div id = \"ApproveSubslipID\" >"+subslipID+"</div>";
						MSG += "<div id = \"ApproveOwnerID\"  >"+ownerID+"</div>";
						MSG += "<div id = \"ApproveShiftID\"  >"+ShiftID+"</div>";
						MSG += "<div id = \"ApproveTakerID\"  >"+TakerID+"</div>";						
						MSG += "<div class = \"ownerNameShift\"> <span class = \"Underline Bold\"> Owner of Shift:</span> "+OwnerOfShift+" </div>";						
						MSG += "<div id = \"UpperCell\">";							
											
							MSG += "<div class = \"ReasonView\"> <span class = \"Underline Bold\">Reason</span> <br>  "+Reason+"</div>";
													
							MSG += "<div class = \"CellTopRight\">";								
								MSG += "<span class = \"Underline Bold\">Person taking the shift:</span> "+personTakingShift+" <br>";
								MSG += "Created Date: "+cdt+"<br>";
								MSG += "Signed Date: "+signedDate+" <br>"
								MSG += "<span class = \"Underline Bold\">Shift Info</span><br>";
								MSG += "Shift Date: "+ShiftDate+"<br>";
								MSG += "Position: "+Position+"<br>";
								MSG += "StartTime: "+startTime+" <br>";
								MSG += "EndTime: "+endTime+"<br>";								
							MSG += "</div>";														
						MSG += "</div>";												
						
						MSG += "<div id = \"LowerCell\">";														
							MSG += "<input type = \"button\"  value = \"Accept Sub Slip\" class=\"SSacceptButton\" >"							
							MSG += "<input type = \"button\"  value = \"Reject Sub Slip\" class=\"SSrejectButton\" >"															
						MSG += "</div>";
					
					MSG += "</div>";									
				MSG += "</div>";		
			}
			if (lengthi == 0)
			{
				MSG += "<div id = \"SubmittedSubSlip\" class = \"Headguard\">";			
				MSG += "<h1>No subslips waiting for approval at the moment... </h1>";
				MSG += "</div>";		
			}
				
			$('#BoxOfAvialiableSubslips h3 #Count').empty().append(AllSubSlips.length);			
			$('#InnerBoxOfAvialiableSubslips').empty();
			$('#InnerBoxOfAvialiableSubslips').append(MSG);
		}//End of function loadSubmittedSubSlips													
		$('#InnerBoxOfAvialiableSubslips').on('click','.SSrejectButton', function() {			
			var subslipID = $(this).parents("#LowerCell").siblings('#ApproveSubslipID').text();
			var ownerID = $(this).parents("#LowerCell").siblings('#ApproveOwnerID').text();
			OpenRejectModal(subslipID,ownerID);
		});	
		$('#InnerBoxOfAvialiableSubslips').on('click','.SSacceptButton', function() {			
			var subslipID = $(this).parents("#LowerCell").siblings('#ApproveSubslipID').text();
			var TakerID = $(this).parents("#LowerCell").siblings('#ApproveTakerID').text();
			var ownerID = $(this).parents("#LowerCell").siblings('#ApproveOwnerID').text();
			var ShiftID = $(this).parents("#LowerCell").siblings('#ApproveShiftID').text();	
			ShiftSwitch( subslipID, TakerID, ownerID, ShiftID );			
		});
		function ShiftSwitch( subslipID, TakerID, ownerID, ShiftID ) {
			var inputarr = {};
			inputarr["subslipID"] = subslipID;
			inputarr["ownerID"] = ownerID;
			inputarr["TakerID"] = TakerID;
			inputarr["ShiftID"] = ShiftID;		
			$.post('<?= base_Url(); ?>index.php/SupervisorControllers/ApproveSubSlips/DoSubSlipSwitch', inputarr, function(data) {		
				var instructions = JSON.parse( data );
				if( instructions == 1) {
					getAllFutureSubSlips();		
					alert("The shift was switched and the employee was notified.");					
				} else 
				{ alert("The shift was Not switched. There was an error doing the switch."); }				
			});					
		}/// End of Function ShiftSwitch 
		function OpenRejectModal(subslipID,ownerID) {
			$('#subslipID').html(subslipID);  $('#ownerID').html(ownerID);			
			var modal = $('#RejectSubSlipModal');  var close = $('#close');  var cancel = $('#btnRejectCancel');  var confirm = $('#btnRejectConfirm');
			modal.css({'display':"block"}); 									
			close.click(function() {									
				document.getElementById("Rejectreason").value = "";
				$('#subslipID').empty();  $('#ownerID').empty();
				modal.css({'display': "none"});		
			});
			cancel.click(function() {		
				document.getElementById("Rejectreason").value = "";
				$('#subslipID').empty(); $('#ownerID').empty();
				modal.css({'display': "none"});
			});
			$('#Rejectreason').focus();						
		}/// End of OpenRejectModal		
		/// When the reject button gets clicked on the reject modal.
		$('#RejectSubSlipModal').on('click','#btnRejectConfirm', function() { 
			var Rejectreason = "";
			Rejectreason = $('#Rejectreason').val();				
			if( Rejectreason == "") 
			{ alert("Must state a reason."); }
			else {				
				var subslipID = $('#subslipID').text();
				var ownerID =  $('#ownerID').text();
				RunReject(subslipID, ownerID, Rejectreason );				
			}				
		}); /// End of when RejectSubSlip gets clicked.											
		function RunReject(subslipID, ownerID, Rejectreason) {			
			var inputarr = {};
				inputarr["subslipID"] = subslipID;
				inputarr["ownerID"] = ownerID;
				inputarr["Rejectreason"] = Rejectreason;											
			if( subslipID != null || ownerID != null  ) {
				$.post('<?= base_Url(); ?>index.php/SupervisorControllers/ApproveSubSlips/RejectSubSlip', inputarr, function(data) {					
					var instructions = JSON.parse( data );
					if( instructions == 1) { alert("SubSlip was deleted and the employee was notified."); } else { alert("There was an error..") }
					document.getElementById("Rejectreason").value = "";				
					$('#subslipID').empty();  $('#ownerID').empty();
					$('#RejectSubSlipModal').css({'display': "none"});
					getAllFutureSubSlips();							
				});
			} else 
			{ alert("subslipID or ownerID are null! "); }
		}/// End of function RunReject				
		checkAutoApprove();					
		function checkAutoApprove() {
			var inputarr = {};
			$.post('<?= base_Url(); ?>index.php/SupervisorControllers/ApproveSubSlips/checkAutoApprove', inputarr, function(data) {	
				var OnOff =  data ;		
				if (OnOff == 1)
				{ $('#AutoApproveSwitch').prop('checked', true); }
				else
				{ $('#AutoApproveSwitch').prop('checked', false); }							
			});	
		}/// End of function checkAutoApprove						
		$('#AutoApproveSwitch').click(function() {			
			var chked = $('#AutoApproveSwitch').is(':checked');			
			if(chked == false) {
				var inputarr = {};
				$.post('<?= base_Url(); ?>index.php/SupervisorControllers/ApproveSubSlips/offAutoApprove', inputarr, function(data) { });				
			} else {				
				var inputarr = {};
				$.post('<?= base_Url(); ?>index.php/SupervisorControllers/ApproveSubSlips/onAutoApprove', inputarr, function(data){ });	
			}									
			var inputarr = {};
			$.post('<?= base_Url(); ?>index.php/SupervisorControllers/ApproveSubSlips/checkAutoApprove', inputarr, function(data) {	 var OnOff =  data; });						
		});
		/// End of When AutoApproveSwitch gets clicked.		
	});	
</script>


	<script type = "text/babel" >		
	const AMPM12HourArray = [ "12:00am", "12:15am", "12:30am", "12:45am", "1:00am", "1:15am", "1:30am", "1:45am", "2:00am", "2:15am", "2:30am", "2:45am", "3:00am", "3:15am", "3:30am", "3:45am", "4:00am", "4:15am", "4:30am", "4:45am", "5:00am", "5:15am", "5:30am", "5:45am", "6:00am", "6:15am", "6:30am", "6:45am", "7:00am", "7:15am", "7:30am", "7:45am", "8:00am", "8:15am", "8:30am", "8:45am", "9:00am", "9:15am", "9:30am", "9:45am", "10:00am", "10:15am", "10:30am", "10:45am", "11:00am", "11:15am", "11:30am", "11:45am", "12:00pm", "12:15pm", "12:30pm", "12:45pm", "1:00pm", "1:15pm", "1:30pm", "1:45pm", "2:00pm", "2:15pm", "2:30pm", "2:45pm", "3:00pm", "3:15pm", "3:30pm", "3:45pm", "4:00pm", "4:15pm", "4:30pm", "4:45pm", "5:00pm", "5:15pm", "5:30pm", "5:45pm", "6:00pm", "6:15pm", "6:30pm", "6:45pm", "7:00pm", "7:15pm", "7:30pm", "7:45pm", "8:00pm", "8:15pm", "8:30pm", "8:45pm", "9:00pm", "9:30pm", "9:45pm", "10:00pm", "10:15pm", "10:30pm", "10:45pm", "11:00pm", "11:15pm", "11:30pm", "11:45pm" ];									
	const mysqlTimeArray =  [ "00:00:00", "00:15:00", "00:30:00", "00:45:00", "01:00:00", "01:15:00","01:30:00", "01:45:00", "02:00:00", "02:15:00", "02:30:00", "02:45:00", "03:00:00", "03:15:00","03:30:00", "03:45:00", "04:00:00", "04:15:00", "04:30:00", "04:45:00","05:00:00", "05:15:00", "05:30:00", "05:45:00", "06:00:00", "06:15:00", "06:30:00", "06:45:00", "07:00:00", "07:15:00", "07:30:00", "07:45:00", "08:00:00", "08:15:00", "08:30:00", "08:45:00","09:00:00", "09:15:00", "09:30:00", "09:45:00", "10:00:00", "10:15:00", "10:30:00", "10:45:00", "11:00:00", "11:15:00", "11:30:00", "11:45:00", "12:00:00", "12:15:00", "12:30:00", "12:45:00","13:00:00", "13:15:00", "13:30:00", "13:45:00", "14:00:00", "14:15:00", "14:30:00", "14:45:00", "15:00:00", "15:15:00", "15:30:00", "15:45:00", "16:00:00", "16:15:00", "16:30:00", "16:45:00", "17:00:00", "17:15:00", "17:30:00", "17:45:00", "18:00:00", "18:15:00", "18:30:00", "18:45:00", "19:00:00", "19:15:00", "19:30:00", "19:45:00", "20:00:00", "20:15:00", "20:30:00", "20:45:00","21:00:00", "21:15:00", "21:30:00", "21:45:00", "22:00:00", "22:15:00", "22:30:00", "22:45:00","23:00:00", "23:15:00", "23:30:00", "23:45:00" ];						
		var Subslip = React.createClass({ 			
			render: function() {  	
				var name = this.props.slip.name;
				var reason = this.props.slip.Reason;
				var startTime = this.props.slip.startTime;
				var endTime = this.props.slip.endTime;
				
				var st = AMPM12HourArray[ mysqlTimeArray.indexOf(startTime) ];
				var et =  AMPM12HourArray[ mysqlTimeArray.indexOf(endTime) ];
				
				var date = this.props.slip.ShiftDate;
				var position = this.props.slip.Position;
				var cdt = this.props.slip.cdt;
			return (
				<div>
				<div id = "SubmittedSubSlip" className = {position}>
						<div className = "InnerCellText">
							<div className = "ownerNameShift">
								<span className = "Underline Bold">
									Owner of Shift:
								</span>
								{name}							
							</div>					
							<div id = "UpperCell">						
								<div className = "ReasonView"> 
									<span className = "Underline Bold">
										Reason
									</span>   
									{reason} 
								</div>							
								<div className = "CellTopRight">	
									<p>									
										<span className = "Underline Bold">Subslip creation date & time:</span> {cdt}	<br /> 
									</p>
									<p>
      								<span className = "Underline Bold">
										Shift Info <br />
									</span>									
										Shift Date: {date} <br />
										Position: <span className = "Bold">{position}</span>  <br />
										StartTime: {st}  <br />
										EndTime: {et}	<br />	
									</p>
								</div>														
							</div>
						</div>
					</div>
					</div>
			);}
		});			
		var Unsignedsubslips = React.createClass({ 
			render: function() {  	
			return (
				<div id = "Yourshifts" >										
					{this.props.slips.map((slip, i) => (  						
						<Subslip key = {i} slip = {slip} id = "row" />											
					))}											
				</div>
			);}
		});			
		var Slips  = JSON.parse( '<?= $UnSignedSubslips ?>' );	
		var length = Slips.length;
		$('#UnSingedSubslips h3 #Count').empty().append(length);	
		
		ReactDOM.render ( <Unsignedsubslips slips = {Slips} />, document.getElementById("InnerUnSingedSubslips") );
	</script>


<a href ="<?= base_url(); ?>index.php?/SupervisorControllers/TheEditPage"> <div id = "EditButton"> <p> Supervisor page <p> </div></a>
<div id = "ApproveSubSlippage">
	<div id="RejectSubSlipModal" class="modal">
		<div id ="RSModalcontent">
			<div id="close">&times;</div>						
			<h3> Tell Employee why Subslip is being rejected. </h3>
			<form>			
				<p> Enter reason here. <textarea  id = "Rejectreason" name="Rejectreason"  maxlength = "200" cols ="40" rows ="4" ></textarea> </p>
				<input type = "button" id = "btnRejectConfirm" value = "Confirm"/>
				<input type = "button" id = "btnRejectCancel" value = "Cancel"/>
				<span id ="subslipID" class = "HideEmployeeID" ></span>
				<span id ="ownerID" class = "HideEmployeeID" ></span>
			<form>
		</div>
	</div>	
	<h1>Approve Sub Slips</h1>	
	
	<div id = "AutoApprove"> <p> Auto Approve?	<input type = "checkbox" id = "AutoApproveSwitch" name = "AutoApproveSwitch" > Check marking this allows subslips to automatically be approved when someone signs a subslip.</p></div>				
	<div id = "BoxOfAvialiableSubslips" >
		<h3> Submitted subslips that have been taken, waiting approval by a supervisor. Count: <span id = "Count"> </span></h3>
		<div id = "InnerBoxOfAvialiableSubslips" >		 </div>
	</div>
	
	<div id = "UnSingedSubslips"> 
		<h3>Subslips that have been submitted, and have not been taken yet. Count: <span id = "Count"> </span> </h3>
		<div id = "InnerUnSingedSubslips"></div>
	</div>		
</div><!--End of id ApproveSubSlippage -->