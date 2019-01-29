<?php
	/**
	 * admin/login.php
	 *
	 * Login page for the admin panel
	 *
	 * @author     Ajith Gopi
	 * @copyright  2019, Ajith Gopi
	 * @license    https://github.com/ajithgopi/mcq-quiz/blob/master/LICENSE  BSD 3-Clause License
	 *
	 * DO NOT REMOVE THIS COPYRIGHT INFORMATION WITHOUT PERMISSION. YOU WILL BE VIOLATING THE LICENSE
	 * AGGREMENT WHEN YOU DO SO. (according to https://github.com/ajithgopi/mcq-quiz/blob/master/LICENSE).
	 */
	 
    require_once "../includes/connection.php";
	if(isset($_SESSION['adm']) && $_SESSION['adm']==true){
		header("location:index.php");
	}
	if(isset($_POST['username']) && isset($_POST['password'])){
		$username = $conn->real_escape_string($_POST['username']);
		$password = $conn->real_escape_string($_POST['password']);
		
		$login = $conn->query("SELECT * FROM `admins` WHERE `username`='$username' AND `password`='$password'");
		if($login->num_rows>0){
			$_SESSION['adm'] = $login->fetch_array()['id'];
			header("location:index.php");
		}
		else{
			$msg= "Invalid username/password!";
		}
	}
?>
<?php include "header.php"; ?>
<center><h4 class="light-font">Login</h4></center>
<br/>

<form method="post">
	<div class="input-field">
		<input type="text" id="username" name="username" required/>
		<label for="name">Username</label>
	</div>
	<div class="input-field">
		<input type="password" id="password" name="password" required/>
		<label for="password">Password</label>
	</div>
	<button type="submit" class="waves-effect waves-light btn">Login</button>
</form>
<?php include "footer.php"; ?>