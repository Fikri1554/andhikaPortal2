<?php
require_once("../config.php");

// ========= -START- Cek owner card pada tanggal yg dipilih =========
if($halamanPost == "cekPin")
{
	$pin = $_POST['pin'];
	//$html= "<input type=\"text\" name=\"cekPin\" value=\"ada\"/>";
	$query = $CKoneksi->mysqlQuery("SELECT idsurvey FROM tblsurvey WHERE pin = '".$pin."' AND used = 'N'");
	$query1 = $CKoneksi->mysqlQuery("SELECT idsurvey FROM tblsurvey WHERE pin = '".$pin."' AND used = 'Y'");
	$jmlRow = $CKoneksi->mysqlNRows($query);
	$jmlRow1 = $CKoneksi->mysqlNRows($query1);
	
	$nilai = "tidak";
	if($jmlRow == 1)
	{
		$nilai = "ada";
	}
	if($jmlRow1 == 1)
	{
		$nilai = "used";
	}
	echo "<input type=\"hidden\" name=\"cekPin\" value=\"".$nilai."\"/>";
}

?>