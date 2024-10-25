<?php
require_once("../../config.php");
	
	$idKeluhanGet = $_GET['idkeluhan'];
	$time = $CPublic->zerofill($CPublic->jamServer());
	$tgl = $CPublic->zerofill($CPublic->waktuServer("tanggal"), 2); 
	$bln = $CPublic->zerofill($CPublic->waktuServer("bulan"), 2); 
	$thn = $CPublic->waktuServer("tahun");
	$dateAct = $tgl."/".$bln."/".$thn;
	$date = $thn."-".$bln."-".$tgl;

if($halamanGet == "view")
{
	$thnBlnGet = $_GET['thnBln'];
	$dateCard = $CPublic->zerofill($_GET['dateCard'], 2);
	$ownerId = $_GET['ownerId'];

	$A0cek = statusCek($CKoneksi, $idKeluhanGet, "A0");
	$A1cek = statusCek($CKoneksi, $idKeluhanGet, "A1");
	$A2cek = statusCek($CKoneksi, $idKeluhanGet, "A2");
	$A3cek = statusCek($CKoneksi, $idKeluhanGet, "A3");
	$A4cek = statusCek($CKoneksi, $idKeluhanGet, "A4");
	$A5cek = statusCek($CKoneksi, $idKeluhanGet, "A5");
	$A6cek = statusCek($CKoneksi, $idKeluhanGet, "A6");
	$B0cek = statusCek($CKoneksi, $idKeluhanGet, "B0");
	$B1cek = statusCek($CKoneksi, $idKeluhanGet, "B1");
	$B2cek = statusCek($CKoneksi, $idKeluhanGet, "B2");
	$B3cek = statusCek($CKoneksi, $idKeluhanGet, "B3");
	$B4cek = statusCek($CKoneksi, $idKeluhanGet, "B4");
	$B5cek = statusCek($CKoneksi, $idKeluhanGet, "B5");
	$B6cek = statusCek($CKoneksi, $idKeluhanGet, "B6");
	$C0cek = statusCek($CKoneksi, $idKeluhanGet, "C0");
	$C1cek = statusCek($CKoneksi, $idKeluhanGet, "C1");
	$C2cek = statusCek($CKoneksi, $idKeluhanGet, "C2");
	$C3cek = statusCek($CKoneksi, $idKeluhanGet, "C3");
	$C4cek = statusCek($CKoneksi, $idKeluhanGet, "C4");
	$C5cek = statusCek($CKoneksi, $idKeluhanGet, "C5");
	$C6cek = statusCek($CKoneksi, $idKeluhanGet, "C6");
	$C7cek = statusCek($CKoneksi, $idKeluhanGet, "C7");
	$C8cek = statusCek($CKoneksi, $idKeluhanGet, "C8");
	$C9cek = statusCek($CKoneksi, $idKeluhanGet, "C9");
	$C10cek = statusCek($CKoneksi, $idKeluhanGet, "C10");
	$C11cek = statusCek($CKoneksi, $idKeluhanGet, "C11");
	$D0cek = statusCek($CKoneksi, $idKeluhanGet, "D0");
	$D1cek = statusCek($CKoneksi, $idKeluhanGet, "D1");
	$D2cek = statusCek($CKoneksi, $idKeluhanGet, "D2");
	$D3cek = statusCek($CKoneksi, $idKeluhanGet, "D3");
	$E0cek = statusCek($CKoneksi, $idKeluhanGet, "E0");
	$E1cek = statusCek($CKoneksi, $idKeluhanGet, "E1");
	$E2cek = statusCek($CKoneksi, $idKeluhanGet, "E2");
	$E3cek = statusCek($CKoneksi, $idKeluhanGet, "E3");
	$E4cek = statusCek($CKoneksi, $idKeluhanGet, "E4");
	$E5cek = statusCek($CKoneksi, $idKeluhanGet, "E5");
	$E6cek = statusCek($CKoneksi, $idKeluhanGet, "E6");
	
	$query = $CKoneksi->mysqlQuery("SELECT * FROM tblkeluhan WHERE idkeluhan = '".$idKeluhanGet."' AND deletests=0 LIMIT 1");
	while($row = $CKoneksi->mysqlFetch($query))
	{
		$aman = $row['aman'];
		$tidakAman = $row['tidakaman'];
		$lokasi = $row['lokasi'];
		$dateDB = $row['date'];
		$hari = substr($dateDB,8,2);
		$bulan = substr($dateDB,5,2);
		$tahun = substr($dateDB,0,4);
		$dateCreate = $hari."/".$bulan."/".$tahun;
		$ownerName = $row['ownername'];
		$komen = $row['admcomment'];
		$ownerIdDb = $row['ownerid'];
	}
}
if($aksiGet == "simpanViewCard")
{
	$idKeluhan = $_GET['idKeluhan'];
	$komenPost = mysql_real_escape_string($_POST['komen']);
	$dateDBGet = $_GET['dateDB'];
	$ownerIdDbGet = $_GET['ownerId'];
	$ownerNameGet = $_GET['ownerName'];
	//echo $ownerIdDbGet." komen: ".$komenPost." ".$dateDBGet;
	
	$CKoneksi->mysqlQuery("UPDATE tblkeluhan SET admcomment = '".$komenPost."', updusrdt = '".$CPublic->userWhoAct()."' WHERE idkeluhan = '".$idKeluhan."' AND ownerid = '".$ownerIdDbGet."' AND date = '".$dateDBGet."' AND deletests=0;");

	$CHistory->updateLogQhse($userIdLogin, "Komentar Stop Card(idkeluhan = <b>".$idKeluhan."</b>, Date = <b>".$dateDBGet."</b>, ownerid = <b>".$ownerIdDbGet."</b>, ownername = <b>".$ownerNameGet."</b>, komentar = <b>".$komenPost."</b>)");
}
?>

