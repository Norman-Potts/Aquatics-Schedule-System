<link href = "<?= assetUrl(); ?>css/ChangeAnAvailability.css" rel="stylesheet" type="text/css"/> 
<script type = "text/javascript">

	$(document).ready(function() {		

		ReloadTableEmployeelist(); // Load the table with all employees in Database.
		var EmployeeList;
				
		$('#UpdateEmployeeTableinner').on('mouseenter', '.UpdateEmployeeCell', function(){																			
				$( this ).removeClass("notHoverTable");
				$( this ).addClass("liOVERTable");	
			}).on('mouseleave', '.UpdateEmployeeCell', function() {
				$( this ).removeClass("liOVERTable");
				$( this ).addClass("notHoverTable");				
			});										
		

			/// When an employee row gets clicked.
			$('#UpdateEmployeeTableinner').on('click', '.UpdateEmployeeCell', function(){															
				/// Remove the mouse hover classes and add the choose background blue class.
				$( this ).removeClass("notHoverTable");
				$( this ).removeClass("liOVERTable");
				$(".liSelectedEmployeeRow" ).removeClass("liSelectedEmployeeRow");//Also remove any previously chosen employee rows.
				$( this ).addClass("liSelectedEmployeeRow");
			
				var ID = $(this).find(".upEmCell:first").text();
				var Firstname = $(this).find(".upEmCell").eq(1).text(); 
				var Lastname = $(this).find(".upEmCell").eq(2).text();
				
				var idnum = parseInt(ID);								
				$('#btn_saveAvailability').removeClass("btn_UpdateEmployeeNotActive");
				$('#btn_saveAvailability').addClass("btn_UpdateEmployeeColorActive");
				loadForm(	idnum, Firstname, Lastname	);
			});
			///End of when an employee row gets clicked.

			
			var formloaded = false;						
			$('#btn_saveAvailability').addClass("btn_UpdateEmployeeNotActive");
			
			/// when the save button gets clicked.
			$('#btn_saveAvailability').click(function(){
				if(formloaded)
				{
					saveAvailability();
					clear();// After saveAvailability function run clear the form.
				}			
			});		
			/// End of when the save button gets clicked.
				
			
		/** function GetEmployeelist
			purpose: gets employee list threw an ajaxs call.		
		*/
		function ReloadTableEmployeelist()
		{
			var employeelist;
			var inputarr =[];
			$.post('<?= base_Url(); ?>index.php/SupervisorControllers/UpdateEmployee/loadList', inputarr, function(data)
			{					
				var employees = JSON.parse(data);
				loadTable(employees);								
			});
		}/*End of function ReloadTableEmployeelist*/
		
		

		/** function loadTable
			purpose: loads table with all employee information in employeelist
			
			parameter: employeelist	 the array list of employee information. 
		*/
		function loadTable(	 employeelist	)
		{
			var MSG = "";
			EmployeeList = employeelist;
									
			for( var emp in  employeelist 	)
			{
				var id = employeelist[emp]["employeeID"];
				var firstname = employeelist[emp]["Firstname"];
				var lastname = employeelist[emp]["Lastname"];
				var username = employeelist[emp]["Username"];
				var Password = employeelist[emp]["Password"];
				var lifeguard =	"";
					if (  employeelist[emp]["Lifeguard"] == true ){ lifeguard = "Yes"; }else{ lifeguard = "no"; }
				var instructor = "";
					if (  employeelist[emp]["Instructor"] == true ){ instructor = "Yes"; }else{ instructor = "no"; }
				var headguard = "";		 
					if (  employeelist[emp]["Headguard"] == true ){ headguard = "Yes"; }else{ headguard = "no"; }
				var supervisor = "";		 
					if (  employeelist[emp]["Supervisor"] == true ){ supervisor = "Yes"; }else{ supervisor = "no"; }
					
					MSG += "<div class = \"UpdateEmployeeCell  Bold\">";
					MSG += "<div class =\"hidden\"> "+id+"   </div>";
					MSG += "<div class =\"upEmNameCell\"> "+firstname+"   </div>";
					MSG += "<div class =\"upEmNameCell\"> "+lastname+"    </div>";
					MSG += "<div class =\"upEmUserNameCell\"> "+username+"    </div>";
					MSG += "<div class =\"upEmCertCell\"> "+lifeguard+"   </div>";
					MSG += "<div class =\"upEmCertCell\"> "+instructor+"  </div>";
					MSG += "<div class =\"upEmCertCell\"> "+headguard+"   </div>";
					MSG += "<div class =\"upEmCertlastCell\"> "+supervisor+"  </div>";	
					MSG += "</div>";
			}//End of for each employee in employeelist
					
			//Put the rows into tbody of the table.			
			$('#UpdateEmployeeTableinner').empty();
			$('#UpdateEmployeeTableinner').append( MSG );
			
		}/* End of Function loadTable */
		

		// textarea variables, now global.
		var txt_Mondays = $("#Av_Mondays textarea");
		var txt_Tuesdays = $("#Av_Tuesdays textarea");
		var txt_Wednesdays = $("#Av_Wednesdays textarea");
		var txt_Thrusdays = $("#Av_Thrusdays textarea");
		var txt_Fridays	= $("#Av_Fridays textarea");
		var txt_Saturdays = $("#Av_Saturdays textarea");
		var txt_Sundays = $("#Av_Sundays textarea");
		var txt_General = $("#Av_General textarea");
		var eIDofForm;

		/* function loadForm
				loads the availability form with this employee;s availability.
		*/
		function loadForm( EID, Firstname, Lastname )
		{
			
			var inputarr= {};
			inputarr['ID'] = EID;															
			$.post('<?= base_Url(); ?>index.php/SupervisorControllers/EmployeeAvailability/GetAvailability', inputarr, function(data)
			{	
				  
					
				$("#SelectedEmployeeName").empty().append(""+Firstname+" "+Lastname+"");				
				
					var Availability = JSON.parse( data );		
					/// Determine if the Availability has been set. Then fill the form.
				
					if( Availability == "Nothing yet" || Availability == null)
					{ /// Availability has not been set...
						txt_Mondays.val("Not set yet.");
            txt_Tuesdays.val("Not set yet."); 
            txt_Wednesdays.val("Not set yet.");
            txt_Thrusdays.val("Not set yet.");
            txt_Fridays.val("Not set yet.");
            txt_Saturdays.val("Not set yet.");
            txt_Sundays.val("Not set yet.");
            txt_General.val("Not set yet.");
				
						
					}
					else
					{ /// Availability has been set...
						var current_Mondays = Availability["Mondays"];
				    var current_Tuesdays = Availability["Tuesdays"];
				    var current_Wednesdays = Availability["Wednesdays"];
				    var current_Thrusdays = Availability["Thrusdays"];
						var current_Fridays	= Availability["Fridays"];
						var current_Saturdays = Availability["Saturdays"];
				    var current_Sundays = Availability["Sundays"];
						var current_General = Availability["GeneralNotes"];					
						
						txt_Mondays.val(    current_Mondays );
            txt_Tuesdays.val(   current_Tuesdays ); 
            txt_Wednesdays.val( current_Wednesdays );
            txt_Thrusdays.val(  current_Thrusdays );
            txt_Fridays.val(    current_Fridays );
            txt_Saturdays.val(  current_Saturdays );
            txt_Sundays.val(    current_Sundays );
            txt_General.val(    current_General );
				
						
						
					}
					
				eIDofForm = EID;
				formloaded = true;
			});					 		
		}// End of Function loadForm
		
		
		function saveAvailability()
		{
			var set_Mondays = txt_Mondays.val();
			var set_Tuesdays = txt_Tuesdays.val(); 
			var set_Wednesdays = txt_Wednesdays.val();
			var set_Thrusdays = txt_Thrusdays.val();
			var set_Fridays	= txt_Fridays.val();
			var set_Saturdays = txt_Saturdays.val();
			var set_Sundays = txt_Sundays.val();
			var set_General = txt_General.val();
			
			var Availability = {};
					Availability["Mondays"]      = set_Mondays;
					Availability["Tuesdays"]     = set_Tuesdays;
					Availability["Wednesdays"]   = set_Wednesdays;
					Availability["Thrusdays"]    = set_Thrusdays;
					Availability["Fridays"]      = set_Fridays;
					Availability["Saturdays"] 	 = set_Saturdays;
					Availability["Sundays"] 		 = set_Sundays;
					Availability["GeneralNotes"] = set_General;
			
			var jAvailability = JSON.stringify( Availability );														
			var inputarr = {};			
			inputarr['ID'] = eIDofForm;
			inputarr['AvailabilityStr'] = jAvailability; 		
			
			$.post('<?= base_Url(); ?>index.php/SupervisorControllers/EmployeeAvailability/SetAvailability', inputarr, function(data)
			{	
				if( data == 1 )
				{
					alert( "Availability update was succesful");					
					
				}else{
					alert("Availability update had an error");
				}
			});
			
		
		}/// End of function saveAvailability
		
		

		/* function clear
				clears the availability form.
		*/
		function clear()
		{
			
			txt_Mondays.val("");    
      txt_Tuesdays.val("");   
      txt_Wednesdays.val(""); 
      txt_Thrusdays.val("");  
      txt_Fridays.val("");    
      txt_Saturdays.val("");  
      txt_Sundays.val("");    
      txt_General.val("");    
			$("#SelectedEmployeeName").empty();								
			eIDofForm = null;
			formloaded = false;
			$('#btn_saveAvailability').removeClass("btn_UpdateEmployeeColorActive");
			$('#btn_saveAvailability').addClass("btn_UpdateEmployeeNotActive");
		}

		
	});//End of Jquery Document dot ready
		


