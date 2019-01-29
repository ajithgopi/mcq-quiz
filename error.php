<?php
	/**
	 * error.php
	 *
	 * Page that show when an error is occured
	 *
	 * @author     Ajith Gopi
	 * @copyright  2019, Ajith Gopi
	 * @license    https://github.com/ajithgopi/mcq-quiz/blob/master/LICENSE  BSD 3-Clause License
	 *
	 * DO NOT REMOVE THIS COPYRIGHT INFORMATION WITHOUT PERMISSION. YOU WILL BE VIOLATING THE LICENSE
	 * AGGREMENT WHEN YOU DO SO. (according to https://github.com/ajithgopi/mcq-quiz/blob/master/LICENSE).
	 */
 
	$page_title="Something went wrong!";
	$is_error_page=true;
    require_once("includes/connection.php");
	if(isset($_GET['reason'])){
		switch($_GET['reason']){
			case 1:
			$errmsg = "Coudn't allote question set. Please contact an authorized person/volunteer for more information.";
			break;
		}
	}
?>
<?php include "includes/header.php"; ?>
<br/>
<center><h4 class="light-font">Sorry for the inconvenience!</h4></center><br/>
<center>
	<?php echo $errmsg; ?>
	<br/><br/><br/>
	<a href="index.php" class="btn">OK</a>
</center>
<?php include "includes/footer.php"; ?>