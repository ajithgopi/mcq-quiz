<?php
	/**
	 * instructions.php
	 *
	 * Instructions for the candidates
	 *
	 * @author     Ajith Gopi
	 * @copyright  2019, Ajith Gopi
	 * @license    https://github.com/ajithgopi/mcq-quiz/blob/master/LICENSE  BSD 3-Clause License
	 *
	 * DO NOT REMOVE THIS COPYRIGHT INFORMATION WITHOUT PERMISSION. YOU WILL BE VIOLATING THE LICENSE
	 * AGGREMENT WHEN YOU DO SO. (according to https://github.com/ajithgopi/mcq-quiz/blob/master/LICENSE).
	 */
	 
    require_once("includes/connection.php");
	$page_title="Instructions";
	
	$time_to_wait = strtotime($cexam['start_date']) - time();
	
	if(!isset($_POST['regcode']) || $_POST['regcode']=="" || !isset($_POST['institute']) || !isset($_POST['prtcpnts'])){
		header('location:register.php');
		die();
	}
	
	if(isset($_SESSION['u'])){
		header('location:quiz.php');
		die();
	}
	
	$regcode = $conn->real_escape_string(trim(strtoupper($_POST['regcode'])));
	
	$user = $conn->query("SELECT * FROM `registrations` WHERE regcode='$regcode' AND `exam`='$settings[active_exam]'");
	if($user->num_rows>0){
		$_SESSION["u"] = $user->fetch_array()["id"];
		header("location:quiz.php");
		exit();
	}
	
    if(isset($_POST['subm'])){
		if($cexam["start_date"]=='0000-00-00 00:00:00' || $time_to_wait<=0){
			$codes=array();
			$qcodes = $conn->query("SELECT DISTINCT `qcode` FROM `question_sets` WHERE `exam`='$settings[active_exam]'");
			while($row=$qcodes->fetch_array()){
				array_push($codes,$row["qcode"]);
			}
			$cnt = count($codes);
			if($cnt>0){
				
				$participants = $conn->real_escape_string($_POST['prtcpnts']);
				$institute = $conn->real_escape_string($_POST['institute']);
				
				$code = $codes[mt_rand(0,$cnt-1)];
				
				$conn->query("INSERT INTO `registrations`(`regcode`,`participants`,`institute`,`exam`,`date_registered`, `qcode`,`status`) VALUES ('$regcode','$participants','$institute','$settings[active_exam]','".date('Y-m-d H:i:s')."','$code',0)");
				$_SESSION["u"] = $conn->insert_id;
				header("location:quiz.php");
				exit();
			}
			header("location:error.php?reason=1");
			exit();
		}
		else{
			$msg = "Hold on! You can login once the exam has started.";
		}
    }
?>
<?php include "includes/header.php"; ?>

<?php
	if(isset($msg)){
		echo "<script>M.toast({html: '$msg'})</script>";
	}
?>


<!--
<h1 align="center" class="light-font" style="text-transform:uppercase;">#<?php echo htmlspecialchars($_POST['regcode']) ?></h1>
-->
<br/>
<center><h4 class="light-font">Instructions</h4></center>
<div class="row">
	<div class="col s10 offset-s1">
		<p style="text-align:justify;color:#333">
			<ol>
				<li>The total duration of the exam is <b><?php echo $cexam["time_alloted"] ?> minutes</b>.</li>
				<li>Once you start the exam, You're not allowed to leave the hall till the exam is complete.</li>
				<li>The exam must be completed within the allocated time. If you don't click the <b>COMPLETE</b> button within that time, the exam will be completed automatically.</li>
				<?php if($cexam['secured']): ?>
				<li>You're not allowed to change this browser window while in the exam screen. Doing so (including taking a new tab, opening a third party software or clicking outside the browser's document area) will result in a permenant ban.</li>
				<?php endif; ?>
			</ol>
			<br/><br/>
			<h5>Status</h5><br/>
			<a class="btn-floating btn-small green"><i class="material-icons">check</i></a> Answered<br/><br/>
			<a class="btn-floating btn-small blue">1</a> Not visited<br/><br/>
			<a class="btn-floating btn-small red">1</a> Failed to save<br/><br/>
			<h5>Tips</h5>
			<ol>
				<li>Reload the page to move answered questions to the top of the list</li>
			</ol>
		</p>
	</div>
</div>
<br/><br/>
<form method="post">
	<div class="row" style="text-align:center">
		<div id="time_rem"></div><br/>
		<input type="hidden" value="<?php echo isset($_POST['regcode'])?$_POST['regcode']:"" ?>" name="regcode"/>
		<input type="hidden" value="<?php echo isset($_POST['prtcpnts'])?$_POST['prtcpnts']:"" ?>" name="prtcpnts"/>
		<input type="hidden" value="<?php echo isset($_POST['institute'])?$_POST['institute']:"" ?>" name="institute"/>
		<button name="subm" type="submit" disabled class="btn waves-effect waves-light" id="btn-start">Start</button>
		<br/><br/>
		<span style="font-size:80%;">By clicking agree, you confirm that you've read, and agree to the terms &amp; conditions and the instructions.</span>
		<br/><br/>
	</div>
</form>
<script>
	
	$(document).ready(function(){
		$('.modal').modal();
	});
	
	var time_rem=<?php echo $time_to_wait ?>;

	calcTimeRem();
	var timer=setInterval(calcTimeRem,1000);
	
	function calcTimeRem(){
		if(time_rem>0){
			$("#time_rem").html("Please wait " + secondsToHms(time_rem) + " before you can start the exam.");
			time_rem--;
		}
		else{
			$("#time_rem").html("You can now start the exam. Good luck!");
			$("#btn-start").removeAttr('disabled');
			clearInterval(timer);
		}
	}
	
	function secondsToHms(d){
		d = Number(d);
		var h = Math.floor(d / 3600);
		var m = Math.floor(d % 3600 / 60);
		var s = Math.floor(d % 3600 % 60);
		return (h>0?(h+"h:"):"") + (m>0?(pad(m,2)+"m:"):"") + pad(s,2)+"s";
	}
	function pad(num, size) {
		var s = num+"";
		while (s.length < size) s = "0" + s;
		return s;
	}
</script>
<?php include "includes/footer.php"; ?>