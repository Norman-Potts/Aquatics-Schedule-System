<!DOCTYPE HTML>
<html lang = "en-US">
<head>
	<title>Schedule</title> 
	<link href = "<?= assetUrl(); ?>css/LoginLogout_Styles.css" rel="stylesheet" type="text/css"/> 
	<link rel="shortcut icon" href="<?= assetUrl(); ?>/img/Schedulefavicon.ico" type="image/x-icon"> 
	
	
<? session_start(); ?>
</head>
<body>
	<div id= "loginParentBox" class = "TextCenter">

			<h1><b>Supervisor Controller Logout Page </b></h1>
			<br>
			<h2>You have logged out. </h2>
			<br>
			<h3>Click <a href = "<?= base_url()?>">here</a> to log back in.</h3>
			<p>or</p>
			<h3>Click <a href = "https://normanpotts.ca/WebPortfolio/Aquatics_Schedule_System/EmployeeFrontEnd/">here</a> to log in to the employee site</h3>						
		
	</div>

	<div id = "authorship">
		<p>Created by <br> Norman Potts </p>
	</div>
	
</body>
</html>