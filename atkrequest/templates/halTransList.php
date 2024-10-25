<?php
require_once("../../config.php");
require_once("../configAtk.php");

$tglServer = $CPublic->tglServer();
$tgl =  substr($tglServer,6,2);
$bln =  substr($tglServer,4,2);
$thn =  substr($tglServer,0,4);

$date = $CPublic->bulanSetengah($bln, "eng")." ".$tgl.", ".$thn;
$dateTime = str_replace("-","/",$CPublic->waktuSek());

if($aksiGet == "cancel")
{
	$transIdGet = $_GET['transId'];
	$userId = $CReqAtk->atkTrans("ownerid", $transIdGet);
	
	$tglTrans = $CReqAtk->atkTrans("tgl", $transIdGet);
	$blnTrans = $CReqAtk->atkTrans("bln", $transIdGet);
	$thnTrans = $CReqAtk->atkTrans("thn", $transIdGet);
	$dateTrans = $CPublic->bulanSetengah($blnTrans, "eng")." ".$tglTrans.", ".$thnTrans;
	
	$CKoneksiAtk->mysqlQuery("UPDATE trans SET canusrdt='".$CPublic->userWhoAct()."',cancelsts=1 WHERE transid=".$transIdGet.";");
	
	$CHistory->updateLogReqAtk($userIdLogin, "Cancel Transaction (transid = <b>".$transIdGet."</b>)");
	
// ========= NOTIFIKASI ===================================================================================================	
	// Notif Email ke user
	if($CLogin->notification($db, "notifemail", $userId) == "Y") 
	{
		$emailKeUsr = $CReqAtk->detilLoginAtk($userId, "useremail", $db)."@andhika.com";
		$CNotif->emailCancelTrans("Approved", $CReqAtk, $transIdGet, $emailKeUsr, $dateTrans, $db, $link);
	}
	//variabel notif desktop ke user
	$notes = "Your ATK order at ".$dateTrans." has been Canceled.";
	//Notif Desktop ke admin
	$CReqAtk->varNotifDesktop($userId, $koneksiOdbc, $koneksiOdbcId, $CPublic, $CLogin, $CNotif, $notes, $db);
}

if($aksiGet == "return")
{
	$transIdGet = $_GET['transId'];
	$query = $CKoneksiAtk->mysqlQuery("SELECT * FROM transdtl WHERE transid=".$transIdGet."");
	while($row = $CKoneksiAtk->mysqlFetch($query))
	{
		//echo $row['itemid']." | ".$row['transqty']."<br/>";
		$itemName = $CReqAtk->detilAtkItem("itemname", $row['itemid']);
		
		$sqlCopy = $CKoneksiAtk->mysqlQuery("INSERT INTO cart (ownerid,itemid,itemname,cartqty) VALUES (".$userIdLogin.",".$row['itemid'].",'".$itemName."',".$row['transqty'].")");
	}
	
	$CKoneksiAtk->mysqlQuery("UPDATE trans SET canusrdt='".$CPublic->userWhoAct()."',cancelsts=1 WHERE transid=".$transIdGet.";");
	$CHistory->updateLogReqAtk($userIdLogin, "Return Transaction to Cart(transid = <b>".$transIdGet."</b>)");
}

