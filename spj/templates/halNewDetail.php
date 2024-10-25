<!DOCTYPE HTML>
<?php
require_once('../../config.php');
require_once('../configSpj.php');

$reportIdGet = $_GET['reportId'];		
$trActiveGet = $_GET['trActive'];

$ownerEmpno = $CSpj->detilLoginSpj($CSpj->detilReport($reportIdGet, "ownerid"), "empno", $db);

$tglDetil = "";

$btnCls = "class=\"spjBtnStandarDis\"";
$dis = " disabled";
$idEditFollower = "";
if($aksiGet == "new")
{
	$btnCls = "class=\"spjBtnStandar\"";
	$dis = "";
	$valCur = "";
}

if($CSpj->detilReport($reportIdGet, "othercur1") != 00)
{
	$otherCur1 = $CSpj->detilReport($reportIdGet, "othercur1");
}
if($CSpj->detilReport($reportIdGet, "othercur2") != 00)
{
	$otherCur2 = $CSpj->detilReport($reportIdGet, "othercur2");
}

if($aksiGet == "edit")
{
	$reportIdGet = $_GET['reportId'];
	$detilIdGet = $_GET['detilId'];
	
	$tglDetil = $CSpj->detilReportDetil($detilIdGet, "tgldetil");
	$tgl = substr($tglDetil,6,2);
	$bln = substr($tglDetil,4,2);
	$thn = substr($tglDetil,0,4);
	$dateFormat = "value=".$tgl."/".$bln."/".$thn."";
	$idEditFollower = $CSpj->detilReportDetil($detilIdGet, "pengikut");

	$valLoc = 'value="'.$CSpj->detilReportDetil($detilIdGet, "lokasi").'"';
	
	$costTunj = "";
	if($CSpj->detilReportDetil($detilIdGet, "costtunj") != 0.00)
	{
		$costTunj = 'value="'.number_format((float)$CSpj->detilReportDetil($detilIdGet, "costtunj"),2, '.', ',').'"';
	}
	$ketTunj = 'value="'.$CPublic->konversiQuotes1($CSpj->detilReportDetil($detilIdGet, "kettunj")).'"';
	
	/*$costPjp = "";
	if($CSpj->detilReportDetil($detilIdGet, "costpjp") != "")
	{
		$costPjp = 'value="'.number_format($CSpj->detilReportDetil($detilIdGet, "costpjp")).'"';
	}
	$ketPjp = 'value="'.$CPublic->konversiQuotes1($CSpj->detilReportDetil($detilIdGet, "ketpjp")).'"';*/
	
	$costTrans = "";
	if($CSpj->detilReportDetil($detilIdGet, "costtrans") != 0.00)
	{
		$costTrans = 'value="'.number_format((float)$CSpj->detilReportDetil($detilIdGet, "costtrans"),2, '.', ',').'"';
	}
	$ketTrans = 'value="'.$CPublic->konversiQuotes1($CSpj->detilReportDetil($detilIdGet, "kettrans")).'"';
	
	$costAkomd = "";
	if($CSpj->detilReportDetil($detilIdGet, "costakomd") != 0.00)
	{
		$costAkomd = 'value="'.number_format((float)$CSpj->detilReportDetil($detilIdGet, "costakomd"),2, '.', ',').'"';
	}
	$ketAkomd = 'value="'.$CPublic->konversiQuotes1($CSpj->detilReportDetil($detilIdGet, "ketakomd")).'"';
	
	$costKonsm = "";
	if($CSpj->detilReportDetil($detilIdGet, "costkonsm") != 0.00)
	{
		$costKonsm = 'value="'.number_format((float)$CSpj->detilReportDetil($detilIdGet, "costkonsm"),2, '.', ',').'"';
	}
	$ketKonsm = 'value="'.$CPublic->konversiQuotes1($CSpj->detilReportDetil($detilIdGet, "ketkonsm")).'"';
	
	$costLain = "";
	if($CSpj->detilReportDetil($detilIdGet, "costlain") != 0.00)
	{
		$costLain = 'value="'.number_format((float)$CSpj->detilReportDetil($detilIdGet, "costlain"),2, '.', ',').'"';
	}
	$ketLain = 'value="'.$CPublic->konversiQuotes1($CSpj->detilReportDetil($detilIdGet, "ketlain")).'"';
	
	//echo $reportIdGet." ".$detilIdGet." ".$tglDetil." ".$dateFormat;
}
?>
<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<link href="../css/cssSpj.css" rel="stylesheet" type="text/css" />
<link href="../css/loading.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></LINK>

