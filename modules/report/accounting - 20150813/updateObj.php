<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class updateObj extends commonObj {
    
    # Current Server Date
    
    function getCurDate(){
        $sql = "
        select CONVERT(VARCHAR,GETDATE(),23) as CurrentDateTime
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
	
    # Update Paid
    
	function cleartblVenPerPaid(){
		$sql = "
		TRUNCATE TABLE ORS_tblVenPerPaid
		";
		return $this->execQry($sql);
	}
	
	function updatetblVenPerPaid(){
        $curDate = $this->getCurDate();
        $varCurDate = $curDate['CurrentDateTime'];
        
        $varCurDateFirstDay =  date('Y-m-01',strtotime($varCurDate));
        
		$sql = "
		insert into ORS_tblVenPerPaid (org_id,invoice_id,invoice_num,vendor_id,segment1,check_number,check_date,terms_date,amount_paid,
            invoice_amount,amount_remaining,check_amount,amount_applied_to_check,status_lookup_code)
        select    ORAPROD.org_id,ORAPROD.invoice_id,ORAPROD.invoice_num,ORAPROD.vendor_id,ORAPROD.segment1,ORAPROD.check_number,ORAPROD.check_date,ORAPROD.terms_date,ORAPROD.amount_paid,
            ORAPROD.invoice_amount,ORAPROD.amount_remaining,ORAPROD.check_amount,ORAPROD.amount_applied_to_check,ORAPROD.status_lookup_code
        from 
        openquery(ORAPROD,'
            SELECT 
            ap_invoices_all.org_id,  
            ap_invoices_all.invoice_id,
            ap_invoices_all.invoice_num,
            ap_invoices_all.vendor_id,
            ap_suppliers.segment1,
            ap_checks_all.check_number,
            ap_checks_all.check_date,
            ap_invoices_all.terms_date,
            ap_invoices_all.amount_paid,
            ap_invoices_all.invoice_amount,
            ap_payment_schedules_all.amount_remaining,
            ap_checks_all.amount as check_amount,
            ap_invoice_payments_all.amount as amount_applied_to_check,
            ap_checks_all.status_lookup_code
            FROM ap_payment_schedules_all
            INNER JOIN ap_invoice_payments_all
            ON ap_payment_schedules_all.INVOICE_ID = ap_invoice_payments_all.INVOICE_ID
            INNER JOIN ap_checks_all
            ON ap_invoice_payments_all.CHECK_ID = ap_checks_all.CHECK_ID
            INNER JOIN ap_invoices_all
            ON ap_invoices_all.INVOICE_ID    = ap_payment_schedules_all.INVOICE_ID
            LEFT JOIN ap_suppliers
            ON ap_suppliers.VENDOR_ID = ap_invoices_all.VENDOR_ID
            WHERE 
            ap_checks_all.CHECK_DATE between to_date(''$varCurDateFirstDay'') and to_date(''$varCurDate'')
            and ap_checks_all.STATUS_LOOKUP_CODE <> ''VOIDED''
        ') ORAPROD
        ";
		return $this->execQry($sql);
	}
    
    # Update Outstanding - Current
    
    function cleartblVenPerOutstanding(){
        $sql = "
        TRUNCATE TABLE ORS_tblVenPerOutstanding
        ";
        return $this->execQry($sql);
    }
    
    function updatetblVenPerOutstanding(){
        $curDate = $this->getCurDate();
        $varCurDate = $curDate['CurrentDateTime'];
        
        # Summary
        /*
        $sql = " 
        insert into ORS_tblVenPerOutstanding (org_id,vendor_id,segment1,amount_paid,invoice_amount,amount_remaining)
        select    ORAPROD.org_id,ORAPROD.vendor_id,ORAPROD.segment1,ORAPROD.amount_paid,ORAPROD.invoice_amount,ORAPROD.amount_remaining
        from 
        openquery(ORAPROD,'
        SELECT 
        ap_invoices_all.org_id,  
        ap_invoices_all.vendor_id,
        ap_suppliers.segment1,
        sum(ap_invoices_all.amount_paid) as amount_paid,
        sum(ap_invoices_all.invoice_amount) as invoice_amount,
        sum(ap_payment_schedules_all.amount_remaining) as amount_remaining
        FROM ap_payment_schedules_all
        INNER JOIN ap_invoices_all
        ON ap_invoices_all.INVOICE_ID    = ap_payment_schedules_all.INVOICE_ID
        LEFT JOIN ap_suppliers
        ON ap_suppliers.VENDOR_ID = ap_invoices_all.VENDOR_ID
        WHERE 
        ap_payment_schedules_all.amount_remaining > 0
        and ap_invoices_all.terms_date <= to_date(''$varCurDate'')
        group by ap_invoices_all.org_id,  
        ap_invoices_all.vendor_id,
        ap_suppliers.segment1
        order by ap_invoices_all.org_id,ap_suppliers.segment1
        ') ORAPROD
        ";
        */
        
        # Detail
        $sql = " 
        insert into ORS_tblVenPerOutstanding (org_id,vendor_id,segment1,amount_paid,invoice_amount,amount_remaining,terms_date,invoice_num)
        select    ORAPROD.org_id,ORAPROD.vendor_id,ORAPROD.segment1,ORAPROD.amount_paid,ORAPROD.invoice_amount,ORAPROD.amount_remaining,ORAPROD.terms_date,ORAPROD.invoice_num
        from 
        openquery(ORAPROD,'
        SELECT 
        ap_invoices_all.org_id,  
        ap_invoices_all.vendor_id,
        ap_suppliers.segment1,
        ap_invoices_all.terms_date,
        ap_invoices_all.amount_paid,
        ap_invoices_all.invoice_amount,
        ap_payment_schedules_all.amount_remaining,
        ap_invoices_all.invoice_num
        FROM ap_payment_schedules_all
        INNER JOIN ap_invoices_all
        ON ap_invoices_all.INVOICE_ID    = ap_payment_schedules_all.INVOICE_ID
        LEFT JOIN ap_suppliers
        ON ap_suppliers.VENDOR_ID = ap_invoices_all.VENDOR_ID
        WHERE 
        ap_payment_schedules_all.amount_remaining <> ''0''
        and ap_invoices_all.terms_date >= to_date(''$varCurDate'')
        group by 
        ap_invoices_all.org_id,  
        ap_invoices_all.vendor_id,
        ap_suppliers.segment1,
        ap_invoices_all.terms_date,
        ap_invoices_all.amount_paid,
        ap_invoices_all.invoice_amount,
        ap_payment_schedules_all.amount_remaining,
        ap_invoices_all.invoice_num
        order by ap_invoices_all.org_id,ap_suppliers.segment1
        ') ORAPROD
        ";
        
        return $this->execQry($sql);
    }
    
    function updateBucketNumFieldTblVenPerOutstanding(){
        $sql = "
        update ORS_tblVenPerOutstanding 
        set bucket_num = (convert(bigint,convert(datetime,getdate()))-1) - convert(bigint,convert(datetime,terms_date))
        ";
        return $this->execQry($sql);
    }
    
    function updateBucketNamFieldTblVenPerOutstanding(){
        $sql = "
        update ORS_tblVenPerOutstanding 
        set bucket_name = CASE WHEN ((convert(bigint,convert(datetime,getdate()))-1) - convert(bigint,convert(datetime,terms_date))) < 31 THEN 'CURRENT'
        when ((convert(bigint,convert(datetime,getdate()))-1) - convert(bigint,convert(datetime,terms_date))) >= 31 
            and ((convert(bigint,convert(datetime,getdate()))-1) - convert(bigint,convert(datetime,terms_date))) <= 60
            then 'OVER30' 
        when ((convert(bigint,convert(datetime,getdate()))-1) - convert(bigint,convert(datetime,terms_date))) >= 61 
            and ((convert(bigint,convert(datetime,getdate()))-1) - convert(bigint,convert(datetime,terms_date))) <= 90
            then 'OVER60' 
        when ((convert(bigint,convert(datetime,getdate()))-1) - convert(bigint,convert(datetime,terms_date))) > 90 
            then 'OVER90' 
        else 'NO_BUCKET' end
        ";
        return $this->execQry($sql);
    }
    
    # Update Outstanding - Due
    
    function cleartblVenPerOutstandingDue(){
        $sql = "
        TRUNCATE TABLE ORS_tblVenPerOutstandingDue
        ";
        return $this->execQry($sql);
    }
    
    function updatetblVenPerOutstandingDue(){
        $curDate = $this->getCurDate();
        $varCurDate = $curDate['CurrentDateTime'];
        
        # Summary
        /*
        $sql = " 
        insert into ORS_tblVenPerOutstandingDue (org_id,vendor_id,segment1,amount_paid,invoice_amount,amount_remaining)
        select    ORAPROD.org_id,ORAPROD.vendor_id,ORAPROD.segment1,ORAPROD.amount_paid,ORAPROD.invoice_amount,ORAPROD.amount_remaining
        from 
        openquery(ORAPROD,'
        SELECT 
        ap_invoices_all.org_id,  
        ap_invoices_all.vendor_id,
        ap_suppliers.segment1,
        sum(ap_invoices_all.amount_paid) as amount_paid,
        sum(ap_invoices_all.invoice_amount) as invoice_amount,
        sum(ap_payment_schedules_all.amount_remaining) as amount_remaining
        FROM ap_payment_schedules_all
        INNER JOIN ap_invoices_all
        ON ap_invoices_all.INVOICE_ID    = ap_payment_schedules_all.INVOICE_ID
        LEFT JOIN ap_suppliers
        ON ap_suppliers.VENDOR_ID = ap_invoices_all.VENDOR_ID
        WHERE 
        ap_payment_schedules_all.amount_remaining > 0
        and ap_invoices_all.terms_date > to_date(''$varCurDate'')
        group by ap_invoices_all.org_id,  
        ap_invoices_all.vendor_id,
        ap_suppliers.segment1
        order by ap_invoices_all.org_id,ap_suppliers.segment1
        ') ORAPROD
        ";
        */
        
        # Detail
        $sql = " 
        insert into ORS_tblVenPerOutstandingDue (org_id,vendor_id,segment1,amount_paid,invoice_amount,amount_remaining,terms_date,invoice_num)
        select    ORAPROD.org_id,ORAPROD.vendor_id,ORAPROD.segment1,ORAPROD.amount_paid,ORAPROD.invoice_amount,ORAPROD.amount_remaining,ORAPROD.terms_date,ORAPROD.invoice_num
        from 
        openquery(ORAPROD,'
        SELECT 
        ap_invoices_all.org_id,  
        ap_invoices_all.vendor_id,
        ap_suppliers.segment1,
        ap_invoices_all.terms_date,
        ap_invoices_all.amount_paid,
        ap_invoices_all.invoice_amount,
        ap_payment_schedules_all.amount_remaining,
        ap_invoices_all.invoice_num
        FROM ap_payment_schedules_all
        INNER JOIN ap_invoices_all
        ON ap_invoices_all.INVOICE_ID    = ap_payment_schedules_all.INVOICE_ID
        LEFT JOIN ap_suppliers
        ON ap_suppliers.VENDOR_ID = ap_invoices_all.VENDOR_ID
        WHERE 
        ap_payment_schedules_all.amount_remaining <> ''0''
        and ap_invoices_all.terms_date < to_date(''$varCurDate'')
        group by 
        ap_invoices_all.org_id,  
        ap_invoices_all.vendor_id,
        ap_suppliers.segment1,
        ap_invoices_all.terms_date,
        ap_invoices_all.amount_paid,
        ap_invoices_all.invoice_amount,
        ap_payment_schedules_all.amount_remaining,
        ap_invoices_all.invoice_num
        order by ap_invoices_all.org_id,ap_suppliers.segment1
        ') ORAPROD
        ";
        return $this->execQry($sql);
    }
    
    function updateBucketNumFieldTblVenPerOutstandingDue(){
        $sql = "
        update ORS_tblVenPerOutstandingDue 
        set bucket_num = (convert(bigint,convert(datetime,getdate()))-1) - convert(bigint,convert(datetime,terms_date))
        ";
        return $this->execQry($sql);
    }
    
    function updateBucketNamFieldTblVenPerOutstandingDue(){
        $sql = "
        update ORS_tblVenPerOutstandingDue 
        set bucket_name = CASE WHEN ((convert(bigint,convert(datetime,getdate()))-1) - convert(bigint,convert(datetime,terms_date))) < 31 THEN 'CURRENT'
        when ((convert(bigint,convert(datetime,getdate()))-1) - convert(bigint,convert(datetime,terms_date))) >= 31 
            and ((convert(bigint,convert(datetime,getdate()))-1) - convert(bigint,convert(datetime,terms_date))) <= 60
            then 'OVER30' 
        when ((convert(bigint,convert(datetime,getdate()))-1) - convert(bigint,convert(datetime,terms_date))) >= 61 
            and ((convert(bigint,convert(datetime,getdate()))-1) - convert(bigint,convert(datetime,terms_date))) <= 90
            then 'OVER60' 
        when ((convert(bigint,convert(datetime,getdate()))-1) - convert(bigint,convert(datetime,terms_date))) > 90 
            then 'OVER90' 
        else 'NO_BUCKET' end
        ";
        return $this->execQry($sql);
    }
    
    # Update Data as of
    
    function updatetblVenPerDate(){
        $sql = "
        insert into ORS_tblVenPerDate(dateTime)
        select GETDATE() as CurrentDateTime
        ";
        return $this->execQry($sql);
    }
    
    # Update Ar Payment
    
    function cleartblArPayment(){
        $sql = "
        TRUNCATE TABLE ORS_tblArPayment
        ";
        return $this->execQry($sql);
    }
    
    function updatetblArPayment(){
        $curDate = $this->getCurDate();
        $varCurDate = $curDate['CurrentDateTime'];
        
        $varCurDateFirstDay =  date('Y-m-01',strtotime($varCurDate));
        
        $sql = "
        insert into ORS_tblArPayment (org_id,attribute11,account_name,location,trx_number,receipt_number,gl_date,amount_due_remaining,class,
        amount_due_original,trx_date,name)
        select    ORAPROD.ORG_ID,ORAPROD.ATTRIBUTE11,ORAPROD.ACCOUNT_NAME,ORAPROD.LOCATION,ORAPROD.TRX_NUMBER,ORAPROD.RECEIPT_NUMBER,ORAPROD.GL_DATE,
                ORAPROD.AMOUNT_DUE_REMAINING,ORAPROD.CLASS,ORAPROD.AMOUNT_DUE_ORIGINAL,
                ORAPROD.TRX_DATE,ORAPROD.name
        from 
        openquery(ORAPROD,'
            SELECT distinct ar_payment_schedules_all.ORG_ID,
                HZ_CUST_ACCOUNTS.ATTRIBUTE11,
                HZ_CUST_ACCOUNTS.ACCOUNT_NAME,
                HZ_CUST_SITE_USES_ALL.LOCATION,
                ra_customer_trx_all.TRX_NUMBER,
                ar_cash_receipts_all.RECEIPT_NUMBER,
                ar_payment_schedules_all.GL_DATE,
                ar_payment_schedules_all.AMOUNT_DUE_REMAINING,
                ar_payment_schedules_all.CLASS,
                ar_payment_schedules_all.AMOUNT_DUE_ORIGINAL,
                ar_payment_schedules_all.TRX_DATE,
                hz_cust_profile_classes.name
            FROM (((ar_payment_schedules_all
            LEFT JOIN ra_customer_trx_all
            ON ar_payment_schedules_all.CUSTOMER_TRX_ID = ra_customer_trx_all.CUSTOMER_TRX_ID)
            LEFT JOIN ar_cash_receipts_all
            ON ar_payment_schedules_all.CASH_RECEIPT_ID = ar_cash_receipts_all.CASH_RECEIPT_ID)
            INNER JOIN HZ_CUST_ACCOUNTS
            ON ar_payment_schedules_all.CUSTOMER_ID = HZ_CUST_ACCOUNTS.CUST_ACCOUNT_ID)
            LEFT JOIN hz_customer_profiles
            ON HZ_CUST_ACCOUNTS.CUST_ACCOUNT_ID = hz_customer_profiles.CUST_ACCOUNT_ID
            LEFT JOIN hz_cust_profile_classes
            ON hz_cust_profile_classes.PROFILE_CLASS_ID = hz_customer_profiles.PROFILE_CLASS_ID
            INNER JOIN HZ_CUST_SITE_USES_ALL
            ON ar_payment_schedules_all.CUSTOMER_SITE_USE_ID   = HZ_CUST_SITE_USES_ALL.SITE_USE_ID
            WHERE ar_payment_schedules_all.ORG_ID              in (85,87,133)
            AND ar_payment_schedules_all.TRX_DATE between to_date(''$varCurDateFirstDay'') and to_date(''$varCurDate'')
            AND ar_payment_schedules_all.AMOUNT_DUE_REMAINING <> 0
            AND ar_payment_schedules_all.CLASS = ''PMT''
        ') ORAPROD
        ";
        return $this->execQry($sql);
    }
    
    # Update Ar Transaction - Current
    
    function cleartblArTransaction(){
        $sql = "
        TRUNCATE TABLE ORS_tblArTransaction
        ";
        return $this->execQry($sql);
    }
    
    function updatetblArTransaction(){
        $curDate = $this->getCurDate();
        $varCurDate = $curDate['CurrentDateTime'];
        
        $varCurDateFirstDay =  date('Y-m-01',strtotime($varCurDate));
        
        $sql = "
        insert into ORS_tblArTransaction (org_id,attribute11,account_name,location,trx_number,receipt_number,gl_date,amount_due_remaining,class,
        amount_due_original,trx_date,name)
        select    ORAPROD.ORG_ID,ORAPROD.ATTRIBUTE11,ORAPROD.ACCOUNT_NAME,ORAPROD.LOCATION,ORAPROD.TRX_NUMBER,ORAPROD.RECEIPT_NUMBER,ORAPROD.GL_DATE,
                ORAPROD.AMOUNT_DUE_REMAINING,ORAPROD.CLASS,ORAPROD.AMOUNT_DUE_ORIGINAL,
                ORAPROD.TRX_DATE,ORAPROD.name
        from 
        openquery(ORAPROD,'
            SELECT distinct ar_payment_schedules_all.ORG_ID,
                HZ_CUST_ACCOUNTS.ATTRIBUTE11,
                HZ_CUST_ACCOUNTS.ACCOUNT_NAME,
                HZ_CUST_SITE_USES_ALL.LOCATION,
                ra_customer_trx_all.TRX_NUMBER,
                ar_cash_receipts_all.RECEIPT_NUMBER,
                ar_payment_schedules_all.GL_DATE,
                ar_payment_schedules_all.AMOUNT_DUE_REMAINING,
                ar_payment_schedules_all.CLASS,
                ar_payment_schedules_all.AMOUNT_DUE_ORIGINAL,
                ar_payment_schedules_all.TRX_DATE,
                hz_cust_profile_classes.name
            FROM (((ar_payment_schedules_all
            LEFT JOIN ra_customer_trx_all
            ON ar_payment_schedules_all.CUSTOMER_TRX_ID = ra_customer_trx_all.CUSTOMER_TRX_ID)
            LEFT JOIN ar_cash_receipts_all
            ON ar_payment_schedules_all.CASH_RECEIPT_ID = ar_cash_receipts_all.CASH_RECEIPT_ID)
            INNER JOIN HZ_CUST_ACCOUNTS
            ON ar_payment_schedules_all.CUSTOMER_ID = HZ_CUST_ACCOUNTS.CUST_ACCOUNT_ID)
            LEFT JOIN hz_customer_profiles
            ON HZ_CUST_ACCOUNTS.CUST_ACCOUNT_ID = hz_customer_profiles.CUST_ACCOUNT_ID
            LEFT JOIN hz_cust_profile_classes
            ON hz_cust_profile_classes.PROFILE_CLASS_ID = hz_customer_profiles.PROFILE_CLASS_ID
            INNER JOIN HZ_CUST_SITE_USES_ALL
            ON ar_payment_schedules_all.CUSTOMER_SITE_USE_ID   = HZ_CUST_SITE_USES_ALL.SITE_USE_ID
            WHERE ar_payment_schedules_all.ORG_ID              in (87)
            AND ar_payment_schedules_all.TRX_DATE >= to_date(''$varCurDate'')
            AND ar_payment_schedules_all.AMOUNT_DUE_REMAINING <> 0
            AND ar_payment_schedules_all.CLASS <> ''PMT''
        ') ORAPROD
        ";
        return $this->execQry($sql);
    }
    
    function updateBucketNumFieldTblArTransaction(){
        $sql = "
        update ORS_tblArTransaction 
        set bucket_num = (convert(bigint,convert(datetime,getdate()))-1) - convert(bigint,convert(datetime,trx_date))
        ";
        return $this->execQry($sql);
    }
    
    function updateBucketNamFieldTblArTransaction(){
        $sql = "
        update ORS_tblArTransaction 
        set bucket_name = CASE WHEN ((convert(bigint,convert(datetime,getdate()))-1) - convert(bigint,convert(datetime,trx_date))) < 31 THEN 'CURRENT'
        when ((convert(bigint,convert(datetime,getdate()))-1) - convert(bigint,convert(datetime,trx_date))) >= 31 
            and ((convert(bigint,convert(datetime,getdate()))-1) - convert(bigint,convert(datetime,trx_date))) <= 60
            then 'OVER30' 
        when ((convert(bigint,convert(datetime,getdate()))-1) - convert(bigint,convert(datetime,trx_date))) >= 61 
            and ((convert(bigint,convert(datetime,getdate()))-1) - convert(bigint,convert(datetime,trx_date))) <= 90
            then 'OVER60' 
        when ((convert(bigint,convert(datetime,getdate()))-1) - convert(bigint,convert(datetime,trx_date))) > 90 
            then 'OVER90' 
        else 'NO_BUCKET' end
        ";
        return $this->execQry($sql);
    }
    
    # Update Ar Transaction - Due
    
    function cleartblArTransactionDue(){
        $sql = "
        TRUNCATE TABLE ORS_tblArTransactionDue
        ";
        return $this->execQry($sql);
    }
    
    function updatetblArTransactionDue(){
        $curDate = $this->getCurDate();
        $varCurDate = $curDate['CurrentDateTime'];
        
        $varCurDateFirstDay =  date('Y-m-01',strtotime($varCurDate));
        
        $sql = "
        insert into ORS_tblArTransactionDue (org_id,attribute11,account_name,location,trx_number,receipt_number,gl_date,amount_due_remaining,class,
        amount_due_original,trx_date,name)
        select    ORAPROD.ORG_ID,ORAPROD.ATTRIBUTE11,ORAPROD.ACCOUNT_NAME,ORAPROD.LOCATION,ORAPROD.TRX_NUMBER,ORAPROD.RECEIPT_NUMBER,ORAPROD.GL_DATE,
                ORAPROD.AMOUNT_DUE_REMAINING,ORAPROD.CLASS,ORAPROD.AMOUNT_DUE_ORIGINAL,
                ORAPROD.TRX_DATE,ORAPROD.name
        from 
        openquery(ORAPROD,'
            SELECT distinct ar_payment_schedules_all.ORG_ID,
                HZ_CUST_ACCOUNTS.ATTRIBUTE11,
                HZ_CUST_ACCOUNTS.ACCOUNT_NAME,
                HZ_CUST_SITE_USES_ALL.LOCATION,
                ra_customer_trx_all.TRX_NUMBER,
                ar_cash_receipts_all.RECEIPT_NUMBER,
                ar_payment_schedules_all.GL_DATE,
                ar_payment_schedules_all.AMOUNT_DUE_REMAINING,
                ar_payment_schedules_all.CLASS,
                ar_payment_schedules_all.AMOUNT_DUE_ORIGINAL,
                ar_payment_schedules_all.TRX_DATE,
                hz_cust_profile_classes.name
            FROM (((ar_payment_schedules_all
            LEFT JOIN ra_customer_trx_all
            ON ar_payment_schedules_all.CUSTOMER_TRX_ID = ra_customer_trx_all.CUSTOMER_TRX_ID)
            LEFT JOIN ar_cash_receipts_all
            ON ar_payment_schedules_all.CASH_RECEIPT_ID = ar_cash_receipts_all.CASH_RECEIPT_ID)
            INNER JOIN HZ_CUST_ACCOUNTS
            ON ar_payment_schedules_all.CUSTOMER_ID = HZ_CUST_ACCOUNTS.CUST_ACCOUNT_ID)
            LEFT JOIN hz_customer_profiles
            ON HZ_CUST_ACCOUNTS.CUST_ACCOUNT_ID = hz_customer_profiles.CUST_ACCOUNT_ID
            LEFT JOIN hz_cust_profile_classes
            ON hz_cust_profile_classes.PROFILE_CLASS_ID = hz_customer_profiles.PROFILE_CLASS_ID
            INNER JOIN HZ_CUST_SITE_USES_ALL
            ON ar_payment_schedules_all.CUSTOMER_SITE_USE_ID   = HZ_CUST_SITE_USES_ALL.SITE_USE_ID
            WHERE ar_payment_schedules_all.ORG_ID              in (87)
            AND ar_payment_schedules_all.TRX_DATE < to_date(''$varCurDate'')
            AND ar_payment_schedules_all.AMOUNT_DUE_REMAINING <> 0
            AND ar_payment_schedules_all.CLASS <> ''PMT''
        ') ORAPROD
        ";
        return $this->execQry($sql);
    }
    
    function updateBucketNumFieldTblArTransactionDue(){
        $sql = "
        update ORS_tblArTransactionDue 
        set bucket_num = (convert(bigint,convert(datetime,getdate()))-1) - convert(bigint,convert(datetime,trx_date))
        ";
        return $this->execQry($sql);
    }
    
    function updateBucketNamFieldTblArTransactionDue(){
        $sql = "
        update ORS_tblArTransactionDue 
        set bucket_name = CASE WHEN ((convert(bigint,convert(datetime,getdate()))-1) - convert(bigint,convert(datetime,trx_date))) < 31 THEN 'CURRENT'
        when ((convert(bigint,convert(datetime,getdate()))-1) - convert(bigint,convert(datetime,trx_date))) >= 31 
            and ((convert(bigint,convert(datetime,getdate()))-1) - convert(bigint,convert(datetime,trx_date))) <= 60
            then 'OVER30' 
        when ((convert(bigint,convert(datetime,getdate()))-1) - convert(bigint,convert(datetime,trx_date))) >= 61 
            and ((convert(bigint,convert(datetime,getdate()))-1) - convert(bigint,convert(datetime,trx_date))) <= 90
            then 'OVER60' 
        when ((convert(bigint,convert(datetime,getdate()))-1) - convert(bigint,convert(datetime,trx_date))) > 90 
            then 'OVER90' 
        else 'NO_BUCKET' end
        ";
        return $this->execQry($sql);
    }
    
    # Update Data as of AR
    
    function updatetblCusPerDate(){
        $sql = "
        insert into ORS_tblCusPerDate(dateTime)
        select GETDATE() as CurrentDateTime
        ";
        return $this->execQry($sql);
    }
        
}
?>