if($halamanGet == "approved")
{
	$transIdGet = $_GET['transId'];
	$trActive= $_GET['trActive'];
	$userId = $CReqAtk->atkTrans("ownerid", $transIdGet);
	
	$tglTrans = $CReqAtk->atkTrans("tgl", $transIdGet);
	$blnTrans = $CReqAtk->atkTrans("bln", $transIdGet);
	$thnTrans = $CReqAtk->atkTrans("thn", $transIdGet);
	$dateTrans = $CPublic->bulanSetengah($blnTrans, "eng")." ".$tglTrans.", ".$thnTrans;
	
	$query = $CKoneksiAtk->mysqlQuery("SELECT * FROM transdtl WHERE transid=".$transIdGet."");
	while($row = $CKoneksiAtk->mysqlFetch($query))
	{
		$detilId = $row['detilid'];
		$transQty = $row['transqty'];
		$aprvQty = $row['aprvqty'];
		
		if($aprvQty == "")
		{
			$CKoneksiAtk->mysqlQuery("UPDATE transdtl SET aprvqty='".$transQty."' WHERE detilid=".$detilId.";");
			transOut($CKoneksiAtk, $bln, $thn, $row['itemid'], $transQty);	//update stock barang	
		}
		if($aprvQty != "")
		{
			$CKoneksiAtk->mysqlQuery("UPDATE transdtl SET aprvqty='".$aprvQty."' WHERE detilid=".$detilId.";");
			transOut($CKoneksiAtk, $bln, $thn, $row['itemid'], $aprvQty);	//update stock barang
		}
	}
	$CKoneksiAtk->mysqlQuery("UPDATE trans SET status='Approved', aprvdate='".$CPublic->userWhoAct()."' WHERE transid=".$transIdGet.";");
	$CHistory->updateLogReqAtk($userIdLogin, "Approve Transaksi(transid = <b>".$transIdGet."</b>)");

// ========= NOTIFIKASI ===================================================================================================	
	// Notif Email ke user
	if($CLogin->notification($db, "notifemail", $userId) == "Y") 
	{
		$emailKeUsr = $CReqAtk->detilLoginAtk($userId, "useremail", $db)."@andhika.com";
		$CNotif->emailAprvTrans("Approved", $CReqAtk, $transIdGet, $emailKeUsr, $dateTrans, $db, $link);
	}
	//variabel notif desktop ke user
	$notes = "Your ATK order at ".$dateTrans." has been Approved by HRGA. Please, receive your order immediately.";
	//Notif Desktop ke user
	$CReqAtk->varNotifDesktop($userId, $koneksiOdbc, $koneksiOdbcId, $CPublic, $CLogin, $CNotif, $notes, $db);
	
	$minimStock = $CReqAtk->cekLessStock($bln, $thn);
	// cek stock yang lebih kecil dari minimal stock. jika ada, kirim notifikasi.
	if($minimStock > 0 ) 
	{
		//Notif Email ke Admin
		$emailKeAdmin = $CReqAtk->emailKe($db);
		if($emailKeAdmin != "")
		{
			$CNotif->emailLessStock($CReqAtk, $emailKeAdmin, $date, $bln, $thn, $minimStock);
		}
		
		//variabel notif desktop ke admin
		$notes = "There are some ATK items below Min. Stock. Please, check Andhika Portal.";
		//Notif Desktop ke admin
		$CReqAtk->varNotifDesktop("", $koneksiOdbc, $koneksiOdbcId, $CPublic, $CLogin, $CNotif, $notes, $db);
	}
}

if($halamanGet == "aprvRefund")
{
	$transIdGet = $_GET['transId'];
	$trActive= $_GET['trActive'];
	
	$CKoneksiAtk->mysqlQuery("UPDATE trans SET status='Approved', rfnddate='".$CPublic->userWhoAct()."' WHERE transid=".$transIdGet.";");
	$CHistory->updateLogReqAtk($userIdLogin, "Approve Refund Transaksi(transid = <b>".$transIdGet."</b>)");
}

if($halamanGet == "refund")
{
	$transIdGet = $_GET['transId'];
	$trActive= $_GET['trActive'];
	
	$CKoneksiAtk->mysqlQuery("UPDATE trans SET status='Refund' WHERE transid=".$transIdGet.";");
	$CHistory->updateLogReqAtk($userIdLogin, "Ubah status menjadi refund(transid = <b>".$transIdGet."</b>)");
}

if($halamanGet == "received")
{
	$transIdGet = $_GET['transId'];
	$trActive= $_GET['trActive'];
	$userId = $CReqAtk->atkTrans("ownerid", $transIdGet);
	
	$tglTrans = $CReqAtk->atkTrans("tgl", $transIdGet);
	$blnTrans = $CReqAtk->atkTrans("bln", $transIdGet);
	$thnTrans = $CReqAtk->atkTrans("thn", $transIdGet);
	$dateTrans = $CPublic->bulanSetengah($blnTrans, "eng")." ".$tglTrans.", ".$thnTrans;
	
	$CKoneksiAtk->mysqlQuery("UPDATE trans SET status='Completed', compdate='".$CPublic->userWhoAct()."' WHERE transid=".$transIdGet.";");
	$CHistory->updateLogReqAtk($userIdLogin, "Receive item untuk Status Completed Transaksi(transid = <b>".$transIdGet."</b>)");

// ========= NOTIFIKASI ===================================================================================================	
	//notif email ke User
	if($CLogin->notification($db, "notifemail", $userId) == "Y") 
	{
		$emailKeUsr = $CReqAtk->detilLoginAtk($userId, "useremail", $db)."@andhika.com";
		$CNotif->emailAprvTrans("Completed", $CReqAtk, $transIdGet, $emailKeUsr, $dateTrans, $db, $link);
	}
	//variabel notif desktop ke user
	$notes = "Your ATK order at ".$dateTrans." has been Completed.";
	$userEmpNo = $CReqAtk->detilLoginAtk($userId, "empno", $db);
	//Notif Desktop ke user
	$CReqAtk->varNotifDesktop($userId, $koneksiOdbc, $koneksiOdbcId, $CPublic, $CLogin, $CNotif, $notes, $db);
	
	//notif email ke Admin
	$emailKe = $CReqAtk->emailKe($db);
	if($emailKe != "")
	{
		$CNotif->emailAprvTrans("Completed", $CReqAtk, $transIdGet, $emailKe, $dateTrans, $db, $link);
	}
	//variabel notif desktop ke admin
	$user =$CReqAtk->detilLoginAtk($userId,"userfullnm",$db);
	$notes = $user." ATK order at ".$dateTrans." has been Completed.";
	//Notif Desktop ke admin
	$CReqAtk->varNotifDesktop("", $koneksiOdbc, $koneksiOdbcId, $CPublic, $CLogin, $CNotif, $notes, $db);
}

