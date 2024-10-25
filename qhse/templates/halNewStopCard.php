<?php
require_once("../../config.php");
	$time = $CPublic->zerofill($CPublic->jamServer());
	$tgl = $CPublic->zerofill($CPublic->waktuServer("tanggal"), 2); 
	$bln = $CPublic->zerofill($CPublic->waktuServer("bulan"), 2); 
	$thn = $CPublic->waktuServer("tahun");
	$dateAct = $tgl."/".$bln."/".$thn;
	$date = $thn."-".$bln."-".$tgl;
	
if($aksiGet == "simpanNewCard")
{
	
	$aman = mysql_real_escape_string($_POST['lapAman']);
	$tidakAman = mysql_real_escape_string($_POST['lapTidakAman']);
	$lokasi = mysql_real_escape_string($_POST['lokasi']);
	
	$A0 = changeValue('A0');
	$A1 = changeValue('A1');
	$A2 = changeValue('A2');
	$A3 = changeValue('A3');
	$A4 = changeValue('A4');
	$A5 = changeValue('A5');
	$A6 = changeValue('A6');
	$B0 = changeValue('B0');
	$B1 = changeValue('B1');
	$B2 = changeValue('B2');
	$B3 = changeValue('B3');
	$B4 = changeValue('B4');
	$B5 = changeValue('B5');
	$B6 = changeValue('B6');
	$C0 = changeValue('C0');
	$C1 = changeValue('C1');
	$C2 = changeValue('C2');
	$C3 = changeValue('C3');
	$C4 = changeValue('C4');
	$C5 = changeValue('C5');
	$C6 = changeValue('C6');
	$C7 = changeValue('C7');
	$C8 = changeValue('C8');
	$C9 = changeValue('C9');
	$C10 = changeValue('C10');
	$C11 = changeValue('C11');
	$D0 = changeValue('D0');
	$D1 = changeValue('D1');
	$D2 = changeValue('D2');
	$D3 = changeValue('D3');
	$E0 = changeValue('E0');
	$E1 = changeValue('E1');
	$E2 = changeValue('E2');
	$E3 = changeValue('E3');
	$E4 = changeValue('E4');
	$E5 = changeValue('E5');
	$E6 = changeValue('E6');

	/*echo "ID: ".$userIdLogin." name : ".$userFullnm." tanggal : ".$date." waktu : ".$time." Aman : ".$aman." tidak aman : ".$tidakAman." Lokasi : ".$lokasi." A0 : ".$A0." A1 : ".$A1." A2 : ".$A2." A3 : ".$A3." A4 : ".$A4." A5 : ".$A5." A6 : ".$A6." B0 : ".$B0." B1 : ".$B1." B2 : ".$B2." B3 : ".$B3." B4 : ".$B4." B5 : ".$B5." B6 : ".$B6." C0 : ".$C0." C1 : ".$C1." C2 : ".$C2." C3 : ".$C3." C4 : ".$C4." C5 : ".$C5." C6 : ".$C6." C7 : ".$C7." C8 : ".$C8." C9 : ".$C9." C10 : ".$C10." C11 : ".$C11." D0 : ".$D0." D1 : ".$D1." D2 : ".$D2." D3 : ".$D3." E0 : ".$E0." E1 : ".$E1." E2 : ".$E2." E3 : ".$E3." E4 : ".$E4." E5 : ".$E5." E6 : ".$E6;*/
	$CKoneksi->mysqlQuery("INSERT INTO tblkeluhan (ownerid, ownername, time, date, A0, A1, A2, A3, A4, A5, A6, B0, B1, B2, B3, B4, B5, B6, C0, C1, C2, C3, C4, C5, C6, C7, C8, C9, C10, C11, D0, D1, D2, D3, E0, E1, E2, E3, E4, E5, E6, aman, tidakaman, lokasi, addusrdt) VALUES ('".$userIdLogin."', '".$userFullnm."', '".$time."', '".$date."', '".$A0."', '".$A1."', '".$A2."', '".$A3."', '".$A4."', '".$A5."', '".$A6."', '".$B0."', '".$B1."', '".$B2."', '".$B3."', '".$B4."', '".$B5."', '".$B6."', '".$C0."', '".$C1."', '".$C2."', '".$C3."', '".$C4."', '".$C5."', '".$C6."', '".$C7."', '".$C8."', '".$C9."', '".$C10."', '".$C11."', '".$D0."', '".$D1."', '".$D2."', '".$D3."', '".$E0."', '".$E1."', '".$E2."', '".$E3."', '".$E4."', '".$E5."', '".$E6."', '".$aman."', '".$tidakAman."', '".$lokasi."', '".$CPublic->userWhoAct()."')");
	$lastInsertId = mysql_insert_id();
	$CHistory->updateLogQhse($userIdLogin, "Buat Stop Card baru (idkeluhan = <b>".$lastInsertId."</b>, Date = <b>".$date."</b>, Time = <b>".$time."</b>, Lokasi = <b>".$lokasi."</b>)");
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
function klikCekBox(status, idCekBox, jml)
{	
	if(status==true)
	{
		status = false;
	}
		
	for(i=1; i<=jml; i++)
	{
		document.getElementById(idCekBox+i).checked=status;
	}
}

function unCek(elementId, status)
{
	if(status==true)
	{
		status = false;
	}
	document.getElementById(elementId).checked=status;
	
}

function pilihCek(tipe, nilaiCek)
{
	if(tipe == "A0")
	{
		document.getElementById('jmlCekA').value = "0";
	}
	if(tipe == "A")
	{
		var jmlCekA = parseInt (document.getElementById('jmlCekA').value);
		if(nilaiCek == true)
		{
			document.getElementById('jmlCekA').value = parseInt( jmlCekA + 1 );
		}
		else if(nilaiCek == false)
		{
			document.getElementById('jmlCekA').value = parseInt( jmlCekA - 1 );
		}
	}
	if(tipe == "B0")
	{
		document.getElementById('jmlCekB').value = "0";
	}
	
	if(tipe == "B")
	{
		var jmlCekB = parseInt (document.getElementById('jmlCekB').value);
		if(nilaiCek == true)
		{
			document.getElementById('jmlCekB').value = parseInt( jmlCekB + 1 );
		}
		else if(nilaiCek == false)
		{
			document.getElementById('jmlCekB').value = parseInt( jmlCekB - 1 );
		}
	}
	
	if(tipe == "C0")
	{
		document.getElementById('jmlCekC').value = "0";
	}
	if(tipe == "C")
	{
		var jmlCekC = parseInt (document.getElementById('jmlCekC').value);
		if(nilaiCek == true)
		{
			document.getElementById('jmlCekC').value = parseInt( jmlCekC + 1 );
		}
		else if(nilaiCek == false)
		{
			document.getElementById('jmlCekC').value = parseInt( jmlCekC - 1 );
		}
	}
	
	if(tipe == "D0")
	{
		document.getElementById('jmlCekD').value = "0";
	}
	if(tipe == "D")
	{
		var jmlCekD = parseInt (document.getElementById('jmlCekD').value);
		if(nilaiCek == true)
		{
			document.getElementById('jmlCekD').value = parseInt( jmlCekD + 1 );
		}
		else if(nilaiCek == false)
		{
			document.getElementById('jmlCekD').value = parseInt( jmlCekD - 1 );
		}
	}
	
	if(tipe == "E0")
	{
		document.getElementById('jmlCekE').value = "0";
	}
	if(tipe == "E")
	{
		var jmlCekE = parseInt (document.getElementById('jmlCekE').value);
		if(nilaiCek == true)
		{
			document.getElementById('jmlCekE').value = parseInt( jmlCekE + 1 );
		}
		else if(nilaiCek == false)
		{
			document.getElementById('jmlCekE').value = parseInt( jmlCekE - 1 );
		}
	}
}

function pilihBtnSave()
{
	document.getElementById('errorMsg').innerHTML = "&nbsp;";
	
	var lapAman = document.getElementById('lapAman').value.replace(/&/g,"%26");
	var lapTidakAman = document.getElementById('lapTidakAman').value.replace(/&/g,"%26");
	var lokasi = document.getElementById('lokasi').value.replace(/&/g,"%26"); 
	var A0 = document.getElementById('A0').checked;
	var B0 = document.getElementById('B0').checked;
	var C0 = document.getElementById('C0').checked;
	var D0 = document.getElementById('D0').checked;
	var E0 = document.getElementById('E0').checked; 

	if(A0 == false) // TIDAK DICENTANG
	{
		if(document.getElementById('jmlCekA').value == "0")
		{
			document.getElementById('errorMsg').innerHTML = "<img src=\"../../picture/exclamation-red.png\" width=\"14\"/>&nbsp;REAKSI SESEORANG Column, Please check Safe/Unsafe!";
			document.getElementById('A0').focus(); 
	return false;
		}
	}
	
	if(B0 == false) // TIDAK DICENTANG
	{
		if(document.getElementById('jmlCekB').value == "0")
		{
			document.getElementById('errorMsg').innerHTML = "<img src=\"../../picture/exclamation-red.png\" width=\"14\"/>&nbsp;ALAT PELINDUNG PRIBADI Column, Please check Safe/Unsafe!";
			document.getElementById('B0').focus(); 
	return false;
		}
	}
	
	if(C0 == false) // TIDAK DICENTANG
	{
		if(document.getElementById('jmlCekC').value == "0")
		{
			document.getElementById('errorMsg').innerHTML = "<img src=\"../../picture/exclamation-red.png\" width=\"14\"/>&nbsp;POSISI SESEORANG Column, Please check Safe/Unsafe!";
			document.getElementById('C0').focus(); 
	return false;
		}
	}
	
	if(D0 == false) // TIDAK DICENTANG
	{
		if(document.getElementById('jmlCekD').value == "0")
		{
			document.getElementById('errorMsg').innerHTML = "<img src=\"../../picture/exclamation-red.png\" width=\"14\"/>&nbsp;PERKAKAS DAN PERALATAN Column, Please check Safe/Unsafe!";
			document.getElementById('D0').focus(); 
	return false;
		}
	}
	
	if(E0 == false) // TIDAK DICENTANG
	{
		if(document.getElementById('jmlCekE').value == "0")
		{
			document.getElementById('errorMsg').innerHTML = "<img src=\"../../picture/exclamation-red.png\" width=\"14\"/>&nbsp;PROSEDUR DAN PETUNJUK Column, Please check Safe/Unsafe!";
			document.getElementById('E0').focus(); 
	return false;
		}
	}

	if(lokasi.replace(/ /g,"") == "")
	{
		document.getElementById('errorMsg').innerHTML = "<img src=\"../../picture/exclamation-red.png\" width=\"14\"/>&nbsp;Location still empty!";
		document.getElementById('lokasi').focus(); 
		return false;
	}
	
	var answer  = confirm("Are you sure want to save?");
	if(answer)
	{	
		formNewCard.submit();
	}
	else
	{	return false;	}
}

function tutup()
{
	var answer  = confirm("Are you sure want to close?");
	if(answer)
	{
		exit();
	}
	else
	{	return false;	}
}
function exit()
{
	parent.tb_remove(false);
	parent.document.getElementById('iframeHalStopCardList').src = "";
	parent.document.getElementById('iframeHalStopCardList').src = "templates/halStopCardList.php";
	//parent.gantiDateAct('<?php //echo $dateActGet; ?>');
}

setInterval(function(){ $('blink').each( function(){ $(this).css('visibility' , $(this).css('visibility') === 'hidden' ? '' : 'hidden') } ); }, 850);
</script>

<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportal/css/main.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportal/css/archives.css" rel="stylesheet" type="text/css" />

<body bgcolor="#F8F8F8" bottommargin="3" topmargin="3">
<center>

<input type="hidden" id="jmlCekA" value="0">
<input type="hidden" id="jmlCekB" value="0">
<input type="hidden" id="jmlCekC" value="0">
<input type="hidden" id="jmlCekD" value="0">
<input type="hidden" id="jmlCekE" value="0">
<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
<tr valign="top">
	<td width="30%" height="25"><span class="teksLvlFolder" style="color:#666;">Date : </span>
        <span class="formInput" style="text-decoration:underline;color:#00F;font-weight:bold;"><?php echo $dateAct; ?></span></td>
    <td width="70%" align="right" ><span class="teksLvlFolder" style="color:#999;font-size:16px;font-style:italic;"><b>:: Create New Safety Observation ::</b></span>&nbsp;</td>
</tr>

<tr valign="top">
    <td class="tdMyFolder" bgcolor="#FFFFFF" colspan="2" align="center" valign="top" style="cursor:default;">
    	<div style="width:510px;height:440px;overflow:scroll;overflow-x: hidden;top: expression(offsetParent.scrollTop);background-color:#FFF;">
        <form action="halNewStopCard.php?aksi=simpanNewCard&dateAct=<?php echo $dateAct; ?>" method="post" id="formNewCard" name="formNewCard">
            <table cellpadding="0" cellspacing="0" height="100%" width="99%" border="0" style="font-size:11px;font-family:tahoma;">
            	<tr>
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
                <tr>
                    <td colspan="2">
                        <b>REAKSI SESEORANG / <em>REACTION OF PEOPLE</em></b> </td><td width="4%" align="right"><input type="checkbox" name="A0" id="A0" onClick="klikCekBox(this.checked, 'A', 6); pilihCek('A0', this.checked);">
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#E1FFFF" colspan="3">
                        &nbsp;<input type="checkbox" name="A1" id="A1" onClick="unCek('A0', this.checked); pilihCek('A', this.checked);"> Penyesuaian Penggunaan APD / <em>Adjusting PPE</em></td>
                </tr>
                <tr>
                    <td bgcolor="#F5FEFE" colspan="3">
                        &nbsp;<input type="checkbox" name="A2" id="A2" onClick="unCek('A0', this.checked); pilihCek('A', this.checked);"> Merubah Posisi / <em>Changing Position</em></td>
                </tr>
                <tr>
                    <td bgcolor="#E1FFFF" colspan="3">
                        &nbsp;<input type="checkbox" name="A3" id="A3" onClick="unCek('A0', this.checked); pilihCek('A', this.checked);"> Mengatur Pekerjaan /<em> Rearranging Job</em></td>
                </tr>
                <tr>
                    <td bgcolor="#F5FEFE" colspan="3">
                        &nbsp;<input type="checkbox" name="A4" id="A4" onClick="unCek('A0', this.checked) ;pilihCek('A', this.checked);"> Menghentikan Pekerjaan / <em>Stopping Job</em></td>
                </tr>
                <tr>
                    <td bgcolor="#E1FFFF" colspan="3">
                        &nbsp;<input type="checkbox" name="A5" id="A5" onClick="unCek('A0', this.checked);pilihCek('A', this.checked);"> Memasang Arde / <em>Attaching Ground</em></td>
                </tr>
                <tr>
                    <td style="border-bottom: dashed 1px #CCC;" bgcolor="#F5FEFE" colspan="3">
                        &nbsp;<input type="checkbox" name="A6" id="A6" onClick="unCek('A0', this.checked); pilihCek('A', this.checked);" > Melakukan Penguncian / <em>Performing Lockouts</em></td>
                </tr>
<!-- END OF 1 ================================================================================================================ -->
                <tr valign="bottom">
                	<td width="53%">
                    	&nbsp;<img src="../../picture/symbol_check.png" width="20" style="vertical-align:middle;"/> Beri tanda bila tidak aman
                    </td>	
                    <td align="right" colspan="2">
                    	Beritahu bila aman <img src="../../picture/symbol_check.png" width="20" style="vertical-align:middle;"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <b>ALAT PELINDUNG PRIBADI / <em>PERSONAL PROTECTIVE EQUIPMENT</em></b> </td><td width="4%" align="right"><input type="checkbox" name="B0" id="B0" onClick="klikCekBox(this.checked, 'B', 6); pilihCek('B0', this.checked);">
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#E1FFFF" colspan="3">
                        &nbsp;<input type="checkbox" name="B1" id="B1" onClick="unCek('B0', this.checked); pilihCek('B', this.checked);"> Kepala / <em>Head</em></td>
                </tr>
                <tr>
                    <td bgcolor="#F5FEFE" colspan="3">
                        &nbsp;<input type="checkbox" name="B2" id="B2" onClick="unCek('B0', this.checked); pilihCek('B', this.checked);"> Mata dan Muka / <em>Eyes and Face </em></td>
                </tr>
                <tr>
                    <td bgcolor="#E1FFFF" colspan="3">
                        &nbsp;<input type="checkbox" name="B3" id="B3" onClick="unCek('B0', this.checked); pilihCek('B', this.checked);"> Telinga / <em>Ear</em></td>
                </tr>
                <tr>
                    <td bgcolor="#F5FEFE" colspan="3">
                        &nbsp;<input type="checkbox" name="B4" id="B4" onClick="unCek('B0', this.checked); pilihCek('B', this.checked);"> Sistem Pernapasan / <em>Respiratory System</em></td>
                </tr>
                <tr>
                    <td bgcolor="#E1FFFF" colspan="3">
                        &nbsp;<input type="checkbox" name="B5" id="B5" onClick="unCek('B0', this.checked); pilihCek('B', this.checked);"> Lengan dan Tangan /<em> Arms and Hands</em></td>
                </tr>
                <tr>
                    <td style="border-bottom: dashed 1px #CCC;" bgcolor="#F5FEFE" colspan="3">
                        &nbsp;<input type="checkbox" name="B6" id="B6" onClick="unCek('B0', this.checked); pilihCek('B', this.checked);" > Kaki dan Telapak Kaki / <em>Leg - Foot</em></td>
                </tr>
<!-- END OF 2 ================================================================================================================ -->
                <tr valign="bottom">
                	<td width="53%">
                    	&nbsp;<img src="../../picture/symbol_check.png" width="20" style="vertical-align:middle;"/> Beri tanda bila tidak aman
                    </td>	
                    <td align="right" colspan="2">
                    	Beritahu bila aman <img src="../../picture/symbol_check.png" width="20" style="vertical-align:middle;"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <b>POSISI SESEORANG (Penyebab Cidera) / <em>POSITIONS OF PEOPLE</em> (<em>Injury Causes</em>)</b> </td><td align="right"><input type="checkbox" name="C0" id="C0" onClick="klikCekBox(this.checked, 'C', 11); pilihCek('C0', this.checked);">
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#E1FFFF" colspan="3">
                        &nbsp;<input type="checkbox" name="C1" id="C1" onClick="unCek('C0', this.checked); pilihCek('C', this.checked);"> Menabrak Barang / <em>Striking Againts Objects</em></td>
                </tr>
                <tr>
                    <td bgcolor="#F5FEFE" colspan="3">
                        &nbsp;<input type="checkbox" name="C2" id="C2" onClick="unCek('C0', this.checked); pilihCek('C', this.checked);"> 
                        Tertimpa Barang / <em>Struck by Objects</em></td>
                </tr>
                <tr>
                    <td bgcolor="#E1FFFF" colspan="3">
                        &nbsp;<input type="checkbox" name="C3" id="C3" onClick="unCek('C0', this.checked); pilihCek('C', this.checked);"> 
                        Terhimpit Barang / <em>Caught In, On, or Between Objects</em></td>
                </tr>
                <tr>
                    <td bgcolor="#F5FEFE" colspan="3">
                        &nbsp;<input type="checkbox" name="C4" id="C4" onClick="unCek('C0', this.checked); pilihCek('C', this.checked);"> 
                        Jatuh / <em>Falling</em></td>
                </tr>
                <tr>
                    <td bgcolor="#E1FFFF" colspan="3">
                        &nbsp;<input type="checkbox" name="C5" id="C5" onClick="unCek('C0', this.checked); pilihCek('C', this.checked);"> 
                        Berada di Tempat Sangat Panas / <em>Contracting Temperature Extremes</em></td>
                </tr>
                <tr>
                    <td bgcolor="#F5FEFE" colspan="3">
                        &nbsp;<input type="checkbox" name="C6" id="C6" onClick="unCek('C0', this.checked); pilihCek('C', this.checked);"> 
                        Menghisap / <em>Inhaling</em> (Zat Berbahaya / <em>Absorbing a Hazardous Substance</em>)</td>
                </tr>
                <tr>
                    <td bgcolor="#E1FFFF" colspan="3">
                        &nbsp;<input type="checkbox" name="C7" id="C7" onClick="unCek('C0', this.checked); pilihCek('C', this.checked);"> 
                        Absorbsi (Zat Berbahaya / <em>Absorbing a Hazardous Substance</em>)</td>
                </tr>
                <tr>
                    <td bgcolor="#F5FEFE" colspan="3">
                        &nbsp;<input type="checkbox" name="C8" id="C8" onClick="unCek('C0', this.checked); pilihCek('C', this.checked);"> 
                        Menelan / <em>Swallowing</em> (Zat Berbahaya / <em>Absorbing a Hazardous Substance</em>)</td>
                </tr>
                <tr>
                    <td bgcolor="#E1FFFF" colspan="3">
                        &nbsp;<input type="checkbox" name="C9" id="C9" onClick="unCek('C0', this.checked); pilihCek('C', this.checked);"> 
                        Gerakan Membahayakan / <em>Over Exertion</em></td>
                </tr>
                <tr>
                    <td bgcolor="#F5FEFE" colspan="3">
                        &nbsp;<input type="checkbox" name="C10" id="C10" onClick="unCek('C0', this.checked); pilihCek('C', this.checked);"> 
                        Gerakan Berlebihan / <em>Repetitive Motion</em></td>
                </tr>
                <tr>
                    <td style="border-bottom: dashed 1px #CCC;" bgcolor="#E1FFFF" colspan="3">
                        &nbsp;<input type="checkbox" name="C11" id="C11" onClick="unCek('C0', this.checked); pilihCek('C', this.checked);" >   	   
                         Posisi Janggal / <em>Awkward Position </em>/ <em>Static Postures</em></td>
                </tr>
<!-- END OF 3 ================================================================================================================ -->
                <tr valign="bottom">
                	<td width="53%">
                    	&nbsp;<img src="../../picture/symbol_check.png" width="20" style="vertical-align:middle;"/> Beri tanda bila tidak aman
                    </td>	
                    <td align="right" colspan="2">
                    	Beritahu bila aman <img src="../../picture/symbol_check.png" width="20" style="vertical-align:middle;"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <b>PERKAKAS DAN PERALATAN / <em>TOOLS AND EQUIPMENT</em></b> </td><td width="4%" align="right"><input type="checkbox" name="D0" id="D0" onClick="klikCekBox(this.checked, 'D', 3); pilihCek('D0', this.checked);">
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#E1FFFF" colspan="3">
                        &nbsp;<input type="checkbox" name="D1" id="D1" onClick="unCek('D0', this.checked); pilihCek('D', this.checked);">
                        Tidak sesuai dengan pekerjaan / <em>Wrong the Job</em></td>
                </tr>
                <tr>
                    <td bgcolor="#F5FEFE" colspan="3">
                        &nbsp;<input type="checkbox" name="D2" id="D2" onClick="unCek('D0', this.checked); pilihCek('D', this.checked);"> 
                        Salah Penggunaan / <em>Used Incorrectly</em></td>
                </tr>
                <tr>
                    <td style="border-bottom: dashed 1px #CCC;" bgcolor="#E1FFFF" colspan="3">
                        &nbsp;<input type="checkbox" name="D3" id="D3" onClick="unCek('D0', this.checked); pilihCek('D', this.checked);" > 
                        Berada di Tempat yang Tidak Aman / <em>In Unsafe Condition</em></td>
                </tr>
<!-- END OF 4 ================================================================================================================ -->
                <tr valign="bottom">
                	<td width="53%">
                    	&nbsp;<img src="../../picture/symbol_check.png" width="20" style="vertical-align:middle;"/> Beri tanda bila tidak aman
                    </td>	
                    <td align="right" colspan="2">
                    	Beritahu bila aman <img src="../../picture/symbol_check.png" width="20" style="vertical-align:middle;"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <b>PROSEDUR DAN PETUNJUK / <em>PROCEDURES AND ORDERLINESS</em></b> </td><td align="right"><input type="checkbox" name="E0" id="E0" onClick="klikCekBox(this.checked, 'E', 6); pilihCek('E0', this.checked);">
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#E1FFFF" colspan="3">
                        &nbsp;<input type="checkbox" name="E1" id="E1" onClick="unCek('E0', this.checked); pilihCek('E', this.checked);"> Prosedur Tidak Memadai / <em>Procedures Inaquate</em></td>
                </tr>
                <tr>
                    <td bgcolor="#F5FEFE" colspan="3">
                        &nbsp;<input type="checkbox" name="E2" id="E2" onClick="unCek('E0', this.checked); pilihCek('E', this.checked);"> Prosedur Tidak Diketahui / Tidak Mengerti / <em>Procedures Not Known / Understood</em></td>
                </tr>
                <tr>
                    <td bgcolor="#E1FFFF" colspan="3">
                        &nbsp;<input type="checkbox" name="E3" id="E3" onClick="unCek('E0', this.checked); pilihCek('E', this.checked);"> Prosedur Tidak Diikuti / <em>Procedures Not Followed</em></td>
                </tr>
                <tr>
                    <td bgcolor="#F5FEFE" colspan="3">
                        &nbsp;<input type="checkbox" name="E4" id="E4" onClick="unCek('E0', this.checked); pilihCek('E', this.checked);"> Petunjuk Standar Tidak Memadai / <em>Orderliness Standards Inaquate</em></td>
                </tr>
                <tr>
                    <td bgcolor="#E1FFFF" colspan="3">
                        &nbsp;<input type="checkbox" name="E5" id="E5" onClick="unCek('E0', this.checked); pilihCek('E', this.checked);"> Petunjuk Standar Tidak Diketahui / Dimengerti / <em>Orderliness Standards Not Known / Understood</em></td>
                </tr>
                <tr>
                    <td bgcolor="#F5FEFE" colspan="3">
                        &nbsp;<input type="checkbox" name="E6" id="E6" onClick="unCek('E0', this.checked); pilihCek('E', this.checked);" > Prosedur Tidak Diikuti / <em>Orderliness Not Followed</em></td>
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
                          	<li style="list-style:none;margin-left:-15px;"><textarea class="elementDefault" name="lapAman" id="lapAman" style="width:440px;height:150px;" ></textarea></li>
							<br/>
                        	<li style="margin-left:-15px;">Tindakan <span style="color:red">Tidak</span> Aman yang Diamati / <em>Unsafe Acts Observerd</em></li>
                            <li style="margin-left:-15px;">Tindakan Perbaikan / <em>Immediate Corrective Action</em></li>
                            <li style="margin-left:-15px;">Tindakan Pencegahan / <em>Action to Prevent Recurrence</em></li>
                            <li style="list-style:none;margin-left:-15px;"><textarea class="elementDefault" name="lapTidakAman" id="lapTidakAman" style="width:440px;height:150px;" ></textarea></li>
                        </ul>
                    </td>
                </tr>
                <tr>
                	<td colspan="3">Lokasi / <em>Location</em> : <input type="text" class="elementDefault" name="lokasi" id="lokasi" style="width:375px;height:28px;"></td>
                </tr>
            </table>
		</form>
		</div>
	</td>
<tr valign="middle"><td colspan="2" bgcolor="#FFFFFF" align="left"><blink><span id="errorMsg" class="errorMsg"></span></blink>&nbsp;</td></tr>
<tr valign="top">
	<td class="tdMyFolder" colspan="2" bgcolor="#FFFFFF" height="65" valign="middle" style="cursor:default;">
    &nbsp;<button class="btnStandarKecil" onMouseOver="this.className='btnStandarKecilHover'" onMouseOut="this.className='btnStandarKecil'" type="button" style="width:90px;height:55px;" onClick="tutup();" title="Close Create New Stop Card">
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
        <button class="btnStandarKecil" onMouseOver="this.className='btnStandarKecilHover'" onMouseOut="this.className='btnStandarKecil'" onClick="pilihBtnSave();return false;" style="width:90px;height:55px;" title="Save New Stop Card">
            <table class="fontBtnKecil" onMouseOver="this.className='fontBtnKecilHover'" onMouseOut="this.className='fontBtnKecil'" border="0" width="100%" height="100%">
            <tr>
                <td align="center"><img src="../../picture/Floppy-Disk-blue-32.png" height="25"/> </td> 
            </tr>
            <tr>
                <td align="center">SUBMIT</td>
            </tr>
            </table>
        </button>
        &nbsp;
        <button class="btnStandarKecil" onMouseOver="this.className='btnStandarKecilHover'" onMouseOut="this.className='btnStandarKecil'" onClick="formNewCard.reset();" style="width:90px;height:55px;" title="UNDO (Back to Current Condition)">
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
function changeValue($box)
{
	$value = "N";
	
	if($_POST[$box] == on)
	{
		$value = "Y";
	}
	return $value;
}
if($aksiGet == "simpanNewCard")
{
	echo "exit();";
}
?>
</script>