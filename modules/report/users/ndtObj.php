<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class ndtObj extends commonObj {
    
    # NDT VENDOR LIST
    function getVendorList(){

        $sql = "
        DECLARE @List varchar(MAX)

        SELECT @List = COALESCE(@List + ',', '') + '''''' + Cast(SUPPLIER_NO As varchar(10)) + ''''''
        FROM ORS_tblNdtSupplier

        SELECT @List As 'List'
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }

    # STS DETAILS
	function viewNdt($dteFrom,$company) {
        
        
        $listOfVendor = $this->getVendorList();
        $listOfVendor = trim($listOfVendor['List'],',');   
        
        $dteFrom = strtotime($dteFrom);
        $mmsDate = date('ymd',$dteFrom); 
        
        if($company == "87"){
            $library = "mm760lib";
            $filterCompany = "
            AND TBLSTR.STRNUM in (select STRNUM from openquery(pgjda, 'select * from mm760lib.tblstr') where STCOMP = 700
            and STRNAM NOT LIKE 'X%'
            and STRNUM < 900
            and STRNUM <> 805)
            ";   
        }                                                       
        else if($company == "85"){
            $library = "mm760lib";
            $filterCompany = "
            AND TBLSTR.STRNUM in (select TBLSTR.STRNUM from openquery(pgjda, 'select * from mm760lib.TBLSTR') TBLSTR 
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
            $library = "mm760lib";
            $filterCompany = "
            AND TBLSTR.STRNUM in (select STRNUM from openquery(pgjda, 'select * from mm760lib.tblstr') where STCOMP = 302
            and STRNAM NOT LIKE 'X%'
            and STRNUM < 900
            and STRNUM <> 805
            and STSHRT <> 'SBCHO')
            ";   
        }
        else if($company == "113"){
            $library = "mmneslib";
            $filterCompany = "
            AND TBLSTR.STRNUM in (select STRNUM from openquery(pgjda, 'select * from mmneslib.tblstr') where STCOMP = 811
            and STRNUM < 900)
            ";   
        }
        else if($company == "153"){
            $library = "mmneslib";
            $filterCompany = "
            AND TBLSTR.STRNUM in (select STRNUM from openquery(pgjda, 'select * from mmneslib.tblstr') where STCOMP = 810
            and STRNUM < 900)
            ";   
        }
        else
        {    
            $filterCompany = '';
        }
			
		$sql="
		SELECT 
            AICMP as ORG_UNIT,
            JDAX.AINUM as SUPPLIER,
            APSUPP.ASNAME as SUPPLIERNAME,
            JDAX.AIAMT as AMOUNT,
            JDAX.AIDTIV as INVOICEDATE,
            JDAX.AIDTPS as POSTINGDATE,
            JDAX.AIINV as INVOICENUMBER,
            TBLSTR.STRNUM as LOCATION, 
            TBLSTR.STSHRT as STORE,
            JDAX.AITRMS as TERMS 
        FROM 
            openquery(pgjda,'SELECT * FROM {$library}.apopen 
        WHERE 
            AIDTIV = ''{$mmsDate}'' ') as JDAX
        LEFT JOIN 
            (SELECT * FROM OPENQUERY(pgjda,'SELECT * FROM {$library}.tblstr')) TBLSTR ON JDAX.AISTR = TBLSTR.STRNUM 
        LEFT JOIN
            (SELECT * FROM OPENQUERY(pgjda,'SELECT * FROM {$library}.apsupp')) APSUPP ON JDAX.AINUM = APSUPP.ASNUM
        WHERE 
            AINOTE like '%RCR%'
            AND AITRMS = 100
            {$filterCompany}
		";      
		return $this->getArrRes($this->execQry($sql));
        // AINUM IN({$listOfVendor}) 
	}
    
    # ORACLE DATA
    function findOracleDetails($invoice,$supplier,$orgId){
        
        if($orgId == ""){
            $orgIdQry = "";    
        }else{
            $orgIdQry = "and ap_invoices_all.org_id = ''$orgId''";  
        }
        
        $replace_single_qoute = str_replace("'","",$invoice);
        
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
                where ap_invoices_all.invoice_num = ''$replace_single_qoute''
                and ap_suppliers.segment1 = ''$supplier''
                and (ap_invoices_all.source = ''PO'' OR   ap_invoices_all.source = ''Manual Invoice Entry'')
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