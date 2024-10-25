<?php
require_once("config.php");

$tpl = new myTemplate($tplDir."halUtama.html");
$tpl->AssignInclude("CONTENT_TENGAH",$tplDir."archives.html");

$tpl->prepare();
$tpl->printToScreen();
?>