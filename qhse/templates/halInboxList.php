<?php
require_once("../../config.php");
/*echo $statusExpPage;
if($statusExpPage == "YES")
{
	echo "<script>";
	echo "parent.loadUrl('../index.php?aksi=sessionExpired')";	
	echo "</script>";
}*/

$dateActGet = $CPublic->convTglDB($_GET['dateAct']);

$tglAct = $CPublic->cariNilaiTglDB($dateActGet, "tanggal");
$blnAct = $CPublic->cariNilaiTglDB($dateActGet, "bulan");
$thnAct = $CPublic->cariNilaiTglDB($dateActGet, "tahun");

if($halamanGet == "read")
{
	$idKeluhanGet = $_GET['idkeluhan'];
	
	$queryR = $CKoneksi->mysqlQuery("SELECT * FROM tblkeluhan WHERE idkeluhan = '".$idKeluhanGet."' AND deletests=0 LIMIT 1");
	while($row = $CKoneksi->mysqlFetch($queryR))
	{
		$readStatus = $row['adminread'];
		$cardOwner = $row['ownername'];
	}
	if($readStatus == "N" && $userJenis == "user" && $userQhse == "Y")//jika adminQHSE membuka, status read set Y
	{
		$CKoneksi->mysqlQuery("UPDATE tblkeluhan SET adminread='Y', updusrdt='".$CPublic->userWhoAct()."' WHERE idkeluhan=".$idKeluhanGet." AND deletests=0");
		$CHistory->updateLogQhse($userIdLogin, "Baca Stop Card ".$cardOwner."(idkeluhan = <b>".$idKeluhanGet."</b>)");
	}
}
?>
<script type="text/javascript" src="../../js/main.js"></script>
<link href="../../css/main.css" rel="stylesheet" type="text/css"/>
<link href="../../css/archives.css" rel="stylesheet" type="text/css"/>
<link href="../../andhikaportal/css/main.css" rel="stylesheet" type="text/css"/>
<link href="../../andhikaportal/css/archives.css" rel="stylesheet" type="text/css"/>

<body onLoad="loadScroll('halInboxList');" onUnload="saveScroll('halInboxList');">

<table width="100%">
<form method="post" action="halDailyActList.php?dateAct=<?php echo $_GET['dateAct']; ?>" name="formUrut">
    <input type="hidden" name="urutSeb">
    <input type="hidden" name="urutSek">
    <input type="hidden" name="urutSet">
    <input type="hidden" name="idActivity">
    <input type="hidden" name="aksi">
</form>
<?php

$blnSek = $CPublic->zerofill($CPublic->bulanServer(),2);
$thnSek = $CPublic->tahunServer();
$blnThnSek = $thnSek."-".$blnSek;

$thnBln = $_GET['thnBln'];
$dateCardGet = $_GET['dateCard'];
$ownerIdGet = $_GET['ownerId'];
$date = $thnBln."-".$dateCardGet;

$sqlThnBln = "";
if($thnBln != "0000-00")
{
	$sqlThnBln = "SUBSTR(date,1,7)='".$thnBln."' AND";
}

$sqldateCard = "";
if($dateCardGet != "00")
{
	$sqldateCard = "SUBSTR(date,9,2)='".$dateCardGet."'AND";
}

$sqlOwnerId = "";
if($ownerIdGet != "00000")
{
	$sqlOwnerId = "ownerid = ".$ownerIdGet." AND";
}

