<?php
	/**
	 * admin/stats.php
	 *
	 * Page that shows the current statistics of an examination. Lined to AJAX based page: fetch_status.php
	 *
	 * @author     Ajith Gopi
	 * @copyright  2019, Ajith Gopi
	 * @license    https://github.com/ajithgopi/mcq-quiz/blob/master/LICENSE  BSD 3-Clause License
	 *
	 * DO NOT REMOVE THIS COPYRIGHT INFORMATION WITHOUT PERMISSION. YOU WILL BE VIOLATING THE LICENSE
	 * AGGREMENT WHEN YOU DO SO. (according to https://github.com/ajithgopi/mcq-quiz/blob/master/LICENSE).
	 */
	 
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