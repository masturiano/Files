<?php
session_start();
include "../../../adodb/adodb.inc.php";
include("../../../includes/db.inc.php");
include("../../../includes/common.php");
include("customerPerformanceObj.php");

$customerPerformanceObj = new customerPerformanceObj();

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
    
    case 'searchCustomer':
        $arrResult = array();
            $arrSupp = $customerPerformanceObj->findCustomer($_GET['term']);
                foreach($arrSupp as $val){
                    $arrResult[] = array(
                        "id"=>$val['cusNum']."|".$val['cusName'],
                        "label"=>$val['cusNum']." - ".str_replace("-",'-',$val['cusName']),
                        "value" => strip_tags($val['cusName']));    
                }
        echo json_encode($arrResult);
    exit();    
    break;
	
	case 'Print':
    
        ini_set('memory_limit', '2048M');
			//echo "window.open('manpowerBil_xls.php?{$_SERVER['QUERY_STRING']}');";
            //EXECUTE master.dbo.xp_cmdshell  'bcp "SELECT * from PGBIS..DIM_PROD_CAT" queryout C:\TEST.csv -tº -c    -Usa -Psa -Svash'
        $customerPerformanceObj->manpowerBillingCsv();     
            
	exit();
	break;
    
    case 'dispPerCustomer':
        $assPosCustomer= $customerPerformanceObj->getPositivePerCustomer($_GET['hdnCusCode']);
        $assNegCustomer= $customerPerformanceObj->getNegativePerCustomer($_GET['hdnCusCode']);

        $assPosOutStaCustomer = $customerPerformanceObj->getOutstandingPositivePerCustomer($_GET['hdnCusCode']);
        $assNegOutStaCustomer = $customerPerformanceObj->getOutstandingNegativePerCustomer($_GET['hdnCusCode']);
        
        $assPosOutStaDueCustomer = $customerPerformanceObj->getOutstandingDuePositivePerCustomer($_GET['hdnCusCode']);
        $assNegOutStaDueCustomer = $customerPerformanceObj->getOutstandingDueNegativePerCustomer($_GET['hdnCusCode']);
        ?>
            <table border="0" align="center" width="90%">
                <tr>
                    <td align="left"><font style="font-size: 12;">PER CUSTOMER : <?php echo $_GET['txtCus']; ?> </font></td>
                </tr>
            </table>
            <table border=1 align="center" width="90%">
                <tr>
                    <td align="center" width="30%" bgcolor="lightgreen"><font style="font-size: 12;"><b> TYPE </b></font></td>
                    <td align="center" width="20%" bgcolor="lightgreen"><font style="font-size: 12;"><b> RECEIVABLES </b></font></td>
                    <td align="center" width="20%" bgcolor="lightgreen"><font style="font-size: 12;"><b> DEDUCTIBLES </b></font></td>
                    <td align="center" width="20%" bgcolor="lightgreen"><font style="font-size: 12;"><b> NET </b></font></td>
                    <td align="center" width="10%" bgcolor="lightgreen"><font style="font-size: 12;"><b> AGING </b></font></td>
                </tr>
                <tr>
                    <td align="left" width="30%"><font style="font-size: 12;"> Unapplied </td>
                    <td align="right" width="20%"><font style="font-size: 12;"><?php echo number_format($assPosCustomer['amount_due_remaining'],2); ?></font></td>
                    <td align="right" width="20%"><font style="font-size: 12; color: red;"><?php echo number_format($assNegCustomer['amount_due_remaining'],2); ?></font></td>
                    <?php $totPaidCustomer = $assPosCustomer['amount_due_remaining'] + $assNegCustomer['amount_due_remaining']; ?>
                    <td align="right" width="20%">
                        <?php if($totPaidCustomer < 0){ ?><font style="font-size: 12; color: red;"><?php }else{ ?><font style="font-size: 12;"><?php } ?>
                        <?php echo number_format($totPaidCustomer,2); ?>
                        </font>
                    </td>
                    <td bgcolor="black"></td>
                </tr>
                <tr>
                    <td align="left" width="30%"><font style="font-size: 12;"> Outstanding - Current </td>
                    <td align="right" width="20%"><font style="font-size: 12;"><?php echo number_format($assPosOutStaCustomer['amount_due_remaining'],2); ?></font></td>
                    <td align="right" width="20%"><font style="font-size: 12; color: red;"><?php echo number_format($assNegOutStaCustomer['amount_due_remaining'],2); ?></font></td>
                    <?php $totPaidOutStaCustomer = $assPosOutStaCustomer['amount_due_remaining'] + $assNegOutStaCustomer['amount_due_remaining']; ?> 
                    <td align="right" width="20%">
                        <?php if($totPaidOutStaCustomer < 0){ ?><font style="font-size: 12; color: red;"><?php }else{ ?><font style="font-size: 12;"><?php } ?>
                        <?php echo number_format($totPaidOutStaCustomer,2); ?>
                        </font>
                    </td>
                    <td rowspan="2" align="center"><input type="button" name="viewAging" class="btn btn-success" onClick="agingPerCustomer();" value="View"></td>
                </tr>
                <tr>
                    <td align="left" width="30%"><font style="font-size: 12;"> Outstanding - Due </b></font></td>
                    <td align="right" width="20%"><font style="font-size: 12;"><?php echo number_format($assPosOutStaDueCustomer['amount_due_remaining'],2); ?></font></td>
                    <td align="right" width="20%"><font style="font-size: 12; color: red;"><?php echo number_format($assNegOutStaDueCustomer['amount_due_remaining'],2); ?></font></td>
                    <?php $totPaidOutStaDueCustomer = $assPosOutStaDueCustomer['amount_due_remaining'] + $assNegOutStaDueCustomer['amount_due_remaining']; ?>
                    <td align="right" width="20%">
                        <?php if($totPaidOutStaDueCustomer < 0){ ?><font style="font-size: 12; color: red;"><?php }else{ ?><font style="font-size: 12;"><?php } ?>
                        <?php echo number_format($totPaidOutStaDueCustomer,2); ?>
                        </font>
                    </td>
                </tr>
            </table>
        <?php
    exit();
    break;
    
    case 'dispAging':
        $assAgingHeader = $customerPerformanceObj->agingHeader();
        
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
                    <td align="center" width="20%" bgcolor="lightgreen"><font style="font-size: 12;"><b> RECEIVABLES </b></font></td>
                    <?php
                        foreach($assAgingHeader as $valHeader){  
                            $assAgingPositive = $customerPerformanceObj->agingPositive($valHeader['bucket_name']);
                            ?>
                            <td align="right" width="20%">
                            <?php if($assAgingPositive['amount_due_remaining'] < 0){ ?><font style="font-size: 12; color: red;"><?php }else{ ?><font style="font-size: 12;"><?php } ?>
                            <?php echo number_format($assAgingPositive['amount_due_remaining'],2); ?></font></td>
                            <?php 
                        }
                    ?>
                </tr>
                <tr>
                    <td align="center" width="20%" bgcolor="lightgreen"><font style="font-size: 12;"><b> DEDUCTIBLES </b></font></td>
                    <?php
                        foreach($assAgingHeader as $valHeader){
                            $assAgingNegative = $customerPerformanceObj->agingNegative($valHeader['bucket_name']);
                            ?>
                            <td align="right" width="20%">
                            <?php if($assAgingNegative['amount_due_remaining'] < 0){ ?><font style="font-size: 12; color: red;"><?php }else{ ?><font style="font-size: 12;"><?php } ?>
                            <?php echo number_format($assAgingNegative['amount_due_remaining'],2); ?></font></td>
                            <?php 
                        }
                    ?>
                </tr>
                <tr>
                    <td align="center" width="20%" bgcolor="lightgreen"><font style="font-size: 12;"><b> TOTAL </b></font></td>
                    <?php
                        foreach($assAgingHeader as $valHeader){
                            $assAgingTotal = $customerPerformanceObj->agingTotal($valHeader['bucket_name']);
                            ?>
                            <td align="right" width="20%">
                            <?php if($assAgingTotal['amount_due_remaining'] < 0){ ?><font style="font-size: 12; color: red;"><?php }else{ ?><font style="font-size: 12;"><?php } ?>
                            <b><?php echo number_format($assAgingTotal['amount_due_remaining'],2); ?> </b></font></td>
                            <?php 
                        }
                    ?>
                </tr>
            </table>
        <?php
    exit();
    break;
    
    case 'dispAgingPerCustomer':
        $assAgingHeaderPerCustomer = $customerPerformanceObj->agingHeaderPerCustomer($_GET['hdnCusCode']);
        
        ?>
            <br/>
            <table border="0" align="center" width="90%">
                <tr>
                    <td align="left"><font style="font-size: 12;">PER CUSTOMER: <?php echo $_GET['txtCus']; ?></font></td>
                </tr>
            </table>
            <table border=1 align="center" width="90%">
                <tr>
                    <td> </td>
                    <?php
                        foreach($assAgingHeaderPerCustomer as $valHeaderPerCustomer){
                            ?>
                            <td align="center" width="20%" bgcolor="lightgreen"><font style="font-size: 12;"><b> <?php echo $valHeaderPerCustomer['bucket_name']; ?> </b></font></td>
                            <?php    
                        }
                    ?>
                </tr>
                <tr>
                    <td align="center" width="20%" bgcolor="lightgreen"><font style="font-size: 12;"><b> RECEIVABLES </b></font></td>
                    <?php
                        foreach($assAgingHeaderPerCustomer as $valHeaderPerCustomer){  
                            $assAgingPositivePerCustomer = $customerPerformanceObj->agingPositivePerCustomer($_GET['hdnCusCode'],$valHeaderPerCustomer['bucket_name']);
                            ?>
                            <td align="right" width="20%">
                            <?php if($assAgingPositivePerCustomer['amount_due_remaining'] < 0){ ?><font style="font-size: 12; color: red;"><?php }else{ ?><font style="font-size: 12;"><?php } ?>
                            <?php echo number_format($assAgingPositivePerCustomer['amount_due_remaining'],2); ?></font></td>
                            <?php 
                        }
                    ?>
                </tr>
                <tr>
                    <td align="center" width="20%" bgcolor="lightgreen"><font style="font-size: 12;"><b> DEDUCTIBLES </b></font></td>
                    <?php
                        foreach($assAgingHeaderPerCustomer as $valHeaderPerCustomer){
                            $assAgingNegativePerCustomer = $customerPerformanceObj->agingNegativePerCustomer($_GET['hdnCusCode'],$valHeaderPerCustomer['bucket_name']);
                            ?>
                            <td align="right" width="20%">
                            <?php if($assAgingNegativePerCustomer['amount_due_remaining'] < 0){ ?><font style="font-size: 12; color: red;"><?php }else{ ?><font style="font-size: 12;"><?php } ?>
                            <?php echo number_format($assAgingNegativePerCustomer['amount_due_remaining'],2); ?></font></td>
                            <?php 
                        }
                    ?>
                </tr>
                <tr>
                    <td align="center" width="20%" bgcolor="lightgreen"><font style="font-size: 12;"><b> TOTAL </b></font></td>
                    <?php
                        foreach($assAgingHeaderPerCustomer as $valHeaderPerCustomer){
                            $assAgingTotalPerCustomer = $customerPerformanceObj->agingTotalPerCustomer($_GET['hdnCusCode'],$valHeaderPerCustomer['bucket_name']);
                            ?>
                            <td align="right" width="20%">
                            <?php if($assAgingTotalPerCustomer['amount_due_remaining'] < 0){ ?><font style="font-size: 12; color: red;"><?php }else{ ?><font style="font-size: 12;"><?php } ?>
                            <b><?php echo number_format($assAgingTotalPerCustomer['amount_due_remaining'],2); ?> </b></font></td>
                            <?php 
                        }
                    ?>
                </tr>
            </table>
            <br/>
            <table border="0" align="center" width="90%">  
                <tr>
                    <td align="right">
                        <input type="button" name="viewAgingCheckDetails" class="btn btn-success" onClick="agingPerCustomerCheckDetails();" value="Check Details">
                    </td>
                </tr>
            </table>
            
        <?php
    exit();
    break;
    
    case 'dispAgingPerCustomerCheckDetails':
        $agingCheckDetails = $customerPerformanceObj->agingHeaderPerCustomerCheckDetails($_GET['hdnCusCode']);
        $storedProc =  $customerPerformanceObj->storedProc($_GET['hdnCusCode']);
        ?>
            <br />
            <table border="0" align="center" width="100%">
                <tr>
                    <td align="left"><font style="font-size: 12;">PER CUSTOMER: <?php echo $_GET['txtCus']; ?></font></td>
                </tr>
            </table>
            <br />
            <table id="agingCheckDetails" class="agingCheckDetails" align="center" width="100%">
                 <thead>
                    <tr>
                        <td>TRANSACTION NUMBER</td>
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
                    //$agingCheckDetailsPositiveCurrent = $customerPerformanceObj->agingHeaderPerVendorCheckDetailsPositiveCurrent($_GET['hdnSuppCode'],$val['invoice_num']);
                    //$agingCheckDetailsPositiveOver30 = $customerPerformanceObj->agingHeaderPerVendorCheckDetailsPositiveOver30($_GET['hdnSuppCode'],$val['invoice_num']);
                    //$agingCheckDetailsPositiveOver60 = $customerPerformanceObj->agingHeaderPerVendorCheckDetailsPositiveOver60($_GET['hdnSuppCode'],$val['invoice_num']);
                    //$agingCheckDetailsPositiveOver90 = $customerPerformanceObj->agingHeaderPerVendorCheckDetailsPositiveOver90($_GET['hdnSuppCode'],$val['invoice_num']);
                    //$agingCheckDetailsNegativeCurrent = $customerPerformanceObj->agingHeaderPerVendorCheckDetailsNegativeCurrent($_GET['hdnSuppCode'],$val['invoice_num']);
                    //$agingCheckDetailsNegativeOver30 = $customerPerformanceObj->agingHeaderPerVendorCheckDetailsNegativeOver30($_GET['hdnSuppCode'],$val['invoice_num']);
                    //$agingCheckDetailsNegativeOver60 = $customerPerformanceObj->agingHeaderPerVendorCheckDetailsNegativeOver60($_GET['hdnSuppCode'],$val['invoice_num']);
                    //$agingCheckDetailsNegativeOver90 = $customerPerformanceObj->agingHeaderPerVendorCheckDetailsNegativeOver90($_GET['hdnSuppCode'],$val['invoice_num']);
                    ?>
                    <tr>
                        <td align="left"><? echo $val['trx_number']; ?></td>
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