<script src="../../js/jquery-1.4.3.js"></script>
<script src="../../js/main.js"></script>
<script src="../js/main.js"></script>
<script src="../../js/JavaScriptUtil.js"></script>
<script src="../../js/Parsers.js"></script>
<script src="../../js/InputMask.js"></script>

<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />

<script language="javascript">
function ajaxKomen(id, aksi, halaman)
{
	var mypostrequest=new ajaxRequest()
	mypostrequest.onreadystatechange=function()
	{
		if (mypostrequest.readyState==4)
		{
			if (mypostrequest.status==200 || window.location.href.indexOf("http")==-1)
			{
				document.getElementById(halaman).innerHTML=mypostrequest.responseText;
			}
		}
	}
	
	if(aksi == "cekKomenAdaTidak")
	{
		var komen = document.getElementById('komen').value.replace(/&/g,"%26");
		var ownerId = "<?php echo $ownerIdDb; ?>";
		var idKeluhan = "<?php echo $idKeluhanGet; ?>";
		var parameters="halaman="+aksi+"&ownerId="+ownerId+"&komen="+komen+"&idKeluhan="+idKeluhan;
	}
	
	mypostrequest.open("POST", "../halPostFoldQhse.php", true);
	mypostrequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	mypostrequest.send(parameters);
}

function tutup()
{	
	var answer  = confirm("Are you sure want to close?");
	if(answer)
	{
		ajaxKomen("", "cekKomenAdaTidak", "idAdaTidak");
		
		var delay=500//1 seconds
		
    	setTimeout(function()
		{
			var adaTidak = document.getElementById('adaTidak').value;
			if(adaTidak == "tidak")
			{
				var userJenis = '<?php echo $userJenis ; ?>';
				var userQhse = '<?php echo $userQhse ; ?>';
				
				if(userJenis == 'user' && userQhse == 'Y')//hanya admin QHSE yang dapat input komentar
				{
					formViewCard.submit();
					exit();
				}
				else{
					exit();
				}
				return false;
			}
			if(adaTidak == "ada")
			{
				exit();
			}
		},delay);
	}
	else
	{	return false;	}
}

