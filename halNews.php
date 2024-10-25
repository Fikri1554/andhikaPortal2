<?php
require_once("config.php");

$tpl = new myTemplate($tplDir."halUtama.html");
$tpl->AssignInclude("CONTENT_TENGAH",$tplDir."news.html");

$tpl->prepare();
$tpl->printToScreen();
?>