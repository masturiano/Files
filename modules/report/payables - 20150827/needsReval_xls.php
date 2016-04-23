<?
################### INCLUDE FILE #################
	session_start();
	ini_set('include_path','C:\wamp\php\PEAR');
	include("../../../includes/db.inc.php");
	include("../../../includes/common.php");
	include("needsRevalObj.php");
	require_once 'Spreadsheet/Excel/Writer.php';
	
	$needsRevalObj = new needsRevalObj();
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
	
	$filename = "Needs Revalidation.xls";
	$workbook->send($filename);
	$worksheet = &$workbook->addWorksheet("Needs Revalidation");
	$worksheet->setLandscape();
	$worksheet->freezePanes(array(3,0));
	
	$worksheet->write(0,0,"Needs Revalidation From ".date('m/d/Y',strtotime($_GET['txtDateFrom']))." to Present",$headerFormat);
	//$worksheet->write(6,0,"Month Range: ".$printMonthFrom.$monthYearFrom." - ".$printMonthTo.$monthYearTo,$headerFormat);
	
	for($i=1;$i<10;$i++) {
		$worksheet->write(0, $i, "",$headerFormat);	
	}
	$worksheet->setColumn(0,0,20);
	$worksheet->setColumn(1,1,20);
	$worksheet->setColumn(2,2,20);
	$worksheet->setColumn(3,3,20);
	$worksheet->setColumn(4,4,20);
	$worksheet->setColumn(5,5,20);
	$worksheet->setColumn(6,6,20);
	$worksheet->setColumn(7,7,30);
	$worksheet->setColumn(8,8,20);
	$worksheet->setColumn(9,9,20);
	
	$worksheet->write(1,0, "".$pMode,$headerFormat);
	
	$worksheet->write(2,0,"ORG ID",$headerFormat);
	$worksheet->write(2,1,"VENDOR NUM",$headerFormat);
	$worksheet->write(2,2,"INVOICE NUM",$headerFormat);
	$worksheet->write(2,3,"SOURCE",$headerFormat);
	$worksheet->write(2,4,"INVOICE DATE",$headerFormat);
	$worksheet->write(2,5,"CREATION DATE",$headerFormat);
	$worksheet->write(2,6,"INVOICE AMOUNT",$headerFormat);
	$worksheet->write(2,7,"BATCH NAME",$headerFormat);
	$worksheet->write(2,8,"FILENAME",$headerFormat);
	$worksheet->write(2,9,"VENDOR SITE CODE",$headerFormat);
	
	
		$ctr = 2;
		
		$row1 = ($col==0) ? $Deptc1:$Deptc;
		$row2 = ($col==0) ? $detail2:$detail2;
		$col = ($col==0) ? 1:0;
		
		$arrDailyPaidProc = $needsRevalObj->neverValDidNotPasXls($_GET['txtDateFrom'],$_GET['cmbOrgId']);
		foreach ($arrDailyPaidProc as $valD) {
			
			$ctr++;	
			if($valD['Org_id'] == 85){$company = "PPCI";}
			if($valD['Org_id'] == 87){$company = "JR";}
			if($valD['Org_id'] == 133){$company = "Puregold Subic";}
			
			$worksheet->write($ctr,0,$company,$row1);
			$worksheet->write($ctr,1,$valD['Segment1'],$row1);
			$worksheet->write($ctr,2," ".$valD['Invoice_Num'],$row1);
			$worksheet->write($ctr,3,$valD['Source'],$row1);
			$worksheet->write($ctr,4,date('m/d/Y',strtotime($valD['Invoice_Date'])),$row1);
			$worksheet->write($ctr,5,date('m/d/Y',strtotime($valD['Creation_Date'])),$row1);
			$worksheet->write($ctr,6,$valD['Invoice_Amount'],$row2);
			$worksheet->write($ctr,7,$valD['Batch_Name'],$row2);
			$worksheet->write($ctr,8,$valD['Attribute13'],$row2);
			$worksheet->write($ctr,9,$valD['Vendor_Site_Code'],$row2);
			
			
			//$totInvAmt += $valD['INVOICE_AMOUNT'];
			//$totPdAmt += $valD['PAIDAMT'];
		}
		$ctr++;	
		//$worksheet->write($ctr,5,"TOTAL:",$headerFormat2);
		//$worksheet->write($ctr,6,$totInvAmt,$headerFormat2);
		//$worksheet->write($ctr,7,$totPdAmt,$headerFormat2);
		//$worksheet->write($ctr,7,$totAmtDueRemain,$headerFormat2);
		


			
$workbook->close();
?>