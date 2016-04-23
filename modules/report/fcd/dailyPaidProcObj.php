<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");

class dailyPaidProcObj extends commonObj {

	function loginNotExist($uName,$pWord) {
		$sqlLogin = "SELECT * FROM ORS_tblUsers where userName='$uName' and userPass='$pWord' and userStat='A'";
		return $this->execQry($sqlLogin);
	}
	function login($uName,$pWord) {
		$sqlLogin = "SELECT * FROM ORS_tblUsers where userName='$uName' and userPass='$pWord' and userStat='A'";
		return $this->getSqlAssoc($this->execQry($sqlLogin));
	}
		
	function dailyPaidProcessCsv($dteFrom,$dteTo,$company) {
		
		$dteFrom = date("Y-m-d", strtotime("$dteTo", time()));
		$dteTo = date("Y-m-d", strtotime("$dteTo +1 day", time()));
		
		if($company == "0"){
			$orgId = "85,87,133,89,91,113,153";
		}
		else
		{
			$orgId = $company;
		}
		
		$turnOnAnsiNulls = "SET ANSI_NULLS ON";
		$turnOnAnsiWarn = "SET ANSI_WARNINGS ON";

		$sql="
		select	ORAPROD.INVOICE_ID,ORAPROD.ORG_ID,ORAPROD.SEGMENT1,ORAPROD.VENDOR_NAME,ORAPROD.INVOICE_NUM,
				ORAPROD.INVOICE_DATE,ORAPROD.INVOICE_AMOUNT,ORAPROD.PAIDAMT,ORAPROD.DESCRIPTION,ORAPROD.CHECK_NUMBER,ORAPROD.CHECK_DATE,ORAPROD.CHECK_AMOUNT
		from openquery(ORAPROD,'
				SELECT 
				ap_invoices_all.INVOICE_ID,
				ap_invoices_all.ORG_ID,
				ap_suppliers.SEGMENT1,
				ap_suppliers.VENDOR_NAME,
				ap_invoices_all.INVOICE_NUM,
				ap_invoices_all.invoice_date,
				ap_invoices_all.INVOICE_AMOUNT,
				ap_invoice_payments_all.AMOUNT AS PAIDAMT,
				ap_invoices_all.DESCRIPTION,
				ap_checks_all.CHECK_NUMBER,
				ap_checks_all.CHECK_DATE,
				ap_checks_all.AMOUNT AS CHECK_AMOUNT
				FROM
				ap_invoices_all
				INNER JOIN ap_suppliers
				ON ap_suppliers.VENDOR_ID = ap_invoices_all.VENDOR_ID
				LEFT JOIN ap_invoice_payments_all
				ON ap_invoices_all.INVOICE_ID = ap_invoice_payments_all.INVOICE_ID
				LEFT JOIN ap_checks_all
				ON ap_invoice_payments_all.CHECK_ID = ap_checks_all.CHECK_ID
				WHERE ap_checks_all.creation_date between to_date(''$dteFrom'') and to_date(''$dteTo'') 
				and ap_invoices_all.ORG_ID in (85)
				ORDER BY
				ap_invoices_all.ORG_ID,
				ap_checks_all.CHECK_NUMBER
			') ORAPROD
		";
		$this->execQry($turnOnAnsiNulls);
		$this->execQry($turnOnAnsiWarn);
		
		$gmt = time() + (8 * 60 * 60);
		$todayTime = date("His",$gmt);
		$datefileM = date("m",$gmt);
		$datefileD = date("d",$gmt);
		$datefileY = date("Y",$gmt);
		$fileExt = ".CSV";
		
		$file_desti = "exported_file/"; // File destination
		$filename = "DAILY_PAID".$fileExt;
			
			if (file_exists($file_desti.$filename)) {
				unlink($file_desti.$filename);
			}
			
			$strlength = strlen($this->execQry($sql));
			
			$xcontentx = "";
			if($strlength > 0){
			
				$arrContent = $this->getArrRes($this->execQry($sql));
				
				$xcontentx .= trim("DATE,STORE_CODE,VENDOR_CODE,DEPARTMENT_CODE,SUBDEPARTMENT_CODE,CLASS_CODE,SUBCLASS_CODE,BUYER_CODE,PRODUCT_CODE,REBATE_CODE,REALISED_REBATE_VAL");
				$xcontentx .= "\r\n";
			
					foreach($arrContent as $val){
						//$xcontentx .= trim(date("Y-m-d h:i:s",strtotime($myrow['DATE']))).",";
						$xcontentx .= trim($val['INVOICE_ID']).",";
						$xcontentx .= trim($val['ORG_ID']).",";
						$xcontentx .= trim($val['SEGMENT1']).",";
						$xcontentx .= trim($val['VENDOR_NAME']).",";
						$xcontentx .= trim($val['INVOICE_NUM']).",";
						$xcontentx .= trim($val['invoice_date']).",";
						$xcontentx .= trim($val['INVOICE_AMOUNT']).",";
						$xcontentx .= trim($val['PAIDAMT']).",";
						$xcontentx .= trim($val['DESCRIPTION']).",";
						$xcontentx .= trim($val['CHECK_NUMBER']);
						$xcontentx .= trim($val['CHECK_DATE']);
						$xcontentx .= trim($val['CHECK_AMOUNT']);
						$xcontentx .= "\r\n";
					}
				
			}
			$create = fopen($file_desti.$filename, "x"); //uses fopen to create our file.
			fwrite($create, $xcontentx);
			fclose($create);
		
	}
	
	function dailyPaidProcessXls($dteFrom,$dteTo,$company) {
		
		$dteFrom = date("Y-m-d", strtotime("$dteFrom", time()));
		$dteTo = date("Y-m-d", strtotime("$dteTo +1 day", time()));
		
		if($company == "0"){
			$orgId = "85,87,133,89,91,113,153";
		}
		else
		{
			$orgId = $company;
		}
		
		$turnOnAnsiNulls = "SET ANSI_NULLS ON";
		$turnOnAnsiWarn = "SET ANSI_WARNINGS ON";

		$sql="
		select	ORAPROD.INVOICE_ID,ORAPROD.ORG_ID,ORAPROD.SEGMENT1,ORAPROD.VENDOR_NAME,ORAPROD.INVOICE_NUM,
				ORAPROD.INVOICE_DATE,ORAPROD.INVOICE_AMOUNT,ORAPROD.PAIDAMT,ORAPROD.DESCRIPTION,ORAPROD.CHECK_NUMBER,ORAPROD.CHECK_DATE,ORAPROD.CHECK_AMOUNT,ORAPROD.CREATION_DATE
		from openquery(ORAPROD,'
				SELECT 
				ap_invoices_all.INVOICE_ID,
				ap_invoices_all.ORG_ID,
				ap_suppliers.SEGMENT1,
				ap_suppliers.VENDOR_NAME,
				ap_invoices_all.INVOICE_NUM,
				ap_invoices_all.invoice_date,
				ap_invoices_all.INVOICE_AMOUNT,
				ap_invoice_payments_all.AMOUNT AS PAIDAMT,
				ap_invoices_all.DESCRIPTION,
				ap_checks_all.CHECK_NUMBER,
				ap_checks_all.CHECK_DATE,
				ap_checks_all.AMOUNT AS CHECK_AMOUNT,
				ap_checks_all.CREATION_DATE
				FROM
				ap_invoices_all
				INNER JOIN ap_suppliers
				ON ap_suppliers.VENDOR_ID = ap_invoices_all.VENDOR_ID
				LEFT JOIN ap_invoice_payments_all
				ON ap_invoices_all.INVOICE_ID = ap_invoice_payments_all.INVOICE_ID
				LEFT JOIN ap_checks_all
				ON ap_invoice_payments_all.CHECK_ID = ap_checks_all.CHECK_ID
				WHERE ap_checks_all.creation_date >= to_date(''$dteFrom'') and ap_checks_all.creation_date <= to_date(''$dteTo'') 
				and ap_invoices_all.ORG_ID in ($orgId)
				ORDER BY
				ap_invoices_all.ORG_ID,
				ap_checks_all.CHECK_NUMBER
			') ORAPROD
		";
		$this->execQry($turnOnAnsiNulls);
		$this->execQry($turnOnAnsiWarn);
		return $this->getArrRes($this->execQry($sql));
		
	}
	
}
?>