<?php
    require_once "../includes/connection.php";
	require_once "admin_login.php";
	if(isset($_POST['subm'])){
		$cpass = $conn->real_escape_string($_POST['cpass']);
		$npass = $conn->real_escape_string($_POST['npass']);
		$rpass = $conn->real_escape_string($_POST['rpass']);
		
		if($npass==$rpass){
			if($conn->query("SELECT `password` FROM `admins` WHERE `id`='$_SESSION[adm]'")->fetch_array()['password']==$cpass){
				$conn->query("UPDATE `admins` SET `password`='$npass' WHERE `id`='$_SESSION[adm]'");
				$msg = "Password changed";
			}
			else
				$msg = "Current password was found wrong";
		}
		else
			$msg = "New passwords doesn't match";
	}
?>
<?php include "header.php"; ?>

<center><h4 class="light-font">Change password</h4></center>
<br/>
<form method="post">
	<div class="input-field">
		<input type="password" id="cpass" name="cpass" required/>
		<label for="cpass">Current Password</label>
	</div>
	<div class="input-field">
		<input type="password" id="npass" name="npass" required/>
		<label for="npass">New Password</label>
	</div>
	<div class="input-field">
		<input type="password" id="rpass" name="rpass" required/>
		<label for="rpass">Repeat new Password</label>
	</div>
	<button type="submit" name="subm" class="waves-effect waves-light btn">Change</button>
</form>
<?php include "footer.php"; ?>
<br/>