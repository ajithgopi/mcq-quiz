<?php
	/**
	 * admin/admin_login.php
	 *
	 * Admin panel login page
	 *
	 * @author     Ajith Gopi
	 * @copyright  2019, Ajith Gopi
	 * @license    https://github.com/ajithgopi/mcq-quiz/blob/master/LICENSE  BSD 3-Clause License
	 *
	 * DO NOT REMOVE THIS COPYRIGHT INFORMATION WITHOUT PERMISSION. YOU WILL BE VIOLATING THE LICENSE
	 * AGGREMENT WHEN YOU DO SO. (according to https://github.com/ajithgopi/mcq-quiz/blob/master/LICENSE).
	 */
	 
    require_once "../includes/connection.php";
	if(!isset($_SESSION['adm']) || $_SESSION['adm']<=0){
		header("location:login.php");
	}
?>