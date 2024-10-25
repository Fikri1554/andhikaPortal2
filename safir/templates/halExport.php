<?php 
require_once("../configSafir.php"); 
require_once("../class/clsencrypt.php");

$idDataGet = $_GET['idData'];
$namaKapalGet = $_GET['namaKapal'];
$hdsnGet = $_GET['hdsn'];
?>

<script language="javascript">
this.window.onload = 
function() 
{
	parent.doneWait();
	parent.panggilEnableLeftClick();
}
</script>

<?php
if($aksiGet == "exportData")
{
	$dir = "../data/exportDoc/";
	$fileExport = $idDataGet.str_replace(" ","",str_replace("-","",$hdsnGet)).".saf";
	
	$CKoneksi->mysqlQuery("UPDATE datalaporan SET export_ho='Y', waktuexport_ho='".$CPublic->tglServer()."/".$CPublic->jamServer()."', fileexport_ho='".$fileExport."' WHERE iddata=".$idDataGet." AND namakapal='".$namaKapalGet."' AND hdsn='".$hdsnGet."' AND deletests=0;");
	$CHistory->updateLog2($userIdLogin, "Export Data Laporan HO (iddata = <b>".$idDataGet."</b>)");
	
	$cb100 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb100");
	$cb110 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb110");
	$cb120 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb120");
	$cb130 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb130");
	$cb140 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb140");
	$cb150 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb150");
	$cb160 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb160");
	$cb170 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb170");
	$cb180 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb180");
	
	$cb200 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb200");
	$cb210 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb210");
	$cb220 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb220");
	$cb230 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb230");
	$cb240 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb240");
	$cb250 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb250");
	$cb260 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb260");
	$cb270 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb270");
	$cb280 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb280");
	
	$cb300 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb300");
	$cb310 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb310");
	$cb320 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb320");
	$text330 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "text330");
	$text340 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "text340");
	$cb350 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb350");
	$cb360 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb360");
	$cb370 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb370");
	
	$cb400 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb400");
	$cb410 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb410");
	$cb420 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb420");
	$cb430 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb430");
	$cb440 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb440");
	$cb450 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb450");
	$cb460 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb460");
	$cb470 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb470");
	$cb480 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb480");
	
	$cb500 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb500");
	$cb510 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb510");
	$cb520 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb520");
	$cb530 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb530");
	$cb540 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb540");
	$cb550 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb550");
	
	$cb600 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb600");
	$cb610 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb610");
	$cb620 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb620");
	$cb630 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb630");
	$cb640 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb640");
	$cb650 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb650");
	$cb660 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb660");
	$cb670 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb670");
	$cb680 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb680");
	
	$cb700 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb700");
	$cb710 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb710");
	$cb720 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb720");
	$cb730 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb730");
	$cb740 = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb740");
	$noticeGiven = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "noticegiven");
	
	$describeHappen = detilInfoReplace($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "describehappen");
	$probableCaused = detilInfoReplace($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "probablecaused");
	$immediateCorr = detilInfoReplace($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "immediatecorr");
	$corrAction = detilInfoReplace($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "corraction");
	$preventive = detilInfoReplace($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "preventive");
	$proposalDate = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "proposaldate");
	$isiPic = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "pic");
	
	$signPlace = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "signplace");
	$signDate = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "signdate");
	$signMaster = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "signmaster");
	$signChef = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "signchef");
	$signSafCom = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "signsafcom");
	
	$addUsrDtInfo = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "addusrdt");
	$updUsrDtInfo = detilInfo($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "updusrdt");
	
	$teks = "";
	$teks.= "SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';#-#-";                                                                                           
	$teks.= "DELETE FROM datainfo WHERE iddata='".$idDataGet."' AND namakapal='".$namaKapalGet."' AND hdsn='".$hdsnGet."' AND deletests=0;#-#-";
	//$teks.= "UPDATE datainfo SET deletests=1 WHERE iddata='".$idDataGet."' AND deletests=0;#-#-";
	$teks.= "INSERT INTO datainfo (iddata,namakapal,hdsn,noreport,nmvessel,cb100,cb110,cb120,cb130,cb140,cb150,cb160,cb170,cb180,cb200,cb210,cb220,cb230,cb240,cb250,cb260,cb270,cb280,cb300,cb310,cb320,text330,text340,cb350,cb360,cb370,cb400,cb410,cb420,cb430,cb440,cb450,cb460,cb470,cb480,cb500,cb510,cb520,cb530,cb540,cb550,cb600,cb610,cb620,cb630,cb640,cb650,cb660,cb670,cb680,cb700,cb710,cb720,cb730,cb740,noticegiven,describehappen,probablecaused,immediatecorr,corraction,preventive,proposaldate,pic,signplace,signdate,signmaster,signchef,signsafcom,addusrdt,updusrdt,deletests) VALUES('".$idDataGet."','".$namaKapalGet."','".$hdsnGet."','".$noReport."','".$nmVessel."','".$cb100."','".$cb110."','".$cb120."','".$cb130."','".$cb140."','".$cb150."','".$cb160."','".$cb170."','".$cb180."','".$cb200."','".$cb210."','".$cb220."','".$cb230."','".$cb240."','".$cb250."','".$cb260."','".$cb270."','".$cb280."','".$cb300."','".$cb310."','".$cb320."','".$text330."','".$text340."','".$cb350."','".$cb360."','".$cb370."','".$cb400."','".$cb410."','".$cb420."','".$cb430."','".$cb440."','".$cb450."','".$cb460."','".$cb470."','".$cb480."','".$cb500."','".$cb510."','".$cb520."','".$cb530."','".$cb540."','".$cb550."','".$cb600."','".$cb610."','".$cb620."','".$cb630."','".$cb640."','".$cb650."','".$cb660."','".$cb670."','".$cb680."','".$cb700."','".$cb710."','".$cb720."','".$cb730."','".$cb740."','".$noticeGiven."',".$describeHappen.",".$probableCaused.",".$immediateCorr.",".$corrAction.",".$preventive.",'".$proposalDate."','".$isiPic."','".$signPlace."','".$signDate."','".$signMaster."','".$signChef."','".$signSafCom."','".$addUsrDtInfo."','".$updUsrDtInfo."','0');#-#-";
	
	$teks.= "DELETE FROM paperdescribe WHERE iddata='".$idDataGet."' AND namakapal='".$namaKapalGet."' AND hdsn='".$hdsnGet."' AND deletests=0;#-#-";
	//$teks.= "UPDATE paperdescribe SET deletests=1 WHERE iddata='".$idDataGet."' AND deletests=0;#-#-";
	$queryPaper = $CKoneksi->mysqlQuery("SELECT iddata, urutan, REPLACE(REPLACE(isipaper, '\n', '#n#n'), '\r', '#r#r') AS isipaper, addusrdt, updusrdt, delusrdt, deletests FROM paperdescribe WHERE iddata='".$idDataGet."' AND namakapal='".$namaKapalGet."' AND hdsn='".$hdsnGet."' AND deletests=0;");
	while($rowPaper = $CKoneksi->mysqlFetch($queryPaper))
	{
		//$teks.= "INSERT INTO paperdescribe (iddata, urutan, isipaper, addusrdt, updusrdt, delusrdt, deletests) VALUES ('".$idDataGet."', '".$rowPaper['urutan']."', REPLACE(REPLACE('".htmlspecialchars( html_entity_decode( $rowPaper['isipaper'] ), ENT_QUOTES)."', '#n#n', '\\n'), '#r#r', '\\r'), '".$rowPaper['addusrdt']."', '".$rowPaper['updusrdt']."', '".$rowPaper['delusrdt']."', '".$rowPaper['deletests']."');#-#-";
		$teks.= "INSERT INTO paperdescribe (iddata, namakapal, hdsn, urutan, isipaper, addusrdt, updusrdt, delusrdt, deletests) VALUES ('".$idDataGet."', '".$namaKapalGet."', '".$hdsnGet."', '".$rowPaper['urutan']."', REPLACE(REPLACE('". mysql_real_escape_string($rowPaper['isipaper'])."', '#n#n', '\\n'), '#r#r', '\\r'), '".$rowPaper['addusrdt']."', '".$rowPaper['updusrdt']."', '".$rowPaper['delusrdt']."', '".$rowPaper['deletests']."');#-#-";
	}
	
	$noReport = detilData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "noreport");
	$nmVessel = detilData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "nmvessel");
	$noVoyage = detilData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "novoyage");
	$dateEvent = detilData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "dateevent");
	$nmWriter = detilData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "nmwriter");
	$dateReport = detilData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "datereport");
	$typeReport = detilData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "typereport");
	$export = detilData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "export");
	$waktuExport = detilData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "waktuexport");
	$fileExport = detilData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "fileexport");
	$exportHo = detilData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "export_ho");
	$waktuExportHo = detilData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "waktuexport_ho");
	$fileExportHo = detilData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "fileexport_ho"); 
	
	$lastImport = detilData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "lastimport");
	$ack = detilData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "ack");
	$waktuAck = detilData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "waktuack");
	$ackBy = detilData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "ackby");
	$accept = detilData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "accept");
	$waktuAccept = detilData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "waktuaccept");
	$waktuNotAccept = detilData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "waktunotaccept");
	$acceptBy = detilData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "acceptby");
	$closed = detilData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "closed");
	$waktuClosed = detilData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "waktuclosed");
	$waktuNotClosed = detilData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "waktunotclosed");
	$closedBy = detilData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "closedby");
	$explanation = detilData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "explanation");
	
	$addUsrDtLap = detilData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "addusrdt");
	$updUsrDtLap = detilData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "updusrdt");
	$delUsrDtLap = detilData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "delusrdt");
	
	$teks.= "DELETE FROM datalaporan WHERE iddata='".$idDataGet."' AND namakapal='".$namaKapalGet."' AND hdsn='".$hdsnGet."' AND deletests=0 LIMIT 1;#-#-";
	//$teks.= "UPDATE datalaporan SET deletests=1 WHERE iddata='".$idDataGet."' LIMIT 1;#-#-";
	$teks.= "INSERT INTO datalaporan(iddata, namakapal, hdsn, noreport, nmvessel, novoyage, dateevent, nmwriter, datereport, typereport, export, waktuexport, fileexport, export_ho, waktuexport_ho, fileexport_ho, lastimport, ack, waktuack, ackby, accept, waktuaccept, waktunotaccept, acceptby, closed, waktuclosed, waktunotclosed, closedby, explanation, addusrdt, updusrdt, delusrdt, deletests)VALUES('".$idDataGet."', '".$namaKapalGet."', '".$hdsnGet."', '".$noReport."', '".$nmVessel."', '".$noVoyage."', '".$dateEvent."', '".$nmWriter."', '".$dateReport ."', '".$typeReport."', '".$export."', '".$waktuExport."', '".$fileExport."', '".$exportHo."', '".$waktuExportHo."', '".$fileExportHo."', '".$lastImport."', '".$ack."', '".$waktuAck."', '".$ackBy."', '".$accept."', '".$waktuAccept."', '".$waktuNotAccept."', '".$acceptBy."', '".$closed."', '".$waktuClosed."', '".$waktuNotClosed."', '".$closedBy."', '".$explanation."', '".$addUsrDtLap."', '".$updUsrDtLap."', '".$delUsrDtLap."', '0');#-#-";
	
	$enc = new Encryption;
	$encTeks = $enc->encrypt("andhika", $teks);
	//$encTeks = $teks;
	
	$cnmsize = $dir.$fileExportHo;
	//$cnm = fopen($cnmsize, "a+"); // DIGUNAKAN JIKA ISI TEKS DITAMBAHKAN TERUS MENERUS / TIDAK SEKALI ISI TEKS (HAPUS BARU DIISI)
	$cnm = fopen($cnmsize, "w");
	fwrite($cnm, $encTeks);
	fclose($cnm);
}