<script type="text/javascript" src="../../js/jquery-1.11.2.js"></script>
<script type="text/javascript" src="../js/jquery.alphanum.js"></script>
<script src="../../js/JavaScriptUtil.js"></script>
<script src="../../js/Parsers.js"></script>
<script src="../../js/InputMask.js"></script>
<script type="text/javascript" src="../../js/main.js"></script>
<SCRIPT type="text/javascript" src="../../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<script type="text/javascript" src="../js/loading.js"></script>
<?php
/*if($aksiPost == "new")
{
	echo "<pre>";
	print_r($_POST);
	echo "</pre>";
}*/
if($aksiPost == "new")
{
	$reportId = $_POST['reportId'];
	$detilDate = $_POST['detilDate'];
	$tgl = substr($detilDate,6,2);
	$bln = substr($detilDate,4,2);
	$thn = substr($detilDate,0,4);
	$dateFormat = $thn.$bln.$tgl;
	
	$folNya = $_POST['slcFol'];
	$lokasi = $_POST['lokasi'];
	
	$jmlTunj = $_POST['jmlTunj'];
	//$jmlPjp = $_POST['jmlPjp'];
	$jmlTrans = $_POST['jmlTrans'];
	$jmlAkom = $_POST['jmlAkom'];
	$jmlConsm = $_POST['jmlConsm'];
	$jmlOther = $_POST['jmlOther'];
	$other1 = $_POST['other1'];
	$other2 = $_POST['other2'];
	$maxField = max($jmlTunj, $jmlTrans, $jmlAkom, $jmlConsm, $jmlOther);
	
	//echo $reportId." ".$dateFormat." ".$lokasi." ".$jmlTunj." ".$jmlPjp." ".$jmlTrans." ".$jmlAkom." ".$jmlConsm." ".$jmlOther." ".$maxField."<br/>" ;
	
	for($i=1;$i<=$maxField;$i++)
	{
		//echo $i."<br/>";
		$urutanAkhir = $CSpj->urutanAkhirReportDetail($reportId, $dateFormat);
		$urutan = $urutanAkhir+1;

		$curTunj = $_POST['curTunj'.$i];
		$costTunj = str_replace(",","",$_POST['costTunj'.$i]);
		
		/*$curPjp = $_POST['curPjp'.$i];
		$costPjp = str_replace(",","",$_POST['costPjp'.$i]);*/
		
		$curTrans = $_POST['curTrans'.$i];
		$costTrans = str_replace(",","",$_POST['costTrans'.$i]);
		
		$curAkom = $_POST['curAkom'.$i];
		$costAkom = str_replace(",","",$_POST['costAkom'.$i]);
		
		$curConsm = $_POST['curConsm'.$i];
		$costConsm = str_replace(",","",$_POST['costConsm'.$i]);
		
		$curOther = $_POST['curOther'.$i];
		$costOther = str_replace(",","",$_POST['costOther'.$i]);
		
		//echo $costTunj." ".$costPjp." ".$costTrans." ".$costAkom." ".$costConsm." ".$costOther."<br/>";
		
		// HITUNG IDR Currency
		$idrTunj = 0;
		//$idrPjp = 0;
		$idrTrans = 0;
		$idrAkom = 0;
		$idrConsm = 0;
		$idrOther = 0;
		
		if($curTunj == 01)	{	$idrTunj = $costTunj;	}
		//if($curPjp == "idr")	{	$idrPjp = $costPjp;		}
		if($curTrans == 01)	{	$idrTrans = $costTrans;	}
		if($curAkom == 01)	{	$idrAkom = $costAkom;	}
		if($curConsm == 01)	{	$idrConsm = $costConsm;	}
		if($curOther == 01)	{	$idrOther = $costOther;	}
		
		$idrTotal = $idrTunj + $idrTrans + $idrAkom + $idrConsm + $idrOther ;
		//echo "idr: idrTunj".$idrTunj." idrTrans".$idrTrans." idrAkom".$idrAkom." idrConsm".$idrConsm." idrOther".$idrOther." = ".$idrTotal."<br/>";
		
		// HITUNG USD Curency
		$usdTunj = 0;
		//$usdPjp = 0;
		$usdTrans = 0;
		$usdAkom = 0;
		$usdConsm = 0;
		$usdOther = 0;
		
		if($curTunj == 02	){	$usdTunj = $costTunj;	}
		//if($curPjp == "usd"		){	$usdPjp = $costPjp;		}
		if($curTrans == 02	){	$usdTrans = $costTrans;	}
		if($curAkom == 02	){	$usdAkom = $costAkom;	}
		if($curConsm == 02	){	$usdConsm = $costConsm;	}
		if($curOther == 02	){	$usdOther = $costOther;	}
		
		$usdTotal = $usdTunj + $usdTrans + $usdAkom + $usdConsm + $usdOther ;
		//echo "usd: ".$usdTunj." ".$usdTrans." ".$usdAkom." ".$usdConsm." ".$usdOther." = ".$usdTotal."<br/>";
		
		// HITUNG OTHER1 Currency
		if($other1 != "")
		{
			$other1Tunj = 0;
			$other1Trans = 0;
			$other1Akom = 0;
			$other1Consm = 0;
			$other1Other = 0;
			
			if($curTunj == $other1	){	$other1Tunj = $costTunj;	}
			if($curTrans == $other1	){	$other1Trans = $costTrans;	}
			if($curAkom == $other1	){	$other1Akom = $costAkom;	}
			if($curConsm == $other1	){	$other1Consm = $costConsm;	}
			if($curOther == $other1	){	$other1Other = $costOther;	}
			
			$other1Total = $other1Tunj + $other1Trans + $other1Akom + $other1Consm + $other1Other ;
			//echo "other1: ".$other1Tunj." ".$other1Trans." ".$other1Akom." ".$other1Consm." ".$other1Other." = ".$other1Total."<br/>";
		}
		
		// HITUNG OTHER2 Currency
		if($other2 != "")
		{
			$other2Tunj = 0;
			$other2Trans = 0;
			$other2Akom = 0;
			$other2Consm = 0;
			$other2Other = 0;
			
			if($curTunj == $other2	){	$other2Tunj = $costTunj;	}
			if($curTrans == $other2	){	$other2Trans = $costTrans;	}
			if($curAkom == $other2	){	$other2Akom = $costAkom;	}
			if($curConsm == $other2	){	$other2Consm = $costConsm;	}
			if($curOther == $other2	){	$other2Other = $costOther;	}
			
			$other2Total = $other2Tunj + $other2Trans + $other2Akom + $other2Consm + $other2Other ;
			//echo "other2: ".$other2Tunj." ".$other2Trans." ".$other2Akom." ".$other2Consm." ".$other2Other." = ".$other2Total."<br/>";
		}
		//insert Database
		$CKoneksiSpj->mysqlQuery("INSERT INTO reportdetil (reportid, urutan, tgldetil, lokasi, curtunj, costtunj, kettunj, curtrans, costtrans, kettrans, curakomd, costakomd, ketakomd, curkonsm, costkonsm, ketkonsm, curlain, costlain, ketlain, idrtotal, usdtotal, othercur1total, othercur2total,pengikut, addusrdt) VALUES (".$reportId.", ".$urutan.", ".$dateFormat.", '".$lokasi."', '".$curTunj."', '".$costTunj."', '".mysql_real_escape_string($_POST['ketTunj'.$i])."', '".$curTrans."', '".$costTrans."', '".mysql_real_escape_string($_POST['ketTrans'.$i])."', '".$curAkom."', '".$costAkom."', '".mysql_real_escape_string($_POST['ketAkom'.$i])."', '".$curConsm."', '".$costConsm."', '".mysql_real_escape_string($_POST['ketConsm'.$i])."', '".$curOther."', '".$costOther."', '".mysql_real_escape_string($_POST['ketOther'.$i])."', '".$idrTotal."', '".$usdTotal."', '".$other1Total."', '".$other2Total."','".$folNya."', '".$CPublic->userWhoAct()."');");
		$koma = ", ";
		if($i == $maxField)
		{
			$koma = "";
		}
		$lastInsertId.= mysql_insert_id().$koma;
	}
	
	//update other currency
	$curOther1 = 0;
	if($other1 != "")
	{
		$curOther1 = $other1;
	}
	$curOther2 = 0;
	if($other2 != "")
	{
		$curOther2 = $other2;
	}
	// grand total count & update
	$idrGrandTotal = $CSpj->grandTotal("idrtotal", $reportId);
	$usdGrandTotal = $CSpj->grandTotal("usdtotal", $reportId);
	$other1GrandTotal = $CSpj->grandTotal("othercur1total", $reportId);
	$other2GrandTotal = $CSpj->grandTotal("othercur2total", $reportId);
	$CKoneksiSpj->mysqlQuery("UPDATE report SET idrgrandtotal = ".$idrGrandTotal.", usdgrandtotal = ".$usdGrandTotal.", othercur1grandtotal = ".$other1GrandTotal.", othercur2grandtotal = ".$other2GrandTotal.", othercur1 = ".$curOther1.", othercur2 = ".$curOther2." WHERE reportid = ".$reportId." AND deletests = 0;");
	
	// uang kembali count & update
	$idrDp = $CSpj->detilReport($reportId, "idrdp");
	if($CSpj->detilReport($reportId, "idrdp") == "")
	{
		$idrDp = 0;
	}
	$idrKembali = $idrDp - $idrGrandTotal;
	$usdDp = $CSpj->detilReport($reportId, "usddp");
	if($CSpj->detilReport($reportId, "usddp") == "")
	{
		$usdDp = 0;
	}
	$usdKembali = $usdDp - $usdGrandTotal;
	$CKoneksiSpj->mysqlQuery("UPDATE report SET idrtotalkembali = ".$idrKembali.", usdtotalkembali = ".$usdKembali." WHERE reportid = ".$reportId." AND deletests = 0;");
	
	//insert history
	$CHistory->updateLogSpj($userIdLogin, "Membuat detail report (reportid = <b>".$reportId."</b>, detilid = <b>".$lastInsertId."</b>)");
}

