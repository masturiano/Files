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
    
    function findSupplier($terms){
                
        $sql = "
        SELECT     TOP 10 sql_mmpgtlib.dbo.APSUPP.ASNUM AS suppCode, sql_mmpgtlib.dbo.APSUPP.ASNAME AS suppName
        FROM         sql_mmpgtlib.dbo.APSUPP 
        WHERE     (sql_mmpgtlib.dbo.APSUPP.ASNUM LIKE '%$terms%') AND (sql_mmpgtlib.dbo.APSUPP.ASNAME NOT LIKE '%NTBU%') OR
        (sql_mmpgtlib.dbo.APSUPP.ASNAME LIKE '%$terms%') AND (sql_mmpgtlib.dbo.APSUPP.ASNAME NOT LIKE '%NTBU%')";
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
    
    
}
?>