<?php
    require_once "../includes/connection.php";
	require_once "admin_login.php";
    include_once "../libs/PHPExcel.php";
?>
<?php
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("Santhigiri College")
                                ->setLastModifiedBy("Santhigiri College")
                                ->setTitle("Quiz")
                                ->setSubject("Quiz Report")
                                ->setDescription("Quiz Report")
                                ->setKeywords("quiz")
                                ->setCategory("quiz");

    if(isset($_POST['exam']))
        $exam = $conn->real_escape_string($_POST['exam']);
    else
        $exam = $settings['active_exam'];

	$exam_det = $conn->query("SELECT * FROM `exams` WHERE `id`='$exam'")->fetch_array();

	$filename = $exam_det["name"]." Results - ".date('d-m-Y').".xls";

    $regs = $conn->query("SELECT *,(SELECT SUM(`score`) FROM `answers` WHERE `regid`=`registrations`.`id`) AS `scr` FROM `registrations` WHERE `exam`='$exam' ORDER BY `scr` DESC,`date_registered`-`date_completed` DESC");
	echo $conn->error;
	$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', "Registration code")
                    ->setCellValue('B1', "Participants")
                    ->setCellValue('C1', "Institute")
                    ->setCellValue('D1', "Question set")
                    ->setCellValue('E1', "Score")
                    ->setCellValue('F1', "Correct/Attended")
                    ->setCellValue('G1', "Max Score")
                    ->setCellValue('H1', "Percentage")
                    ->setCellValue('I1', "Date")
                    ->setCellValue('J1', "Time")
                    ->setCellValue('K1', "Status")
                    ->setCellValue('L1', "Time completed")
                    ->setCellValue('M1', "Time consumed");

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(false);
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth('16');
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	//$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('16');
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	//$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('16');
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(false);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('12');
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(false);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth('8');
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(false);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth('15');
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(false);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth('10');
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(false);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth('11');
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(false);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth('11');
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(false);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth('12');
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(false);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth('12');
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(false);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth('15');
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(false);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth('12');

	//Content
    $i=2;
    while($reg=$regs->fetch_array()){
        $qcode = $reg["qcode"];
        $answers = $conn->query("SELECT COUNT(*) AS `answered` FROM `answers` WHERE `regid`='$reg[id]'")->fetch_array();
        $max_score = $exam_det["nquestions"]*$exam_det['pos_mark'];

		$time_consumed = strtotime($reg["date_completed"])-strtotime($reg["date_registered"]);
		$time_consumed = $time_consumed<0?"N/A":secsToHms($time_consumed);
		
		$ncorrect = intval($reg["scr"]);
		$answered = $answers["answered"];
		
		$neg_marks = ($answered-$ncorrect)*$exam_det['neg_mark'];
		
		$score = ($ncorrect*$exam_det['pos_mark'])-$neg_marks;
		
		$percentage = $max_score>0?(($score/$max_score)*100):0;
		
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i, $reg["regcode"])
                    ->setCellValue('B'.$i, $reg["participants"])
                    ->setCellValue('C'.$i, $reg["institute"])
                    ->setCellValue('D'.$i, 'QS'.$qcode)
                    ->setCellValue('E'.$i, $score)
                    ->setCellValue('F'.$i, $ncorrect.'/'.$answered)
                    ->setCellValue('G'.$i, $max_score)
                    ->setCellValue('H'.$i, round($percentage,2)."%")
                    ->setCellValue('I'.$i, date('d/m/Y',strtotime($reg["date_registered"])))
                    ->setCellValue('J'.$i, date('h:i:s A',strtotime($reg["date_registered"])))
                    ->setCellValue('K'.$i, decodeStatus($reg['status']))
                    ->setCellValue('L'.$i, $reg["date_completed"]=="0000-00-00 00:00:00"?"N/A":date('h:i:s A',strtotime($reg["date_completed"])))
                    ->setCellValue('M'.$i, $time_consumed);
        $i++;
    }

	function decodeStatus($status){
		switch($status){
			case 0:
				return "Ongoing";
			case 1:
				return "Completed";
			case -1:
				return "Banned";
		}
	}

	function secsToHms($seconds) {
		$t = round($seconds);
		return sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60);
	}

  $objPHPExcel->setActiveSheetIndex(0);

  $objPHPExcel->getActiveSheet()->setTitle('Results - '.$exam_det["name"]);
  $objPHPExcel->setActiveSheetIndex(0);
  header('Content-Type: application/vnd.ms-excel');
  header('Content-Disposition: attachment;filename="'.$filename.'"');
  header('Cache-Control: max-age=0');
  header('Cache-Control: max-age=1');
  header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
  header ('Cache-Control: cache, must-revalidate');
  header ('Pragma: public');
  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
  $objWriter->save('php://output');
  exit;

?>
