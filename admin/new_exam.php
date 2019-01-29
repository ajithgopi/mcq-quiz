<?php
	/**
	 * admin/new_exam.php
	 *
	 * Page to create new examination
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
	if(isset($_POST['subm'])){
		$name = $conn->real_escape_string($_POST['name']);
		$time = $conn->real_escape_string($_POST['time']);
		$start_date = $conn->real_escape_string($_POST['start_date']);
		$start_time = $conn->real_escape_string($_POST['start_time']);
		$start_datetime = date('Y-m-d H:i:s',strtotime("$start_date $start_time"));
		$nquestions = $conn->real_escape_string($_POST['nquestions']);
		$pos_mark = $conn->real_escape_string($_POST['pos_mark']);
		$neg_mark = $conn->real_escape_string($_POST['neg_mark']);
		$secured = (int)isset($_POST['secured']);
		
		$conn->query("INSERT INTO `exams` (`name`,`time_alloted`,`start_date`,`nquestions`,`secured`,`pos_mark`,`neg_mark`,`show_scores`) VALUES('$name','$time','$start_datetime','$nquestions','$secured','$pos_mark','$neg_mark','$show_scores')");
		header("location:exams.php?msg=".base64_encode("Exam created"));
	}
?>

<?php include "header.php"; ?>

<center><h4 class="light-font">New Exam</h4></center>
<div class="input-field col s12">
	<form method="post">
		<div class="input-field">
			<input type="text" id="name" name="name" required/>
			<label for="name">Name</label>
		</div>
		<div class="input-field">
			<input type="text" class="datepicker" name="start_date" id="start_date" required/>
			<label for="start_date">Start date</label>
		</div>
		<div class="input-field">
			<input type="text" name="start_time" id="start_time" class="timepicker" required/>
			<label for="start_time">Start time</label>
		</div>
		<div class="input-field">
			<input type="number" name="time" required min="1" value="30"/>
			<label for="name">Time alloted (in minutes)</label>
		</div>
		<div class="input-field">
			<input type="number" min="1" name="nquestions" required value="25"/>
			<label for="name">Questions per session</label>
		</div>
		<div class="input-field">
			<input type="text" pattern="^\d+(\.\d+)?$" name="pos_mark" required value="1"/>
			<label for="name">Positive mark</label>
		</div>
		<div class="input-field">
			<input type="number" pattern="^\d+(\.\d+)?$" name="neg_mark" required value="0"/>
			<label for="name">Negative (Will be multiplied by the number of wrong answers)</label>
		</div>
		<div class="switch">
			<label>
				Secured
				<input checked name="secured" type="checkbox">
				<span class="lever"></span>
			</label>
		</div><br/>
		<div class="switch">
			<label>
				Show scores after completion
				<input checked name="show_scores" type="checkbox">
				<span class="lever"></span>
			</label>
		</div><br/>
		<button type="submit" name="subm" class="waves-effect waves-light btn">Add</button>
	</form>
</div>

<script>
	$(document).ready(function(){
		$('.timepicker').timepicker();
		$('.datepicker').datepicker();
	});
	
</script>

<?php include "footer.php"; ?>