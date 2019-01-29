<?php
	/**
	 * admin/index.php
	 *
	 * Index page to list all the functionalities
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
<br/><br/>

<a href="exams.php">Exams</a><br/>
<a href="questions.php">Questions</a><br/>
<a href="qsets.php">Question Sets</a><br/>
<a href="results.php">Results</a><br/>
<a href="stats.php">Stats</a><br/>
<a href="change_pass.php">Change password</a><br/>
<a href="logout.php">Logout</a><br/>

<?php include "footer.php"; ?>