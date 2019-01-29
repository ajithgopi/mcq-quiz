<?php
    require_once("includes/connection.php");
    if(isset($_SESSION['u'])){
        $u = $_SESSION["u"];
    }
    else{
        header("location:register.php");
    }
	
	$exam_det = $conn->query("SELECT * FROM `exams` WHERE `id`=(SELECT `exam` FROM `registrations` WHERE `id`='$u')")->fetch_array();
	
	$page_title = $exam_det["name"];
	
    $total_time = $exam_det["time_alloted"] * 60;
	
	$registration = $conn->query("SELECT * FROM `registrations` WHERE `id`='$u'")->fetch_array();
    $time_elapsed = time() - strtotime($registration["date_registered"]);
    $time_rem = $total_time-$time_elapsed;

    if($time_rem<0 || $registration["status"]!=0)
        header("location:completed.php?reg=".$registration['id']);
?>
<?php include "includes/header.php"; ?>
<script>
	var total_questions=<?php echo $cexam['nquestions']; ?>;
	function markAnswer(qstn,opt,qno,ntry=1){
		let answered_text = '<div class="preloader-wrapper small active"><div class="spinner-layer spinner-green-only"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div>';
		if($("#qno_"+qno).html()!=answered_text)
			$("#qno_"+qno).html(answered_text);
		
		var isChecked = ( $("#o_"+qno+"_"+opt).attr('isChecked') == '1')?'del':'edit';
		
		$.post("ajax/mark_answer.php",
		{
			q:qstn,
			o:opt,
			a:isChecked
		},
		function(data,status){
			if(data=="1"){
				if(isChecked=='edit'){
					$("#question_"+qno).attr('answered','1');
					$("#o_"+qno+"_"+opt).attr('isChecked','1');
					$("#qno_"+qno).html('<a class="btn-floating btn-small green"><i class="material-icons">check</i></a>');
				}
				else{
					clearAnswers(qno);
					$("#qno_"+qno).html('<a class="btn-floating btn-small blue">'+qno+'</a>');
				}
				//$("#qno_"+qno).html('<a class="btn-floating btn-small green">'+qno+'</a>');
			}else{
				if(ntry<3){
					setTimeout(function(){markAnswer(qstn,opt,qno,ntry+1);},300);
				}
				else{
					clearAnswers(qno);
					$("#qno_"+qno).html('<a class="btn-floating btn-small red">'+qno+'</a>');
					M.toast({html: "Failed to save last answer (Question #" + qno + "). Please mark the answer again!"});
				}
			}
		});
	}
	function clearAnswers(qno){
		for(var i=1;i<=4;i++){
			$("#o_"+qno+"_"+i).attr('isChecked','0')
			$("#o_"+qno+"_"+i).prop("checked",false);
		}
		$("#qno_"+qno).html('<a class="btn-floating btn-small red">'+qno+'</a>');
	}
</script>

<div class="timer_holder"><center><span class="light-font tooltipped" style="max-width:150px;" id="timer" data-position="left" data-tooltip="Time remaining"></span></center></div>

<div class="questions" <?php if($cexam['secured']): ?>style="-moz-user-select: none; -webkit-user-select: none; -ms-user-select:none; user-select:none;-o-user-select:none;" unselectable="on" onselectstart="return false;" onmousedown="return false;" <?php endif; ?>>
	<?php
		$questions = $conn->query("SELECT *,`question_pool`.`id` AS `qid` FROM `question_sets`,`question_pool` WHERE `question_sets`.`question`=`question_pool`.`id` AND `qcode`=(SELECT `qcode` FROM `registrations` WHERE `id`='$u') ORDER BY (SELECT COUNT(*) FROM `answers` WHERE `regid`='$u' AND `question`=`question_pool`.`id`) DESC, RAND()");
		$qno=1;
		while($q=$questions->fetch_array()):
			$sel_opt = $conn->query("SELECT `copt` FROM `answers` WHERE `question`='$q[id]' AND `regid`='$u'")->fetch_array()["copt"];
			$order=array();
			while(count($order)<4){
				$it = rand(1,4);
				if(!in_array($it,$order))
					array_push($order,$it);
			}
	?>
	
	<div class="row" id="question_<?php echo $qno; ?>" answered="<?php echo $sel_opt>0?1:0; ?>">
		<div class="col s1 align-right" id="qno_<?php echo $qno; ?>">
			<?php if($sel_opt>0): ?>
				<a class="btn-floating btn-small green"><i class="material-icons">check</i></a>
			<?php else: ?>
				<a class="btn-floating btn-small blue"><?php echo $qno ?></a>
			<?php endif; ?>
		</div>
		
		<div class="col s11"><?php echo nl2br(htmlspecialchars($q["question"])) ?><br/><br/>
			<?php for($i=0;$i<4;$i++): ?>
			<label>
				<input id="<?php echo "o_".$qno."_".$order[$i]; ?>"  name="q<?php echo $qno ?>" type="radio" <?php echo $sel_opt>0&&$order[$i]==$sel_opt?"checked isChecked='1'":" isChecked='0'"; ?> onclick="markAnswer(<?php echo $q['qid']; ?>,<?php echo $order[$i] ?>,<?php echo $qno; ?>)"/>
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

<center><a onclick="completeExam()" class="waves-effect waves-light btn">Complete</a></center>
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
			location.href="completed.php?reg=<?php echo $registration['id'] ?>";
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
	window.onscroll = function() {scrollController()};
	var timer = document.getElementById("timer");
	var sticky = timer.offsetTop;

	function scrollController() {
		if (window.pageYOffset >= sticky+40) {
			timer.classList.add("stick-to-top")
		} else {
			timer.classList.remove("stick-to-top");
		}
	}
	$(document).ready(function(){
		$('.tooltipped').tooltip();
	});
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
			location.href="completed.php?reg=<?php echo $registration['id'] ?>";
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
				location.href="completed.php?b=1";
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
</script>
<?php endif; ?>
	<br/><br/>
<div id='warning' style='position:fixed;top:0;left:0;width:100%;height:100%;background:#222;display:none;z-index:9999;align-items:center;justify-content:center;'><span style='color:white'><center>Warning! This is a secured exam!</center><br/>You're outside the browser window! Please come back, or you will be banned if you click now.<br/><br/><span id="ban_timer" style="display:none;text-align:center;color:#ff4444">Banning in 5 seconds...</span></span></div>
<?php include "includes/footer.php"; ?>