<?php
require_once("../config.php");
require_once("configSafir.php");

$idDataGet = $_GET['idData'];
$namaKapalGet = $_GET['namaKapal'];
$hdsnGet = $_GET['hdsn'];

if($aksiGet == "printReport")
{
	$tpl = new myTemplate($tplDir."laporanSafir.html");
	
	$tpl->prepare();
	
	$tpl->Assign("noReport", $CData->detilData($idDataGet, $namaKapalGet, $hdsnGet, "noreport"));
	$tpl->Assign("nmVessel", $CData->detilData($idDataGet, $namaKapalGet, $hdsnGet, "nmvessel"));
	$tpl->Assign("noVoyage", $CData->detilData($idDataGet, $namaKapalGet, $hdsnGet, "novoyage"));
	$tpl->Assign("dateEvent", $CPublic->convTglNonDB( $CData->detilData($idDataGet, $namaKapalGet, $hdsnGet, "dateevent")) );
	$tpl->Assign("nmWriter", $CData->detilData($idDataGet, $namaKapalGet, $hdsnGet, "nmwriter"));
	$tpl->Assign("dateReport", $CPublic->convTglNonDB( $CData->detilData($idDataGet, $namaKapalGet, $hdsnGet, "datereport")) );

	$tpl->Assign("cekA", $cekA);
	$tpl->Assign("cekB", $cekB);
	$tpl->Assign("cekC", $cekC);
	$tpl->Assign("cekD", $cekD);
	
	if($CData->detilData($idDataGet, $namaKapalGet, $hdsnGet, "typereport") == "A")
	{	
		$tpl->Assign("cekA", "&radic;");
		
		$tpl->Assign("cek100", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb100"));
		$tpl->Assign("cek110", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb110"));
		$tpl->Assign("cek120", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb120"));
		$tpl->Assign("cek130", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb130"));
		$tpl->Assign("cek140", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb140"));
		$tpl->Assign("cek150", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb150"));
		$tpl->Assign("cek160", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb160"));
		$tpl->Assign("cek170", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb170"));
		$tpl->Assign("cek180", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb180"));
		
		$tpl->Assign("cek200", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb200"));
		$tpl->Assign("cek210", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb210"));
		$tpl->Assign("cek220", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb220"));
		$tpl->Assign("cek230", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb230"));
		$tpl->Assign("cek240", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb240"));
		$tpl->Assign("cek250", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb250"));
		$tpl->Assign("cek260", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb260"));
		$tpl->Assign("cek270", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb270"));
		$tpl->Assign("cek280", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb280"));
		
		$tpl->Assign("cek300", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb300"));
		$tpl->Assign("cek310", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb310"));
		$tpl->Assign("cek320", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb320"));
		$tpl->Assign("text330", $CData->detilInfo($idDataGet, $namaKapalGet, $hdsnGet, "text330"));
		$tpl->Assign("text340", $CData->detilInfo($idDataGet, $namaKapalGet, $hdsnGet, "text340"));
		$tpl->Assign("cek350", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb350"));
		$tpl->Assign("cek360", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb360"));
		$tpl->Assign("cek370", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb370"));
		
		$tpl->Assign("cek400", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb400"));
		$tpl->Assign("cek410", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb410"));
		$tpl->Assign("cek420", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb420"));
		$tpl->Assign("cek430", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb430"));
		$tpl->Assign("cek440", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb440"));
		$tpl->Assign("cek450", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb450"));
		$tpl->Assign("cek460", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb460"));
		$tpl->Assign("cek470", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb470"));
		$tpl->Assign("cek480", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb480"));
	}
	if($CData->detilData($idDataGet, $namaKapalGet, $hdsnGet, "typereport") == "B")
	{	
		$tpl->Assign("cekB", "&radic;");
		
		$tpl->Assign("cek100", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb100"));
		$tpl->Assign("cek110", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb110"));
		$tpl->Assign("cek120", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb120"));
		$tpl->Assign("cek130", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb130"));
		$tpl->Assign("cek140", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb140"));
		$tpl->Assign("cek150", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb150"));
		$tpl->Assign("cek160", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb160"));
		$tpl->Assign("cek170", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb170"));
		$tpl->Assign("cek180", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb180"));
		
		$tpl->Assign("cek200", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb200"));
		$tpl->Assign("cek210", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb210"));
		$tpl->Assign("cek220", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb220"));
		$tpl->Assign("cek230", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb230"));
		$tpl->Assign("cek240", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb240"));
		$tpl->Assign("cek250", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb250"));
		$tpl->Assign("cek260", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb260"));
		$tpl->Assign("cek270", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb270"));
		$tpl->Assign("cek280", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb280"));
	}
	if($CData->detilData($idDataGet, $namaKapalGet, $hdsnGet, "typereport") == "C")
	{	
		$tpl->Assign("cekC", "&radic;");
		
		$tpl->Assign("cek500", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb500"));
		$tpl->Assign("cek510", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb510"));
		$tpl->Assign("cek520", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb520"));
		$tpl->Assign("cek530", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb530"));
		$tpl->Assign("cek540", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb540"));
		$tpl->Assign("cek550", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb550"));
	}
	if($CData->detilData($idDataGet, $namaKapalGet, $hdsnGet, "typereport") == "D")
	{	
		$tpl->Assign("cekD", "&radic;");
		
		$tpl->Assign("cek600", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb600"));
		$tpl->Assign("cek610", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb610"));
		$tpl->Assign("cek620", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb620"));
		$tpl->Assign("cek630", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb630"));
		$tpl->Assign("cek640", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb640"));
		$tpl->Assign("cek650", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb650"));
		$tpl->Assign("cek660", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb660"));
		$tpl->Assign("cek670", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb670"));
		$tpl->Assign("cek680", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb680"));
	}
	
	$tpl->Assign("cek700", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb700"));
	$tpl->Assign("cek710", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb710"));
	$tpl->Assign("cek720", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb720"));
	$tpl->Assign("cek730", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb730"));
	$tpl->Assign("cek740", detilCentang($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, "cb740"));

	$tpl->Assign("noticeGiven", $CData->detilInfo($idDataGet, $namaKapalGet, $hdsnGet, "noticegiven"));
	$tpl->Assign("describeHappen", $CData->detilInfo($idDataGet, $namaKapalGet, $hdsnGet, "describehappen"));
	$tpl->Assign("probableCaused", $CData->detilInfo($idDataGet, $namaKapalGet, $hdsnGet, "probablecaused"));
	$tpl->Assign("immediateCorr", $CData->detilInfo($idDataGet, $namaKapalGet, $hdsnGet, "immediatecorr"));
	$tpl->Assign("corraction", $CData->detilInfo($idDataGet, $namaKapalGet, $hdsnGet, "corraction"));
	$tpl->Assign("preventive", $CData->detilInfo($idDataGet, $namaKapalGet, $hdsnGet, "preventive"));
	
	$proposalDate = "..................";
	if($CData->detilInfo($idDataGet, $namaKapalGet, $hdsnGet, "proposaldate") != "0000-00-00")
	{
		$proposalDate = $CPublic->convTglNonDB( $CData->detilInfo($idDataGet, $namaKapalGet, $hdsnGet, "proposaldate") );
	}
	$pic = "..................................";
	if($CData->detilInfo($idDataGet, $namaKapalGet, $hdsnGet, "pic") != "")
	{
		$pic = $CData->detilInfo($idDataGet, $namaKapalGet, $hdsnGet, "pic");
	}
	$tpl->Assign("proposalDate", $proposalDate);
	$tpl->Assign("pic", $pic);
	
	$tpl->Assign("signPlace", $CData->detilInfo($idDataGet, $namaKapalGet, $hdsnGet, "signplace"));
	$tpl->Assign("signDate", $CPublic->convTglNonDB( $CData->detilInfo($idDataGet, $namaKapalGet, $hdsnGet, "signdate")) );
	$tpl->Assign("signMaster", $CData->detilInfo($idDataGet, $namaKapalGet, $hdsnGet, "signmaster"));
	$tpl->Assign("signChef", $CData->detilInfo($idDataGet, $namaKapalGet, $hdsnGet, "signchef"));
	$tpl->Assign("signSafcom", $CData->detilInfo($idDataGet, $namaKapalGet, $hdsnGet, "signsafcom"));

	if($CData->detilData($idDataGet, $namaKapalGet, $hdsnGet, "accept") == "Y")
	{
		$tpl->Assign("cekAccept", "&radic;");
	}
	else
	{
		$tpl->Assign("cekNotAccept", "&radic;");
	}
	$tpl->Assign("acceptBy", $CData->detilData($idDataGet, $namaKapalGet, $hdsnGet, "acceptby"));
	if($CData->detilData($idDataGet, $namaKapalGet, $hdsnGet, "closed") == "Y")
	{
		$tpl->Assign("cekClosed", "&radic;");
	}
	else
	{
		$tpl->Assign("cekNotClosed", "&radic;");
	}
	$tpl->Assign("explanation", $CData->detilData($idDataGet, $namaKapalGet, $hdsnGet, "explanation"));
	$tpl->Assign("closedBy", $CData->detilData($idDataGet, $namaKapalGet, $hdsnGet, "closedby"));

	$paperDescribe = "";
	$jmlPaperAktif = $CData->jmlPaperAktif($idDataGet, $namaKapalGet, $hdsnGet);
	
	$paperStyle = "";
	$paperStyle.= "<style>";
	for($a=1; $a<=$jmlPaperAktif; $a++)
	{
		$paperStyle.= "
		.wrap".(3+$a)."
		{
			position:relative;
			width: 794px;
			height: 1122px;
		
			margin:0 auto;
		}";
	}
	$paperStyle.= "</style>";
	
	$isiPaper = "";
	for($b=1; $b<=$jmlPaperAktif; $b++)
	{
		$isiPaper.= "
		<div class=\"wrap".(3+$b)."\">
			<div style=\"position: absolute; width: 200px; height:40px; padding:10px; text-align:justify; font-family:Arial; font-size:12px; left:36px;\">
				<b><u>MORE DESCRIBE</u></b><br><i>Paper ".$b."</i>
			</div>
			<div style=\"position:absolute; width:700px; left:50%; margin-left:-350px; top:60px; text-align:justify; font-family:Arial; font-size:12px;\">
				<p>
				".detilIsiPaper($CKoneksi, $idDataGet, $namaKapalGet, $hdsnGet, $b)."
				</p>
			</div>
			<div style=\"page-break-after: left;\"></div>
		</div>";
	}
	
	 $paperDescribe.= $paperStyle.$isiPaper;

	$tpl->Assign("paperDescribe", $paperDescribe);
	$tpl->printToScreen();
}

function detilIsiPaper($CKoneksi, $idData, $namaKapal, $hdsn, $urutan)
{
	$query = $CKoneksi->mysqlQuery("SELECT REPLACE(isipaper, '\n', '<br>') AS isipaper FROM paperdescribe WHERE iddata = '".$idData."' AND namakapal='".$namaKapal."' AND hdsn='".$hdsn."' AND urutan = '".$urutan."' AND deletests=0;");
	$row = $CKoneksi->mysqlFetch($query);
	
	return $row['isipaper'];
}

function detilCentang($CKoneksi, $idData, $namaKapal, $hdsn, $fields)
{
	$nilai = "";
	$row = $CKoneksi->mysqlFetch( $CKoneksi->mysqlQuery("SELECT ".$fields." FROM datainfo WHERE iddata='".$idData."' AND namakapal='".$namaKapal."' AND hdsn='".$hdsn."' AND deletests=0;") );
	if( $row[$fields] == "Y" )
	{
		$nilai = "&radic;";
	}
	
	return $nilai;
}

$CKoneksi->tutupKoneksi($CKoneksi->bukaKoneksi());

/*
i always get s solution for a difficult job, working overtime, honestly person, work with heart and sincerity, listen to opinions and do the best

saya selalu mendapatkan solusi untuk pekerjaan yang sulit, terbiasa lembur, pribadi yang menyenangkan, bekerja dengan hati dan ikhlas, senang mendengarkan pendapat 
maupun kritik dan selalu berusaha melakukan yang terbaik
*/
?>