<?php
session_start();
include "../../../adodb/adodb.inc.php";
include("../../../includes/db.inc.php");
include("../../../includes/common.php");
include("manpowerBilObj.php");

$apObj = new manpowerBillObj();

switch($_GET['action']){
	
	case 'Print':
			echo "window.open('apPosted_xls.php?{$_SERVER['QUERY_STRING']}');";
	exit();
	
	break;
		
}

$arrOrgId = array('0'=>"All",'85'=>"PPCI",'87'=>"JR",'133'=>"PUREGOLD SUBIC");
$arrSource = array('0'=>"All",'85'=>"PPCI",'87'=>"JR",'133'=>"PUREGOLD SUBIC");
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
        
        <script type="text/javascript">
		
		function validate(dateStart,dateEnd) {
		
            var company = $('#cmbCompany').val();
            var sourceName = $('#cmbApSourceName').val();
            var invoicePrefix = $('#cmbPostedPrefix').val();   
            
            if(company == 0){
                $().toastmessage('showToast', {
                    text: 'Please select company!',
                    sticky: false,
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
            if(sourceName == 0){
                $().toastmessage('showToast', {
                    text: 'Please select source!',
                    sticky: false,
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
			
			printXls(); 
		}
        
        function printXls(){
        
            var dateStart = $('#txtDateFrom').val();
            var dateEnd = $('#txtDateTo').val();
            
            if (valDateStartEnd(dateStart,dateEnd,'txtDateFrom','txtDateTo')){
                $.ajax({
                    url: 'apPosted.php',
                    type: "GET",
                    data: $("#formInq").serialize()+'&action=Print',
                    success: function(Data){
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
        
        function getXMLHTTP() { //fuction to return the xml http object
            var xmlhttp=false;    
            try{
                xmlhttp=new XMLHttpRequest();
            }
            catch(e)    {        
                try{            
                    xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
                }
                catch(e){
                    try{
                    xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
                    }
                    catch(e1){
                        xmlhttp=false;
                    }
                }
            }
                 
            return xmlhttp;
        }



        function getInvoicePrefix(strURL) {        
            
            var req = getXMLHTTP();
            
            if (req) {
                
                req.onreadystatechange = function() {
                    if (req.readyState == 4) {
                        // only if "OK"
                        if (req.status == 200) {                        
                            document.getElementById('divPostedPrefix').innerHTML=req.responseText;                        
                        } else {
                            alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                        }
                    }                
                }            
                req.open("GET", strURL, true);
                req.send(null);
            }
                    
        }
		
		</script>
        
        <style type="text/css">
		.selectBox {
			width:205px;
		}
		
		.selectBox2 {
			width:495px;
		}
        
        .sourceName {
            width:205px;
        }
        
        .cmbCompany {
            width:205px;
        }
        
        .dummyPostedPrefix {
            width:205px;
        }
        
        .postedPrefix {
            width:205px;
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
		-->	
		</style>   
    </head>
    	<body>
			<div id="activity_pane">
			<br />
            <form name="formInq" id="formInq">
                <div class="dvContainer">
                	<table border=0 align="center">
						<th colspan="6">
                        	<h4 align="center"><font style="font-family:Lucida Handwriting"> Ap Posted Report</font></h4>
                        </th>
						<tr>
							<td colspan = "6"> </td>
						</tr>
                        <tr>
                            <td><font style="font-size:13px"> Company </font></td>
                            <td><font style="font-size:13px"> : </font></td>
                            <td><? $apObj->DropDownMenu($apObj->makeArr($apObj->getCompany(),'org_id','comp_name','Select'),'cmbCompany','','class="cmbCompany ui-widget-content ui-corner-all"'); ?></td>
                        </tr>
                        <tr>
                            <td colspan = "6"> </td>
                        </tr>    
                        <tr>
                            <td><font style="font-size:13px"> Source </font></td>
                            <td><font style="font-size:13px"> : </font></td>
                            <td colspan = "2">
                            <? $invoice_source_name = $apObj->getInvoiceSource(); ?>
                            <select name="cmbApSourceName" id="cmbApSourceName" class="sourceName ui-widget-content ui-corner-all" onChange="getInvoicePrefix('apPostedPrefix.php?invPrefix='+this.value)">   
                                <option value="0">SELECT</option>  
                                <? foreach($invoice_source_name as $row) { ?>
                                <option value="<?=$row['source_id']?>"><?=$row['source_name']?></option>
                                <? } ?>
                            </select>
                            </td>
                        </tr>    
                        <tr>
                            <td colspan = "6"> </td>
                        </tr>
                        <tr>
                            <td><font style="font-size:13px"> Invoice Prefix </font></td>
                            <td><font style="font-size:13px"> : </font></td>
                            <td colspan = "2">
                                <div id="divPostedPrefix">
                                    <select name="dummyCmbPostedPrefix" class="dummyPostedPrefix ui-widget-content ui-corner-all">     
                                        <option value="0"></option>  
                                    </select>  
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan = "6"> </td>
                        </tr>
						<tr>
							<!-- <td><font style="font-size:13px"> Trx Date From </font></td> -->
							<td><font style="font-size:13px"> Invoice Date </font></td>
							<td><font style="font-size:13px"> : </font></td>
							<td>
                                <input type="text" name="txtDateFrom" id="txtDateFrom" readonly="readonly" class="textBox" style="height:30px;" placeholder="From"/>
						        <font style="font-size:13px"> - </font>
                                <input type="text" name="txtDateTo" id="txtDateTo" readonly="readonly" class="textBox" style="height:30px;"  placeholder="To"/>
                            </td>
						</tr>
						<tr>
							<td colspan = "6"> </td>
						</tr>
                        <tr>
                        	<td colspan="6" align="center">
                            	<input type="button" name="submit" class="btn btn-success" onClick="validate();" value="Export to Excel">
                            </td>
                        </tr>
                     </table>
                </div>
            </form>	
            
            <!--<img src="../../../includes/images/Spinner.gif">-->
  
			</div>
            <div style="clear:both;"></div>
			
        </body>
</html>