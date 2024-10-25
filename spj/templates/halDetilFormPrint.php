<!DOCTYPE HTML>
<?php
require_once("../../config.php");
require_once("../configSpj.php");

$formIdGet = $_GET['formId'];

$query = $CKoneksiSpj->mysqlQuery("SELECT * FROM form WHERE formid = ".$formIdGet." AND deletests=0;");
$row = $CKoneksiSpj->mysqlFetch($query);
//echo "a ".$halamanGet;
$dateForm = $row['datefrom'];
$thn =  substr($dateForm,0,4);
$bln =  substr($dateForm,4,2);
$tgl =  substr($dateForm,6,2);
$formDate =  $tgl." ".$CPublic->detilBulanNamaAngka($bln, "ind")." ".$thn;

$dateTo = $row['dateto'];
$thnTo =  substr($dateTo,0,4);
$blnTo =  substr($dateTo,4,2);
$tglTo =  substr($dateTo,6,2);
$toDate =  $tglTo." ".$CPublic->detilBulanNamaAngka($blnTo, "ind")." ".$thnTo;

if($aksiGet == "ubahStatus")
{
	$formIdGet = $_GET['formId'];
	$userFullNm = $CSpj->detilLoginSpj($CSpj->detilForm($formIdGet, "ownerid"), "userfullnm", $db);
	$dest = $CSpj->detilForm($formIdGet, "destination");
	
	$CKoneksiSpj->mysqlQuery("UPDATE form SET status = 'Processed', updusrdt = '".$CPublic->userWhoAct()."' WHERE formid = ".$formIdGet.";");
	$CHistory->updateLogSpj($userIdLogin, "Read Form SPJ (formid = <b>".$formIdGet."</b>, ownerform = <b>".$userFullNm."</b>, tujuan dinas = <b>".$dest."</b>)");
}
?>

<link href="../css/cssSpj.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../../js/jquery-1.11.2.js"></script>

<script>
//Automatically setting height of textarea to height of its contents
$(function() {
	 $('textarea').height($('textarea').prop('scrollHeight'));
});
</script>

<table cellpadding="0" cellspacing="0" width="100%">
<!-- header -->
<tr> 
	<td>
    <table cellpadding="0" cellspacing="0" width="100%">
    <tr><td width="7%" height="2px"></td></tr>
    <tr>
    	<td>&nbsp;</td>
    	<td width="12%" align="center">
        	<img src="../../picture/AndhikaTransparentBkGndBlue.png" width="60px" />
        </td>
        <td width="74%" align="center">
        <?php // Menentukan Perusahaan -> Andhika atau Adnyana
			$img = "<img src=\"../picture/Tulisan Biru PT Andhika Lines.JPG\" width=\"380px\"/>";
			$comp = $row['kdcmp'];
			if($comp == "02")
			{
				$img = "<img src=\"../picture/Tulisan Biru PT Adnyana.JPG\" width=\"300px\"/>";
			}
		?>
            <?php echo $img;?>&nbsp;
            
        </td>
        <td width="7%">&nbsp;</td>
    </tr>
    <tr>
    	<td>&nbsp;</td>
    	<td width="12%" style="font-family:Arial;font-size:7px;font-weight:bold;" align="center">
        <?php
			$number = "";
			if($row['kdcmp'] == 01)
			{
				$number = "311";
			}
			if($row['kdcmp'] == 02)
			{
				$number = "321";
			}
		?>
        	MEMBER OF INSA<br/>No. <?php echo $number;?>/INSA/VIII/1990
        </td>
        <td width="74%">&nbsp;
            
        </td>
        <td width="7%">&nbsp;</td>
    </tr>
    </table>
    </td>
</tr>
<tr><td height="5" style="border-bottom:solid medium #000;"></td></tr>
<tr><td height="7"></td></tr>
<tr>
	<td align="center" class="fontMyFolderList" style="color:#000;">
    	<span style="text-decoration:underline;font-size:14px;font-weight:bold;">SURAT PERINTAH JALAN</span>
        <br/>
        <?php
			$spjNo = $row['spjno'];
			$nomor = "";
			if($spjNo != "")
			{
        		$nomor = "Nomor : ".$row['spjno']."";
			}
			
			echo $nomor;
		?>
    </td>
