<?
################### INCLUDE FILE #################
	session_start();
	ini_set('include_path','C:\wamp\php\PEAR');
	include("../../../includes/db.inc.php");
	include("../../../includes/common.php");
	include("leasingSetupObj.php");
	require_once 'Spreadsheet/Excel/Writer.php';
	
	$leasingSetupObj = new leasingSetupObj();
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
	
	$filename = "Leasing_Setup.xls";
	$workbook->send($filename);
	$worksheet = &$workbook->addWorksheet("Leasing Setup");
	$worksheet->setLandscape();
	$worksheet->freezePanes(array(3,0));
	
	$worksheet->write(0,0,"Leasing Setup Report From ".date('m/d/Y',strtotime($_GET['txtDateFrom']))." to ".date('m/d/Y',strtotime($_GET['txtDateTo'])),$headerFormat);
	//$worksheet->write(6,0,"Month Range: ".$printMonthFrom.$monthYearFrom." - ".$printMonthTo.$monthYearTo,$headerFormat);
	
	for($i=1;$i<18;$i++) {
		$worksheet->write(0, $i, "",$headerFormat);	
	}
	$worksheet->setColumn(0,0,10);
	$worksheet->setColumn(1,1,11);
    $worksheet->setColumn(2,2,40);
    $worksheet->setColumn(3,3,15);
	$worksheet->setColumn(4,4,30);
	$worksheet->setColumn(5,5,15);
	$worksheet->setColumn(6,6,33);
	$worksheet->setColumn(7,7,15);
	$worksheet->setColumn(8,8,25);
	$worksheet->setColumn(9,9,25);
	$worksheet->setColumn(10,10,10);
	$worksheet->setColumn(11,11,15);
    $worksheet->setColumn(12,12,15);
    $worksheet->setColumn(13,13,15);
    $worksheet->setColumn(14,14,25);
    $worksheet->setColumn(15,15,15);
    $worksheet->setColumn(16,16,15);
	$worksheet->setColumn(17,17,50);
	
	$worksheet->write(1,0, "".$pMode,$headerFormat);
	
	$worksheet->write(2,0,"ORG ID",$headerFormat);
	$worksheet->write(2,1,"ACCOUNT #",$headerFormat);
	$worksheet->write(2,2,"ACCOUNT NAME",$headerFormat);
    $worksheet->write(2,3,"STORE CODE",$headerFormat);
    $worksheet->write(2,4,"STORE NAME",$headerFormat);
	$worksheet->write(2,5,"LOCATION",$headerFormat);
	$worksheet->write(2,6,"TRANS #",$headerFormat);
	$worksheet->write(2,7,"TRANS DATE",$headerFormat);
	$worksheet->write(2,8,"AMTOUNT DUE ORIGINAL",$headerFormat);
	$worksheet->write(2,9,"AMOUNT DUE REMAINING",$headerFormat);
	$worksheet->write(2,10,"CLASS",$headerFormat);
	$worksheet->write(2,11,"CLASS NAME",$headerFormat);
    $worksheet->write(2,12,"CREATION DATE",$headerFormat);  
    $worksheet->write(2,13,"LEAS NUM",$headerFormat);
    $worksheet->write(2,14,"PAYMENT PURPOSE CODE",$headerFormat);  
    $worksheet->write(2,15,"START DATE",$headerFormat);
    $worksheet->write(2,16,"END DATE",$headerFormat);
    $worksheet->write(2,17,"TERM COMMENTS",$headerFormat);
	
		$ctr = 2;
		
		$row1 = ($col==0) ? $Deptc1:$Deptc;
		$row2 = ($col==0) ? $detail2:$detail2;
		$col = ($col==0) ? 1:0;
		
		$arrLeasingSetup = $leasingSetupObj->leasingSetup($_GET['txtDateFrom'],$_GET['txtDateTo'],$_GET['tagsid'],$_GET['cmbOrgId'],$_GET['storeShort']);
		foreach ($arrLeasingSetup as $valD) {
			
			$ctr++;	
			if($valD['ORG_ID'] == 85){$company = "PPCI";}
			if($valD['ORG_ID'] == 87){$company = "JR";}
			if($valD['ORG_ID'] == 133){$company = "Puregold Subic";}
			$worksheet->write($ctr,0,$company,$row1);
			$worksheet->write($ctr,1,$valD['ACCOUNT_NUMBER'],$row1);
			$worksheet->write($ctr,2,$valD['ACCOUNT_NAME'],$row1);
            
            $rowStoreInfo = $leasingSetupObj->findStore($valD['LOCATION']);
            $worksheet->write($ctr,3,$rowStoreInfo['strnum'],$row1);
            $worksheet->write($ctr,4,$rowStoreInfo['strnam'],$row1);
            
			$worksheet->write($ctr,5,$valD['LOCATION'],$row1);
			$worksheet->write($ctr,6,"Trans #:  ".$valD['TRX_NUMBER'],$row1);
			$worksheet->write($ctr,7,date('m/d/Y',strtotime($valD['TRX_DATE'])),$row1);
			$worksheet->write($ctr,8,$valD['AMOUNT_DUE_ORIGINAL'],$row2);
			$worksheet->write($ctr,9,$valD['AMOUNT_DUE_REMAINING'],$row2);
			$worksheet->write($ctr,10,$valD['CLASS'],$row1);
			$worksheet->write($ctr,11,$valD['NAME'],$row1);
			$worksheet->write($ctr,12,date('m/d/Y',strtotime($valD['CREATION_DATE'])),$row1);
            
            if(($valD['LEAS_ID'] != '' && is_numeric($valD['LEAS_ID'])) && $valD['PAY_PUR_CODE'] != '' && $valD['PAY_PUR_CODE'] == 'BASE RENT'){
                $rowStoreInfo2 = $leasingSetupObj->findComments($valD['LEAS_ID'],$valD['PAY_PUR_CODE']);
                foreach ($rowStoreInfo2 as $valD2){ 
                $worksheet->write($ctr,13,$valD['LEAS_ID'],$row1);
                $worksheet->write($ctr,14,$valD2['payment_purpose_code'],$row1);
                $worksheet->write($ctr,15,date('m/d/Y',strtotime($valD2['start_date'])),$row1);
                $worksheet->write($ctr,16,date('m/d/Y',strtotime($valD2['end_date'])),$row1);
                $worksheet->write($ctr,17,$valD2['term_comments'],$row1);
                $ctr++;
                }
                $ctr--;
            }else{    
                $worksheet->write($ctr,13,'',$row1);
                $worksheet->write($ctr,14,'',$row1);
                $worksheet->write($ctr,15,'',$row1);
                $worksheet->write($ctr,16,'',$row1);
                $worksheet->write($ctr,17,'',$row1);
            }   
			$totAmtDueOrig += $valD['AMOUNT_DUE_ORIGINAL'];
			$totAmtDueRemain += $valD['AMOUNT_DUE_REMAINING'];
		} 
        $ctr++;
		$worksheet->write($ctr,7,"TOTAL:",$headerFormat2);
		$worksheet->write($ctr,8,$totAmtDueOrig,$headerFormat2);
		$worksheet->write($ctr,9,$totAmtDueRemain,$headerFormat2);
		


			
$workbook->close();
?>