function transOut($CKoneksiAtk, $bln, $thn, $itemId, $qty)
{
	$sql = $CKoneksiAtk->mysqlQuery("SELECT stockid,stockout,stockall FROM stock WHERE itemid=".$itemId." AND stockmonth='".$bln."' AND stockyear=".$thn.";");
	while($r = $CKoneksiAtk->mysqlFetch($sql))
	{
		$stockOutInp = $r['stockout']+$qty;
		$stockAllInp = $r['stockall']-$qty;

		$CKoneksiAtk->mysqlQuery("UPDATE stock SET stockout=".$stockOutInp.", stockall=".$stockAllInp." WHERE stockid=".$r['stockid']." AND active = 'Y';");
	}	
}
?>

<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportaltes/css/main.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportaltes/css/archives.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../../js/main.js"></script>

<script language="javascript">
function onClickTr(trId, jmlRow, status, transId)
{	
	var idTrSeb = document.getElementById('idTrSeb').value;
	var idTdNameSeb = document.getElementById('idTdNameSeb').value;
	var idTdTglSeb = document.getElementById('idTdTglSeb').value;
	var user = parent.document.getElementById('tipeUser').value;
	var halaman = "";
	
	if(idTrSeb != "" || idTdNameSeb != "")
	{
		document.getElementById(idTrSeb).onmouseover = function onmouseover(){	this.style.backgroundColor='#D9EDFF';	}
		document.getElementById(idTrSeb).onmouseout = function onmouseout(){	this.style.backgroundColor='#FFFFFF';	}
		document.getElementById(idTrSeb).style.fontWeight='';
		document.getElementById(idTrSeb).style.backgroundColor='#FFFFFF';
		document.getElementById(idTrSeb).style.cursor = 'pointer';	
		document.getElementById(idTdNameSeb).style.fontWeight='';// FONT TIDAK BOLD UNTUK TD YANG DIPILIH
		document.getElementById(idTdTglSeb).style.fontWeight='';
	}
	
	document.getElementById('tr'+trId).onmouseout = '';
	document.getElementById('tr'+trId).onmouseover ='';
	document.getElementById('tr'+trId).style.fontWeight='';
	document.getElementById('tr'+trId).style.backgroundColor='#B0DAFF';
	document.getElementById('tr'+trId).style.cursor = 'default';
	document.getElementById('tr'+trId).style.fontSize='10px';
	document.getElementById('idTrSeb').value = 'tr'+trId;
	
	document.getElementById('tdName'+trId).style.fontWeight='bold'; // FONT BOLD UNTUK TD YANG DIPILIH
	document.getElementById('tdTgl'+trId).style.fontWeight='bold';
	document.getElementById('idTdNameSeb').value = 'tdName'+trId; // BERI ISI idTdNameSeb DENGAN ID TD YANG DIPILIH SEBELUMNYA
	document.getElementById('idTdTglSeb').value = 'tdTgl'+trId;
	parent.document.getElementById('status').innerHTML = status;
	
	if(user == "adminAtk")
	{
		var halaman = "read";
		if(status != "Completed")
		{
			parent.document.getElementById('btnCancel').disabled = false;
			parent.document.getElementById('btnCancel').className = 'btnStandar';
			
			parent.document.getElementById('btnApprv').disabled = false;
			parent.document.getElementById('btnApprv').className = 'btnStandar';
		}
		if(status == "Completed" || status == "Approved")
		{
			parent.document.getElementById('btnCancel').disabled = true;
			parent.document.getElementById('btnCancel').className = 'btnStandarDisabled';
			
			parent.document.getElementById('btnApprv').disabled = true;
			parent.document.getElementById('btnApprv').className = 'btnStandarDisabled';
		}
		if(status == "Approved")
		{
			parent.document.getElementById('btnRefund').disabled = false;
			parent.document.getElementById('btnRefund').className = 'btnStandar';
		}
		if(status != "Approved")
		{
			parent.document.getElementById('btnRefund').disabled = true;
			parent.document.getElementById('btnRefund').className = 'btnStandarDisabled';
		}
		if(status == "Refund")
		{
			parent.document.getElementById('btnCancel').disabled = true;
			parent.document.getElementById('btnCancel').className = 'btnStandarDisabled';
		}
	}
	
	if(user == "userAtk")
	{
		parent.document.getElementById('btnCancel').disabled = true;
		parent.document.getElementById('btnCancel').className = 'btnStandarDisabled';
		
		parent.document.getElementById('btnReturn').disabled = true;
		parent.document.getElementById('btnReturn').className = 'btnStandarDisabled';
		
		parent.document.getElementById('btnReceived').disabled = true;
		parent.document.getElementById('btnReceived').className = 'btnStandarDisabled';
		
		if(status == "Unread")
		{
			parent.document.getElementById('btnCancel').disabled = false;
			parent.document.getElementById('btnCancel').className = 'btnStandar';
			
			parent.document.getElementById('btnReturn').disabled = false;
			parent.document.getElementById('btnReturn').className = 'btnStandar';
		}
		if(status != "Unread")
		{
			parent.document.getElementById('btnCancel').disabled = true;
			parent.document.getElementById('btnCancel').className = 'btnStandarDisabled';
			
			parent.document.getElementById('btnReturn').disabled = true;
			parent.document.getElementById('btnReturn').className = 'btnStandarDisabled';
		}
		if(status == "Approved")
		{
			parent.document.getElementById('btnReceived').disabled = false;
			parent.document.getElementById('btnReceived').className = 'btnStandar';
			
		}
	}
	
	parent.document.getElementById('transId').value = transId ;
	parent.document.getElementById('trActive').value = trId ;
	parent.detailTrans(transId,halaman);
}

