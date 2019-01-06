<?php
	$page_title="Thank you!";
    require_once("includes/connection.php");
	
	if(isset($_SESSION['u'])){
		if(isset($_GET['b']) && $_GET['b']=='1'){
			$conn->query("UPDATE `registrations` SET `status`='-1',`date_completed`='".date('Y-m-d H:i:s')."' WHERE `id`='$_SESSION[u]'");
		}
		else{
			$conn->query("UPDATE `registrations` SET `status`='1',`date_completed`='".date('Y-m-d H:i:s')."' WHERE `id`='$_SESSION[u]'");
		}
	}
    $_SESSION=array();
    session_destroy();
?>
<?php include "includes/header.php"; ?>
<br/>
<center>
<?php if(isset($_GET['b']) && $_GET['b']=='1'): ?>
	<h4 class="light-font">Uh oh!</h4><br/>
	You've been banned for changing the browser window. Please contact the administrator or technical support if you think this is a mistake.
<?php else: ?>
	<h4 class="light-font">Thank you!</h4><br/>
	<?php if($cexam['show_scores']): ?>
	<span style="letter-spacing:8px;">YOUR SCORE</span><br/>
	<?php
		$reg = $conn->real_escape_string($_GET['reg']);
		$regd = $conn->query("SELECT *,
							(SELECT SUM(`score`) FROM `answers` WHERE `regid`=`registrations`.`id`) AS `scr`,
							(SELECT COUNT(*) FROM `answers` WHERE `regid`=`registrations`.`id`) AS `ansd`
						FROM `registrations` WHERE id='$reg'")->fetch_array();
		$right=intval($regd["scr"]);
		$negative = ($regd['ansd']-$right)*$cexam['neg_mark'];
		$positive = $right*$cexam['pos_mark'];
		$score = $positive-$negative;
	?>
	<h2 class="light-font"><b><?php echo $score ?></b><?php if($cexam['neg_mark']!=0): ?><sup style="font-size:60%;" class="light-font"><?php echo $positive ?>-<?php echo $negative ?></sup><?php endif; ?>/<?php echo $cexam['nquestions'] ?></h2>
	<?php else: ?>
	Thanks for attending <?php echo $cexam["name"]; ?>. Your results will be announced soon!
	<?php endif; ?>
<?php endif; ?>
<br/><br/>
<div style='font-size:80%'>
	You will be redirected back to home page in <span id="time_left">10</span> seconds.
</div>
</center>
<script>
	var time_left = 10;
	setInterval(function(){
		if(time_left>1){
			time_left--;
			$("#time_left").html(time_left);
		}
		else
			location.href="index.php";
	},1000);
</script>
<?php include "includes/footer.php"; ?>