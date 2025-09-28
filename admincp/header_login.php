<?php 
session_start();
ob_start();

if(isset($_SESSION['adminuser'])){
	header("location:index.php");
}


include("../db.php");

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Flippy BizLister - Admin Control Panel</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="favicon.ico" rel="shortcut icon" type="image/x-icon"/>

<link href="css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="css/style.css" rel="stylesheet" type="text/css">
<link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">

<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<script src="js/jquery.min.js"></script>	
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.pjax.js" type="text/javascript"></script>
<script src="js/jquery.ui.shake.js"></script>

<script>
$(function(){
  $(document).pjax('a', '.main-header');
});
</script>

</head>

<body>
<div id="wrap">

<div class="container-fluid">

<header class="main-header">

<a class="logo" href="index.php"><img class="img-responsive" src="images/logo.png" alt="Flippy Admin Penal"></a>

</header>