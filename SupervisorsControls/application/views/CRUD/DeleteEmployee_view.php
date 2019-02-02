<link href = "<?= assetUrl(); ?>css/DeleteEmployee_Styles.css" rel="stylesheet" type="text/css"/> 
<a href ="<?= base_url(); ?>index.php?/SupervisorControllers/TheEditPage"> <div id = "EditButton"> <p> Supervisor page <p> </div></a>


<script type = "text/javascript">	
	$(document).ready(function() {	
		
		var EmployeeList; //The global employee list.					
		ReloadTableEmployeelist();
					
		$('#UpdateEmployeeTableinner').on('mouseenter', '.UpdateEmployeeCell', function() {																			
				$( this ).removeClass("notHoverTable");
				$( this ).addClass("liOVERTable");	
			}).on('mouseleave', '.UpdateEmployeeCell', function() {
				$( this ).removeClass("liOVERTable");
				$( this ).addClass("notHoverTable");				
			});							
		
	

	
		/** function GetEmployeelist
			purpose: gets employee list threw an ajaxs call.		
		*/
		function ReloadTableEmployeelist() {
			var employeelist;
			var inputarr =[];
			$.post('<?= base_Url(); ?>index.php/SupervisorControllers/UpdateEmployee/loadList', inputarr, function(data)
			{					
				var employees = JSON.parse( data);
				loadTable( employees);								
			});
		}/*End of function ReloadTableEmployeelist*/
		
	
	
			
		/** function loadTable
			purpose: loads table with all employee information in employeelist
			
			parameter: employeelist	 the array list of employee information. 
		*/
		function loadTable(	 employeelist	) {
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
				
				
				MSG += "<div class = \"UpdateEmployeeCell notHoverTable\">";
					MSG += "<div class =\"hidden\"> "+id+"   </div>";
					MSG += "<div class =\"upEmNameCell\"> "+firstname+"   </div>";
					MSG += "<div class =\"upEmNameCell\"> "+lastname+"  </div>";
					MSG += "<div class =\"upEmUserNameCell\"> "+username+"   </div>";

					MSG += "<div class =\"upEmCertCell\"> "+lifeguard+"  </div>";
					MSG += "<div class =\"upEmCertCell\"> "+instructor+"   </div>";
					MSG += "<div class =\"upEmCertCell\"> "+headguard+" 	 </div>";
					MSG += "<div class =\"upEmCertlastCell\"> "+supervisor+"   </div>";	
				MSG += "</div>";
			}//End of for each employee in employeelist
					
			//Put the rows into tbody of the table.			
			$('#UpdateEmployeeTableinner').empty();
			$('#UpdateEmployeeTableinner').append( MSG );
			
		}/* End of Function loadTable */
		
		

			
		
		/* When a Employee row gets clicked gets clicked */						
		$('#UpdateEmployeeTableinner').on('click', '.UpdateEmployeeCell', function(){							
			/// Remove the mouse hover classes and add the choose background blue class.
		
			$( this ).removeClass("notHoverTable");
			$( this ).removeClass("liOVERTable");
			$(".liSelectedEmployeeRow" ).removeClass("liSelectedEmployeeRow");//Also remove any previously chosen employee rows.
			$( this ).addClass("liSelectedEmployeeRow");
		
		 
			var ID = $(this).find(".hidden:first").text(); //Get the ID of this row.
			var idnum = parseInt(ID);								
			loadForm(	idnum	);//load form with the employee with this ID.				
		});/*End of on click of employee row.*/
		
		
		
			
		//When the deleted button gets clicked... run the deleted procedures.
		$('#AreYouSureEmployeeDelete').on('click', '#btn_delete', function(){				
			runDelete();
		});		
		
				
		//When the Cancel button gets clicked... run the cancel procedures.
		$('#AreYouSureEmployeeDelete').on('click', '#btn_cancel', function(){				
			Cancel();
		});		
		
		
		
			
		/** function loadForm
			purpose: loads form with ID
		
			parameters: ID	
		*/
		function loadForm( ID ) {								
			var firstname = "";
			var lastname = "";			
			var username = "";
			var password = "";
			var lifeguard = "";
			var instructor = "";
			var headguard = "";
			var supervisor = "";			
			for( var i = 0; i < EmployeeList.length ; i++)
			{				
				if ( EmployeeList[i]["employeeID"] == ID  ) 
				{				
					firstname = EmployeeList[i]["Firstname"];
					lastname = EmployeeList[i]["Lastname"];
					username = EmployeeList[i]["Username"];
					password = EmployeeList[i]["Password"];
					lifeguard = EmployeeList[i]["Lifeguard"];
					instructor = EmployeeList[i]["Instructor"];
					headguard = EmployeeList[i]["Headguard"];
					supervisor = EmployeeList[i]["Supervisor"];										
				}								
			}			
			$('#EmployeeID').val( 	ID );
			$('#Firstname').val( 	firstname );
			$('#Lastname').val( 	lastname );
			$('#password').val(		password );			
			if( lifeguard == true )
			{	$('#Lifeguard').prop('checked',true);			}
			else
			{	$('#Lifeguard').prop('checked', false);			}
			
			if( instructor == true )
			{	$('#Instructor').prop('checked',true);			}
			else
			{	$('#Instructor').prop('checked', false);		}
			
			if( headguard == true )
			{	$('#Headguard').prop('checked', true);			}
			else
			{	$('#Headguard').prop('checked', false);			}	
			
			if( supervisor == true)
			{	$('#Supervisor').prop('checked', true);			}
			else
			{	$('#Supervisor').prop('checked', false);		}
		
		
			// Load shifts.
			var inputarr = {};
			inputarr['eID'] = ID;		
			console.log(inputarr);
			$.post('<?= base_Url(); ?>index.php/SupervisorControllers/DeleteEmployee/loadShifts', inputarr, function(data) {					
				var shifts = JSON.parse(data);				
				var packageshift = "";
				for(var i in shifts ) {
					var positon = ConvertNumberPosition(shifts[i]["Position"]);
					var date =shifts[i]["date"];
					var st =shifts[i]["startTime"];
					var et =shifts[i]["endTime"];									
					packageshift += 	"<div class =\"DeleteShift "+positon+"Shift\">";
					packageshift += 	"<p>";
					packageshift += 	"Position: "+positon+"<br>";
					packageshift += 		"Date: "+date+"<br>";
					packageshift += 	"StartTime: "+st+"<br>";
					packageshift += 		"EndTime: "+et+"<br>";
					packageshift += 	"</p>";
					packageshift += 	"</div>";
				}
				$("#WhiteInner").empty().append(packageshift)																								
				//Display AreYouSureEmployeeDelete
				var msg = "";
				msg +="<p>";
				msg +=	"When you delete an employee their shifts, subslips, and chat posts, will also be deleted.";
				msg +=	"Their future shifts are listed on the left. It might be a good idea to write those shifts";
				msg +=	"down, because they will be deleted.";
				msg +=	"<br><br>";
				msg +=	"Are you sure you wish to delete this employee?";
				msg +=	"</p>";
				msg +=	"<button id = \"btn_delete\">Delete</button>	"		
				msg +="<button id = \"btn_cancel\">Cancel</button>";
				$("#AreYouSureEmployeeDelete").empty().append(msg)			
			});				
		
		}//End of function loadForm			
		
		
		function Cancel() {
			$('#WhiteInner').empty();
			$('#AreYouSureEmployeeDelete').empty().append("<p>No employee selected.</p>");
			$(".liSelectedEmployeeRow" ).removeClass("liSelectedEmployeeRow");//Also remove any previously chosen employee rows.
			$('#EmployeeID').val('');
			$('#Firstname').val('');
			$('#Lastname').val('');
			$('#password').val('');			
			$('#Lifeguard').prop('checked', false);						
			$('#Instructor').prop('checked', false);		
			$('#Headguard').prop('checked', false);			
			$('#Supervisor').prop('checked', false);					
		}
		
		
		/* function runDelete
				When an employee has been selected to be deleted.
		*/
		function runDelete() {	
			if( confirm("Are your sure you want to delete this employe?!") ) {
				var id = $('#EmployeeID').val();
				var inputarr = {};
				inputarr['eID'] = id;	
				Cancel();//Clear form anyway... 				
				$.post('<?= base_Url(); ?>index.php?/SupervisorControllers/DeleteEmployee/DeleteThisEmployee', inputarr, function(data)
				{							
					if(data == 1 ) {
						alert("Employee was deleted!");
						
					} else {
						alert("There was an errro...   ¯\_(ツ)_/¯ ");						
					}										
					ReloadTableEmployeelist();
			
				});
			}
		}				
		
		
	});