if($aksiPost == "edit")
{
	$reportId = $_POST['reportId'];
	$detilId = $_POST['detilId'];
	$detilDate = $_POST['detilDate'];
	$tgl = substr($detilDate,6,2);
	$bln = substr($detilDate,4,2);
	$thn = substr($detilDate,0,4);
	$dateFormat = $thn.$bln.$tgl;
	
	$folNya = $_POST['slcFol'];
	$lokasi = $_POST['lokasi'];
	$other1 = $_POST['other1'];
	$other2 = $_POST['other2'];
	//echo $reportId." ".$detilId." ".$dateFormat."<br/>" ;
	
	$urutanAkhir = $CSpj->urutanAkhirReportDetail($reportId, $dateFormat);
	$urutan = $urutanAkhir+1;

	$curTunj = $_POST['curTunj1'];
	$costTunj = str_replace(",","",$_POST['costTunj1']);
	
	/*$curPjp = $_POST['curPjp1'];
	$costPjp = str_replace(",","",$_POST['costPjp1']);*/
	
	$curTrans = $_POST['curTrans1'];
	$costTrans = str_replace(",","",$_POST['costTrans1']);
	
	$curAkom = $_POST['curAkom1'];
	$costAkom = str_replace(",","",$_POST['costAkom1']);
	
	$curConsm = $_POST['curConsm1'];
	$costConsm = str_replace(",","",$_POST['costConsm1']);
	
	$curOther = $_POST['curOther1'];
	$costOther = str_replace(",","",$_POST['costOther1']);
	
	//echo $costTunj." ".$costPjp." ".$costTrans." ".$costAkom." ".$costConsm." ".$costOther."<br/>";
	
	//HITUNG IDR Currency
	$idrTunj = 0;
	//$idrPjp = 0;
	$idrTrans = 0;
	$idrAkom = 0;
	$idrConsm = 0;
	$idrOther = 0;
	
	if($curTunj == 01)	{	$idrTunj = $costTunj;	}
	//if($curPjp == "idr")	{	$idrPjp = $costPjp;		}
	if($curTrans == 01)	{	$idrTrans = $costTrans;	}
	if($curAkom == 01)	{	$idrAkom = $costAkom;	}
	if($curConsm == 01)	{	$idrConsm = $costConsm;	}
	if($curOther == 01)	{	$idrOther = $costOther;	}
	
	$idrTotal = $idrTunj + $idrTrans + $idrAkom + $idrConsm + $idrOther ;
	echo "idr: ".$idrTunj." ".$idrTrans." ".$idrAkom." ".$idrConsm." ".$idrOther." = ".$idrTotal."<br/>";
	
	//HITUNG USD Currency
	$usdTunj = 0;
	//$usdPjp = 0;
	$usdTrans = 0;
	$usdAkom = 0;
	$usdConsm = 0;
	$usdOther = 0;
	
	if($curTunj == 02	){	$usdTunj = $costTunj;	}
	//if($curPjp == "usd"		){	$usdPjp = $costPjp;		}
	if($curTrans == 02	){	$usdTrans = $costTrans;	}
	if($curAkom == 02	){	$usdAkom = $costAkom;	}
	if($curConsm == 02	){	$usdConsm = $costConsm;	}
	if($curOther == 02	){	$usdOther = $costOther;	}
	
	$usdTotal = $usdTunj + $usdTrans + $usdAkom + $usdConsm + $usdOther ;
	echo "usd: ".$usdTunj." ".$usdTrans." ".$usdAkom." ".$usdConsm." ".$usdOther." = ".$usdTotal."<br/>";
	
	// HITUNG OTHER1 Currency
	if($other1 != "")
	{
		$other1Tunj = 0;
		$other1Trans = 0;
		$other1Akom = 0;
		$other1Consm = 0;
		$other1Other = 0;
		
		if($curTunj == $other1	){	$other1Tunj = $costTunj;	}
		if($curTrans == $other1	){	$other1Trans = $costTrans;	}
		if($curAkom == $other1	){	$other1Akom = $costAkom;	}
		if($curConsm == $other1	){	$other1Consm = $costConsm;	}
		if($curOther == $other1	){	$other1Other = $costOther;	}
		
		$other1Total = $other1Tunj + $other1Trans + $other1Akom + $other1Consm + $other1Other ;
		echo "other1: ".$other1Tunj." ".$other1Trans." ".$other1Akom." ".$other1Consm." ".$other1Other." = ".$other1Total."<br/>";
	}
	
	// HITUNG OTHER2 Currency
	if($other2 != "")
	{
		$other2Tunj = 0;
		$other2Trans = 0;
		$other2Akom = 0;
		$other2Consm = 0;
		$other2Other = 0;
		
		if($curTunj == $other2	){	$other2Tunj = $costTunj;	}
		if($curTrans == $other2	){	$other2Trans = $costTrans;	}
		if($curAkom == $other2	){	$other2Akom = $costAkom;	}
		if($curConsm == $other2	){	$other2Consm = $costConsm;	}
		if($curOther == $other2	){	$other2Other = $costOther;	}
		
		$other2Total = $other2Tunj + $other2Trans + $other2Akom + $other2Consm + $other2Other ;
		echo "other2: ".$other2Tunj." ".$other2Trans." ".$other2Akom." ".$other2Consm." ".$other2Other." = ".$other2Total."<br/>";
	}
	
	$dateBefore = $CSpj->detilReportDetil($detilId, "tgldetil");
	if($dateBefore == $dateFormat)
	{
		//update Database
		$CKoneksiSpj->mysqlQuery("UPDATE reportdetil SET lokasi = '".$lokasi."', curtunj = '".$curTunj."', costtunj = '".$costTunj."', kettunj = '".mysql_real_escape_string($_POST['ketTunj1'])."', curtrans = '".$curTrans."', costtrans = '".$costTrans."', kettrans = '".mysql_real_escape_string($_POST['ketTrans1'])."', curakomd = '".$curAkom."', costakomd = '".$costAkom."', ketakomd = '".mysql_real_escape_string($_POST['ketAkom1'])."', curkonsm = '".$curConsm."', costkonsm = '".$costConsm."', ketkonsm = '".mysql_real_escape_string($_POST['ketConsm1'])."', curlain = '".$curOther."', costlain = '".$costOther."', ketlain = '".mysql_real_escape_string($_POST['ketOther1'])."', idrtotal = '".$idrTotal."', usdtotal = '".$usdTotal."', othercur1total = '".$other1Total."', othercur2total = '".$other2Total."', pengikut = '".$folNya."', updusrdt = '".$CPublic->userWhoAct()."' WHERE detilid = ".$detilId." AND deletests=0;");
	}
	if($dateBefore != $dateFormat)
	{
		$urutanAkhir = $CSpj->urutanAkhirReportDetail($reportId, $dateFormat);
		$urutan = $urutanAkhir+1;
		
		//update Database
		$CKoneksiSpj->mysqlQuery("UPDATE reportdetil SET urutan = ".$urutan.", tgldetil = ".$dateFormat.", lokasi = '".$lokasi."', curtunj = '".$curTunj."', costtunj = '".$costTunj."', kettunj = '".$_POST['ketTunj1']."', curtrans = '".$curTrans."', costtrans = '".$costTrans."', kettrans = '".$_POST['ketTrans1']."', curakomd = '".$curAkom."', costakomd = '".$costAkom."', ketakomd = '".$_POST['ketAkom1']."', curkonsm = '".$curConsm."', costkonsm = '".$costConsm."', ketkonsm = '".$_POST['ketConsm1']."', curlain = '".$curOther."', costlain = '".$costOther."', ketlain = '".$_POST['ketOther1']."', idrtotal = '".$idrTotal."', usdtotal = '".$usdTotal."', othercur1total = '".$other1Total."', othercur2total = '".$other2Total."', pengikut = '".$folNya."', updusrdt = '".$CPublic->userWhoAct()."' WHERE detilid = ".$detilId." AND deletests=0;");
	}

	//update other currency
	$curOther1 = 0;
	if($other1 != "")
	{
		$curOther1 = $other1;
	}
	$curOther2 = 0;
	if($other2 != "")
	{
		$curOther2 = $other2;
	}
	// grand total count & update
	$idrGrandTotal = $CSpj->grandTotal("idrtotal", $reportId);
	$usdGrandTotal = $CSpj->grandTotal("usdtotal", $reportId);
	$other1GrandTotal = $CSpj->grandTotal("othercur1total", $reportId);
	$other2GrandTotal = $CSpj->grandTotal("othercur2total", $reportId);
	$CKoneksiSpj->mysqlQuery("UPDATE report SET idrgrandtotal = ".$idrGrandTotal.", usdgrandtotal = ".$usdGrandTotal.", othercur1grandtotal = ".$other1GrandTotal.", othercur2grandtotal = ".$other2GrandTotal.", othercur1 = ".$curOther1.", othercur2 = ".$curOther2." WHERE reportid = ".$reportId." AND deletests = 0;");
	
	// uang kembali count & update
	$idrDp = $CSpj->detilReport($reportId, "idrdp");
	if($CSpj->detilReport($reportId, "idrdp") == "")
	{
		$idrDp = 0;
	}
	$idrKembali = $idrDp - $idrGrandTotal;
	$usdDp = $CSpj->detilReport($reportId, "usddp");
	if($CSpj->detilReport($reportId, "usddp") == "")
	{
		$usdDp = 0;
	}
	$usdKembali = $usdDp - $usdGrandTotal;
	$CKoneksiSpj->mysqlQuery("UPDATE report SET idrtotalkembali = ".$idrKembali.", usdtotalkembali = ".$usdKembali." WHERE reportid = ".$reportId." AND deletests = 0;");
	
	//cek Other currency masih dipakai atau tidak
	//jika nilai 0, maka other currency dianggap tidak dipakai, dan dihapus.
	$CSpj->otherCurHapusTidak($other1GrandTotal, $other2GrandTotal, $reportId);
	
	//insert history
	$CHistory->updateLogSpj($userIdLogin, "Membuat detail report (reportid = <b>".$reportId."</b>, detilid = <b>".$detilId."</b>)");
}
?>
<script>
window.onload = function()
{
	doneWait();
}
	
