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
	
    
	
	$filename = "Progress_Bill_Line.xls";
	$workbook->send($filename);
	$worksheet = &$workbook->addWorksheet("Progress_Bill_Line");
	$worksheet->setLandscape();
	$worksheet->freezePanes(array(3,0));
	
	$worksheet->write(0,0,"Progress Bill Line Report",$headerFormat);
	
	for($i=1;$i<6;$i++) {
		$worksheet->write(0, $i, "",$headerFormat);	
	}
	$worksheet->setColumn(0,0,25);
	$worksheet->setColumn(1,1,25);
    $worksheet->setColumn(2,2,25);
    $worksheet->setColumn(3,3,25);
    $worksheet->setColumn(4,4,25);
    $worksheet->setColumn(4,5,50);

    $worksheet->write(2,0,"INVOICE_ID.".$company,$headerFormat);
    $worksheet->write(2,1,"GL_LINE_CODE",$headerFormat);
    $worksheet->write(2,2,"SumOfLINE_AMT",$headerFormat);
    $worksheet->write(2,3,"TAX_CLASSIFICATION_CODE",$headerFormat);
    $worksheet->write(2,4,"NAME",$headerFormat);
    $worksheet->write(2,5,"DESCRIPTION",$headerFormat);
                
	$ctr = 2;
		            
	$arrPayDetail = $progressBillObj->exportLineDetail();
    
	foreach ($arrPayDetail as $valD) {
        
        $row_left = ($col==0) ? $detail_left_color_blue:$detail_left_color_white;
        $row_center = ($col==0) ? $detail_center_color_blue:$detail_center_color_white;
        $row_right = ($col==0) ? $detail_right_color_blue:$detail_right_color_white;
        $row_right_number = ($col==0) ? $detail_right_color_blue_number:$detail_right_color_white_number;
        $col = ($col==0) ? 1:0;
		
		$ctr++;	
        
        $worksheet->write($ctr,0,$valD['INVOICE_ID'],$row_left);
        $worksheet->write($ctr,1,$valD['GL_LINE_CODE'],$row_left);
        $worksheet->write($ctr,2,$valD['SumOfLINE_AMT'],$row_right_number);
        $worksheet->write($ctr,3,$valD['TAX_CLASSIFICATION_CODE'],$row_left);
        $worksheet->write($ctr,4,$valD['NAME'],$row_left);
        $worksheet->write($ctr,5,$valD['DESCRIPTION'],$row_left);
        
        $total_sum_of_lamount += $valD['SumOfLINE_AMT'];          
	}
    
    $ctr++;
    
    $worksheet->write($ctr,0,"TOTAL",$headerFormat); 
    $worksheet->write($ctr,2,number_format($total_sum_of_lamount,2),$headerFormat); 

$workbook->close();
?>