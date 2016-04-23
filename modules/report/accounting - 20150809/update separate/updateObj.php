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
    
    function updatetblVenPerDatePaid(){
        $sql = "
        insert into ORS_tblVenPerDatePaid(dateTime)
        select GETDATE() as CurrentDateTime
        ";
        return $this->execQry($sql);
    }
    
    # Update Outstanding
    
    function cleartblVenPerOutstanding(){
        $sql = "
        TRUNCATE TABLE ORS_tblVenPerOutstanding
        ";
        return $this->execQry($sql);
    }
    
    function updatetblVenPerOutstanding(){
        $curDate = $this->getCurDate();
        $varCurDate = $curDate['CurrentDateTime'];
        
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
        return $this->execQry($sql);
    }
    
    function updatetblVenPerDateOutStand(){
        $sql = "
        insert into ORS_tblVenPerDateOutStand(dateTime)
        select GETDATE() as CurrentDateTime
        ";
        return $this->execQry($sql);
    }
    
    # Update Outstanding Due
    
    function cleartblVenPerOutstandingDue(){
        $sql = "
        TRUNCATE TABLE ORS_tblVenPerOutstandingDue
        ";
        return $this->execQry($sql);
    }
    
    function updatetblVenPerOutstandingDue(){
        $curDate = $this->getCurDate();
        $varCurDate = $curDate['CurrentDateTime'];
        
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
        return $this->execQry($sql);
    }
    
    function updatetblVenPerDateOutStandDue(){
        $sql = "
        insert into ORS_tblVenPerDateOutStandDue(dateTime)
        select GETDATE() as CurrentDateTime
        ";
        return $this->execQry($sql);
    }
        
}
?>