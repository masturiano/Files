<?php
include "../../../adodb/adodb.inc.php";
include("../../../includes/db.inc.php");
include("../../../includes/common.php");
include("progressBillObj.php");

$updateObj = new progressBillObj();

switch($_POST['action']){
	
	case "processImportInvoiceId":
		
		$updateObj->importInvoiceId();
        
        $timeH = (int)date('H'); 
        $timeM = (int)date('i'); 
        $timeS = (int)date('s'); 
        echo "$('#timeEndImportInvoiceId').html('".$timeH.":".$timeM.":".$timeS."'+'<br/>').css('color','blue');";
		
		exit();
	break;    
    
    case "processImportInvoice":
        
        $updateObj->importInvoice();
        
        $timeH = (int)date('H'); 
        $timeM = (int)date('i'); 
        $timeS = (int)date('s'); 
        echo "$('#timeEndImportInvoice').html('".$timeH.":".$timeM.":".$timeS."'+'<br/>').css('color','blue');";
        
        exit();
    break;
    
    case "processImportPay":
        
        $updateObj->importPay();
        
        $timeH = (int)date('H'); 
        $timeM = (int)date('i'); 
        $timeS = (int)date('s');  
        echo "$('#timeEndImportPay').html('".$timeH.":".$timeM.":".$timeS."'+'<br/>').css('color','blue');";
        
        exit();
    break;
    
    case "processImportMinorCode":
        
        $updateObj->importMinorcode();
        
        $timeH = (int)date('H'); 
        $timeM = (int)date('i'); 
        $timeS = (int)date('s');  
        echo "$('#timeEndImportMinorCode').html('".$timeH.":".$timeM.":".$timeS."'+'<br/>').css('color','blue');";
        
        exit();
    break;
    
    case "exportInvoiceXls":
        
        echo "window.open('progressBillInvoiceXls.php?{$_SERVER['QUERY_STRING']}');";
        
        exit();
    break;    
    
    case "exportPayXls":
        
        echo "window.open('progressBillPayXls.php?{$_SERVER['QUERY_STRING']}');";
        
        exit();
    break;  
    
    case "exportLineXls":
        
        echo "window.open('progressBillLineXls.php?{$_SERVER['QUERY_STRING']}');";
        
        exit();
    break;  
    
    case "exportSummaryXls":
        
        echo "window.open('progressBillSummaryXls.php?{$_SERVER['QUERY_STRING']}');";
        
        exit();
    break;   
    
    
}
?>

