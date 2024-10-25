<?php
require_once("config.php");

$aksiPost = $_POST['aksi'];

if($aksiPost == "morePaper")
{
	$jmlPaperPost = $_POST['jmlPaper'];
	$idDataInfoPost = $_POST['idDataInfo'];
	
	$html = "";
	for($i=1;$i<=$jmlPaperPost;$i++)
	{
		$isiPaper = $_POST['isiPaper'.$i];
		//".$CData->detilPaper($idDataInfoPost, $i, "isipaper")."
		$html.= "<span class=\"boldPersonal\" style=\"color:#A60000;\">Paper ".$i."</span><br />";
		$html.= "<textarea class=\"styeInputText\" cols=\"70\" rows=\"5\" id=\"paper$i\" name=\"paper$i\" readonly>".$isiPaper."</textarea>&nbsp;<br>";
	}
	echo $html;
}

if($aksiPost == "simpanSementara")
{
	$noReportPost = $_POST['noReport'];
	$nmVesselPost = $_POST['nmVessel'];
	$noVoyagePost = $_POST['noVoyage'];
	$dateEventPost = $CPublic->convTglDB($_POST['dateEvent']);
	$nmWriterPost = $_POST['nmWriter'];
	$dateReportPost = $CPublic->convTglDB($_POST['dateReport']);
	$typeReportPost = $_POST['typeReport'];

	$query = $CKoneksi->mysqlQuery("UPDATE datalaporan_temp SET noreport='".$noReportPost."', nmvessel='".$nmVesselPost."', novoyage='".$noVoyagePost."', dateevent='".$dateEventPost."', nmwriter='".$nmWriterPost."', datereport='".$dateReportPost."', typereport='".$typeReportPost."'  WHERE deletests=0 LIMIT 1 ;");	
	echo $noVoyagePost;
}

$CKoneksi->tutupKoneksi($CKoneksi->bukaKoneksi());
?>