<?
################### INCLUDE FILE #################
	session_start();
	ini_set('include_path','C:\wamp\php\PEAR');
	include("../../../includes/db.inc.php");
	include("../../../includes/common.php");
	include("userResponsibilityObj.php");
	require_once 'Spreadsheet/Excel/Writer.php';
	
	$userResponsibilityObj = new userResponsibilityObj();
	$workbook = new Spreadsheet_Excel_Writer();
	$headerFormat = $workbook->addFormat(array('Size' => 11,
                                      'Color' => 'black',
                                      'bold'=> 1,
									  'border' => 1,
									  'Align' => 'merge'));
	$headerFormat->setFontFamily('Calibri'); 
	
	$headerFormat2 = $workbook->addFormat(array('Size' => 11,
                                      'Color' => 'black',
                                      'bold'=> 1,
									  'border' => 1,
									  'Align' => 'center'));
	$headerFormat2->setFontFamily('Calibri'); 
	$headerFormat2->setNumFormat('#,##0.00');
	
	$headerBorder    = $workbook->addFormat(array('Size' => 10,
                                      'Color' => 'black',
                                      'bold'=> 1,
									  'border' => 1,
									  'Align' => 'merge'));
									  
	$headerBorder->setFontFamily('Calibri'); 
	$workbook->setCustomColor(13,155,205,255);
	$TotalBorder    = $workbook->addFormat(array('Align' => 'right','bold'=> 1,'border'=>1,'fgColor' => 'white'));
	$TotalBorder->setFontFamily('Calibri'); 
	$TotalBorder->setTop(5); 
	$detailrBorder   = $workbook->addFormat(array('border' =>1,'Align' => 'right'));
	$detailrBorder->setFontFamily('Calibri'); 
	$detailrBorderAlignRight2   = $workbook->addFormat(array('Align' => 'left'));
	$detailrBorderAlignRight2->setFontFamily('Calibri');
	$workbook->setCustomColor(12,183,219,255);
	
	
	
	$Deptc   = $workbook->addFormat(array('Size' => 10,
										  'fgColor' => 'white',
										  'Pattern' => 1,
										  'border' =>1,
										  'Align' => 'center'));
	$Deptc->setFontFamily('Calibri'); 
	
	$Deptc1   = $workbook->addFormat(array('Size' => 10,
										  'border' =>1,
										  'Pattern' => 1,
										  'Align' => 'center'));
	$Deptc1->setFgColor(12); 
	$Deptc1->setFontFamily('Calibri');
	
	$detail   = $workbook->addFormat(array('Size' => 10,
										  'fgColor' => 'white',
										  'Pattern' => 1,
										  'border' =>1,
										  'Align' => 'left'));
	$detail->setFontFamily('Calibri'); 

	$detail2   = $workbook->addFormat(array('Size' => 10,
										  'border' =>1,
										  'Pattern' => 1,
										  'Align' => 'right'));
	$detail2->setFgColor(12); 
	$detail2->setFontFamily('Calibri'); 
	$detail2->setNumFormat('#,##0.00');
	
	$total = $workbook->addFormat(array('Size' => 10,
										'Color' => 'black',
										'bold'=> 1,
										'border' =>1,
										'Pattern' => 1,
										'Align' => 'center'));
	$total->setFgColor(12); 
	$total->setFontFamily('Calibri'); 
	$total->setNumFormat('#,##0.00');
	
	$filename = "Oracle User Monitoring Report.xls";
	$workbook->send($filename);
	$worksheet = &$workbook->addWorksheet("Oracle User Monitoring Report");
	$worksheet->setLandscape();
	$worksheet->freezePanes(array(3,0));
	
	$worksheet->write(0,0,"User responsibilty and Forms acessed From ".date('m/d/Y',strtotime($_GET['txtDateFrom']))." to ".date('m/d/Y',strtotime($_GET['txtDateTo'])),$headerFormat);
	//$worksheet->write(6,0,"Month Range: ".$printMonthFrom.$monthYearFrom." - ".$printMonthTo.$monthYearTo,$headerFormat);
	
	for($i=1;$i<8;$i++) {
		$worksheet->write(0, $i, "",$headerFormat);	
	}
	$worksheet->setColumn(0,0,10);
	$worksheet->setColumn(1,1,20);
    $worksheet->setColumn(2,2,60);
    $worksheet->setColumn(3,3,20);
	$worksheet->setColumn(4,4,20);
	$worksheet->setColumn(5,5,20);
	$worksheet->setColumn(6,6,35);
	$worksheet->setColumn(7,7,35);
	$worksheet->setColumn(8,8,20);
	$worksheet->setColumn(9,9,20);
	$worksheet->setColumn(10,10,20);
	$worksheet->setColumn(11,11,20);
    $worksheet->setColumn(12,12,20);
    $worksheet->setColumn(13,13,20);
    $worksheet->setColumn(14,14,20);
    $worksheet->setColumn(15,15,20);
    $worksheet->setColumn(16,16,20);
	$worksheet->setColumn(17,17,20);
	
	$worksheet->write(1,0, "".$pMode,$headerFormat);
	
	$worksheet->write(2,0,"USER ID",$headerFormat);
	$worksheet->write(2,1,"USER NAME",$headerFormat);
	$worksheet->write(2,2,"DESCRIPTION",$headerFormat);
    $worksheet->write(2,3,"START TIME",$headerFormat);
    $worksheet->write(2,4,"END TIME",$headerFormat);
	$worksheet->write(2,5,"RESPONSIBILITY ID",$headerFormat);
	$worksheet->write(2,6,"RESPONSIBILITY NAME",$headerFormat);
	$worksheet->write(2,7,"USER FORM NAME",$headerFormat);
	
		$ctr = 2;
		
		$row1 = ($col==0) ? $Deptc1:$Deptc;
		$row2 = ($col==0) ? $detail2:$detail2;
		$col = ($col==0) ? 1:0;
		
		$arrLeasingSetup = $userResponsibilityObj->leasingSetup($_GET['txtDateFrom'],$_GET['txtDateTo'],$_GET['formName']);
		foreach ($arrLeasingSetup as $valD) {
			
			$ctr++;	
			$worksheet->write($ctr,0,$valD['USER_ID'],$row1);
            $worksheet->write($ctr,1,$valD['USER_NAME'],$row1);
            $worksheet->write($ctr,2,$valD['DESCRIPTION'],$row1);
            $dateFrom = (date('m/d/Y',strtotime($valD['START_TIME']))=='01/01/1970') ? '':date('m/d/Y',strtotime($valD['START_TIME']));
            $worksheet->write($ctr,3,$dateFrom,$row1);
            $dateTo = (date('m/d/Y',strtotime($valD['END_TIME']))=='01/01/1970') ? '':date('m/d/Y',strtotime($valD['END_TIME']));
            $worksheet->write($ctr,4,$dateTo,$row1);
            $worksheet->write($ctr,5,$valD['RESPONSIBILITY_ID'],$row1);
            $worksheet->write($ctr,6,$valD['RESPONSIBILITY_NAME'],$row1);
            $worksheet->write($ctr,7,$valD['USER_FORM_NAME'],$row1);
		} 
			
$workbook->close();
?>