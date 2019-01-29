<?php
	/**
	 * admin/answers.php
	 *
	 * Page that lists answers by each candidate in a specific exam
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
	
	if(!isset($_GET['reg']) || $_GET['reg']==0)
		exit();
	$regid = $conn->real_escape_string($_GET['reg']);
	$reg = $conn->query("SELECT * FROM `registrations` WHERE `id`='$regid'")->fetch_array();
	$sel_exam = $conn->query("SELECT * FROM exams WHERE id='$reg[exam]'")->fetch_array();
	if($reg===false)
		die();
	$page_title="Answers (#".$reg['regcode'].")";
?>

<?php include "header.php"; ?>
<script>
	function exportExcel(){
		$('body').html($("#data").html());
		$('body').css('background','white');
		$('.qhead').css('width','auto');
		window.print();
		location.reload();
	}
</script>
<button onclick="exportExcel()" class="btn-floating teal" style="float:right"><i class="material-icons">print</i></button>
<br/>
<center><h4 class="light-font">Answers by #<?php echo $reg['regcode'] ?></h4></center>
<br/>
<div id="data">
	<table cellpadding="5">
		<tr>
			<th class="qhead" style="width:600px">Question</th>
			<th>Right asnwer</th>
			<th>Chosen answer</th>
			<th>Score</th>
		</tr>
		<?php
			$answers=$conn->query("SELECT question_pool.question as question,opt_1,opt_2,opt_3,opt_4,right_opt,copt,score FROM `answers`,`question_pool` WHERE `regid`='$regid' and `answers`.`question`=`question_pool`.`id`");
			$ts=0;
			while($row=$answers->fetch_array()){
				$ts+=($row['score']?$sel_exam['pos_mark']:-$sel_exam['neg_mark']);
		?>
		<tr>
			<td title="<?php echo htmlspecialchars($row['question']) ?>"><?php echo nl2br(htmlspecialchars($row['question'])) ?></td>
			<td><?php echo $row['opt_'.$row['right_opt']] ?></td>
			<td><?php echo $row['opt_'.$row['copt']] ?></td>
			<td><a class="btn-floating btn-small <?php echo $row['score']?'green':'red' ?>"><?php echo ($row['score']?$sel_exam['pos_mark']:-$sel_exam['neg_mark']) ?></a></td>
		</tr>
		<?php 
			}
		?>
		<tr>
			<th colspan="3">Total</th>
			<td><a class="btn-floating btn-small blue"><?php echo $ts ?></a></td>
		</tr>
	</table>
</div>
<?php include "footer.php"; ?>