<?
session_start();
#### Roshan's Ajax dropdown code with php
#### Copyright reserved to Roshan Bhattarai - nepaliboy007@yahoo.com
#### if you have any problem contact me at http://roshanbh.com.np
#### fell free to visit my blog http://php-ajax-guru.blogspot.com
?>
<? 
include "../../../adodb/adodb.inc.php";
include("../../../includes/db.inc.php");
include("../../../includes/common.php");
include("manpowerBillObj.php");
$apObj = new manpowerBillObj();

$invPrefix=$_REQUEST['invPrefix'];
$invoice_prefix = $apObj->getInvoicePrefix__($invPrefix);
?>
<select name="cmbPostedPrefix" class="dummyPostedPrefix" style="width: 205px;">
    <option value="0"></option> 
    <? foreach($invoice_prefix as $row) { ?>      
    <option value="<?=$row['prefix']?>"><?=$row['prefix']?></option>
    <? } 
    ?>
</select>