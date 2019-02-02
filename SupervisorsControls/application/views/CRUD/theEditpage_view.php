<link href = "<?= assetUrl(); ?>css/theEditPage_Styles.css" rel="stylesheet" type="text/css"/> 
<a href = "<?= base_url()?>index.php/SupervisorControllers/theEditPage/logout"> <div id="logoutButton"> <p>Logout<p></div></a>					
<div id ="SupervisorControls" >	
	<h1> Supervisor's Controls</h1>
	<nav id = "ManageShiftsNav"> <h2>Manage Shifts</h2>
		<a href = "<?= base_url();?>index.php?/SupervisorControllers/ManageSchedule"><li>Manage the Schedule </li></a>					
		<a href = "<?= base_url();?>index.php?/SupervisorControllers/ApproveSubSlips"><li>Approve sub slips   </li></a>					
	</nav>											
	<nav id = "ManageEmployeesNav"> <h2>Manage Employees</h2>
		<a href = "<?= base_url();?>index.php?/SupervisorControllers/CreateNewEmployee"><li>Create a new employee    </li></a>					
		<a href = "<?= base_url();?>index.php?/SupervisorControllers/UpdateEmployee"><li>Update Employee    </li></a>
		<a href = "<?= base_url();?>index.php?/SupervisorControllers/DeleteEmployee"><li>Delete employee    </li></a>
		<a href = "<?= base_url();?>index.php?/SupervisorControllers/EmployeeAvailability"><li>Change an employee's availability    </li></a>
	</nav>
			
</div>