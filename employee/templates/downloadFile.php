<?php
require_once("../../config.php");
$ideFileGet = $_GET['ideFile'];

$query = $CKoneksi->mysqlQuery("SELECT * FROM empldoc WHERE ide = ".$ideFileGet." AND deletests=0");

if($halamanGet == "form")
{
	$query = $CKoneksi->mysqlQuery("SELECT * FROM emplform WHERE idform = ".$ideFileGet." AND deletests=0");
}

$pathFolder="../data/document/";
$row = $CKoneksi->mysqlFetch($query);

	$fileName = ucwords(strtolower($row['namedoc']));
	$fileDoc = $row['filedoc'];
	$extFile = $row['extdoc'];

$queryMime = $CKoneksi->mysqlQuery("SELECT mime FROM tblekstension WHERE nmekstension='".$extFile."' AND status='Y' LIMIT 1;");
$rowMime = $CKoneksi->mysqlFetch($queryMime);

	$mimeType = $rowMime['mime'];

$pathFile = $pathFolder.$fileDoc;

// headers to send your file
header("Content-Type: ".$mimeType);
//header("Content-Length: " . filesize($pathFile));
header('Content-Disposition: attachment; filename="'.$fileName.".".$extFile.'"');

// upload the file to the user and quit
readfile($pathFile);
exit;
?>