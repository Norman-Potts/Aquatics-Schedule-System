
<link href = "<?= assetUrl(); ?>css/MyProfile.css" rel="stylesheet" type="text/css"/> 
<div id = "ProfileBox" >		

	
	<h3 id = "ProfileName" > <?= $_SESSION['Firstname']?>   <?= $_SESSION['Lastname'] ?> 
		<?php if ($_SESSION['Supervisor'] == true  ){   ?>				
  	  	<a href ="https://normanpotts.ca/WebPortfolio/Aquatics_Schedule_System/SupervisorsControls/"> <div id = "EditButton"> <p> Supervisor page <p> </div></a>
		<?php }?>		
	</h3>											
	

	
	<div id = "CRTtable">
		<table  border="1" >
			<thead> 
				<tr><th><h3>Certifications</h3></th><td>Lifeguard </td><td>Instructor</td><td>Headguard </td><td>Supervisor</td></tr>
			</thead>
			<tbody>		
				<tr><th><h3>Yes or No</h3> </th> 
				<td><?php if($_SESSION['Lifeguard']) {echo "Yes";}else{echo "No";} ?></td> 		
				<td><?php if($_SESSION['Instructor']){echo "Yes";}else{echo "No";} ?></td>
				<td><?php if($_SESSION['Headguard']) {echo "Yes";}else{echo "No";} ?></td>
				<td><?php if($_SESSION['Supervisor']){echo "Yes";}else{echo "No";} ?></td></tr>
			</tbody>
		</table>
	</div>
	
	
	<br />
	<div id = "UserAvailability">
		<h3>Availiability</h3>
		<div id= "UserAvailabilityinner">	
		<table border="1">
			<tbody>			
			<tr><td class = "leftTD">Monday:</td>         <td class = "rightTD"><p><?php  echo $Availability->Mondays; ?>  </p></td></tr>
			<tr><td class = "leftTD">Tuesday:</td>        <td class = "rightTD"><p> <?php  echo $Availability->Tuesdays; ?> </p></td></tr>
			<tr><td class = "leftTD">Wednesday:</td>      <td class = "rightTD"><p> <?php  echo $Availability->Wednesdays; ?> </p></td></tr>
			<tr><td class = "leftTD">Thrusday:</td>       <td class = "rightTD"><p> <?php  echo $Availability->Thrusdays; ?> </p></td></tr>
			<tr><td class = "leftTD">Friday:</td>         <td class = "rightTD"><p> <?php  echo $Availability->Fridays; ?> </p></td></tr>
			<tr><td class = "leftTD">Saturday:</td>       <td class = "rightTD"><p> <?php  echo $Availability->Saturdays; ?> </p></td></tr>
			<tr><td class = "leftTD">Sunday:</td>         <td class = "rightTD"><p> <?php  echo $Availability->Sundays; ?> </p></td></tr>
			<tr><td class = "leftTD">General Notes:</td>  <td class = "rightTD"><p> <?php  echo $Availability->GeneralNotes; ?> </p></td></tr>					
			</tbody>
		</table>
		
		</div> 
	</div>			
	<br />
		
	<div id = "YourShftsBlock" >
	<h3> Your Shifts. </h3>
	<div id = "YourShiftsInner">
		
	</div>
	</div>	
	<script type = "text/babel" >		
		var Shift = React.createClass({ 			
			render: function() {  	
			var sub = (this.props.shift.AssignedSubSlipID  != null )? "Yes" : "Nope" ;
			return (
				<div id = "SubSlipCell" className = {this.props.shift.Position} >										
				<div className = "InnerCellText">
				<p>
				Date: <span id = "SshiftDate" className = "bold">{this.props.shift.date}</span> <br />
				Position: <span id = "SshiftPosition" className = "bold">{this.props.shift.Position}</span><br />
				Time: <span id = "SshiftTime" className = "bold" >{this.props.shift.startTime} - {this.props.shift.endTime}</span><br />
				Submitted Subslip? <span className = "bold">{ sub }</span>
				</p>
				</div>
				</div>
			);}
		});			
		var Yourshifts = React.createClass({ 
			render: function() {  	
			return ( <div id = "Yourshifts" > {this.props.shifts.map((shift, i) => (   <Shift key = {i} shift = {shift} id = "row" /> ))} </div> );}
		});			
		var ShiftsArr = <?php echo $Shifts ?>;
		ReactDOM.render ( <Yourshifts shifts = {ShiftsArr} />, document.getElementById("YourShiftsInner") );
	</script>
	

	<div id = "YourSubSlipsBlock" >
	<h3> Your Submitted Sub-Slips.</h3>
	<div id = "YourSubSlipsInner">	
	</div>
	</div>	
	<script type = "text/babel" >		
		var Slip = React.createClass({ 		
			deletSubSlip: function(event) {				
				event.preventDefault();
				if( confirm("Are you sure you want to remove this sub slip.") ) {	
					var inputArr = {};	
					inputArr['subslipID'] = this.props.slip.subslipID;
					console.log(inputArr);
					$.post('<?= base_Url(); ?>index.php/MyProfile/RemoveSubSlip', inputArr, function(data) {							
						try {
							console.log(data);
							var response = JSON.parse(data);
							console.log(response);
							switch(response) {					
								
								case 1:		
									alert("Subslip was removed." );
									location.reload();							
								break;	
								case 2:
									alert("Subslip was signed before it could be deleted. ");
								break;
								case 3:
									alert("Errors Something broke idk... ");
								break;
								default:
									alert("Errors Something broke idk... ");						
							}
							
						}catch(e)
						{
							alert("Errors Something broke idk... ");
						}
					});										
				}				
			},		
			render: function() {  				
				var YesNo = (this.props.slip.TakenTrueorFalse == 1 )? "Yes" : "Nope" ;						
				var deletebutton = <button onClick = {this.deletSubSlip} > Remove SubSlip </button> ;						
			var reason = this.props.slip.Reason;		
			return (
				<div id = "SubSlipCell" className = {this.props.slip.Position} >																			
					<div className = "InnerCellText">						
						<div id = "UpperCell">																	
							<div className = "CellTopRight">								
									Date: <span id = "SshiftDate" className = "bold">{this.props.slip.ShiftDate}</span> <br />
									Position: <span id = "SshiftPosition" className = "bold">{this.props.slip.Position}</span><br />
									Time: <span id = "SshiftTime" className = "bold" >{this.props.slip.startTime} - {this.props.slip.endTime}</span><br />
									Taken? <span className = "bold">{ YesNo }</span><br />	
									<span className = "Underline bold"> Reason </span> 									
							</div>														
								<div className = "ReasonView"> 
									 {reason} 
								</div>															
						</div>
						<div id = "LowerCell">
							{deletebutton}
						</div>
					</div>
				</div>
			);}
		});			
		var Yoursubslips = React.createClass({ 
			render: function() {  	
			return ( <div id = "Yourshifts" > {this.props.slips.map((slip, i) => (  <Slip key = {i} slip = {slip} id = "row" /> ))} </div> );}
		});			
		var SubSlipsArr = <?php echo $SubSlips ?>;
		ReactDOM.render ( <Yoursubslips slips = {SubSlipsArr} />, document.getElementById("YourSubSlipsInner") );
	</script>
	
	<div id = "YourTakenSubSlipsBlock" >
	<h3> Your submitted Sub-Slips that have been signed by someone, waiting approval.</h3>
	<div id = "YourTakenSubSlipsInner">	
	</div>
	</div>
		<script type = "text/babel" >		
		var Slip = React.createClass({ 				
			render: function() {  				
				var YesNo = (this.props.slip.TakenTrueorFalse == 1 )? "Yes" : "Nope" ;					
				var takenfrom = <span></span>;
			console.log(this.props.slip.TakenTrueorFalse );
			if( this.props.slip.TakenTrueorFalse == 1  )
			{ 
				takenfrom = <span>Substitute:  {this.props.slip.Name}<br /></span>  ;							
			}			
			var reason = this.props.slip.Reason;		
			return (
				<div id = "SubSlipCell" className = {this.props.slip.Position} >																			
					<div className = "InnerCellText">						
						<div id = "UpperCell">																	
							<div className = "CellTopRight">								
									Date: <span id = "SshiftDate" className = "bold">{this.props.slip.ShiftDate}</span> <br />
									Position: <span id = "SshiftPosition" className = "bold">{this.props.slip.Position}</span><br />
									Time: <span id = "SshiftTime" className = "bold" >{this.props.slip.startTime} - {this.props.slip.endTime}</span><br />
									Taken? <span className = "bold">{ YesNo }</span><br />	
										{takenfrom}
									<span className = "Underline bold"> Reason </span> 									
							</div>														
								<div className = "ReasonView"> 
									 {reason} 
								</div>															
						</div>
						
					</div>
				</div>
			);}
		});			
		var YourTakenSubSlipsInner = React.createClass({ 
			render: function() {  	
			return ( <div id = "Yourshifts" > {this.props.slips.map((slip, i) => (  <Slip key = {i} slip = {slip} id = "row" />	 ))} </div> );}
		});			
		var SubSlipsArr = <?php echo $YourTakenSubSlips ?>;		
		ReactDOM.render ( <YourTakenSubSlipsInner slips = {SubSlipsArr} />, document.getElementById("YourTakenSubSlipsInner") );
	</script>
	
	
	<div id = "YourApprovedSubSlipsBlock" >
	<h3> Your Approved Sub-Slips.</h3>
	<div id = "YourApprovedSubSlipsInner">	
	</div>
	</div>
	<script type = "text/babel" >		
		var Slip = React.createClass({ 					
			render: function() {  									
				var takenfrom = <span>Substitute:  {this.props.slip.Name}<br /></span>  ;								
				var reason = this.props.slip.Reason;		
			return (
				<div id = "SubSlipCell" className = {this.props.slip.Position} >																			
					<div className = "InnerCellText">						
						<div id = "UpperCell">																	
							<div className = "CellTopRight">		
									<div className = "MargeZero paddingZero TextCenter bold" > Approved. </div>
									Date: <span id = "SshiftDate" className = "bold">{this.props.slip.ShiftDate}</span> <br />
									Position: <span id = "SshiftPosition" className = "bold">{this.props.slip.Position}</span><br />
									Time: <span id = "SshiftTime" className = "bold" >{this.props.slip.startTime} - {this.props.slip.endTime}</span><br />
									
										{takenfrom}
									<span className = "Underline bold"> Reason </span> 									
							</div>														
								<div className = "ReasonView"> 
									 {reason} 
								</div>															
						</div>
						
					</div>
				</div>
			);}
		});			
		var YourApprovedSubSlips = React.createClass({ 
			render: function() {  	
			return ( <div id = "Yourshifts" > {this.props.slips.map((slip, i) => (   <Slip key = {i} slip = {slip} id = "row" /> ))}	 </div> );}
		});			
		var SubSlipsArr = <?php echo $YourApprovedSubSlips ?>;		
		ReactDOM.render ( <YourApprovedSubSlips slips = {SubSlipsArr} />, document.getElementById("YourApprovedSubSlipsInner") );
	</script>
	
	
	
	
	<div id = "SlipsYouHaveSignedBlock" >
	<h3> Sub-Slips you have signed, waiting approval.</h3>
	<div id = "SlipsYouHaveSignedInner">	
	</div>
	</div>
		<script type = "text/babel" >		
		var Slip = React.createClass({ 					
			render: function() {  									
				var takenfrom = <span>Substitute:  {this.props.slip.Name}<br /></span>  ;								
				var reason = this.props.slip.Reason;		
			return (
				<div id = "SubSlipCell" className = {this.props.slip.Position} >																			
					<div className = "InnerCellText">						
						<div id = "UpperCell">																	
							<div className = "CellTopRight">											
									Date: <span id = "SshiftDate" className = "bold">{this.props.slip.ShiftDate}</span> <br />
									Position: <span id = "SshiftPosition" className = "bold">{this.props.slip.Position}</span><br />
									Time: <span id = "SshiftTime" className = "bold" >{this.props.slip.startTime} - {this.props.slip.endTime}</span><br />
									
										{takenfrom}
									<span className = "Underline bold"> Reason </span> 									
							</div>														
								<div className = "ReasonView"> 
									 {reason} 
								</div>															
						</div>
						
					</div>
				</div>
			);}
		});			
		var SlipsYouHaveSigned = React.createClass({ 
			render: function() {  	
			return ( <div id = "Yourshifts" > {this.props.slips.map((slip, i) => (   <Slip key = {i} slip = {slip} id = "row" /> ))}	 </div> );}
		});			
		var SubSlipsArr = <?php echo $SlipsYouHaveSigned ?>;		
		ReactDOM.render ( <SlipsYouHaveSigned slips = {SubSlipsArr} />, document.getElementById("SlipsYouHaveSignedInner") );
	</script>
	
	
	
	
	
	
	<div id = "YourApprovedSubSlipsBlock" >
	<h3> Sub-Slips you have signed and have been approved.</h3>
	<div id = "ApprovedSlipsYouHaveSignedInner">	
	</div>
	</div>
	<script type = "text/babel" >		
		var Slip = React.createClass({ 					
			render: function() {  									
				var takenfrom = <span>Covering for:  {this.props.slip.Name}<br /></span>  ;								
				var reason = this.props.slip.Reason;		
			return (
				<div id = "SubSlipCell" className = {this.props.slip.Position} >																			
					<div className = "InnerCellText">						
						<div id = "UpperCell">																	
							<div className = "CellTopRight">		
									<div className = "MargeZero paddingZero TextCenter bold" > Approved. </div>
									Date: <span id = "SshiftDate" className = "bold">{this.props.slip.ShiftDate}</span> <br />
									Position: <span id = "SshiftPosition" className = "bold">{this.props.slip.Position}</span><br />
									Time: <span id = "SshiftTime" className = "bold" >{this.props.slip.startTime} - {this.props.slip.endTime}</span><br />
									
										{takenfrom}
									<span className = "Underline bold"> Reason </span> 									
							</div>														
								<div className = "ReasonView"> 
									 {reason} 
								</div>															
						</div>
						
					</div>
				</div>
			);}
		});			
		var ApprovedSlipsYouHaveSigned = React.createClass({ 
			render: function() {  	
			return ( <div id = "Yourshifts" > {this.props.slips.map((slip, i) => (   <Slip key = {i} slip = {slip} id = "row" /> ))}	 </div> );}
		});			
		var SubSlipsArr = <?php echo $ApprovedSlipsYouHaveSigned ?>;		
		ReactDOM.render ( <ApprovedSlipsYouHaveSigned slips = {SubSlipsArr} />, document.getElementById("ApprovedSlipsYouHaveSignedInner") );
	</script>
	
	
</div><!--End of myProfile page -->