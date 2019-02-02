window.RunJS = function RunJS(  parA, parB, parC, parD, parE, parF  ) {	
	const ASSET_URL = parA;	const BASE_URL = parB;	const TODAY_DISPLAY_DATE  = parC;
	const TODAY_YYYYMMDD = parD;	const TODAYS_SCHEDULE_ARRAY  = parE;	const DEFAULT_EMPLOYEE_ARR = parF;
	const AMPM12HourArray = [ "12:00am", "12:15am", "12:30am", "12:45am", "1:00am", "1:15am", "1:30am", "1:45am", "2:00am", "2:15am", "2:30am", "2:45am", "3:00am", "3:15am", "3:30am", "3:45am", "4:00am", "4:15am", "4:30am", "4:45am", "5:00am", "5:15am", "5:30am", "5:45am", "6:00am", "6:15am", "6:30am", "6:45am", "7:00am", "7:15am", "7:30am", "7:45am", "8:00am", "8:15am", "8:30am", "8:45am", "9:00am", "9:15am", "9:30am", "9:45am", "10:00am", "10:15am", "10:30am", "10:45am", "11:00am", "11:15am", "11:30am", "11:45am", "12:00pm", "12:15pm", "12:30pm", "12:45pm", "1:00pm", "1:15pm", "1:30pm", "1:45pm", "2:00pm", "2:15pm", "2:30pm", "2:45pm", "3:00pm", "3:15pm", "3:30pm", "3:45pm", "4:00pm", "4:15pm", "4:30pm", "4:45pm", "5:00pm", "5:15pm", "5:30pm", "5:45pm", "6:00pm", "6:15pm", "6:30pm", "6:45pm", "7:00pm", "7:15pm", "7:30pm", "7:45pm", "8:00pm", "8:15pm", "8:30pm", "8:45pm", "9:00pm", "9:30pm", "9:45pm", "10:00pm", "10:15pm", "10:30pm", "10:45pm", "11:00pm", "11:15pm", "11:30pm", "11:45pm" ];									
	const mysqlTimeArray =  [ "00:00:00", "00:15:00", "00:30:00", "00:45:00", "01:00:00", "01:15:00","01:30:00", "01:45:00", "02:00:00", "02:15:00", "02:30:00", "02:45:00", "03:00:00", "03:15:00","03:30:00", "03:45:00", "04:00:00", "04:15:00", "04:30:00", "04:45:00","05:00:00", "05:15:00", "05:30:00", "05:45:00", "06:00:00", "06:15:00", "06:30:00", "06:45:00", "07:00:00", "07:15:00", "07:30:00", "07:45:00", "08:00:00", "08:15:00", "08:30:00", "08:45:00","09:00:00", "09:15:00", "09:30:00", "09:45:00", "10:00:00", "10:15:00", "10:30:00", "10:45:00", "11:00:00", "11:15:00", "11:30:00", "11:45:00", "12:00:00", "12:15:00", "12:30:00", "12:45:00","13:00:00", "13:15:00", "13:30:00", "13:45:00", "14:00:00", "14:15:00", "14:30:00", "14:45:00", "15:00:00", "15:15:00", "15:30:00", "15:45:00", "16:00:00", "16:15:00", "16:30:00", "16:45:00", "17:00:00", "17:15:00", "17:30:00", "17:45:00", "18:00:00", "18:15:00", "18:30:00", "18:45:00", "19:00:00", "19:15:00", "19:30:00", "19:45:00", "20:00:00", "20:15:00", "20:30:00", "20:45:00","21:00:00", "21:15:00", "21:30:00", "21:45:00", "22:00:00", "22:15:00", "22:30:00", "22:45:00","23:00:00", "23:15:00", "23:30:00", "23:45:00" ];						
	const Arrmonths = ['January','February','March','April','May','June','July','August','September','October','November','December']; 		
	
	/// Mydateselector component for selecting dates.
	var Mydateselector = React.createClass({
		getInitialState:function() { 
			var today = new Date();
			var dd = today.getDate(); var mm = today.getMonth(); 
			var yyyy = today.getFullYear();			
			var Arryears  = [ yyyy, yyyy+1, yyyy+2, yyyy+3, yyyy+4, yyyy+5]; 			
			var DisMonth = Arrmonths[mm];							
			var Arrdays = this.CalculateDaysArr(DisMonth,yyyy);												
			return { Day: dd, Month: DisMonth, Year: yyyy , dayss: Arrdays, monthss: Arrmonths, yearss:Arryears	 };			
		},
		CalculateDaysArr: function(month, year) {			
			var ArrDays = []; var NumDaysinMonth;			
			 // 31 or 30 days?
			if(month === 'January' || month === 'March' || month === 'May' || month === 'July' || month === 'August' || month === 'October' || month === 'December') 
			{ NumDaysinMonth = 31; } 
			else if(month === 'April' || month === 'June' || month === 'September' || month === 'November') 
			{ NumDaysinMonth = 30; }
			else {   
				var yr = year; /* If month is February, calculate whether it is a leap year or not */
				(yr - 2016) % 4 === 0 ? NumDaysinMonth = 29 : NumDaysinMonth = 28;
			}			
			var di= 0;
			do {
				di++;
				ArrDays.push(di);				
			}while(di < NumDaysinMonth )						
			return ArrDays;			
		},
		DayChange: function(event) {
			var d  =  event.target.value;  var y  = this.state.Year;  var m  = this.state.Month;
			this.props.dateChange(d, m, y);
			this.setState({ Day: d,});						
		},
		MonthChange: function(event) {			
			var y  = this.state.Year;  var m  =  event.target.value;			
			var  Arrdays = this.CalculateDaysArr(m,y);	
			var d = 0;	var lstdy = Arrdays.length;		 
			if ( lstdy < this.state.Day ) // Set the new day state to the current state day if the last day of the set month is  is less than the current day state.		
			{ d = lstdy; }
			else {
				var s = this.state.Day;
				d = Number(s);
			}					
			this.props.dateChange(d, m, y);		
			this.setState({ Month: m, dayss: Arrdays, Day: d});					
		},
		YearChange: function(event) {						
			var m  = this.state.Month;  var y  =  event.target.value;			
			var  Arrdays = this.CalculateDaysArr(m,y);	
			var d = 0;  var lstdy = Arrdays.length;						
			if ( lstdy < this.state.Day ) // Set the new day state to the current state day if the last day of the set month is  is less than the current day state.
			{  d = lstdy;  }
			else {
				var s = this.state.Day;
				d = Number(s);
			}			
			this.props.dateChange(d, m, y);	
			this.setState({ Year: y, dayss: Arrdays, Day: d });			
		},			
		render: function() {			
			return (
				<span id = "Step1">	<h4>Select Shift Date</h4>																												
					<span> Year <select id="year" name="year"  value={this.state.Year} onChange={this.YearChange}>							  
								{this.state.yearss.map((item) => (  <option  key = {item} value = {item} >{item}</option>  ))}								
								</select>
					</span>
					<span> Month <select id="month" name="month"  value={this.state.Month} onChange={this.MonthChange}>
								{this.state.monthss.map((item) => (  <option  key = {item} value = {item} >{item}</option>  ))}
								</select>
					</span>						
					<span> Day <select id="day" name="day"  value={this.state.Day} onChange={this.DayChange}>
								{this.state.dayss.map((item) => (  <option key = {item} value = {item} >{item}</option>  ))}
								</select>
					</span>						
				</span>							
			)
		},
	});/// End of Mydateselector component for selecting dates.




	/// Begining of Setshifttime, Parent for application.
	var Setshifttime = React.createClass({ 			
		startChange: function(event) {
			var s = event.target.value; var e = this.props.eIndex;
			s = parseInt(s); e = parseInt(e);					
			if ( s >= e-1 ) { e = s+2; }				
			this.props.startChange(s, e);					
		},
		endChange: function(event) {
			var e = event.target.value;				
			e = parseInt(e);				
			this.props.endChange(e);			
		},	
		render: function() { 			
			var startTimes = []; var endTimes = [];				
			for( var i =  0; i < AMPM12HourArray.length-2; i++) { startTimes.push( <option key = {i} value = {i} > { AMPM12HourArray[i] } </option> ); }		
			for( var i = this.props.sIndex + 2; i < AMPM12HourArray.length; i++) { endTimes.push( <option key = {i} value = {i} > { AMPM12HourArray[i] } </option>  );  }	
			var hours = (this.props.eIndex - this.props.sIndex) / 4;
			return ( 
				<div id = "Step2">		
					<h4>Select Shift Time</h4>
					<p>Start: 						
						<select name = "StartTime" id = "StartTime" onChange={this.startChange}> {startTimes} </select>					
						<span className ="End">End:</span> 						
						<select name = "EndTime" id = "EndTime" onChange={this.endChange}> {endTimes} </select>
					</p>
					<p> Hours: {hours} </p>
				</div>								
			)
		},
	});//// End of Setshifttime component




	/// Begining of Positionchoice, Parent for application.
	var Positionchoice = React.createClass({ 	
		setposition: function(event) {
			var p = event.target.value;
			p = parseInt( p );
			this.props.changePosition( p );				
		},
		render: function() { 		
			var lifeguard = false; var instructor = false; var headguard = false; var supervisor = false;		
			switch(this.props.selectedPosition) {			
				case 1: lifeguard = true; break;
				case 2: instructor = true; break;
				case 3: headguard = true; break;
				case 4: supervisor = true; break;			
			}	
			return ( 					
				<div id ="Step3"  >											
					<h4>Select type of Shift</h4>
					<div  className  ="LifeguardShift">		<input type = "radio" name="Cert" value = "1" id = "Lifeguard"  checked={lifeguard}  onChange={this.setposition}  />Lifeguard</div>
					<div  className  ="InstructorShift">	<input type = "radio" name="Cert" value = "2" id = "Instructor" checked={instructor} onChange={this.setposition}  />Instructor</div>
					<div  className  ="HeadGuardShift">		<input type = "radio" name="Cert" value = "3" id = "Headguard"  checked={headguard}  onChange={this.setposition} />Headguard</div>
					<div  className  ="SupervisorShift">	<input type = "radio" name="Cert" value = "4" id = "Supervisor" checked={supervisor} onChange={this.setposition}  />Supervisor</div>								
				</div>		
		)},
	});//// End of Positionchoice component.

			
			

			
	var Employeecell = React.createClass({ 	
		setEmployee: function(event) {
			event.preventDefault();
			var EmployeeID = this.props.employee["employeeID"];				
			if (  EmployeeID == this.props.Selectedid ){  this.props.UNsetEmployee(EmployeeID);  } else {  this.props.setEmployee(EmployeeID);  }
		},
		render: function() {
			var name =  ""+this.props.employee['Firstname']+" "+this.props.employee['Lastname']+"";											
			var General = "Availability not set yet.";  var A = "Availability not set yet.";				
			var Availability = this.props.employee.Availability;  var dayofweek = this.props.dayofweek;		
			if( Availability != null ) {  A = Availability[dayofweek];  General = Availability["GeneralNotes"];  }				
			var lifeguard = "";var instructor = "";var headguard = "";var supervisor = "";
			if( this.props.employee['Lifeguard']  == true  ){ lifeguard = "Yes";}else{lifeguard = "No";}
			if( this.props.employee['Instructor'] == true  ){ instructor = "Yes";}else{instructor = "No";}
			if( this.props.employee['Headguard']  == true  ){ headguard = "Yes";}else{headguard = "No";}
			if( this.props.employee['Supervisor'] == true  ){ supervisor = "Yes";}else{supervisor = "No";}
			var EmployeeID = this.props.employee["employeeID"];	 
			var CellSass = ""; var SelectedOrNOT = "Select Employee";		
			if (this.props.Selectedid != null) { 
				if(   EmployeeID == this.props.Selectedid  ) {  CellSass = "SelectedCell";	SelectedOrNOT = "Employee Selected";  }
			}
			var hoursthisweek = this.props.employee["HoursThisWeek"];  var Shifthours = this.props.hours;
			var total = hoursthisweek + Shifthours;			
			return ( 
				<div id = "EmployeeCell"   className = {CellSass}  >
					<div className = "EmployeeCellLEFT"> 
						<h2> {name} </h2>					
						<p> <span className= "Bold">Hours This Week:</span> { hoursthisweek } </p>
						<p> <span className= "Bold">Total hours plus this shift:</span> { hoursthisweek } + {Shifthours} = {total}</p>
						<p> <span className= "Bold">Availability on {this.props.dayofweek}:</span> { A }</p>
						<p> <span className= "Bold">General Availability Notes:</span> { General }</p>						
					</div>				
					<div className = "EmployeeCellRIGHT"> 
						<table className = "CertTableinEmployeeList">
							<thead>
								<tr>
									<th>Certifications</th><th>Yes or No </th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Lifeguard </td><td>{lifeguard}</td>
								</tr>
								<tr>
									<td>Instructor </td><td>{instructor}</td>
								</tr>
								<tr>
									<td>Headguard </td><td>{headguard}</td>
								</tr>
								<tr>
									<td>Supervisor </td><td>{supervisor}</td>
								</tr>
							</tbody>
						</table>																			
					</div>									
					<button onClick = { this.setEmployee }>{SelectedOrNOT}</button> 
				</div>
			)
		},
	});



				
	/// Begining of Controls, Parent for application.
	var Controls = React.createClass({ 
		dateChange: function(d, m, y) {  this.props.dateChange(d, m, y);  },
		render: function() { 
			return ( 
				<div id = "ManageScheduleTop">		
					<form name = "CreateShiftForm" id = "CreateShiftForm"  >		
						<div id = "StepsOneTwoThree" className = "GeneralWhiteGreyContainer">
<Mydateselector   dateChange = { this.dateChange} />																						
<Setshifttime  sIndex = {this.props.sIndex} eIndex = {this.props.eIndex} startChange = {this.props.startChange} endChange = {this.props.endChange} />																																
<Positionchoice changePosition = {this.props.changePosition}  selectedPosition = {this.props.position} />																					
						</div>
						<div id = "Step4" >
							<div id = "ChosenEmployee" className = "HideEmployeeID"></div>				
							<h4>
							Select an available employee. Shows employees available on selected date and who have the
							specified certification for the selected shift type. This list reloads every time the date or 
							shift type changes. Remember to select a shift type. Tip, use Ctrl + F to help find the employee you want to select. List is randomly sorted.
							</h4>						
								<div id = "EmployeeListBox" >								
									<div id = "EmployeeListInner"> 												
									{this.props.employeeArr.map((employee, i) => (											
<Employeecell hours = {this.props.hours} UNsetEmployee = {this.props.UNsetEmployee} Selectedid = {this.props.Selectedid} setEmployee = {this.props.setEmployee} dayofweek = {this.props.dayofweek} employee = {employee} key={i} />																								
									))}								
									</div>
								</div>					
						</div>										
						<div id = "FeedBackBox">																				
<Createshiftbutton  TryToCreateEmployee = {this.props.TryToCreateEmployee}  position = {this.props.position} sIndex = {this.props.sIndex} eIndex = {this.props.eIndex} date = {this.props.date} Selectedid = {this.props.Selectedid}  />												
						</div>			
					</form>
				</div>
			);
		},
	});/// End of Controls.




	var Createshiftbutton = React.createClass({
		TryToCreateEmployee: function(event) {
			event.preventDefault();
			var p = this.props.position;  var s = this.props.sIndex; var e = this.props.eIndex; var d = this.props.date; var id = this.props.Selectedid;				
			this.props.TryToCreateEmployee( p, s, e, id, d );
		},	
		render: function() {
			var p = this.props.position; var s = this.props.sIndex; var e = this.props.eIndex; var d = this.props.date; var id = this.props.Selectedid;
			var sass = ""; var abled = "";
			if( p == 0 || id == null ){ sass = "DisabledCreateShiftSubmit";  abled = true; } else {  sass = "EnabledCreateShiftSubmit"; abled = false; }			
			return ( 
<button id = "CreateShiftSubmit" className = {sass}  onClick = { this.TryToCreateEmployee } disabled = {abled} > Create Shift </button>
			)
		},	
	});



	var Editshifmodal = React.createClass({
		delete: function() {
			var shiftID = this.props.shiftInfo[0];		
			this.props.deleteShift( shiftID );
		},	
		close: function() {	 this.props.close(); },
		render: function() {
			var shiftInfo = this.props.shiftInfo;
			var shiftID = shiftInfo[0]; var hours = shiftInfo[1]; var name = shiftInfo[2]+" "+shiftInfo[3]; var start = shiftInfo[4]; var end = shiftInfo[5]; var position = shiftInfo[6];							
			return (
				<div className = "modal">				
					<div id="ModalContentShiftInfo" className = {position}>
						<div className = "InnerCellText">
							<button id = "close" onClick ={this.close} >X</button>
							<div>
							<h2> Shift Info: </h2>				
							<div><span className ="Bold"> Name:  </span> {name} </div>
							<div><span className ="Bold"> Position: </span> {position} </div>
							<div><span className ="Bold"> Start:  </span> {start}  </div>
							<div><span className ="Bold"> End: </span> {end}   </div>				
							<div><span className ="Bold"> Hours: </span> {hours} </div>				 
							</div>
							<button id = "deleteShiftbutt"  onClick ={this.delete} > Delete Shift </button>
						</div>
					</div>
				</div>
			)
		},
	});



	var Shiftinfo = React.createClass({	
		shiftInfo: function() {									
			var	shiftID = this.props.shiftID;  var	hours = this.props.hours;  var	firstname = this.props.firstname; var	lastname = this.props.lastname; var	ShiftStart = this.props.ShiftStart; var	ShiftEnd = this.props.ShiftEnd; var	position = this.props.position;		
			this.props.ShowShiftModal( shiftID, hours, firstname, lastname, ShiftStart, ShiftEnd, position );		
		},
		render:function() {  return (  <button  onClick = { this.shiftInfo } className = "ShiftInfoButton" > Shift Info  </button> 	); },
	});
	 

	 
	 
	/// Shiftrow component
	var Shiftrow = React.createClass({	
		render:function() {			
			var shifts = this.props.employee["Shifts"];		
			var countOfShifts = shifts.length; var rowblocks = [];	var s = 0; var middleShift = false; var currentShiftStart = ""; var currentShiftEnd = ""; var position = "";		
			for( var i = 0; i < 95; i++ ) {			
				if( s < countOfShifts ) {
					currentShiftStart = shifts[s]["startTime"]; currentShiftEnd = shifts[s]["endTime"];	 position = shifts[s]["Position"];				
					if( currentShiftStart == mysqlTimeArray[i] ) {
						middleShift = true; var sass =  "StartofShiftBlock "+position; var  ShiftStart = AMPM12HourArray[i];			
						var	e =  mysqlTimeArray.indexOf( currentShiftEnd ) ;  var ShiftEnd = AMPM12HourArray[ e ]; var ShiftID = shifts[s][ "ShiftID" ];
						var hours = (e - i) /4 ;					
						rowblocks.push(
							<div key = {i+170}   className = {sass} > <span className = "textSmall">  <Shiftinfo ShowShiftModal = {this.props.ShowShiftModal} shiftID = {ShiftID} hours = {hours} firstname = {this.props.employee["Firstname"]} lastname = {this.props.employee["Lastname"]} ShiftStart={ShiftStart} ShiftEnd={ShiftEnd}  position={position} />	 { AMPM12HourArray[i]  } </span></div>
						);				
					} else if( currentShiftEnd == mysqlTimeArray[i+1] ) {						
						middleShift = false; s++;/* increment shift index.*/ var sass =  "EndofShiftBlock "+position;					
						rowblocks.push( <div key = {i+170}  className = {sass}> <span className = "textSmall"> { AMPM12HourArray[i]  } </span></div> );				
					} else if( middleShift == true) {
						var sass =  "MiddleofShiftBlock "+position;
						rowblocks.push( <div key = {i+170} className = {sass} > <span className = "textSmall"> { AMPM12HourArray[i]  } </span></div> );			
					} else {
											var sass =  "";
					if ( (i)%4 == 0 )
					{	sass += " EmptyWholeBlock "; }			
					else 
					{ sass += " EmptyBlock "; }									
					if( i < 16 || i > 84)
					{ sass += " emptyShadeNight"; }
					else if ( i >= 16 && i < 44 )
					{  sass += " emptyShadeMorning"; }
					else if( i >= 44 && i < 52 )
					{	sass += " emptyShadeLunch"; }
					else if( i >= 52 && i < 72 )
					{ sass += " emptyShadeAfternoon"; }
					else if( i >= 72 && i <= 84)
					{ sass += " emptyShadeEvening" }
				
					rowblocks.push( <div key = {i+170}  className = {sass}> <span className = "timetextSmall"> { AMPM12HourArray[i]  } </span></div> );			

					}	
				} else {
					var sass =  "";
					if ( (i)%4 == 0 )
					{ sass += " EmptyWholeBlock "; }				
					else 
					{ sass += " EmptyBlock "; }				
					if( i < 16 || i > 84)
					{ sass += " emptyShadeNight "; }
					else if ( i >= 16 && i < 44 )
					{  sass += " emptyShadeMorning "; }
					else if( i >= 44 && i < 52 )
					{	sass += " emptyShadeLunch "; }
					else if( i >= 52 && i < 72 )
					{ sass += " emptyShadeAfternoon "; }
					else if( i >= 72 && i <= 84)
					{ sass += " emptyShadeEvening " }
									
					rowblocks.push( <div key = {i+170} className = {sass}> <span className = "timetextSmall"> { AMPM12HourArray[i]  } </span></div> );			
				}					
			}
			return ( <div className = "Shiftrow"> {rowblocks} </div> );
		},
	});/// End of Shiftrow Component 




	/// Begining of TheScheduleDisplay, Parent for application.
	var TheScheduleDisplay = React.createClass({  	
		render: function() {			
			var timeblock = [];  var namesblock = [];  var theShiftsBlock = [];		
			for( var i = 0; i < 95; i++ ) { timeblock.push( <div key = {i} className = "timeblock"> <span className = "textSmall"> { AMPM12HourArray[i]  } </span></div> );}				
			for( var e = 0; e < this.props.TheScheduleArray.length; e++ ) {				
				var firstname =this.props.TheScheduleArray[e]["Firstname"]; var lastname = this.props.TheScheduleArray[e]["Lastname"];
				namesblock.push( <div key = {e+95} className = "nameblock"> <span className = "nametext">{firstname} {lastname}</span></div> );			
				theShiftsBlock.push(<Shiftrow key = {e+50} ShowShiftModal = {this.props.ShowShiftModal} employee = {this.props.TheScheduleArray[e]} /> );
			}
			var displayDate = this.props.dayofweek;
			displayDate = displayDate.substring(0, displayDate.length - 1);
			return ( 
				<div> 
				<div id = "DateBox"><p> Date:{displayDate} {this.props.displayDate} </p></div>							
				<div id = "TheScheduleDisplay">
					<div id = "nameColumn" >
						<div id = "CornerStone"></div>
						{namesblock}
						<div id = "CornerStone"></div>
					</div> 
					<div id = "rightSideOfSchedulething">
						<div id = "timeView">
							{timeblock}
						</div>				
						<div id = "theShiftsBlock">
							{theShiftsBlock}				
						</div>		
					</div>
				</div>
				</div>			
			);
		},
	});/// End of Displayschedule.

	function weekSwitcher(x) {
		var dayofweek;
		switch(x){case 0: dayofweek = "Mondays"; break; case 1: dayofweek = "Tuesdays"; break; case 2: dayofweek = "Wednesdays"; break; case 3: dayofweek = "Thrusdays"; break; case 4: dayofweek = "Fridays"; break; case 5: dayofweek = "Saturdays"; break; case 6: dayofweek = "Sundays"; break;	 }/* End of switch day */		
		return dayofweek;
	}

	/// Begining of ManageSchedule app page, Parent for application.
	var ManageScheduleeapp = React.createClass({
		getInitialState:function() { 		
			var d = new Date(TODAY_YYYYMMDD);
			var x = d.getDay(); 	
			var dayofweek = weekSwitcher(x);			
			return {
				dayOFweek: dayofweek,
				date: TODAY_YYYYMMDD,
				displayDate: TODAY_DISPLAY_DATE,
				ScheduleArray: TODAYS_SCHEDULE_ARRAY,
				sIndex: 0,
				eIndex: 2,
				hours: 0.5,
				position: 1,
				employeeArr: DEFAULT_EMPLOYEE_ARR,
				SelectedEmployeeID: null,
				ShiftInfo: null, 
				ModalVisable: 0
			};
		},
		startChange: function(s,e) {		
			var p = this.state.position; var dayofweek = this.state.dayOFweek; var yyyymmdd = this.state.date; var display = this.state.displayDate; var TheScheduleArray = this.state.ScheduleArray;
			this.loadEmployees(  s, e, p, dayofweek, yyyymmdd, display, TheScheduleArray );
		},	
		endChange: function(e) {		
			var s = this.state.sIndex; var p = this.state.position; var dayofweek = this.state.dayOFweek; var yyyymmdd = this.state.date; var display = this.state.displayDate; var TheScheduleArray = this.state.ScheduleArray;		
			this.loadEmployees( s, e, p, dayofweek, yyyymmdd, display, TheScheduleArray );
		},
		changePosition: function(p) {					
			var e = this.state.eIndex; var s = this.state.sIndex; var dayofweek = this.state.dayOFweek; var yyyymmdd = this.state.date; var display = this.state.displayDate; var TheScheduleArray = this.state.ScheduleArray;
			this.loadEmployees( s, e, p, dayofweek, yyyymmdd, display, TheScheduleArray );
		},
		setEmployee: function(ID) {	 this.setState({ SelectedEmployeeID: ID });	 },
		loadEmployees: function( s, e, p, dayofweek, yyyymmdd, display, TheScheduleArray ) {									
				var inputArr = {};
					inputArr['yyyymmdd'] = yyyymmdd;	
					inputArr['StartTime'] = mysqlTimeArray[s];	
					inputArr['EndTime'] = mysqlTimeArray[e];	
					inputArr['ShiftType'] = p;				
				console.log(JSON.stringify(inputArr));
				$.post(''+BASE_URL +'index.php/SupervisorControllers/ManageSchedule/reloadEmployeeSelectBox', inputArr, function(data) {						
					console.log(data);
					if ( data != 0 ) {
					try {					
						var Arr = JSON.parse( data ); var h = (s+e) / 4;										
						this.setState({	 hours: h, sIndex: s, eIndex: e, position: p, SelectedEmployeeID: null, dayOFweek: dayofweek, date: yyyymmdd,  displayDate: display, ScheduleArray: TheScheduleArray, employeeArr: Arr, ShiftInfo: null,  ModalVisable: 0 });																			
						} catch(e) 
						{ alert("An error occurred when the server tried to reload the employee list. "); }						
					} else 
					{ alert("An error occurred when the server tried to reload the employee list. "); }										
					
				}.bind(this));							
		},	
		dateChange: function(d, m, y) {						
			var display = y+"-"+m+"-"+d; var MonthNumber = Arrmonths.indexOf(m) +1;		
			if( d<10 ) {  d = '0'+d;  } 
			if( MonthNumber<10 ) {  MonthNumber = '0'+MonthNumber;  } 		
			var yyyymmdd =  y+"-"+MonthNumber+"-"+d; var TheScheduleArray = [];	 
			var d = new Date(yyyymmdd);
			var x = d.getDay()			
			var dayofweek = weekSwitcher(x);			
			/* Reload schedule only when the set date is not equal to the current date of schedule. */
			if( yyyymmdd != this.state.date ) {	 this.reloadSchedule( dayofweek, yyyymmdd, display ); }		
		},
		reloadSchedule: function( dayofweek, yyyymmdd, display ) {
			var inputDate = {};		
					inputDate['GivenDate'] = yyyymmdd;				
				$.post(''+BASE_URL +'index.php/SupervisorControllers/ManageSchedule/reloadTheScheduleArraybyGivenDate', inputDate, function(data) {									
					if ( data != 0 ) {
						try {
							var TheScheduleArray = JSON.parse( data );	 																	
							var s = this.state.sIndex; var e = this.state.eIndex; var p = this.state.position;
							this.loadEmployees( s, e, p, dayofweek, yyyymmdd, display, TheScheduleArray );
						} catch(e) 
						{ alert("An error occurred when the server tried to reload the schedule. "); }						
					} else 
					{ alert("An error occurred when the server tried to reload the schedule. "); }										
				}.bind(this));				
		},
		TryToCreateEmployee: function(  p, s, e, id, d ) {			
			var inputarr = {};		
				inputarr['date'] = d;
				inputarr['StartTime'] = mysqlTimeArray[s];
				inputarr['EndTime'] = mysqlTimeArray[e];
				inputarr['ShiftType'] = p;
				inputarr['ID'] = id;					
			$.post(''+BASE_URL +'index.php/SupervisorControllers/ManageSchedule/CreateTheShift', inputarr, function(data) {																																							
				if( data != 1) {					
					alert("There was an error making the shift.");
				}
				else {					
					this.reloadSchedule(this.state.dayOFweek, d, this.state.displayDate );					
				}			
				
			}.bind(this));						 
		},		
		ShowShiftModal: function(  shiftID, hours, firstname, lastname, ShiftStart, ShiftEnd, position ) {		
			var shiftInfo = [shiftID, hours, firstname, lastname, ShiftStart, ShiftEnd, position] ;
			this.setState({ ShiftInfo: shiftInfo, ModalVisable: 1});
		},
		closeModal: function() { this.setState({ ShiftInfo: null, ModalVisable: 0}); },
		deleteShift: function(shiftID) {
			var inputarr = {};		
				inputarr['ShiftID'] = shiftID;					
			$.post(''+BASE_URL +'index.php/SupervisorControllers/ManageSchedule/deleteShift', inputarr, function(response) {																
				if( response != 1  )
				{
					alert('Shift was not deleted, an error occured.');
				} else {													
					var dayofweek = this.state.dayOFweek; var yyyymmdd = this.state.date; var display = this.state.displayDate;								
					this.reloadSchedule( dayofweek, yyyymmdd, display);				
				}
				
			}.bind(this));				
		},
		UNsetEmployee: function() { this.setState({ SelectedEmployeeID: null }); },
		render: function() { 
			var editmodal;		
			if( this.state.ModalVisable == 1 && this.state.ShiftInfo != null) { 
				editmodal = <Editshifmodal deleteShift = {this.deleteShift}    close = {this.closeModal} shiftInfo = {this.state.ShiftInfo} ModalVisable = {this.state.ModalVisable} /> ;
			} else 
			{ editmodal = <div></div>; }	
			return ( 
				<div>
					{editmodal}
					<Controls  hours = {this.state.hours} UNsetEmployee = {this.UNsetEmployee} TryToCreateEmployee = {this.TryToCreateEmployee} position = {this.state.position} Selectedid = { this.state.SelectedEmployeeID }  setEmployee = {this.setEmployee} dayofweek = {this.state.dayOFweek} employeeArr = {this.state.employeeArr} changePosition = {this.changePosition} sIndex = {this.state.sIndex} eIndex = {this.state.eIndex} date = {this.state.date}  dateChange = {this.dateChange} startChange = {this.startChange} endChange = {this.endChange}/>
					<TheScheduleDisplay ShowShiftModal = {this.ShowShiftModal} dayofweek = {this.state.dayOFweek}   TheScheduleArray = {this.state.ScheduleArray}   displayDate = {this.state.displayDate} />
				</div> 
			);
		},
	});/// End of Scheduleapp


	ReactDOM.render ( <ManageScheduleeapp />, document.getElementById("ManageSchedule") );	
};