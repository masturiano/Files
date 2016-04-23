<?
################### INCLUDE FILE #################
	session_start();
	ini_set('include_path','C:\wamp\php\PEAR');
	include("../../../includes/db.inc.php");
	include("../../../includes/common.php");
	include("concessObj.php");
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
	
	$filename = "Concess_Posted.xls";
	$workbook->send($filename);
	$worksheet = &$workbook->addWorksheet("Concess Posted");
	$worksheet->setLandscape();
	$worksheet->freezePanes(array(3,0));
	
	$worksheet->write(0,0,"Concess Posted Report From ".date('m/d/Y',strtotime($_GET['txtDateFrom']))." to ".date('m/d/Y',strtotime($_GET['txtDateTo'])),$headerFormat);
	//$worksheet->write(6,0,"Month Range: ".$printMonthFrom.$monthYearFrom." - ".$printMonthTo.$monthYearTo,$headerFormat);
	
	for($i=1;$i<11;$i++) {
		$worksheet->write(0, $i, "",$headerFormat);	
	}
	$worksheet->setColumn(0,0,20);
	$worksheet->setColumn(1,1,30);
    $worksheet->setColumn(2,2,20);
    $worksheet->setColumn(3,3,30);
	$worksheet->setColumn(4,4,20);
	$worksheet->setColumn(5,5,20);
	$worksheet->setColumn(6,6,20);
	$worksheet->setColumn(7,7,20);
	$worksheet->setColumn(8,8,20);
	$worksheet->setColumn(9,9,20);
	$worksheet->setColumn(10,10,30);
	$worksheet->setColumn(11,11,20);
    $worksheet->setColumn(12,12,20);
    $worksheet->setColumn(13,13,20);
    $worksheet->setColumn(14,14,20);
    $worksheet->setColumn(15,15,20);
    $worksheet->setColumn(16,16,20);
	$worksheet->setColumn(17,17,20);
	
	$worksheet->write(1,0, "".$pMode,$headerFormat);
	
    $worksheet->write(2,0,"STORE CODE",$headerFormat);
    $worksheet->write(2,1,"STORE NAME",$headerFormat);
    $worksheet->write(2,2,"SUPPLIER CODE",$headerFormat);
    $worksheet->write(2,3,"SUPPLIER NAME",$headerFormat);
	$worksheet->write(2,4,"INV NO",$headerFormat);
	$worksheet->write(2,5,"INV DATE",$headerFormat);
    $worksheet->write(2,6,"DUE DATE",$headerFormat);
	$worksheet->write(2,7,"VOUCHED DATE",$headerFormat);
	$worksheet->write(2,8,"INV AMT",$headerFormat);
    $worksheet->write(2,9,"UPLOADED AMT",$headerFormat);
	$worksheet->write(2,10,"ORACLE CREATION DATE",$headerFormat);
	
		$ctr = 2;
		
		$row1 = ($col==0) ? $Deptc1:$Deptc;
		$row2 = ($col==0) ? $detail2:$detail2;
		$col = ($col==0) ? 1:0;
		
		$arrLeasingSetup = $leasingSetupObj->viewCon($_GET['txtDateFrom'],$_GET['txtDateTo'],$_GET['supplierCode'],$_GET['cmbOrgId'],$_GET['storeNum']);
		foreach ($arrLeasingSetup as $valD) {
			
			$ctr++;	
            //$worksheet->write($ctr,0,$valD['Col001'],$row1);
            //$worksheet->write($ctr,1,$valD['Col004'],$row1);
            
            
            
			if($valD['ORG_ID'] == 85){$company = "PPCI";}
			if($valD['ORG_ID'] == 87){$company = "JR";}
			if($valD['ORG_ID'] == 133){$company = "Puregold Subic";}
			$worksheet->write($ctr,0,$company,$row1);
            
            $rowStoreInfo = $leasingSetupObj->findStore2($valD['strCode']);
            $worksheet->write($ctr,0,$rowStoreInfo['strnum'],$row1);
            $worksheet->write($ctr,1,$rowStoreInfo['strnam'],$row1);
            $worksheet->write($ctr,2,$valD['suppCode'],$row1);
            $rowStoreInfo3 = $leasingSetupObj->findSuppName($valD['suppCode']);
            $worksheet->write($ctr,3,$rowStoreInfo3['suppName'],$row1);
            $worksheet->write($ctr,4," ".$valD['invNo'],$row1);
            $worksheet->write($ctr,5,date('m/d/Y',strtotime($valD['invDate'])),$row1);
            $worksheet->write($ctr,6,date('m/d/Y',strtotime($valD['dueDate'])),$row1);
            $worksheet->write($ctr,7,date('m/d/Y',strtotime($valD['vouchedDate'])),$row1);
            $worksheet->write($ctr,8,$valD['invAmt'],$row2);
            $worksheet->write($ctr,9,$valD['uploadedAmt'],$row2);
            $rowStoreInfo2 = $leasingSetupObj->findCreationDate($valD['invNo'],$valD['suppCode']);
            if($rowStoreInfo2['creation_date'] != ''){
                $worksheet->write($ctr,10,date('m/d/Y',strtotime($rowStoreInfo2['creation_date'])),$row1);
            }else{                                
                $worksheet->write($ctr,10,'Not yet loaded',$row1);
            }
            
        
			$totInvAmt += $valD['invAmt'];
			$totUplAmt += $valD['uploadedAmt'];
		}
		$ctr++;	
		$worksheet->write($ctr,7,"TOTAL:",$headerFormat2);
		$worksheet->write($ctr,8,$totInvAmt,$headerFormat2);
		$worksheet->write($ctr,9,$totUplAmt,$headerFormat2);
		


			
$workbook->close();
?>