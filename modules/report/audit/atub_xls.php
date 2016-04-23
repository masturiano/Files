<?
################### INCLUDE FILE #################
	session_start();
	ini_set('include_path','C:\wamp\php\PEAR');
	include("../../../includes/db.inc.php");
	include("../../../includes/common.php");
	include("atubObj.php");
	require_once 'Spreadsheet/Excel/Writer.php';
	
	$leasingSetupObj = new atubObj();
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
    
    $detail3   = $workbook->addFormat(array('Size' => 10,
                                          'border' =>1,
                                          'Pattern' => 1,
                                          'Align' => 'left'));
    $detail3->setFgColor(12); 
    $detail3->setFontFamily('Calibri'); 
    $detail3->setNumFormat('#,##0.00');
    
    # Detail left 2 
    $detailLeftRed2   = $workbook->addFormat(array('Size' => 10,
                                          'fgColor' => 'blue',
                                          'Color' => 'red',
                                          'border' =>1,
                                          'Pattern' => 1,
                                          'Align' => 'center'));
    $detailLeftRed2->setFgColor(12); 
    $detailLeftRed2->setFontFamily('Calibri'); 
	
	$total = $workbook->addFormat(array('Size' => 10,
										'Color' => 'black',
										'bold'=> 1,
										'border' =>1,
										'Pattern' => 1,
										'Align' => 'center'));
	$total->setFgColor(12); 
	$total->setFontFamily('Calibri'); 
	$total->setNumFormat('#,##0.00');
	
	$filename = "ATUB_Posted.xls";
	$workbook->send($filename);
	$worksheet = &$workbook->addWorksheet("ATUB Posted");
	$worksheet->setLandscape();
	$worksheet->freezePanes(array(3,0));
	
	$worksheet->write(0,0,"ATUB Posted Report From ".date('m/d/Y',strtotime($_GET['txtDateFrom']))." to ".date('m/d/Y',strtotime($_GET['txtDateTo'])),$headerFormat);
	//$worksheet->write(6,0,"Month Range: ".$printMonthFrom.$monthYearFrom." - ".$printMonthTo.$monthYearTo,$headerFormat);
	
	for($i=1;$i<11;$i++) {
		$worksheet->write(0, $i, "",$headerFormat);	
	}
	$worksheet->setColumn(0,0,18);
	$worksheet->setColumn(1,1,15);
    $worksheet->setColumn(2,2,15);
    $worksheet->setColumn(3,3,15);
	$worksheet->setColumn(4,4,18);
	$worksheet->setColumn(5,5,15);
	$worksheet->setColumn(6,6,20);
	$worksheet->setColumn(7,7,15);
	$worksheet->setColumn(8,8,25);
	$worksheet->setColumn(9,9,26);
	$worksheet->setColumn(10,10,50);
    
    if($_GET['cmbOrgId'] == 85){$company = "PPCI";}
    if($_GET['cmbOrgId'] == 87){$company = "JR";}
    if($_GET['cmbOrgId'] == 133){$company = "Puregold Subic";}

    $worksheet->write(1,0,"COMPANY: ".$company,$headerFormat);
	
    $worksheet->write(2,0,"TRANSACTION NO.",$headerFormat);
    $worksheet->write(2,1,"POSTED DATE",$headerFormat);
    $worksheet->write(2,2,"PERIOD FROM",$headerFormat);
    $worksheet->write(2,3,"PERIOD TO",$headerFormat);
	$worksheet->write(2,4,"AMOUNT",$headerFormat);
	$worksheet->write(2,5,"CUSTOMER NO.",$headerFormat);
    $worksheet->write(2,6,"CUSTOMER NAME",$headerFormat);
	$worksheet->write(2,7,"STORE CODE",$headerFormat);
	$worksheet->write(2,8,"ORACLE CREATION DATE",$headerFormat);
    $worksheet->write(2,9,"ORACLE ACCOUNT NUMBER",$headerFormat);
	$worksheet->write(2,10,"ORACLE DESCRIPTION",$headerFormat);
                
		$ctr = 2;
		
		$row1 = ($col==0) ? $Deptc1:$Deptc;
        $row2 = ($col==0) ? $detail2:$detail2;
		$row3 = ($col==0) ? $detail3:$detail3;
		$col = ($col==0) ? 1:0;
		
		$arrLeasingSetup = $leasingSetupObj->viewAtub($_GET['txtDateFrom'],$_GET['txtDateTo'],$_GET['dispCusName'],$_GET['cmbOrgId'],$_GET['storeNum']);
		foreach ($arrLeasingSetup as $valD) {
			
			$ctr++;	
            //$worksheet->write($ctr,0,$valD['Col001'],$row1);
            //$worksheet->write($ctr,1,$valD['Col004'],$row1);
            
			
			//$worksheet->write($ctr,0,$company,$row1);
            
            //$rowStoreInfo = $leasingSetupObj->findStore2($valD['strCode']);
            //$worksheet->write($ctr,0,$rowStoreInfo['strnum'],$row1);
            //$worksheet->write($ctr,1,$rowStoreInfo['strnam'],$row1);
            $worksheet->write($ctr,0,$valD['invoice'],$row3);
            $worksheet->write($ctr,1,date('m/d/Y',strtotime($valD['mmrbs_dateposted'])),$row1);
            $worksheet->write($ctr,2,date('m/d/Y',strtotime($valD['mmrbs_period_from'])),$row1);
            $worksheet->write($ctr,3,date('m/d/Y',strtotime($valD['mmrbs_period_to'])),$row1);
            $worksheet->write($ctr,4,$valD['mmrbs_dtl_total'],$row2);
            $worksheet->write($ctr,5,$valD['mmrbs_dtl_tcode'],$row1);
            $worksheet->write($ctr,6,$valD['mmrbs_dtl_tname'],$row3);
            $worksheet->write($ctr,7,$valD['mmrbs_dtl_strcode'],$row1);
            $rowStoreInfo2 = $leasingSetupObj->findCreationDate($valD['invoice'],$valD['mmrbs_dtl_tcode'],$_GET['cmbOrgId']);
            if($rowStoreInfo2['creation_date'] == '' || $rowStoreInfo2['creation_date'] == '01/01/1970'){
                $worksheet->write($ctr,8,'Not yet loaded',$detailLeftRed2);
            }else{                                
                $worksheet->write($ctr,8,date('m/d/Y',strtotime($rowStoreInfo2['creation_date'])),$row1);
            }
            $worksheet->write($ctr,9,$rowStoreInfo2['account_number'],$row1);
            $worksheet->write($ctr,10,$rowStoreInfo2['description'],$row3);
            //$rowStoreInfo3 = $leasingSetupObj->findSuppName($valD['suppCode']);
            //$worksheet->write($ctr,3,$rowStoreInfo3['suppName'],$row1);
            //$worksheet->write($ctr,4," ".$valD['invNo'],$row1);
            //$worksheet->write($ctr,5,date('m/d/Y',strtotime($valD['invDate'])),$row1);
            //$worksheet->write($ctr,6,date('m/d/Y',strtotime($valD['dueDate'])),$row1);
            //$worksheet->write($ctr,7,date('m/d/Y',strtotime($valD['vouchedDate'])),$row1);
            //$worksheet->write($ctr,8,$valD['invAmt'],$row2);
            //$worksheet->write($ctr,9,$valD['uploadedAmt'],$row2);
            //$rowStoreInfo2 = $leasingSetupObj->findCreationDate($valD['invNo'],$valD['suppCode']);
            //if($rowStoreInfo2['creation_date'] != ''){
            //    $worksheet->write($ctr,10,date('m/d/Y',strtotime($rowStoreInfo2['creation_date'])),$row1);
            //}else{                                
            //    $worksheet->write($ctr,10,'Not yet loaded',$row1);
            //}
            
        
			//$totInvAmt += $valD['invAmt'];
			//$totUplAmt += $valD['uploadedAmt'];
		}
		$ctr++;	
		//$worksheet->write($ctr,7,"TOTAL:",$headerFormat2);
		//$worksheet->write($ctr,8,$totInvAmt,$headerFormat2);
		//$worksheet->write($ctr,9,$totUplAmt,$headerFormat2);
		


			
$workbook->close();
?>