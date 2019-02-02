<!--- Manage Schedule page --->
<link href = "<?= assetUrl(); ?>css/ManageSchedule_Styles.css" rel="stylesheet" type="text/css"/> 
<link href = "<?= assetUrl(); ?>css/DisplaySchedule_Styles.css" rel="stylesheet" type="text/css"/> 
<script type = "text/babel" src="<?= assetUrl(); ?>js/ManageSchedule.js"> </script>
<script type = "text/babel">		
	var ParA  = "<?= assetUrl(); ?>";
	var ParB  = "<?= base_Url(); ?>";
	var ParC  = "<?= $TodayDisplayDate ?>";
	var ParD  = "<?= $TodayYyyymmdd ?>";
	var ParE  = [];
	var ParF  = [];
	try {
		ParE  = JSON.parse( '<?= $TodaysScheduleArray ?>' );
		ParF  = JSON.parse( '<?= $DefaultEmployeeArr ?>' );	
	}catch(e)
	{
		alert("Schedule and Employee data could not be initialized.")
	}
	
	
	/// Can put in try catch error handling here for when page doesnt have correct inital loading values. 	
	RunJS(  ParA, ParB, ParC, ParD, ParE, ParF );	
</script>
<a href ="<?= base_url(); ?>index.php?/SupervisorControllers/TheEditPage"> <div id = "EditButton"> <p> Supervisor page <p> </div></a>
<div id = "ManageSchedule">
</div>
<!-- End of  Manage Schedule page -->