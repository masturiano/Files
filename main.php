<?php
//initialize session
session_start();

include("includes/db.inc.php");
include("includes/common.php");
include("loginObj.php");

$loginObj = new loginObj();

$userName = $_SESSION['userName'];
$passWord = $_SESSION['passWord'];
$userAcess = $loginObj->login($userName,$passWord);
$userNotExist = $loginObj->getRecCount($loginObj->loginNotExist($userName,$passWord));

//Restriction
if($userName == '' && $passWord == ''){
header('Location: index.php');
}

?>
<html>
	<head>
    	
        <!-- jQuery, Bootstrap -->
    	<link rel="stylesheet" href="includes/bootstrap/css/bootstrap.css"/>
        <link rel="stylesheet" href="includes/bootstrap/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="includes/bootstrap/css/bootstrap-responsive.css"/>
        <link rel="stylesheet" href="includes/bootstrap/css/bootstrap-responsive.min.css"/>
        <!-- jQuery, Bootstrap -->
        
        <!-- latest jQuery, Boostrap JS and hover dropdown plugin -->
		<script src="includes/twitter-bootstrap-hover-dropdown-master/jquery-latest.min.js"></script>
        <script src="includes/twitter-bootstrap-hover-dropdown-master/twitter-bootstrap-hover-dropdown.js"></script>
        <!-- latest jQuery, Boostrap JS and hover dropdown plugin -->
        
        <!-- jQuery, BGIFrame -->
        <script type="text/javascript" src="includes/external/jquery.bgiframe-2.1.1.js"></script>
        <script language="javascript">
        function menu(url)	{
        $("#bodyFrame").attr('src',url);
        }		
		</script>
        <!-- jQuery, BGIFrame -->
        
        <!-- Collapsible Menu -->
        <script src="includes/collapsible/jquery-1.2.1.min.js" type="text/javascript"></script>
		<script src="includes/collapsible/menu.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="includes/collapsible/style.css" />
        <!-- Collapsible Menu -->
        
        <script src="includes/jquery/development-bundle/jquery-1.6.2.js" type="text/javascript"></script>
        
		<style type="text/css">
		<!--
		body { 
			background:url(includes/images/bgAbstract.jpg) no-repeat center center fixed; 
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
			top:0px;
		}
		.dropdown-menu{
			position:absolute;
			top:33px;
		}
		.topHeader{
			width:99%;
			height:60px;
			margin-left: auto;
			margin-right: auto;
			border-color:#000;
			border-style:solid;
			border-width:0px;
		}
		.puregoldLogo{
			height:60px;
			width:420px;
			background-color:transparent;

		}
		.creditCard{
			height:50px;
			width:120px;
			background-color:transparent;

		}
		.dvHeader{
			width:99%;
			height:42px;
			margin-left: auto;
			margin-right: auto;
			position:relative;
			top:0px;
			box-shadow: 10px 10px 5px #888888;
		}
		.dvContainer{
			height:600px;
			max-width:99%;
			margin-left: auto;
			margin-right: auto;
			position:relative;
			top:2px;
			border-color:#000;
			border-style:solid;
			border-width:0px;
			margin-left: auto;
			margin-right: auto;
			box-shadow: 10px 10px 5px #888888;
			background-color:#FFF;
		}
		.dvSidebar{
			height:600px;
			float:left;
			width:17.1%;
			border-color:#000;
			border-style:solid;
			border-width:1px;
			background-color:#000;
		}
		.dvBody{
			height:600px;
			float:left;
			min-width:20%;
			width:82.5%;
			border-color:#000;
			border-style:solid;
			border-width:1px;
			background-color:#FFF;
		}
		.bodyFrame{
			height:596px;
			width:99.7%;
		}
		.dvFooter{
			width:99%;
			height:42px;
			margin-left: auto;
			margin-right: auto;
			position:relative;
			top:6px;
			box-shadow: 10px 10px 5px #888888;
		}
		-->	
		</style>

    </head>
	 	<body>
        	<div class="topHeader">
            	<img src="includes/images/PUREGOLD.png" class="puregoldLogo">
                <img src="includes/images/report.png" class="creditCard">
            </div>
        	<div class="dvHeader">
            <?php
				include("header.php");
			?>
            </div>
            
            <div class="dvContainer">
            	<div class="dvSidebar">
					<?php
						include("sidebar.php");
					?>
                </div>
                
                <div class="dvBody" id="dvBody">
                	<iframe id="bodyFrame" class="bodyFrame"></iframe>
                </div>	
            
			</div>   
            
            <div class="dvFooter">
            <?php
				include("footer.php");
			?>
            </div> 

		</body>
</html>