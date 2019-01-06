<?php
    require_once "../includes/connection.php";
	require_once "admin_login.php";
	
    if(isset($_POST["subm"])){
        $question = trim(trim($conn->real_escape_string($_POST['question'])),'\r\n');
        $opts = $_POST["opt"];
        foreach($opts as &$opt){
            $opt = $conn->real_escape_string($opt);
        }
        $ro = $conn->real_escape_string($_POST["ro"]);
		
		if($conn->query("SELECT COUNT(*) AS `cnt` FROM `question_pool` WHERE `exam`='$settings[active_exam]' && `question`='$question'")->fetch_array()["cnt"]==0){
			$conn->query("INSERT INTO `question_pool` (`exam`,`question`, `opt_1`, `opt_2`, `opt_3`, `opt_4`, `right_opt`) VALUES('$settings[active_exam]','$question','$opts[0]','$opts[1]','$opts[2]','$opts[3]',$ro)");
			$msg = "Question added!";
		}
		else
			$msg = "Question already exists in this exam!";
    }
?>

<?php include "header.php"; ?>

<center><h4 class="light-font">New Question</h4></center>

<form method="post">
	<div class="row">
		<div class="col s12 input-field">
			<textarea name="question"  id="question" class="materialize-textarea" data-length="300"></textarea>
			<label for="question">Question</label>
		</div>
	</div>
	<p>Mark the right item using the radio button</p>
	<div class="row">
		<div class="col s1 input-field">
			<label>
				<input type="radio" name="ro" value="1" checked/>
				<span></span>
			</label>
		</div>
		<div class="col s11 input-field">
			<input type="text" id="opt_1" required name="opt[0]" />
			<label for="opt_1">Option 1</label>
		</div>
	</div>
	<div class="row">
		<div class="col s1 input-field">
			<label>
				<input type="radio" name="ro" value="2"/>
				<span></span>
			</label>
		</div>
		<div class="col s11 input-field">
			<input type="text" required id="opt_2" name="opt[1]" />
			<label for="opt_2">Option 2</label>
		</div>
	</div>
	<div class="row">
		<div class="col s1 input-field">
			<label>
				<input type="radio" name="ro" value="3"/>
				<span></span>
			</label>
		</div>
		<div class="col s11 input-field">
			<input type="text" required id="opt_3" name="opt[2]" />
			<label for="opt_3">Option 3</label>
		</div>
	</div>
	<div class="row">
		<div class="col s1 input-field">
			<label>
				<input type="radio" name="ro" value="4"/>
				<span></span>
			</label>
		</div>
		<div class="col s11 input-field">
			<input type="text" required id="opt_4" name="opt[3]" />
			<label for="opt_4">Option 4</label>
		</div>
	</div>
    <button type="submit" name="subm" class="waves-effect waves-light btn">Save</button>
</form>

<script>
	$(document).ready(function() {
		$('textarea#question').characterCounter();
	});
</script>

<?php include "footer.php"; ?>