function aprvRefresh(id)
{
	document.getElementById('tr'+id).click();
}
</script>

<body onLoad="loadScroll('transList');" onUnload="saveScroll('transList');">

<table width="99%">
<input type="hidden" id="idTrSeb" name="idTrSeb">
<input type="hidden" id="idTdNameSeb" name="idTdNameSeb">
<input type="hidden" id="idTdTglSeb" name="idTdTglSeb">

<?php
$condition = "";
$cCncl = "cancelsts=0";
$cOwner = "";
if($adminAtk == "N")
{
	$cOwner = " ownerid=".$userIdLogin."";
	$cCncl = " AND cancelsts=0";
}
$condition = "WHERE ".$cOwner.$cCncl."";

if($halamanGet == "filter")
{
	$fThn = $_GET['fThn'];
	$fBln = $_GET['fBln'];
	$fTgl = $_GET['fTgl'];
	$fSts = $_GET['fSts'];
	$fNm = $_GET['fNm'];
	
	$condition = "";
	$cThn = "";
	$cBln = "";
	$cTgl = "";
	$cSts = "";
	$cOwner = "";
	
	if($fThn != "0000")
	{
		$cThn = " thn = ".$fThn."";
	}
	if($fBln != "00")
	{
		$cBln = " bln = ".$fBln."";
		if($fThn != "0000")
		{	$cBln = " AND bln = ".$fBln."";	}
	}
	if($fTgl != "00")
	{
		$cTgl = " tgl = ".$fTgl."";
		if($fThn != "0000" || $fBln != "00")
		{	$cTgl = " AND tgl = ".$fTgl."";	}
	}
	if($fSts != "all")
	{
		$cSts = " status = '".$fSts."'";
		if($fThn != "0000" || $fBln != "00" || $fTgl != "00")
		{	$cSts = " AND status = '".$fSts."'";	}
	}
	if($fNm != "00000")
	{
		$cOwner = " ownerid = '".$fNm."'";
		if($fThn != "0000" || $fBln != "00" || $fTgl != "00" || $fSts != "all")
		{	$cOwner = " AND ownerid = '".$fNm."'";	}
	}
	if($fThn != "0000" || $fBln != "00" || $fTgl != "00" || $fSts != "all" || $fNm != "00000")
	{
		$cCncl = " AND cancelsts=0";
	}
	
	if($adminAtk == "N")
	{
		$cOwner = " ownerid = ".$userIdLogin."";
		if($fThn != "0000" || $fBln != "00" || $fTgl != "00" || $fSts != "all")
		{	$cOwner = " AND ownerid = ".$userIdLogin."";	}
	}
	
	$condition = "WHERE ".$cThn.$cBln.$cTgl.$cSts.$cOwner.$cCncl."";
}

