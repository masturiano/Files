<?php
session_start();
include "../../../adodb/adodb.inc.php";
include("../../../includes/db.inc.php");
include("../../../includes/common.php");
include("manpowerBilObj.php");

$manpowerBilObj = new manpowerBilObj();

$arrSuppList = $manpowerBilObj->viewSupplier();

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
	
	case 'Print':
    
        ini_set('memory_limit', '2048M');
			//echo "window.open('manpowerBil_xls.php?{$_SERVER['QUERY_STRING']}');";
            //EXECUTE master.dbo.xp_cmdshell  'bcp "SELECT * from PGBIS..DIM_PROD_CAT" queryout C:\TEST.csv -tº -c    -Usa -Psa -Svash'
        $manpowerBilObj->manpowerBillingCsv();  
        
        
        /*
        $arrManpowerBilling = $manpowerBilObj->manpowerBillingCsv();
        $assServerDate = $manpowerBilObj->serverDate();

        $serverDate = $assServerDate['CurrentDateTime'];            

        $serverCurrentDate = date("mdY", strtotime("$serverDate", time()));

        $fileExt = ".CSV";
        $filename = "MANPOWER_".$serverCurrentDate.$fileExt;
        
        $file_desti = "EXPORTEDFILE/"; // File destination

        if (file_exists($file_desti.$filename)) {
            unlink($file_desti.$filename);
        }
        $strlength = strlen($arrManpowerBilling); //gets the length of our $content string.
        $xcontentx = "";
        
        $company = array('85'=>'PPCI','87'=>'JR','133'=>'SUBIC');
        
        if($strlength > 0){
            
            $xcontentx .= trim('"INVOICE ID","VENDOR NO","VENDOR NAME","ORG ID","VENDOR SITE CODE","INVOICE NUM","INVOICE DATE","INVOICE AMOUNT","AMOUNT REMAINING","SOURCE","MATCH STATUS FLAG","CREATION DATE","BANK ACCOUNT NAME","CHECK NUMBER","CHECK SIGNED DATE","CHECK RELEASED DATE","DESCRIPTION"');
            $xcontentx .= "\r\n";
            
            foreach ($arrManpowerBilling as $valD) {
                
                $xcontentx .= trim($valD['INVOICE_ID']).",";
                $xcontentx .= '"'.trim($valD['SEGMENT1']).'",';
                $xcontentx .= '"'.trim($valD['VENDOR_NAME']).'",';
                $xcontentx .= '"'.trim($company[$valD['ORG_ID']]).'",';
                $xcontentx .= '"'.trim($valD['VENDOR_SITE_CODE']).'",';
                $xcontentx .= '"'.trim($valD['INVOICE_NUM']).'",';
                $invoiceDate = (date('m/d/Y',strtotime($valD['INVOICE_DATE']))=='01/01/1970') ? '':date('m/d/Y',strtotime($valD['INVOICE_DATE']));
                $xcontentx .= trim($invoiceDate).',';
                $xcontentx .= trim($valD['INVOICE_AMOUNT']).",";
                $xcontentx .= trim($valD['AMOUNT_REMAINING']).",";
                $xcontentx .= '"'.trim($valD['SOURCE']).'",';
                $xcontentx .= '"'.trim($valD['MATCH_STATUS_FLAG']).'",';
                $creationDate = (date('m/d/Y',strtotime($valD['CREATION_DATE']))=='01/01/1970') ? '':date('m/d/Y',strtotime($valD['CREATION_DATE']));
                $xcontentx .= trim($creationDate).",";
                $xcontentx .= '"'.trim($valD['BANK_ACCOUNT_NAME']).'",';
                $xcontentx .= trim($valD['CHECK_NUMBER']).",";
                $checkSigDate = (date('m/d/Y',strtotime($valD['CHECK_SIGNED_DATE']))=='01/01/1970') ? '':date('m/d/Y',strtotime($valD['CHECK_SIGNED_DATE']));
                $xcontentx .= trim($checkSigDate).",";
                $checkRelDate = (date('m/d/Y',strtotime($valD['CHECK_RELEASED_DATE']))=='01/01/1970') ? '':date('m/d/Y',strtotime($valD['CHECK_RELEASED_DATE']));
                $xcontentx .= trim($checkRelDate).",";
                $xcontentx .= '"'.trim($valD['DESCRIPTION']).'"';
                $xcontentx .= "\r\n";
            }
            $create = fopen($file_desti.$filename, "x"); //uses fopen to create our file.
            fwrite($create, $xcontentx);
            fclose($create);
            
            echo "
            $().toastmessage('showToast', {
            text     : '<b>Filename: </b> ".$filename." successfully create! <br>"
            .'<font color="#00FF00"><b>File Path:</b></font> \192.168.200.210\manpower_csv\ '."',
            sticky   : true,
            position : 'middle-center',
            type     : 'success',
            close    : function () {console.log('toast is closed ...');}
            });
            ";
        }
        else{
            echo "
            $().toastmessage('showToast', {
            text     : '<b>Manpower CSV Creation Failed! <br>',
            sticky   : true,
            position : 'middle-center',
            type     : 'error',
            close    : function () {console.log('toast is closed ...');}
            });
            ";
            return true;
        }
        
        */   
            
	exit();
	
	break;
		
}