function simpanDetil(tipe)
{
	var aksi = formDetil.aksi.value;
	var detilDate = formDetil.detilDate.value;
	var lokasi = formDetil.lokasi.value;
	
	var jmlTunj = formDetil.jmlTunj.value;
	//var jmlPjp = formDetil.jmlPjp.value;
	var jmlTrans = formDetil.jmlTrans.value;
	var jmlAkom = formDetil.jmlAkom.value;
	var jmlConsm = formDetil.jmlConsm.value;
	var jmlOther = formDetil.jmlOther.value;
	var maxField = Math.max(jmlTunj,jmlTrans,jmlAkom,jmlConsm,jmlOther);
	var folNya = formDetil.slcFol.value;
	
	//alert(folNya);return false;
	
	var img = '<img src=\"../../picture/exclamation-red.png\" width=\"14\"/>&nbsp;';

	if(detilDate.replace(/ /g,"") == "") // date tidak boleh kosong
	{
		document.getElementById('errorMsg').innerHTML = img+ "Date still empty";
		document.getElementById('detilDate').focus();
		return false;
	}
	
	if(lokasi.replace(/ /g,"") == "") // location tidak boleh kosong
	{
		document.getElementById('errorMsg').innerHTML = img+ "Location still empty";
		document.getElementById('lokasi').focus();
		return false;
	}
	
	var i;
	for(i=1; i<=maxField; i++)
	{
		var curTunj = $('#curTunj'+i).val();
		if(document.getElementById('curTunj'+i) != null && cekCurrency(curTunj) == "false")
		{
			document.getElementById('errorMsg').innerHTML = img+ "Maximum 4 currency";
			refreshCurrency();
			return false;
		}
		var costTunj = $('#costTunj'+i).val();
		var ketTunj = $('#ketTunj'+i).val();
		
		/*var curPjp = $('#curPjp'+i).val();
		var costPjp = $('#costPjp'+i).val();
		var ketPjp = $('#ketPjp'+i).val();*/
		
		var curTrans = $('#curTrans'+i+'').val();
		if(document.getElementById('curTrans'+i) != null && cekCurrency(curTrans) == "false")
		{
			document.getElementById('errorMsg').innerHTML = img+ "Maximum 4 currency";
			refreshCurrency();
			return false;
		}
		var costTrans = $('#costTrans'+i).val();
		var ketTrans = $('#ketTrans'+i).val();
		
		var curAkom = $('#curAkom'+i).val();
		if(document.getElementById('curAkom'+i) != null && cekCurrency(curAkom) == "false")
		{
			document.getElementById('errorMsg').innerHTML = img+ "Maximum 4 currency";
			refreshCurrency();
			return false;
		}
		var costAkom = $('#costAkom'+i).val();
		var ketAkom = $('#ketAkom'+i).val();
		
		var curConsm = $('#curConsm'+i).val();
		if(document.getElementById('curConsm'+i) != null && cekCurrency(curConsm) == "false")
		{
			document.getElementById('errorMsg').innerHTML = img+ "Maximum 4 currency";
			refreshCurrency();
			return false;
		}
		var costConsm = $('#costConsm'+i).val();
		var ketConsm = $('#ketConsm'+i).val();
		
		var curOther = $('#curOther'+i).val();
		if(document.getElementById('curOther'+i) != null && cekCurrency(curOther) == "false")
		{
			document.getElementById('errorMsg').innerHTML = img+ "Maximum 4 currency";
			refreshCurrency();
			return false;
		}
		var costOther = $('#costOther'+i).val();
		var ketOther = $('#ketOther'+i).val();
		
		if(cekKosong(curTunj,costTunj,ketTunj,i,'Tunj') == "tidakIsi")//validasi kosong
		{
			document.getElementById('errorMsg').innerHTML = img+ "Field "+i+" Daily Allowance still empty";
			document.getElementById('costTunj'+i).focus();
			return false;
		}
		if(cekKosong(curTunj,costTunj,ketTunj,i,'Tunj') == "kosong")
		{
			if(cekKosong(curTrans,costTrans,ketTrans,i,'Trans') == "tidakIsi")
			{
				document.getElementById('errorMsg').innerHTML = img+ "Field "+i+" Transportation still empty";
				document.getElementById('costTrans'+i).focus();
				return false;
			}
			if(cekKosong(curTrans,costTrans,ketTrans,i,'Trans') == "kosong")
			{
				if(cekKosong(curAkom,costAkom,ketAkom,i,'Akom') == "tidakIsi")
				{
					document.getElementById('errorMsg').innerHTML = img+ "Field "+i+" Accommodation still empty";
					document.getElementById('costAkom'+i).focus();
					return false;
				}
				if(cekKosong(curAkom,costAkom,ketAkom,i,'Akom') == "kosong")
				{
					if(cekKosong(curConsm,costConsm,ketConsm,i,'Consm') == "tidakIsi")
					{
						document.getElementById('errorMsg').innerHTML = img+ "Field "+i+" Conusmption still empty";
						document.getElementById('costConsm'+i).focus();
						return false;
					}
					if(cekKosong(curConsm,costConsm,ketConsm,i,'Consm') == "kosong")
					{
						if(cekKosong(curOther,costOther,ketOther,i,'Other') == "tidakIsi")
						{
							document.getElementById('errorMsg').innerHTML = img+ "Field "+i+" Other still empty";
							document.getElementById('costOther'+i).focus();
							return false;
						}
						if(cekKosong(curOther,costOther,ketOther,i,'Other') == "kosong")
						{
							document.getElementById('errorMsg').innerHTML = img+ "Please fill at least one of all cost type";
							return false;
						}
					}
				}
			}
		}
		
		if(document.getElementById('curTunj'+i) != null)
		{
			if(cekCurCost(curTunj, costTunj, i, 'Tunj') == "terminated")
			{
				return false;
			}
		}
		/*if(document.getElementById('curPjp'+i) != null)
		{
			if(cekCurCost(curPjp, costPjp, i, 'Pjp') == "terminated")
			{
				return false;
			}
		}*/
		if(document.getElementById('curTrans'+i) != null)
		{
			if(cekCurCost(curTrans, costTrans, i, 'Trans') == "terminated")
			{
				return false;
			}
		}
		if(document.getElementById('curAkom'+i) != null)
		{
			if(cekCurCost(curAkom, costAkom, i, 'Akom') == "terminated")
			{
				return false;
			}
		}
		if(document.getElementById('curConsm'+i) != null)
		{
			if(cekCurCost(curConsm, costConsm, i, 'Consm') == "terminated")
			{
				return false;
			}
		}
		if(document.getElementById('curOther'+i) != null)
		{
			if(cekCurCost(curOther, costOther, i, 'Other') == "terminated")
			{
				return false;
			}
		}
	}
	
	var answer  = confirm("Are you sure want to save?");
	if(answer)
	{
		pleaseWait();
		formDetil.submit();
	}
	else
	{	refreshCurrency(); 
		return false;	
	}
}

