<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class updateFormNameObj extends commonObj {
	
	function cleartblFormName(){
		$sql = "
		TRUNCATE TABLE ORS_tblFormName
		";
		return $this->execQry($sql);
	}
	
	function updatetblFormName(){
		$sql = "
		insert into ORS_tblFormName (USER_FORM_NAME)
        select    ORAPROD.USER_FORM_NAME
        from 
        openquery(ORAPROD,'
            select distinct APPLSYS.FND_FORM_TL.USER_FORM_NAME  
            from APPLSYS.FND_FORM_TL
            order by APPLSYS.FND_FORM_TL.USER_FORM_NAME
        ') ORAPROD
        ";
		return $this->execQry($sql);
	}
    
}
?>