function exit()
{
	
	parent.tb_remove(false);
	parent.document.getElementById('iframeHalInboxList').src = "";
	parent.document.getElementById('iframeHalInboxList').src = "templates/halInboxList.php?halaman=read&idkeluhan=<?php echo $idKeluhanGet; ?>&thnBln=<?php echo $thnBlnGet; ?>&dateCard=<?php echo $dateCard; ?>&ownerId=<?php echo $ownerId; ?>";
}


function disBtn()
{
	var userJenis = '<?php echo $userJenis ; ?>';
	var userQhse = '<?php echo $userQhse ; ?>';
	
	if(userJenis == 'user' && userQhse == 'Y')// Tombol Close tidak aktif ketika komen kosong, jika yang melihat Admin QHSE
	{
		document.getElementById('btnClose').disabled = true;
		var komen = document.getElementById('komen').value.replace(/&/g,"%26");
		//alert(komen);
		if(komen.replace(/ /g,"") != "")
		{
			document.getElementById('btnClose').disabled = false;
		}
		if(komen.replace(/ /g,"") == "")
		{
			document.getElementById('btnClose').disabled = true;
		}
	}
}



setInterval(function(){ $('blink').each( function(){ $(this).css('visibility' , $(this).css('visibility') === 'hidden' ? '' : 'hidden') } ); }, 850);
</script>

<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportal/css/main.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportal/css/archives.css" rel="stylesheet" type="text/css" />

<body bgcolor="#F8F8F8" bottommargin="3" topmargin="3" onLoad="disBtn();">
<center>
<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
<tr valign="top" style="border:none">
	<td width="51%" height=""  style="border:none"><span class="teksLvlFolder" style="color:#666;">Created Date : </span>
        <span class="formInput" style="text-decoration:underline;color:#00F;font-weight:bold;"><?php echo $dateCreate; ?></span>
    <td width="49%" align="right" ><span class="teksLvlFolder" style="color:#999;font-size:16px;font-style:italic;"><b>:: View Safety Observation ::</b></span>&nbsp;</td>
</tr>
<tr valign="top" style="border:none">
	<td width="51%" height="" colspan="2" style="border:none">
        <span class="teksLvlFolder" style="color:#666;">Card Owner &nbsp;&nbsp;: </span>
        <span class="formInput" style="color:#00F;font-weight:bold;"><?php echo $ownerName; ?></span></td>
</tr>

<tr valign="top">
    <td class="tdMyFolder" bgcolor="#FFFFFF" colspan="2" align="center" valign="top" style="cursor:default;">
    	<div style="width:510px;height:450px;overflow:scroll;overflow-x: hidden;top: expression(offsetParent.scrollTop);background-color:#FFF;">
        <form action="halViewStopCard.php?aksi=simpanViewCard&dateDB=<?php echo $dateDB; ?>&idKeluhan=<?php echo $idKeluhanGet; ?>&ownerId=<?php echo $ownerIdDb; ?>&ownerName=<?php echo $ownerName; ?>" method="post" id="formViewCard" name="formViewCard">
            <table cellpadding="0" cellspacing="0" height="100%" width="99%" border="0" style="font-size:11px;font-family:tahoma;">
            	<tr >
                    <td align="center" style="font-size:12px;color:#666;" colspan="3">
                        <b>DAFTAR PENGAMATAN / <em>OBSERVATION CHECKLIST</em></b><br/>&nbsp;
                    </td>
                </tr>
                <tr valign="bottom">
                	<td width="53%">
                    	&nbsp;<img src="../../picture/symbol_check.png" width="20" style="vertical-align:middle;"/> Beri tanda bila tidak aman
                    </td>	
                    <td align="right" colspan="2">
                    	Beritahu bila aman <img src="../../picture/symbol_check.png" width="20" style="vertical-align:middle;"/>
                    </td>
                </tr>
                <tr height="19px;">
                    <td colspan="2">
                        <b>REAKSI SESEORANG / <em>REACTION OF PEOPLE</em></b> </td><td width="4%" align="right"><?php echo $A0cek ; ?>
                    </td>
                </tr>
                <tr height="19px;">
                    <td bgcolor="#E1FFFF" colspan="3">
                        &nbsp;<?php echo $A1cek ; ?> Penyesuaian Penggunaan APD / <em>Adjusting PPE</em></td>
                </tr>
                <tr height="19px;">
                    <td bgcolor="#F5FEFE" colspan="3">
                        &nbsp;<?php echo $A2cek ; ?> Merubah Posisi / <em>Changing Position</em></td>
                </tr>
                <tr height="19px;">
                    <td bgcolor="#E1FFFF" colspan="3">
                        &nbsp;<?php echo $A3cek ; ?> Mengatur Pekerjaan /<em> Rearranging Job</em></td>
                </tr>
                <tr height="19px;">
                    <td bgcolor="#F5FEFE" colspan="3">
                        &nbsp;<?php echo $A4cek ; ?> Menghentikan Pekerjaan / <em>Stopping Job</em></td>
                </tr>
                <tr height="19px;">
                    <td bgcolor="#E1FFFF" colspan="3">
                        &nbsp;<?php echo $A5cek ; ?> Memasang Arde / <em>Attaching Ground</em></td>
                </tr>
                <tr height="19px;">
                    <td style="border-bottom: dashed 1px #CCC;" bgcolor="#F5FEFE" colspan="3">
                        &nbsp;<?php echo $A6cek ; ?> Melakukan Penguncian / <em>Performing Lockouts</em></td>
                </tr>
