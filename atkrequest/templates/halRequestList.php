<?php
require_once('../../config.php');
require_once('../configAtk.php');

$tglServer = $CPublic->tglServer();
$tgl =  substr($tglServer,6,2);
$bln =  substr($tglServer,4,2);
$thn =  substr($tglServer,0,4);

if($aksiGet == "cancel")
{
	$reqIdGet = $_GET['reqId'];
	$ownerId = $CReqAtk->atkReq("ownerId", $reqIdGet);
	$tglReq = $CReqAtk->atkReq("tgl", $reqIdGet);
	$blnReq = $CReqAtk->atkReq("bln", $reqIdGet);
	$thnReq = $CReqAtk->atkReq("thn", $reqIdGet);
	$dateReq = $CPublic->bulanSetengah($blnReq, "eng")." ".$tglReq.", ".$thnReq;
	
	$CKoneksiAtk->mysqlQuery("UPDATE reqnew SET canusrdt='".$CPublic->userWhoAct()."',cancelsts=1 WHERE reqid=".$reqIdGet.";");
	$CHistory->updateLogReqAtk($userIdLogin, "Cancel Request New Item (reqid = <b>".$reqIdGet."</b>)");
	
	// Notif Email ke user
	if($CLogin->notification($db, "notifemail", $ownerId) == "Y") 
	{
		$emailKeUsr = $CReqAtk->detilLoginAtk($ownerId, "useremail", $db)."@andhika.com";
		$CNotif->emailCancelReq($CReqAtk, $reqIdGet, $dateReq, $emailKeUsr);
	}
	//variabel notif desktop ke user
	$notes = "Your ATK Request New Item at ".$dateReq." has been Canceled.";
	//Notif Desktop ke user
	$CReqAtk->varNotifDesktop($ownerId, $koneksiOdbc, $koneksiOdbcId, $CPublic, $CLogin, $CNotif, $notes, $db);
}
?>
<script language="javascript">
function onClickTr(trId, jmlRow, status, reqId)
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
			
			parent.document.getElementById('btnGive').disabled = false;
			parent.document.getElementById('btnGive').className = 'btnStandar';
		}
		if(status == "Completed")
		{
			parent.document.getElementById('btnCancel').disabled = true;
			parent.document.getElementById('btnCancel').className = 'btnStandarDisabled';
			
			parent.document.getElementById('btnGive').disabled = true;
			parent.document.getElementById('btnGive').className = 'btnStandarDisabled';
		}
	}
	
	if(user == "userAtk")
	{	
		if(status == "Unread")
		{
			parent.document.getElementById('btnCancel').disabled = false;
			parent.document.getElementById('btnCancel').className = 'btnStandar';
		}
		if(status != "Unread")
		{
			parent.document.getElementById('btnCancel').disabled = true;
			parent.document.getElementById('btnCancel').className = 'btnStandarDisabled';
		}
	}
	
	parent.document.getElementById('reqId').value = reqId ;
	parent.document.getElementById('trActive').value = trId ;
	parent.detailTrans(reqId,halaman);
}
</script>

<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportaltes/css/main.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportaltes/css/archives.css" rel="stylesheet" type="text/css" />
<body>

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
$query = $CKoneksiAtk->mysqlQuery("SELECT * FROM reqnew ".$condition." ORDER BY reqid DESC");
$jmlRow = $CKoneksiAtk->mysqlNRows($query);
while($row = $CKoneksiAtk->mysqlFetch($query))
{	
	//$transDate = $row['thn']."/".$row['bln']."/".$row['tgl'];
	$transDate = $CPublic->bulanSetengah($row['bln'], "eng")." ".$row['tgl'].", ".$row['thn'];
	
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
		$stsDisp1 = "<div style=\"width:20px;height:16px;border:solid 1px #ccc;background-color:#FF464A;float:left;\">&nbsp;</div>";
	}
	if($status == "Completed")
	{
		//$stsDisp = "background-color=#5EFF46;";
		$stsDisp1 = "<div style=\"width:20px;height:16px;border:solid 1px #ccc;background-color:#5EFF46;float:left;\">&nbsp;</div>";
	}
	
	if($adminAtk == "Y")
	{
		$display = $CReqAtk->detilLoginAtk($row['ownerid'], "userfullnm", $db);
	}
	if($adminAtk == "N")
	{
		$display = $CPublic->potongKarakter($row['reqname'], "40");
	}
	
	$onClickTr = "onClickTr('".$i."', '".$jmlRow."','".$status."','".$row['reqid']."');";
?>

    <tr onMouseOver="this.style.backgroundColor='#DDF0FF';" onMouseOut="this.style.backgroundColor='#FFFFFF';" onClick="<?php echo $onClickTr;?>" id="tr<?php echo $i;?>">
        <td class="tdMyFolder">
            <table width="100%">
            <tr class="fontMyFolderList" height="17">
                <td width="7%" align="center" style="color:#000080;font-weight:bold;font-family:Tahoma;"><?php echo $i;?></td>
                <td width="66%" id="tdName<?php echo $i;?>" title="">
                	<?php echo $bO.$display.$bT;?>&nbsp;
                </td>
                <td id="tdTgl<?php echo $i;?>" width="22%" align="left">
                	<?php echo $bO.$transDate.$bT;?>
                </td>
                <td width="5%" align="center" style=" <?php echo $stsDisp?>"><?php echo $stsDisp1;?></td>
            </tr>
            </table>
        </td>
    </tr>
    
    <!--<tr onMouseOver="this.style.backgroundColor='#DDF0FF';" onMouseOut="this.style.backgroundColor='#FFFFFF';" onClick="onClickTr('2', '', '', '3','Processed');" id="tr2">
        <td class="tdMyFolder">
            <table width="100%">
            <tr class="fontMyFolderList" height="17">
                <td width="7%" align="center" style="color:#000080;font-weight:bold;font-family:Tahoma;">2</td>
                <td id="tdNameDivisi2" title="">
                	Ichsannur Yupi Sogi&nbsp;
                </td>
                <td  id="tdTgl2" width="19%" align="left">
                	2014/12/04
                </td>
                <td width="5%" align="center" style="background-color=#FF464A;">&nbsp;</td>
            </tr>
            </table>
        </td>
    </tr>
    
    <tr onMouseOver="this.style.backgroundColor='#DDF0FF';" onMouseOut="this.style.backgroundColor='#FFFFFF';" onClick="onClickTr('3', '', '', '3','Completed');" id="tr3">
        <td class="tdMyFolder">
            <table width="100%">
            <tr class="fontMyFolderList" height="17">
                <td width="7%" align="center" style="color:#000080;font-weight:bold;font-family:Tahoma;">3</td>
                <td id="tdNameDivisi3" title="">
                	Ichsannur Yupi Sogi&nbsp;
                </td>
                <td  id="tdTgl3" width="19%" align="left">
                	2014/12/04
                </td>
                <td width="5%" align="center" style="background-color=#5EFF46;">&nbsp;</td>
            </tr>
            </table>
        </td>
    </tr>-->
<?php	
$i++;}
echo $html;
?>
</table>
</body>
<script>
<?php
if($aksiGet == "cancel")
{
	echo "parent.refreshPage();
		  parent.report('Your Request successfully cancel');";
}
?>
</script>