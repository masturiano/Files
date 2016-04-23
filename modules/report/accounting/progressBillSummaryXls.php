<?
################### INCLUDE FILE #################
	session_start();
	ini_set('include_path','C:\wamp\php\PEAR');
	include("../../../includes/db.inc.php");
	include("../../../includes/common.php");
	include("progressBillObj.php");
	require_once 'Spreadsheet/Excel/Writer.php';
	
	$progressBillObj = new progressBillObj();
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
	
    
	
	$filename = "Progress_Bill_Merge.xls";
	$workbook->send($filename);
	$worksheet = &$workbook->addWorksheet("Progress_Bill_Merge");
	$worksheet->setLandscape();
	$worksheet->freezePanes(array(3,0));
	
	$worksheet->write(0,0,"Progress Bill Merge Report",$headerFormat);
	
	for($i=1;$i<20;$i++) {
		$worksheet->write(0, $i, "",$headerFormat);	
	}
	$worksheet->setColumn(0,0,20);
	$worksheet->setColumn(1,1,20);
    $worksheet->setColumn(2,2,50);
    $worksheet->setColumn(3,3,20);
    $worksheet->setColumn(4,4,20);
    $worksheet->setColumn(5,5,20);
    $worksheet->setColumn(6,6,20);
	$worksheet->setColumn(7,7,20);
	$worksheet->setColumn(8,8,20);
    $worksheet->setColumn(9,9,20);
    $worksheet->setColumn(10,10,100);
    
    $worksheet->setColumn(11,11,20);
    $worksheet->setColumn(12,12,20);
    $worksheet->setColumn(13,13,50);
    $worksheet->setColumn(14,14,30);
    $worksheet->setColumn(15,15,100);
    
    $worksheet->setColumn(16,16,20);
    $worksheet->setColumn(17,17,20);
    $worksheet->setColumn(18,18,20);
	$worksheet->setColumn(19,19,30);
    
    // $worksheet->setColumn(9,9,20);
    // $worksheet->setColumn(10,10,40);
    // $worksheet->setColumn(11,11,40);
    // $worksheet->setColumn(12,12,20);

    $worksheet->write(2,0,"INVOICE_ID.".$company,$headerFormat);
    $worksheet->write(2,1,"SEGMENT1",$headerFormat);
    $worksheet->write(2,2,"VENDOR_NAME",$headerFormat);
    $worksheet->write(2,3,"ORG_ID",$headerFormat);
    $worksheet->write(2,4,"VENDOR_SITE_CODE",$headerFormat);
    $worksheet->write(2,5,"INVOICE_NUM",$headerFormat);
    $worksheet->write(2,6,"INVOICE_DATE",$headerFormat);  
	$worksheet->write(2,7,"INVOICE_AMOUNT",$headerFormat);
	$worksheet->write(2,8,"SOURCE",$headerFormat);  
	$worksheet->write(2,9,"AMOUNT_REMAINING",$headerFormat);
    $worksheet->write(2,10,"DESCRIPTION",$headerFormat);
    
    $worksheet->write(2,11,"LINE_GL_LINE_CODE",$headerFormat);
    $worksheet->write(2,12,"LINE_AMT_SUM",$headerFormat);
    $worksheet->write(2,13,"LINE_TAX_CLASSIFICATION_CODE",$headerFormat);
    $worksheet->write(2,14,"LINE_NAME",$headerFormat);
    $worksheet->write(2,15,"LINE_DESCRIPTION",$headerFormat);
    
    $worksheet->write(2,16,"PAY_AMOUNT",$headerFormat);
    $worksheet->write(2,17,"PAY_CHECK_DATE",$headerFormat);
    $worksheet->write(2,18,"PAY_CHECK_NUMBER",$headerFormat);
    $worksheet->write(2,19,"PAY_STATUS_LOOKUP_CODE",$headerFormat);
    
    // $worksheet->write(2,9,"GL_LINE_CODE",$headerFormat);
    // $worksheet->write(2,10,"LINE_AMT",$headerFormat);
    // $worksheet->write(2,11,"TAX_CLASSIFICATION_CODE",$headerFormat);
    // $worksheet->write(2,12,"NAME",$headerFormat);
                
	$ctr = 2;
		            
	$arrInvoiceSummary = $progressBillObj->exportSummary();
    
	foreach ($arrInvoiceSummary as $valD) {
        
        $row_left = ($col==0) ? $detail_left_color_blue:$detail_left_color_white;
        $row_center = ($col==0) ? $detail_center_color_blue:$detail_center_color_white;
        $row_right = ($col==0) ? $detail_right_color_blue:$detail_right_color_white;
        $row_right_number = ($col==0) ? $detail_right_color_blue_number:$detail_right_color_white_number;
        $col = ($col==0) ? 1:0;
		
		$ctr++;	
        
        $worksheet->write($ctr,0,$valD['INVOICE_ID'],$row_left);
        $worksheet->write($ctr,1,$valD['SEGMENT1'],$row_left);
        $worksheet->write($ctr,2,$valD['VENDOR_NAME'],$row_left);
        $worksheet->write($ctr,3,$valD['ORG_ID'],$row_left);
        $worksheet->write($ctr,4,$valD['VENDOR_SITE_CODE'],$row_left);
        $worksheet->write($ctr,5," ".$valD['INVOICE_NUM'],$row_left);
        if(date('m/d/Y',strtotime($valD['INVOICE_DATE'])) != '01/01/1970')
        {
            $worksheet->write($ctr,6,date('m/d/Y',strtotime($valD['INVOICE_DATE'])),$row_center);    
        }
        else
        {
            $worksheet->write($ctr,6,"-",$row_center);            
        }    
        $worksheet->write($ctr,7,$valD['INVOICE_AMOUNT'],$row_right_number);
        $worksheet->write($ctr,8,$valD['SOURCE'],$row_left);
        $worksheet->write($ctr,9,$valD['AMOUNT_REMAINING'],$row_right_number);
        $worksheet->write($ctr,10,$valD['DESCRIPTION'],$row_left);
        
        $worksheet->write($ctr,11,$valD['LINE_GL_LINE_CODE'],$row_left);
        $worksheet->write($ctr,12,$valD['LINE_AMT_SUM'],$row_right_number);
        $worksheet->write($ctr,13,$valD['LINE_TAX_CLASSIFICATION_CODE'],$row_left);
        $worksheet->write($ctr,14,$valD['LINE_NAME'],$row_left);
        $worksheet->write($ctr,15,$valD['LINE_DESCRIPTION'],$row_left);

        $worksheet->write($ctr,16,$valD['PAY_AMOUNT'],$row_right_number);                        
        if(date('m/d/Y',strtotime($valD['PAY_CHECK_DATE'])) != '01/01/1970')
        {
            $worksheet->write($ctr,17,date('m/d/Y',strtotime($valD['PAY_CHECK_DATE'])),$row_center);
        }
        else
        {
            $worksheet->write($ctr,17,"-",$row_center);                
        }
        $worksheet->write($ctr,18,$valD['PAY_CHECK_NUMBER'],$row_left);
        $worksheet->write($ctr,19,$valD['PAY_STATUS_LOOKUP_CODE'],$row_left);
        
        // $worksheet->write($ctr,9,$valD['GL_LINE_CODE'],$row_left);
        // $worksheet->write($ctr,10,$valD['LINE_AMT'],$row_left);
        // $worksheet->write($ctr,11,$valD['TAX_CLASSIFICATION_CODE'],$row_left);
        // $worksheet->write($ctr,12,$valD['NAME'],$row_left);
        
        $total_invoice_amount += $valD['INVOICE_AMOUNT'];       
        $total_amount_remaining += $valD['AMOUNT_REMAINING'];  
              
        $total_line_amount += $valD['LINE_AMT_SUM'];   
             
        $total_pay_amount += $valD['PAY_AMOUNT'];        
	}
    
    $ctr++;
    
    $worksheet->write($ctr,0,"TOTAL",$headerFormat); 
    $worksheet->write($ctr,7,number_format($total_invoice_amount,2),$headerFormat); 
    $worksheet->write($ctr,9,number_format($total_amount_remaining,2),$headerFormat); 
    $worksheet->write($ctr,12,number_format($total_line_amount,2),$headerFormat); 
    $worksheet->write($ctr,16,number_format($total_pay_amount,2),$headerFormat); 

$workbook->close();
?>