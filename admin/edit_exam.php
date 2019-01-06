<?php
    require_once "../includes/connection.php";
	require_once "admin_login.php";
	
	if(isset($_GET['id']) && isset($_GET['act']))
		$exam = $conn->real_escape_string($_GET['id']);
	else
		header("location:exams.php");
	
	if($_GET['act']=='del'){
		if($settings['active_exam']!=$exam){
			if(isset($_POST['confirm_delete'])){
				$conn->query("DELETE FROM `answers` WHERE `question` IN (SELECT `id` FROM `question_pool` WHERE `exam`='$exam')");
				$conn->query("DELETE FROM `question_pool` WHERE `exam`='$exam'");
				$conn->query("DELETE FROM `registrations` WHERE `exam`='$exam'");
				$conn->query("DELETE FROM `question_sets` WHERE `exam`='$exam'");
				$conn->query("DELETE FROM `exams` WHERE `id`='$exam'");
				header("location:exams.php?msg=".base64_encode("Exam deleted"));
			}
		}
		else{
			header("location:exams.php?msg=".base64_encode("Can't delete active exam"));
		}
	}
	else{
		$nqsets = $conn->query("SELECT COUNT(*) AS `cnt` FROM `question_sets` WHERE `exam`='$exam'")->fetch_array()['cnt'];
		if(isset($_POST['subm'])){
			$name = $conn->real_escape_string($_POST['name']);
			$time = $conn->real_escape_string($_POST['time']);
			$start_date = $conn->real_escape_string($_POST['start_date']);
			$start_time = $conn->real_escape_string($_POST['start_time']);
			$start_datetime = date('Y-m-d H:i:s',strtotime("$start_date $start_time"));
			$secured = isset($_POST['secured']);
			$show_scores = isset($_POST['show_scores']);
			$nquestions = $conn->real_escape_string($_POST['nquestions']);
			$pos_mark = $conn->real_escape_string($_POST['pos_mark']);
			$neg_mark = $conn->real_escape_string($_POST['neg_mark']);
		
			$conn->query("UPDATE  `exams` SET `name`='$name',`time_alloted`='$time',`start_date`='$start_datetime',`secured`='$secured',`show_scores`='$show_scores', `nquestions`='$nquestions',`neg_mark`='$neg_mark',`pos_mark`='$pos_mark' WHERE `id`='$exam'");
			header("location:exams.php?msg=".base64_encode("Changes saved!"));
		}
	}
	$exam_det = $conn->query("SELECT * FROM `exams` WHERE `id`='$exam'")->fetch_array();
?>

<?php include "header.php"; ?>
<?php if($_GET['act']=='edit'): ?>
<center><h4 class="light-font">Edit <?php echo htmlspecialchars($exam_det["name"]) ?></h4></center>
<div class="input-field col s12">
	<form method="post">
		<div class="input-field">
			<input type="text" id="name" name="name" value="<?php echo htmlspecialchars($exam_det["name"]) ?>" required/>
			<label for="name">Name</label>
		</div>
		<div class="input-field">
			<input type="text" class="datepicker" value="<?php echo date('M d, Y',strtotime($exam_det['start_date'])); ?>" name="start_date" id="start_date" required/>
			<label for="start_date">Start date</label>
		</div>
		<div class="input-field">
			<input type="text" name="start_time" value="<?php echo date('h:i A',strtotime($exam_det['start_date'])); ?>" id="start_time" class="timepicker" required/>
			<label for="start_time">Start time</label>
		</div>
		<div class="input-field">
			<input type="number" id="time" name="time" required min="1" value="<?php echo $exam_det["time_alloted"] ?>" value="30"/>
			<label for="time">Time alloted (in minutes)</label>
		</div>
		<div class="input-field">
			<input type="number" name="nquestions" id="nquestions" required min="1" <?php if($nqsets>0): ?> class="tooltipped" data-position="bottom" data-tooltip="Please remove all the existing question sets to change this" readonly <?php endif; ?>value="<?php echo $exam_det["nquestions"] ?>"/>
			<label for="nquestions">Number of questions</label>
		</div>
		<div class="input-field">
			<input type="text" pattern="^\d+(\.\d+)?$" name="pos_mark"  id="pos_mark" min="0" required value="<?php echo $exam_det["pos_mark"] ?>"/>
			<label for="pos_mark">Positive mark</label>
		</div>
		<div class="input-field">
			<input type="text" pattern="^\d+(\.\d+)?$" name="neg_mark" id="neg_mark" name="neg_mark" required value="<?php echo $exam_det["neg_mark"] ?>"/>
			<label for="neg_mark">Negative mark (Will be multiplied by the number of wrong answers)</label>
		</div>
		<div class="switch">
			<label>
				Secured
				<input name="secured" <?php echo $exam_det["secured"]?'checked':'' ?> type="checkbox">
				<span class="lever"></span>
			</label>
		</div><br/>
		<div class="switch">
			<label>
				Show scores after completion
				<input name="show_scores" <?php echo $exam_det["show_scores"]?'checked':'' ?> type="checkbox">
				<span class="lever"></span>
			</label>
		</div><br/>
		<button type="submit" name="subm" class="waves-effect waves-light btn">Save</button>
	</form>
</div>

<script>
	$(document).ready(function(){
		$('.timepicker').timepicker();
		$('.datepicker').datepicker();
		$('.tooltipped').tooltip();
	});
	
</script>
<?php else: ?>
<center><p>Are you sure you want to delete <i><?php echo htmlspecialchars($exam_det["name"]) ?></i>? All the registrations, Questions, Question sets and answeres associated with this exam will also be deleted. This action can't be undone.</p></center>
<br/><br/>
<center>
<form method="post">
	<button name="confirm_delete" class="btn red waves-effect waves-light">DELETE</button></center>
</form>
<?php endif; ?>
<?php include "footer.php"; ?>