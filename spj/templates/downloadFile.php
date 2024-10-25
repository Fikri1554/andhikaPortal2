<?php
require_once("../../config.php");

/*$pathFolder="../data/document/";
$pathFolderConvFold="../data/documentConvFold/";

$ideFileGet = $_GET['ideFile'];

$idFold = $CFile->detilFile($ideFileGet, "idfold");
$foldSub = $CFolder->detilFoldByIdFold($idFold, "foldsub");
$levelFolder="LEVEL".$foldSub;
$fileFold = $CFolder->detilFoldByIdFold($idFold, "filefold");
$fileDoc = $CFile->detilFile($ideFileGet, "filedoc");*/


/*$convFold = $CFile->detilFile($ideFileGet, "convfold");
if($convFold == "N")
{
	$pathFile = $pathFolder.$levelFolder."/".$fileFold."/".$fileDoc;
}
else if($convFold == "Y")
{
	$pathFile = $pathFolderConvFold.$levelFolder."/".$fileFold."/".$fileDoc;
}*/
$pathFile = "../spj.pdf";

$fileName = "spj";
$extFile = "pdf";
$mimeType = $CFile->detilExtension("pdf", "mime");

// headers to send your file
header("Content-Type: ".$mimeType);
header("Content-Length: ". filesize($pathFile));
header('Content-Disposition: attachment; filename="'.$fileName.".".$extFile.'"');

// upload the file to the user and quit
readfile($pathFile);
exit;
?>