<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class stsObj extends commonObj {

    # STS DETAILS
	function viewSts($dteFrom,$dteTo,$company) {
        
        if($company == "87"){
            $filterCompany = "
            where strCode in (select STRNUM from openquery(pgjda, 'select * from mm760lib.tblstr') where STCOMP = 700
            and STRNAM NOT LIKE 'X%'
            and STRNUM < 900
            and STRNUM <> 805)
            ";   
        }
        else if($company == "85"){
            $filterCompany = "
            where strCode in (select TBLSTR.STRNUM from openquery(pgjda, 'select * from mm760lib.TBLSTR') TBLSTR 
            LEFT JOIN
            OPENQUERY(pgjda, 'select * from mm760lib.INVADIx1') INVADIx1
            ON  TBLSTR.STRNUM = INVADIx1.STRNUM
            where INVADIx1.ADINUM in (4,5,6)
            and TBLSTR.STSHRT not in (
                select stshrt from openquery(pgjda, 'select * from mm760lib.tblstr') where stcomp = 700
                and STRNAM NOT LIKE 'X%'
                and STRNUM < 900
                and STRNUM <> 805
            ))
            ";   
        }
        else if($company == "133"){
            $filterCompany = "
            where strCode in (select STRNUM from openquery(pgjda, 'select * from mm760lib.tblstr') where STCOMP = 302
            and STRNAM NOT LIKE 'X%'
            and STRNUM < 900
            and STRNUM <> 805
            and STSHRT <> 'SBCHO')
            ";   
        }
        else if($company == "113"){
            $filterCompany = "
            where strCode in (select STSHRT from openquery(pgjda, 'select * from mmneslib.tblstr') where STCOMP = 810
            and STRNUM < 900)
            ";   
        }
        else if($company == "153"){
            $filterCompany = "
            where strCode in (select STSHRT from openquery(pgjda, 'select * from mmneslib.tblstr') where STCOMP = 811
            and STRNUM < 900)
            ";   
        }
        else
        {
            $filterCompany = '';
        }
			
		$sql="
		SELECT  * from openquery([192.168.200.229], 'select 
        tblTransType.typePrefix+CAST(tblStsDlyApHist.stsNo AS nvarchar) + ''-'' + CAST(tblStsDlyApHist.stsSeq AS nvarchar) as invoice,
        stsRefno,compCode,strCode,suppCode,
        stsApplyAmt,stsApplyDate,stsActualDate,uploadDate,
        uploadApFile,status,stsRemarks,stsPaymentMode,
        tblTransType.typeDesc
        FROM         pg_sts.dbo.tblStsDlyApHist
        LEFT OUTER JOIN
        pg_sts.dbo.tblTransType ON pg_sts.dbo.tblTransType.typeCode = pg_sts.dbo.tblStsDlyApHist.stsType
        WHERE tblStsDlyApHist.uploadDate between ''$dteFrom'' and ''$dteTo''
        ') STS
        {$filterCompany}
		";      
		return $this->getArrRes($this->execQry($sql));
	}
    
    # ORACLE DATA
    function findOracleDetails($invoice,$supplier,$orgId){
        
        if($orgId == ""){
            $orgIdQry = "";    
        }else{
            $orgIdQry = "and ap_invoices_all.org_id = ''$orgId''";  
        }
        
        $sql="
            select  
            ORAPROD.invoice_num,     
            ORAPROD.segment1,     
            ORAPROD.invoice_amount,     
            ORAPROD.description, 
            ORAPROD.attribute13,    
            ORAPROD.source,
            ORAPROD.creation_date
            from 
            openquery(ORAPROD,'
                select 
                ap_invoices_all.invoice_num,
                ap_suppliers.segment1,
                ap_invoices_all.invoice_amount,
                ap_invoices_all.description,
                ap_invoices_all.attribute13,
                ap_invoices_all.source,
                ap_invoices_all.creation_date
                from ap_invoices_all 
                left join ap_suppliers on ap_invoices_all.vendor_id = ap_suppliers.vendor_id
                where ap_invoices_all.invoice_num = ''$invoice''
                and ap_suppliers.segment1 = ''$supplier''
                and (ap_invoices_all.source = ''STS'' OR   ap_invoices_all.source = ''Manual Invoice Entry'')
                $orgIdQry
            ') ORAPROD
            ";       
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    function findSuppDetails($suppCode) {
            
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
    
    function findStoreDetails($strCode){
        $sql = "select strnum,strnam from TBLSTR
                where strnum = '{$strCode}'
                ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
	
}
?>