<?php
    require_once "../includes/connection.php";
	require_once "admin_login.php";
?>
<?php include "header.php"; ?>

<div id="stats_holder"></div>

<script>
	fetch_stats();
	var timer = setInterval(fetch_stats,3000);
	function fetch_stats(){
		$.get("fetch_stats.php",function(data,status){
			$("#stats_holder").html(data);
		});
	}
</script>
<?php include "footer.php"; ?>