</tr>
<tr><td height="5"></td></tr>
<!-- tabel detil -->
<tr>
	<td align="center">
    <table class="fontMyFolderList" cellpadding="0" cellspacing="3" width="98%" style="border:solid thin #000;">
    <tr>
    	<td width="14%" align="left">
        	Nama
        </td>
        <td width="36%" align="left" style="color:#000080;">
        	<?php echo ucwords(strtolower($row['ownername']));?>
        </td>
        <td width="17%" align="left">
        	Keperluan
        </td>
        <td width="33%" align="left" style="color:#000080;">
        	<?php echo $row['necessary'];?>
        </td>
    </tr>
    
    <tr>
    	<td align="left">
        	Jabatan
        </td>
        <td align="left" style="color:#000080;">
        	<?php echo ucwords(strtolower($CEmployee->detilJabatan($CEmployee->detilTblEmpGen($row['ownerempno'], "kdjabatan"), "nmjabatan")));?>
        </td>
        <td align="left">
        	Tanggal Berangkat
        </td>
        <td align="left" style="color:#000080;">
        	<?php echo $formDate;?>
        </td>
    </tr>
    
    <tr>
    	<td align="left">
        	Golongan
        </td>
        <td align="left" style="color:#000080;">
        	<?php echo ucwords(strtolower($CEmployee->detilPangkat($CEmployee->detilTblEmpGen($row['ownerempno'], "kdpangkat"), "nmpangkat")));?>
        </td>
        <td align="left">
        	Tanggal Kembali
        </td>
        <td align="left" style="color:#000080;">
        	<?php echo $toDate;?>
        </td>
    </tr>
    
    <tr>
    	<td align="left">
        	Tempat Tujuan
        </td>
        <td align="left" style="color:#000080;">
        	<?php echo $row['destination'];?>
        </td>
        <td align="left">
        	Kendaraan
        </td align="left">
        <td align="left" style="color:#000080;">
        	<?php echo $row['vehicle'];?>
        </td>
    </tr>
    
    <tr>
    	<td align="left" valign="top">
        	Catatan
        </td>
        <td align="left" colspan="2">
        	<textarea style="color:#000080;font-family:Tahoma;font-size:12px;width:98%;border:none;overflow:hidden;" readonly><?php echo $row['note'];?></textarea>
        </td>
        <td valign="top">
        <table cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr height="17">
            <td align="left" valign="top">
                Pengikut  :
            </td>
        </tr>
        <tr>
            <td align="left" style="color:#000080;" valign="top">
            <?php
                echo ucwords(strtolower($CSpj->menuFollower($formIdGet, $db)));
            ?>
            </td>
        </tr>    
        </table>
        </td>
    </tr>
    <tr><td colspan="4" height="2"></td></tr>
    </table>
    </td>
</tr>
<tr><td height="5"></td></tr>
<!-- Data Approval -->
<tr>
<td align="center">
    <table class="fontMyFolderList" style="font-size:13px;" cellpadding="0" cellspacing="3" width="98%">
    <tr align="left">
        <td width="14%">
            Dikeluarkan di
        </td>
        <td width="86%" style="color:#000080;">
            Jakarta
        </td>
 	</tr>
    <tr align="left">
        <td>
            Pada Tanggal
        </td>
        <td style="color:#000080;">
        <?php
			$tglSpj = "<i>Waiting . . .</i>";
			if($row['spjdate'] != "")
			{
				$tglSpjDb = $row['spjdate'];
				$thnSpj =  substr($tglSpjDb,0,4);
				$blnSpj =  substr($tglSpjDb,4,2);
				$tglSpj =  substr($tglSpjDb,6,2);
				$tglSpj =  $tglSpj." ".$CPublic->detilBulanNamaAngka($blnSpj, "ind")." ".$thnSpj;
			}
			
			echo $tglSpj;
		?>
        </td>
 	</tr>
    <tr><td colspan="2" height="5"></td></tr>
    </table>
