<?php
    require_once "../includes/connection.php";
	if(!isset($_SESSION['adm']) || $_SESSION['adm']<=0){
		header("location:login.php");
	}
?>