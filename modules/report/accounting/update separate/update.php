<?php
include "../../../adodb/adodb.inc.php";
include("../../../includes/db.inc.php");
include("../../../includes/common.php");
include("updateObj.php");

$updateObj = new updateObj();

switch($_POST['action']){
	
	case "updatePaid":
		
		if($updateObj->cleartblVenPerPaid()){
			if($updateObj->updatetblVenPerPaid()){
                $updateObj->updatetblVenPerDatePaid();    
            }  
		}
		
		exit();
	break;
    
    case "updateOutstanding":
        
        if($updateObj->cleartblVenPerOutstanding()){
            if($updateObj->updatetblVenPerOutstanding()){
                $updateObj->updatetblVenPerDateOutStand();    
            }  
        }
        
        exit();
    break;
    
    case "updateOutstandingDue":
        
        if($updateObj->cleartblVenPerOutstandingDue()){
            if($updateObj->updatetblVenPerOutstandingDue()){
                $updateObj->updatetblVenPerDateOutStandDue();    
            }  
        }
        
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
		

		function updatePaid(){

			$.ajax({
				url: 'update.php',
				type: 'POST',
				data: 'action=updatePaid',
				beforeSend: function() {
					jQuery('#activity_pane').showLoading();
				},
				success: function(data){
					jQuery('#activity_pane').hideLoading();
						$().toastmessage('showToast', {
						text: 'Success!',
						sticky: true,
						position: 'middle-center',
						type: 'success',
						closeText: '',
						close: function () {
							console.log("toast is closed ...");
						}
					});
				}
			});
			
		}
        
        function updateOutstanding(){

            $.ajax({
                url: 'update.php',
                type: 'POST',
                data: 'action=updateOutstanding',
                beforeSend: function() {
                    jQuery('#activity_pane').showLoading();
                },
                success: function(data){
                    jQuery('#activity_pane').hideLoading();
                        $().toastmessage('showToast', {
                        text: 'Success!',
                        sticky: true,
                        position: 'middle-center',
                        type: 'success',
                        closeText: '',
                        close: function () {
                            console.log("toast is closed ...");
                        }
                    });
                }
            });
            
        }
        
        function updateOutstandingDue(){

            $.ajax({
                url: 'update.php',
                type: 'POST',
                data: 'action=updateOutstandingDue',
                beforeSend: function() {
                    jQuery('#activity_pane').showLoading();
                },
                success: function(data){
                    jQuery('#activity_pane').hideLoading();
                        $().toastmessage('showToast', {
                        text: 'Success!',
                        sticky: true,
                        position: 'middle-center',
                        type: 'success',
                        closeText: '',
                        close: function () {
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
                	<table border=0 align="center" width="90%">  
						<th colspan="2"><h4 align="center"><font style="font-family:Lucida Handwriting"> Update </font></h4></th>
                     </table>
                     
                     <br>
                     
                     <table border=1 align="center" width="90%">  
                        <tr>
                            <td align="center" width="40%" bgcolor="lightgreen"><font style="font-size: 12;"><b> TYPE </b></font></td>
                            <td align="center" width="40%" bgcolor="lightgreen"><font style="font-size: 12;"><b> ACTION </b></font></td>
                        </tr>
                        <tr>
                            <td align="left" width="40%"><font style="font-size: 12;"> Paid </font></td>
                            <td align="center">
                                <input type="button" name="submit" class="btn btn-success" onClick="updatePaid();" value="UPDATE">
                            </td>
                        </tr>
                        <tr>
                            <td align="left" width="40%"><font style="font-size: 12;"> Outstanding </font></td>
                            <td align="center">
                                <input type="button" name="submit" class="btn btn-success" onClick="updateOutstanding();" value="UPDATE">
                            </td>
                        </tr>
                        <tr>
                            <td align="left" width="40%"><font style="font-size: 12;"> Outstanding Due </font></td>
                            <td align="center">
                                <input type="button" name="submit" class="btn btn-success" onClick="updateOutstandingDue();" value="UPDATE">
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