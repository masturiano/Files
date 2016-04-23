<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");

class invoiceStatusObj extends commonObj {

	function loginNotExist($uName,$pWord) {
		$sqlLogin = "SELECT * FROM ORS_tblUsers where userName='$uName' and userPass='$pWord' and userStat='A'";
		return $this->execQry($sqlLogin);
	}
	function login($uName,$pWord) {
		$sqlLogin = "SELECT * FROM ORS_tblUsers where userName='$uName' and userPass='$pWord' and userStat='A'";
		return $this->getSqlAssoc($this->execQry($sqlLogin));
	}
	
	function neverValDidNotPasXls($dteFrom,$dteTo,$company,$source,$stat,$valid) {
		
		$dteFrom = date("Y-m-d", strtotime("$dteFrom", time()));
        $dteTo = date("Y-m-d", strtotime("$dteTo +1 day", time()));
		//$dteTo = date("Y-m-d", strtotime("$dteTo +1 day", time()));
		
		if($company == "0"){
			$orgId = "85,87,133";
		}
		else
		{
			$orgId = $company;
		}
        
        if($source == "0"){
            $sourceType = "";
        }
        else
        {
            $sourceType = "And A.Source = ''$source''";
        }
        
        if($stat == "0"){
            $statType = "";
        }
        elseif($stat == "P")
        {
            $statType = "And A.amount_paid <> 0";
        }
        elseif($stat == "U")
        {
            $statType = "And A.amount_paid = 0"; 
        }else
        {
            $statType = ""; 
        }
        
        if($valid == "0"){
            $validation = "";
        }
        elseif($stat == "V")
        {
            $validation = "And A.Invoice_Id  In (Select D.Invoice_Id From Ap_Invoice_Distributions_All D)";
        }
        elseif($stat == "U")
        {
            $validation = "And A.Invoice_Id  Not In (Select D.Invoice_Id From Ap_Invoice_Distributions_All D)"; 
        }else
        {
            $validation = ""; 
        }
		
		$turnOnAnsiNulls = "SET ANSI_NULLS ON";
		$turnOnAnsiWarn = "SET ANSI_WARNINGS ON";

		$sql="
		select	ORAPROD.Org_id,ORAPROD.Segment1,ORAPROD.Invoice_Num,ORAPROD.Source,ORAPROD.Invoice_Date,
				ORAPROD.Creation_Date,ORAPROD.Invoice_Amount,ORAPROD.Amount_Paid,ORAPROD.Amount_Remaining,
                ORAPROD.Batch_Name,ORAPROD.Attribute13,ORAPROD.Vendor_Site_Code
		from openquery(ORAPROD,'
				SELECT
                A.Org_id, 
                B.Segment1, 
                A.Invoice_Num, 
                A.Source, 
                A.Invoice_Date, 
                A.Creation_Date, 
                A.Invoice_Amount,
                A.Amount_Paid,
                P.Amount_Remaining,
                C.Batch_Name,
                A.Attribute13,
                E.Vendor_Site_Code
                From Ap_Invoices_All A
                left join ap_suppliers B on A.vendor_id = B.vendor_id
                left join Ap_Batches_All C on A.batch_id = C.batch_id
                left join Ap_Supplier_Sites_All E on  A.vendor_site_id = E.vendor_site_id
                left join ap_payment_schedules_all P on A.invoice_id = P.invoice_id
                Where 
				A.Org_Id in ($orgId) 
				And (A.Creation_Date >= to_date(''$dteFrom'')
                and A.Creation_Date <= to_date(''$dteTo''))
				And A.Cancelled_Date Is Null
                And A.Source = ''RFP''
                $statType
                $validation
				Order By A.Org_id,B.Segment1,A.Creation_Date
			') ORAPROD
		";
		$this->execQry($turnOnAnsiNulls);
		$this->execQry($turnOnAnsiWarn);
		return $this->getArrRes($this->execQry($sql));
		
	}
    
    function findStore($strShort){
        $sql = "select strnum,strnam from TBLSTR
                where stshrt = '{$strShort}'
                ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
	
}
?>