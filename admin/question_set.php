<?php
	/**
	 * admin/question_set.php
	 *
	 * Page that shows the questions inside a question set
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
    if(isset($_GET["set"])){
        $set = $conn->real_escape_string($_GET['set']);
        if(isset($_GET["act"]) && $_GET["act"]=="del"){
            $conn->query("DELETE FROM `question_sets` WHERE `qcode`='$set' AND `exam`='$settings[active_exam]'");
            header("location:qsets.php?msg=".base64_encode("Question set deleted"));
        }
    }
    else
        $set = $conn->query("SELECT (MAX(`qcode`)+1) AS `nset` FROM `question_sets`")->fetch_array()["nset"];
    
	if(isset($_POST['subm'])){
		if(count($_POST["q"])==$cexam["nquestions"]){
			$conn->query("DELETE FROM `question_sets` WHERE `qcode`='$set' AND `exam`='$settings[active_exam]'");
			foreach($_POST["q"] as $q){
				$conn->query("INSERT INTO `question_sets` (`exam`,`qcode`,`question`) VALUES('$settings[active_exam]','$set','".$conn->real_escape_string($q)."')");
			}
			header("location:qsets.php?msg=".base64_encode("Question set saved"));
		}
		else
			$msg = "Please select exactly ".$cexam["nquestions"]." questions to proceed!";
    }
	$questions = $conn->query("SELECT * FROM `question_pool` WHERE `exam`='$settings[active_exam]'");
	if($questions->num_rows < $cexam['nquestions']){
		header("location:qsets.php?msg=".base64_encode("No enough questions!"));
		die("Not enough questions!");
	}
?>
<?php include "header.php"; ?>
<center><h4 class="light-font">Question set</h4></center>
<br/>

<div class="timer_holder"><center><span class="light-font tooltipped" style="max-width:150px;color:gray;" id="timer" data-position="left" data-tooltip="Questions Selected"></span></center></div>

<form method="post" id="qform" onsubmit="return validate();">
	<button onclick="event.preventDefault();selectRandom();return false;" class="waves-effect waves-light btn">Select Random</button>
	<button type="submit" name="subm" class="waves-effect waves-light btn">Save</button>
    <br/><br/><br/>
	<?php
        $i=1;
        while($row=$questions->fetch_array()):
            $selected = boolval($conn->query("SELECT COUNT(*) AS `cnt` FROM `question_sets` WHERE qcode='$set' AND `question`='$row[id]'")->fetch_array()["cnt"]);
    ?>
	<div class="row">
		<div class="col s1"><?php echo $i ?></div>
		<div class="col s11">
			<label>
				<input type="checkbox" id="q_<?php echo $i ?>" value="<?php echo $row["id"] ?>" name="q[]" onchange="countSelection()" <?php echo $selected?"checked":"" ?>/>
				<span><?php echo $row["question"] ?></span>
			</label>
		</div>
	</div>
    <?php 
        $i++;
        endwhile;
    ?>
    <button type="submit" name="subm" class="waves-effect waves-light btn">Save</button>
</form>

<script>
	var nquestions = <?php echo $cexam['nquestions'] ?>;
	var total_questions = <?php echo $i-1 ?>;
	
	var count;
	countSelection();
    function countSelection(){
        count = 0;
        $("[name='q[]']").each(function() {
            if($(this).prop("checked")){
                count++;
            }
        });
        $("#timer").html(count + "/" + nquestions);
		if(count==nquestions)
			$("#timer").css('color','green');
		else
			$("#timer").css('color','gray');
		return count;
    }
	function validate(){
		if(nquestions !=countSelection()){
			M.toast({html:"Please add exactly " + nquestions + " questions to proceed!"});
			return false;
		}
	}
	function selectRandom(){
		$("[name='q[]']").each(function() {
            $(this).prop("checked",false);
        });
		qns = [];
		while(qns.length<nquestions){
			var cq = getRandomInt(1,total_questions);
			if(qns.indexOf(cq)<0)
				qns.push(cq);
		}
		
		qns.forEach(function(item,index){
			$("#q_"+item).prop("checked",true);
		});
		countSelection();
	}
	function getRandomInt(min, max) {
		return Math.floor(Math.random() * (max - min + 1)) + min;
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
	$(document).ready(function(){
		$('.tooltipped').tooltip();
	});
</script>

<?php include "footer.php"; ?>