$i=1;
$query = $CKoneksiAtk->mysqlQuery("SELECT * FROM trans ".$condition." ORDER BY transid DESC");
$jmlRow = $CKoneksiAtk->mysqlNRows($query);
while($row = $CKoneksiAtk->mysqlFetch($query))
{	
	$transId = $row['transid'];
	//$transDate = $row['thn']."/".$row['bln']."/".$row['tgl'];
	$transDate = $CPublic->bulanSetengah($row['bln'], "eng")." ".$row['tgl'].", ".$row['thn'];
	$itemUsr = $CReqAtk->transDtItemName($row['transid']);
	
	$bO = "";
	$bT = "";
	if($thn == $row['thn'] && $bln == $row['bln'] && $tgl == $row['tgl'])
	{
		$bO = "<b>";
		$bT = "</b>";
	}
	
	$status = $row['status'];
	$stsDisp = "";
	$stsDisp1 = "";	
	if($status == "Unread")
	{
		$stsDisp = "background-image:url(../../picture/qhse_notif.png);background-repeat:no-repeat;background-position:top right;";
	}
	if($status == "Processed")
	{
		//$stsDisp = "background-color=#FF464A;";
		$stsDisp1 = "<div style=\"width:16px;height:14px;border:solid 1px #ccc;background-color:#FF464A;float:left;\">&nbsp;</div>";
	}
	if($status == "Approved")
	{
		//$stsDisp = "background-color=#F5FF46;";
		$stsDisp1 = "<div style=\"width:16px;height:14px;border:solid 1px #ccc;background-color:#F5FF46;float:left;\">&nbsp;</div>";
	}
	if($status == "Refund")
	{
		$stsDisp = "background-image:url(../../picture/arrow-return-180-left.png);background-repeat:no-repeat;background-position:top right;";
	}
	if($status == "Completed")
	{
		//$stsDisp = "background-color=#5EFF46;";
		$stsDisp1 = "<div style=\"width:16px;height:14px;border:solid 1px #ccc;background-color:#5EFF46;float:left;\">&nbsp;</div>";
	}
	
	if($adminAtk == "Y")
	{
		$display = $CReqAtk->detilLoginAtk($row['ownerid'], "userfullnm", $db);
	}
	if($adminAtk == "N")
	{
		$display = $CPublic->potongKarakter($itemUsr, "40");
	}
	
	$onClickTr = "onClickTr('".$i."', '".$jmlRow."','".$status."','".$transId."');";
?>

    <tr id="tr<?php echo $i;?>" onMouseOver="this.style.backgroundColor='#DDF0FF';" onMouseOut="this.style.backgroundColor='#FFFFFF';" onClick="<?php echo $onClickTr;?>">
        <td class="tdMyFolder">
            <table width="100%">
            <tr class="fontMyFolderList" height="17">
                <td width="7%" align="center" style="color:#000080;font-weight:bold;font-family:Tahoma;"><?php echo $i; ?></td>
                <td width="65%" id="tdName<?php echo $i;?>" title="">
                	<?php echo $bO.$display.$bT;?>&nbsp;
                </td>
                <td  id="tdTgl<?php echo $i;?>" width="23%" align="left">
                	<?php echo $bO.$transDate.$bT;?>
                </td>
                <td width="5%" align="center" style=" <?php echo $stsDisp;?>"><?php echo $stsDisp1;?></td>
            </tr>
            </table>
        </td>
    </tr>
<?php	
$i++;}
?>
</table>
</body>
<script language="javascript">
<?php
if($aksiGet == "cancel" || $aksiGet == "return")
{
	$teks = "";
	if($aksiGet == "cancel")
	{
		$teks = "Cancelled";
	}
	if($aksiGet == "return")
	{
		$teks = "Returned";
	}
	echo "parent.report('".$teks."');
		  parent.refreshPage();";
}

if($halamanGet == "approved" || $halamanGet == "received" || $halamanGet == "aprvRefund")
{
	$report = "";
	if($halamanGet == "approved")
	{
		$report = "Approved";
	}
	if($halamanGet == "received")
	{
		$report = "Received";
	}
	if($halamanGet == "aprvRefund")
	{
		$report = "re-Approved";
	}
	echo "aprvRefresh('".$trActive."');
		  parent.report('".$report."');";
}
if($halamanGet == "refund")
{
	echo "aprvRefresh('".$trActive."');";
}
?>
</script>