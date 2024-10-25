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
?>
<script type="text/javascript" src="../../js/main.js"></script>
<link href="../../css/main.css" rel="stylesheet" type="text/css"/>
<link href="../../css/archives.css" rel="stylesheet" type="text/css"/>
<link href="../../andhikaportal/css/main.css" rel="stylesheet" type="text/css"/>
<link href="../../andhikaportal/css/archives.css" rel="stylesheet" type="text/css"/>

<script language="javascript">

</script>
<body> 

<table width="100%">
<form method="post" action="halDailyActList.php?dateAct=<?php echo $_GET['dateAct']; ?>" name="formUrut">
    <input type="hidden" name="urutSeb">
    <input type="hidden" name="urutSek">
    <input type="hidden" name="urutSet">
    <input type="hidden" name="idActivity">
    <input type="hidden" name="aksi">
</form>
<?php
//------ ==START== Fungsi delete Stop Card ---------
if($aksiGet == "delete")
{
	$idKeluhanGet = $_GET['idkeluhan'];
	
	$CKoneksi->mysqlQuery("UPDATE tblkeluhan SET deletests=1, delusrdt='".$CPublic->userWhoAct()."' WHERE idkeluhan=".$idKeluhanGet." AND deletests=0");
	$CHistory->updateLogQhse($userIdLogin, "Hapus Stop Card (idstopcard = <b>".$idKeluhanGet."</b>)");
}
//------ ==END OF== Fungsi delete Stop Card ---------
$html = "";
$urutan = 0;
$query = $CKoneksi->mysqlQuery("SELECT * FROM tblkeluhan WHERE ownerid=".$userIdLogin." AND deletests=0 ORDER BY idkeluhan DESC");
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
	
	$read = "Sent";
	$dis = "";
	if($row['adminread']== "Y")
	{
		$read = "Already Read";
		$dis = "disabled";
	}
	$urutan++;
	
	$html.="<tr onMouseOver=\"this.style.backgroundColor='#DDF0FF';\" onMouseOut=\"this.style.backgroundColor='#FFFFFF';\">
            <td class=\"tdMyFolder\">
                <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
                <tr>
                    <td width=\"5%\" onclick=\"parent.openThickboxWindow('".$row['idkeluhan']."','edit');\">
						<table width=\"100%\" height=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
                        <tr>
                        	<td height=\"10\" align=\"center\"></td>
                        </tr>	
                    	<tr>
                        	<td height=\"35\" align=\"center\" style=\"font-size:22px;color:#006;font-weight:bold;font-family:Tahoma;\">".$urutan."</td>
                        </tr>
                        <tr>
                        	<td height=\"10\" align=\"center\"></td>
                        </tr>
                        </table>
					</td>
					<!-- <td width=\"68%\"> JIKA PAKAI BUTTON EDIT -->
                    <td width=\"78%\" onclick=\"parent.openThickboxWindow('".$row['idkeluhan']."','edit');\">
                    	<table width=\"100%\" class=\"fontMyFolderList\">
                        <tr>
                        	<td width=\"115\">Created Date</td>
							<td width=\"10\">:</td>
							<td>
								<span style=\"color:#006;\">".$dateCreate." ".$timeCreate."</span>
							</td>
                        </tr>
                        <tr>
                            <td>Status</td><td>:</td>
							<td style=\"color:#006;\">".$read."</td>
                        </tr>
						<tr>
                            <td>Comment</td>
							<td>:</td><td style=\"color:#006;\">".$komen."</td>
                        </tr>
                        </table>
                    </td>
					
					<td align=\"right\" class=\"\">
					<!--
                    	<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"parent.openThickboxWindow('".$row['idkeluhan']."','detail');\" style=\"width:75px;height:55px;\">
                            <table width=\"100%\" height=\"100%\" class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\">
                              <tr>
                                <td align=\"center\"><img src=\"../../picture/Information-blue-32.png\" height=\"25\"/> </td>
                                
                              </tr>
                              <tr>
                                <td align=\"center\">DETAIL</td>
                              </tr>
                            </table>
                        </button>
						&nbsp; -->
						<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"parent.openThickboxWindow('".$row['idkeluhan']."','edit');\" style=\"width:75px;height:55px;\" title=\"Edit/View this line Stop Card\">
                            <table width=\"100%\" height=\"100%\" class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\">
                              <tr>
                                <td align=\"center\"><img src=\"../../picture/Auction-blue-32.png\" height=\"25\"/> </td>
                                
                              </tr>
                              <tr>
                                <td align=\"center\">EDIT</td>
                              </tr>
                            </table>
                        </button>
						&nbsp;
						<button class=\"btnStandarKecil\" onMouseOver=\"this.className='btnStandarKecilHover'\" onMouseOut=\"this.className='btnStandarKecil'\" type=\"button\" onclick=\"parent.deleteStopCard('".$row['idkeluhan']."');\" style=\"width:75px;height:55px;\" title=\"Delete this line Stop Card\" ".$dis.">
                            <table width=\"100%\" height=\"100%\" class=\"fontBtnKecil\" onMouseOver=\"this.className='fontBtnKecilHover'\" onMouseOut=\"this.className='fontBtnKecil'\">
                              <tr>
                                <td align=\"center\"><img src=\"../../picture/Button-Cross-blue-32.png\" height=\"25\"/> </td>
                                
                              </tr>
                              <tr>
                                <td align=\"center\">DELETE</td>
                              </tr>
                            </table>
                        </button>
                    </td>
                </table>
            </td>
        </tr>";
}
echo $html;
?>

</table>
</body>