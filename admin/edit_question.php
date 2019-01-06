<?php
    require_once "../includes/connection.php";
    require_once "admin_login.php";
	
    if(isset($_GET['q']) && isset($_GET['act'])){
        $q = $conn->real_escape_string($_GET['q']);
        if($_GET['act']=="del"){
            $conn->query("DELETE FROM `question_pool` WHERE `id`='$q'");
			$conn->query("DELETE FROM `question_sets` WHERE `question`='$q'");
            header("location:questions.php?msg=".base64_encode("Question deleted!"));
        }
        else{
            $qn = $conn->query("SELECT * FROM `question_pool` WHERE `id`='$q'")->fetch_array();
            if(isset($_POST['subm'])){
                $qstn = $conn->real_escape_string($_POST['question']);
                $opts = $_POST["opt"];
                foreach($opts as &$opt){
                    $opt = $conn->real_escape_string($opt);
                }
                $ro = $conn->real_escape_string($_POST["ro"]);
                $conn->query("UPDATE `question_pool` SET `question`='$qstn', `opt_1`='$opts[0]', `opt_2`='$opts[1]', `opt_3`='$opts[2]', `opt_4`='$opts[3]',`right_opt`='$ro' WHERE `id`='$q'");
                header("location:questions.php?msg=".base64_encode("Changes saved!"));
            }
        }
    }
    else
        header("location:questions.php");
?>

<?php include "header.php"; ?>

<center><h4 class="light-font">Edit Question</h4></center>

<form method="post">
	<div class="row">
		<div class="col s12 input-field">
			<textarea name="question"  id="question" class="materialize-textarea" data-length="65535"><?php echo htmlspecialchars($qn["question"]) ?></textarea>
			<label for="question">Question</label>
		</div>
	</div>
	<p>Mark the right option using the radio button</p>
    <?php for($i=1;$i<=4;$i++): ?>
	
	<div class="row">
		<div class="col s1 input-field">
			<label>
				<input type="radio" name="ro" value="<?php echo $i ?>" <?php echo ($qn["right_opt"]==$i)?"checked":"" ?>/>
				<span></span>
			</label>
		</div>
		<div class="col s11 input-field">
			<input type="text" id="opt_<?php echo $i ?>" name="opt[<?php echo $i-1 ?>]" value="<?php echo $qn["opt_".$i] ?>" value="<?php echo $i ?>" required/>
			<label for="opt_<?php echo $i ?>">Option <?php echo $i ?></label>
		</div>
	</div>
    <?php endfor; ?>
    <button type="submit" name="subm" class="waves-effect waves-light btn">Save</button>
</form>

<?php include "footer.php"; ?>