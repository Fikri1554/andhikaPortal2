<?php
require_once("../configSafir.php");

$folderExport="../data/exportDoc/";

$idDataGet = $_GET['idData'];
$namaKapalGet = $_GET['namaKapal'];
$hdsnGet = $_GET['hdsn'];

$fileDoc = $CData->detilData($idDataGet, $namaKapalGet, $hdsnGet, "fileexport_ho");
$pathFile = $folderExport.$fileDoc;

$fileName = $CData->detilData($idDataGet, $namaKapalGet, $hdsnGet, "noreport");
$mimeType = "text/plain";

// headers to send your file
header("Content-Type: ".$mimeType);
header("Content-Length: " . filesize($pathFile));
header('Content-Disposition: attachment; filename="'.$fileName.'.saf"');

// upload the file to the user and quit
readfile($pathFile);
exit;
?>