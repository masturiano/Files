<?
################### INCLUDE FILE #################
	session_start();
	ini_set('include_path','C:\wamp\php\PEAR');
	include("../../../includes/db.inc.php");
	include("../../../includes/common.php");
	include("manpowerBilObj.php");
	require_once 'Spreadsheet/Excel/Writer.php';
	
	$manpowerBilObj = new manpowerBilObj();
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
	
	$filename = "Manpower Billing.xls";
	$workbook->send($filename);
    $worksheet = &$workbook->addWorksheet("Sheet1");
	$worksheet->setLandscape();
	$worksheet->freezePanes(array(3,0));
    

	
	$worksheet->write(0,0,"Manpower Billing",$headerFormat);
	//$worksheet->write(6,0,"Month Range: ".$printMonthFrom.$monthYearFrom." - ".$printMonthTo.$monthYearTo,$headerFormat);
	
	for($i=1;$i<16;$i++) {
		$worksheet->write(0, $i, "",$headerFormat);	
	}
	$worksheet->setColumn(0,0,20);
	$worksheet->setColumn(1,1,20);
    $worksheet->setColumn(2,2,45);
    $worksheet->setColumn(3,3,15);
	$worksheet->setColumn(4,4,20);
	$worksheet->setColumn(5,5,20);
	$worksheet->setColumn(6,6,20);
	$worksheet->setColumn(7,7,20);
	$worksheet->setColumn(8,8,20);
	$worksheet->setColumn(9,9,20);
	$worksheet->setColumn(10,10,20);
	$worksheet->setColumn(11,11,20);
    $worksheet->setColumn(12,12,30);
    $worksheet->setColumn(13,13,20);
    $worksheet->setColumn(14,14,30);
    $worksheet->setColumn(15,15,30);
    $worksheet->setColumn(16,16,100);
	
	$worksheet->write(1,0, "".$pMode,$headerFormat);
	
	$worksheet->write(2,0,"INVOICE ID",$headerFormat);
	$worksheet->write(2,1,"VENDOR NO",$headerFormat);
	$worksheet->write(2,2,"VENDOR NAME",$headerFormat);
    $worksheet->write(2,3,"ORG ID",$headerFormat);
    $worksheet->write(2,4,"VENDOR SITE CODE",$headerFormat);
	$worksheet->write(2,5,"INVOICE NUM",$headerFormat);
	$worksheet->write(2,6,"INVOICE DATE",$headerFormat);
    $worksheet->write(2,7,"INVOICE AMOUNT",$headerFormat);
    $worksheet->write(2,8,"AMOUNT REMAINING",$headerFormat);
    $worksheet->write(2,9,"SOURCE",$headerFormat);
    $worksheet->write(2,10,"MATCH STATUS FLAG",$headerFormat);
    $worksheet->write(2,11,"CREATION DATE",$headerFormat);
    $worksheet->write(2,12,"BANK ACCOUNT NAME",$headerFormat);
    $worksheet->write(2,13,"CHECK NUMBER",$headerFormat);
    $worksheet->write(2,14,"CHECK SIGNED DATE",$headerFormat);
    $worksheet->write(2,15,"CHECK RELEASED DATE",$headerFormat);
	$worksheet->write(2,16,"DESCRIPTION",$headerFormat);
	
        $cnt = 1;
		$ctr = 3;
		
		$row1 = ($col==0) ? $Deptc1:$Deptc;
		$row2 = ($col==0) ? $detail2:$detail2;
		$col = ($col==0) ? 1:0;
        
        $company = array('85'=>'PPCI','87'=>'JR','133'=>'SUBIC');
        
        $rowscounter = 1;
        $active_sheet = 1;
		
		$arrManpowerBilling = $manpowerBilObj->manpowerBilling($_GET['txtDateFrom'],$_GET['txtDateTo']);
        
        
        
            foreach ($arrManpowerBilling as $valD) {
            
                $worksheet->write($ctr,0,$valD['INVOICE_ID'],$row1);
                $worksheet->write($ctr,1,$valD['SEGMENT1'],$row1);
                $worksheet->write($ctr,2,$valD['VENDOR_NAME'],$row1);
                $worksheet->write($ctr,3,$company[$valD['ORG_ID']],$row1);
                $worksheet->write($ctr,4,$valD['VENDOR_SITE_CODE'],$row1);
                $worksheet->write($ctr,5," ".$valD['INVOICE_NUM'],$row1);
                $invoiceDate = (date('m/d/Y',strtotime($valD['INVOICE_DATE']))=='01/01/1970') ? '':date('m/d/Y',strtotime($valD['INVOICE_DATE']));
                $worksheet->write($ctr,6,$invoiceDate,$row1);
                $worksheet->write($ctr,7,$valD['INVOICE_AMOUNT'],$row1);
                $worksheet->write($ctr,8,$valD['AMOUNT_REMAINING'],$row1);
                $worksheet->write($ctr,9,$valD['SOURCE'],$row1);
                $worksheet->write($ctr,10,$valD['MATCH_STATUS_FLAG'],$row1);
                $creationDate = (date('m/d/Y',strtotime($valD['CREATION_DATE']))=='01/01/1970') ? '':date('m/d/Y',strtotime($valD['CREATION_DATE']));
                $worksheet->write($ctr,11,$creationDate,$row1);
                $worksheet->write($ctr,12,$valD['BANK_ACCOUNT_NAME'],$row1);
                $worksheet->write($ctr,13,$valD['CHECK_NUMBER'],$row1);
                $worksheet->write($ctr,14,date('m/d/Y',strtotime($valD['CHECK_SIGNED_DATE'])),$row1);
                $worksheet->write($ctr,15,date('m/d/Y',strtotime($valD['CHECK_RELEASED_DATE'])),$row1);
                $worksheet->write($ctr,16,$valD['DESCRIPTION'],$row1);
                $ctr++; 
                  
               if($ctr > 65000){
                 $ctr = 3;
                 $cnt++;
                    $worksheet = &$workbook->addWorksheet("Sheet".$cnt);
                    $worksheet->setLandscape();
                    $worksheet->freezePanes(array(3,0));

                    $worksheet->write(0,0,"Manpower Billing",$headerFormat);
                    //$worksheet->write(6,0,"Month Range: ".$printMonthFrom.$monthYearFrom." - ".$printMonthTo.$monthYearTo,$headerFormat);

                    for($i=1;$i<16;$i++) {
                        $worksheet->write(0, $i, "",$headerFormat);    
                    }
                    $worksheet->setColumn(0,0,20);
                    $worksheet->setColumn(1,1,20);
                    $worksheet->setColumn(2,2,45);
                    $worksheet->setColumn(3,3,15);
                    $worksheet->setColumn(4,4,20);
                    $worksheet->setColumn(5,5,20);
                    $worksheet->setColumn(6,6,20);
                    $worksheet->setColumn(7,7,20);
                    $worksheet->setColumn(8,8,20);
                    $worksheet->setColumn(9,9,20);
                    $worksheet->setColumn(10,10,20);
                    $worksheet->setColumn(11,11,20);
                    $worksheet->setColumn(12,12,30);
                    $worksheet->setColumn(13,13,20);
                    $worksheet->setColumn(14,14,30);
                    $worksheet->setColumn(15,15,30);
                    $worksheet->setColumn(16,16,100);

                    $worksheet->write(1,0, "".$pMode,$headerFormat);

                    $worksheet->write(2,0,"INVOICE ID",$headerFormat);
                    $worksheet->write(2,1,"VENDOR NO",$headerFormat);
                    $worksheet->write(2,2,"VENDOR NAME",$headerFormat);
                    $worksheet->write(2,3,"ORG ID",$headerFormat);
                    $worksheet->write(2,4,"VENDOR SITE CODE",$headerFormat);
                    $worksheet->write(2,5,"INVOICE NUM",$headerFormat);
                    $worksheet->write(2,6,"INVOICE DATE",$headerFormat);
                    $worksheet->write(2,7,"INVOICE AMOUNT",$headerFormat);
                    $worksheet->write(2,8,"AMOUNT REMAINING",$headerFormat);
                    $worksheet->write(2,9,"SOURCE",$headerFormat);
                    $worksheet->write(2,10,"MATCH STATUS FLAG",$headerFormat);
                    $worksheet->write(2,11,"CREATION DATE",$headerFormat);
                    $worksheet->write(2,12,"BANK ACCOUNT NAME",$headerFormat);
                    $worksheet->write(2,13,"CHECK NUMBER",$headerFormat);
                    $worksheet->write(2,14,"CHECK SIGNED DATE",$headerFormat);
                    $worksheet->write(2,15,"CHECK RELEASED DATE",$headerFormat);
                    $worksheet->write(2,16,"DESCRIPTION",$headerFormat);
               }   
            }    

$workbook->close();
?>

<?php
/*
?>
foreach($result as $value)
{
    $val = array_values($value);

    if($rowscounter < 65000)
    {
        $objPHPExcel->addRow($val,$rowscounter);
    }
    else
    {
        $active_sheet++;
        $objPHPExcel->createSheet();
        $objPHPExcel->setActiveSheetIndex($active_sheet);
        $rowscounter = 1;
    }
    $rowscounter++;
}
*/
?>