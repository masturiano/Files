<?
$now = date('Y-m-d H:i:s');
ini_set("date.timezone","Asia/Manila");
class userResponsibilityObj extends commonObj {

	function viewConCus() {
			
			$sql="
			select cusnum from ORS_tblARZMST where cusnum = cusNum
			";
			return $this->getArrRes($this->execQry($sql));
	}
		
	function leasingSetup($dteFrom,$dteTo,$formName) {
		
		$dteTo = date("Y-m-d", strtotime("$dteTo +1 day", time()));
        if($formName == '0'){
            $filterFormName = "";    
        }
        else
        {
            $filterFormName = "and APPLSYS.FND_FORM_TL.USER_FORM_NAME  = ''$formName''";    
        }
        
		$sql="
		select	ORAPROD.USER_ID,ORAPROD.USER_NAME,ORAPROD.DESCRIPTION,ORAPROD.START_TIME,ORAPROD.END_TIME,
				ORAPROD.RESPONSIBILITY_ID,ORAPROD.RESPONSIBILITY_NAME,ORAPROD.USER_FORM_NAME
		from 
			openquery(ORAPROD,'
                SELECT fnd_user.USER_ID,
                fnd_user.USER_NAME,
                fnd_user.DESCRIPTION,
                fnd_login_responsibilities.START_TIME,
                fnd_login_responsibilities.END_TIME,
                fnd_login_responsibilities.RESPONSIBILITY_ID,
                APPLSYS.FND_RESPONSIBILITY_TL.RESPONSIBILITY_NAME,
                APPLSYS.FND_FORM_TL.USER_FORM_NAME
                FROM fnd_user
                INNER JOIN fnd_loginS
                ON fnd_user.USER_ID = fnd_loginS.USER_ID
                INNER JOIN fnd_login_responsibilities
                ON fnd_loginS.LOGIN_ID = fnd_login_responsibilities.LOGIN_ID
                INNER JOIN APPLSYS.FND_LOGIN_RESP_FORMS
                ON fnd_login_responsibilities.LOGIN_RESP_ID = APPLSYS.FND_LOGIN_RESP_FORMS.LOGIN_RESP_ID
                AND fnd_login_responsibilities.LOGIN_ID     = APPLSYS.FND_LOGIN_RESP_FORMS.LOGIN_ID
                INNER JOIN APPLSYS.FND_FORM_TL
                ON APPLSYS.FND_LOGIN_RESP_FORMS.FORM_ID       = APPLSYS.FND_FORM_TL.FORM_ID
                AND APPLSYS.FND_LOGIN_RESP_FORMS.FORM_APPL_ID = APPLSYS.FND_FORM_TL.APPLICATION_ID
                INNER JOIN APPLSYS.FND_RESPONSIBILITY_TL
                ON fnd_login_responsibilities.RESPONSIBILITY_ID = APPLSYS.FND_RESPONSIBILITY_TL.RESPONSIBILITY_ID
                WHERE (fnd_login_responsibilities.START_TIME >= to_date(''$dteFrom'') and fnd_login_responsibilities.START_TIME <= to_date(''$dteTo''))
                $filterFormName
                ORDER BY fnd_login_responsibilities.START_TIME,
                fnd_user.USER_NAME
			') ORAPROD
		";
		return $this->getArrRes($this->execQry($sql));
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
	
    function findFormName(){
        $sql = "
        select USER_FORM_NAME from ORS_tblFormName order by USER_FORM_NAME
        ";
        return $this->getArrRes($this->execQry($sql));
    }
    
}
?>