
window.RunJS = function RunJS(   parB, parC, parD, parE, ParF, ParG  ) {	

const BASE_URL = parB;	
const TODAY_DISPLAY_DATE  = parC;
const TODAY_YYYYMMDD = parD;	const TODAYS_SCHEDULE_ARRAY  = parE;	
const AMPM12HourArray = [ "12:00am", "12:15am", "12:30am", "12:45am", "1:00am", "1:15am", "1:30am", "1:45am", "2:00am", "2:15am", "2:30am", "2:45am", "3:00am", "3:15am", "3:30am", "3:45am", "4:00am", "4:15am", "4:30am", "4:45am", "5:00am", "5:15am", "5:30am", "5:45am", "6:00am", "6:15am", "6:30am", "6:45am", "7:00am", "7:15am", "7:30am", "7:45am", "8:00am", "8:15am", "8:30am", "8:45am", "9:00am", "9:15am", "9:30am", "9:45am", "10:00am", "10:15am", "10:30am", "10:45am", "11:00am", "11:15am", "11:30am", "11:45am", "12:00pm", "12:15pm", "12:30pm", "12:45pm", "1:00pm", "1:15pm", "1:30pm", "1:45pm", "2:00pm", "2:15pm", "2:30pm", "2:45pm", "3:00pm", "3:15pm", "3:30pm", "3:45pm", "4:00pm", "4:15pm", "4:30pm", "4:45pm", "5:00pm", "5:15pm", "5:30pm", "5:45pm", "6:00pm", "6:15pm", "6:30pm", "6:45pm", "7:00pm", "7:15pm", "7:30pm", "7:45pm", "8:00pm", "8:15pm", "8:30pm", "8:45pm", "9:00pm", "9:15pm", "9:30pm", "9:45pm", "10:00pm", "10:15pm", "10:30pm", "10:45pm", "11:00pm", "11:15pm", "11:30pm", "11:45pm" ];									
const mysqlTimeArray =  [ "00:00:00", "00:15:00", "00:30:00", "00:45:00", "01:00:00", "01:15:00","01:30:00", "01:45:00", "02:00:00", "02:15:00", "02:30:00", "02:45:00", "03:00:00", "03:15:00","03:30:00", "03:45:00", "04:00:00", "04:15:00", "04:30:00", "04:45:00","05:00:00", "05:15:00", "05:30:00", "05:45:00", "06:00:00", "06:15:00", "06:30:00", "06:45:00", "07:00:00", "07:15:00", "07:30:00", "07:45:00", "08:00:00", "08:15:00", "08:30:00", "08:45:00","09:00:00", "09:15:00", "09:30:00", "09:45:00", "10:00:00", "10:15:00", "10:30:00", "10:45:00", "11:00:00", "11:15:00", "11:30:00", "11:45:00", "12:00:00", "12:15:00", "12:30:00", "12:45:00","13:00:00", "13:15:00", "13:30:00", "13:45:00", "14:00:00", "14:15:00", "14:30:00", "14:45:00", "15:00:00", "15:15:00", "15:30:00", "15:45:00", "16:00:00", "16:15:00", "16:30:00", "16:45:00", "17:00:00", "17:15:00", "17:30:00", "17:45:00", "18:00:00", "18:15:00", "18:30:00", "18:45:00", "19:00:00", "19:15:00", "19:30:00", "19:45:00", "20:00:00", "20:15:00", "20:30:00", "20:45:00","21:00:00", "21:15:00", "21:30:00", "21:45:00", "22:00:00", "22:15:00", "22:30:00", "22:45:00","23:00:00", "23:15:00", "23:30:00", "23:45:00" ];				
const Arrmonths = ['January','February','March','April','May','June','July','August','September','October','November','December']; 		
const isSupervisor = ParF; 
const isHeadguard = ParG;


/// Mydateselector component for selecting dates.
var Mydateselector = React.createClass({
	getInitialState:function() { 
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth(); //January is 0!
		var yyyy = today.getFullYear();			
		var Arryears  = [ yyyy, yyyy+1, yyyy+2, yyyy+3, yyyy+4, yyyy+5];		
		var Arrmonths = ['January','February','March','April','May','June','July','August','September','October','November','December']; 		
		var DisMonth = Arrmonths[mm];							
		var  Arrdays = this.CalculateDaysArr(DisMonth,yyyy);												
		return {				
			Day: dd, Month: DisMonth, Year: yyyy , dayss: Arrdays, monthss: Arrmonths, yearss:Arryears																	
		};			
	},
	componentWillMount:function() {
		this.props.dateChange(this.state.Day, this.state.Month, this.state.Year);
	},		
	CalculateDaysArr: function(month, year) {			
		var ArrDays = [];
		var NumDaysinMonth;			
		 // 31 or 30 days?
		if(month === 'January' || month === 'March' || month === 'May' || month === 'July' || month === 'August' || month === 'October' || month === 'December') {
			NumDaysinMonth = 31;
		} else if(month === 'April' || month === 'June' || month === 'September' || month === 'November') {
			NumDaysinMonth = 30;
		} else {
			// If month is February, calculate whether it is a leap year or not
			var yr = year;
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
		var d  =  event.target.value;
		var y  = this.state.Year;
		var m  = this.state.Month;
		this.props.dateChange(d, m, y);
		this.setState({ Day: d,});						
	},
	MonthChange: function(event) {			
		var y  = this.state.Year;
		var m  =  event.target.value;			
		var  Arrdays = this.CalculateDaysArr(m,y);	
		var d = 0; // Set the new day state to the current state day if the last day of the set month is  is less than the current day state.		
		var lstdy = Arrdays.length;			
		if ( lstdy < this.state.Day ) {
			d = lstdy;
		} else {
			var s = this.state.Day;
				d = Number(s);
		}					
		this.props.dateChange(d, m, y);		
		this.setState({ Month: m, dayss: Arrdays, Day: d});					
	},
	YearChange: function(event) {						
		var m  = this.state.Month;
		var y  =  event.target.value;			
		var  Arrdays = this.CalculateDaysArr(m,y);	
		var d = 0; // Set the new day state to the current state day if the last day of the set month is  is less than the current day state.
		var lstdy = Arrdays.length;			
		if ( lstdy < this.state.Day ) {
			d = lstdy;
		} else {
			var s = this.state.Day;
			d = Number(s);
		}			
		this.props.dateChange(d, m, y);	
		this.setState({ Year: y, dayss: Arrdays, Day: d });			
	},			
	render: function() {			
		return (
			<span id = "Mydateselector">																													
				<span> Year <select id="year" name="year"  value={this.state.Year} onChange={this.YearChange}>							  
							{this.state.yearss.map((item) => (
								<option  key = {item} value = {item} >{item}</option>
							))}								
							</select>
				</span>
				<span> Month <select id="month" name="month"  value={this.state.Month} onChange={this.MonthChange}>
							{this.state.monthss.map((item) => (
								<option  key = {item} value = {item} >{item}</option>
							))}
							</select>
				</span>						
				<span> Day <select id="day" name="day"  value={this.state.Day} onChange={this.DayChange}>
							{this.state.dayss.map((item) => (																
								<option key = {item} value = {item} >{item}</option>									
							))}
							</select>
				</span>						
			</span>							
		)
	},
});/// End of Mydateselector component for selecting dates.




/// Begining of Controls, Parent for application.
var Controls = React.createClass({ 
	dateChange: function(d, m, y) {	this.props.dateChange(d, m, y);  },
	GoBackADay: function() {		
		var BackAdayDate; //YYYY-mm-dd of the previous day of the schedule.																						
		var date = new Date( this.props.date);
		date.setDate(date.getDate() - 1);
		var BackAdayDate = date.toISOString();
		BackAdayDate = BackAdayDate.substring(0,10);								
		var d = Number( BackAdayDate[8]+""+BackAdayDate[9]   );
		var m = Number( BackAdayDate[5]+""+BackAdayDate[6]   );
		var Arrmonths = ['January','February','March','April','May','June','July','August','September','October','November','December']; 		
		var month = Arrmonths[m-1];
		var y = Number( BackAdayDate[0]+""+BackAdayDate[1]+""+BackAdayDate[2]+""+BackAdayDate[3]      );
		this.props.dateChange(d, month, y);
	},
	GoForwardADay: function() {						
		var BackAdayDate; //YYYY-mm-dd of the previous day of the schedule.																						
		var date = new Date( this.props.date);			
		date.setDate(date.getDate() + 1);
		var BackAdayDate = date.toISOString();
		BackAdayDate = BackAdayDate.substring(0,10);											
		var d = Number( BackAdayDate[8]+""+BackAdayDate[9]   );
		var m = Number( BackAdayDate[5]+""+BackAdayDate[6]   );
		var Arrmonths = ['January','February','March','April','May','June','July','August','September','October','November','December']; 		
		var month = Arrmonths[m-1];			
		var y = Number( BackAdayDate[0]+""+BackAdayDate[1]+""+BackAdayDate[2]+""+BackAdayDate[3]      );
		this.props.dateChange(d, month, y);
	},
	Gotoday: function() {
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth(); //January is 0!
		var yyyy = today.getFullYear();			
		var Arrmonths = ['January','February','March','April','May','June','July','August','September','October','November','December']; 		
		var DisMonth = Arrmonths[mm];					
		this.props.dateChange(dd, DisMonth, yyyy);		
		
	},
	render: function() { 
		return ( 			
			<div id = "SchedulePageUpperRow">
				<button onClick = {this.Gotoday} id = "GoToToday">Go To Today</button>	
				<p> <Mydateselector dateChange = {this.dateChange} /> </p>								
				<div id = "SchedulePageBottomRow" > 
					<button onClick = {this.GoBackADay} id = "GoBackADay"><span className ="FontDouble"> &#8678; </span> Go back a day.</button> 
					<div id = "DateBox"><p> Date: {this.props.displayDate} </p></div>
					<button  onClick = {this.GoForwardADay} id = "GoForwardADay"> Go Forward a day.<span className ="FontDouble">&#8680;</span> </button> 
				</div>	
			</div>			
		);
	},
});/// End of Controls.




var Shiftinfo = React.createClass({	
	shiftInfo: function() {
		
		
		if(!isHeadguard || !isSupervisor)
		{
			
			var firstname = this.props.firstname;
			var lastname = this.props.lastname;		
			var MSG = "Shift Info \n";
				MSG += "Employee: "+this.props.firstname+" "+this.props.lastname+"\n";
				MSG += "Position: "+this.props.position+" \n";
				MSG += "Start: "+this.props.ShiftStart+" \n";
				MSG += "End: "+this.props.ShiftEnd+" \n";
				MSG += "Hours: "+this.props.hours+" \n";			
			alert(MSG);
		}
		else {
			var	shiftID = this.props.shiftID; 
			var	hours = this.props.hours;
			var	firstname = this.props.firstname;
			var	lastname = this.props.lastname; 
			var	ShiftStart = this.props.ShiftStart; 
			var	ShiftEnd = this.props.ShiftEnd; 
			var	position = this.props.position;		
			this.props.showModal(shiftID, hours, firstname, lastname, ShiftStart, ShiftEnd, position);
			
		}
		
		
		
		
	},
	render:function() {		
		return (
			<button  onClick = { this.shiftInfo } className = "ShiftInfoButton" >
				Shift Info
			</button>
		);		
	},
});


/// Shiftrow component
var Shiftrow = React.createClass({	
	render:function() {
		
		var shifts = this.props.employee["Shifts"];		
		var countOfShifts = shifts.length;		
		var rowblocks = [];	var s = 0; var middleShift = false;
		var currentShiftStart = ""; var currentShiftEnd = ""; var position = "";		
		var StartEnd = this.props.startend;
		for( var i = StartEnd[0]; i < StartEnd[1]; i++ ) {			
			if( s < countOfShifts ) {
				currentShiftStart = shifts[s]["startTime"];
				currentShiftEnd = shifts[s]["endTime"];					
				position = shifts[s]["Position"];
				var ShiftID = shifts[s]["ShiftID"]
				if( currentShiftStart == mysqlTimeArray[i] ) {
					middleShift = true;
					var sass =  "StartofShiftBlock "+position;
					var  ShiftStart = AMPM12HourArray[i];			
					var	e =  mysqlTimeArray.indexOf( currentShiftEnd ) ;
					var ShiftEnd = AMPM12HourArray[ e ];
					var hours = (e - i) /4 ;
					
					
					rowblocks.push( <div key = {i+170}   className = {sass} > <span className = "timetextSmall"> 
					<Shiftinfo showModal = {this.props.ShowModal} shiftID = {ShiftID} hours = {hours} firstname = {this.props.employee["Firstname"]} lastname = {this.props.employee["Lastname"]} ShiftStart={ShiftStart} ShiftEnd={ShiftEnd}  position={position} /> { AMPM12HourArray[i]  } </span></div> );				
				} else if( currentShiftEnd == mysqlTimeArray[i+1] ) {
					s++;/// increment shift index.
					middleShift = false;												
					var sass =  "EndofShiftBlock "+position;										
					rowblocks.push( <div key = {i+170}  className = {sass}> <span className = "timetextSmall"> { AMPM12HourArray[i]  } </span></div> );				
				} else if( middleShift == true) {
					var sass =  "MiddleofShiftBlock "+position;
					rowblocks.push( <div key = {i+170} className = {sass} > <span className = "timetextSmall"> { AMPM12HourArray[i]  } </span></div> );			
				} else {
					var sass =  "";
					if ( (i)%4 == 0 )
					{	sass += " EmptyWholeBlock "; }			
					else 
					{ sass += " EmptyBlock "; }		
				
					if( i < 20 || i > 83)
					{ sass += " emptyShadeNight"; }
					else if ( i >= 20 && i < 32 )
					{  sass += " emptyShadeMorning"; }
					else if ( i >= 32 && i < 46 )
					{  sass += " emptyShadeBrunch"; }
					else if( i >= 46 && i < 50 )
					{	sass += " emptyShadeLunch"; }
					else if( i >= 52 && i < 68 )
					{ sass += " emptyShadeAfternoon"; }
					else if( i >= 68 && i <= 83)
					{ sass += " emptyShadeEvening" }
				
					rowblocks.push( <div key = {i+170}  className = {sass}> <span className = "timetextSmall"> { AMPM12HourArray[i]  } </span></div> );			
					
				}	
			} else { /* After shifts have been printed.*/
				var sass =  "";
				if ( (i)%4 == 0 )
				{ sass += " EmptyWholeBlock "; }				
				else 
				{ sass += " EmptyBlock "; }				
				if( i < 20 || i > 83)
				{ sass += " emptyShadeNight "; }
				else if ( i >= 20 && i < 32 )
				{  sass += " emptyShadeMorning "; }
				else if ( i >= 32 && i < 46 )
				{  sass += " emptyShadeBrunch"; }
				else if( i >= 46 && i < 50 )
				{	sass += " emptyShadeLunch "; }
				else if( i >= 52 && i < 68 )
				{ sass += " emptyShadeAfternoon "; }
				else if( i >= 68 && i <= 83)
				{ sass += " emptyShadeEvening " }
				
				
				
				rowblocks.push( <div key = {i+170} className = {sass}> <span className = "timetextSmall"> { AMPM12HourArray[i]  } </span></div> );			
			}					
		}
		return (
		<div className = "Shiftrow">
			{rowblocks}
		</div>
		);
	},
});/// End of Shiftrow Component 


function findStartfindEnd( arr ) {
	var StartIndex = mysqlTimeArray.length;	 var EndIndex = 0;	
	for( var i = 0; i < arr.length; i++ ) {
		var shiftsArr = arr[i]["Shifts"];
		for( var s = 0; s < shiftsArr.length; s++ ) {
			var st = shiftsArr[s]["startTime"];
			var et = shiftsArr[s]["endTime"];			
			var sIndex = mysqlTimeArray.indexOf(st);
			var eIndex = mysqlTimeArray.indexOf(et);			
			if( sIndex < StartIndex ) { StartIndex = sIndex; }
			if( eIndex > EndIndex ) { EndIndex = eIndex; }									
		}
	}		
	if( StartIndex-2 >= 0 ) { StartIndex = StartIndex-2; } 
	if( EndIndex+2 < mysqlTimeArray.length) { EndIndex = EndIndex+2; }
	var StartEnd = [StartIndex,EndIndex];		
	return StartEnd;
}


/// Begining of TheScheduleDisplay, Parent for application.
var TheScheduleDisplay = React.createClass({  	
	render: function() {		
		var timeblock = [];  var namesblock = [];  var theShiftsBlock = [];		
		var arr = this.props.TheScheduleArray;
		var StartEnd = findStartfindEnd( arr );				
		for( var i = StartEnd[0]; i < StartEnd[1]; i++ ) { timeblock.push( <div key = {i} className = "timeblock"> <span className = "timetextSmall"> { AMPM12HourArray[i]  } </span></div> );}				
		
		if(this.props.TheScheduleArray.length > 0) {						
			for( var e = 0; e < this.props.TheScheduleArray.length; e++ ) {				
				var firstname = this.props.TheScheduleArray[e]["Firstname"];
				var lastname = this.props.TheScheduleArray[e]["Lastname"];
				namesblock.push( <div key = {e+95} className = "nameblock"> <span className = "nametext">{firstname} {lastname}</span></div> );			
				theShiftsBlock.push(<Shiftrow ShowModal = {this.props.ShowModal} startend = {StartEnd} key = {e+50} employee = {this.props.TheScheduleArray[e]} /> );
			}
		}else {			
			theShiftsBlock.push(<div><h1>No shifts on this date.</h1></div> );
		}
			
		return ( 
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
		);
	},
});/// End of Displayschedule.






	var Displayshifmodal = React.createClass({
		getInitialState:function() { 
			return {				
				HistoryArr: null
			};
		},
		showSlipHistory: function() { 
			var shiftInfo = this.props.shiftInfo;
			var ShiftID = shiftInfo[0];			
			var inputDate = {};
				inputDate['ShiftID'] = ShiftID;				
			$.post(''+BASE_URL+'/index.php/TheSchedulePage/getShiftHistoryArr', inputDate, function(data)
			{	
				var arr = [];
				
				try {															
				
					var historyArr  = JSON.parse( data );						
					for( var name in historyArr)
					{									
						arr.push(<li key = {name} >{historyArr[name]}</li>);						
					}
					
				

				}catch(e)
				{ arr.push( <li>um, Errors.</li> ); }

					
				this.setState({ HistoryArr: arr });					
				
			}.bind(this));			
			
		},
		close: function() {	 this.props.close(); },
		render: function() {
			var shiftInfo = this.props.shiftInfo;
			var shiftID = shiftInfo[0]; var hours = shiftInfo[1]; var name = shiftInfo[2]+" "+shiftInfo[3]; var start = shiftInfo[4]; var end = shiftInfo[5]; var position = shiftInfo[6];		
			var	arr = "";
			if(this.state.HistoryArr != null)
			{				
				arr = this.state.HistoryArr;				
			}
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
							<button onClick ={this.showSlipHistory} > Show subslip history. </button>
							</div>		
							<ol>
							{arr}
							</ol>														
						</div>
					</div>
				</div>
			)
		},
	});

	
	
	
/// Begining of Schedule app page, Parent for application.
var Scheduleapp = React.createClass({
	getInitialState:function() { 

		return {				
			date: TODAY_YYYYMMDD,
			displayDate: TODAY_DISPLAY_DATE,
			ScheduleArray: TODAYS_SCHEDULE_ARRAY,
			ModalVisable: 0,
			ShiftInfo: null
		};
	},	
	dateChange: function(d, m, y) {						
		var display = y+"-"+m+"-"+d;	
		var Arrmonths = ['January','February','March','April','May','June','July','August','September','October','November','December']; 			
		var MonthNumber = Arrmonths.indexOf(m) +1;		
		if(d<10) {  d = '0'+d;  } 
		if(MonthNumber<10) {  MonthNumber = '0'+MonthNumber;  } 		
		var yyyymmdd =  y+"-"+MonthNumber+"-"+d;										
		var TheScheduleArray = [];		
		/* Prevent Ajax call when datechanged too is same as state. */
		if( yyyymmdd != this.state.date ) {					
			var inputDate = {};
				inputDate['GivenDate'] = yyyymmdd;				
			$.post(''+BASE_URL+'/index.php/TheSchedulePage/reloadTheScheduleArraybyGivenDate', inputDate, function(data)
			{				
				TheScheduleArray  = JSON.parse( data );
				
				this.setState({    	date: yyyymmdd, displayDate: display, ScheduleArray: TheScheduleArray  });						
			}.bind(this));						
		}		
	},
	ShowShiftModal: function(  shiftID, hours, firstname, lastname, ShiftStart, ShiftEnd, position ) {		
			var shiftInfo = [shiftID, hours, firstname, lastname, ShiftStart, ShiftEnd, position] ;
			
			this.setState({ ShiftInfo: shiftInfo, ModalVisable: 1});
		},
	closeModal: function() { this.setState({ ShiftInfo: null, ModalVisable: 0}); },
	render: function() { 
	
		var shiftmodal;		
		if( this.state.ModalVisable == 1 && this.state.ShiftInfo != null) { 
			shiftmodal = <Displayshifmodal   close = {this.closeModal} shiftInfo = {this.state.ShiftInfo} ModalVisable = {this.state.ModalVisable} /> ;
		} else 
		{ shiftmodal = <div></div>; }
	
		return ( 
			<div>	
				{shiftmodal}			
				<Controls date = {this.state.date} displayDate = {this.state.displayDate}  dateChange = {this.dateChange} />
				<TheScheduleDisplay ShowModal = {this.ShowShiftModal} TheScheduleArray = {this.state.ScheduleArray}  />
			</div> 
		);
	},
});/// End of Scheduleapp.

ReactDOM.render ( <Scheduleapp />, document.getElementById("ScheduleApp") );
};