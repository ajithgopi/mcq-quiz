<?php
    session_start();
	/*
		Database credentials
	*/
	
	$_host		=	"localhost";
	$_username	=	"root";
	$_password	=	"";
	$_db_name	=	"";
	
    $conn = new mysqli($_host,$_username,$_password,$_db_name);
    if($conn->connect_error){
        die("Couldn't connect");
    }
	
	$settings = $conn->query("SELECT * FROM `settings`")->fetch_array();
	$cexam = $conn->query("SELECT * FROM `exams` WHERE `id`='$settings[active_exam]'");
	if($cexam->num_rows<1){
		$conn->query("UPDATE `settings` SET `active_exam`='1'");
		$cexam = $conn->query("SELECT * FROM `exams` WHERE `id`='$settings[active_exam]'");
	}
	$cexam=$cexam->fetch_array();
	
	$conn->query('SET NAMES utf8');
	function iif($cond,$tp,$fp){
		return $cond?$tp:$fp;
	}
	
	function updateRegStatus(){
		global $conn;
		global $cexam;
		$conn->query("UPDATE registrations SET status='1' WHERE ADDTIME(date_registered,'0 00:$cexam[time_alloted]:00.0')<NOW()");
	}
	
	date_default_timezone_set("Asia/Kolkata");
	
	$site_title = $cexam["name"];
?>