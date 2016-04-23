<?
################### INCLUDE FILE #################
	session_start();
	ini_set('include_path','C:\wamp\php\PEAR');
	include("../../../includes/db.inc.php");
	include("../../../includes/common.php");
	include("ndtObj.php");
	require_once 'Spreadsheet/Excel/Writer.php';
	
	$ndtObj = new ndtObj();
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
	
	
	
	#DETAIL ALIGN LEFT COLOR WHITE 
    $detail_left_color_white   = $workbook->addFormat(array('Size' => 10,
                                          'fgColor' => 'white',
                                          'Pattern' => 1,
                                          'border' =>1,
                                          'Align' => 'left'));
    $detail_left_color_white->setFontFamily('Calibri'); 
    #DETAIL ALIGN LEFT COLOR BLUE 
    $detail_left_color_blue   = $workbook->addFormat(array('Size' => 10,
                                          'border' =>1,
                                          'Pattern' => 1,
                                          'Align' => 'left'));
    $detail_left_color_blue->setFgColor(12); 
    $detail_left_color_blue->setFontFamily('Calibri');
    
    #DETAIL ALIGN CENTER COLOR WHITE 
    $detail_center_color_white   = $workbook->addFormat(array('Size' => 10,
                                          'fgColor' => 'white',
                                          'Pattern' => 1,
                                          'border' =>1,
                                          'Align' => 'center'));
    $detail_center_color_white->setFontFamily('Calibri'); 
    #DETAIL ALIGN CENTER COLOR BLUE 
    $detail_center_color_blue   = $workbook->addFormat(array('Size' => 10,
                                          'border' =>1,
                                          'Pattern' => 1,
                                          'Align' => 'center'));
    $detail_center_color_blue->setFgColor(12); 
    $detail_center_color_blue->setFontFamily('Calibri');
    
    #DETAIL ALIGN RIGHT COLOR WHITE 
    $detail_right_color_white   = $workbook->addFormat(array('Size' => 10,
                                          'fgColor' => 'white',
                                          'Pattern' => 1,
                                          'border' =>1,
                                          'Align' => 'right'));
    $detail_right_color_white->setFontFamily('Calibri'); 
    #DETAIL ALIGN RIGHT COLOR BLUE 
    $detail_right_color_blue   = $workbook->addFormat(array('Size' => 10,
                                          'border' =>1,
                                          'Pattern' => 1,
                                          'Align' => 'right'));
    $detail_right_color_blue->setFgColor(12); 
    $detail_right_color_blue->setFontFamily('Calibri');
    
    #DETAIL ALIGN RIGHT COLOR WHITE WITH NUMBER FORMAT 
    $detail_right_color_white_number   = $workbook->addFormat(array('Size' => 10,
                                          'fgColor' => 'white',
                                          'Pattern' => 1,
                                          'border' =>1,
                                          'Align' => 'right'));
    $detail_right_color_white_number->setFontFamily('Calibri'); 
    $detail_right_color_white_number->setNumFormat('#,##0.00');
    #DETAIL ALIGN RIGHT COLOR BLUE WITH NUMBER FORMAT
    $detail_right_color_blue_number   = $workbook->addFormat(array('Size' => 10,
                                          'border' =>1,
                                          'Pattern' => 1,
                                          'Align' => 'right'));
    $detail_right_color_blue_number->setFgColor(12); 
    $detail_right_color_blue_number->setFontFamily('Calibri');
    $detail_right_color_blue_number->setNumFormat('#,##0.00');
	
	$filename = "NDT_Posted.xls";
	$workbook->send($filename);
	$worksheet = &$workbook->addWorksheet("NDT Posted");
	$worksheet->setLandscape();
	$worksheet->freezePanes(array(3,0));
	
	$worksheet->write(0,0,"RCR NDT Report Date ".date('m/d/Y',strtotime($_GET['txtDateFrom'])),$headerFormat);
	
	for($i=1;$i<16;$i++) {
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
    $worksheet->setColumn(9,9,25);
    $worksheet->setColumn(10,10,25);
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
    if($_GET['cmbOrgId'] == 153){$company = "Daily Commodities";}
    if($_GET['cmbOrgId'] == 113){$company = "Firstlane Supertraders";}

    $worksheet->write(1,0,"COMPANY: ".$company,$headerFormat);
    
    $worksheet->write(2,0,"ORG UNIT",$headerFormat);
    $worksheet->write(2,1,"SUPPLIER.",$headerFormat);
    $worksheet->write(2,2,"SUPPLIER NAME.",$headerFormat);
    $worksheet->write(2,3,"AMOUNT",$headerFormat);
    $worksheet->write(2,4,"INVOICE DATE",$headerFormat);
    $worksheet->write(2,5,"POSTING DATE",$headerFormat);
    $worksheet->write(2,6,"INVOICE NUM",$headerFormat);
    $worksheet->write(2,7,"LOCATION.",$headerFormat);
    $worksheet->write(2,8,"SHORT CODE",$headerFormat); 
    $worksheet->write(2,9,"ORACLE CREATION DATE",$headerFormat);
    $worksheet->write(2,10,"ORACLE INVOICE NUM",$headerFormat);
    $worksheet->write(2,11,"ORACLE SUPP CODE",$headerFormat);
    $worksheet->write(2,12,"ORACLE INVOICE AMOUNT",$headerFormat);
    $worksheet->write(2,13,"ORACLE FILENAME",$headerFormat);
    $worksheet->write(2,14,"ORACLE SOURCE",$headerFormat);
    $worksheet->write(2,15,"ORACLE DESCRIPTION",$headerFormat);
                
		$ctr = 2;   
        
		$arrSts = $ndtObj->viewNdt($_GET['txtDateFrom'],$_GET['cmbOrgId']);
		foreach ($arrSts as $rowSts) {
			
			$ctr++;	  
            
            $row_left = ($col==0) ? $detail_left_color_blue:$detail_left_color_white;
            $row_center = ($col==0) ? $detail_center_color_blue:$detail_center_color_white;
            $row_right = ($col==0) ? $detail_right_color_blue:$detail_right_color_white;
            $row_right_number = ($col==0) ? $detail_right_color_blue_number:$detail_right_color_white_number;
            $col = ($col==0) ? 1:0;
            
            $worksheet->write($ctr,0,$rowSts['ORG_UNIT'],$row_left);
            $worksheet->write($ctr,1,$rowSts['SUPPLIER'],$row_left);
            $worksheet->write($ctr,2,$rowSts['SUPPLIERNAME'],$row_left);
            $worksheet->write($ctr,3,$rowSts['AMOUNT'],$row_right_number);
            
            $invoice_date_year = "20".substr($rowSts['INVOICEDATE'],0,2);
            $invoice_date_month = substr($rowSts['INVOICEDATE'],2,2); 
            $invoice_date_date = substr($rowSts['INVOICEDATE'],4,2);
            $invoice_date_combine = $invoice_date_month."/".$invoice_date_date."/".$invoice_date_year;
            $worksheet->write($ctr,4,date('m/d/Y',strtotime($invoice_date_combine)),$row_center);
            
            $posting_date_year = "20".substr($rowSts['POSTINGDATE'],0,2);
            $posting_date_month = substr($rowSts['POSTINGDATE'],2,2); 
            $posting_date_date = substr($rowSts['POSTINGDATE'],4,2);       
            $posting_date_combine = $posting_date_month."/".$posting_date_date."/".$posting_date_year;
            $worksheet->write($ctr,5,date('m/d/Y',strtotime($posting_date_combine)),$row_center);
            
            $worksheet->write($ctr,6," ".$rowSts['INVOICENUMBER'],$row_center);
            $worksheet->write($ctr,7,$rowSts['LOCATION'],$row_center);
            $worksheet->write($ctr,8,$rowSts['STORE'],$row_left);
            
            
            $rowStoreInfo2 = $ndtObj->findOracleDetails($rowSts['INVOICENUMBER'],$rowSts['SUPPLIER'],$_GET['cmbOrgId']);
            if($rowStoreInfo2['creation_date'] == '' || $rowStoreInfo2['creation_date'] == '01/01/1970'){
                $worksheet->write($ctr,9,'Not yet loaded',$row_left);
            }else{                                
                $worksheet->write($ctr,9,date('m/d/Y',strtotime($rowStoreInfo2['creation_date'])),$row_left);
            }
            $worksheet->write($ctr,10," ".$rowStoreInfo2['invoice_num'],$row_left);
            $worksheet->write($ctr,11,$rowStoreInfo2['segment1'],$row_left);
            $worksheet->write($ctr,12,$rowStoreInfo2['invoice_amount'],$row_right_number);   
            $worksheet->write($ctr,13,$rowStoreInfo2['attribute13'],$row_left);
            $worksheet->write($ctr,14,$rowStoreInfo2['source'],$row_left);
            $worksheet->write($ctr,15,$rowStoreInfo2['description'],$row_left);
            
            $totalStsAmount += $rowSts['AMOUNT'];
            $totalOraAmount += $rowStoreInfo2['invoice_amount'];
            
            
            
            //$worksheet->write($ctr,0,$valD['Col001'],$row1);
            //$worksheet->write($ctr,1,$valD['Col004'],$row1);  
            //$worksheet->write($ctr,0,$company,$row1);    
            //$rowStoreInfo = $ndtObj->findStore2($valD['strCode']);
            //$worksheet->write($ctr,0,$rowStoreInfo['strnum'],$row1);
            //$worksheet->write($ctr,1,$rowStoreInfo['strnam'],$row1);
		}
		$ctr++;	
        
        $worksheet->write($ctr,0,'TOTAL',$headerFormat);
        $worksheet->write($ctr,3,$totalStsAmount,$headerFormat);
        $worksheet->write($ctr,10,$totalOraAmount,$headerFormat);
			
$workbook->close();
?>