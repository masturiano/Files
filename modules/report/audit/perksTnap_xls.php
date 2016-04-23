<?
################### INCLUDE FILE #################
	session_start();
	ini_set('include_path','C:\wamp\php\PEAR');
	include("../../../includes/db.inc.php");
	include("../../../includes/common.php");
	include("perksTnapObj.php");
	require_once 'Spreadsheet/Excel/Writer.php';
    
    $card_type = $_GET['cmbCardTyp'];
	
	$perksTnapObj = new perksTnapObj();
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
	
    
	
	$filename = $card_type."_unauthorized.xls";
	$workbook->send($filename);
	$worksheet = &$workbook->addWorksheet("$card_type");
	$worksheet->setLandscape();
	$worksheet->freezePanes(array(3,0));
	                    
	$worksheet->write(0,0,"$card_type Un Authorized Report From ".date('m/d/Y',strtotime($_GET['txtDateFrom']))." to ".date('m/d/Y',strtotime($_GET['txtDateTo'])),$headerFormat);
	
	for($i=1;$i<9;$i++) {
		$worksheet->write(0, $i, "",$headerFormat);	
	}
	$worksheet->setColumn(0,0,20);
	$worksheet->setColumn(1,1,20);
    $worksheet->setColumn(2,2,25);
    $worksheet->setColumn(3,3,25);
    $worksheet->setColumn(4,4,40);
    $worksheet->setColumn(5,5,30);
    $worksheet->setColumn(6,6,40);
	$worksheet->setColumn(7,7,20);
	$worksheet->setColumn(8,8,20);
	$worksheet->setColumn(9,9,20);
	$worksheet->setColumn(10,10,20);
    
    if($_GET['cmbOrgId'] == 85){$company = "PPCI";}
    if($_GET['cmbOrgId'] == 87){$company = "JR";}
    if($_GET['cmbOrgId'] == 133){$company = "Puregold Subic";}

    $worksheet->write(2,0,"CARD NO.".$company,$headerFormat);
    $worksheet->write(2,1,"ORIGINAL DATE",$headerFormat);
    $worksheet->write(2,2,"PRINTED NAME",$headerFormat);
    $worksheet->write(2,3,"SIGN UP LOCATION CODE",$headerFormat);
    $worksheet->write(2,4,"SIGN UP LOCATION NAME",$headerFormat);
    $worksheet->write(2,5,"TRANSACTION LOCATION CODE",$headerFormat);
    $worksheet->write(2,6,"TRANSACTION LOCATION NAME",$headerFormat);
	$worksheet->write(2,7,"AMOUNT SPENT",$headerFormat);
	$worksheet->write(2,8,"TRANSACT POINTS",$headerFormat);
    $worksheet->write(2,9,"NET SPENT",$headerFormat);
	$worksheet->write(2,10,"TRANSACTION COUNT",$headerFormat);
                
	$ctr = 2;
		            
	$arrPerksTnap = $perksTnapObj->getData($_GET['txtDateFrom'],$_GET['txtDateTo'],$card_type);
    
	foreach ($arrPerksTnap as $valD) {
        
        $row_left = ($col==0) ? $detail_left_color_blue:$detail_left_color_white;
        $row_center = ($col==0) ? $detail_center_color_blue:$detail_center_color_white;
        $row_right = ($col==0) ? $detail_right_color_blue:$detail_right_color_white;
        $row_right_number = ($col==0) ? $detail_right_color_blue_number:$detail_right_color_white_number;
        $col = ($col==0) ? 1:0;
		
		$ctr++;	
        
        $worksheet->write($ctr,0," ".$valD['CardNo'],$row_left);
        $worksheet->write($ctr,1,date('m/d/Y',strtotime($valD['OriginalDate'])),$row_center);
        $worksheet->write($ctr,2,$valD['PrintedName'],$row_left);
        $worksheet->write($ctr,3,$valD['SignupLocationCode'],$row_left);
        if($valD['SignupLocationCode'] == 'HQ'){
            $store_number = "901";    
        }
        else{
            $store_number = $valD['SignupLocationCode'];  
        }
        $rowStoreInfo = $perksTnapObj->findStore($store_number);
        $worksheet->write($ctr,4,$rowStoreInfo['strnam'],$row_left);
        $worksheet->write($ctr,5,$valD['StoreTransacted'],$row_left);
        if($valD['StoreTransacted'] == 'HQ'){
            $store_number2 = "901";    
        }
        else{
            $store_number2 = $valD['StoreTransacted'];  
        }
        $rowStoreInfoTransacted = $perksTnapObj->findStore($store_number2);
        $worksheet->write($ctr,6,$rowStoreInfoTransacted['strnam'],$row_left);
        $worksheet->write($ctr,7,$valD['amountSpent'],$row_right_number);
        $worksheet->write($ctr,8,$valD['transactPoints'],$row_right_number);
        $worksheet->write($ctr,9,$valD['NettSpent'],$row_right_number);
        $worksheet->write($ctr,10,$valD['transactionCount'],$row_right_number); 
        
        $total_amount_spent += $valD['amountSpent'];       
        $total_transact_points += $valD['transactPoints'];       
        $total_net_spent += $valD['NettSpent'];       
        $total_transaction_count += $valD['transactionCount'];       
	}
    
    $ctr++;
    
    $worksheet->write($ctr,1,"TOTAL",$headerFormat); 
    $worksheet->write($ctr,7,number_format($total_amount_spent,2),$headerFormat); 
    $worksheet->write($ctr,8,number_format($total_transact_points,2),$headerFormat); 
    $worksheet->write($ctr,9,number_format($total_net_spent,2),$headerFormat); 
    $worksheet->write($ctr,10,number_format($total_transaction_count,2),$headerFormat); 

$workbook->close();
?>