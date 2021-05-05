<?php
include 'connection.php';
//Routes
$tpl  = 'includes/templates/';
$inc  = 'includes/languages/';
$func = 'includes/functions/';
$css  = 'layout/css/';
$js   = 'layout/js/';
include $func . 'functions.php';
include 'includes/languages/en.php';
include $tpl . "header.php";
if(!isset($noNavbar)){include $tpl .'navbar.php';}
echo '<div class="container">'
?>
