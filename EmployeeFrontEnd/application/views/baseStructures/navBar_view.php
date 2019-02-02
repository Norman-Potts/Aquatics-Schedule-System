<link href = "<?= assetUrl(); ?>css/NavBar_Styles.css" rel="stylesheet" type="text/css"/> 
<div id ="NavBarParent">
	<div id ="NavigationBar">      
		<a href ="<?= base_url(); ?>index.php?/Home"            class="redButtons" id= "homeButton"       > Home </a>    								
		<a href ="<?= base_url(); ?>index.php?/TheSchedulePage" class="redButtons" id = "Schedulebutton">Schedule </a>				
		<a href ="<?= base_url(); ?>index.php?/AvailableShifts" class="redButtons" id = "AvailableShiftsbutton">Available Shifts </a>
		<a href ="<?= base_url(); ?>index.php?/MakeASubSlip"    class="redButtons" id = "MakeASubSlipbutton">Make a SubSlip </a>		
		<a href ="<?= base_url(); ?>index.php?/MyProfile"       class="redButtons" id = "MyProfilebutton">
		  <p>
			<span class = "NavTitleMyProfile">Profile</span>
			<span class = "NavNameBox"><?= $_SESSION['Firstname']?> <br/>  <?= $_SESSION['Lastname'] ?></span>				
		  </p>				
		</a>						
		<script type = "text/JavaScript">		
			$(document).ready(function() {												
				
				
				loadNotifications();		
				function loadNotifications( ) {			
					try { var notifications = JSON.parse('<?php echo $Notifications; ?>');		
					} catch(err){  console.log(err);  }
					var msg = ""; 	var countOfNewNote = 0; 	var countOfOldNote = 0;
					for(var item in notifications)
					{	if(notifications[item]["readOrUnread"] == false)  {  countOfNewNote++;	} else { countOfOldNote++; }  }						
					if(countOfNewNote > 0) {
						$('#NotificationCount').append(": "+countOfNewNote+"");
						msg += "<div id = \"OLDNotificationS\">";
						msg += "<div id = \"LineBox\">New </div>";
						for(var item in notifications) {			
							if(notifications[item]["readOrUnread"] == false) {						
								msg += "<div id=\"notificationCell\" class = \"NewNotification\">";					
								msg += "<p  class = \"bold\">"+notifications[item]["message"]+"</p>";				
								var CreatedDateAndTime = ""+notifications[item]["CreatedDateAndTime"]+"";
								msg += "<pre>Date & Time: "+CreatedDateAndTime+"</pre>"
								msg += "</div>";
							}
						}				
						msg += "</div>";
					}// End of IF countOfNewNote > 0.												
					if(countOfOldNote > 0) {
						msg += "<div id = \"OLDNotificationS\">";
						msg += "<div id = \"LineBox\">Old</div>";
						for(var item in notifications) {			
							if(notifications[item]["readOrUnread"] == true) {
								msg += "<div id=\"notificationCell\" class = \"OldNotification\">";			
								msg += " <p>"+notifications[item]["message"]+"</p>";				
								var CreatedDateAndTime = ""+notifications[item]["CreatedDateAndTime"]+"";
								msg += "<pre>Date & Time: "+CreatedDateAndTime+"</pre>"
								msg += "</div>";
							}
						}
						msg += "</div>";
					}// End of if countOfOldNote > 0.			
					if(countOfNewNote == 0 && countOfOldNote == 0) {
						msg += "<div id=\"notificationCell\" class = \"NewNotification\">";			
						msg += "<p class = \"bold\" >No notifications to show.</p>";								
						msg += "</div>";
					}						
					$('#NotificationBOXInner').append(msg);					
				}// End of Function loadNotifications.							
				
				
				
				$('#NotificationBOXInner').css("display","none");
			
				var h3 = $("#NotificationBOX h3"); 			
				h3.click( function() {											
					if($('#NotificationBOXInner').css('display') != 'none') {	
						/// Hide notification, and switch button name.
							$('#NotificationBOXInner').css("display","none");							
					} else {
						/// Show notifications, and switch button name.
							$('#NotificationBOXInner').css("display","block");
						/// Now make ajax call to change notificationss					
						$.post('<?= base_Url(); ?>index.php/Home/ClearNewNotifications', 0, function(data) { console.log(data); });
					}						
				});
				
				
			});	/// End of Jquery		
		</script>
		<div id = "NotificationBOX">
			<h3>Notifications<span id="NotificationCount"></span>				
			</h3>
			<div id= "NotificationBOXInner"><!-- Notifications go here --></div>			
		</div>		
		  <!-- Logout link -->
		  <a href = "<?= base_url()?>index.php/Home/logout" ><div id="logoutButton"><p> Logout </p></div></a>							
	</div>
</div>
	<div id = "CenterBox" >