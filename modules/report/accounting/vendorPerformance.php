<?php
session_start();
include "../../../adodb/adodb.inc.php";
include("../../../includes/db.inc.php");
include("../../../includes/common.php");
include("vendorPerformanceObj.php");

$vendorPerformanceObj = new vendorPerformanceObj();

switch($_GET['action']){

	case "GETAUTO":
			$arrResult = array();
				$arr= $manpowerBilObj($_GET['term']);
				foreach($arr as $val){
					$arrResult[] = array(
						"id"=>$val['cusnum'],
						"label"=>$val['dispCusName']);	
				}
			echo json_encode($arrResult); 		
	exit();	
	break;	
    
    case 'searchSupplier':
        $arrResult = array();
            $arrSupp = $vendorPerformanceObj->findSupplier($_GET['term']);
                foreach($arrSupp as $val){
                    $arrResult[] = array(
                        "id"=>$val['suppCode']."|".$val['suppName'],
                        "label"=>$val['suppCode']." - ".str_replace("-",'-',$val['suppName']),
                        "value" => strip_tags($val['suppName']));    
                }
        echo json_encode($arrResult);
    exit();    
    break;
	
	case 'Print':
    
        ini_set('memory_limit', '2048M');
			//echo "window.open('manpowerBil_xls.php?{$_SERVER['QUERY_STRING']}');";
            //EXECUTE master.dbo.xp_cmdshell  'bcp "SELECT * from PGBIS..DIM_PROD_CAT" queryout C:\TEST.csv -tº -c    -Usa -Psa -Svash'
        $vendorPerformanceObj->manpowerBillingCsv();     
            
	exit();
	break;
    
    case 'dispPerVendor':
        $assPaidPosVendor = $vendorPerformanceObj->getPaidPositivePerVendor($_GET['hdnSuppCode']);
        $assPaidNegVendor = $vendorPerformanceObj->getPaidNegativePerVendor($_GET['hdnSuppCode']);

        $assPaidPosOutStaVendor = $vendorPerformanceObj->getOutstandingPositivePerVendor($_GET['hdnSuppCode']);
        $assPaidNegOutStaVendor = $vendorPerformanceObj->getOutstandingNegativePerVendor($_GET['hdnSuppCode']);
        
        $assPaidPosOutStaDueVendor = $vendorPerformanceObj->getOutstandingDuePositivePerVendor($_GET['hdnSuppCode']);
        $assPaidNegOutStaDueVendor = $vendorPerformanceObj->getOutstandingDueNegativePerVendor($_GET['hdnSuppCode']);
        ?>
            <table border="0" align="center" width="90%">
                <tr>
                    <td align="left"><font style="font-size: 12;">PER VENDOR : <?php echo $_GET['txtSupp']; ?> </font></td>
                </tr>
            </table>
            <table border=1 align="center" width="90%">
                <tr>
                    <td align="center" width="30%" bgcolor="lightgreen"><font style="font-size: 12;"><b> TYPE </b></font></td>
                    <td align="center" width="20%" bgcolor="lightgreen"><font style="font-size: 12;"><b> PAYABLES </b></font></td>
                    <td align="center" width="20%" bgcolor="lightgreen"><font style="font-size: 12;"><b> DEDUCTIBLES </b></font></td>
                    <td align="center" width="20%" bgcolor="lightgreen"><font style="font-size: 12;"><b> NET </b></font></td>
                    <td align="center" width="10%" bgcolor="lightgreen"><font style="font-size: 12;"><b> AGING </b></font></td>
                </tr>
                <tr>
                    <td align="left" width="30%"><font style="font-size: 12;"> Paid </td>
                    <td align="right" width="20%"><font style="font-size: 12;"><?php echo number_format($assPaidPosVendor['amount_applied_to_check'],2); ?></font></td>
                    <td align="right" width="20%"><font style="font-size: 12; color: red;"><?php echo number_format($assPaidNegVendor['amount_applied_to_check'],2); ?></font></td>
                    <?php $totPaidVendor = $assPaidPosVendor['amount_applied_to_check'] + $assPaidNegVendor['amount_applied_to_check']; ?>
                    <td align="right" width="20%">
                        <?php if($totPaidVendor < 0){ ?><font style="font-size: 12; color: red;"><?php }else{ ?><font style="font-size: 12;"><?php } ?>
                        <?php echo number_format($totPaidVendor,2); ?>
                        </font>
                    </td>
                    <td bgcolor="black"></td>
                </tr>
                <tr>
                    <td align="left" width="30%"><font style="font-size: 12;"> Outstanding - Current </td>
                    <td align="right" width="20%"><font style="font-size: 12;"><?php echo number_format($assPaidPosOutStaVendor['amount_remaining'],2); ?></font></td>
                    <td align="right" width="20%"><font style="font-size: 12; color: red;"><?php echo number_format($assPaidNegOutStaVendor['amount_remaining'],2); ?></font></td>
                    <?php $totPaidOutStaVendor = $assPaidPosOutStaVendor['amount_remaining'] + $assPaidNegOutStaVendor['amount_remaining']; ?>
                    <td align="right" width="20%">
                        <?php if($totPaidOutStaVendor < 0){ ?><font style="font-size: 12; color: red;"><?php }else{ ?><font style="font-size: 12;"><?php } ?>
                        <?php echo number_format($totPaidOutStaVendor,2); ?>
                        </font>
                    </td>
                    <td rowspan="2" align="center"><input type="button" name="viewAging" class="btn btn-success" onClick="agingPerVendor();" value="View"></td>
                </tr>
                <tr>
                    <td align="left" width="30%"><font style="font-size: 12;"> Outstanding - Due </b></font></td>
                    <td align="right" width="20%"><font style="font-size: 12;"><?php echo number_format($assPaidPosOutStaDueVendor['amount_remaining'],2); ?></font></td>
                    <td align="right" width="20%"><font style="font-size: 12; color: red;"><?php echo number_format($assPaidNegOutStaDueVendor['amount_remaining'],2); ?></font></td>
                    <?php $totPaidOutStaDueVendor = $assPaidPosOutStaDueVendor['amount_remaining'] + $assPaidNegOutStaDueVendor['amount_remaining']; ?>
                    <td align="right" width="20%">
                        <?php if($totPaidOutStaDueVendor < 0){ ?><font style="font-size: 12; color: red;"><?php }else{ ?><font style="font-size: 12;"><?php } ?>
                        <?php echo number_format($totPaidOutStaDueVendor,2); ?>
                        </font>
                    </td>
                </tr>
            </table>
        <?php
    exit();
    break;
    
    case 'dispAging':
        $assAgingHeader = $vendorPerformanceObj->agingHeader();
        
        ?>
            <br/>
            <table border="0" align="center" width="90%">
                <tr>
                    <td align="left"><font style="font-size: 12;">SUMMARY</font></td>
                </tr>
            </table>
            <table border=1 align="center" width="90%">
                <tr>
                    <td> </td>
                    <?php
                        foreach($assAgingHeader as $valHeader){
                            ?>
                            <td align="center" width="20%" bgcolor="lightgreen"><font style="font-size: 12;"><b> <?php echo $valHeader['bucket_name']; ?> </b></font></td>
                            <?php    
                        }
                    ?>
                </tr>
                <tr>
                    <td align="center" width="20%" bgcolor="lightgreen"><font style="font-size: 12;"><b> PAYABLES </b></font></td>
                    <?php
                        foreach($assAgingHeader as $valHeader){  
                            $assAgingPositive = $vendorPerformanceObj->agingPositive($valHeader['bucket_name']);
                            ?>
                            <td align="right" width="20%">
                            <?php if($assAgingPositive['amount_remaining'] < 0){ ?><font style="font-size: 12; color: red;"><?php }else{ ?><font style="font-size: 12;"><?php } ?>
                            <?php echo number_format($assAgingPositive['amount_remaining'],2); ?></font></td>
                            <?php 
                        }
                    ?>
                </tr>
                <tr>
                    <td align="center" width="20%" bgcolor="lightgreen"><font style="font-size: 12;"><b> DEDUCTIBLES </b></font></td>
                    <?php
                        foreach($assAgingHeader as $valHeader){
                            $assAgingNegative = $vendorPerformanceObj->agingNegative($valHeader['bucket_name']);
                            ?>
                            <td align="right" width="20%">
                            <?php if($assAgingNegative['amount_remaining'] < 0){ ?><font style="font-size: 12; color: red;"><?php }else{ ?><font style="font-size: 12;"><?php } ?>
                            <?php echo number_format($assAgingNegative['amount_remaining'],2); ?></font></td>
                            <?php 
                        }
                    ?>
                </tr>
                <tr>
                    <td align="center" width="20%" bgcolor="lightgreen"><font style="font-size: 12;"><b> TOTAL </b></font></td>
                    <?php
                        foreach($assAgingHeader as $valHeader){
                            $assAgingTotal = $vendorPerformanceObj->agingTotal($valHeader['bucket_name']);
                            ?>
                            <td align="right" width="20%">
                            <?php if($assAgingTotal['amount_remaining'] < 0){ ?><font style="font-size: 12; color: red;"><?php }else{ ?><font style="font-size: 12;"><?php } ?>
                            <b><?php echo number_format($assAgingTotal['amount_remaining'],2); ?> </b></font></td>
                            <?php 
                        }
                    ?>
                </tr>
            </table>
        <?php
    exit();
    break;
    
    case 'dispAgingPerVendor':
        $assAgingHeaderPerVendor = $vendorPerformanceObj->agingHeaderPerVendor($_GET['hdnSuppCode']);
        
        ?>
            <br/>
            <table border="0" align="center" width="90%">
                <tr>
                    <td align="left"><font style="font-size: 12;">PER VENDOR: <?php echo $_GET['txtSupp']; ?></font></td>
                </tr>
            </table>
            <table border=1 align="center" width="90%">
                <tr>
                    <td> </td>
                    <?php
                        foreach($assAgingHeaderPerVendor as $valHeaderPerVendor){
                            ?>
                            <td align="center" width="20%" bgcolor="lightgreen"><font style="font-size: 12;"><b> <?php echo $valHeaderPerVendor['bucket_name']; ?> </b></font></td>
                            <?php    
                        }
                    ?>
                </tr>
                <tr>
                    <td align="center" width="20%" bgcolor="lightgreen"><font style="font-size: 12;"><b> PAYABLES </b></font></td>
                    <?php
                        foreach($assAgingHeaderPerVendor as $valHeaderPerVendor){  
                            $assAgingPositivePerVendor = $vendorPerformanceObj->agingPositivePerVendor($_GET['hdnSuppCode'],$valHeaderPerVendor['bucket_name']);
                            ?>
                            <td align="right" width="20%">
                            <?php if($assAgingPositivePerVendor['amount_remaining'] < 0){ ?><font style="font-size: 12; color: red;"><?php }else{ ?><font style="font-size: 12;"><?php } ?>
                            <?php echo number_format($assAgingPositivePerVendor['amount_remaining'],2); ?></font></td>
                            <?php 
                        }
                    ?>
                </tr>
                <tr>
                    <td align="center" width="20%" bgcolor="lightgreen"><font style="font-size: 12;"><b> DEDUCTIBLES </b></font></td>
                    <?php
                        foreach($assAgingHeaderPerVendor as $valHeaderPerVendor){
                            $assAgingNegativePerVendor = $vendorPerformanceObj->agingNegativePerVendor($_GET['hdnSuppCode'],$valHeaderPerVendor['bucket_name']);
                            ?>
                            <td align="right" width="20%">
                            <?php if($assAgingNegativePerVendor['amount_remaining'] < 0){ ?><font style="font-size: 12; color: red;"><?php }else{ ?><font style="font-size: 12;"><?php } ?>
                            <?php echo number_format($assAgingNegativePerVendor['amount_remaining'],2); ?></font></td>
                            <?php 
                        }
                    ?>
                </tr>
                <tr>
                    <td align="center" width="20%" bgcolor="lightgreen"><font style="font-size: 12;"><b> TOTAL </b></font></td>
                    <?php
                        foreach($assAgingHeaderPerVendor as $valHeaderPerVendor){
                            $assAgingTotalPerVendor = $vendorPerformanceObj->agingTotalPerVendor($_GET['hdnSuppCode'],$valHeaderPerVendor['bucket_name']);
                            ?>
                            <td align="right" width="20%">
                            <?php if($assAgingTotalPerVendor['amount_remaining'] < 0){ ?><font style="font-size: 12; color: red;"><?php }else{ ?><font style="font-size: 12;"><?php } ?>
                            <b><?php echo number_format($assAgingTotalPerVendor['amount_remaining'],2); ?> </b></font></td>
                            <?php 
                        }
                    ?>
                </tr>
            </table>
            <br/>
            <table border="0" align="center" width="90%">  
                <tr>
                    <td align="right">
                        <input type="button" name="viewAgingCheckDetails" class="btn btn-success" onClick="agingPerVendorCheckDetails();" value="AP Details">
                    </td>
                </tr>
            </table>
            
        <?php
    exit();
    break;
    
    case 'dispAgingPerVendorCheckDetails':
        $agingCheckDetails = $vendorPerformanceObj->agingHeaderPerVendorCheckDetails($_GET['hdnSuppCode']);
        $storedProc =  $vendorPerformanceObj->storedProc($_GET['hdnSuppCode']);
        ?>
            <br />
            <table border="0" align="center" width="100%">
                <tr>
                    <td align="left"><font style="font-size: 12;">PER VENDOR: <?php echo $_GET['txtSupp']; ?></font></td>
                </tr>
            </table>
            <br />
            <table id="agingCheckDetails" class="agingCheckDetails" align="center" width="100%">
                 <thead>
                    <tr>
                        <td>INVOICE NUMBER</td>
                        <td>CURRENT POSITIVE</td>
                        <td>CURRENT NEGATIVE</td>
                        <td>OVER30 POSITIVE</td>
                        <td>OVER30 NEGATIVE</td>
                        <td>OVER60 POSITIVE</td>
                        <td>OVER60 NEGATIVE</td>
                        <td>OVER90 POSITIVE</td>
                        <td>OVER90 NEGATIVE</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($storedProc as $val) {
                    //$agingCheckDetailsPositiveCurrent = $vendorPerformanceObj->agingHeaderPerVendorCheckDetailsPositiveCurrent($_GET['hdnSuppCode'],$val['invoice_num']);
                    //$agingCheckDetailsPositiveOver30 = $vendorPerformanceObj->agingHeaderPerVendorCheckDetailsPositiveOver30($_GET['hdnSuppCode'],$val['invoice_num']);
                    //$agingCheckDetailsPositiveOver60 = $vendorPerformanceObj->agingHeaderPerVendorCheckDetailsPositiveOver60($_GET['hdnSuppCode'],$val['invoice_num']);
                    //$agingCheckDetailsPositiveOver90 = $vendorPerformanceObj->agingHeaderPerVendorCheckDetailsPositiveOver90($_GET['hdnSuppCode'],$val['invoice_num']);
                    //$agingCheckDetailsNegativeCurrent = $vendorPerformanceObj->agingHeaderPerVendorCheckDetailsNegativeCurrent($_GET['hdnSuppCode'],$val['invoice_num']);
                    //$agingCheckDetailsNegativeOver30 = $vendorPerformanceObj->agingHeaderPerVendorCheckDetailsNegativeOver30($_GET['hdnSuppCode'],$val['invoice_num']);
                    //$agingCheckDetailsNegativeOver60 = $vendorPerformanceObj->agingHeaderPerVendorCheckDetailsNegativeOver60($_GET['hdnSuppCode'],$val['invoice_num']);
                    //$agingCheckDetailsNegativeOver90 = $vendorPerformanceObj->agingHeaderPerVendorCheckDetailsNegativeOver90($_GET['hdnSuppCode'],$val['invoice_num']);
                    ?>
                    <tr>
                        <td align="left"><? echo $val['invoice_num']; ?></td>
                        <td align="right"><? if($val['pos_cur'] < 0){ ?><font style="color: red;"><?php } ?><? echo number_format($val['pos_cur'],2); ?></font></td>
                        <td align="right"><? if($val['neg_cur'] < 0){ ?><font style="color: red;"><?php } ?><? echo number_format($val['neg_cur'],2); ?></font></td>
                        <td align="right"><? if($val['pos_over30'] < 0){ ?><font style="color: red;"><?php } ?><? echo number_format($val['pos_over30'],2); ?></font></td>
                         <td align="right"><? if($val['neg_over30'] < 0){ ?><font style="color: red;"><?php } ?><? echo number_format($val['neg_over30'],2); ?></font></td> 
                        <td align="right"><? if($val['pos_over60'] < 0){ ?><font style="color: red;"><?php } ?><? echo number_format($val['pos_over60'],2); ?></font></td>
                        <td align="right"><? if($val['neg_over60'] < 0){ ?><font style="color: red;"><?php } ?><? echo number_format($val['neg_over60'],2); ?></font></td>    
                        <td align="right"><? if($val['pos_over90'] < 0){ ?><font style="color: red;"><?php } ?><? echo number_format($val['pos_over90'],2); ?></font></td>
                        <td align="right"><? if($val['neg_over90'] < 0){ ?><font style="color: red;"><?php } ?><? echo number_format($val['neg_over90'],2); ?></font></td>
                    </tr>
                    <?php 
                    $tot_pos_cur += $val['pos_cur'];
                    $tot_neg_cur += $val['neg_cur'];
                    $tot_pos_over30 += $val['pos_over30'];
                    $tot_neg_over30 += $val['neg_over30'];
                    $tot_pos_over60 += $val['pos_over60'];
                    $tot_neg_over60 += $val['neg_over60'];
                    $tot_pos_over90 += $val['pos_over90'];
                    $tot_neg_over90 += $val['neg_over90'];
                    }?> 
            </tbody>
            <tfoot>
                    <tr>
                        <td align="left">TOTAL</td>
                        <td align="left"><? if($tot_pos_cur < 0){ ?><font style="color: red;"><?php } ?><? echo number_format($tot_pos_cur,2); ?></font></td>
                        <td align="right"><? if($tot_neg_cur < 0){ ?><font style="color: red;"><?php } ?><? echo number_format($tot_neg_cur,2); ?></font></td>
                        <td align="right"><? if($tot_pos_over30 < 0){ ?><font style="color: red;"><?php } ?><? echo number_format($tot_pos_over30,2); ?></font></td>
                        <td align="right"><? if($tot_neg_over30 < 0){ ?><font style="color: red;"><?php } ?><? echo number_format($tot_neg_over30,2); ?></font></td> 
                        <td align="right"><? if($tot_pos_over60 < 0){ ?><font style="color: red;"><?php } ?><? echo number_format($tot_pos_over60,2); ?></font></td>
                        <td align="right"><? if($tot_neg_over60 < 0){ ?><font style="color: red;"><?php } ?><? echo number_format($tot_neg_over60,2); ?></font></td>    
                        <td align="right"><? if($tot_pos_over90 < 0){ ?><font style="color: red;"><?php } ?><? echo number_format($tot_pos_over90,2); ?></font></td>
                        <td align="right"><? if($tot_neg_over90 < 0){ ?><font style="color: red;"><?php } ?><? echo number_format($tot_neg_over90,2); ?></font></td>
                    </tr> 
                </tfoot>

            </table>
        <?php
    exit();
    break;
		
}