$assDataAsOf = $customerPerformanceObj->getDateTime();

$assUnappliedPos = $customerPerformanceObj->getUnappliedPositive();
$assUnappliedNeg = $customerPerformanceObj->getUnappliedNegative();

$assPosOutSta = $customerPerformanceObj->getOutstandingPositive();
$assNegOutSta = $customerPerformanceObj->getOutstandingNegative();

$assPosOutStaDue = $customerPerformanceObj->getOutstandingDuePositive();
$assNegOutStaDue = $customerPerformanceObj->getOutstandingDueNegative();


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
                url: 'customerPerformance.php',
                type: "GET",
                data: $("#formInq").serialize()+'&action=dispPerCustomer',
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
            $("#txtCus").autocomplete({
                source: "customerPerformance.php?action=searchCustomer",
                minLength: 1,
                select: function(event, ui) {    
                    var content = ui.item.id.split("|");
                    $("#hdnCusCode").val(content[0]);
                    $("#txtRep").val(content[1]);
                }
            }); 
        }); 
        
        function aging(){
        
            $.ajax({
                url: 'customerPerformance.php',
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
        
        function agingPerCustomer(){
        
            $.ajax({
                url: 'customerPerformance.php',
                type: "GET",
                data: $("#formInq").serialize()+'&action=dispAgingPerCustomer',
                beforeSend: function() {
                    jQuery('#activity_pane').showLoading();
                },
                success: function(Data){
                    jQuery('#activity_pane').hideLoading();
                    $(function() {
                        $("#divDialogAgingPerCustomer").html(Data);
                        $('#divDialogAgingPerCustomer').dialog({ 
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
        
        function agingPerCustomerCheckDetails(){
        
            $.ajax({
                url: 'customerPerformance.php',
                type: "GET",
                data: $("#formInq").serialize()+'&action=dispAgingPerCustomerCheckDetails',
                beforeSend: function() {
                    jQuery('#activity_pane').showLoading();
                },
                success: function(Data){
                    jQuery('#activity_pane').hideLoading();
                    $(function() {
                        $("#divDialogAgingPerCustomerCheckDetails").html(Data);
                        $('#divDialogAgingPerCustomerCheckDetails').dialog({ 
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
                        	<h4 align="center"><font style="font-family:Lucida Handwriting"> Customer Trial Balance </font></h4>
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
                            <td align="center" width="20%" bgcolor="lightgreen"><font style="font-size: 12;"><b> RECEIVABLES </b></font></td>
                            <td align="center" width="20%" bgcolor="lightgreen"><font style="font-size: 12;"><b> DEDUCTIBLES </b></font></td>
                            <td align="center" width="20%" bgcolor="lightgreen"><font style="font-size: 12;"><b> NET </b></font></td>
                            <td align="center" width="10%" bgcolor="lightgreen"><font style="font-size: 12;"><b> AGING </b></font></td>
                        </tr>
                        <tr>
                            <td align="left" width="30%"><font style="font-size: 12;"> Unapplied </td>
                            <td align="right" width="20%"><font style="font-size: 12;"><?php echo number_format($assUnappliedPos['amount_due_remaining'],2); ?></font></td>
                            <td align="right" width="20%"><font style="font-size: 12; color: red;"><?php echo number_format($assUnappliedNeg['amount_due_remaining'],2); ?></font></td>
                            <?php $totPaid = $assUnappliedPos['amount_due_remaining'] + $assUnappliedNeg['amount_due_remaining']; ?>
                            <td align="right" width="20%">
                                <?php if($totPaid < 0){ ?><font style="font-size: 12; color: red;"><?php }else{ ?><font style="font-size: 12;"><?php } ?>
                                <?php echo number_format($totPaid,2); ?>
                                </font>
                            </td>
                            <td bgcolor="black"></td>
                        </tr>
                        <tr>
                            <td align="left" width="30%"><font style="font-size: 12;"> Outstanding - Current </td>
                            <td align="right" width="20%"><font style="font-size: 12;"><?php echo number_format($assPosOutSta['amount_due_remaining'],2); ?></font></td>
                            <td align="right" width="20%"><font style="font-size: 12; color: red;"><?php echo number_format($assNegOutSta['amount_due_remaining'],2); ?></font></td>
                            <?php $totOutSta = $assPosOutSta['amount_due_remaining'] + $assNegOutSta['amount_due_remaining']; ?>
                            <td align="right" width="20%">
                                <?php if($totOutSta < 0){ ?><font style="font-size: 12; color: red;"><?php }else{ ?><font style="font-size: 12;"><?php } ?>
                                <?php echo number_format($totOutSta,2); ?>
                                </font>
                            </td>
                            <td rowspan="2" align="center"><input type="button" name="viewAging" class="btn btn-success" onClick="aging();" value="View"></td>
                        </tr>
                        <tr>
                            <td align="left" width="30%"><font style="font-size: 12;"> Outstanding - Due </b></font></td>
                            <td align="right" width="20%"><font style="font-size: 12;"><?php echo number_format($assPosOutStaDue['amount_due_remaining'],2); ?></font></td>
                            <td align="right" width="20%"><font style="font-size: 12; color: red;"><?php echo number_format($assNegOutStaDue['amount_due_remaining'],2); ?></font></td>
                            <?php $totOutStaDue = $assPosOutStaDue['amount_due_remaining'] + $assNegOutStaDue['amount_due_remaining']; ?>
                            <td align="right" width="20%">
                                <?php if($totOutStaDue < 0){ ?><font style="font-size: 12; color: red;"><?php }else{ ?><font style="font-size: 12;"><?php } ?>
                                <?php echo number_format($totOutStaDue,2); ?>
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
                     <input type="hidden" id="hdnCusCode" name="hdnCusCode" />
                     <table border=0 align="center" width="90%">
                        <tr>
                            <td width="10%"><font style="font-size: 12;">Customer Search: </font></td>
                            <td align="left" width="30%"><input type="text" name="txtCus" id="txtCus" class="textBox" style="height:30px; width: 400px;" onclick="(this.value='')"/></td>
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
            <div id="divDialogAgingPerCustomer" style="display:none;"></div>
            <div id="divDialogAgingPerCustomerCheckDetails" style="display:none;"></div>
			
        </body>
</html>