<?php
    require_once "../includes/connection.php";
	require_once "admin_login.php";
	
	if(isset($_POST['pid'])){
		$pid = $conn->real_escape_string($_POST['pid']);
		
		$total_time = $cexam["time_alloted"] * 60;
		$registration = $conn->query("SELECT * FROM `registrations` WHERE `id`='$pid'")->fetch_array();
		$time_elapsed = time() - strtotime($registration["date_registered"]);
		$time_rem = $total_time-$time_elapsed;

		if($time_rem<0)
			$status=1;
		else
			$status=0;
		
		if($conn->query("UPDATE `registrations` SET `status`='$status' WHERE `id`='$pid'"))
			die("OK");
	}
?>