<!-- END OF 1 ================================================================================================================ -->
                <!--<tr valign="bottom">
                	<td width="53%">
                    	&nbsp;<img src="../../picture/symbol_check.png" width="20" style="vertical-align:middle;"/> Beri tanda bila tidak aman
                    </td>	
                    <td align="right" colspan="2">
                    	Beritahu bila aman <img src="../../picture/symbol_check.png" width="20" style="vertical-align:middle;"/>
                    </td>
                </tr>-->
                <tr height="19px;">
                    <td colspan="2">
                        <b>ALAT PELINDUNG PRIBADI / <em>PERSONAL PROTECTIVE EQUIPMENT</em></b> </td><td width="4%" align="right"><?php echo $B0cek ; ?>
                    </td>
                </tr>
                <tr height="19px;">
                    <td bgcolor="#E1FFFF" colspan="3">
                        &nbsp;<?php echo $B1cek ; ?> Kepala / <em>Head</em></td>
                </tr>
                <tr height="19px;">
                    <td bgcolor="#F5FEFE" colspan="3">
                        &nbsp;<?php echo $B2cek ; ?> Mata dan Muka / <em>Eyes and Face </em></td>
                </tr>
                <tr height="19px;">
                    <td bgcolor="#E1FFFF" colspan="3">
                        &nbsp;<?php echo $B3cek ; ?> Telinga / <em>Ear</em></td>
                </tr>
                <tr height="19px;">
                    <td bgcolor="#F5FEFE" colspan="3">
                        &nbsp;<?php echo $B4cek ; ?> Sistem Pernapasan / <em>Respiratory System</em></td>
                </tr>
                <tr height="19px;">
                    <td bgcolor="#E1FFFF" colspan="3">
                        &nbsp;<?php echo $B5cek ; ?> Lengan dan Tangan /<em> Arms and Hands</em></td>
                </tr>
                <tr height="19px;">
                    <td style="border-bottom: dashed 1px #CCC;" bgcolor="#F5FEFE" colspan="3">
                        &nbsp;<?php echo $B6cek ; ?> Kaki dan Telapak Kaki / <em>Leg - Foot</em></td>
                </tr>
