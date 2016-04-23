<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class atubObj extends commonObj {
    
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
    
    function viewCustomer() {
            
        $sql="
        select cusnum,cast(cusnum as nvarchar)+' - '+cast(ORS_tblCIMCUS.FULL_NAME as nvarchar) as dispCusName 
        from ORS_tblARZMST 
        LEFT OUTER JOIN ORS_tblCIMCUS
        on ORS_tblCIMCUS.CUSTOMER_NUMBER = ORS_tblARZMST.cusnum
        WHERE ORS_tblARZMST.cusnum not like '%NTBU%'
        AND ORS_tblCIMCUS.FULL_NAME not like '%NTBU%' 
        AND ORS_tblCIMCUS.FULL_NAME not like '%NOT TO BE USE%'     
        ORDER BY ORS_tblARZMST.cusnum
        ";
        return $this->getArrRes($this->execQry($sql));
    }

	function viewAtub($dteFrom,$dteTo,$cusCode,$company,$strNum) {
        
        if($cusCode == "0"){
            $filterCus = '';
        }
        else
        {
            $filterCus = "and ATUB_detail.mmrbs_dtl_tcode = '$cusCode'";
        }
        
        if($company == "87"){
            $filterCompany = "
            and ATUB_detail.mmrbs_dtl_strcode in (select STRNUM from openquery(pgjda, 'select * from mmpgtlib.tblstr') where STCOMP = 700
            and STRNAM NOT LIKE 'X%'
            and STRNUM < 900
            and STRNUM <> 805)
            ";   
        }
        else if($company == "85"){
            $filterCompany = "
            and ATUB_detail.mmrbs_dtl_strcode in (select TBLSTR.STRNUM from openquery(pgjda, 'select * from mmpgtlib.TBLSTR') TBLSTR 
            LEFT JOIN
            OPENQUERY(pgjda, 'select * from mmpgtlib.INVADIx1') INVADIx1
            ON  TBLSTR.STRNUM = INVADIx1.STRNUM
            where INVADIx1.ADINUM in (4,5,6)
            and TBLSTR.STSHRT not in (
                select stshrt from openquery(pgjda, 'select * from mmpgtlib.tblstr') where stcomp = 700
                and STRNAM NOT LIKE 'X%'
                and STRNUM < 900
                and STRNUM <> 805
            ))
            ";   
        }
        else if($company == "302"){
            $filterCompany = "
            and ATUB_detail.mmrbs_dtl_strcode in (select STRNUM from openquery(pgjda, 'select * from mmpgtlib.tblstr') where STCOMP = 302
            and STRNAM NOT LIKE 'X%'
            and STRNUM < 900
            and STRNUM <> 805
            and STSHRT <> 'SBCHO')
            ";   
        }
        else
        {
            $filterCompany = '';
        }
        
        if($strNum == "0"){
            $filterStore = "";
        }
        else
        {
            $filterStore = "and ATUB_detail.mmrbs_dtl_strcode = '$strNum'";
        }
			
		$sql="
		select 
        cast(ATUB_detail.mmrbs_dtl_type as nvarchar)+cast(ATUB_detail.mmrbs_dtl_soa as nvarchar) as invoice,
        ATUB_header.mmrbs_posted,
        ATUB_header.mmrbs_dateposted,
        ATUB_header.mmrbs_period_from,
        ATUB_header.mmrbs_period_to,
        ATUB_header.mmrbs_strcode,
        ATUB_detail.mmrbs_dtl_total,
        ATUB_detail.mmrbs_dtl_tcode,
        ATUB_detail.mmrbs_dtl_tname,
        ATUB_detail.mmrbs_dtl_strcode
        from openquery([192.168.200.228], 'select * from adpro.dbo.tbl_mmrbs_detail_atub') as ATUB_detail
        left join 
        (select * from openquery([192.168.200.228], 'select * from adpro.dbo.tbl_mmrbs_header_atub 
            where mmrbs_dateposted > ''$dteFrom'' and  mmrbs_dateposted < ''$dteTo''
        ')) as ATUB_header
        on ATUB_detail.mmrbs_dtl_batchserial = ATUB_header.mmrbs_batchserial
        and ATUB_detail.mmrbs_dtl_strcode = ATUB_header.mmrbs_strCode
        where ATUB_header.mmrbs_posted = 1
        $filterCus
        $filterCompany
        $filterStore
        order by ATUB_header.mmrbs_dateposted desc
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
    
    function findCreationDate($invoice,$customer,$orgId){
        
        if($customer == ""){  
            $customerQry = "";       
        }else{
            $customerQry = "and hz_cust_accounts.account_number = ''$customer''";       
        }
        
        if($orgId == ""){
            $orgIdQry = "";    
        }else{
            $orgIdQry = "and RA_CUSTOMER_TRX_ALL.org_id = ''$orgId''";  
        }
        
        $sql="
            select  
            ORAPROD.creation_date,     
            ORAPROD.org_id,     
            ORAPROD.account_number,     
            ORAPROD.description     
            from 
                openquery(ORAPROD,'
                    SELECT 
                    RA_CUSTOMER_TRX_ALL.org_id,
                    RA_CUSTOMER_TRX_ALL.creation_date,
                    hz_cust_accounts.account_number,
                    ra_customer_trx_lines_all.description
                    FROM RA_CUSTOMER_TRX_ALL
                    JOIN ar_payment_schedules_all on RA_CUSTOMER_TRX_ALL.trx_number = ar_payment_schedules_all.trx_number
                    LEFT JOIN ra_customer_trx_lines_all
                    ON ra_customer_trx_lines_all.CUSTOMER_TRX_ID = RA_CUSTOMER_TRX_ALL.CUSTOMER_TRX_ID
                    INNER JOIN ar_payment_schedules_all
                    ON RA_CUSTOMER_TRX_ALL.TRX_NUMBER = ar_payment_schedules_all.TRX_NUMBER
                    INNER JOIN HZ_CUST_ACCOUNTS
                    ON ar_payment_schedules_all.CUSTOMER_ID          = HZ_CUST_ACCOUNTS.CUST_ACCOUNT_ID
                    and ar_payment_schedules_all.org_id = ra_customer_trx_lines_all.org_id
                    where 
                    RA_CUSTOMER_TRX_ALL.trx_number = ''$invoice'' 
                    $customerQry
                    $orgIdQry
                    and RA_CUSTOMER_TRX_ALL.interface_header_context = ''ATUB''
                ') ORAPROD
            ";       
        //return $this->getSqlAssoc($this->execQry($sql));
        return $this->getSqlAssoc($this->execQry($sql));
    }
	
}
?>