function cekKosong(cur,cost,ket,i,nama)
{
	var hasil;
	if((cur == "" || document.getElementById('cur'+nama+i) == null) && (cost == "" || document.getElementById('cost'+nama+i) == null) && (ket == "" || document.getElementById('ket'+nama+i) == null))
	{
		hasil = "kosong"; 
	}
	if(i > 1)
	{
		if(cur == "" && cost == "" && ket == "")
		{
			hasil = "tidakIsi"; 
		}
	}
	
	return hasil;
}

function cekCurCost(cur, cost, i, field)
{
	var hasil;
	var img = '<img src=\"../../picture/exclamation-red.png\" width=\"14\"/>&nbsp;';
	var note;
	if(field == "Tunj"){ note = "Daily Allowance";	}
	//if(field == "Pjp"){ note = "PJP2U";	}
	if(field == "Trans"){ note = "Transportation";	}
	if(field == "Akom"){ note = "Accommodation";	}
	if(field == "Consm"){ note = "Consumption";	}
	if(field == "Other"){ note = "Other";	}
	
	if(cur != "")
	{
		if(cost.replace(/ /g,"") == "") // cost tidak boleh kosong
		{
			document.getElementById('errorMsg').innerHTML = img+ "Cost "+note+" still empty";
			document.getElementById('cost'+field+i).focus();
			hasil = "terminated";
		}
	}
	else if(cost != "")
	{
		if(cur.replace(/ /g,"") == "") // currency tidak boleh kosong
		{
			document.getElementById('errorMsg').innerHTML = img+ "Currency "+note+" still empty";
			document.getElementById('cur'+field+i).focus();
			hasil = "terminated";
		}
	}
	else
	{
		hasil = "accepted";
	}

	return hasil;
}

function setup(id)
{
	var decimalSeparator = ".";
    var groupSeparator = ",";
		
	var numParser1 = new NumberParser(2, decimalSeparator, groupSeparator, true);
    var numMask1 = new NumberMask(numParser1, id, 9);
}
function setup1(id)
{
	$('#'+id+'').alphanum();
}

function ajaxDinamicField(aksi, jml, field, halaman, fieldJml, k)
{
	/*aksi = + atau -
	field = jenis field, ex: daily allowance, PJP2U, etc
	jml = jml field yg ada
	halaman = id Div
	fieldJml = value jumlah field*/
	
	if(aksi == "tambah")
	{
		if(cekField(jml, field) == "proses")
		{
			var jmlCount = parseInt(jml)+1;
			$('#'+fieldJml+'').val(jmlCount);
			if(jml != 1)
			{
				var vars = {};
				var i;
				for(i=2;i<=jml;i++)//ambil value field Currency, Cost, dan Keterangan
				{
					vars['cur'+field+i] = $('#cur'+field+i+'').val();
					vars['cost'+field+i] = $('#cost'+field+i+'').val();
					vars['ket'+field+i] = $('#ket'+field+i+'').val();
				}
			}
			
			$.post( 
				"../halPost.php",
				{	halaman: field, jml: jmlCount, vars: vars	},
				function(data){
					$('#'+halaman+'').html(data);	
				}
			);
		}
		if(cekField(jml, field) == "terminated")
		{
			alert('Fill the blank field for add more, please');
		}
	}
	if(aksi == "kurang")
	{
		if(jml != 1)
		{
			var jmlCount = parseInt(jml)-1;
			$('#'+fieldJml+'').val(jmlCount);
			if(jml > 2)
			{
				var vars = {};
				var i;
				for(i=2;i<=jml;i++)//ambil value field Currency, Cost, dan Keterangan
				{
					if(i >= k)
					{
						var j = i+1;
						vars['cur'+field+i] = $('#cur'+field+j+'').val();
						vars['cost'+field+i] = $('#cost'+field+j+'').val();
						vars['ket'+field+i] = $('#ket'+field+j+'').val();
					}
					else
					{
						vars['cur'+field+i] = $('#cur'+field+i+'').val();
						vars['cost'+field+i] = $('#cost'+field+i+'').val();
						vars['ket'+field+i] = $('#ket'+field+i+'').val();
					}
				}
			}
		}
		$.post( 
			"../halPost.php",
			{	halaman: field, jml: jmlCount, vars: vars	},
			function(data){
				$('#'+halaman+'').html(data);	
			}
		);
	}
}

function cekField(i, field)
{
	var cur = $('#cur'+field+i).val();
	var cost = $('#cost'+field+i).val();
	var ket = $('#ket'+field+i).val();
	
	var hasil = "proses";
	if(cur == "" && cost == "" && ket == "")
	{
		hasil = "terminated";
	}
	return hasil;
}

function ajaxTunjangan(curency)
{
	var gol = $('#gol').val();
	var idName = $('#slcFol').val();
	if(idName == "0")
	{
		alert("name empty..!!");
		$('#curTunj1').val("");
		return false;
	}
	$.post( 
		"../halPost.php",
		{	halaman: 'cariTunjangan', curency: curency, gol:gol,idName : idName	},
		function(data){
			$('#divCostTunj').html(data);	
		}
	);
}

function ajaxTransport(curency)
{
	var gol = $('#gol').val();
	var idName = $('#slcFol').val();
	if(idName == "0")
	{
		alert("name empty..!!");
		$('#curTrans1').val("");
		return false;
	}
	$.post( 
		"../halPost.php",
		{	halaman: 'cariTransport', curency: curency, gol:gol,idName : idName	},
		function(data){
			$('#divCostTrans').html(data);	
		}
	);
}