<!-- END OF 2 ================================================================================================================ -->
                <!--<tr valign="bottom">
                	<td width="53%">
                    	&nbsp;<img src="../../picture/symbol_check.png" width="20" style="vertical-align:middle;"/> Beri tanda bila tidak aman
                    </td>	
                    <td align="right" colspan="2">
                    	Beritahu bila aman <img src="../../picture/symbol_check.png" width="20" style="vertical-align:middle;"/>
                    </td>
                </tr>-->
                <tr height="19px;">
                    <td colspan="2">
                        <b>POSISI SESEORANG (Penyebab Cidera) / <em>POSITIONS OF PEOPLE</em> (<em>Injury Causes</em>)</b> </td><td align="right"><?php echo $C0cek ; ?>
                    </td>
                </tr>
                <tr height="19px;">
                    <td bgcolor="#E1FFFF" colspan="3">
                        &nbsp;<?php echo $C1cek ; ?> Menabrak Barang / <em>Striking Againts Objects</em></td>
                </tr>
                <tr height="19px;">
                    <td bgcolor="#F5FEFE" colspan="3">
                        &nbsp;<?php echo $C2cek ; ?> Tertimpa Barang / <em>Struck by Objects</em></td>
                </tr>
                <tr height="19px;">
                    <td bgcolor="#E1FFFF" colspan="3">
                        &nbsp;<?php echo $C3cek ; ?> Terhimpit Barang / <em>Caught In, On, or Between Objects</em></td>
                </tr>
                <tr height="19px;">
                    <td bgcolor="#F5FEFE" colspan="3">
                        &nbsp;<?php echo $C4cek ; ?> Jatuh / <em>Falling</em></td>
                </tr>
                <tr height="19px;">
                    <td bgcolor="#E1FFFF" colspan="3">
                        &nbsp;<?php echo $C5cek ; ?> Berada di Tempat Sangat Panas / <em>Contracting Temperature Extremes</em></td>
                </tr>
                <tr height="19px;">
                    <td bgcolor="#F5FEFE" colspan="3">
                        &nbsp;<?php echo $C6cek ; ?> Menghisap / <em>Inhaling</em> (Zat Berbahaya / <em>Absorbing a Hazardous Substance</em>)</td>
                </tr>
                <tr height="19px;">
                    <td bgcolor="#E1FFFF" colspan="3">
                        &nbsp;<?php echo $C7cek ; ?> Absorbsi (Zat Berbahaya / <em>Absorbing a Hazardous Substance</em>)</td>
                </tr>
                <tr height="19px;">
                    <td bgcolor="#F5FEFE" colspan="3">
                        &nbsp;<?php echo $C8cek ; ?> Menelan / <em>Swallowing</em> (Zat Berbahaya / <em>Absorbing a Hazardous Substance</em>)</td>
                </tr>
                <tr height="19px;">
                    <td bgcolor="#E1FFFF" colspan="3">
                        &nbsp;<?php echo $C9cek ; ?> Gerakan Membahayakan / <em>Over Exertion</em></td>
                </tr>
                <tr height="19px;">
                    <td bgcolor="#F5FEFE" colspan="3">
                        &nbsp;<?php echo $C10cek ; ?> Gerakan Berlebihan / <em>Repetitive Motion</em></td>
                </tr>
                <tr height="19px;">
                    <td style="border-bottom: dashed 1px #CCC;" bgcolor="#E1FFFF" colspan="3">
                        &nbsp;<?php echo $C11cek ; ?> Posisi Janggal / <em>Awkward Position </em>/ <em>Static Postures</em></td>
                </tr>
