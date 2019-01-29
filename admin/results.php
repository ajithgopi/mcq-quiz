<?php
	/**
	 * admin/results.php
	 *
	 * Page that shows the result of an examination
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
<center><h4 class="light-font">Results</h4></center>
<br/>

<form method="post" action="export.php">
	<div class="input-field col s12">
		<select name="exam">
			<?php
				$exams = $conn->query("SELECT *,(SELECT COUNT(*) FROM `registrations` WHERE `exam`=`exams`.`id`) AS `regs` FROM `exams`");
				while($row=$exams->fetch_array()){
					echo "<option value='$row[id]' ".($row['id']==$settings['active_exam']?'selected':'').">".$row['name']." ($row[regs] Participants)</option>";
				}
			?>
		</select>
		<label>Exam</label>
	</div>
	<br/>
    <button type="submit" class="waves-effect waves-light btn" style="float:right">Download</button>
</form>
<script>
$(document).ready(function(){
	$('select').formSelect();
});
</script>
<?php include "footer.php"; ?>