$html = "";
$urutan = 0;
//$query = $CKoneksi->mysqlQuery("SELECT * FROM tblkeluhan WHERE ".$ownerCardQuery." deletests=0 ORDER BY date DESC");
$query = $CKoneksi->mysqlQuery("SELECT * FROM tblkeluhan WHERE ".$sqlThnBln." ".$sqldateCard." ".$sqlOwnerId." deletests=0 ORDER BY date DESC");
$jmlRow = $CKoneksi->mysqlNRows($query);
while($row = $CKoneksi->mysqlFetch($query))
{
	$komen = $CPublic->potongKarakter($row['admcomment'], 90) ;
	
	$dateDB = $row['date'];
	$hari = substr($dateDB,8,2);
	$bulan = substr($dateDB,5,2);
	$tahun = substr($dateDB,0,4);
	$dateCreate = $hari." ".$CPublic->bulanSetengah($bulan, "ind")." ".$tahun;
	
	$timeDB = $row['time'];
	$jam = substr($timeDB,0,2);
	$menit = substr($timeDB,3,2);
	$timeCreate = $jam.":".$menit;
	
	$urutan++;
	
	$adminReadCard = "background-image:url(../../picture/qhse_notif.png);background-repeat:no-repeat;background-position:top left;";
	if($row['adminread'] == "Y")
	{
		$adminReadCard = "";
	}
	
	$html.="<tr onMouseOver=\"this.style.backgroundColor='#DDF0FF';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF';\">
            <td class=\"tdMyFolder\">
                <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
                <tr>
                    <td width=\"5%\" onclick=\"parent.openThickboxWindow('".$row['idkeluhan']."','view');\">
						<table width=\"100%\" height=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
                        <tr>
                        	<td height=\"10\" align=\"center\"></td>
                        </tr>	
                    	<tr>
                        	<td height=\"35\" align=\"center\" style=\"font-size:22px;color:#006;font-weight:bold;font-family:Tahoma;".$adminReadCard."\">".$urutan."</td>
                        </tr>
                        <tr>
                        	<td height=\"10\" align=\"center\"></td>
                        </tr>
                        </table>
					</td>
					
                    <td width=\"77%\" onclick=\"parent.openThickboxWindow('".$row['idkeluhan']."','view');\">
                    	<table width=\"100%\" class=\"fontMyFolderList\">
						<tr>
                            <td>Card Owner</td>
							<td>:</td><td style=\"color:#006;\">".$row['ownername']."</td>
                        </tr>
                        <tr>
                        	<td width=\"115\">Created Date</td>
							<td width=\"10\">:</td>
							<td>
								<span style=\"color:#006;\">".$dateCreate." ".$timeCreate."</span>
							</td>
                        </tr>
                        <tr>
                            <td>Comment</td>
							<td>:</td><td style=\"color:#006;\">".$komen."</td>
                        </tr>
                        </table>
                    </td>
					
					<td align=\"right\" class=\"\">
						<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"parent.klikBtnPrint('".$row['idkeluhan']."');\" style=\"width:75px;height:55px;\" title=\"Print this line Stop Card\">
                            <table width=\"100%\" height=\"100%\" class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\">
                              <tr>
                                <td align=\"center\"><img src=\"../../picture/Printer-blue-32.png\" height=\"25\"/> </td>
                                
                              </tr>
                              <tr>
                                <td align=\"center\">PRINT</td>
                              </tr>
                            </table>
                        </button>
						&nbsp;
						<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"parent.openThickboxWindow('".$row['idkeluhan']."','view');\" style=\"width:75px;height:55px;\" title=\"View this line Stop Card\">
                            <table width=\"100%\" height=\"100%\" class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\">
                              <tr>
                                <td align=\"center\"><img src=\"../../picture/Metro-Tasks-Blue-32.png\" height=\"25\"/> </td>
                                
                              </tr>
                              <tr>
                                <td align=\"center\">VIEW</td>
                              </tr>
                            </table>
                        </button>
						&nbsp;
                    </td>
                </table>
            </td>
        </tr>";
}
echo $html;
?>

</table>
</body>
<script language="javascript">
<?php
if($halamanGet == "read")
{
	$unreadDateGet = $thnBln."-".$dateCardGet;

	$unread = $CQhse->unReadInbox($unreadDateGet, $ownerIdGet);
	echo "parent.document.getElementById('tdUnread').innerHTML = 'UNREAD : ".$unread." &nbsp;';";
	$notif = $CQhse->unReadInbox("0000-00-00", "000000");
	if($notif == 0)
	{
		echo "parent.document.getElementById('inboxBtnId').style.backgroundImage = 'none'";
	}
}
?>
</script>