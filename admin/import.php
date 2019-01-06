<?php
    require_once "../includes/connection.php";
	require_once "admin_login.php";
    include_once "../libs/PHPExcel.php";
?>
<?php
    if(isset($_POST['subm'])){
        $tmpfname = $_FILES['qsheet']['tmp_name'];
        $excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
        $excelObj = $excelReader->load($tmpfname);
        $worksheet = $excelObj->getSheet(0);
        $lastRow = $worksheet->getHighestRow();
		
		$ignored=0;
		
        for ($row = 1; $row <= $lastRow; $row++) {
			if($worksheet->getCell('A'.$row)!=''){
				$question = $conn->real_escape_string(trim($worksheet->getCell('A'.$row)->getValue()));
				$opt_1 = $conn->real_escape_string($worksheet->getCell('B'.$row)->getValue());
				$opt_2 = $conn->real_escape_string($worksheet->getCell('C'.$row)->getValue());
				$opt_3 = $conn->real_escape_string($worksheet->getCell('D'.$row)->getValue());
				$opt_4 = $conn->real_escape_string($worksheet->getCell('E'.$row)->getValue());
				$ro = $conn->real_escape_string($worksheet->getCell('F'.$row)->getValue());
				
				$ascii = ord(strtoupper($ro));
				if($ascii>=65 && $ascii<=68)
					$ro=$ascii-64;
				if($conn->query("SELECT COUNT(*) AS `cnt` FROM `question_pool` WHERE `exam`='$settings[active_exam]' && `question`='$question'")->fetch_array()["cnt"]==0)
					$conn->query("INSERT INTO `question_pool` (`exam`,`question`, `opt_1`, `opt_2`, `opt_3`, `opt_4`, `right_opt`) VALUES('$settings[active_exam]','$question','$opt_1','$opt_2','$opt_3','$opt_4',$ro)");
				else
					$ignored++;
			}
			else
				$ignored++;
        }
		$msg = ($lastRow-$ignored)."/".$lastRow." questions imported!";
    }   
?>

<?php include "header.php"; ?>
<center><h4 class="light-font">Import questions</h4></center>
<br/>

<form method="post" enctype="multipart/form-data">
	<div class="file-field input-field">
		<div class="waves-effect waves-light btn">
			<span>Upload excel file</span>
			<input type="file" name="qsheet">
		</div>
			<div class="file-path-wrapper">
			<input readonly class="file-path validate" type="text">
		</div>
	</div>
    <button type="submit" name="subm" class="waves-effect waves-light btn" style="float:right">Import</button>
</form>
<br/>
<br/>
<br/>
<h5>Demo excel file: <a href="../demo/demo.xlsx" class="btn waves-effect waves-light">Download</a></h5>
<img style="width:100%" src="../img/demo_excel.jpg">
<?php include "footer.php"; ?>