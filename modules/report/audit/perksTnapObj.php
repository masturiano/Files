<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class perksTnapObj extends commonObj {
    
    function getData($dte_from,$dte_to,$card_type) {
        
        if($card_type == 'Perks'){
            $db = "MatrixCRM_Puregold"; 
        }
        else if($card_type == 'Tnap'){
            $db = "MatrixCRM_TNAP"; 
        }
        else{
            $db = mysql_error();    
        }

        $sql="
        select * from openquery([192.168.200.142],'
            select 
                c.CardNo,
                t.OriginalDate,
                c.PrintedName,
                c.SignupLocationCode,
                substring(t.ReceiptNo,0,4) as StoreTransacted,
                sum(t.AmountSpent) as amountSpent,
                sum(t.TransactPoints) as transactPoints,
                sum(t.NettSpent) as NettSpent,
                count(t.ReceiptNo) as transactionCount
            from 
                $db.dbo.transact t 
                inner join $db.dbo.Card c on t.CardNo=c.CardNo
            where 
                len(receiptNo)=17 
                and t.OriginalDate between ''$dte_from'' and ''$dte_to''
            group by 
                c.CardNo,
                t.OriginalDate,
                c.PrintedName,
                c.SignupLocationCode,
                substring(t.ReceiptNo,0,4) 
            having 
                count(t.ReceiptNo)>4
            order by  
                OriginalDate,PrintedName asc
        ') 
        ";
        return $this->getArrRes($this->execQry($sql));
    }
    
    function findStore($strNum){
        $sql = "
        select 
            strnum,strnam 
        from 
            sql_mmpgtlib..TBLSTR
        where 
            strnum = '{$strNum}'
        ";
        return $this->getSqlAssoc($this->execQry($sql));
    }
}
?>