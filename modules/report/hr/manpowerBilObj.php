<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class manpowerBilObj extends commonObj {

	function viewConCus() {
			
			$sql="
			select cusnum from ORS_tblARZMST where cusnum = cusNum
			";
			return $this->getArrRes($this->execQry($sql));
	}
		
	function manpowerBilling($dteFrom,$dteTo) {
        
        $listOfVendor = $this->getVendorList();
        $listOfVendor = trim($listOfVendor['List'],',');
		$dteTo = date("Y-m-d", strtotime("$dteTo +1 day", time()));

        
		$sql="
		select	ORAPROD.INVOICE_ID,ORAPROD.SEGMENT1,ORAPROD.VENDOR_NAME,
                ORAPROD.ORG_ID,
                ORAPROD.VENDOR_SITE_CODE,
				ORAPROD.INVOICE_NUM,ORAPROD.INVOICE_DATE,ORAPROD.INVOICE_AMOUNT,ORAPROD.AMOUNT_REMAINING,ORAPROD.SOURCE,
                ORAPROD.DESCRIPTION,ORAPROD.MATCH_STATUS_FLAG,ORAPROD.CREATION_DATE,ORAPROD.BANK_ACCOUNT_NAME,ORAPROD.CHECK_NUMBER,ORAPROD.CHECK_DATE,
                ORAPROD.CHECK_SIGNED_DATE,ORAPROD.CHECK_RELEASED_DATE
		from 
			openquery(ORAPROD,'
                SELECT DISTINCT ap_invoices_all.INVOICE_ID,
                ap_suppliers.SEGMENT1,
                ap_suppliers.VENDOR_NAME,
                ap_invoices_all.ORG_ID,
                ap_supplier_sites_all.VENDOR_SITE_CODE,
                ap_invoices_all.INVOICE_NUM,
                ap_invoices_all.INVOICE_DATE,
                ap_invoices_all.INVOICE_AMOUNT,
                ap_payment_schedules_all.AMOUNT_REMAINING,
                ap_invoices_all.SOURCE,
                ap_invoices_all.DESCRIPTION,
                ap_invoice_distributions_all.MATCH_STATUS_FLAG,
                ap_invoices_all.CREATION_DATE,
                ap_checks_all.BANK_ACCOUNT_NAME,
                ap_checks_all.CHECK_NUMBER,
                ap_checks_all.CHECK_DATE,
                XXIOM_CHECK_TAGGING.CHECK_SIGNED_DATE,
                XXIOM_CHECK_TAGGING.CHECK_RELEASED_DATE
                FROM ap_suppliers
                INNER JOIN ap_invoices_all
                ON ap_suppliers.VENDOR_ID = ap_invoices_all.VENDOR_ID
                LEFT JOIN ap_payment_schedules_all
                ON ap_invoices_all.INVOICE_ID = ap_payment_schedules_all.INVOICE_ID
                INNER JOIN ap_invoice_distributions_all
                ON ap_invoice_distributions_all.INVOICE_ID = ap_invoices_all.INVOICE_ID
                INNER JOIN ap_supplier_sites_all
                ON ap_supplier_sites_all.VENDOR_SITE_ID = ap_invoices_all.VENDOR_SITE_ID
                LEFT JOIN ap_invoice_payments_all
                ON ap_invoice_payments_all.INVOICE_ID = ap_invoices_all.INVOICE_ID
                LEFT JOIN ap_checks_all
                ON ap_invoice_payments_all.CHECK_ID = ap_checks_all.CHECK_ID
                LEFT JOIN XXIOM_CHECK_TAGGING
                ON ap_checks_all.CHECK_ID = XXIOM_CHECK_TAGGING.CHECK_ID
                WHERE ap_suppliers.SEGMENT1 IN ($listOfVendor)
                AND XXIOM_CHECK_TAGGING.CHECK_RELEASED_DATE >= ''{$dteFrom}'' AND XXIOM_CHECK_TAGGING.CHECK_RELEASED_DATE <= ''{$dteTo}''
                AND ap_invoices_all.ORG_ID                        IN (87, 85, 133)
                AND ap_invoice_distributions_all.MATCH_STATUS_FLAG = ''A''
                AND ap_checks_all.STATUS_LOOKUP_CODE              <> ''VOIDED''
                ORDER BY ap_invoices_all.ORG_ID,
                ap_suppliers.SEGMENT1,
                ap_invoices_all.INVOICE_NUM
			') ORAPROD
		";
		return $this->getArrRes($this->execQry($sql));
        // EXECUTE master.dbo.xp_cmdshell  'bcp "SELECT top 100 * from dbOracle..tbl_Manpower" queryout C:\EXPORTFILE\Manpower.csv -t, -c     -Usa -Psa -SWIN-PGCHEQUE'
	}
    
    function manpowerBillingCsv() {
        
        $listOfVendor = $this->getVendorList();
        $listOfVendor = trim($listOfVendor['List'],',');
        
        $sql="
        truncate table ORS_tblManpower
        ";
        
        $sql2="
        insert into ORS_tblManpower values('INVOICE_ID','SEGMENT1','VENDOR_NAME','_ORG_ID','VENDOR_SITE_CODE','INVOICE_NUM','INVOICE_DATE','INVOICE_AMOUNT','AMOUNT_REMAINING','SOURCE','DESCRIPTION','MATCH_STATUS_FLAG','CREATION_DATE','BANK_ACCOUNT_NAME','CHECK_NUMBER','CHECK_SIGNED_DATE','CHECK_RELEASED_DATE','STORE_CODE')
        ";
        
        $sql3="    
        insert into ORS_tblManpower  
        select   ORAPROD.INVOICE_ID,ORAPROD.SEGMENT1,ORAPROD.VENDOR_NAME,
                ORAPROD.ORG_ID,
                ORAPROD.VENDOR_SITE_CODE,
                ORAPROD.INVOICE_NUM,ORAPROD.INVOICE_DATE,ORAPROD.INVOICE_AMOUNT,ORAPROD.AMOUNT_REMAINING,ORAPROD.SOURCE,
                ORAPROD.DESCRIPTION,ORAPROD.MATCH_STATUS_FLAG,ORAPROD.CREATION_DATE,ORAPROD.BANK_ACCOUNT_NAME,ORAPROD.CHECK_NUMBER,
                ORAPROD.CHECK_SIGNED_DATE,ORAPROD.CHECK_RELEASED_DATE,'' AS STORE_CODE        
        from 
            openquery(ORAPROD,'
                SELECT DISTINCT ap_invoices_all.INVOICE_ID,
                ap_suppliers.SEGMENT1,
                ap_suppliers.VENDOR_NAME,
                ap_invoices_all.ORG_ID,
                ap_supplier_sites_all.VENDOR_SITE_CODE,
                ap_invoices_all.INVOICE_NUM,
                ap_invoices_all.INVOICE_DATE,
                ap_invoices_all.INVOICE_AMOUNT,
                ap_payment_schedules_all.AMOUNT_REMAINING,
                ap_invoices_all.SOURCE,
                ap_invoices_all.DESCRIPTION,
                ap_invoice_distributions_all.MATCH_STATUS_FLAG,
                ap_invoices_all.CREATION_DATE,
                ap_checks_all.BANK_ACCOUNT_NAME,
                ap_checks_all.CHECK_NUMBER,
                XXIOM_CHECK_TAGGING.CHECK_SIGNED_DATE,
                XXIOM_CHECK_TAGGING.CHECK_RELEASED_DATE
                FROM ap_suppliers
                INNER JOIN ap_invoices_all
                ON ap_suppliers.VENDOR_ID = ap_invoices_all.VENDOR_ID
                LEFT JOIN ap_payment_schedules_all
                ON ap_invoices_all.INVOICE_ID = ap_payment_schedules_all.INVOICE_ID
                INNER JOIN ap_invoice_distributions_all
                ON ap_invoice_distributions_all.INVOICE_ID = ap_invoices_all.INVOICE_ID
                INNER JOIN ap_supplier_sites_all
                ON ap_supplier_sites_all.VENDOR_SITE_ID = ap_invoices_all.VENDOR_SITE_ID
                LEFT JOIN ap_invoice_payments_all
                ON ap_invoice_payments_all.INVOICE_ID = ap_invoices_all.INVOICE_ID
                LEFT JOIN ap_checks_all
                ON ap_invoice_payments_all.CHECK_ID = ap_checks_all.CHECK_ID
                LEFT JOIN XXIOM_CHECK_TAGGING
                ON ap_checks_all.CHECK_ID = XXIOM_CHECK_TAGGING.CHECK_ID
                WHERE ap_suppliers.SEGMENT1 IN ($listOfVendor)
                AND ap_invoices_all.ORG_ID                        IN (87, 85, 133)
                AND ap_invoice_distributions_all.MATCH_STATUS_FLAG = ''A''
                AND ap_checks_all.STATUS_LOOKUP_CODE              <> ''VOIDED''
                ORDER BY ap_invoices_all.ORG_ID,
                ap_suppliers.SEGMENT1,
                ap_invoices_all.INVOICE_NUM
            ') ORAPROD
        ";
        
        $sql4__ = "
        SELECT
        INVOICE_ID,
        SEGMENT1,
        VENDOR_NAME,
        ORG_ID,
        VENDOR_SITE_CODE,
        INVOICE_NUM,
        INVOICE_DATE,
        INVOICE_AMOUNT,
        AMOUNT_REMAINING,
        SOURCE,
        DESCRIPTION,
        MATCH_STATUS_FLAG,
        CREATION_DATE,
        BANK_ACCOUNT_NAME,
        CHECK_NUMBER,
        CHECK_SIGNED_DATE,
        CHECK_RELEASED_DATE
        from ORS_tblManpower
        ";
        
        $sql5="
        UPDATE ORS_tblManpower set STORE_CODE = (select strnum from openquery(pgjda, 'select strnum,stshrt from mmpgtlib.tblstr') where stshrt = VENDOR_SITE_CODE) WHERE STORE_CODE <> 'STORE_CODE'
        ";
        
        /*
        $sql5__ = "
        SELECT  CAST(
        isnull(cast(INVOICE_ID as varchar),'')+','+
        '"'+cast(SEGMENT1 as varchar)+'",'+
        '"'+cast(VENDOR_NAME as varchar)+'",'+
        '"'+cast(ORG_ID as varchar)+'",'+
        '"'+cast(VENDOR_SITE_CODE as varchar)+'",'+
        '"'+cast(INVOICE_NUM as varchar)+'",'+
        '"'+isnull(cast(INVOICE_DATE as varchar),'')+'",'+
        cast(INVOICE_AMOUNT as varchar)+','+
        cast(AMOUNT_REMAINING as varchar)+','+
        '"'+cast(SOURCE as varchar)+'",'+
        '"'+cast(DESCRIPTION as varchar)+'",'+
        '"'+cast(MATCH_STATUS_FLAG as varchar)+'",'+
        isnull(cast(CREATION_DATE as varchar),'')+','+
        '"'+cast(BANK_ACCOUNT_NAME as varchar)+'",'+
        cast(CHECK_NUMBER as varchar)+','+
        isnull(cast(CHECK_SIGNED_DATE as varchar),'')+','+
        isnull(cast(CHECK_RELEASED_DATE as varchar),'') AS text) as c
        from ORS_tblManpower
        ORDER BY ORG_ID,
        SEGMENT1,
        INVOICE_NUM
        ";
        */
        
        $assServerDate = $this->serverDate();

        $serverDate = $assServerDate['CurrentDateTime'];            

        $serverCurrentDate = date("mdy_his", strtotime("$serverDate", time()));

        $fileExt = ".CSV";
        $filename = "MANPOWER_".$serverCurrentDate.$fileExt;
        
        $fileDesti = "C:\EXPORTFILE\\";
        
        $sql6="
        EXECUTE master.dbo.xp_cmdshell  'bcp \"SELECT * from dbOracle..ORS_TblManpowerView \"  queryout $fileDesti$filename -t -c  -Uoracle -Poracle@2015 -SWIN-PGCHEQUE'
        ";

        if($this->execQry($sql)){
            if($this->execQry($sql2)){
                if($this->execQry($sql3)){
                    if($this->execQry($sql5)){
                        return $this->execQry($sql6);     
                    }  
                }
            }  
        }
        
        //return $this->execQry($sql);
        // EXECUTE master.dbo.xp_cmdshell  'bcp "SELECT top 100 * from dbOracle..tbl_Manpower" queryout C:\EXPORTFILE\Manpower.csv -t, -c     -Usa -Psa -SWIN-PGCHEQUE'
        
        
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
		$sql = "select TBLSTR.stshrt,TBLSTR.stshrt+' - ('+TBLSTR.strnam+')' as strShrtName from TBLSTR
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
    
    function findCommentsAtub($invoice){
        $sql="
            select cast(ATUB_detail.mmrbs_dtl_type as nvarchar)+cast(ATUB_detail.mmrbs_dtl_soa as nvarchar) as invoice,ATUB_header.mmrbs_period_from,ATUB_header.mmrbs_period_to 
            from openquery(ATUB, 'select * from adpro.dbo.tbl_mmrbs_detail_atub') as ATUB_detail
            left join 
            (select * from openquery(ATUB, 'select * from adpro.dbo.tbl_mmrbs_header_atub')) as ATUB_header
            on ATUB_detail.mmrbs_dtl_batchserial = ATUB_header.mmrbs_batchserial
            and ATUB_detail.mmrbs_dtl_strcode = ATUB_header.mmrbs_strCode
            where (ATUB_detail.mmrbs_dtl_soa is not null and ATUB_detail.mmrbs_dtl_soa <> '')
            and cast(ATUB_detail.mmrbs_dtl_type as nvarchar)+cast(ATUB_detail.mmrbs_dtl_soa as nvarchar) = '$invoice'
            ";       
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    function getVendorList(){

        $sql = "
        DECLARE @List varchar(MAX)

        SELECT @List = COALESCE(@List + ',', '') + '''''' + Cast(SUPPLIER_NO As varchar(10)) + ''''''
        FROM ORS_Manpower_Supplier

        SELECT @List As 'List'
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    function serverDate(){

        $sql = "
        select GETDATE() as CurrentDateTime
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    function viewSupplier() {
        
        $sql="
        select 
            supplier_no,asname
        from 
            ORS_Manpower_Supplier a
        left join 
            sql_mmpgtlib.dbo.APSUPP b ON b.asnum = a.supplier_no
        order by 
            supplier_no;
        ";
        return $this->getArrRes($this->execQry($sql));
    }
}
?>