function replaceTeks($teks)
{
	$search = array(chr(174), chr(175));
	$replace = array('"', '"');
	return str_replace($search, $replace, $text); 
}

function lastExport($CKoneksi, $waktuExport)
{
	$exp = explode("/", $waktuExport);
	
	$query = $CKoneksi->mysqlQuery("SELECT CONCAT(DAY('".$exp[0]."'),', ',MONTHNAME('".$exp[0]."'),' ',YEAR('".$exp[0]."')) as lastexport");
	$row = $CKoneksi->mysqlFetch($query);
	
	return $row['lastexport']." ".$exp[1];
}

$waktuExport = detilData($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "waktuexport");
$lastExport = lastExport($CKoneksi, $waktuExport);

function detilInfoReplace($CKoneksi, $idData, $namaKapal, $hdsn, $fields)
{
	$query = $CKoneksi->mysqlQuery("SELECT REPLACE(REPLACE(".$fields.", '\n', '#n#n'), '\r', '#r#r') AS ".$fields." FROM datainfo WHERE iddata='".$idData."' AND namakapal='".$namaKapal."' AND hdsn='".$hdsn."' AND deletests=0;");
	$row = $CKoneksi->mysqlFetch( $query );
	
	return "REPLACE(REPLACE('".mysql_real_escape_string($row[$fields])."', '#n#n', '\\n'), '#r#r', '\\r')";
}

function detilInfo($CKoneksi, $idData, $namaKapal, $hdsn, $fields)
{
	$query = $CKoneksi->mysqlQuery("SELECT ".$fields." FROM datainfo WHERE iddata='".$idData."' AND namakapal='".$namaKapal."' AND hdsn='".$hdsn."' AND deletests=0;");
	$row = $CKoneksi->mysqlFetch( $query );
	// JIKA ADA CHARACTER left double quote	(")/(â€œs) ATAU right double quote (")/(â€) YANG MERUPAKAN TIPE COLLATION LATIN 1 DIRUBAH MENJADI 	left double quote(&ldquo;) ATAUN right double quote	(&rdquo;) AGAR BISA DI ENKRIP 	
	return mysql_real_escape_string($row[$fields]);
}

function detilData($CKoneksi, $idData, $namaKapal, $hdsn, $field)
{
	$query = $CKoneksi->mysqlQuery("SELECT ".$field." FROM datalaporan WHERE iddata='".$idData."' AND namakapal='".$namaKapal."' AND hdsn='".$hdsn."' AND deletests=0");
	$row = $CKoneksi->mysqlFetch($query);
	
	return mysql_real_escape_string($row[$field]);
}
?>

<script>
parent.refreshDataList();
</script>