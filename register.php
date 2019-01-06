<?php
    require_once("includes/connection.php");
	$page_title="Register";
	
	if(isset($_SESSION['u'])){
		header('location:quiz.php');
		die();
	}
?>

<?php include "includes/header.php"; ?>

<?php
	if(isset($msg)){
		echo "<script>M.toast({html: '$msg'})</script>";
	}
?>

<br/>
<center><h4 class="light-font">Login</h4></center>
<form method="post" action="instructions.php" id="login-form">
	<div class="row">
		<div class="input-field col m8 offset-m2 s10 offset-s1">
			<input autocomplete="off" id="regcode" name="regcode" maxlength="15" required type="text">
			<label for="regcode">Registration Code</label>
		</div>
	</div>
	<div class="row">
		<div class="input-field col m8 offset-m2 s10 offset-s1">
			<input autocomplete="off" id="prtcpnts" name="prtcpnts" maxlength="100" type="text">
			<label for="prtcpnts">Participants (Seperate by comma)</label>
		</div>
	</div>
	<div class="row">
		<div class="input-field col m8 offset-m2 s10 offset-s1">
			<input autocomplete="off" id="institute" name="institute" maxlength="100" type="text">
			<label for="institute">Institute</label>
		</div>
	</div>
	<div class="row">
		<center><button type="submit" class="btn waves-effect waves-light">Next</button></center>
	</div>
</form>
<br/><br/>
<script>
	$('#regcode').keypress(function (e) {
		var regex = new RegExp("^[a-zA-Z0-9]+$");
		var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
		if (regex.test(str)) {
			return true;
		}
		if(e.keyCode!=13 && e.keyCode!=8){
			e.preventDefault();
			return false;
		}
	});
</script>
<?php include "includes/footer.php"; ?>