function ajaxConsumtion(curency)
{
	var gol = $('#gol').val();
	var idName = $('#slcFol').val();
	if(idName == "0")
	{
		alert("name empty..!!");
		$('#curConsm1').val("");
		return false;
	}
	
	$.post('../halPost.php',
		{ halaman : "cariConsumtion", curency: curency, gol:gol,idName : idName },
			function(data) 
			{	
				$("#costConsm1").val(data.tunCons);
				$("#ketConsm1").val(data.ketCons);
			},
		"json"
	);
}

function refreshCurrency()
{
	$('#other1').val("");
	$('#other2').val("");
}

function cekCurrency(currency)//tambah dan cek other currency (selain IDR dan USD)
{
	var flag = "true";
	var other1 = $('#other1').val();
	var other1db = $('#other1db').val();
	if(other1db != "")
	{
		$('#other1').val($('#other1db').val());
	}
	
	var other2 = $('#other2').val();
	var other2db = $('#other2db').val();
	if(other2db != "")
	{
		$('#other2').val($('#other2db').val());
	}
	
	if(currency != 01 && currency != 02 && currency != "")
	{
		if(other1 == "" && other2 == "")
		{
			$('#other1').val(currency);
		}
		else if(other1 != "" && other2 != "")
		{
			if(currency != other1 && currency != other2)
			{
				flag = "false";//alert('MAX Currency is 4');
			}
		}
		else
		{
			if(other1 != "" && other2 == "")//jika other1 sudah terisi
			{
				if(other1 != currency)
				{
					$('#other2').val(currency);
				}
			}
			
			if(other1 == "" && other2 != "")//jika other2 sudah terisi
			{
				if(other2 != currency)
				{
					$('#other1').val(currency);
				}
			}
		}
	}
	
	return flag;
}

setInterval(function(){ $('blink').each( function(){ $(this).css('visibility' , $(this).css('visibility') === 'hidden' ? '' : 'hidden') } ); }, 400);
</script>
<body bgcolor="#F8F8F8">
<div id="loaderImg" style="visibility:visible;width:570px;" class="pleaseWait2">
    <div class="isiPleaseWait tabelBorderAll">
    	&nbsp;Please Wait...&nbsp;<img src="../picture/loading (115).gif" height="12"/>&nbsp;
    </div>
</div>
<center>
<input type="hidden" id="gol" name="gol" value="<?php echo $CEmployee->gol($ownerEmpno)?>"/>
<input type="hidden" id="other1db" name="other1db" value="<?php echo $otherCur1;?>"/>
<input type="hidden" id="other2db" name="other2db" value="<?php echo $otherCur2;?>"/>
<!--<input type="hidden" id="gol" name="gol" value="<?php echo $CEmployee->gol($userEmpNo);?>"/>-->
<table cellpadding="0" cellspacing="0" border="0" width="99%" height="99%" align="center">
<tr valign="top" style="width:100%;">
	<td align="left">
    	<!--<span class="teksLvlFolder" style="color:#666;font-size:14px;"><b></b></span>-->
    </td>
	<td align="right">
    	<span class="teksLvlFolder" style="color:#999;font-size:16px;font-style:italic;"><b>:: <?php echo ucfirst($aksiGet);?> Report Detail ::</b></span>
    </td>
</tr>

