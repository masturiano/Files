<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class leasingSetupObj extends commonObj {
    
    function viewSupplier() {
            
        $sql="
        SELECT DISTINCT APSUPP.ASNAME AS suppName, APSUPP.ASNUM AS suppCode, CAST(APSUPP.ASNUM AS varchar) + ' - ' + APSUPP.ASNAME AS suppCodeName
        FROM APSUPP 
        WHERE APSUPP.ASNAME not like '%(NTBU)%'
        AND APSUPP.ASNAME <> '' AND APSUPP.ASNAME is not null
        ORDER BY APSUPP.ASNUM
        ";
        return $this->getArrRes($this->execQry($sql));
    }

	function viewCon($dteFrom,$dteTo,$supCode,$company,$strNum) {
        
        if($supCode == "0"){
            $filterSup = '';
        }
        else
        {
            $filterSup = "and tblapconcessinvoice.suppCode = ''$supCode''";
        }
        
        if($company == "0"){
            $filterCompany = '101,102,103,104,105,801,802,803,804,805,806,807,808,700,302';
        }
        else
        {
            $filterCompany = $company;
        }
        
        if($strNum == "0"){
            $filterStore = "";
        }
        else
        {
            $filterStore = "and tblapconcessinvoice.strCode = ''$strNum''";
        }
			
		$sql="
		select 
        CONCESS.strCode,
        CONCESS.suppCode,
        CONCESS.refNo,
        CONCESS.invNo,
        CONCESS.invDate,
        CONCESS.dueDate,
        CONCESS.vouchedDate,
        CONCESS.invAmt,
        CONCESS.uploadedAmt
        from openquery(CONCESS,'select 
        tblapconcessinvoice.strCode,
        tblapconcessinvoice.suppCode,
        tblapconcessinvoice.refNo,
        tblapconcessinvoice.invNo,
        tblapconcessinvoice.invDate,
        tblapconcessinvoice.dueDate, 
        tblapconcesshdr.vouchedDate,  
        tblapconcessinvoice.invAmt,
        tblapconcessinvoice.uploadedAmt
        FROM
        tblapconcessinvoice
        left join tblapconcesshdr on tblapconcesshdr.refno = tblapconcessinvoice.refno
        and tblapconcesshdr.compCode = tblapconcessinvoice.compCode
        and tblapconcesshdr.strCode = tblapconcessinvoice.strCode
        and tblapconcesshdr.suppCode = tblapconcessinvoice.suppCode
        WHERE 
        tblapconcessinvoice.voucherNo is not null
        and tblapconcessinvoice.apBatchNo is not null 
        and tblapconcessinvoice.compCode in ($filterCompany)  
        and tblapconcesshdr.vouchedDate between ''$dteFrom'' and ''$dteTo''
        $filterSup
        $filterStore') CONCESS
		";      
		return $this->getArrRes($this->execQry($sql));
	}
    
    function viewCon2($dteFrom,$dteTo,$cusNum,$company,$strShort) {
        $sql="
        select Col001,Col004 from dbOlic..tbl_efd_invoice where Col001 = 'EFD014213092'
        ";
        return $this->getArrRes($this->execQry($sql));
    }
	
	function findCustomer($term){
		$sql = "select cusnum,cast(cusnum as nvarchar)+' - '+cast(ORS_tblCIMCUS.FULL_NAME as nvarchar) as dispCusName 
				from ORS_tblARZMST 
				LEFT OUTER JOIN ORS_tblCIMCUS
				on ORS_tblCIMCUS.CUSTOMER_NUMBER = ORS_tblARZMST.cusnum
				WHERE     (ORS_tblARZMST.cusnum like '%$term%') 
				";
		/*$sql = "SELECT     TOP 10 CAST(INUMBR AS nvarchar(50)) + ' - ' + IDESCR AS combSkuDesc,
				CAST(INUMBR AS varchar) AS 				INUMBR
				FROM         tblsku
				WHERE     (INUMBR LIKE '%$term%') 
				";*/

		return $this->getArrRes($this->execQry($sql));
	}
	
	function checkCustomerNumber($cusNum){
		$sql = "
		select cusnum from ORS_tblARZMST where cusnum = '{$cusNum}'
		";
		return $this->getRecCount($this->execQry($sql));
	}
	
	function findSite(){
		$sql = "select TBLSTR.strnum,TBLSTR.stshrt,TBLSTR.stshrt+' - ('+TBLSTR.strnam+')' as strShrtName from TBLSTR
				where (TBLSTR.STRNAM NOT LIKE 'X%')
				and (TBLSTR.STCOMP in (101,102,103,104,105,801,802,803,804,805,806,807,808,700))
				and (TBLSTR.STRNUM < 900)
				order by stshrt
				";
		/*$sql = "SELECT     TOP 10 CAST(INUMBR AS nvarchar(50)) + ' - ' + IDESCR AS combSkuDesc,
				CAST(INUMBR AS varchar) AS 				INUMBR
				FROM         tblsku
				WHERE     (INUMBR LIKE '%$term%') 
				";*/

		return $this->getArrRes($this->execQry($sql));
	}
	
    function findStore($strShort){
        $sql = "select strnum,strnam from TBLSTR
                where stshrt = '{$strShort}'
                ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    function findSuppName($suppCode) {
            
        $sql="
        SELECT DISTINCT APSUPP.ASNAME AS suppName, APSUPP.ASNUM AS suppCode, CAST(APSUPP.ASNUM AS varchar) + ' - ' + APSUPP.ASNAME AS suppCodeName
        FROM APSUPP 
        WHERE APSUPP.ASNAME not like '%(NTBU)%'
        AND APSUPP.ASNAME <> '' AND APSUPP.ASNAME is not null
        AND APSUPP.ASNUM = '$suppCode'
        ORDER BY APSUPP.ASNAME
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    function findStore2($strCode){
        $sql = "select strnum,strnam from TBLSTR
                where strnum = '{$strCode}'
                ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    function findComments($leasId,$payPurCode){
        $sql="
            select  ORAPROD.term_comments,ORAPROD.payment_purpose_code,ORAPROD.start_date,ORAPROD.end_date        
            from 
                openquery(ORAPROD,'
                    select  lease_id,term_comments,payment_purpose_code,start_date,end_date
                    from pn_payment_terms_all 
                    where lease_id = ''$leasId''
                    and payment_purpose_code = ''$payPurCode''
                    and term_comments is not null
                    -- and rownum = 1
                    order by start_date desc

                ') ORAPROD
            ";       
        //return $this->getSqlAssoc($this->execQry($sql));
        return $this->getArrRes($this->execQry($sql));
    }
    
    function findCreationDate($invoice,$vendor){
        $sql="
            select  ORAPROD.creation_date     
            from 
                openquery(ORAPROD,'
                    select 
                    ap_invoices_all.creation_date
                    from ap_invoices_all 
                    left join ap_suppliers on ap_invoices_all.vendor_id = ap_suppliers.vendor_id
                    left join ap_payment_schedules_all on ap_invoices_all.invoice_id = ap_payment_schedules_all.invoice_id
                    where
                    ap_invoices_all.invoice_num = ''$invoice''
                    and ap_suppliers.segment1 = ''$vendor''
                    and source = ''CON''
                ') ORAPROD
            ";       
        //return $this->getSqlAssoc($this->execQry($sql));
        return $this->getSqlAssoc($this->execQry($sql));
    }
	
}
?>