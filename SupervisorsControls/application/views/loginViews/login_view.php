<!DOCTYPE HTML>
<html lang = "en-US">
<head>
	<title>Schedule</title> 
	<link rel="shortcut icon" href="<?= assetUrl(); ?>img/Schedulefavicon.ico" type="image/x-icon"> 
	<link href = "<?= assetUrl(); ?>css/LoginLogout_Styles.css" rel="stylesheet" type="text/css"/> 

	
	<script src	= "https://code.jquery.com/jquery.js" > </script>	
	<script type = "text/javascript">
				
	</script>
				
</head>
<body>
	<div id = "loginParentBox">		
		<div class = "TextCenter">
			<h1>The Supervisors login </h1>							
			<h2> Please login below</h2>
		 
						<form id="myLoginForm" action="<?= base_Url(); ?>index.php/Login/loginuser" onsubmit="return Validate();" method="post" name="Login" accept-charset= "utf-8"  >						
						
								Username: <input type="text" name="Username" value = "" maxlength="42"  autofocus> 
									<br><br>
								Password:	<input type="password" name ="password" value = "" maxlength="20" />
									<br><br>						
									<input type = "submit" value = "Login" id = "loginbutton" />
						</form> 
									
						<h3>Click <a href = "https://normanpotts.ca/WebPortfolio/Aquatics_Schedule_System/EmployeeFrontEnd/">here</a> to log back in to the employee site</h3>			
								
						<div id = "RedErrorBox"> 							
						</div>
		</div>				 
	</div>


	
	<div id = "HelpModal" class= "modal">
		<div id ="HelpModalContent">
			<div id="close">&times;</div>
			<div class="loginhelpmessage">
				<h1>How to login</h1>
				<p class="bold" >Step 1:</p> <p>Confirm with your supervisor that you are using the correct Username and Password. </p>
				<p class="bold" >Step 2:</p> <p>Enter your Username using the correct format  "<span class = "bold">Firstname.Lastname</span>". Make sure you put the period in the middle. </p>
				<p class="bold" >Step 3:</p> <p>Enter your password for your account. </p>
				<p class="bold" >Step 4:</p> <p>Click on the green login button. </p>
				<p class="TextCenter bold">If you are still having trouble, ask Norman for help.</p>
			</div>
		</div>
	</div>			

	

		<div id = "authorship">		
			<p class = "bold"> Created by Norman Potts </p>
		</div>
	
	
</body>
</html>