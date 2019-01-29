<?php
	/**
	 * admin/header.php
	 *
	 * Common header for all the pages in the admin panel
	 *
	 * @author     Ajith Gopi
	 * @copyright  2019, Ajith Gopi
	 * @license    https://github.com/ajithgopi/mcq-quiz/blob/master/LICENSE  BSD 3-Clause License
	 *
	 * DO NOT REMOVE THIS COPYRIGHT INFORMATION WITHOUT PERMISSION. YOU WILL BE VIOLATING THE LICENSE
	 * AGGREMENT WHEN YOU DO SO. (according to https://github.com/ajithgopi/mcq-quiz/blob/master/LICENSE).
	 */
	 
	 
	header('Content-type: text/html; charset=utf-8');
	if(isset($_POST['change_current'])){
		updateRegStatus();
		if(!$conn->query("SELECT COUNT(*) AS `cnt` FROM `registrations` WHERE `exam`='$cexam[id]' AND `status`='0'")->fetch_array()['cnt']){
			$exam = $conn->real_escape_string($_POST['change_current']);
			$conn->query("UPDATE `settings` SET `active_exam`='$exam'");
			
			$settings = $conn->query("SELECT * FROM `settings`")->fetch_array();
			$cexam = $conn->query("SELECT * FROM `exams` WHERE `id`='$exam'")->fetch_array();
			$msg = "Active exam changed to ".$cexam['name'];
		}
		else{
			$msg = "Failed to change active exam as there are ongoing sessions in this exam";
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<link type="text/css" rel="stylesheet" href="../css/materialize.min.css"  media="screen,projection"/>
	<link type="text/css" rel="stylesheet" href="../css/custom.css"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<script src="../js/jquery.min.js"></script>
	<script type="text/javascript" src="../js/materialize.min.js"></script>
	<title><?php echo $site_title." - ".(isset($page_title)?$page_title:"Quiz") ?></title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<meta charset="UTF-8">
</head>
<body>
	<br/><br/><br/>
	<div class="" style='margin:0 1% 0 1%'>
		<div class="row">
			<div class="col s12  xl10 offset-xl1 main_content">
				<div class="quiz_name_heading row" style="margin-bottom:0;">
					<?php if(!isset($_SESSION['adm']) || $_SESSION['adm']<=0): ?>
					<div class="col s11 light-font">
						<span style='vertical-align:middle' class="light-font">Admin Panel</span>
					</div>
					<?php else: ?>
					<div class="col s11 light-font">
						<form method="post">
							<div class="input-field">
								<select name="change_current" onchange="submit()">
									<?php
										$exams = $conn->query("SELECT * FROM `exams`");
										while($row=$exams->fetch_array()){
											echo "<option value='$row[id]' ".iif($row['id']==$settings["active_exam"],"selected ","").">".$row['name']."</option>";
										}
									?>
								</select>
								<label>Currently managing</label>
							</div>
						</form>
						<script>
						$(document).ready(function(){
							$('select').formSelect();
						});
						</script>
					</div>
					<!--
					<div class="col s1 align-right light-font">
						<a class="btn-floating btn-large waves-effect waves-light green darken-1" href="<?php $url = explode('/',$_SERVER['REQUEST_URI']);echo '../'.$url[count($url)-2] ?>"><i class="material-icons">home</i></a>
					</div>
					-->
					<div class="col s1 align-right light-font">
						<a class="btn-floating btn-large waves-effect waves-light red darken-1" href="logout.php"><i class="material-icons">logout</i></a>
					</div>
					<?php endif; ?>
				</div>
				<?php $request_uri =  basename(parse_url($_SERVER['REQUEST_URI'])['path']); ?>
				<?php if(isset($_SESSION['adm'])): ?>
				<nav>
					<div class="nav-wrapper green lighten-2">
						<ul id="nav-mobile" class="left hide-on-med-and-down">
							<li class="waves-effect waves-light" <?php echo ($request_uri=='' || $request_uri=='index.php')?'class="active"':'' ?>><a href="index.php">Home</a></li>
							<li class="waves-effect waves-light" <?php echo $request_uri=='exams.php'?'class="active"':'' ?>><a href="exams.php">Exams</a></li>
							<li class="waves-effect waves-light" <?php echo $request_uri=='questions.php'?'class="active"':'' ?>><a href="questions.php">Questions</a></li>
							<li class="waves-effect waves-light" <?php echo $request_uri=='qsets.php'?'class="active"':'' ?>><a href="qsets.php">Question sets</a></li>
							<li class="waves-effect waves-light" <?php echo $request_uri=='results.php'?'class="active"':'' ?>><a href="results.php">Results</a></li>
							<li class="waves-effect waves-light" <?php echo $request_uri=='stats.php'?'class="active"':'' ?>><a href="stats.php">Stats</a></li>
							<li class="waves-effect waves-light" <?php echo $request_uri=='change_pass.php'?'class="active"':'' ?>><a href="change_pass.php">Change Password</a></li>
							<li><a href="logout.php">Logout</a></li>
						</ul>
					</div>
				</nav>
				<?php endif; ?>
				<div class="row">
					<div class="col s10 offset-s1">