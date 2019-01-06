  <?php
	$path_prefix = isset($path_prefix)?$path_prefix:'';
  ?>
  <!DOCTYPE html>
  <html>
    <head>
		<link type="text/css" rel="stylesheet" href="<?php echo $path_prefix ?>css/materialize.min.css"  media="screen,projection"/>
		<link type="text/css" rel="stylesheet" href="<?php echo $path_prefix ?>css/custom.css"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<script src="<?php echo $path_prefix ?>js/jquery.min.js"></script>
		<script type="text/javascript" src="<?php echo $path_prefix ?>js/materialize.min.js"></script>
		<title><?php echo $site_title." - ".(isset($page_title)?$page_title:"Quiz") ?></title>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
		<meta charset="UTF-8">
    </head>
    <body>
		<br/>
		<center><img src="<?php echo $path_prefix ?>img/logo.png" ondragstart="return false;" style="height:130px;"></img></center>
		<br/><br/>
		<div class="container">
			<div class="row">
				<div class="col s12 main_content">
					<div class="quiz_name_heading row">
						<div class="col s10 light-font">
							<?php echo htmlspecialchars($cexam["name"]) ?>
						</div>
						<div class="col s2 align-right light-font">
							<?php
								if(isset($_SESSION['u'])){
									echo "#".$conn->query("SELECT `regcode` FROM `registrations` WHERE `id`='$_SESSION[u]' AND `exam`='$settings[active_exam]'")->fetch_array()["regcode"];
								}
								elseif(isset($_POST['regcode'])){
									echo "#".htmlspecialchars(strtoupper($_POST['regcode']));
								}
								elseif(isset($dump_reg_code)){
									echo "#".htmlspecialchars(strtoupper($dump_reg_code));
								}
							?>
						</div>
					</div>
