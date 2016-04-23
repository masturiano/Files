<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class vendorPerformanceObj extends commonObj {
    
    # Data as of
        
    function getDateTime(){
        $sql = "
        select max(dateTime) as dateTime  from ORS_tblVenPerDate
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    # Paid
    
    function getPaidPositive(){
        $sql = "
        select sum(amount_applied_to_check) as amount_applied_to_check from ORS_tblVenPerPaid 
        where amount_applied_to_check >= 0.00
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    function getPaidNegative(){
        $sql = "
        select sum(amount_applied_to_check) as amount_applied_to_check from ORS_tblVenPerPaid 
        where amount_applied_to_check < 0.00
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    # Outstanding 
    
    function getOutstandingPositive(){
        $sql = "
        select sum(amount_remaining) as amount_remaining from ORS_tblVenPerOutstanding
        where amount_remaining > 0.00 
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    function getOutstandingNegative(){
        $sql = "
        select sum(amount_remaining) as amount_remaining from ORS_tblVenPerOutstanding 
        where amount_remaining < 0.00
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }

    # Outstanding Due
    
    function getOutstandingDuePositive(){
        $sql = "
        select sum(amount_remaining) as amount_remaining from ORS_tblVenPerOutstandingDue
        where amount_remaining > 0.00  
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    function getOutstandingDueNegative(){
        $sql = "
        select sum(amount_remaining) as amount_remaining from ORS_tblVenPerOutstandingDue 
        where amount_remaining < 0.00
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    # Find Supplier
    
    function findCustomer($terms){
                
        $sql = "
        SELECT     TOP 10 sql_mmpgtlib.dbo.CIMCUS.CUSTOMER_NUMBER AS cusCode, sql_mmpgtlib.dbo.CIMCUS.FULL_NAME AS cusName
        FROM         sql_mmpgtlib.dbo.CIMCUS 
        WHERE     (sql_mmpgtlib.dbo.CIMCUS.CUSTOMER_NUMBER LIKE '%$terms%') AND (sql_mmpgtlib.dbo.CIMCUS.FULL_NAME NOT LIKE '%NTBU%') OR
        (sql_mmpgtlib.dbo.CIMCUS.FULL_NAME LIKE '%$terms%') AND (sql_mmpgtlib.dbo.CIMCUS.FULL_NAME NOT LIKE '%NTBU%')
        ";
        return $this->getArrRes($this->execQry($sql));
    }
    
    # Paid Per Vendor
    
    function getPaidPositivePerVendor($vendorCode){
        $sql = "
        select sum(amount_applied_to_check) as amount_applied_to_check from ORS_tblVenPerPaid 
        where amount_applied_to_check >= 0.00
        and segment1 = '$vendorCode'
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    function getPaidNegativePerVendor($vendorCode){
        $sql = "
        select sum(amount_applied_to_check) as amount_applied_to_check from ORS_tblVenPerPaid 
        where amount_applied_to_check < 0.00
        and segment1 = '$vendorCode'
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    # Outstanding Per Vendor 
    
    function getOutstandingPositivePerVendor($vendorCode){
        $sql = "
        select sum(amount_remaining) as amount_remaining from ORS_tblVenPerOutstanding
        where segment1 = '$vendorCode' 
        and amount_remaining > 0.00
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    function getOutstandingNegativePerVendor($vendorCode){
        $sql = "
        select sum(amount_remaining) as amount_remaining from ORS_tblVenPerOutstanding 
        where segment1 = '$vendorCode'
        and amount_remaining < 0.00
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }

    # Outstanding Due Per Vendor
    
    function getOutstandingDuePositivePerVendor($vendorCode){
        $sql = "
        select sum(amount_remaining) as amount_remaining from ORS_tblVenPerOutstandingDue 
        where segment1 = '$vendorCode'
        and amount_remaining > 0.00
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    function getOutstandingDueNegativePerVendor($vendorCode){
        $sql = "
        select sum(amount_remaining) as amount_remaining from ORS_tblVenPerOutstandingDue 
        where segment1 = '$vendorCode'
        and amount_remaining < 0.00
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    # Aging Header
    function agingHeader(){
        $sql = "    
        select agingHeader.bucket_name from (
        select bucket_name from ORS_tblVenPerOutstanding
        group by bucket_name
        union 
        select bucket_name from ORS_tblVenPerOutstandingDue
        group by bucket_name) agingHeader 
        group by agingHeader.bucket_name
        ";
        return $this->getArrRes($this->execQry($sql));
    }
    
    # Aging Positive
    function agingPositive($bucketName){
        $sql = "
        select sum(agingPositive.amount_remaining) as amount_remaining,agingPositive.bucket_name from (
        select sum(amount_remaining) as amount_remaining,bucket_name from ORS_tblVenPerOutstanding
        where amount_remaining > 0.00 
        and bucket_name = '$bucketName'
        group by bucket_name
        union 
        select sum(amount_remaining) as amount_remaining,bucket_name from ORS_tblVenPerOutstandingDue
        where amount_remaining > 0.00 
        and bucket_name = '$bucketName'
        group by bucket_name) agingPositive 
        group by agingPositive.bucket_name
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    # Aging Negative
    function agingNegative($bucketName){
        $sql = "
        select sum(agingPositive.amount_remaining) as amount_remaining,agingPositive.bucket_name from (
        select sum(amount_remaining) as amount_remaining,bucket_name from ORS_tblVenPerOutstanding
        where amount_remaining < 0.00 
        and bucket_name = '$bucketName'
        group by bucket_name
        union 
        select sum(amount_remaining) as amount_remaining,bucket_name from ORS_tblVenPerOutstandingDue
        where amount_remaining < 0.00 
        and bucket_name = '$bucketName'
        group by bucket_name) agingPositive 
        group by agingPositive.bucket_name
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    # Aging Total
    function agingTotal($bucketName){
        $sql = "
        select sum(agingPositive.amount_remaining) as amount_remaining,agingPositive.bucket_name from (
        select sum(amount_remaining) as amount_remaining,bucket_name from ORS_tblVenPerOutstanding
        where bucket_name = '$bucketName'
        group by bucket_name
        union 
        select sum(amount_remaining) as amount_remaining,bucket_name from ORS_tblVenPerOutstandingDue
        where bucket_name = '$bucketName'
        group by bucket_name) agingPositive 
        group by agingPositive.bucket_name
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    # Aging Header Per Vendor
    function agingHeaderPerVendor($vendorCode){
        $sql = "    
        select agingHeader.bucket_name from (
        select bucket_name from ORS_tblVenPerOutstanding
        group by bucket_name
        union 
        select bucket_name from ORS_tblVenPerOutstandingDue
        group by bucket_name) agingHeader 
        group by agingHeader.bucket_name
        ";
        return $this->getArrRes($this->execQry($sql));
    }
    
    # Aging Positive Per Vendor
    function agingPositivePerVendor($vendorCode,$bucketName){
        $sql = "
        select sum(agingPositive.amount_remaining) as amount_remaining,agingPositive.bucket_name from (
        select sum(amount_remaining) as amount_remaining,bucket_name from ORS_tblVenPerOutstanding
        where amount_remaining > 0.00
        and segment1 = '$vendorCode' 
        and bucket_name = '$bucketName'
        group by bucket_name
        union 
        select sum(amount_remaining) as amount_remaining,bucket_name from ORS_tblVenPerOutstandingDue
        where amount_remaining > 0.00 
        and segment1 = '$vendorCode'
        and bucket_name = '$bucketName'
        group by bucket_name) agingPositive 
        group by agingPositive.bucket_name
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    # Aging Negative Per Vendor
    function agingNegativePerVendor($vendorCode,$bucketName){
        $sql = "
        select sum(agingPositive.amount_remaining) as amount_remaining,agingPositive.bucket_name from (
        select sum(amount_remaining) as amount_remaining,bucket_name from ORS_tblVenPerOutstanding
        where amount_remaining < 0.00 
        and segment1 = '$vendorCode'
        and bucket_name = '$bucketName'
        group by bucket_name
        union 
        select sum(amount_remaining) as amount_remaining,bucket_name from ORS_tblVenPerOutstandingDue
        where amount_remaining < 0.00
        and segment1 = '$vendorCode'
        and bucket_name = '$bucketName'
        group by bucket_name) agingPositive 
        group by agingPositive.bucket_name
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    # Aging Total Per Vendor
    function agingTotalPerVendor($vendorCode,$bucketName){
        $sql = "
        select sum(agingPositive.amount_remaining) as amount_remaining,agingPositive.bucket_name from (
        select sum(amount_remaining) as amount_remaining,bucket_name from ORS_tblVenPerOutstanding
        where segment1 = '$vendorCode'
        and bucket_name = '$bucketName'
        group by bucket_name
        union 
        select sum(amount_remaining) as amount_remaining,bucket_name from ORS_tblVenPerOutstandingDue
        where segment1 = '$vendorCode'
        and bucket_name = '$bucketName'
        group by bucket_name) agingPositive 
        group by agingPositive.bucket_name
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    # Aging Header Per Vendor Check Details
    function agingHeaderPerVendorCheckDetails($vendorCode){
        $sql = "    
        select agingPositive.invoice_num from (
            select invoice_num from ORS_tblVenPerOutstanding
            where amount_remaining > 0.00 
            and segment1 = '$vendorCode' 
            group by invoice_num
            union 
            select invoice_num from ORS_tblVenPerOutstandingDue
            where amount_remaining > 0.00 
            and segment1 = '$vendorCode' 
            group by invoice_num) agingPositive 
            group by agingPositive.invoice_num
        order by agingPositive.invoice_num asc
        ";
        return $this->getArrRes($this->execQry($sql));
    }
    
    # Aging Header Per Vendor Check Details
        # Positive Current
    function agingHeaderPerVendorCheckDetailsPositiveCurrent($vendorCode,$invoiceNum){
        $sql = "    
        select agingPositive.invoice_num,agingPositive.amount_remaining as amount_remaining,agingPositive.bucket_name from (
            select invoice_num,amount_remaining,bucket_name from ORS_tblVenPerOutstanding
            where amount_remaining > 0.00 
            and segment1 = '$vendorCode' 
            and bucket_name = 'CURRENT'
            and invoice_num = '$invoiceNum'
            group by invoice_num,amount_remaining,bucket_name
            union 
            select invoice_num,amount_remaining,bucket_name from ORS_tblVenPerOutstandingDue
            where amount_remaining > 0.00 
            and segment1 = '$vendorCode' 
            and bucket_name = 'CURRENT'
            and invoice_num = '$invoiceNum'
            group by invoice_num,amount_remaining,bucket_name) agingPositive 
        group by agingPositive.invoice_num,agingPositive.amount_remaining,agingPositive.bucket_name
        order by agingPositive.amount_remaining desc
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    # Aging Header Per Vendor Check Details
        # Positive Over30
    function agingHeaderPerVendorCheckDetailsPositiveOver30($vendorCode,$invoiceNum){
        $sql = "    
        select agingPositive.invoice_num,agingPositive.amount_remaining as amount_remaining,agingPositive.bucket_name from (
            select invoice_num,amount_remaining,bucket_name from ORS_tblVenPerOutstanding
            where amount_remaining > 0.00 
            and segment1 = '$vendorCode' 
            and bucket_name = 'OVER30'
            and invoice_num = '$invoiceNum'
            group by invoice_num,amount_remaining,bucket_name
            union 
            select invoice_num,amount_remaining,bucket_name from ORS_tblVenPerOutstandingDue
            where amount_remaining > 0.00 
            and segment1 = '$vendorCode' 
            and bucket_name = 'OVER30'
            and invoice_num = '$invoiceNum'
            group by invoice_num,amount_remaining,bucket_name) agingPositive 
        group by agingPositive.invoice_num,agingPositive.amount_remaining,agingPositive.bucket_name
        order by agingPositive.amount_remaining desc
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    # Aging Header Per Vendor Check Details
        # Positive Over60
    function agingHeaderPerVendorCheckDetailsPositiveOver60($vendorCode,$invoiceNum){
        $sql = "    
        select agingPositive.invoice_num,agingPositive.amount_remaining as amount_remaining,agingPositive.bucket_name from (
            select invoice_num,amount_remaining,bucket_name from ORS_tblVenPerOutstanding
            where amount_remaining > 0.00 
            and segment1 = '$vendorCode' 
            and bucket_name = 'OVER60'
            and invoice_num = '$invoiceNum'
            group by invoice_num,amount_remaining,bucket_name
            union 
            select invoice_num,amount_remaining,bucket_name from ORS_tblVenPerOutstandingDue
            where amount_remaining > 0.00 
            and segment1 = '$vendorCode' 
            and bucket_name = 'OVER60'
            and invoice_num = '$invoiceNum'
            group by invoice_num,amount_remaining,bucket_name) agingPositive 
        group by agingPositive.invoice_num,agingPositive.amount_remaining,agingPositive.bucket_name
        order by agingPositive.amount_remaining desc
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    # Aging Header Per Vendor Check Details
        # Positive Over90
    function agingHeaderPerVendorCheckDetailsPositiveOver90($vendorCode,$invoiceNum){
        $sql = "    
        select agingPositive.invoice_num,agingPositive.amount_remaining as amount_remaining,agingPositive.bucket_name from (
            select invoice_num,amount_remaining,bucket_name from ORS_tblVenPerOutstanding
            where amount_remaining > 0.00 
            and segment1 = '$vendorCode' 
            and bucket_name = 'OVER90'
            and invoice_num = '$invoiceNum'
            group by invoice_num,amount_remaining,bucket_name
            union 
            select invoice_num,amount_remaining,bucket_name from ORS_tblVenPerOutstandingDue
            where amount_remaining > 0.00 
            and segment1 = '$vendorCode' 
            and bucket_name = 'OVER90'
            and invoice_num = '$invoiceNum'
            group by invoice_num,amount_remaining,bucket_name) agingPositive 
        group by agingPositive.invoice_num,agingPositive.amount_remaining,agingPositive.bucket_name
        order by agingPositive.amount_remaining desc
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    # Aging Header Per Vendor Check Details
        # Negative Current
    function agingHeaderPerVendorCheckDetailsNegativeCurrent($vendorCode,$invoiceNum){
        $sql = "    
        select agingPositive.invoice_num,agingPositive.amount_remaining as amount_remaining,agingPositive.bucket_name from (
            select invoice_num,amount_remaining,bucket_name from ORS_tblVenPerOutstanding
            where amount_remaining < 0.00 
            and segment1 = '$vendorCode' 
            and bucket_name = 'CURRENT'
            and invoice_num = '$invoiceNum'
            group by invoice_num,amount_remaining,bucket_name
            union 
            select invoice_num,amount_remaining,bucket_name from ORS_tblVenPerOutstandingDue
            where amount_remaining < 0.00 
            and segment1 = '$vendorCode' 
            and bucket_name = 'CURRENT'
            and invoice_num = '$invoiceNum'
            group by invoice_num,amount_remaining,bucket_name) agingPositive 
        group by agingPositive.invoice_num,agingPositive.amount_remaining,agingPositive.bucket_name
        order by agingPositive.amount_remaining desc
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    # Aging Header Per Vendor Check Details
        # Negative Over30
    function agingHeaderPerVendorCheckDetailsNegativeOver30($vendorCode,$invoiceNum){
        $sql = "    
        select agingPositive.invoice_num,agingPositive.amount_remaining as amount_remaining,agingPositive.bucket_name from (
            select invoice_num,amount_remaining,bucket_name from ORS_tblVenPerOutstanding
            where amount_remaining < 0.00 
            and segment1 = '$vendorCode' 
            and bucket_name = 'OVER30'
            and invoice_num = '$invoiceNum'
            group by invoice_num,amount_remaining,bucket_name
            union 
            select invoice_num,amount_remaining,bucket_name from ORS_tblVenPerOutstandingDue
            where amount_remaining < 0.00 
            and segment1 = '$vendorCode' 
            and bucket_name = 'OVER30'
            and invoice_num = '$invoiceNum'
            group by invoice_num,amount_remaining,bucket_name) agingPositive 
        group by agingPositive.invoice_num,agingPositive.amount_remaining,agingPositive.bucket_name
        order by agingPositive.amount_remaining desc
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    # Aging Header Per Vendor Check Details
        # Negative Over60
    function agingHeaderPerVendorCheckDetailsNegativeOver60($vendorCode,$invoiceNum){
        $sql = "    
        select agingPositive.invoice_num,agingPositive.amount_remaining as amount_remaining,agingPositive.bucket_name from (
            select invoice_num,amount_remaining,bucket_name from ORS_tblVenPerOutstanding
            where amount_remaining < 0.00 
            and segment1 = '$vendorCode' 
            and bucket_name = 'OVER60'
            and invoice_num = '$invoiceNum'
            group by invoice_num,amount_remaining,bucket_name
            union 
            select invoice_num,amount_remaining,bucket_name from ORS_tblVenPerOutstandingDue
            where amount_remaining < 0.00 
            and segment1 = '$vendorCode' 
            and bucket_name = 'OVER60'
            and invoice_num = '$invoiceNum'
            group by invoice_num,amount_remaining,bucket_name) agingPositive 
        group by agingPositive.invoice_num,agingPositive.amount_remaining,agingPositive.bucket_name
        order by agingPositive.amount_remaining desc
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    # Aging Header Per Vendor Check Details
        # Negative Over90
    function agingHeaderPerVendorCheckDetailsNegativeOver90($vendorCode,$invoiceNum){
        $sql = "    
        select agingPositive.invoice_num,agingPositive.amount_remaining as amount_remaining,agingPositive.bucket_name from (
            select invoice_num,amount_remaining,bucket_name from ORS_tblVenPerOutstanding
            where amount_remaining < 0.00 
            and segment1 = '$vendorCode' 
            and bucket_name = 'OVER90'
            and invoice_num = '$invoiceNum'
            group by invoice_num,amount_remaining,bucket_name
            union 
            select invoice_num,amount_remaining,bucket_name from ORS_tblVenPerOutstandingDue
            where amount_remaining < 0.00 
            and segment1 = '$vendorCode' 
            and bucket_name = 'OVER90'
            and invoice_num = '$invoiceNum'
            group by invoice_num,amount_remaining,bucket_name) agingPositive 
        group by agingPositive.invoice_num,agingPositive.amount_remaining,agingPositive.bucket_name
        order by agingPositive.amount_remaining desc
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    # Aging Header Per Vendor Check Details
        # Store Procedure
    function storedProc($vendorCode){
        $sql = "    
        exec aging_details '$vendorCode'
        ";
        return $this->getArrRes($this->execQry($sql));
    }
}
?>