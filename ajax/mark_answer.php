<?php
    require_once("../includes/connection.php");
    if(isset($_SESSION['u'])){
        $u = $_SESSION["u"];

        $question = $conn->real_escape_string($_POST['q']);
        $option = $conn->real_escape_string($_POST['o']);
        $action = $conn->real_escape_string($_POST['a']);

        $score = $conn->query("SELECT COUNT(*) AS `cnt` FROM `question_pool` WHERE `id`='$question' AND `right_opt`='$option' AND `exam`='$settings[active_exam]'")->fetch_array()["cnt"]; 

        if($conn->query("SELECT COUNT(*) AS `cnt` FROM `answers` WHERE `regid`='$u' AND `question`='$question'")->fetch_array()["cnt"]>0){
            if($action=='edit')
				$conn->query("UPDATE `answers` SET `copt`='$option',`score`='$score' WHERE `regid`='$u' AND `question`='$question'");
			elseif($action=='del')
				$conn->query("DELETE FROM `answers` WHERE `regid`='$u' AND `question`='$question'");
        }
        else{
            $conn->query("INSERT INTO `answers` (`regid`, `question`, `copt`,`score`) VALUES('$u','$question','$option','$score')");
		}
        die("1");
    }
?>