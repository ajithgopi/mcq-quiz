<?php
	/**
	 * admin/preview.php
	 *
	 * Page that shows the preview of the examination as how the candidates would see them
	 *
	 * @author     Ajith Gopi
	 * @copyright  2019, Ajith Gopi
	 * @license    https://github.com/ajithgopi/mcq-quiz/blob/master/LICENSE  BSD 3-Clause License
	 *
	 * DO NOT REMOVE THIS COPYRIGHT INFORMATION WITHOUT PERMISSION. YOU WILL BE VIOLATING THE LICENSE
	 * AGGREMENT WHEN YOU DO SO. (according to https://github.com/ajithgopi/mcq-quiz/blob/master/LICENSE).
	 */
	 
    require_once("../includes/connection.php");
	require_once "admin_login.php";
	
	if(isset($_GET['qs'])){
		$qs = $conn->real_escape_string($_GET['qs']);
	}
	
	//$exam_det = $conn->query("SELECT * FROM `exams` WHERE `id`=(SELECT `exam` FROM `registrations` WHERE `id`='$u')")->fetch_array();
	$exam_det = $cexam;
	
	$page_title = $exam_det["name"];
	
    $total_time = $exam_det["time_alloted"] * 60;
	
    $time_elapsed = 0;
    $time_rem = $total_time-$time_elapsed;
?>
<?php
	$path_prefix = '../';
	$dump_reg_code = 'PRV'.mt_rand(111,999);
	include "../includes/header.php";
?>
<script>
	var total_questions=<?php echo $cexam['nquestions']; ?>;
	function markAnswer(qstn,opt,qno,ntry=1){
		$("#qno_"+qno).html('<a class="btn-floating btn-small green"><i class="material-icons">check</i></a>');
	}
</script>

<div class="timer_holder"><center><span class="light-font tooltipped" style="max-width:150px;" id="timer" data-position="left" data-tooltip="Time remaining"></span></center></div>

<div class="questions" <?php if($cexam['secured']): ?>style="-moz-user-select: none; -webkit-user-select: none; -ms-user-select:none; user-select:none;-o-user-select:none;" unselectable="on" onselectstart="return false;" onmousedown="return false;" <?php endif; ?>>
	<?php
		$questions = $conn->query("SELECT *,`question_pool`.`id` AS `qid` FROM `question_sets`,`question_pool` WHERE `question_sets`.`question`=`question_pool`.`id` AND `qcode`='$qs'");
		$qno=1;
		while($q=$questions->fetch_array()):
			$order=array();
			while(count($order)<4){
				$it = rand(1,4);
				if(!in_array($it,$order))
					array_push($order,$it);
			}
	?>
	
	<div class="row" id="question_<?php echo $qno; ?>">
		<div class="col s1 align-right" id="qno_<?php echo $qno; ?>">
			<a class="btn-floating btn-small blue"><?php echo $qno ?></a>
		</div>
		
		<div class="col s11"><?php echo nl2br(htmlspecialchars($q["question"])) ?><br/><br/>
			<?php for($i=0;$i<4;$i++): ?>
			<label>
				<input id="<?php echo "o_".$qno."_".$order[$i]; ?>"  name="q<?php echo $qno ?>" type="radio" onclick="markAnswer(<?php echo $q['qid']; ?>,<?php echo $order[$i] ?>,<?php echo $qno; ?>)"/>
				<span><?php echo htmlspecialchars($q["opt_".$order[$i]]); ?></span>
			</label>
			<br/><div style="margin-bottom:6px;"></div>
			<?php endfor; ?>
		</div>
	</div>
	
	<br/>
	<?php 
		$qno++;
		endwhile;
	?>
	
</div>

<center><a onclick="window.close();" class="waves-effect waves-light btn">Complete</a></center>
<br/><br/>



<script>
	var total_time = <?php echo $total_time ?>;
	var time_rem=<?php echo $time_rem ?>;

	calcTimeRem();
	var timer=setInterval(calcTimeRem,1000);
	
	var warning_time = Math.round(30*total_time/100);
	var last_time = Math.round(15*total_time/100);
	
	function calcTimeRem(){
		if(time_rem<=0){
			location.href="completed.php";
		}
		else{
			$("#timer").html(secondsToHms(time_rem));
			if(time_rem<=last_time){
				$("#timer").css('color','#F23040');
			}
			else if(time_rem<=warning_time){
				$("#timer").css('color','#DEA54B');
			}
			time_rem--;
		}
	}
	
	function secondsToHms(d){
		d = Number(d);
		var h = Math.floor(d / 3600);
		var m = Math.floor(d % 3600 / 60);
		var s = Math.floor(d % 3600 % 60);
		return (h>0?(h+":"):"") + (m>0?(pad(m,2)+":"):"") +pad(s,2);
	}
	function pad(num, size) {
		var s = num.toString();
		while(s.length<size)
			s = "0" + s;
		return s;
	}
	window.onscroll = function() {myFunction()};
	var timer = document.getElementById("timer");
	var sticky = timer.offsetTop;

	function myFunction() {
		if (window.pageYOffset >= sticky+40) {
			timer.classList.add("stick-to-top")
		} else {
			timer.classList.remove("stick-to-top");
		}
	}
	$('body').contextmenu(function() {
		return false;
	});
	function completeExam(){
		var total_answered=0;
		var first_occurance=0;
		for(var i=1;i<=total_questions;i++){
			if($("#question_"+i).attr('answered')==1)
				total_answered++;
			else{
				//$("#qno_"+i).html('<a class="btn-floating btn-small red">'+i+'</a>');
				if(first_occurance==0)
					first_occurance=i;
			}
		}
		if(total_questions==total_answered || confirm("You have only answered "+total_answered+"/"+total_questions+" questions. Are you sure want to finish this exam?"))
			//location.href="qsets.php";
			window.close();
		else{
			$('body,html').animate({
				scrollTop: eval($('#question_' + first_occurance).offset().top-10)
			}, 500);
		}
	}
</script>
<?php if($cexam['secured']): ?>
<script>
	var ban_time=10;
	var ban_timer=ban_time;
	var timer_focus_check = setInterval(function(){
		if(!document.hasFocus()){
			$('#warning').css('display','flex');
			if(ban_timer<=0)
				//location.href="qsets.php";
				window.close();
			else{
				$("#ban_timer").css('display','block');
				$("#ban_timer").html("Banning in "+ban_timer+ " seconds...");
				ban_timer--;
			}
		}
		else{
			$("#ban_timer").css('display','none');
			//ban_timer=ban_time;
		}
	},1000);
	$(document).mouseleave(function() {
		$('#warning').css('display','flex');
	});
	$(document).on('focus click',function(e) {
		$('#warning').css('display','none');
	});
	$(document).mouseenter(function() {
		if(document.hasFocus())
			$('#warning').css('display','none');
	});
	$(document).ready(function(){
		$('.tooltipped').tooltip();
	});
</script>
<?php endif; ?>
	<br/><br/>
<div id='warning' style='position:fixed;top:0;left:0;width:100%;height:100%;background:#222;display:none;z-index:9999;align-items:center;justify-content:center;'><span style='color:white'><center>Warning! This is a secured exam!</center><br/>You're outside the browser window! Please come back, or you will be banned if you click now.<br/><br/><span id="ban_timer" style="display:none;text-align:center;color:#ff4444">Banning in 5 seconds...</span></span></div>
<?php include "../includes/footer.php"; ?>