<!-- END OF 3 ================================================================================================================ -->
                <!--<tr valign="bottom">
                	<td width="53%">
                    	&nbsp;<img src="../../picture/symbol_check.png" width="20" style="vertical-align:middle;"/> Beri tanda bila tidak aman
                    </td>	
                    <td align="right" colspan="2">
                    	Beritahu bila aman <img src="../../picture/symbol_check.png" width="20" style="vertical-align:middle;"/>
                    </td>
                </tr>-->
                <tr height="19px;">
                    <td colspan="2">
                        <b>PERKAKAS DAN PERALATAN / <em>TOOLS AND EQUIPMENT</em></b> </td><td width="4%" align="right"><?php echo $D0cek ; ?>
                    </td>
                </tr>
                <tr height="19px;">
                    <td bgcolor="#E1FFFF" colspan="3">
                        &nbsp;<?php echo $D1cek ; ?> Tidak sesuai dengan pekerjaan / <em>Wrong the Job</em></td>
                </tr>
                <tr height="19px;">
                    <td bgcolor="#F5FEFE" colspan="3">
                        &nbsp;<?php echo $D2cek ; ?>  Salah Penggunaan / <em>Used Incorrectly</em></td>
                </tr>
                <tr height="19px;">
                    <td style="border-bottom: dashed 1px #CCC;" bgcolor="#E1FFFF" colspan="3">
                        &nbsp;<?php echo $D3cek ; ?>  Berada di Tempat yang Tidak Aman / <em>In Unsafe Condition</em></td>
                </tr>
<!-- END OF 4 ================================================================================================================ -->
                <!--<tr valign="bottom">
                	<td width="53%">
                    	&nbsp;<img src="../../picture/symbol_check.png" width="20" style="vertical-align:middle;"/> Beri tanda bila tidak aman
                    </td>	
                    <td align="right" colspan="2">
                    	Beritahu bila aman <img src="../../picture/symbol_check.png" width="20" style="vertical-align:middle;"/>
                    </td>
                </tr>-->
                <tr height="19px;">
                    <td colspan="2">
                        <b>PROSEDUR DAN PETUNJUK / <em>PROCEDURES AND ORDERLINESS</em></b> </td><td align="right"><?php echo $E0cek ; ?>
                    </td>
                </tr>
                <tr height="19px;">
                    <td bgcolor="#E1FFFF" colspan="3">
                        &nbsp;<?php echo $E1cek ; ?> Prosedur Tidak Memadai / <em>Procedures Inaquate</em></td>
                </tr>
                <tr height="19px;">
                    <td bgcolor="#F5FEFE" colspan="3">
                        &nbsp;<?php echo $E2cek ; ?> Prosedur Tidak Diketahui / Tidak Mengerti / <em>Procedures Not Known / Understood</em></td>
                </tr>
                <tr height="19px;">
                    <td bgcolor="#E1FFFF" colspan="3">
                        &nbsp;<?php echo $E3cek ; ?> Prosedur Tidak Diikuti / <em>Procedures Not Followed</em></td>
                </tr>
                <tr height="19px;">
                    <td bgcolor="#F5FEFE" colspan="3">
                        &nbsp;<?php echo $E4cek ; ?> Petunjuk Standar Tidak Memadai / <em>Orderliness Standards Inaquate</em></td>
                </tr>
                <tr height="19px;">
                    <td bgcolor="#E1FFFF" colspan="3">
                        &nbsp;<?php echo $E5cek ; ?> Petunjuk Standar Tidak Diketahui / Dimengerti / <em>Orderliness Standards Not Known / Understood</em></td>
                </tr>
                <tr height="19px;">
                    <td bgcolor="#F5FEFE" colspan="3">
                        &nbsp;<?php echo $E6cek ; ?> Prosedur Tidak Diikuti / <em>Orderliness Not Followed</em></td>
                </tr>
                <tr><td style="border-bottom: ridge 2px #CCC;" colspan="3">&nbsp;</td></tr>
