<?
################### INCLUDE FILE #################
	session_start();
	ini_set('include_path','C:\wamp\php\PEAR');
	include("../../../includes/db.inc.php");
	include("../../../includes/common.php");
	include("apObj.php");
	require_once 'Spreadsheet/Excel/Writer.php';
	
	$apObj = new apObj();
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
	
	$filename = "Ap_Posted.xls";
	$workbook->send($filename);
	$worksheet = &$workbook->addWorksheet("Ap Posted");
	$worksheet->setLandscape();
	$worksheet->freezePanes(array(3,0));
	
	$worksheet->write(0,0,"Ap Posted Report From ".date('m/d/Y',strtotime($_GET['txtDateFrom']))." to ".date('m/d/Y',strtotime($_GET['txtDateTo'])),$headerFormat);
	//$worksheet->write(6,0,"Month Range: ".$printMonthFrom.$monthYearFrom." - ".$printMonthTo.$monthYearTo,$headerFormat);
	
	for($i=1;$i<11;$i++) {
		$worksheet->write(0, $i, "",$headerFormat);	
	}
	$worksheet->setColumn(0,0,20);
	$worksheet->setColumn(1,1,15);
    $worksheet->setColumn(2,2,40);
    $worksheet->setColumn(3,3,15);
	$worksheet->setColumn(4,4,20);
	$worksheet->setColumn(5,5,15);
	$worksheet->setColumn(6,6,20);
	$worksheet->setColumn(7,7,20);
	$worksheet->setColumn(8,8,20);
	$worksheet->setColumn(9,9,20);
	$worksheet->setColumn(10,10,100);
	
	$worksheet->write(1,0, "".$pMode,$headerFormat);
	
    $worksheet->write(2,0,"COMPANY",$headerFormat);
	$worksheet->write(2,1,"VENDOR NUM",$headerFormat);
	$worksheet->write(2,2,"VENDOR NAME",$headerFormat);
	$worksheet->write(2,3,"INVOICE NUM",$headerFormat);
    $worksheet->write(2,4,"INVOICE DATE",$headerFormat);
    $worksheet->write(2,5,"INVOICE AMOUNT",$headerFormat);
	$worksheet->write(2,6,"CHECK NUMBER",$headerFormat);
	$worksheet->write(2,7,"CHECK DATE",$headerFormat);
	$worksheet->write(2,8,"AMOUNT PAID",$headerFormat);
	$worksheet->write(2,9,"CREATION DATE",$headerFormat);
	$worksheet->write(2,10,"DESCRIPTION",$headerFormat);
	
		$ctr = 2;
		
		$arrApPosted = $apObj->apPosted($_GET['txtDateFrom'],$_GET['txtDateTo'],$_GET['cmbCompany'],$_GET['cmbApSourceName'],$_GET['cmbPostedPrefix']);
		foreach ($arrApPosted as $valD) {
            
            $ctr++;    
            
            $row_left = ($col==0) ? $detail_left_color_blue:$detail_left_color_white;
            $row_center = ($col==0) ? $detail_center_color_blue:$detail_center_color_white;
            $row_right = ($col==0) ? $detail_right_color_blue:$detail_right_color_white;
            $row_right_number = ($col==0) ? $detail_right_color_blue_number:$detail_right_color_white_number;
            $col = ($col==0) ? 1:0;
			                              
			$company = $apObj->getCompanyDetails($_GET['cmbCompany']);
            
			$worksheet->write($ctr,0,$company['comp_name'],$row_left);
            $worksheet->write($ctr,1,$valD['SEGMENT1'],$row_left);
			$worksheet->write($ctr,2,$valD['VENDOR_NAME'],$row_left);
			$worksheet->write($ctr,3," ".$valD['INVOICE_NUM'],$row_center);
            if(date('Y-m-d',strtotime($valD['INVOICE_DATE'])) == '1970-01-01'){
                $worksheet->write($ctr,4," ",$row_center);    
            }
            else{
                $worksheet->write($ctr,4,date('Y-m-d',strtotime($valD['INVOICE_DATE'])),$row_center);    
            }    
            $worksheet->write($ctr,5,$valD['INVOICE_AMOUNT'],$row_right_number);
            $worksheet->write($ctr,6,$valD['CHECK_NUMBER'],$row_left);
            if(date('Y-m-d',strtotime($valD['CHECK_DATE'])) == '1970-01-01'){
                $worksheet->write($ctr,7," ",$row_center);    
            }
            else{
                $worksheet->write($ctr,7,date('Y-m-d',strtotime($valD['CHECK_DATE'])),$row_center);    
            }   
            $worksheet->write($ctr,8,$valD['AMOUNT_PAID'],$row_right_number);
            $worksheet->write($ctr,9,date('Y-m-d',strtotime($valD['CREATION_DATE'])),$row_center);
            $worksheet->write($ctr,10,$valD['DESCRIPTION'],$row_left);

            $total_invoice_amount += $valD['INVOICE_AMOUNT'];
            $total_amount_paid += $valD['AMOUNT_PAID'];
		} 
        $ctr++;
		$worksheet->write($ctr,0,"TOTAL:",$headerFormat);
		$worksheet->write($ctr,5,$total_invoice_amount,$headerFormat2);
		$worksheet->write($ctr,8,$total_amount_paid,$headerFormat2);    
			
$workbook->close();
?>