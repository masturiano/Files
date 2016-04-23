<?
################### INCLUDE FILE #################
	session_start();
	ini_set('include_path','C:\wamp\php\PEAR');
	include("../../../includes/db.inc.php");
	include("../../../includes/common.php");
	include("stsObj.php");
	require_once 'Spreadsheet/Excel/Writer.php';
	
	$stsObj = new stsObj();
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
	
	$filename = "STS_Posted.xls";
	$workbook->send($filename);
	$worksheet = &$workbook->addWorksheet("STS Posted");
	$worksheet->setLandscape();
	$worksheet->freezePanes(array(3,0));
	
	$worksheet->write(0,0,"STS Report Upload Date From ".date('m/d/Y',strtotime($_GET['txtDateFrom']))." to ".date('m/d/Y',strtotime($_GET['txtDateTo'])),$headerFormat);
	
	for($i=1;$i<18;$i++) {
		$worksheet->write(0, $i, "",$headerFormat);	
	}
	$worksheet->setColumn(0,0,20);
	$worksheet->setColumn(1,1,20);
    $worksheet->setColumn(2,2,20);
    $worksheet->setColumn(3,3,20);
	$worksheet->setColumn(4,4,20);
	$worksheet->setColumn(5,5,20);
	$worksheet->setColumn(6,6,20);
	$worksheet->setColumn(7,7,20);
	$worksheet->setColumn(8,8,20);
    $worksheet->setColumn(9,9,20);
    $worksheet->setColumn(10,10,20);
    $worksheet->setColumn(11,11,25);
    $worksheet->setColumn(12,12,25);
    $worksheet->setColumn(13,13,25);
    $worksheet->setColumn(14,14,25);
	$worksheet->setColumn(15,15,25);
    $worksheet->setColumn(16,16,25);
    $worksheet->setColumn(17,17,80);
    
    if($_GET['cmbOrgId'] == 85){$company = "PPCI";}
    if($_GET['cmbOrgId'] == 87){$company = "JR";}
    if($_GET['cmbOrgId'] == 133){$company = "Puregold Subic";}

    $worksheet->write(1,0,"COMPANY: ".$company,$headerFormat);
	
    $worksheet->write(2,0,"STS INVOICE NO.",$headerFormat);
    $worksheet->write(2,1,"STS STS REFNO.",$headerFormat);
    $worksheet->write(2,2,"STS COMP CODE",$headerFormat);
    $worksheet->write(2,3,"STS STORE CODE",$headerFormat);
	$worksheet->write(2,4,"STS SUPP CODE",$headerFormat);
	$worksheet->write(2,5,"STS APPLY AMOUNT",$headerFormat);
    $worksheet->write(2,6,"STS APPLY DATE",$headerFormat);
	$worksheet->write(2,7,"STS UPLOAD DATE",$headerFormat);
    $worksheet->write(2,8,"STS FILENAME",$headerFormat);
    $worksheet->write(2,9,"STS STATUS",$headerFormat);
    $worksheet->write(2,10,"STS PAYMENT MODE",$headerFormat);
    $worksheet->write(2,11,"ORACLE CREATION DATE",$headerFormat);
    $worksheet->write(2,12,"ORACLE INVOICE NUM",$headerFormat);
    $worksheet->write(2,13,"ORACLE SUPP CODE",$headerFormat);
    $worksheet->write(2,14,"ORACLE INVOICE AMOUNT",$headerFormat);
    $worksheet->write(2,15,"ORACLE FILENAME",$headerFormat);
	$worksheet->write(2,16,"ORACLE SOURCE",$headerFormat);
    $worksheet->write(2,17,"ORACLE DESCRIPTION",$headerFormat);
                
		$ctr = 2;
		
		$row1 = ($col==0) ? $Deptc1:$Deptc;
        $row2 = ($col==0) ? $detail2:$detail2;
		$row3 = ($col==0) ? $detail3:$detail3;
		$col = ($col==0) ? 1:0;
		
		$arrSts = $stsObj->viewSts($_GET['txtDateFrom'],$_GET['txtDateTo'],$_GET['cmbOrgId']);
		foreach ($arrSts as $rowSts) {
			
			$ctr++;	  
            
            $worksheet->write($ctr,0,$rowSts['invoice'],$row1);
            $worksheet->write($ctr,1,$rowSts['stsRefno'],$row1);
            $worksheet->write($ctr,2,$rowSts['compCode'],$row1);
            $worksheet->write($ctr,3,$rowSts['strCode'],$row1);
            $worksheet->write($ctr,4,$rowSts['suppCode'],$row1);
            $worksheet->write($ctr,5,$rowSts['stsApplyAmt'],$row3);
            $worksheet->write($ctr,6,date('m/d/Y',strtotime($rowSts['stsApplyDate'])),$row1);
            $worksheet->write($ctr,7,date('m/d/Y',strtotime($rowSts['uploadDate'])),$row1);
            $worksheet->write($ctr,8,$rowSts['uploadApFile'],$row1);
            $worksheet->write($ctr,9,$rowSts['status'],$row1);
            $worksheet->write($ctr,10,$rowSts['stsPaymentMode'],$row1);
            
            $rowStoreInfo2 = $stsObj->findOracleDetails($rowSts['invoice'],$rowSts['suppCode'],$_GET['cmbOrgId']);
            if($rowStoreInfo2['creation_date'] == '' || $rowStoreInfo2['creation_date'] == '01/01/1970'){
                $worksheet->write($ctr,11,'Not yet loaded',$detailLeftRed2);
            }else{                                
                $worksheet->write($ctr,11,date('m/d/Y',strtotime($rowStoreInfo2['creation_date'])),$row1);
            }
            $worksheet->write($ctr,12,$rowStoreInfo2['invoice_num'],$row1);
            $worksheet->write($ctr,13,$rowStoreInfo2['segment1'],$row1);
            $worksheet->write($ctr,14,$rowStoreInfo2['invoice_amount'],$row3);   
            $worksheet->write($ctr,15,$rowStoreInfo2['attribute13'],$row1);
            $worksheet->write($ctr,16,$rowStoreInfo2['source'],$row1);
            $worksheet->write($ctr,17,$rowStoreInfo2['description'],$row3);
            
            $totalStsAmount += $rowSts['stsApplyAmt'];
            $totalOraAmount += $rowStoreInfo2['invoice_amount'];
            
            //$worksheet->write($ctr,0,$valD['Col001'],$row1);
            //$worksheet->write($ctr,1,$valD['Col004'],$row1);  
            //$worksheet->write($ctr,0,$company,$row1);    
            //$rowStoreInfo = $stsObj->findStore2($valD['strCode']);
            //$worksheet->write($ctr,0,$rowStoreInfo['strnum'],$row1);
            //$worksheet->write($ctr,1,$rowStoreInfo['strnam'],$row1);
		}
		$ctr++;	
        
        $worksheet->write($ctr,0,'TOTAL',$headerFormat);
        $worksheet->write($ctr,5,$totalStsAmount,$headerFormat);
        $worksheet->write($ctr,14,$totalOraAmount,$headerFormat);
			
$workbook->close();
?>