<tr valign="top">
	<td colspan="2" class="spjTdMyFolder" bgcolor="#FFFFFF" valign="top" align="center">
    <div style="width:99%;height:344px;overflow:scroll;overflow-x: hidden;top: expression(offsetParent.scrollTop);">
    <form action="" name="formDetil" id="formDetil" method="post" enctype="multipart/form-data">
    <table cellpadding="0" cellspacing="5" width="98%" class="spjFormInput" border="0">
        <tr><td height="5" colspan="2"></td></tr>
        <tr width="3%" valign="top">
            <td height="28px" width="20%" align="left" valign="middle" title="Tanggal dinas">Date</td>
            <td align="left">
                <select id="detilDate" name="detilDate" class="elementMenu" style="width:91%">
                    <option>--PLEASE SELECT--</option>
                    <?php 
                        $formId = $CSpj->detilReport($reportIdGet, "formid");
                        
                        $formDate = $CSpj->detilForm($formId, "datefrom");
                        $startDate = substr($formDate,0,4)."-".substr($formDate,4,2)."-".substr($formDate,6,2);
                        
						$cekExtend = $CSpj->detilForm($formId, "extend");
						$toDate = $CSpj->detilForm($formId, "dateto");
						
						if($cekExtend != "0")
						{
							$toDate = $CSpj->dateAfterExtend($toDate, $cekExtend);
						}
                        $endDate = substr($toDate,0,4)."-".substr($toDate,4,2)."-".substr($toDate,6,2);
                        echo $CSpj->dateRange($startDate, $endDate, $tglDetil, $CPublic);
                    ?>
                </select>
            </td>
            <!--<td align="left" colspan="5">
                <table cellpadding="0" cellspacing="0" width="98%" border="0">
                <tr>
                    <td width="10%"><input type="text" class="elementDefault" id="detilDate" name="detilDate" style="width:80px;height:15px;color:#333;font-weight:bold;font-size:12px;text-align:center;" readonly <?php echo $dateFormat;?>>
                    </td>
                    <td width="90%">
                     &nbsp;<img src="../../picture/calendar.gif" width="30" height="25" class="spjKalender" title="Select Date" onclick="displayCalendar(document.getElementById('detilDate'),'dd/mm/yyyy',this, '', '', '193', '92');"/>
                    </td>
                </tr>
                </table>
            </td>-->
        </tr>
        <tr width="3%" valign="top">
            <td height="28px" width="20%" align="left" valign="middle" title="Tanggal dinas">Name</td>
            <td align="left">
                <select id="slcFol" name="slcFol" class="elementMenu" style="width:91%">
                    <option value="0">--PLEASE SELECT--</option>
                    <?php echo $CSpj->menuUser($db,$idEditFollower); ?>
                </select>
            </td>
        </tr>
        <!-- Lokasi -->
        <tr valign="top">
            <td height="28px" align="left" width="20%" title="Lokasi Dinas">Location</td>
            <td align="left">
                <input type="text" class="elementDefault" id="lokasi" name="lokasi" style="width:88%;height:15px;" title="Tujuan dinas"/ <?php echo $valLoc;?>>
            </td>
        </tr>
        <!-- Tabel -->
        <tr>
            <td colspan="2">
            <table cellpadding="0" cellspacing="0" border="0" width="100%" class="spjTdMyFolder" bgcolor="#F8F8F8">
                <tr valign="middle" style="border:1px solid #666;font-weight:bold;">
                    <td height="28px" width="19%" align="center">Type of Cost</td>
                    <td width="15%" align="center">Currency</td>
                    <td width="23%" align="center">Amount</td>
                    <td width="35%" align="center">Description</td>
                    <td width="8%" align="center">More</td>
                </tr>
            </table>
            </td>
        </tr>
        <!-- Tunjangan -->
        <tr>
            <td colspan="2">
            <!--<div style="width:100%">-->
            <table cellpadding="0" cellspacing="2" border="0" width="100%">
                <tr>
                    <td height="28px" width="22%" align="left" title="Tunjangan Harian">Daily Allowance</td>
                    <td width="10%" align="left">
                        <select class="elementDefault" id="curTunj1" name="curTunj1" title="Mata uang" onChange="ajaxTunjangan(this.value);" style="width:85px;">
                            <option value="">--Select--</option>
                        <?php
                        if($aksiGet == "new")
                        {
							$query = $CKoneksiSpj->mysqlQuery("SELECT * FROM currency WHERE active = 'Y' ORDER BY currencyid ASC LIMIT 2;");
							while($row = $CKoneksiSpj->mysqlFetch($query))
							{
								$html.='<option value="'.$row['currencyid'].'">'.$row['currencyname'].'</option>';
							}
                        }
                        if($aksiGet == "edit")
                        {
                            //$html = $CSpj->currency($CSpj->detilReportDetil($detilIdGet, "curtunj"));
							$query = $CKoneksiSpj->mysqlQuery("SELECT * FROM currency WHERE active = 'Y' ORDER BY currencyid ASC LIMIT 2;");
							while($row = $CKoneksiSpj->mysqlFetch($query))
							{
								$sel = "";
								if($CSpj->detilReportDetil($detilIdGet, "curtunj") == $row['currencyid'])
								{
									$sel = "selected";
								}
								$html.='<option value="'.$row['currencyid'].'" '.$sel.'>'.$row['currencyname'].'</option>';
							}
                        }
                        echo $html;
                        ?>
                        </select>
                    </td>
                    <td width="21%" align="left">
                    <div id="divCostTunj">
                        <input type="text" class="elementDefault" id="costTunj1" name="costTunj1" style="width:89%;height:16px;text-align:right;"  onFocus="setup('costTunj1');" onKeyUp="setup('costTunj1');" title="Jumlah tunjangan" <?php echo $costTunj;?> readonly="true"/>
                        </div>
                    </td>
                    <td width="39%" align="right">
                        <input maxlength="49" type="text" class="elementDefault" id="ketTunj1" name="ketTunj1" style="width:94%;height:16px;"  onFocus="setup1('ketTunj1');" onKeyUp="setup1('ketTunj1');" title="Keterangan tunjangan" <?php echo $ketTunj;?>/>
                    </td>
                    <td width="8%" align="center">
                        <!--<button type="button" <?php echo $btnCls.$dis;?> onclick="ajaxDinamicField('tambah', $('#jmlTunj').val(), 'Tunj', 'divTunj', 'jmlTunj', '')" style="width:30px;height:26px;border-radius:3px;" title="Add field">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                            <tr>
                                <td align="left"><img src="../picture/plus.png" height="15px"/></td>
                            </tr>
                            </table>
                        </button>-->
                        <input type="hidden" id="jmlTunj" name="jmlTunj" value="1"/>
                    </td>
                </tr>
                <tr><td colspan="5">
                	<div id="divTunj"></div>
                </td></tr>
        
        <!-- PJP2U -->
                <!--<tr>
                    <td height="28px" width="22%" align="left" title="PJP2U Dinas">PJP2U</td>
                    <td align="left" width="10%">
                        <select class="elementDefault" id="curPjp1" name="curPjp1" title="Mata uang" style="width:85px;">
                            <option value="">--Select--</option>
                        <?php
                       /* if($aksiGet == "new")
                        {
                            $html='<option value="idr">IDR</option>
                            <option value="usd">USD</option>';
                        }
                        if($aksiGet == "edit")
                        {
                            $html = $CSpj->currency($CSpj->detilReportDetil($detilIdGet, "curpjp"));
                        }
                        echo $html;*/
                        ?>
                        </select>
                    </td>
                    <td width="21%">
                        <input type="text" class="elementDefault" id="costPjp1" name="costPjp1" style="width:89%;height:16px;text-align:right;" onFocus="setup('costPjp1');" onKeyUp="setup('costPjp1');" title="Jumlah PJP2U" <?php //echo $costPjp;?>/>
                    </td>
                    <td width="39%" align="right">
                        <input maxlength="49" type="text" class="elementDefault" id="ketPjp1" name="ketPjp1" style="width:94%;height:16px;" onFocus="setup1('ketPjp1');" onKeyUp="setup1('ketPjp1');" title="Keterangan PJP2U" <?php e//cho $ketPjp;?>/>
                    </td>
                    <td width="8%" align="center">
                        <button type="button" <?php //echo $btnCls.$dis;?> onclick="ajaxDinamicField('tambah', $('#jmlPjp').val(), 'Pjp', 'divPjp', 'jmlPjp', '')" style="width:30px;height:26px;border-radius:3px;" title="Add field">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                            <tr>
                                <td align="center"><img src="../picture/plus.png" height="15px"/></td>
                            </tr>
                            </table>
                        </button>
                        <input type="hidden" id="jmlPjp" name="jmlPjp" value="1"/>
                    </td>
                </tr>
                <tr><td colspan="5">
                    <div id="divPjp"></div>
                </td></tr>-->
                
        <!-- transportasi -->
        
                <tr>
                    <td height="28px" width="22%" align="left" title="Transportasi dinas">Transportation</td>
                    <td align="left" width="10%">
                        <select class="elementDefault" id="curTrans1" name="curTrans1" title="Mata uang" onChange="ajaxTransport(this.value);" style="width:85px;">
                            <option value="">--Select--</option>
                        <?php
                        if($aksiGet == "new")
                        {
                            $html = $CSpj->currency("");
                        }
                        if($aksiGet == "edit")
                        {
                            $html = $CSpj->currency($CSpj->detilReportDetil($detilIdGet, "curtrans"));
                        }
                        echo $html;
                        ?>
                        </select>
                    </td>
                    <td width="21%" id="divCostTrans">
                        <input type="text" class="elementDefault" id="costTrans1" name="costTrans1" style="width:89%;height:16px;text-align:right;" onFocus="setup('costTrans1');" onKeyUp="setup('costTrans1');" title="Jumlah biaya transportasi" <?php echo $costTrans;?>/>
                    </td>
                    <td width="39%" align="right">
                        <input maxlength="49" type="text" class="elementDefault" id="ketTrans1" name="ketTrans1" style="width:94%;height:16px;" onFocus="setup1('ketTrans1');" onKeyUp="setup1('ketTrans1');" title="Keterangan transportasi" <?php echo $ketTrans;?>/>
                    </td>
                    <td width="8%" align="center">
                        <button type="button" <?php echo $btnCls.$dis;?> onclick="ajaxDinamicField('tambah', $('#jmlTrans').val(), 'Trans', 'divTrans', 'jmlTrans', '')" style="width:30px;height:26px;border-radius:3px;" title="Add field">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                            <tr>
                                <td align="center"><img src="../picture/plus.png" height="15px"/></td>
                            </tr>
                            </table>
                        </button>
                        <input type="hidden" id="jmlTrans" name="jmlTrans" value="1"/>
                    </td>
                </tr>
				<tr><td colspan="5">
            		<div id="divTrans"></div>
            	</td></tr>
        <!-- Akomodasi -->
       			<tr>
                    <td height="28px" width="22%" align="left" title="Akomodasi dinas">Accomodation</td>
                    <td align="left" width="10%">
                        <select class="elementDefault" id="curAkom1" name="curAkom1" title="Mata uang" style="width:85px;">
                            <option value="">--Select--</option>
                        <?php
                        if($aksiGet == "new")
                        {
                            $html = $CSpj->currency("");
                        }
                        if($aksiGet == "edit")
                        {
                            $html = $CSpj->currency($CSpj->detilReportDetil($detilIdGet, "curakomd"));
                        }
                        echo $html;
                        ?>
                        </select>
                    </td>
                    <td width="21%">
                        <input type="text" class="elementDefault" id="costAkom1" name="costAkom1" style="width:89%;height:16px;text-align:right;" onFocus="setup('costAkom1');" onKeyUp="setup('costAkom1');" title="Jumlah biaya akomodasi" <?php echo $costAkomd;?>/>
                    </td>
                    <td width="39%" align="right">
                        <input maxlength="49" type="text" class="elementDefault" id="ketAkom1" name="ketAkom1" style="width:94%;height:16px;" onFocus="setup1('ketAkom1');" onKeyUp="setup1('ketAkom1');" title="Keterangan akomodasi" <?php echo $ketAkomd;?>/>
                    </td>
                    <td width="8%" align="center">
                        <button type="button" <?php echo $btnCls.$dis;?> onclick="ajaxDinamicField('tambah', $('#jmlAkom').val(), 'Akom', 'divAkom', 'jmlAkom', '')" style="width:30px;height:26px;border-radius:3px;" title="Add field">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                            <tr>
                                <td align="center"><img src="../picture/plus.png" height="15px"/></td>
                            </tr>
                            </table>
                        </button>
                        <input type="hidden" id="jmlAkom" name="jmlAkom" value="1"/>
                    </td>
                </tr>
            	<tr><td colspan="5">
            		<div id="divAkom"></div>
            	</td></tr>
        <!-- Konsumsi -->
      			<tr>
                    <td height="28px" width="22%" align="left" title="Konsumsi dinas">Consumption</td>
                    <td align="left" width="10%">
                        <select class="elementDefault" id="curConsm1" name="curConsm1" title="Mata uang" onChange="ajaxConsumtion(this.value);" style="width:85px;">
                            <option value="">--Select--</option>
                        <?php
                        if($aksiGet == "new")
                        {
                            $html = $CSpj->currency("");
                        }
                        if($aksiGet == "edit")
                        {
                            $html = $CSpj->currency($CSpj->detilReportDetil($detilIdGet, "curkonsm"));
                        }
                        echo $html;
                        ?>
                        </select>
                    </td>
                    <td width="21%">
                        <input type="text" class="elementDefault" id="costConsm1" name="costConsm1" style="width:89%;height:16px;text-align:right;" onFocus="setup('costConsm1');" onKeyUp="setup('costConsm1');" title="Jumlah biaya konsumsi" <?php echo $costKonsm;?>/>
                    </td>
                    <td width="39%" align="right">
                        <input maxlength="49" type="text" class="elementDefault" id="ketConsm1" name="ketConsm1" style="width:94%;height:16px;" onFocus="setup1('ketConsm1');" onKeyUp="setup1('ketConsm1');" title="Keterangan konsumsi" <?php echo $ketKonsm;?>/>
                    </td>
                    <td width="8%" align="center">
                        <button type="button" <?php echo $btnCls.$dis;?> onclick="ajaxDinamicField('tambah', $('#jmlConsm').val(), 'Consm', 'divConsm', 'jmlConsm', '')" style="width:30px;height:26px;border-radius:3px;" title="Add field">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                            <tr>
                                <td align="center"><img src="../picture/plus.png" height="15px"/></td>
                            </tr>
                            </table>
                        </button>
                        <input type="hidden" id="jmlConsm" name="jmlConsm" value="1"/>
                    </td>
                </tr>
                <tr><td colspan="5">
                	<div id="divConsm"></div>
                </td></tr>
        <!-- Lainnya -->
        		<tr>
                    <td height="28px" width="22%" align="left" title="Keperluan tambahan">Other</td>
                    <td align="left" width="10%">
                        <select class="elementDefault" id="curOther1" name="curOther1" title="Mata uang" style="width:85px;">
                            <option value="">--Select--</option>
                        <?php
                        if($aksiGet == "new")
                        {
                            $html = $CSpj->currency("");
                        }
                        if($aksiGet == "edit")
                        {
                            $html = $CSpj->currency($CSpj->detilReportDetil($detilIdGet, "curlain"));
                        }
                        echo $html;
                        ?>
                        </select>
                    </td>
                    <td width="21%">
                        <input type="text" class="elementDefault" id="costOther1" name="costOther1" style="width:89%;height:16px;text-align:right;" onFocus="setup('costOther1');" onKeyUp="setup('costOther1');" title="Jumlah biaya konsumsi" <?php echo $costLain;?>/>
                    </td>
                    <td width="39%" align="right">
                        <input maxlength="49" type="text" class="elementDefault" id="ketOther1" name="ketOther1" style="width:94%;height:16px;" onFocus="setup1('ketOther1');" onKeyUp="setup1('ketOther1');" title="Keterangan tambahan" <?php echo $ketLain;?>/>
                    </td>
                    <td width="8%" align="center">
                        <button type="button" <?php echo $btnCls.$dis;?> onclick="ajaxDinamicField('tambah', $('#jmlOther').val(), 'Other', 'divOther', 'jmlOther', '')" style="width:30px;height:26px;border-radius:3px;" title="Add field">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                            <tr>
                                <td align="center"><img src="../picture/plus.png" height="15"/></td>
                            </tr>
                            </table>
                        </button>
                        <input type="hidden" id="jmlOther" name="jmlOther" value="1"/>
                    </td>
        		</tr>
                <tr><td colspan="5">
                	<div id="divOther"></div>
                </td></tr>
			</table>
            </td>
        </tr>
        
        <tr>
            <td>
            	<input type="hidden" id="other1" name="other1"/>
				<input type="hidden" id="other2" name="other2"/>
                <input type="hidden" id="aksi" name="aksi" value="<?php echo $aksiGet;?>"/>
                <input type="hidden" id="reportId" name="reportId" value="<?php echo $_GET['reportId'];?>"/>
                <input type="hidden" id="detilId" name="detilId" value="<?php echo $_GET['detilId'];?>"/>
            </td>
            <td align="left" valign="middle"></td>
        </tr>
    </table>
    </form>
    </div>
    </td>