$arrOrgId = array('0'=>"All",'85'=>"PPCI",'87'=>"JR",'133'=>"PUREGOLD SUBIC");
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
        
        <script src="../../../includes/jquery/js/jquery.dataTables.min.js"></script>   
        <script src="../../../includes/jquery/js/dataTables.bootstrap.js"></script>   
        <style type="text/css" title="currentStyle">
            @import "../../../includes/jquery/css/jquery.dataTables_themeroller.css";
        </style>
        
        <script type="text/javascript">
		
		function printXls(){
		
			$.ajax({
                url: 'manpowerBil.php',
                type: "GET",
                data: $("#formInq").serialize()+'&action=Print',
                beforeSend: function() {
                    jQuery('#activity_pane').showLoading();
                },
                success: function(Data){
                    jQuery('#activity_pane').hideLoading();
                    eval(Data);
                    $().toastmessage('showToast', {
                        text: 'Exported to Excel!',
                        sticky: false,
                        position: 'middle-center',
                        type: 'success',
                        closeText: '',
                        close: function () 
                        {
                        console.log("toast is closed ...");
                        }
                    });
                    
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
        
        $('document').ready(function(){
            $('#supplierList').dataTable({
                "sPaginationType": "full_numbers"
            })  
        });
		
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
        
        table#supplierList {
            border: blue;
        }
        
        table#supplierList tr.even td {
        background-color: white;
        }

        table#supplierList tr.odd td {
            background-color: lightgreen;
        }
        
        table#supplierList tr.even:hover td {
            background-color: lightgray;
        }

        table#supplierList tr.odd:hover td {
            background-color: lightgray;
        }
        
        #ndtSuppliersBorder{
            width:70%
        }
		-->	
		</style>   
    </head>
    	<body>
			<div id="activity_pane">
			<br />
            <form name="formInq" id="formInq">
                <div class="dvContainer" align="center">
                	<table border=0 align="center">
						<th colspan="6">
                        	<h4 align="center"><font style="font-family:Lucida Handwriting"> Manpower Billing </font></h4>
                        </th>
						<tr>
							<td colspan = "6"> </td>
						</tr>
                        <tr>
                        	<td colspan="6" align="center">
                            	<input type="button" name="submit" class="btn btn-success" onClick="printXls();" value="Export to CSV">
                            </td>
                        </tr>
                     </table>
                     <br/>
                     <div id="ndtSuppliersBorder">
                        <div align="center">
                         <table id="supplierList" align="center">
                            <thead>
                                <tr>
                                    <td align="center"><b>SUPPLIER NUMBER</b></td>
                                    <td align="center"><b>SUPPLIER NAME</b></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($arrSuppList as $val) {?>
                                <tr>
                                    <td align="left"><?=$val['supplier_no']?></td>
                                    <td align="left"><?=$val['asname']?></td>
                                </tr>
                                <?php }?>
                            </tbody>
                            <tfoot></tfoot>
                        </table>
                        </div>
                     </div>
                </div>
            </form>	
            
            <!--<img src="../../../includes/images/Spinner.gif">-->
  
			</div>
            <div style="clear:both;"></div>
			
        </body>
</html>