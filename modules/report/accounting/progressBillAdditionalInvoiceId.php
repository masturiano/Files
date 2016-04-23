<?php
include "../../../adodb/adodb.inc.php";
include("../../../includes/db.inc.php");
include("../../../includes/common.php");
include("progressBillObj.php");

$updateObj = new progressBillObj();

switch($_POST['action']){
	
	case "processImportAddInvoiceId":
		
		$updateObj->importAdditionalInvoiceId();
        
        $timeH = (int)date('H'); 
        $timeM = (int)date('i'); 
        $timeS = (int)date('s'); 
        echo "$('#timeEndImportInvoiceId').html('".$timeH.":".$timeM.":".$timeS."'+'<br/>').css('color','blue');";
		
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
				url: 'progressBillAdditionalInvoiceId.php',
				type: 'POST',
				data: 'action=processImportAddInvoiceId',
				beforeSend: function(data) {
					$('#btn_process').attr('disabled','disabled');
                    $('#btn_process').val('Processing');
                    $("#labelImportInvoiceId").html('Importing Additional Invoice Id...');
                    $("#loaderImportInvoiceId").show(data);
                    
                    var d = new Date();
                    d.getHours();
                    d.getMinutes();
                    d.getSeconds();
                    $('#timeStartImportInvoiceId').html(d.getHours()+':'+d.getMinutes()+':'+d.getSeconds()+'<br/>').css('color','blue');
				},
				success: function(data){
                    $("#labelImportInvoiceId").html('Import Additional Invoice Id');
                    $("#loaderImportInvoiceId").hide(data);  
                    $('#btn_process').removeAttr('disabled');
                    $('#btn_process').val('Process'); 
                    eval(data); 
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
                                        <font style="font-family:Lucida Handwriting"> Progress Bill Import Additional Invoice Id </font>
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
                                    <font style="font-size: 12;" id="labelImportInvoiceId"> Import Additional Invoice Id </font>
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
                         </table>
                         
                    </div>
                </form>
            </div>     
			
            <div style="clear:both;">
            </div>              
			
        </body>
</html>