</tr>
<tr valign="top">
	<td colspan="2" bgcolor="#FFFFFF" valign="middle" align="center">
    	<blink><span id="errorMsg" class="errorMsg"></span></blink>
    </td>
</tr>
<tr valign="top">
	<td colspan="2" bgcolor="#FFFFFF" height="35" valign="middle" align="center">
       &nbsp;<button class="spjBtnStandar" id="btnNewFolder" onclick="parent.close();" style="width:72px;height:25px;" title="Close Window">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                <tr>
                    <td align="right" width="17"><img src="../picture/control-power.png" height="15"/> </td>
                    <td align="center">CLOSE</td>
                </tr>
                </table>
            </button>
       &nbsp;<button class="spjBtnStandar" id="btnNewFolder" onclick="simpanDetil(); return false;" style="width:77px;height:25px;" title="Save as draft form SPJ">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                <tr>
                    <td align="right" width="17"><img src="../picture/disk.png" height="15"/> </td>
                    <td align="center">SAVE</td>
                </tr>
                </table>
            </button>
    </td>
</tr>
</table>

</center>
</body>
<script language="javascript">
<?php
if($aksiPost == "new" || $aksiPost == "edit")
{
	if($aksiPost == "new")
	{
		$report = "Detail Report succesfully save";	
	}
	if($aksiPost == "edit")
	{
		$report = "Detail Report succesfully edit";	
	}
	echo "parent.exit();
		  parent.report('".$report."');
		  parent.refresh('Y','Y');
		  parent.klikTr('".$trActiveGet."');";
}
?>
</script>
</HTML>	