<html>
	<head>
    	<!-- jQuery, Bootstrap -->
    	<link rel="stylesheet" href="../../../includes/bootstrap/css/bootstrap.css"/>
        <link rel="stylesheet" href="../../../includes/bootstrap/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="../../../includes/bootstrap/css/bootstrap-responsive.css"/>
        <link rel="stylesheet" href="../../../includes/bootstrap/css/bootstrap-responsive.min.css"/>
        <!-- jQuery, Bootstrap -->
        
        <script src="../../../includes/jquery/js/jquery-1.6.2.min.js"></script>
        <script src="../../../includes/jquery/js/jquery-ui-1.8.16.custom.min.js"></script>
        <script src="../../../includes/bootbox/bootbox.js"></script>
		
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
        
        <script type="text/javascript">
		

		function importInvoiceId(){

			$.ajax({
				url: 'progressBill.php',
				type: 'POST',
				data: 'action=processImportInvoiceId',
				beforeSend: function(data) {
					$('#btn_process').attr('disabled','disabled');
                    $('#btn_process').val('Processing');
                    $("#labelImportInvoiceId").html('Importing Invoice Id...');
                    $("#loaderImportInvoiceId").show(data);
                    
                    var d = new Date();
                    d.getHours();
                    d.getMinutes();
                    d.getSeconds();
                    $('#timeStartImportInvoiceId').html(d.getHours()+':'+d.getMinutes()+':'+d.getSeconds()+'<br/>').css('color','blue');
				},
				success: function(data){
                    $("#labelImportInvoiceId").html('Import Invoice Id');
                    $("#loaderImportInvoiceId").hide(data);  
                    eval(data); 
                    importInvoice();
				}
			});
			
		}
        
        function importInvoice(){

            $.ajax({
                url: 'progressBill.php',
                type: 'POST',
                data: 'action=processImportInvoice',
                beforeSend: function(data) {
                    $("#labelImportInvoice").html('Importing Invoice...');
                    $("#loaderImportInvoice").show(data);
                    
                    var d = new Date();
                    d.getHours();
                    d.getMinutes();
                    d.getSeconds();
                    $('#timeStartImportInvoice').html(d.getHours()+':'+d.getMinutes()+':'+d.getSeconds()+'<br/>').css('color','blue');
                },
                success: function(data){
                    $("#labelImportInvoice").html('Import Invoice');
                    $("#loaderImportInvoice").hide(data);  
                    eval(data);
                    importPay();       
                }
            });
            
        }
        
        function importPay(){

            $.ajax({
                url: 'progressBill.php',
                type: 'POST',
                data: 'action=processImportPay',
                beforeSend: function(data) {
                    $("#labelImportPay").html('Importing Pay...');
                    $("#loaderImportPay").show(data);
                    
                    var d = new Date();
                    d.getHours();
                    d.getMinutes();
                    d.getSeconds();
                    $('#timeStartImportPay').html(d.getHours()+':'+d.getMinutes()+':'+d.getSeconds()+'<br/>').css('color','blue');
                },
                success: function(data){
                    $("#labelImportPay").html('Import Pay');
                    $("#loaderImportPay").hide(data);  
                    eval(data);  
                    importMinorCode();    
                }
            });
            
        }
        
        function importMinorCode(){

            $.ajax({
                url: 'progressBill.php',
                type: 'POST',
                data: 'action=processImportMinorCode',
                beforeSend: function(data) {
                    $("#labelImportMinorCode").html('Importing Minor Code...');
                    $("#loaderImportMinorCode").show(data);
                    
                    var d = new Date();
                    d.getHours();
                    d.getMinutes();
                    d.getSeconds();
                    $('#timeStartImportMinorCode').html(d.getHours()+':'+d.getMinutes()+':'+d.getSeconds()+'<br/>').css('color','blue');
                },
                success: function(data){
                    $("#labelImportMinorCode").html('Import Minor Code');
                    $("#loaderImportMinorCode").hide(data);  
                    $('#btn_process').removeAttr('disabled');
                    $('#btn_process').val('Process'); 
                    eval(data); 
                    exportSummary();    
                }
            });
            
        }
        
        function exportSummary(){

            $.ajax({
                url: 'progressBill.php',
                type: 'POST',
                data: 'action=exportSummaryXls',
                success: function(data){
                    eval(data);
                    $().toastmessage('showToast', {
                        text: 'Merge exporting to excel!',
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
        
        function exportInvoice(){

            $.ajax({
                url: 'progressBill.php',
                type: 'POST',
                data: 'action=exportInvoiceXls',
                success: function(data){
                    eval(data);
                    $().toastmessage('showToast', {
                        text: 'Invoice exporting to excel!',
                        sticky: false,
                        position: 'middle-center',
                        type: 'success',
                        closeText: '',
                        close: function () 
                        {
                        console.log("toast is closed ...");
                        }
                    });
                    exportPay();
                }
            });
            
        }
        
        function exportPay(){

            $.ajax({
                url: 'progressBill.php',
                type: 'POST',
                data: 'action=exportPayXls',
                success: function(data){
                    eval(data);
                    $().toastmessage('showToast', {
                        text: 'Pay exporting to excel!',
                        sticky: false,
                        position: 'middle-center',
                        type: 'success',
                        closeText: '',
                        close: function () 
                        {
                        console.log("toast is closed ...");
                        }
                    });
                    exportLine();
                }
            });
            
        }
        
        function exportLine(){

            $.ajax({
                url: 'progressBill.php',
                type: 'POST',
                data: 'action=exportLineXls',
                success: function(data){
                    eval(data);
                    $().toastmessage('showToast', {
                        text: 'Line exporting to excel!',
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
        
        
        
		</script>
        
        <style type="text/css">
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
		-->	
		</style>   
    </head>
    	<body>  
			<div id="activity_pane">
			    <br />
                <form class="form-horizontal">
                    <div class="dvContainer">
                    
                	    <table border="0" align="center" width="90%">  
						    <tr colspan="2">   
                                <td>
                                    <h4 align="center">
                                        <font style="font-family:Lucida Handwriting"> Progress Bill </font>
                                    </h4>
                                </td>  
                            </tr>
                         </table>
                         
                         <br>
                         
                         <table border="0" align="center" width="90%">  
                            <tr>
                                <td align="center">
                                    <input type="button" name="btn_process" id="btn_process" class="btn btn-success" onClick="importInvoiceId();" value="PROCESS">
                                </td>
                            </tr>
                         </table>
                         
                         <br>
                         
                         <table border=1 align="center" width="90%">  
                            <tr>
                                <td align="center" width="40%" bgcolor="lightgreen"><font style="font-size: 12;"><b> PROCESS </b></font></td>
                                <td align="center" width="25%" bgcolor="lightgreen"><font style="font-size: 12;"><b> TIME START </b></font></td>
                                <td align="center" width="25%" bgcolor="lightgreen"><font style="font-size: 12;"><b> TIME END </b></font></td>
                                <td align="center" width="10%" bgcolor="lightgreen"><font style="font-size: 12;"><b> LOADER </b></font></td>
                            </tr>
                            
                            <tr>
                                <td align="left">
                                    <font style="font-size: 12;" id="labelImportInvoiceId"> Import Invoice Id </font>
                                </td>   
                                <td align="center"><font style="font-size: 12;">
                                    <div id="timeStartImportInvoiceId">
                                    </div>     
                                </td>
                                <td align="center"><font style="font-size: 12;">
                                    <div id="timeEndImportInvoiceId">
                                    </div>
                                </td>
                                <td align="center"><font style="font-size: 12;">
                                    <div id="loaderImportInvoiceId" style="display:none;">
                                        <img src="../../../includes/images/ajax-loader.gif">
                                    </div> 
                                </td>
                            </tr>
                            
                            <tr>
                                <td align="left">
                                    <font style="font-size: 12;" id="labelImportInvoice"> Import Invoice </font>
                                </td>
                                <td align="center"><font style="font-size: 12;">
                                    <div id="timeStartImportInvoice">
                                    </div>  
                                </td>
                                <td align="center"><font style="font-size: 12;">
                                    <div id="timeEndImportInvoice">
                                    </div>  
                                </td>
                                <td align="center"><font style="font-size: 12;">
                                    <div id="loaderImportInvoice" style="display:none;">
                                        <img src="../../../includes/images/ajax-loader.gif">
                                    </div> 
                                </td>
                            </tr>
                            
                            <tr>
                                <td align="left">
                                    <font style="font-size: 12;" id="labelImportPay"> Import Pay </font>
                                </td>
                                <td align="center"><font style="font-size: 12;">
                                    <div id="timeStartImportPay">
                                    </div> 
                                </td>
                                <td align="center"><font style="font-size: 12;">
                                    <div id="timeEndImportPay">
                                    </div> 
                                </td>
                                <td align="center"><font style="font-size: 12;">
                                    <div id="loaderImportPay" style="display:none;">
                                        <img src="../../../includes/images/ajax-loader.gif">
                                    </div> 
                                </td>
                            </tr>
                            
                            <tr>
                                <td align="left">
                                    <font style="font-size: 12;" id="labelImportMinorCode"> Import Minor Code </font>
                                </td>
                                <td align="center"><font style="font-size: 12;">
                                    <div id="timeStartImportMinorCode">
                                    </div> 
                                </td>
                                <td align="center"><font style="font-size: 12;">
                                    <div id="timeEndImportMinorCode">
                                    </div> 
                                </td>
                                <td align="center"><font style="font-size: 12;">
                                    <div id="loaderImportMinorCode" style="display:none;">
                                        <img src="../../../includes/images/ajax-loader.gif">
                                    </div> 
                                </td>
                            </tr>
                         </table>
                         
                         <br/>
                         
                         <table border="0" align="center" width="90%">  
                            <tr>
                                <td align="center">
                                    <input type="button" name="btn_print" id="btn_print" class="btn btn-success" onClick="exportSummary();" value="PRINT EXCEL">
                                </td>
                            </tr>
                         </table>
                         
                    </div>
                </form>
            </div>     
			
            <div style="clear:both;">
            </div>              
			
        </body>
</html>