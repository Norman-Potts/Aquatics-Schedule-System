<!DOCTYPE HTML>
<html lang = "en-US">
<head>	
    <link rel="shortcut icon" href="<?= assetUrl(); ?>img/Schedulefavicon.ico" type="image/x-icon">			
	<!--Start User Session-->
	<?php session_start(); ?>
	<?php	/* Determine if a valid use sessions exist already. */
	    $this->Vert = $this->userauth->validSessionExists();		
		if ( $this->Vert == false) {
			$page = base_url() . "index.php?/Login";
			$this->userauth->redirect($page);
		}				
	?>	
	<title>Schedule</title> 

	<!--for jQuery -->
	<script src="https://code.jquery.com/jquery.js"> </script>		

	<!--My CSS-->	
	<link href = "<?= assetUrl(); ?>css/AppStyles.css" rel="stylesheet" type="text/css"/> 
		
	<!-- For React -->	
	<script src="https://unpkg.com/react@15.3.2/dist/react.js"></script>
	<script src="https://unpkg.com/react-dom@15.3.2/dist/react-dom.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/babel-core/5.8.23/browser.min.js"></script>				

		
</head>
<body>	
<div id = "CenterBox" >