</script>
<div id= "DeleteEmployeepage" >
	<h1>Delete an Employee</h1>
	<h2>Click on an employee to Delete</h2>
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
	<form name = "DeleteForm" onsubmit = "return ValidateEmployeeForm();" method = "post" accept-charset = "utf-8" id = "DeleteForm">				
		<h1> Selected Employee</h1>								
		<div class ="OneEm">
		<label for = "EmployeeID"  class="">ID: </label>
		<input  type = "text" maxlength="10"  size = "3" name = "EmployeeID" id = "EmployeeID" value = ""  readonly disabled />
		</div>			
		<div class ="OneEm">
		<label for = "Firstname"  class="">Firstname: </label>
		<input type = "text" name = "Firstname" id = "Firstname" value = ""  maxlength="21" disabled  />					
		</div>			
		<div class ="OneEm">
		<label for = "Lastname"  class="">Lastname: </label>
		<input type = "text" name = "Lastname" id = "Lastname"   value = "" maxlength="21" disabled 	/> 					
		</div>				
		<div class = "VerticalCertContainer">
		<div class ="OneEm"><div  class="LifeguardShift certSelectorsRadioOrCheckBoxk">		<input type = "checkbox"  name = "Lifeguard"  id = "Lifeguard"	disabled /> Lifeguard </div></div>												
		<div class ="OneEm"><div  class ="InstructorShift certSelectorsRadioOrCheckBoxk"> <input type = "checkbox"  name = "Instructor"  id = "Instructor" disabled />Instructor </div></div>														
		<div class ="OneEm"><div  class ="HeadGuardShift certSelectorsRadioOrCheckBoxk">	<input type = "checkbox" name = "Headguard" 	id = "Headguard" disabled />Headguard  </div></div>																		
		<div class ="OneEm"><div  class ="SupervisorShift certSelectorsRadioOrCheckBoxk"> <input type = "checkbox" name = "Supervisor" id = "Supervisor"  disabled	/>Supervisor </div></div>																											
		</div>
	</form>	
	<div id = "ShiftsThisEmployeeHas">
		<h3>This employee's future shifts</h3>
		<div id = "WhiteInner">
		</div>
	</div>			
	<div id = "AreYouSureEmployeeDelete">			
		<p>No employee selected.</p>
	</div>
</div>