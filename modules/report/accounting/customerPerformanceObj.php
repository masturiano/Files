<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class customerPerformanceObj extends commonObj {
    
    # Data as of
        
    function getDateTime(){
        $sql = "
        select max(dateTime) as dateTime  from ORS_tblCusPerDate
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    # Unapplied
    
    function getUnappliedPositive(){
        $sql = "
        select sum(amount_due_remaining) as amount_due_remaining from ORS_tblArPayment 
        where amount_due_remaining >= 0.00
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    function getUnappliedNegative(){
        $sql = "
        select sum(amount_due_remaining) as amount_due_remaining from ORS_tblArPayment 
        where amount_due_remaining < 0.00
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    # Outstanding 
    
    function getOutstandingPositive(){
        $sql = "
        select sum(amount_due_remaining) as amount_due_remaining from ORS_tblArTransaction
        where amount_due_remaining > 0.00 
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    function getOutstandingNegative(){
        $sql = "
        select sum(amount_due_remaining) as amount_due_remaining from ORS_tblArTransaction
        where amount_due_remaining < 0.00 
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }

    # Outstanding Due
    
    function getOutstandingDuePositive(){
        $sql = "
        select sum(amount_due_remaining) as amount_due_remaining from ORS_tblArTransactionDue
        where amount_due_remaining > 0.00  
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }     
    
    function getOutstandingDueNegative(){
        $sql = "
        select sum(amount_due_remaining) as amount_due_remaining from ORS_tblArTransactionDue
        where amount_due_remaining < 0.00 
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    # Find Customer
    
    function findCustomer($terms){
                
        $sql = "
        select TOP 10 sql_mmpgtlib.dbo.CIMCUS.CUSTOMER_NUMBER AS cusNum, sql_mmpgtlib.dbo.CIMCUS.FULL_NAME AS cusName  from sql_mmpgtlib.dbo.CIMCUS
        where sql_mmpgtlib.dbo.CIMCUS.FULL_NAME NOT LIKE '%NTBU%'
        and (sql_mmpgtlib.dbo.CIMCUS.CUSTOMER_NUMBER LIKE '%$terms%' OR sql_mmpgtlib.dbo.CIMCUS.FULL_NAME LIKE '%$terms%')";
        return $this->getArrRes($this->execQry($sql));
    }
    
    # Unapplied Per Customer
    
    function getPositivePerCustomer($customerCode){
        $sql = "
        select sum(amount_due_remaining) as amount_due_remaining from ORS_tblArPayment 
        where amount_due_remaining >= 0.00
        and attribute11 = '$customerCode'
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    function getNegativePerCustomer($customerCode){
        $sql = "
        select sum(amount_due_remaining) as amount_due_remaining from ORS_tblArPayment 
        where amount_due_remaining < 0.00
        and attribute11 = '$customerCode'
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    # Outstanding Per Customer 
    
    function getOutstandingPositivePerCustomer($customerCode){
        $sql = "
        select sum(amount_due_remaining) as amount_due_remaining from ORS_tblArTransaction
        where amount_due_remaining > 0.00 
        and attribute11 = '$customerCode'
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    function getOutstandingNegativePerCustomer($customerCode){
        $sql = "
        select sum(amount_due_remaining) as amount_due_remaining from ORS_tblArTransaction
        where amount_due_remaining < 0.00 
        and attribute11 = '$customerCode'
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }

    # Outstanding Due Per Customer
    
    function getOutstandingDuePositivePerCustomer($customerCode){
        $sql = "
        select sum(amount_due_remaining) as amount_due_remaining from ORS_tblArTransactionDue
        where amount_due_remaining > 0.00  
        and attribute11 = '$customerCode'
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    function getOutstandingDueNegativePerCustomer($customerCode){
        $sql = "
        select sum(amount_due_remaining) as amount_due_remaining from ORS_tblArTransactionDue
        where amount_due_remaining < 0.00  
        and attribute11 = '$customerCode'
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    # Aging Header
    function agingHeader(){
        $sql = "    
        select agingHeader.bucket_name from (
        select bucket_name from ORS_tblArTransaction
        group by bucket_name
        union 
        select bucket_name from ORS_tblArTransactionDue
        group by bucket_name) agingHeader 
        group by agingHeader.bucket_name
        ";
        return $this->getArrRes($this->execQry($sql));
    }
    
    # Aging Positive
    function agingPositive($bucketName){
        $sql = "
        select sum(agingPositive.amount_due_remaining) as amount_due_remaining,agingPositive.bucket_name from (
            select sum(amount_due_remaining) as amount_due_remaining,bucket_name from ORS_tblArTransaction
            where amount_due_remaining > 0.00 
            and bucket_name = '$bucketName'
            group by bucket_name
            union 
            select sum(amount_due_remaining) as amount_due_remaining,bucket_name from ORS_tblArTransactionDue
            where amount_due_remaining > 0.00 
            and bucket_name = '$bucketName'
            group by bucket_name) agingPositive 
        group by agingPositive.bucket_name
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    # Aging Negative
    function agingNegative($bucketName){
        $sql = "
        select sum(agingPositive.amount_due_remaining) as amount_due_remaining,agingPositive.bucket_name from (
            select sum(amount_due_remaining) as amount_due_remaining,bucket_name from ORS_tblArTransaction
            where amount_due_remaining < 0.00 
            and bucket_name = '$bucketName'
            group by bucket_name
            union 
            select sum(amount_due_remaining) as amount_due_remaining,bucket_name from ORS_tblArTransactionDue
            where amount_due_remaining < 0.00 
            and bucket_name = '$bucketName'
            group by bucket_name) agingPositive 
        group by agingPositive.bucket_name
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    # Aging Total
    function agingTotal($bucketName){
        $sql = "
        select sum(agingPositive.amount_due_remaining) as amount_due_remaining,agingPositive.bucket_name from (
            select sum(amount_due_remaining) as amount_due_remaining,bucket_name from ORS_tblArTransaction
            where bucket_name = '$bucketName'
            group by bucket_name
            union 
            select sum(amount_due_remaining) as amount_due_remaining,bucket_name from ORS_tblArTransactionDue
            where bucket_name = '$bucketName'
            group by bucket_name) agingPositive 
        group by agingPositive.bucket_name
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    # Aging Header Per Customer
    function agingHeaderPerCustomer($customerCode){
        $sql = "    
        select agingHeader.bucket_name from (
        select bucket_name from ORS_tblArTransaction
        group by bucket_name
        union 
        select bucket_name from ORS_tblArTransactionDue
        group by bucket_name) agingHeader 
        group by agingHeader.bucket_name
        ";
        return $this->getArrRes($this->execQry($sql));
    }
    
    # Aging Positive Per Customer
    function agingPositivePerCustomer($customerCode,$bucketName){
        $sql = "
        select sum(agingPositive.amount_due_remaining) as amount_due_remaining,agingPositive.bucket_name from (
        select sum(amount_due_remaining) as amount_due_remaining,bucket_name from ORS_tblArTransaction
        where amount_due_remaining > 0.00 
        and bucket_name = '$bucketName'
        and attribute11 = '$customerCode'
        group by bucket_name
        union 
        select sum(amount_due_remaining) as amount_due_remaining,bucket_name from ORS_tblArTransactionDue
        where amount_due_remaining > 0.00 
        and bucket_name = '$bucketName'
        and attribute11 = '$customerCode'
        group by bucket_name) agingPositive 
        group by agingPositive.bucket_name
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    # Aging Negative Per Customer
    function agingNegativePerCustomer($customerCode,$bucketName){
        $sql = "
        select sum(agingPositive.amount_due_remaining) as amount_due_remaining,agingPositive.bucket_name from (
        select sum(amount_due_remaining) as amount_due_remaining,bucket_name from ORS_tblArTransaction
        where amount_due_remaining < 0.00 
        and bucket_name = '$bucketName'
        and attribute11 = '$customerCode'
        group by bucket_name
        union 
        select sum(amount_due_remaining) as amount_due_remaining,bucket_name from ORS_tblArTransactionDue
        where amount_due_remaining < 0.00 
        and bucket_name = '$bucketName'
        and attribute11 = '$customerCode'
        group by bucket_name) agingPositive 
        group by agingPositive.bucket_name
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    # Aging Total Per Customer
    function agingTotalPerCustomer($customerCode,$bucketName){
        $sql = "
        select sum(agingPositive.amount_due_remaining) as amount_due_remaining,agingPositive.bucket_name from (
        select sum(amount_due_remaining) as amount_due_remaining,bucket_name from ORS_tblArTransaction
        where bucket_name = '$bucketName'
        and attribute11 = '$customerCode'
        group by bucket_name
        union 
        select sum(amount_due_remaining) as amount_due_remaining,bucket_name from ORS_tblArTransactionDue
        where bucket_name = '$bucketName'
        and attribute11 = '$customerCode'
        group by bucket_name) agingPositive 
        group by agingPositive.bucket_name
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
    
    # Aging Header Per Customer Check Details
    function agingHeaderPerCustomerCheckDetails($customerCode){
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
    
    # Aging Header Per Customer Check Details
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
    
    # Aging Header Per Customer Check Details
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
    
    # Aging Header Per Customer Check Details
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
    
    # Aging Header Per Customer Check Details
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
    
    # Aging Header Per Customer Check Details
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
    
    # Aging Header Per Customer Check Details
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
    
    # Aging Header Per Customer Check Details
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
    
    # Aging Header Per Customer Check Details
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
    
    # Aging Header Per Customer Check Details
        # Store Procedure
    function storedProc($customerCode){
        $sql = "    
        exec aging_details_customer '$customerCode'
        ";
        return $this->getArrRes($this->execQry($sql));
    }
}
?>