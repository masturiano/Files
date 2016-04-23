<?php
include "../../../adodb/adodb.inc.php";
include("../../../includes/db.inc.php");
include("../../../includes/common.php");
include("updateObj.php");

$updateObj = new updateObj();

switch($_POST['action']){
	
	case "updateAp":
		
		if($updateObj->cleartblVenPerPaid()){
            echo "
            $().toastmessage('showToast', {
            text     : '<b>cleartblVenPerPaid success!</b><br>',
            sticky   : false,
            position : 'middle-center',
            type     : 'success',
            close    : function () {console.log('toast is closed ...');}
            });
            ";
            if($updateObj->updatetblVenPerPaid()){
                echo "
                $().toastmessage('showToast', {
                text     : '<b>updatetblVenPerPaid success!</b><br>',
                sticky   : false,
                position : 'middle-center',
                type     : 'success',
                close    : function () {console.log('toast is closed ...');}
                });
                ";
                if($updateObj->cleartblVenPerOutstanding()){
                    echo "
                    $().toastmessage('showToast', {
                    text     : '<b>cleartblVenPerOutstanding success!</b><br>',
                    sticky   : false,
                    position : 'middle-center',
                    type     : 'success',
                    close    : function () {console.log('toast is closed ...');}
                    });
                    ";
                    if($updateObj->updatetblVenPerOutstanding()){ 
                        echo "
                        $().toastmessage('showToast', {
                        text     : '<b>updatetblVenPerOutstanding success!</b><br>',
                        sticky   : false,
                        position : 'middle-center',
                        type     : 'success',
                        close    : function () {console.log('toast is closed ...');}
                        });
                        ";
                        if($updateObj->cleartblVenPerOutstandingDue()){
                            echo "
                            $().toastmessage('showToast', {
                            text     : '<b>cleartblVenPerOutstandingDue success!</b><br>',
                            sticky   : false,
                            position : 'middle-center',
                            type     : 'success',
                            close    : function () {console.log('toast is closed ...');}
                            });
                            ";
                            if($updateObj->updatetblVenPerOutstandingDue()){
                                echo "
                                $().toastmessage('showToast', {
                                text     : '<b>updatetblVenPerOutstandingDue success!</b><br>',
                                sticky   : false,
                                position : 'middle-center',
                                type     : 'success',
                                close    : function () {console.log('toast is closed ...');}
                                });
                                ";
                                if($updateObj->updateBucketNumFieldTblVenPerOutstanding()){
                                    echo "
                                    $().toastmessage('showToast', {
                                    text     : '<b>updateBucketNumFieldTblVenPerOutstanding success!</b><br>',
                                    sticky   : false,
                                    position : 'middle-center',
                                    type     : 'success',
                                    close    : function () {console.log('toast is closed ...');}
                                    });
                                    ";
                                    if($updateObj->updateBucketNamFieldTblVenPerOutstanding()){
                                        echo "
                                        $().toastmessage('showToast', {
                                        text     : '<b>updateBucketNamFieldTblVenPerOutstanding success!</b><br>',
                                        sticky   : false,
                                        position : 'middle-center',
                                        type     : 'success',
                                        close    : function () {console.log('toast is closed ...');}
                                        });
                                        ";
                                        if($updateObj->updateBucketNumFieldTblVenPerOutstandingDueWrongTermsDate()){
                                            echo "
                                            $().toastmessage('showToast', {
                                            text     : '<b>updateBucketNumFieldTblVenPerOutstandingDueWrongTermsDate success!</b><br>',
                                            sticky   : false,
                                            position : 'middle-center',
                                            type     : 'success',
                                            close    : function () {console.log('toast is closed ...');}
                                            });
                                            ";    
                                            if($updateObj->updateBucketNumFieldTblVenPerOutstandingDue()){
                                                echo "
                                                $().toastmessage('showToast', {
                                                text     : '<b>updateBucketNumFieldTblVenPerOutstandingDue success!</b><br>',
                                                sticky   : false,
                                                position : 'middle-center',
                                                type     : 'success',
                                                close    : function () {console.log('toast is closed ...');}
                                                });
                                                ";
                                                if($updateObj->updateBucketNamFieldTblVenPerOutstandingDue()){
                                                    echo "
                                                    $().toastmessage('showToast', {
                                                    text     : '<b>updateBucketNumFieldTblVenPerOutstandingDue success!</b><br>',
                                                    sticky   : false,
                                                    position : 'middle-center',
                                                    type     : 'success',
                                                    close    : function () {console.log('toast is closed ...');}
                                                    });
                                                    ";
                                                    $updateObj->updatetblVenPerDate();
                                                } 
                                                else{
                                                    echo "
                                                    $().toastmessage('showToast', {
                                                    text     : '<b>Error updateBucketNamFieldTblVenPerOutstandingDue!</b><br>',
                                                    sticky   : true,
                                                    position : 'middle-center',
                                                    type     : 'error',
                                                    close    : function () {console.log('toast is closed ...');}
                                                    });
                                                    ";    
                                                }
                                            } 
                                            else{
                                                echo "
                                                $().toastmessage('showToast', {
                                                text     : '<b>Error updateBucketNumFieldTblVenPerOutstanding!</b><br>',
                                                sticky   : true,
                                                position : 'middle-center',
                                                type     : 'error',
                                                close    : function () {console.log('toast is closed ...');}
                                                });
                                                ";
                                            }        
                                        }
                                        else{
                                            echo "
                                            $().toastmessage('showToast', {
                                            text     : '<b>Error updateBucketNumFieldTblVenPerOutstandingDueWrongTermsDate!</b><br>',
                                            sticky   : true,
                                            position : 'middle-center',
                                            type     : 'error',
                                            close    : function () {console.log('toast is closed ...');}
                                            });
                                            ";       
                                        }   
                                    } 
                                    else{
                                        echo "
                                        $().toastmessage('showToast', {
                                        text     : '<b>Error updateBucketNumFieldTblVenPerOutstanding!</b><br>',
                                        sticky   : true,
                                        position : 'middle-center',
                                        type     : 'error',
                                        close    : function () {console.log('toast is closed ...');}
                                        });
                                        ";
                                    }   
                                }
                                else{
                                    echo "
                                    $().toastmessage('showToast', {
                                    text     : '<b>Error updateBucketNumFieldTblVenPerOutstanding!</b><br>',
                                    sticky   : true,
                                    position : 'middle-center',
                                    type     : 'error',
                                    close    : function () {console.log('toast is closed ...');}
                                    });
                                    ";
                                }    
                            } 
                            else{
                                echo "
                                $().toastmessage('showToast', {
                                text     : '<b>Error updatetblVenPerOutstandingDue!</b><br>',
                                sticky   : true,
                                position : 'middle-center',
                                type     : 'error',
                                close    : function () {console.log('toast is closed ...');}
                                });
                                ";
                            } 
                        }
                        else{
                            echo "
                            $().toastmessage('showToast', {
                            text     : '<b>Error cleartblVenPerOutstandingDue!</b><br>',
                            sticky   : true,
                            position : 'middle-center',
                            type     : 'error',
                            close    : function () {console.log('toast is closed ...');}
                            });
                            ";
                        }
                    }
                    else{
                        echo "
                        $().toastmessage('showToast', {
                        text     : '<b>Error updatetblVenPerOutstanding!</b><br>',
                        sticky   : true,
                        position : 'middle-center',
                        type     : 'error',
                        close    : function () {console.log('toast is closed ...');}
                        });
                        ";
                    }
                }
                else{
                    echo "
                    $().toastmessage('showToast', {
                    text     : '<b>Error cleartblVenPerOutstanding!</b><br>',
                    sticky   : true,
                    position : 'middle-center',
                    type     : 'error',
                    close    : function () {console.log('toast is closed ...');}
                    });
                    ";
                }           
            } 
            else{
                echo "
                $().toastmessage('showToast', {
                text     : '<b>Error updatetblVenPerPaid!</b><br>',
                sticky   : true,
                position : 'middle-center',
                type     : 'error',
                close    : function () {console.log('toast is closed ...');}
                });
                ";
            } 
        }
        else{
            echo "
            $().toastmessage('showToast', {
            text     : '<b>Error cleartblVenPerPaid!</b><br>',
            sticky   : true,
            position : 'middle-center',
            type     : 'error',
            close    : function () {console.log('toast is closed ...');}
            });
            ";
        }
        
        /*
        if($updateObj->cleartblVenPerPaid()){
            if($updateObj->updatetblVenPerPaid()){
                if($updateObj->cleartblVenPerOutstanding()){
                    if($updateObj->updatetblVenPerOutstanding()){ 
                        if($updateObj->cleartblVenPerOutstandingDue()){
                            if($updateObj->updatetblVenPerOutstandingDue()){
                                if($updateObj->updateBucketNumFieldTblVenPerOutstanding()){
                                    if($updateObj->updateBucketNamFieldTblVenPerOutstanding()){
                                        if($updateObj->updateBucketNumFieldTblVenPerOutstandingDue()){
                                            if($updateObj->updateBucketNamFieldTblVenPerOutstandingDue()){
                                                $updateObj->updatetblVenPerDate();
                                            } 
                                        } 
                                    }    
                                }    
                            }  
                        }
                    }
                }           
            }  
        }
        */
		
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
    
    case "updateAr":
        
        if($updateObj->cleartblArPayment()){
            echo "
            $().toastmessage('showToast', {
            text     : '<b>Clear Table tblArPayment success!</b><br>',
            sticky   : false,
            position : 'middle-center',
            type     : 'success',
            close    : function () {console.log('toast is closed ...');}
            });
            ";
            if($updateObj->updatetblArPayment()){
                echo "
                $().toastmessage('showToast', {
                text     : '<b>Update Table tblArPayment success!</b><br>',
                sticky   : false,
                position : 'middle-center',
                type     : 'success',
                close    : function () {console.log('toast is closed ...');}
                });
                ";
                if($updateObj->cleartblArTransaction()){
                    echo "
                    $().toastmessage('showToast', {
                    text     : '<b>Clear Table tblArTransaction success!</b><br>',
                    sticky   : false,
                    position : 'middle-center',
                    type     : 'success',
                    close    : function () {console.log('toast is closed ...');}
                    });
                    ";
                    if($updateObj->updatetblArTransaction()){
                        echo "
                        $().toastmessage('showToast', {
                        text     : '<b>Update Table tblArTransaction success!</b><br>',
                        sticky   : false,
                        position : 'middle-center',
                        type     : 'success',
                        close    : function () {console.log('toast is closed ...');}
                        });
                        ";
                        if($updateObj->cleartblArTransactionDue()){
                            echo "
                            $().toastmessage('showToast', {
                            text     : '<b>Clear Table tblArTransactionDue success!</b><br>',
                            sticky   : false,
                            position : 'middle-center',
                            type     : 'success',
                            close    : function () {console.log('toast is closed ...');}
                            });
                            ";
                             if($updateObj->updatetblArTransactionDue()){
                                echo "
                                $().toastmessage('showToast', {
                                text     : '<b>Update Table tblArTransactionDue success!</b><br>',
                                sticky   : false,
                                position : 'middle-center',
                                type     : 'success',
                                close    : function () {console.log('toast is closed ...');}
                                });
                                ";
                                 if($updateObj->updatetblArTransactionDueWrongTransactionDate()){
                                    echo "
                                    $().toastmessage('showToast', {
                                    text     : '<b>Update Table tblArTransactionDueWrongTransactionDate success!</b><br>',
                                    sticky   : false,
                                    position : 'middle-center',
                                    type     : 'success',
                                    close    : function () {console.log('toast is closed ...');}
                                    });
                                    ";
                                    if($updateObj->updateBucketNumFieldTblArTransaction()){
                                        echo "
                                        $().toastmessage('showToast', {
                                        text     : '<b>Update BucketNumFieldTblArTransaction success!</b><br>',
                                        sticky   : false,
                                        position : 'middle-center',
                                        type     : 'success',
                                        close    : function () {console.log('toast is closed ...');}
                                        });
                                        ";
                                        if($updateObj->updateBucketNamFieldTblArTransaction()){
                                            echo "
                                            $().toastmessage('showToast', {
                                            text     : '<b>Update BucketNumFieldTblArTransaction success!</b><br>',
                                            sticky   : false,
                                            position : 'middle-center',
                                            type     : 'success',
                                            close    : function () {console.log('toast is closed ...');}
                                            });
                                            ";
                                            if($updateObj->updateBucketNumFieldTblArTransactionDue()){
                                                echo "
                                                $().toastmessage('showToast', {
                                                text     : '<b>Update BucketNumFieldTblArTransactionDue( success!</b><br>',
                                                sticky   : false,
                                                position : 'middle-center',
                                                type     : 'success',
                                                close    : function () {console.log('toast is closed ...');}
                                                });
                                                ";
                                                if($updateObj->updateBucketNamFieldTblArTransactionDue()){
                                                    echo "
                                                    $().toastmessage('showToast', {
                                                    text     : '<b>Update BucketNamFieldTblArTransactionDue( success!</b><br>',
                                                    sticky   : false,
                                                    position : 'middle-center',
                                                    type     : 'success',
                                                    close    : function () {console.log('toast is closed ...');}
                                                    });
                                                    ";
                                                    return $updateObj->updatetblCusPerDate();    
                                                }else{
                                                    echo "
                                                    $().toastmessage('showToast', {
                                                    text     : '<b>Error update BucketNamFieldTblArTransactionDue!</b><br>',
                                                    sticky   : true,
                                                    position : 'middle-center',
                                                    type     : 'error',
                                                    close    : function () {console.log('toast is closed ...');}
                                                    });
                                                    ";     
                                                }     
                                            }else{
                                                echo "
                                                $().toastmessage('showToast', {
                                                text     : '<b>Error update BucketNumFieldTblArTransactionDue!</b><br>',
                                                sticky   : true,
                                                position : 'middle-center',
                                                type     : 'error',
                                                close    : function () {console.log('toast is closed ...');}
                                                });
                                                ";     
                                            }  
                                        }else{
                                            echo "
                                            $().toastmessage('showToast', {
                                            text     : '<b>Error update BucketNamFieldTblArTransaction!</b><br>',
                                            sticky   : true,
                                            position : 'middle-center',
                                            type     : 'error',
                                            close    : function () {console.log('toast is closed ...');}
                                            });
                                            ";              
                                        }      
                                     }else{
                                        echo "
                                        $().toastmessage('showToast', {
                                        text     : '<b>Error update BucketNumFieldTblArTransaction!</b><br>',
                                        sticky   : true,
                                        position : 'middle-center',
                                        type     : 'error',
                                        close    : function () {console.log('toast is closed ...');}
                                        });
                                        ";         
                                     }     
                                 }else{
                                    echo "
                                    $().toastmessage('showToast', {
                                    text     : '<b>Error update tblArTransactionDueWrongTransactionDate!</b><br>',
                                    sticky   : true,
                                    position : 'middle-center',
                                    type     : 'error',
                                    close    : function () {console.log('toast is closed ...');}
                                    });
                                    ";        
                                 }   
                             }else{
                                echo "
                                $().toastmessage('showToast', {
                                text     : '<b>Error update tblArTransactionDue!</b><br>',
                                sticky   : true,
                                position : 'middle-center',
                                type     : 'error',
                                close    : function () {console.log('toast is closed ...');}
                                });
                                ";    
                             }
                        }else{
                            echo "
                            $().toastmessage('showToast', {
                            text     : '<b>Error clear tblArTransactionDue!</b><br>',
                            sticky   : true,
                            position : 'middle-center',
                            type     : 'error',
                            close    : function () {console.log('toast is closed ...');}
                            });
                            ";       
                        }    
                    }else{
                        echo "
                        $().toastmessage('showToast', {
                        text     : '<b>Error update tblArTransaction!</b><br>',
                        sticky   : true,
                        position : 'middle-center',
                        type     : 'error',
                        close    : function () {console.log('toast is closed ...');}
                        });
                        ";     
                    }  
                }else{
                    echo "
                    $().toastmessage('showToast', {
                    text     : '<b>Error clear tblArTransaction!</b><br>',
                    sticky   : true,
                    position : 'middle-center',
                    type     : 'error',
                    close    : function () {console.log('toast is closed ...');}
                    });
                    ";      
                }    
            }else{
                echo "
                $().toastmessage('showToast', {
                text     : '<b>Error update tblArPayment!</b><br>',
                sticky   : true,
                position : 'middle-center',
                type     : 'error',
                close    : function () {console.log('toast is closed ...');}
                });
                ";    
            }
        }else{
            echo "
            $().toastmessage('showToast', {
            text     : '<b>Error clear tblArPayment!</b><br>',
            sticky   : true,
            position : 'middle-center',
            type     : 'error',
            close    : function () {console.log('toast is closed ...');}
            });
            ";
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
		

		function updateAp(){

			$.ajax({
				url: 'update.php',
				type: 'POST',
				data: 'action=updateAp',
				beforeSend: function() {
					jQuery('#activity_pane').showLoading();
                    $('#updateBtn').html('UPDATING');
				},
				success: function(data){
                    $('#updateBtn').html('UPDATE');
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
        
        function updateAr(){

            $.ajax({
                url: 'update.php',
                type: 'POST',
                data: 'action=updateAr',
                beforeSend: function() {
                    jQuery('#activity_pane').showLoading();
                    $('#updateBtn').html('UPDATING');
                },
                success: function(data){
                    $('#updateBtn').html('UPDATE');
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
                            <td align="left" width="40%"><font style="font-size: 12;"> Paid, Outstanding - Due, Outstanding - Current </font></td>
                            <td align="center">
                                <input type="button" name="submit" id="updateBtn" class="btn btn-success" onClick="updateAp();" value="UPDATE">
                            </td>
                        </tr>
                        <tr>
                            <td align="left" width="40%"><font style="font-size: 12;"> Ar Payment, Ar Transaction - Due, Ar Transaction - Current</font></td>
                            <td align="center">
                                <input type="button" name="submit" id="updateBtn" class="btn btn-success" onClick="updateAr();" value="UPDATE">
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