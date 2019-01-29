<?php
	/**
	 * admin/logout.php
	 *
	 * Logs the administrator user out and redirects to index page
	 *
	 * @author     Ajith Gopi
	 * @copyright  2019, Ajith Gopi
	 * @license    https://github.com/ajithgopi/mcq-quiz/blob/master/LICENSE  BSD 3-Clause License
	 *
	 * DO NOT REMOVE THIS COPYRIGHT INFORMATION WITHOUT PERMISSION. YOU WILL BE VIOLATING THE LICENSE
	 * AGGREMENT WHEN YOU DO SO. (according to https://github.com/ajithgopi/mcq-quiz/blob/master/LICENSE).
	 */
	 
    require_once("../includes/connection.php");
    $_SESSION=array();
    session_destroy();
    header("location:index.php");
?>