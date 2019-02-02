<!--SchedulePage-->
<link href = "<?= assetUrl(); ?>css/DisplaySchedule_Styles.css" rel="stylesheet" type="text/css"/>
<script type = "text/babel" src="<?= assetUrl(); ?>js/DisplayScheduleEmployeeEnd.js"> </script> 
<script type = "text/babel">		



	var ParB  = "<?= base_Url(); ?>";
	var ParC  = "<?= $TodayDisplayDate ?>";
	var ParD  = "<?= $TodayYyyymmdd ?>";
	var ParE  = [];
	var ParF  = parseInt( <?php if($_SESSION['Supervisor']) {echo "1";}else{echo "0";} ?> ); 
	var ParG = parseInt( <?php if($_SESSION['Headguard']) {echo "1";}else{echo "0";} ?> );
	
	try {
		ParE  = JSON.parse( '<?= $TodaysSchedule ?>' );
	}catch(e)
	{
		alert("Schedule and Employee data could not be initialized.")
	}
	
	
	/// Can put in try catch error handling here for when page doesnt have correct inital loading values. 	
	RunJS(   ParB, ParC, ParD, ParE, ParF, ParG );	
</script>
<div id = "SchedulePage">
	<div id ="ScheduleApp">	
	
	</div>	
	
</div><!--End of schedule page. -->