<!-- END OF 5 ================================================================================================================ -->
				<tr>
                    <td align="center" style="font-size:12px;color:#666;" colspan="3">
                        <br/>
                        <b>LAPORAN PENGAMATAN /<em>OBSERVATION REPORT</em></b>
                    </td>
                </tr>
                <tr>
                  <td colspan="3"><br/>
                    	<ul>
                        	<li style="margin-left:-15px;">Tindakan Aman yang Diamati / <em>Safe Acts Observerd</em></li>
                            <li style="margin-left:-15px;">Rekomendasi untuk peningkatan Keselamatan / <em>Recommendation for Safety Improvement</em></li>
                          	<li style="list-style:none;margin-left:-15px;"><textarea class="elementDefault" name="lapAman" id="lapAman" style="width:440px;height:150px;" readonly><?php echo $aman ; ?></textarea></li>
							<br/>
                        	<li style="margin-left:-15px;">Tindakan <span style="color:red">Tidak</span> Aman yang Diamati / <em>Unsafe Acts Observerd</em></li>
                            <li style="margin-left:-15px;">Tindakan Perbaikan / <em>Immediate Corrective Action</em></li>
                            <li style="margin-left:-15px;">Tindakan Pencegahan / <em>Action to Prevent Recurrence</em></li>
                            <li style="list-style:none;margin-left:-15px;"><textarea class="elementDefault" name="lapTidakAman" id="lapTidakAman" style="width:440px;height:150px;" readonly><?php echo $tidakAman ; ?></textarea></li>
                        </ul>
                    </td>
                </tr>
                <tr>
                	<td colspan="3">Lokasi / <em>Location</em> : <input type="text" class="elementDefault" name="lokasi" id="lokasi" style="width:375px;height:28px;" value="<?php echo $CPublic->konversiQuotes($lokasi); ?>" readonly></td>
                </tr>
                <tr>
                	<td colspan="3"><br/>Komentar / <em>Comment</em> :</textarea></td>
                </tr>
                <tr>
                	<td colspan="3"><textarea class="elementDefault" name="komen" id="komen" style="width:440px;height:75px;margin-left:25px;"" onKeyDown="disBtn();" onKeyUp="disBtn();"><?php echo $komen ; ?></textarea></td>
                </tr>
                <div id="idAdaTidak"><input type="hidden" id="adaTidak"/></div>
            </table>
		</form>
		</div>
	</td>
<tr valign="middle"><td colspan="2" bgcolor="#FFFFFF" align="left"><!--<blink><span id="errorMsg" class="errorMsg"></span></blink>&nbsp;--></td></tr>
<tr valign="top">
	<td class="tdMyFolder" colspan="2" bgcolor="#FFFFFF" height="65" valign="middle" style="cursor:default;">
    &nbsp;<button class="btnStandarKecil" onMouseOver="this.className='btnStandarKecilHover'" onMouseOut="this.className='btnStandarKecil'" type="button" style="width:90px;height:55px;" onClick="tutup();return false;" title="Close View Stop Card" id="btnClose">
        	<table width="100%" height="100%" class="fontBtnKecil" onMouseOver="this.className='fontBtnKecilHover'" onMouseOut="this.className='fontBtnKecil'">
            <tr>
            	<td align="center"><img src="../../picture/Metro-Shut-Down-Blue-32.png" height="25"/> </td>
            </tr>
            <tr>
            	<td align="center">CLOSE</td>
            </tr>
        	</table>
        </button>
        &nbsp;
        <button class="btnStandarKecil" onMouseOver="this.className='btnStandarKecilHover'" onMouseOut="this.className='btnStandarKecil'" onClick="formViewCard.reset();disBtn();" style="width:90px;height:55px;" title="UNDO (Back to Current Condition)">
            <table class="fontBtnKecil" onMouseOver="this.className='fontBtnKecilHover'" onMouseOut="this.className='fontBtnKecil'" border="0" width="100%" height="100%">
            <tr>
                <td align="center"><img src="../../picture/Button-Synchronize-blue-32.png" height="25"/> </td> 
            </tr>
            <tr>
                <td align="center">RESET</td>
            </tr>
            </table>
        </button>
        
</tr>
</table>
</center>
</body>
<script language="javascript">
<?php
function statusCek($CKoneksi, $idKeluhanGet, $field)
{
	$cek = "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
	$query = $CKoneksi->mysqlQuery("SELECT ".$field." FROM tblkeluhan WHERE idkeluhan = '".$idKeluhanGet."' AND deletests=0 LIMIT 1");
	$row = $CKoneksi->mysqlFetch($query);
	
	if($row[$field] == "Y")
	{
		$cek = "<img src=\"../../picture/symbol_check.png\" width=\"20\" style=\"vertical-align:middle;\"/> ";
	}
	return $cek;
}
?>
</script>