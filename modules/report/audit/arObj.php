<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class apObj extends commonObj {
    
    # GET COMPANY
    function getCompany(){
        $sql="
            select 
                org_id,comp_name,comp_short,status
            from
                ORS_tblCompany
            where
                status = 'A'
            order by comp_name
        ";
        return $this->getArrRes($this->execQry($sql));
    }
    
    # DISPLAY COMPANY NAME IN EXCEL REPORT
    function getCompanyDetails($org_id){
        $sql="
            select 
                org_id,comp_name,comp_short,status
            from
                ORS_tblCompany
            where
                status = 'A'
                and org_id = {$org_id}
            order by comp_name
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    # GET INVOICE SOURCE
    function getInvoiceSource(){
        $sql = "
            select 
                source_id,source_name 
            from 
                ORS_tblApSource
            where
                status = 'A'
            order by 
                source_name asc
        ";
        return $this->getArrRes($this->execQry($sql));
    }
    
    # DISPLAY INVOICE SOURCE IN QUERY
    function getInvoiceSourceDetails($source_id){
        $sql = "
            select 
                source_id,source_name 
            from 
                ORS_tblApSource
            where
                status = 'A'   
                and source_id = {$source_id}
            order by 
                source_name asc
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    # GET INVOICE PREFIX
    function getInvoicePrefix($source_id){
        $sql = "
            select 
                source_id,prefix 
            from 
                ORS_tblApInvoicePrefix
            where 
                status = 'A'
                and source_id = {$source_id}
            order by 
                prefix asc
        ";
        return $this->getArrRes($this->execQry($sql));
    }  
    
    # AP POSTED REPORT
    function apPosted($dteFrom,$dteTo,$company,$source,$invPrefix) {
        
        # ADDITIONAL ONE DAY TO DATETO
        $dteTo = date("Y-m-d", strtotime("$dteTo +1 day", time()));
        
        # CHECK COMPANY VALUE
        if($company == "0"){
            $orgId = "";
        }
        else
        {
            $orgId = $company;
        }
        
        # CHECK SOURCE VALUE
        if($source == "0"){
            $src = "";
        }
        else
        {
            $src = $source;
            $src_name = $this->getInvoiceSourceDetails($src);
            $src_name = $src_name['source_name'];
        }
        
        # CHECK PREFIX VALUE
        if($invPrefix == "0"){
            $prefix = "";
        }
        else
        {
            $prefix = $invPrefix;
        }   
        
        # QUERY
        $sql="
        select    
            ORAPROD.SEGMENT1,ORAPROD.VENDOR_NAME,ORAPROD.INVOICE_NUM,ORAPROD.INVOICE_DATE,ORAPROD.INVOICE_AMOUNT,ORAPROD.CHECK_NUMBER,
            ORAPROD.CHECK_DATE,ORAPROD.AMOUNT_PAID,ORAPROD.CREATION_DATE,ORAPROD.DESCRIPTION,ORAPROD.ORG_ID
        from 
            openquery(ORAPROD,'
                select 
                  b.segment1,
                  b.vendor_name,
                  a.invoice_num,
                  a.invoice_date,
                  a.invoice_amount,
                  d.check_number,
                  d.check_date,
                  a.amount_paid,
                  a.creation_date,
                  a.description,
                  a.org_id
                from 
                  ap_invoices_all a 
                left join 
                  ap_suppliers b on a.vendor_id = b.vendor_id
                left join 
                  ap_invoice_payments_all c on a.invoice_id = c.invoice_id
                left join 
                  ap_checks_all d on d.check_id = c.check_id
                where 
                  a.org_id in ({$orgId})
                  and a.invoice_date between to_date(''{$dteFrom}'') and to_date(''{$dteTo}'')
                  and a.source = ''{$src_name}''
                  and a.invoice_num like ''{$prefix}%''
            ') ORAPROD                                                                                 
        ";
        return $this->getArrRes($this->execQry($sql));
    } 
    
    ### EXTRA FUNCTION
    
    # FIND SITE
    function findSite(){
        $sql = "select TBLSTR.stshrt,TBLSTR.stshrt+' - ('+TBLSTR.strnam+')' as strShrtName from TBLSTR
                where (TBLSTR.STRNAM NOT LIKE 'X%')
                and (TBLSTR.STCOMP in (101,102,103,104,105,801,802,803,804,805,806,807,808,700))
                and (TBLSTR.STRNUM < 900)
                order by stshrt
                ";
        return $this->getArrRes($this->execQry($sql));
    }
    
    #FIND STORE
    function findStore($strShort){
        $sql = "select strnum,strnam from TBLSTR
                where stshrt = '{$strShort}'
                ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
	
}
?>