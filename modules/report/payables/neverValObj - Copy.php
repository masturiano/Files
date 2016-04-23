<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");

class neverValObj extends commonObj {

	function loginNotExist($uName,$pWord) {
		$sqlLogin = "SELECT * FROM ORS_tblUsers where userName='$uName' and userPass='$pWord' and userStat='A'";
		return $this->execQry($sqlLogin);
	}
	function login($uName,$pWord) {
		$sqlLogin = "SELECT * FROM ORS_tblUsers where userName='$uName' and userPass='$pWord' and userStat='A'";
		return $this->getSqlAssoc($this->execQry($sqlLogin));
	}
	
	function neverValDidNotPasXls($dteFrom,$company) {
		
		$dteFrom = date("Y-m-d", strtotime("$dteFrom", time()));
		//$dteTo = date("Y-m-d", strtotime("$dteTo +1 day", time()));
		
		if($company == "0"){
			$orgId = "85,87,133";
		}
		else
		{
			$orgId = $company;
		}
		
		$turnOnAnsiNulls = "SET ANSI_NULLS ON";
		$turnOnAnsiWarn = "SET ANSI_WARNINGS ON";

		$sql="
		select	ORAPROD.Org_id,ORAPROD.Segment1,ORAPROD.Invoice_Num,ORAPROD.Source,ORAPROD.Invoice_Date,
				ORAPROD.Creation_Date,ORAPROD.Invoice_Amount,ORAPROD.Batch_Name,ORAPROD.Attribute13,ORAPROD.Vendor_Site_Code
		from openquery(ORAPROD,'
				SELECT
				A.Org_id, 
				B.Segment1, 
				A.Invoice_Num, 
				A.Source, 
				A.Invoice_Date, 
				A.Creation_Date, 
				A.Invoice_Amount,
				C.Batch_Name,
				A.Attribute13,
				E.Vendor_Site_Code
				From Ap_Invoices_All A
				left join ap_suppliers B on A.vendor_id = B.vendor_id
				left join Ap_Batches_All C on A.batch_id = C.batch_id
				left join Ap_Supplier_Sites_All E on  A.vendor_site_id = E.vendor_site_id
				Where 
				A.Org_Id in ($orgId) 
				And A.Creation_Date >= to_date(''$dteFrom'')
				And A.Invoice_Id not In (Select D.Invoice_Id From Ap_Invoice_Distributions_All D Where D.Org_Id in ($orgId)) 
				And A.Cancelled_Date Is Null
				Order By A.Org_id,B.Segment1,A.Invoice_Date
			') ORAPROD
		";
		$this->execQry($turnOnAnsiNulls);
		$this->execQry($turnOnAnsiWarn);
		return $this->getArrRes($this->execQry($sql));
		
	}
	
}
?>