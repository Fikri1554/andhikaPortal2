<?php
require_once("../config.php");

if($halamanPost == "cekFileAdaTidak")
{
	$jenisPost = $_POST['jenis']; // Policy / Procedure
	$aksiFormPost = $_POST['aksiForm']; // form -> new form / edit form
	$namaFilePost = mysql_real_escape_string( $_POST['namaFile'] );
	$idePost = $_POST['ide'];

	$query = $CKoneksi->mysqlQuery("SELECT namedoc FROM empldoc WHERE jenis = '".$jenisPost."' AND namedoc='".$namaFilePost."' AND deletests=0 LIMIT 1");
	if($aksiFormPost == "newForm" || $aksiFormPost == "editForm")
	{
		$query = $CKoneksi->mysqlQuery("SELECT namedoc FROM emplform WHERE ide='".$idePost."' AND namedoc='".$namaFilePost."' AND deletests=0 LIMIT 1");
	}
		
	$row = $CKoneksi->mysqlFetch($query);
	
	$nilai = "kosong";
	if($row['namedoc'] != "")
	{
		$nilai = "ada";
	}

	echo "&nbsp;<input type=\"hidden\" id=\"fileAdaTidak\" name=\"fileAdaTidak\" value=\"".$nilai."\">";
}
?>