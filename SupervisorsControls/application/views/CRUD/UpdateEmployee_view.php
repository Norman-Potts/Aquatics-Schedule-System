<link href = "<?= assetUrl(); ?>css/UpdateEmployee_Styles.css" rel="stylesheet" type="text/css"/> 
<script type = "text/javascript">
	$(document).ready(function() {					

		
		var firstList = JSON.parse( '<?= $initalList ?>');
		
		var EmployeeList;	
		var sortType = 1;
		loadTable( firstList, sortType );
		var selectedEmployee = null; 
		
		
		
		$('#UpdateEmployeeTableinner').on('click', '.UpdateEmployeeCell', function(){	/* When a Employee row gets clicked gets clicked */																	 
			$(".liSelectedEmployeeRow" ).removeClass("liSelectedEmployeeRow");  // Also remove any previously chosen employee rows.			
			var idstr = $(this).find(".hidden:first").text(); // Get the ID of this row.
			var ID = parseInt(idstr);			
			if( ID == selectedEmployee) 
			{	//clear form and disable. 
			
				clearFormAndDisable();				
			}	
			else
			{	selectedEmployee = ID; 		
				$( this ).addClass("liSelectedEmployeeRow");					
				loadForm(	ID	); 	
			} 
			
		});/* When a employee gets selected from the table load the form using the ID*/
			

			
		$('#btn_UpdateEmployee').click(function(){   
			finalvalidateForm();   
		});/* When Update button gets clicked */	
  
  
		
			
		$('#Sort_LastName').click(function(){  
			if(sortType == 3)
			{ sortType = 4; }
			else 
			{ sortType = 3; }		
			loadTable( EmployeeList, sortType );	 
		});/* When Sort_LastName button gets clicked */	
  
		$('#Sort_FirstName').click(function(){  			
			if(sortType == 1)
			{ sortType = 2; }
			else 
			{ sortType = 1; }					
			loadTable( EmployeeList, sortType );	 
		});/* When Sort_FirstName button gets clicked */	
  
  
		$('#Firstname').change(function(){
			var str = UpdateForm.Firstname.value;
			if(selectedEmployee != null) 
			{
				if (str.match(/^([A-Z]{1}[a-z]+)$/)) {
					$('#FirstnameFeedBack').empty();
				}
				else
				{
					$('#FirstnameFeedBack').empty().append("Inncorrect format.");
				}
			
			}
		});
		
		
		$('#Lastname').change(function(){
			if(selectedEmployee != null) 
			{
					
					
			}	
		});
  
  
  
  
  

		function ReloadTableEmployeelist() {
			var employeelist;  var inputarr = [];
			$.post('<?= base_Url(); ?>index.php/SupervisorControllers/UpdateEmployee/loadList', inputarr, function(data) {					
				var employees = JSON.parse( data);				
				loadTable( employees, sortType );								
			});
		}/* End of function ReloadTableEmployeelist */
 
 
 
 
		function loadTable(	 employeelist, typeSort	) {
			var MSG = "";  		
			switch(typeSort) {
				case 1:				
					employeelist.sort((a, b) =>  a.Firstname.localeCompare(b.Firstname));// sort by firstname. 
				break;
				case 2:					
					employeelist.sort((b, a) =>  a.Firstname.localeCompare(b.Firstname));// sort by firstname reverse.				 					
				break;
				case 3:									
					employeelist.sort((a, b) =>  a.Lastname.localeCompare(b.Lastname)); // sort by lastname.									 
				break;
				case 4:				
				 	employeelist.sort((b, a) =>  a.Lastname.localeCompare(b.Lastname)); // sort by lastname reverse.				 
				break;				
				default:							
					employeelist.sort((a, b) =>  a.Firstname.localeCompare(b.Firstname)); // sort by firstname.									
			}

			EmployeeList = employeelist; // Update global variable.
			for( var emp in  employeelist 	) {
				var id = employeelist[emp]["employeeID"];  var firstname = employeelist[emp]["Firstname"];  var lastname = employeelist[emp]["Lastname"];
				var username = employeelist[emp]["Username"];  var Password = employeelist[emp]["Password"];
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
			$('#UpdateEmployeeTableinner').empty().append( MSG );	
		}/// End Func
				
		

		
		
		
		function clearFormAndDisable() {
			/// reset the form... 
			selectedEmployee = null; 
			$('#Firstname').val( "" ); $('#Lastname').val( "" ); $('#password').val( "" );	
			$('#EmployeeID').val("");			 
			$('#Lifeguard').prop('disabled',true);
            $('#Instructor').prop('disabled',true);
			$('#Headguard').prop('disabled', true);
			$('#Supervisor').prop('disabled', true);			
			$('#Firstname').prop('disabled',true);
            $('#Lastname').prop('disabled', true);
			$('#password').prop('disabled', true);			
			$('#Lifeguard').prop('checked', false);	
			$('#Instructor').prop('checked', false);
			$('#Headguard').prop('checked', false);	
			$('#Supervisor').prop('checked', false);		   
			$('#btn_UpdateEmployee').prop('disabled', true);
		}/// End of function.
		
		
		
		function loadForm( ID ) {								
			var firstname = "";  var lastname = "";	 var username = "";  var password = "";  var lifeguard = "";  var instructor = "";  var headguard = "";  var supervisor = "";			
			for( var i = 0; i < EmployeeList.length ; i++) {				
				if ( EmployeeList[i]["employeeID"] == ID  )  {				
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
			$('#EmployeeID').val( ID ); $('#Firstname').val( firstname ); $('#Lastname').val( lastname ); $('#password').val( password );			
			if( lifeguard == true ){ $('#Lifeguard').prop('checked',true);}else{$('#Lifeguard').prop('checked', false);}			
			if( instructor == true ){$('#Instructor').prop('checked',true);}else{$('#Instructor').prop('checked', false);}
			if( headguard == true ){ $('#Headguard').prop('checked', true);}else{$('#Headguard').prop('checked', false);}				
			if( supervisor == true ){$('#Supervisor').prop('checked', true);}else{$('#Supervisor').prop('checked', false);}	

			/// Enable form... 
			$('#Lifeguard').prop('disabled',false);
            $('#Instructor').prop('disabled',false);
			$('#Headguard').prop('disabled', false);
			$('#Supervisor').prop('disabled', false);			
			$('#Firstname').prop('disabled',false);
            $('#Lastname').prop('disabled', false);
			$('#password').prop('disabled', false);			
			$('#btn_UpdateEmployee').prop('disabled', false);
									
		}//End of function loadForm			
		
		
		
		
		
		
		
		
		
		/// ValidateForm
		function finalvalidateForm() {			
		 
		 
			 
			var ID = UpdateForm.EmployeeID.value; 
			var FirstnameField = ""; var LastnameField = ""; var passwordField = ""; 	
			FirstnameField += UpdateForm.Firstname.value; 
			LastnameField += UpdateForm.Lastname.value;
			passwordField += UpdateForm.password.value; 
			
			var LifeguardField = false;
			var InstructorField = false;
			var HeadguardField = false; 
			var SupervisorField = false; 	
		 
			 
			
			LifeguardField = UpdateForm.Lifeguard.checked;
			InstructorField = UpdateForm.Instructor.checked;
			HeadguardField = UpdateForm.Headguard.checked; 
			SupervisorField = UpdateForm.Supervisor.checked; 
 
 
			var lifeguard =	"";
				if (LifeguardField == true  ){ lifeguard = "Yes"; }else{ lifeguard = "no"; }
			var instructor = "";
				if (InstructorField == true ){ instructor = "Yes"; }else{ instructor = "no"; }
			var headguard = "";		 
				if (HeadguardField == true  ){ headguard = "Yes"; }else{ headguard = "no"; }
			var supervisor = "";		 
				if (SupervisorField == true ){ supervisor = "Yes"; }else{ supervisor = "no"; }
			var MsgConfirm = " Are you sure you wish to Update this user acount?  \n ";
				MsgConfirm += "Firstname: "+FirstnameField+"\n Lastname: "+LastnameField+"\n"; 
				MsgConfirm += "Password: "+passwordField+" \n Certifcations \n";
				MsgConfirm += "Lifeguard: "+lifeguard+" \n";
				MsgConfirm += "Instructor: "+instructor+"\n";      
				MsgConfirm += "Headguard: "+headguard+"\n";   
				MsgConfirm += "Supervisor: "+supervisor+"" ;
			if ( confirm(MsgConfirm ) == true) 	
			{ 
				sendForm(FirstnameField, LastnameField, passwordField, LifeguardField, InstructorField, HeadguardField, SupervisorField, ID ); 
			}							
					
		
			
		}//End of function finalvalidateForm

		
		
		
		
		
		
		
		
		





		
		/// Send form and handle response.
		function sendForm(FirstnameField, LastnameField, PasswordField, LifeguardField, InstructorField, HeadguardField, SupervisorField, ID ) {			 
			$("#UpdateReturnError p").empty();			                                                                                                                    /*******  Here *******/
			var inputarr = {};
			inputarr['ID'] = ID; inputarr['firstname'] = FirstnameField; inputarr['lastname'] = LastnameField; inputarr['password'] = PasswordField;
			inputarr['lifeguard'] = LifeguardField; inputarr['instructor'] = InstructorField; inputarr['headguard'] = HeadguardField; inputarr['supervisor'] = SupervisorField;			
			$.post('<?= base_Url(); ?>index.php/SupervisorControllers/UpdateEmployee/UpdateEmployee', inputarr, function(data) {	
				UpdateReturnArr = JSON.parse(data);	
				var Instructions = UpdateReturnArr['Instructions'];						
				switch (Instructions) {
					case 4:					
						alert("Update was succesful.");
						var username = ""+FirstnameField+"."+LastnameField+"";
						var feedback = "Update was succesful with employeeID: "+ID+" Username:"+username+"<br>";
						ReloadTableEmployeelist();
						
						clearFormAndDisable();
						break;
					case 1:
						alert("The data was not acceptable."); 
						var feedback = "The data was not acceptable.";
						
						break;
					case 2:
						var Reasons = UpdateReturnArr['Reasons'];
						var buisnesslogicMessage = "";
						for( var reason in Reasons )
						switch(buisnesslogic) {	
							case 1: buisnesslogicMessage += "Same name in database<br>"; break;							
							case 2: buisnesslogicMessage += "Losing lifeguard certification for but has a future lifeguard shift<br>"; break;
							case 3: buisnesslogicMessage += "Losing instructor certification for but has a future instructor shift<br>"; break;
							case 4: buisnesslogicMessage += "Losing headguard certification for but has a future headguard shift<br>"; break;
							case 5: buisnesslogicMessage += "Losing supervisor certification for but has a future supervisor shift<br>"; break;							
							case 6: buisnesslogicMessage += "extra business logic reason.<br>"; break;						
						} 
						
						break;					
					case 3:						
						alert("There was a computer error when we tried to update.");
						var feedback = "There was a computer error when we tried to update.";

						break;
					default:						
						alert("There was a computer error when we tried to update.");
						var feedback = "There was a computer error when we tried to update.";

				}/// End of switch 
			}); 		
		}/// End of func sendform.
		
		
		
		
		
		
	});/// End of Jquery Document dot ready

</script>
<a href ="<?= base_url(); ?>index.php?/SupervisorControllers/TheEditPage"> <div id = "EditButton"> <p> Supervisor page <p> </div></a>
<div id = "UpdateBox" >
		<h1> Update an Employee	</h1>					
		<div id = "UpdateEmployeeTable" >			
		  <div id ="UpdateTableHeadder" >					 		  
			  <div class = "upEmNameCell">Firstname <button id = "Sort_FirstName" > &#8645 </button></div>
			  <div class = "upEmNameCell">Lastname <button id = "Sort_LastName"> &#8645 </button></div>
			  <div class = "upEmUserNameCell">Username </div> 
			  <div class = "upEmCertCell">Lifeguard </div>
			  <div class = "upEmCertCell">Instructor </div>
			  <div class = "upEmCertCell">Headguard </div>
			  <div class = "upEmCertlastCell">Supervisor </div>		  
		  </div>					
		  <div id = "UpdateEmployeeTableinner"  >				
		
		
		  </div>				  
		</div>
	<form name = "UpdateForm" onsubmit = "return ValidateEmployeeForm();" method = "post" accept-charset = "utf-8" id = "UpdateForm">				
		<h1> Selected Employee</h1>
		<div class = "UpperHalf Bold">
			<div id = "UpdateEmployeeDataHalf">										
				<div class ="OneEm hidden">
				  <label for = "EmployeeID"  class="">ID: </label>
				  <input  type = "text" maxlength="10"  size = "3" name = "EmployeeID" id = "EmployeeID" value = ""  readonly />
				</div>					
				<div class ="OneEm">
					<p>
					 Firstname must start with a capital and be followed by lowercase letters. Example "Norman".
					</p>
				  <label for = "Firstname"  class="">Firstname: </label>
				  <input type = "text" name = "Firstname" id = "Firstname" value = ""  maxlength="21" disabled />		
					<div class = "UpdateErrorFeedbackBlock" id = "FirstnameFeedBack" >					
				
					</div>
				</div>
				<div class ="OneEm">
					<p>
						Lastname must start with a capital and be followed by lowercase letters. Example "Potts".
					</p>
				  <label for = "Lastname"  class="">Lastname: </label>
				  <input type = "text" name = "Lastname" id = "Lastname"   value = "" maxlength="21"	disabled /> 					
					<div class = "UpdateErrorFeedbackBlock" id = "LstnameFeedBack">
					
					</div>
					
				</div>					
				<div class ="OneEm">
					<p>
					 Password Rules.<br />
					 Password must have atleast two capitals, two lowercase, two numbers, two of these symbols: !@#$%^&*() <br />
					 Passwords are not allowed to have any other symbols. <br />
					 Password must be atleast eight characters in length. <br />
					 Password must be less than 17 characters in length.					
					</p>
				  <label for = "password"  class="">Password: </label>
				  <input type = "text" name = "password" id = "password" value = "" maxlength="20" disabled />
				   <div class = "UpdateErrorFeedbackBlock" id = "passwordFeedBack" >
					
					</div>
				</div>					
			</div>				
			<div id = "UpdateEmployeeCertsHalf">
				<p>
					Employees can have any certification.<br />
					Employees cannot have a certification removed when they are assigned to a future shift with that certification or 
					have signed a subslip for a future shift with that certification. 
					This web app checks for these cases when you try to remove a certification. <br />
					You must delete the their future shifts for that certification and reject any future subslips.																			
				</p>
			
			  <span class ="LifeguardShift certSelectorsRadioOrCheckBoxk"> <input type = "checkbox" name = "Lifeguard"  id = "Lifeguard" disabled />Lifeguard </span>
			  <span class ="InstructorShift certSelectorsRadioOrCheckBoxk"><input type = "checkbox" name = "Instructor" id = "Instructor" disabled />Instructor</span>		
			  <span class ="HeadGuardShift certSelectorsRadioOrCheckBoxk"> <input type = "checkbox" name = "Headguard"  id = "Headguard" disabled  />Headguard </span>						
			  <span class ="SupervisorShift certSelectorsRadioOrCheckBoxk"><input type = "checkbox" name = "Supervisor" id = "Supervisor" disabled />Supervisor</span>	
				<br />
				<div class = "UpdateErrorFeedbackBlock" id = "certFeedBack">					
				</div>
			  
			</div>				
		</div>				
		<div class = "UpdateEmployeeBottomHalf">	
			<input type = "button" name = "btn_UpdateEmployee" value = "Update Employee" id = "btn_UpdateEmployee"	disabled />
		</div>				
	</form>
</div>