</td>
</tr>
<tr>
	<td align="center">
    <table class="fontMyFolderList" cellpadding="0" cellspacing="0" border="0" width="96%">
    	<tr>
        	<td width="24%" align="center">Menyetujui,</td>
            <td width="52%">&nbsp;</td>
            <td width="24%" align="center">Mengetahui,</td>
        </tr>
        <tr>
        	<td colspan="3" height="50px"></td>
        </tr>
        <tr>
        	<td width="24%" align="center" style="border-bottom:#000 solid thin;font-weight:bold;">
            <?php
				if($row['aprvbyadm'] == "N")// Jika sudah Approved
				{
					$html = $CSpj->detilLoginSpjByEmpno($row['aprvempno'], "userfullnm", $db);
				}
				if($row['aprvbyadm'] == "Y")// Jika Approved dari Administrator
				{
					$html = $CSpj->detilLoginSpjByEmpno($row['kadivempno'], "userfullnm", $db);
				}
				echo ucwords(strtolower($html));
			?>
            </td>
            <td width="52%">&nbsp;</td>
            <td width="24%" align="center" style="border-bottom:#000 solid thin;font-weight:bold;">
            <?php
				if($row['knowbyadm'] == "N")//jika Sudah Acknowledge
				{
					$know = $CSpj->detilLoginSpjByEmpno($CSpj->detilForm($formIdGet, "knowempno"), "userfullnm", $db);
				}
				if($row['knowbyadm'] == "Y")
				{
					$know = $CSpj->detilLoginSpjByEmpno($CEmployee->detilDiv("050", "divhead"), "userfullnm", $db);
				}
				echo ucwords(strtolower($know));
			?>
            </td>
        </tr>
        <tr>
        	<td width="24%" align="center">
            <?php 
				$jabatan = "";
				if($row['kadivempno'] == "00625")
				{
					$jabatan = "CEO";
				}
				if($row['kadivempno'] != "00625")
				{
					$jabatan = "Kadiv ".$CSpj->detilDivByDivhead($row['kadivempno'], "nmdiv");
				}
				echo $jabatan
			?> 
            </td>
            <td width="52%">&nbsp;</td>
            <td width="24%" align="center">Kadiv. HR & SUPPORT DIV.</td>
        </tr>
    </table>
    </td>
</tr>
<tr><td height="15px"></td></tr>
<tr>
	<td align="center">
	<table class="fontMyFolderList" cellpadding="0" cellspacing="0" border="0" width="96%">
    <tr>
    	<td colspan="3" align="left">Tembusan	:</td>
    </tr>
	<tr align="left">
    	<td width="6%">&nbsp;</td>
        <td width="94%">
        <?php
			echo $CSpj->listTembusan($formIdGet)
		?>
        </td>	
    </tr>
    <tr><td colspan="3" height="3px;"></td></tr>
    <tr>
    	<td colspan="3" align="left">Diisi oleh petugas di Tempat Tujuan (Hanya Digunakan saat Kunjungan Kapal / Kebutuhan Internal Lainnya) :</td>
    </tr>
    </table>
    </td>
</tr>
<tr><td height="3px"></td></tr>
<tr>
	<td align="center">
    <table class="fontMyFolderList" cellpadding="0" cellspacing="0" border="0" width="98%" style="color:#000;">
    	<tr height="20px">
        	<td width="25%" align="left" class="borderAll">&nbsp; Tanggal Kedatangan</td>
            <td width="25%" align="left" class="borderLeftNull">&nbsp;</td>
            <td width="25%" align="left" class="borderLeftNull">&nbsp; Tanggal Kembali</td>
            <td width="25%" align="left" class="borderLeftNull">&nbsp;</td>
        </tr>
        <tr height="80px" style="font-size:8px;" valign="top">
        	<td align="left" class="borderBottomTopNull">&nbsp; Paraf</td>
            <td align="left" class="borderRightJust">&nbsp; Stempel</td>
            <td align="left" class="borderRightJust">&nbsp; Paraf</td>
            <td align="left" class="borderRightJust">&nbsp; Stempel</td>
        </tr>
        <tr height="20px">
        	<td width="25%" align="left" class="borderAll">&nbsp; Tanggal Kedatangan</td>
            <td width="25%" align="left" class="borderLeftNull">&nbsp;</td>
            <td width="25%" align="left" class="borderLeftNull">&nbsp; Tanggal Kembali</td>
            <td width="25%" align="left" class="borderLeftNull">&nbsp;</td>
        </tr>
        <tr height="80px" style="font-size:8px;" valign="top">
        	<td align="left" class="borderTopNull">&nbsp; Paraf</td>
            <td align="left" class="borderTopLeftNull">&nbsp; Stempel</td>
            <td align="left" class="borderTopLeftNull">&nbsp; Paraf</td>
            <td align="left" class="borderTopLeftNull">&nbsp; Stempel</td>
        </tr>
    </table>
    </td>
</tr>
</table>
</HTML>