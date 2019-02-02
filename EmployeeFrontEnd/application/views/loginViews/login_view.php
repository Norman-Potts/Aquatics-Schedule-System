<!DOCTYPE HTML>
<html lang = "en-US">
<head>
	<title>Schedule</title> 
	<link rel="shortcut icon" href="<?= assetUrl(); ?>/img/Schedulefavicon.ico" type="image/x-icon"> 
	<link href = "<?= assetUrl(); ?>css/LoginLogout_Styles.css" rel="stylesheet" type="text/css"/> 

	<? session_start(); ?>	
	<script src	= "https://code.jquery.com/jquery.js" > </script>	
	<script type = "text/javascript">
		
		/** Function Validate()
				Purpose: Offer user friendly feed back for user input on login form.
		*/			
		function Validate() {					   
			var UsernameField = "";
			var passwordField = "";
			var passfail = false;			
			UsernameField += Login.Username.value;				
			passwordField += Login.password.value;
						
			/* If UsernameField and pass is empty */
			if ( UsernameField == "" && passwordField == "" )
			{
				var str = "Username and password is empty.";
				document.getElementById("RedErrorBox").innerHTML = str;
				passfail = false;
			}
			else if ( UsernameField == "" )
			{
				var str = "Username is empty.";
				document.getElementById("RedErrorBox").innerHTML = str;
				passfail = false;				
			}
			else if ( passwordField == "")
			{	
				var str = "Password is empty.";
				document.getElementById("RedErrorBox").innerHTML = str;
				passfail = false;	
						
			}
			else
			{				
				/* Test login format */   				   
				var PatternName = /^[a-zA-Z]+\.[a-zA-Z]+$/;
				var answerName = PatternName.test(UsernameField);
				if (answerName == true)
				{	passfail = true;	}
				else
				{
					var str = "That Username doesnt use correct format.<br> Use Firstname.Lastname";
					document.getElementById("RedErrorBox").innerHTML = str;
					passfail = false;					
				}
			}			
			return passfail
		}/*End of Login */
		
		
		/** Function Help
				Purpose: Displays the help message when the help button is clicked.
		*/
		function Help()
		{		
			var modal = $('#HelpModal');	
			var close = $('#close');
			modal.css({'display':"block"}); 									
			close.click(function(){
				modal.css({'display': "none"});
			});
		}
		
	</script>
				
</head>
<body>
	<div id = "loginParentBox">		


		<div class = "TextCenter">
			<h1>Aquatic Employee Schedule Website </h1>							
			<h2> Please login below</h2>
		 
						<form id="myLoginForm" action="<?= base_Url(); ?>index.php/Login/loginuser" onsubmit="return Validate();" method="post" name="Login" accept-charset= "utf-8"  >						
						Username: <input type="text" name="Username" value = "<?php echo set_value('Username', ''); ?>" maxlength="42"  autofocus> <!--Changed from  21 to 42. Should check for bugss -->
							<br><br>
						Password:	<input type="password" name ="password" value = "<?php echo set_value('password', ''); ?>" maxlength="20" />
							<br><br>						
							<input type = "submit" value = "Login" id = "loginbutton" />
						</form>
									
						<div id = "RedErrorBox"> 
							<?= $msg ?>
						</div>
		</div>				
			<button onclick = "Help();" id = "help">
				<p>Help!</p>
			</button>																							
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

	
	<a href = "<?= base_url()?>index.php/Documentation">
		<div id = "authorship">		
			<p class = "bold"> Created by Norman Potts Click Here First! </p>
		</div>
	</a>
	
	
</body>
</html>