$arrOrgId = array('0'=>"All",'85'=>"PPCI",'87'=>"JR",'133'=>"PUREGOLD SUBIC");

$assDataAsOf = $vendorPerformanceObj->getDateTime();

$assPaidPos = $vendorPerformanceObj->getPaidPositive();
$assPaidNeg = $vendorPerformanceObj->getPaidNegative();

$assPaidPosOutSta = $vendorPerformanceObj->getOutstandingPositive();
$assPaidNegOutSta = $vendorPerformanceObj->getOutstandingNegative();

$assPaidPosOutStaDue = $vendorPerformanceObj->getOutstandingDuePositive();
$assPaidNegOutStaDue = $vendorPerformanceObj->getOutstandingDueNegative();


?>

<html>
	<head>
    	<!-- jQuery, Bootstrap -->
    	<link rel="stylesheet" href="../../../includes/bootstrap/css/bootstrap.css"/>
        <link rel="stylesheet" href="../../../includes/bootstrap/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="../../../includes/bootstrap/css/bootstrap-responsive.css"/>
        <link rel="stylesheet" href="../../../includes/bootstrap/css/bootstrap-responsive.min.css"/>
        <!-- jQuery, Bootstrap -->
        
		<link type="text/css" href="../../../includes/jquery/css/redmond/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
		<link type="text/css" href="../../../includes/jquery/development-bundle/demos/demos.css" rel="stylesheet" />
		
        <script src="../../../includes/jquery/js/jquery-1.6.2.min.js"></script>
        <script src="../../../includes/jquery/js/jquery-ui-1.8.16.custom.min.js"></script>
        <script src="../../../includes/bootbox/bootbox.js"></script>
        
        <script src="../../../includes/toastmessage/src/main/javascript/jquery.toastmessage.js"></script>
        <link rel="stylesheet" type="text/css" href="../../../includes/toastmessage/src/main/resources/css/jquery.toastmessage.css" />
        
        <script src="../../../includes/modal/modal.js"></script>
        <link rel="stylesheet" href="../../../includes/modal/modal.css">
        
        
        <link href="../../../includes/showLoading/css/showLoading.css" rel="stylesheet" media="screen" /> 
        <!--<script type="text/javascript" src="../../../includes/showLoading/js/jquery-1.3.2.min.js"></script>-->
        <script type="text/javascript" src="../../../includes/showLoading/js/jquery.showLoading.js"></script>
        
        <script src="../../../includes/jquery/js/jquery-ui-1.8.16.custom.min.js"></script>
        <script src="../../../includes/jquery/js/jquery.dataTables.min.js"></script>   
        <script src="../../../includes/jquery/js/dataTables.bootstrap.js"></script>   
        <style type="text/css" title="currentStyle">
            @import "../../../includes/jquery/css/jquery.dataTables_themeroller.css";
        </style>
        
        <script type="text/javascript">
		
		function perVendor(){
		
			$.ajax({
                url: 'vendorPerformance.php',
                type: "GET",
                data: $("#formInq").serialize()+'&action=dispPerVendor',
                beforeSend: function() {
                    jQuery('#activity_pane').showLoading();
                },
                success: function(Data){
                    jQuery('#activity_pane').hideLoading();
                    $("#divPerVendor").html(Data);
                }                
            });    
		}
		
		$(function(){
			$('#txtDateFrom, #txtDateTo').datepicker({
				dateFormat : 'yy-mm-dd'
			});
		});     
		
		function valDateStartEnd(valStart,valEnd,id1,id2) {
			var parseStart = Date.parse(valStart);
			var parseEnd = Date.parse(valEnd);
			if (valStart !='' && valEnd !='') {
				if(parseStart > parseEnd) {
					$('#'+id1).addClass('ui-state-error');
					$('#'+id2).addClass('ui-state-error');
					$().toastmessage('showToast', {
						text: 'Date TO Must Be Greater than Date FROM!',
						sticky: true,
						position: 'middle-center',
						type: 'error',
						closeText: '',
						close: function () 
						{
						console.log("toast is closed ...");
						}
					});
					return false;
				} else {
					$('#'+id1).removeClass('ui-state-error');
					$('#'+id2).removeClass('ui-state-error');	
					return true;
				}
			}else {
				$('#'+id1).addClass('ui-state-error');
				$('#'+id2).addClass('ui-state-error');
				$().toastmessage('showToast', {
					text: 'Please Select Date Range!',
					sticky: true,
					position: 'middle-center',
					type: 'error',
					closeText: '',
					close: function () 
					{
					console.log("toast is closed ...");
					}
				});
				return false;
			}
		}
		
		function numericFilter(txb) {
		   txb.value = txb.value.replace(/[^0-9]/ig, "");
		}
		
		function makeBlank(){
		$('#tags').val('');
		$('#tagsid').val('');
		}
		
		$(function() {
			$( "#tags" ).autocomplete({
				source: function(request, response) {
					$.getJSON('leasingSetup.php?action=GETAUTO', {
						term: request.term
					}, response);
				},
				
				select: function(event, ui) {
					var tagId = ui.item.id;
					
					$('#tags').val(tagId);
					$('#tagsid').val(tagId);
				}
			})
		});
        
         $(function(){
            $("#txtSupp").autocomplete({
                source: "vendorPerformance.php?action=searchSupplier",
                minLength: 1,
                select: function(event, ui) {    
                    var content = ui.item.id.split("|");
                    $("#hdnSuppCode").val(content[0]);
                    $("#txtRep").val(content[1]);
                }
            }); 
        }); 
        
        function aging(){
        
            $.ajax({
                url: 'vendorPerformance.php',
                type: "GET",
                data: 'action=dispAging',
                beforeSend: function() {
                    jQuery('#activity_pane').showLoading();
                },
                success: function(Data){
                    jQuery('#activity_pane').hideLoading();
                    $(function() {
                        $("#divDialogAging").html(Data);
                        $('#divDialogAging').dialog({ 
                            height : 200,
                            show: 'blind',
                            hide: 'blind',
                            width : 800,
                            resizable: false,
                            modal: true,
                            closeOnEscape:false,
                            title: 'AGING',
                        })  
                    });
                }                
            });    
        }
        
        function agingPerVendor(){
        
            $.ajax({
                url: 'vendorPerformance.php',
                type: "GET",
                data: $("#formInq").serialize()+'&action=dispAgingPerVendor',
                beforeSend: function() {
                    jQuery('#activity_pane').showLoading();
                },
                success: function(Data){
                    jQuery('#activity_pane').hideLoading();
                    $(function() {
                        $("#divDialogAgingPerVendor").html(Data);
                        $('#divDialogAgingPerVendor').dialog({ 
                            height : 200,
                            show: 'blind',
                            hide: 'blind',
                            width : 800,
                            resizable: false,
                            modal: true,
                            closeOnEscape:false,
                            title: 'AGING',
                        })  
                    });
                }                
            });    
        }
        
        function agingPerVendorCheckDetails(){
        
            $.ajax({
                url: 'vendorPerformance.php',
                type: "GET",
                data: $("#formInq").serialize()+'&action=dispAgingPerVendorCheckDetails',
                beforeSend: function() {
                    jQuery('#activity_pane').showLoading();
                },
                success: function(Data){
                    jQuery('#activity_pane').hideLoading();
                    $(function() {
                        $("#divDialogAgingPerVendorCheckDetails").html(Data);
                        $('#divDialogAgingPerVendorCheckDetails').dialog({ 
                            height : 500,
                            show: 'slideleft',
                            hide: 'slideleft',
                            width : 800,
                            resizable: false,
                            modal: true,
                            closeOnEscape:false,
                            title: 'AGING CHECK DETAILS',
                        })
                        $('#agingCheckDetails').dataTable({
                            "sPaginationType": "full_numbers"
                        })   

                    });
                }                
            });    
        }
		
		</script>
        
        <style type="text/css">
		.selectBox {
			width:205px;
		}
		
		.selectBox2 {
			width:495px;
		}
		
		<!--
		input,
		textarea,
		select {
			padding: 3px;
			font: 900 1em Verdana, Sans-serif;
			font-size:11px;
			color: #333;
			background:#eee;
			border: 1px solid #ccc;
			margin:0 0 0px 0;
			width:700px%;
		}
		input:focus,
		textarea:focus,
		select:focus {
			background: #fff;
			border: 1px solid #999;
		}
		
		input,
		textarea {
		  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
		  -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
		  box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
		  -webkit-transition: border linear 0.2s, box-shadow linear 0.2s;
		  -moz-transition: border linear 0.2s, box-shadow linear 0.2s;
		  -ms-transition: border linear 0.2s, box-shadow linear 0.2s;
		  -o-transition: border linear 0.2s, box-shadow linear 0.2s;
		  transition: border linear 0.2s, box-shadow linear 0.2s;
		}
		input:focus,
		textarea:focus {
		  border-color: rgba(82, 168, 236, 0.8);
		  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(82, 168, 236, 0.6);
		  -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(82, 168, 236, 0.6);
		  box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(82, 168, 236, 0.6);
		  outline: 0;
		  outline: thin dotted \9;
		  /* IE6-9 */
		}
		.dvContainer{
			margin-left:10px;
		}
		#input-userName{
			margin-left:50px;
		}
		
		
		a {
		  color: blue;
		  cursor:pointer;
			  text-decoration: underline;
		}

			div.instructions_container {
		   float: left;
			   width: 100%;

			}

		div#activity_pane {
			   float:left;
			   width: 100%;
			   height: 100%;
			   border: 1px solid #CCCCCC;
			   background-color:#FFF;
		   padding-top: 0px;
		   text-align: center;
			   
			}

			div.example_links 
			 .link_category {
			   margin-bottom: 15px;
			}

		.loading-indicator-bars {
			background-image: url('images/loading-bars.gif');
			width: 150px;
		}
        
        .ui-widget-content{
            background: #F9F9F9;
            border: 1px solid #90d93f;
            color: #222222;
        }
        
        .ui-dialog{
            left: 0;
            outline: 0 none;
            padding: 0 !important;
            position: absolute;
            top: 0;
        }
        
        #success {
            padding:0;
            margin: 0;
        }
        
        .ui-dialog .ui-dialog-content{
            background: none repeat scroll 0 0 transfarent;
            border: 0 none;
            overflow: auto;
            position: relative;
            padding:0 !important;
        }
        
        .ui-widget-header{
            background: #bd0de78;
            border: 0;
            color: #fff;
            font-weight: normal;
        }
        
        .ui-dialog .ui-dialog-titlebar{
            background:lightgreen;
            color: #333;
            border-color: green; 
        }
        
        .ui.datatable-header {
            background-color: transparent
            !important;
            border: none !important;
        }
        
        table#agingCheckDetails tr.even td {
            background-color: white;
        }

        table#agingCheckDetails tr.odd td {
            background-color: lightgreen;
        }
        
        table#agingCheckDetails tr.even:hover td {
            background-color: lightgray;
        }

        table#agingCheckDetails tr.odd:hover td {
            background-color: lightgray;
        }
		-->	
        
        
        
		</style>   
    </head>
    	<body>
			<div id="activity_pane">
			<br />
                <div class="dvContainer">
                	<table border=0 align="center" width="90%">
						<th colspan="6">
                        	<h4 align="center"><font style="font-family:Lucida Handwriting"> Vendor Trial Balance </font></h4>
                        </th>
						<tr>
                            <td colspan = "6"><font style="color: blue; font-size: 12;"><b> Data as of: </b></font><font style="font-size: 12;"><?php echo $assDataAsOf['dateTime']; ?></font></td>
						</tr>
                     </table>
                     
                     <br>
                     
                     <table border=0 align="center" width="90%">
                        <tr>
                            <td align="left"><font style="font-size: 12;">SUMMARY</font></td>
                        </tr>
                     </table>
                     <table border=1 align="center" width="90%">
                        <tr>
                            <td align="center" width="30%" bgcolor="lightgreen"><font style="font-size: 12;"><b> TYPE </b></font></td>
                            <td align="center" width="20%" bgcolor="lightgreen"><font style="font-size: 12;"><b> PAYABLES </b></font></td>
                            <td align="center" width="20%" bgcolor="lightgreen"><font style="font-size: 12;"><b> DEDUCTIBLES </b></font></td>
                            <td align="center" width="20%" bgcolor="lightgreen"><font style="font-size: 12;"><b> NET </b></font></td>
                            <td align="center" width="10%" bgcolor="lightgreen"><font style="font-size: 12;"><b> AGING </b></font></td>
                        </tr>
                        <tr>
                            <td align="left" width="30%"><font style="font-size: 12;"> Paid </td>
                            <td align="right" width="20%"><font style="font-size: 12;"><?php echo number_format($assPaidPos['amount_applied_to_check'],2); ?></font></td>
                            <td align="right" width="20%"><font style="font-size: 12; color: red;"><?php echo number_format($assPaidNeg['amount_applied_to_check'],2); ?></font></td>
                            <?php $totPaid = $assPaidPos['amount_applied_to_check'] + $assPaidNeg['amount_applied_to_check']; ?>
                            <td align="right" width="20%">
                                <?php if($totPaid < 0){ ?><font style="font-size: 12; color: red;"><?php }else{ ?><font style="font-size: 12;"><?php } ?>
                                <?php echo number_format($totPaid,2); ?>
                                </font>
                            </td>
                            <td bgcolor="black"></td>
                        </tr>
                        <tr>
                            <td align="left" width="30%"><font style="font-size: 12;"> Outstanding - Current </td>
                            <td align="right" width="20%"><font style="font-size: 12;"><?php echo number_format($assPaidPosOutSta['amount_remaining'],2); ?></font></td>
                            <td align="right" width="20%"><font style="font-size: 12; color: red;"><?php echo number_format($assPaidNegOutSta['amount_remaining'],2); ?></font></td>
                            <?php $totPaidOutSta = $assPaidPosOutSta['amount_remaining'] + $assPaidNegOutSta['amount_remaining']; ?>
                            <td align="right" width="20%">
                                <?php if($totPaidOutSta < 0){ ?><font style="font-size: 12; color: red;"><?php }else{ ?><font style="font-size: 12;"><?php } ?>
                                <?php echo number_format($totPaidOutSta,2); ?>
                                </font>
                            </td>
                            <td rowspan="2" align="center"><input type="button" name="viewAging" class="btn btn-success" onClick="aging();" value="View"></td>
                        </tr>
                        <tr>
                            <td align="left" width="30%"><font style="font-size: 12;"> Outstanding - Due </b></font></td>
                            <td align="right" width="20%"><font style="font-size: 12;"><?php echo number_format($assPaidPosOutStaDue['amount_remaining'],2); ?></font></td>
                            <td align="right" width="20%"><font style="font-size: 12; color: red;"><?php echo number_format($assPaidNegOutStaDue['amount_remaining'],2); ?></font></td>
                            <?php $totPaidOutStaDue = $assPaidPosOutStaDue['amount_remaining'] + $assPaidNegOutStaDue['amount_remaining']; ?>
                            <td align="right" width="20%">
                                <?php if($totPaidOutStaDue < 0){ ?><font style="font-size: 12; color: red;"><?php }else{ ?><font style="font-size: 12;"><?php } ?>
                                <?php echo number_format($totPaidOutStaDue,2); ?>
                                </font>
                            </td>
                        </tr>
                        <!-- 
                        <tr>
                            <td colspan="6" align="center">
                                <input type="button" name="submit" class="btn btn-success" onClick="printXls();" value="Export to CSV">
                            </td>
                        </tr>
                        -->
                     </table>
                     <br/>
                     <form name="formInq" id="formInq">
                     <input type="hidden" id="hdnSuppCode" name="hdnSuppCode" />
                     <table border=0 align="center" width="90%">
                        <tr>
                            <td width="10%"><font style="font-size: 12;">Vendor Search: </font></td>
                            <td align="left" width="30%"><input type="text" name="txtSupp" id="txtSupp" class="textBox" style="height:30px; width: 400px;" onclick="(this.value='')"/></td>
                            <td><input type="button" name="submit" class="btn btn-success" onClick="perVendor();" value="Submit"></td>
                        </tr>
                     </table>
                     </form>    
                     <div id="divPerVendor" name="divPerVendor"></div>
                </div>
                
            
            
            <!--<img src="../../../includes/images/Spinner.gif">-->
  
			</div>
            
            <div style="clear:both;"></div>
            
            <div id="divDialogAging" style="display:none;"></div>
            <div id="divDialogAgingPerVendor" style="display:none;"></div>
            <div id="divDialogAgingPerVendorCheckDetails" style="display:none;"></div>
			
        </body>
</html>