</script>


<a href ="<?= base_url(); ?>index.php?/SupervisorControllers/TheEditPage"> <div id = "EditButton"> <p> Supervisor page <p> </div></a>
	<script type = "text/JavaScript" >		
		$( "#EditButton" ).addClass("EditButtonNotHover");	
		$("#EditButton").hover(
			function() {
				$( "#EditButton" ).removeClass("EditButtonNotHover");
				$( "#EditButton" ).addClass("EditButtonHover");								
			}, function() {
				$( "#EditButton" ).removeClass("EditButtonHover");
				$( "#EditButton" ).addClass("EditButtonNotHover");
			}
		);		
	</script>


<div id ="AvailabilityCRUDPage">
	<h1>Availability Manager </h1>
	<h3>Select An Employee</h3>
		<div id = "UpdateEmployeeTable" >			
				<div id ="UpdateTableHeadder" >		
			 
			  <div class = "upEmNameCell">Firstname </div>
			  <div class = "upEmNameCell">Lastname </div>
			  <div class = "upEmUserNameCell">Username </div> 
			  <div class = "upEmCertCell">Lifeguard </div>
			  <div class = "upEmCertCell">Instructor </div>
			  <div class = "upEmCertCell">Headguard </div>
			  <div class = "upEmCertlastCell">Supervisor </div>
		  
				</div>						
			<div id = "UpdateEmployeeTableinner">				
			</div>		
		</div>
		
		<div id = "AvailabilityEditorContainer">
			<h3>
				Selected Employee: <span id = "SelectedEmployeeName"></span>
			</h3>
			
			<div class ="AV_Booxes" id= "Av_Mondays">
				<h4>Monday's</h4>
				<textarea rows="6" cols="50">
				</textarea>
			</div>
			<div class ="AV_Booxes" id= "Av_Tuesdays">
				<h4>Tuesday's</h4>
				<textarea rows="6" cols="50">
				</textarea>
			</div>
			<div class ="AV_Booxes" id= "Av_Wednesdays">
				<h4>Wednesday"s</h4>
				<textarea rows="6" cols="50">
				</textarea>
			</div>
			<div class ="AV_Booxes" id= "Av_Thrusdays">
				<h4>Thrusday's</h4>
				<textarea rows="6" cols="50">
				</textarea>
			</div>
			<div class ="AV_Booxes" id= "Av_Fridays" >
				<h4>Friday's</h4>
				<textarea rows="6" cols="50">
				</textarea>
			</div>
			<div class ="AV_Booxes" id= "Av_Saturdays">
				<h4>Saturday's</h4>
				<textarea rows="6" cols="50">
				</textarea>
			</div>
			<div class ="AV_Booxes" id= "Av_Sundays" >
				<h4>Sunday's</h4>
				<textarea rows="6" cols="50">
				</textarea>
			</div>
			<div class ="AV_Booxes" id= "Av_General" >
				<h4>General Availability Notes</h4>
				<textarea rows="6" cols="50" >
				</textarea>
			</div>
		
		<p>
		* Remember the availability notes
		appears on the employee's myProfile page
		and create shift page.					
		</p>
		<button id = "btn_saveAvailability"  >Save</button>
		</div>
	
</div>
			