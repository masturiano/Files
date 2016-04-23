<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class progressBillObj extends commonObj {
    
    # CURRENT SERVER DATE
    
    function getCurDate(){
        $sql = "
        select CONVERT(VARCHAR,GETDATE(),23) as CurrentDateTime
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
	
    # IMPORT INVOICE ID
    
	function importInvoiceId(){
		$sql = "
		IF 
            OBJECT_ID('ORS_tblProgressInvoiceId', 'U') IS NOT NULL
        BEGIN
            TRUNCATE TABLE ORS_tblProgressInvoiceId
        END
        BEGIN
            INSERT INTO ORS_tblProgressInvoiceId
            SELECT 
                *   
            FROM OPENQUERY(ORAPROD, '
                SELECT 
                DISTINCT ap_invoices_all.INVOICE_ID
                FROM 
                    ap_invoice_distributions_all
                INNER JOIN 
                    ap_invoices_all ON ap_invoices_all.INVOICE_ID = ap_invoice_distributions_all.INVOICE_ID
                INNER JOIN 
                    AP_INVOICE_LINES_ALL ON ap_invoices_all.INVOICE_ID = AP_INVOICE_LINES_ALL.INVOICE_ID
                INNER JOIN 
                    AP_AWT_GROUPS ON AP_INVOICE_LINES_ALL.AWT_GROUP_ID = AP_AWT_GROUPS.GROUP_ID
                WHERE 
                    ap_invoices_all.INVOICE_ID NOT IN (21532969, 15578290, 1805236)
                    AND ap_invoices_all.ORG_ID IN (85, 87, 133)
                    AND ap_invoices_all.INVOICE_AMOUNT <> 0
                    AND ap_invoice_distributions_all.MATCH_STATUS_FLAG = ''A''
                    AND AP_INVOICE_LINES_ALL.LINE_TYPE_LOOKUP_CODE = ''ITEM''
                    AND ap_invoices_all.CANCELLED_AMOUNT IS NULL
                    AND (AP_INVOICE_LINES_ALL.TAX_CLASSIFICATION_CODE LIKE ''%DEF%''
                    OR AP_AWT_GROUPS.NAME LIKE ''%PB%'')
            '
            )
        END
		";
		return $this->execQry($sql);
	}
    
    # IMPORT INVOICE
	
	function importInvoice(){
        $curDate = $this->getCurDate();
        $varCurDate = $curDate['CurrentDateTime'];
        
        $varCurDateFirstDay =  date('Y-m-01',strtotime($varCurDate));
        
		$sql = "
		IF 
            OBJECT_ID('ORS_tblProgressInvoice', 'U') IS NOT NULL
        BEGIN
            TRUNCATE TABLE ORS_tblProgressInvoice
        END
        BEGIN
            INSERT INTO ORS_tblProgressInvoice
            SELECT 
                *           
            FROM 
                OPENQUERY(ORAPROD, '
                SELECT DISTINCT 
                    ap_invoices_all.INVOICE_ID,
                    ap_suppliers.SEGMENT1,
                    ap_suppliers.VENDOR_NAME,
                    ap_invoices_all.ORG_ID,
                    ap_supplier_sites_all.VENDOR_SITE_CODE,
                    ap_invoices_all.INVOICE_NUM,
                    ap_invoices_all.INVOICE_DATE,
                    ap_invoices_all.INVOICE_AMOUNT,
                    ap_invoices_all.SOURCE,
                    ap_invoices_all.DESCRIPTION,
                    gl_code_combinations.SEGMENT7 AS gl_line_code,
                    AP_INVOICE_LINES_ALL.AMOUNT   AS line_amt,
                    AP_INVOICE_LINES_ALL.TAX_CLASSIFICATION_CODE,
                    AP_AWT_GROUPS.NAME,
                    ap_payment_schedules_all.AMOUNT_REMAINING
                FROM 
                    ap_payment_schedules_all
                RIGHT JOIN 
                    ap_invoices_all ON ap_invoices_all.INVOICE_ID = ap_payment_schedules_all.INVOICE_ID
                INNER JOIN 
                    ap_suppliers ON ap_suppliers.VENDOR_ID = ap_invoices_all.VENDOR_ID
                INNER JOIN 
                    ap_supplier_sites_all ON ap_supplier_sites_all.VENDOR_SITE_ID = ap_invoices_all.VENDOR_SITE_ID
                INNER JOIN 
                    AP_INVOICE_LINES_ALL ON ap_invoices_all.INVOICE_ID = AP_INVOICE_LINES_ALL.INVOICE_ID
                INNER JOIN 
                    gl_code_combinations ON AP_INVOICE_LINES_ALL.DEFAULT_DIST_CCID = gl_code_combinations.CODE_COMBINATION_ID
                INNER JOIN 
                    AP_AWT_GROUPS ON AP_INVOICE_LINES_ALL.AWT_GROUP_ID = AP_AWT_GROUPS.GROUP_ID
                WHERE
                    AP_INVOICE_LINES_ALL.LINE_TYPE_LOOKUP_CODE = ''ITEM''  
                ORDER BY 
                    ap_invoices_all.ORG_ID,
                    ap_suppliers.SEGMENT1,
                    ap_invoices_all.INVOICE_NUM
                  '
                ) ORACLE
            WHERE ORACLE.INVOICE_ID IN 
                (
                    SELECT
                        INVOICE_ID
                    FROM
                        ORS_tblProgressBill_for_IT_purposes_WithInvId
                    GROUP BY
                        INVOICE_ID
                    UNION ALL
                    SELECT 
                        INVOICE_ID 
                    FROM 
                        ORS_tblProgressInvoiceId
                    GROUP BY
                        INVOICE_ID
                )
        END        
        ";
        //                     AND ap_invoices_all.INVOICE_ID = ''11686733''
		return $this->execQry($sql);
	}
    
    # IMPORT PAY
    
    function importPay(){
        $curDate = $this->getCurDate();
        $varCurDate = $curDate['CurrentDateTime'];
        
        $varCurDateFirstDay =  date('Y-m-01',strtotime($varCurDate));
        
        $sql = "
        IF 
            OBJECT_ID('ORS_tblProgressPay', 'U') IS NOT NULL
        BEGIN
            TRUNCATE TABLE ORS_tblProgressPay
        END
        BEGIN
            INSERT INTO ORS_tblProgressPay 
            SELECT 
                *   
            FROM 
                OPENQUERY(ORAPROD, '
                SELECT ap_payment_schedules_all.INVOICE_ID,
                    ap_invoice_payments_all.AMOUNT,
                    ap_checks_all.CHECK_DATE,
                    ap_checks_all.CHECK_NUMBER,
                    ap_checks_all.STATUS_LOOKUP_CODE
                FROM 
                    ap_payment_schedules_all
                INNER JOIN 
                    ap_invoice_payments_all ON ap_payment_schedules_all.INVOICE_ID = ap_invoice_payments_all.INVOICE_ID
                INNER JOIN 
                    ap_checks_all ON ap_invoice_payments_all.CHECK_ID        = ap_checks_all.CHECK_ID
                WHERE 
                    ap_checks_all.STATUS_LOOKUP_CODE <> ''VOIDED''
                '
                ) ORACLE
            WHERE ORACLE.INVOICE_ID IN 
                (
                    SELECT
                        INVOICE_ID
                    FROM
                        ORS_tblProgressBill_for_IT_purposes_WithInvId
                    GROUP BY
                        INVOICE_ID
                    UNION ALL
                    SELECT 
                        INVOICE_ID 
                    FROM 
                        ORS_tblProgressInvoiceId
                    GROUP BY
                        INVOICE_ID
                )
        END        
        ";
        //                     AND ap_payment_schedules_all.INVOICE_ID = ''2774212''
        return $this->execQry($sql);
    }
    
    # IMPORT MINOR CODE
    
    function importMinorcode(){
        $curDate = $this->getCurDate();
        $varCurDate = $curDate['CurrentDateTime'];
        
        $varCurDateFirstDay =  date('Y-m-01',strtotime($varCurDate));
        
        $sql = "
        IF 
            OBJECT_ID('ORS_tblProgressMinorCode', 'U') IS NOT NULL
        BEGIN
            TRUNCATE TABLE ORS_tblProgressMinorCode
        END
        BEGIN
            INSERT INTO ORS_tblProgressMinorCode
            SELECT 
                *    
            FROM 
                OPENQUERY(ORAPROD, '
                    SELECT fnd_flex_value_sets.FLEX_VALUE_SET_NAME,
                    FND_FLEX_VALUES.FLEX_VALUE,
                    FND_FLEX_VALUES_TL.DESCRIPTION
                    FROM fnd_flex_value_sets
                    INNER JOIN FND_FLEX_VALUES
                    ON fnd_flex_value_sets.FLEX_VALUE_SET_ID = FND_FLEX_VALUES.FLEX_VALUE_SET_ID
                    INNER JOIN FND_FLEX_VALUES_TL
                    ON FND_FLEX_VALUES.FLEX_VALUE_ID              = FND_FLEX_VALUES_TL.FLEX_VALUE_ID
                    WHERE fnd_flex_value_sets.FLEX_VALUE_SET_NAME = ''Minor Account''
                    AND FND_FLEX_VALUES.FLEX_VALUE NOT IN (''00000000'',''T'')
                    ORDER BY FND_FLEX_VALUES.FLEX_VALUE
                '
                ) ORACLE
        END        
        ";
        return $this->execQry($sql);
    }
    
    # EXPORT INVOICE DETAIL
    
    function exportInvoiceDetail(){
        $sql = "    
            SELECT 
                INVOICE_ID, SEGMENT1, VENDOR_NAME, ORG_ID, VENDOR_SITE_CODE, 
                INVOICE_NUM, INVOICE_DATE, INVOICE_AMOUNT, SOURCE, DESCRIPTION, 
                AMOUNT_REMAINING
            FROM 
                ORS_tblProgressInvoice
            GROUP BY 
                INVOICE_ID, SEGMENT1, VENDOR_NAME, ORG_ID, VENDOR_SITE_CODE, 
                INVOICE_NUM, INVOICE_DATE, INVOICE_AMOUNT, SOURCE, DESCRIPTION, 
                AMOUNT_REMAINING   
        ";
        return $this->getArrRes($this->execQry($sql));
    }
    
    # EXPORT PAY DETAILS
    
    function exportPayDetail(){
        $sql = "    
            SELECT 
                * 
            FROM 
                ORS_tblProgressPay
        ";
        return $this->getArrRes($this->execQry($sql));
    }
    
    # EXPORT PAY LINE
    
    function exportLineDetail(){
        $sql = "    
            SELECT 
                A.INVOICE_ID, 
                A.GL_LINE_CODE, 
                Sum(A.LINE_AMT) AS SumOfLINE_AMT, 
                A.TAX_CLASSIFICATION_CODE, 
                A.NAME, 
                B.DESCRIPTION
            FROM 
                ORS_tblProgressInvoice A
            LEFT JOIN 
                ORS_tblProgressMinorCode B ON A.GL_LINE_CODE = B.FLEX_VALUE
            WHERE 
                A.TAX_CLASSIFICATION_CODE LIKE '%DEF%'
                AND NAME LIKE '%PB%'
            GROUP BY 
                A.INVOICE_ID, 
                A.GL_LINE_CODE, 
                A.TAX_CLASSIFICATION_CODE, 
                A.NAME, 
                B.DESCRIPTION
        ";
        return $this->getArrRes($this->execQry($sql));
    }
    
    # IMPORT ADDITIONAL INVOICE ID
    
    function viewAdditionalInvoiceId(){
        $sql = "    
            SELECT 
                INVOICE_NO,SUPPLIERS_NUMBER,NAME,ORIG_AMT
            FROM 
                ORS_tblProgressBill_for_IT_purposes
        ";
        return $this->getArrRes($this->execQry($sql));
    }
    
    function importAdditionalInvoiceId(){
        
        $arrInv = $this->viewAdditionalInvoiceId();
        
        $truncate = "
            TRUNCATE TABLE ORS_tblProgressBill_for_IT_purposes_WithInvId
        ";
        if($this->execQry($truncate)){  
            foreach($arrInv as $valInv){
                $sql = "    
                    insert into ORS_tblProgressBill_for_IT_purposes_WithInvId (SUPPLIERS_NUMBER,INVOICE_NO,ORIG_AMT,INVOICE_ID)
                    select ORAPROD.SEGMENT1,ORAPROD.INVOICE_NUM,ORAPROD.INVOICE_AMOUNT,ORAPROD.INVOICE_ID from openquery(ORAPROD,
                        ' select 
                            ap_suppliers.SEGMENT1,ap_invoices_all.invoice_num,ap_invoices_all.invoice_amount, ap_invoices_all.invoice_id
                          from 
                            ap_invoices_all 
                          left join 
                            ap_suppliers on ap_invoices_all.vendor_id = ap_suppliers.vendor_id
                          where 
                            ap_invoices_all.org_id in (87,85,133) 
                              and ap_invoices_all.invoice_num  = ''{$valInv['INVOICE_NO']}''
                              and ap_suppliers.SEGMENT1 = ''{$valInv['SUPPLIERS_NUMBER']}'' 
                        ' 
                        ) ORAPROD
                ";  
                $this->execQry($sql);
            }  
        }   
    }      
}
?>