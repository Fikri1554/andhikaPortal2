<?php
require_once("../../config.php");

$pin = $_GET['pin'];

if($aksiGet == "submitSurvey")
{
	$pin = $_POST['pin'];
	$time = $CPublic->zerofill($CPublic->jamServer());
	$tgl = $CPublic->zerofill($CPublic->waktuServer("tanggal"), 2); 
	$bln = $CPublic->zerofill($CPublic->waktuServer("bulan"), 2); 
	$thn = $CPublic->waktuServer("tahun");
	$date = $thn."-".$bln."-".$tgl;
	$dateTime = $date." ".$time;
	
	/*echo "time : ".$dateTime." date : ".$date." pin : ".$pin."<br/>A1 : ".$_POST['A1']." A2 : ".$_POST['A2']." A3 : ".$_POST['A3']."<br/> B1 : ".$_POST['B1']." B2 : ".$_POST['B2']." B3 : ".$_POST['B3']." B4 : ".$_POST['B4']." B5 : ".$_POST['B5']." B6 : ".$_POST['B6']." B7 : ".$_POST['B7']." B8 : ".$_POST['B8']."<br/> C1 : ".$_POST['C1']." C2 : ".$_POST['C2']." C3 : ".$_POST['C3']." C4 : ".$_POST['C4']." C5 : ".$_POST['C5']."<br/> D1 : ".$_POST['D1']." D2 : ".$_POST['D2']." D3 : ".$_POST['D3']." D4 : ".$_POST['D4']." D5 : ".$_POST['D5']." D6 : ".$_POST['D6']." D7 : ".$_POST['D7']." D8 : ".$_POST['D8']." D9 : ".$_POST['D9']." D10 : ".$_POST['D10']." D11 : ".$_POST['D11']." D12 : ".$_POST['D12']."<br/> E1 : ".$_POST['E1']." E2 : ".$_POST['E2']." E3 : ".$_POST['E3']." E4 : ".$_POST['E4']."<br/> F1 : ".$_POST['F1']." F2 : ".$_POST['F2']." F3 : ".$_POST['F3']." F4 : ".$_POST['F4']."<br/> G1 : ".$_POST['G1']." G2 : ".$_POST['G2']." G3 : ".$_POST['G3']." G4 : ".$_POST['G4'];*/
	
	$CKoneksi->mysqlQuery("UPDATE tblsurvey SET timesubmit='".$dateTime."', used = 'Y', A1='".$_POST['A1']."', A2='".$_POST['A2']."', A3='".$_POST['A3']."', B1='".$_POST['B1']."', B2='".$_POST['B2']."', B3='".$_POST['B3']."', B4='".$_POST['B4']."', B5='".$_POST['B5']."', B6='".$_POST['B6']."', B7='".$_POST['B7']."', B8='".$_POST['B8']."', C1='".$_POST['C1']."', C2='".$_POST['C2']."', C3='".$_POST['C3']."', C4='".$_POST['C4']."', C5='".$_POST['C5']."', D1='".$_POST['D1']."', D2='".$_POST['D2']."', D3='".$_POST['D3']."', D4='".$_POST['D4']."', D5='".$_POST['D5']."', D6='".$_POST['D6']."', D7='".$_POST['D7']."', D8='".$_POST['D8']."', D9='".$_POST['D9']."', D10='".$_POST['D10']."', D11='".$_POST['D11']."', D12='".$_POST['D12']."', E1='".$_POST['E1']."', E2='".$_POST['E2']."', E3='".$_POST['E3']."', E4='".$_POST['E4']."', F1='".$_POST['F1']."', F2='".$_POST['F2']."', F3='".$_POST['F3']."', F4='".$_POST['F4']."', G1='".$_POST['G1']."', G2='".$_POST['G2']."', G3='".$_POST['G3']."', G4='".$_POST['G4']."' WHERE pin = '".$pin."' AND used = 'N'");
}
?>
<script type="text/javascript" src="../../js/main.js"></script>
<link href="../../css/main.css" rel="stylesheet" type="text/css"/>
<link href="../../css/report.css" rel="stylesheet" type="text/css"/>
<link href="../../css/archives.css" rel="stylesheet" type="text/css"/>
<link href="../../andhikaportal/css/main.css" rel="stylesheet" type="text/css"/>
<link href="../../andhikaportal/css/archives.css" rel="stylesheet" type="text/css"/>

<script language="javascript">
function pinDisable()
{
	parent.document.getElementById('pin').value= '<?php echo $pin; ?>';
	parent.document.getElementById('pin').disabled= true;
	parent.document.getElementById('btnDisplay').className= 'btnStandarDisabled';
	parent.document.getElementById('btnDisplay').disabled= true;
}
function btnSubmit()
{
	var lanjut = "no";
	
	if(cekRadio('A1','A1cek') == 'true' && cekRadio('A2','A2cek') == 'true' && cekRadio('A3','A3cek') == 'true' && cekRadio('B1','B1cek') == 'true' && cekRadio('B2','B2cek') == 'true' && cekRadio('B3','B3cek') == 'true' && cekRadio('B4','B4cek') == 'true' && cekRadio('B5','B5cek') == 'true' && cekRadio('B6','B6cek') == 'true' && cekRadio('B7','B7cek') == 'true' && cekRadio('B8','B8cek') == 'true' && cekRadio('C1','C1cek') == 'true' && cekRadio('C2','C2cek') == 'true' && cekRadio('C3','C3cek') == 'true' && cekRadio('C4','C4cek') == 'true' && cekRadio('C5','C5cek') == 'true' && cekRadio('D1','D1cek') == 'true' && cekRadio('D2','D2cek') == 'true' && cekRadio('D3','D3cek') == 'true' && cekRadio('D4','D4cek') == 'true' && cekRadio('D5','D5cek') == 'true' && cekRadio('D6','D6cek') == 'true' && cekRadio('D7','D7cek') == 'true' && cekRadio('D8','D8cek') == 'true' && cekRadio('D9','D9cek') == 'true' && cekRadio('D10','D10cek') == 'true' && cekRadio('D11','D11cek') == 'true' && cekRadio('D12','D12cek') == 'true' && cekRadio('E1','E1cek') == 'true' && cekRadio('E2','E2cek') == 'true' && cekRadio('E3','E3cek') == 'true' && cekRadio('E4','E4cek') == 'true' && cekRadio('F1','F1cek') == 'true' && cekRadio('F2','F2cek') == 'true' && cekRadio('F3','F3cek') == 'true' && cekRadio('F4','F4cek') == 'true' && cekRadio('G1','G1cek') == 'true' && cekRadio('G2','G2cek') == 'true' && cekRadio('G3','G3cek') == 'true' && cekRadio('G4','G4cek') == 'true')
	{ lanjut = "yes";}

	if(lanjut == "no")
	{
		return false;
	}
	if(lanjut == "yes")
	{
		var answer  = confirm("Are you sure want to submit");
		if(answer)
		{
			surveyForm.submit();
		}
		else
		{	return false;	}
	}
}

function cekRadio(radioNumb, radioVar)
{
	var radio = document.getElementsByName(radioNumb);
	var pjgRadio = radio.length;
	var boolean = 'false';
	var statusRadio = "N";
	for (var i=0; i < pjgRadio; i++)
    {
		var radioVar = radio[i].checked;
		if(radioVar == true)
		{
			statusRadio = "Y";
		}
    }

	document.getElementById('errorMsg').innerHTML = "";
	document.getElementById('tdId'+radioNumb).innerHTML = "&nbsp;";
	if(statusRadio == "N")
	{
		document.getElementById('errorMsg').innerHTML = "<img src=\"../../picture/exclamation-red.png\" width=\"14\"/>&nbsp;Please fill all survey !";
		document.getElementById(radioNumb).focus();
		document.getElementById('tdId'+radioNumb).innerHTML = "<img src=\"../../picture/exclamation-red.png\">&nbsp;";
		boolean = 'false';
	}
	else
	{
		boolean = 'true';
	}
	return boolean;
}
</script>
<body onLoad="pinDisable();">
<table cellpadding="0" cellspacing="0" width="100%" height="400px" border="0"> 
<tr>
	<td width="2%">&nbsp;</td>
	<td width="96%">
    	<p ALIGN="CENTER" style="font-family:'Arial Narrow';font-size:18px;"><b>KUISIONER  INDEKS KEPUASAN KERJA KARYAWAN<br/>ANDHIKA  GROUP</b></p>
		<p ALIGN="JUSTIFY" style="font-family:'Arial Narrow';font-size:15px;"><b>Petunjuk  Pengisian :</b><br/>
		Pernyataan  berikut berkaitan dengan kepuasan kerja yang anda rasakan di Andhika  Group, anda diminta untuk mengungkapkan sejauh mana anda setuju bahwa  Andhika Group memiliki karakteristik yang sesuai dengan pernyataan.  Jika memilih &ldquo;1&rdquo; artinya SANGAT TIDAK SETUJU dengan pernyataan  dan sebaliknya untuk &ldquo;5&rdquo; artinya SANGAT SETUJU dengan pernyataan.  Anda diperbolehkan memilih angka &ldquo;1&rdquo; sampai dengan &ldquo;5&rdquo; sesuai  dengan penilaian anda.</p>
    </td>
    <td width="2%">&nbsp;</td>
</tr>
<tr><td colspan="3" height="5"></td></tr>
<tr>
	<td colspan="3">
    	<form name="surveyForm" method="post" action="halSatiSurvey.php?aksi=submitSurvey">
    	<table cellpadding="0" cellspacing="0" width="100%" style="font-family:'Arial Narrow';">
        <input type="hidden" id="pin" name="pin" value="<?php echo $pin; ?>"/>
<!-- START - Organisasi/ Perusahaan Group (A) =============================================================================== -->
            <tr align="center" bgcolor="#F3F3F3" style="font-size:18px;">
                <td colspan="3" width="80%" height="30" class="tabelBorderLeftRightNull"><b>Organisasi/ Perusahaan</b></td>
                <td width="4%" class="tabelBorderAll"><b>STS</b></td>
                <td width="4%" class="tabelBorderLeftNull"><b>TS</b></td>
                <td width="4%" class="tabelBorderLeftNull"><b>N</b></td>
                <td width="4%" class="tabelBorderLeftNull"><b>S</b></td>
                <td width="4%" class="tabelBorderLeftRightNull"><b>SS</b></td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">1. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Saya bangga menyampaikan kepada orang-orang bahwa saya bekerja untuk Perusahaan ini</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdA1">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="A1" id="A1" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="A1" id="A1" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="A1" id="A1" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="A1" id="A1" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="A1" id="A1" value="5">5</b>
              	</td>
                
            </tr>
            <tr>
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">2. </td>
                <td width="72%" class="tabelBorderBottomJust">&nbsp;Perusahaan tempat saya bekerja merupakan salah satu tempat bekerja terbaik</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdA2">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="A2" id="A2" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="A2" id="A2" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="A2" id="A2" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="A2" id="A2" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="A2" id="A2" value="5">5</b>
              	</td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">3. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Perusahaan memperlakukan saya dengan baik</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdA3">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="A3" id="A3" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="A3" id="A3" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="A3" id="A3" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="A3" id="A3" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="A3" id="A3" value="5">5</b>
              	</td>
            </tr>
<!-- END OF - Organisasi/ Perusahaan Group (A) ============================================================================== -->
<!-- START - Pekerjaan Group (B) ============================================================================================ -->
            <tr align="center" bgcolor="#F3F3F3" height="20" style="font-size:18px;">
                <td colspan="3" width="80%" height="30" class="tabelBorderBottomJust" ><b>Pekerjaan</b></td>
                <td width="4%" class="tabelBorderTopNull"><b>STS</b></td>
                <td width="4%" class="tabelBorderTopLeftNull"><b>TS</b></td>
                <td width="4%" class="tabelBorderTopLeftNull"><b>N</b></td>
                <td width="4%" class="tabelBorderTopLeftNull"><b>S</b></td>
                <td width="4%" class="tabelBorderBottomJust"><b>SS</b></td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">1. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Saya memahami kemampuan kerja yang saya miliki untuk berkontribusi kepada Perusahaan</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdB1">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="B1" id="B1" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="B1" id="B1" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="B1" id="B1" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="B1" id="B1" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="B1" id="B1" value="5">5</b>
              	</td>
            </tr>
            <tr>
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">2. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Saya mendapat peralatan dan perlengkapan kerja dari Perusahaan untuk mendukung pekerjaan yang saya lakukan</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdB2">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="B2" id="B2" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="B2" id="B2" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="B2" id="B2" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="B2" id="B2" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="B2" id="B2" value="5">5</b>
              	</td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">3. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Saya puas dengan kondisi kerja di Perusahaan</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdB3">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="B3" id="B3" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="B3" id="B3" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="B3" id="B3" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="B3" id="B3" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="B3" id="B3" value="5">5</b>
              	</td>
            </tr>
            <tr>
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">4. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Saya puas dengan pekerjaan dan tugas dari Perusahaan yang saya lakukan selama ini</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdB4">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="B4" id="B4" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="B4" id="B4" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="B4" id="B4" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="B4" id="B4" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="B4" id="B4" value="5">5</b>
              	</td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">5. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Pekerjaan saya saat ini menarik dan menantang</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdB5">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="B5" id="B5" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="B5" id="B5" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="B5" id="B5" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="B5" id="B5" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="B5" id="B5" value="5">5</b>
              	</td>
            </tr>
            <tr>
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">6. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Saya mendapatkan pelatihan yang cukup terkait dengan lingkup pekerjaan saat ini</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdB6">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="B6" id="B6" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="B6" id="B6" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="B6" id="B6" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="B6" id="B6" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="B6" id="B6" value="5">5</b>
              	</td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">7. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Kinerja yang telah saya capai dengan baik diperhatikan dan dihargai oleh Atasan Langsung</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdB7">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="B7" id="B7" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="B7" id="B7" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="B7" id="B7" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="B7" id="B7" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="B7" id="B7" value="5">5</b>
              	</td>
            </tr>
            <tr>
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">8. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Secara keseluruhan, saya puas dengan pekerjaan saat ini</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdB8">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="B8" id="B8" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="B8" id="B8" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="B8" id="B8" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="B8" id="B8" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="B8" id="B8" value="5">5</b>
              	</td>
            </tr>
<!-- END OF - Pekerjaan Group (B) =========================================================================================== -->
<!-- START - Program Pelatihan Pengembangan Karir Group (C) ================================================================= -->
            <tr align="center" bgcolor="#F3F3F3" height="20" style="font-size:18px;">
                <td colspan="3" width="80%" height="30" class="tabelBorderBottomJust" ><b>Program Pelatihan Pengembangan Karir</b></td>
                <td width="4%" class="tabelBorderTopNull"><b>STS</b></td>
                <td width="4%" class="tabelBorderTopLeftNull"><b>TS</b></td>
                <td width="4%" class="tabelBorderTopLeftNull"><b>N</b></td>
                <td width="4%" class="tabelBorderTopLeftNull"><b>S</b></td>
                <td width="4%" class="tabelBorderBottomJust"><b>SS</b></td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">1. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Saya mendapatkan evaluasi atas kinerja secara berkala</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdC1">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="C1" id="C1" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="C1" id="C1" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="C1" id="C1" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="C1" id="C1" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="C1" id="C1" value="5">5</b>
              	</td>
            </tr>
            <tr>
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">2. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Saya puas dengan kesempatan untuk mendapatkan pelatihan yang relevan dengan pekerjaan dan tuntutan bisnis</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdC2">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="C2" id="C2" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="C2" id="C2" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="C2" id="C2" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="C2" id="C2" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="C2" id="C2" value="5">5</b>
              	</td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">3. </td>
                <td width="72%" class="tabelBorderBottomJust" style="font-size:15px;">&nbsp;Perusahaan mengutamakan pemenuhan posisi lowong melalui suksesi internal dibandingkan dengan merekrut karyawan baru</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdC3">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="C3" id="C3" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="C3" id="C3" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="C3" id="C3" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="C3" id="C3" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="C3" id="C3" value="5">5</b>
              	</td>
            </tr>
            <tr>
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">4. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Saya puas dengan kesempatan berkarir yang diberikan oleh Perusahaan</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdC4">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="C4" id="C4" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="C4" id="C4" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="C4" id="C4" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="C4" id="C4" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="C4" id="C4" value="5">5</b>
              	</td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">5. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Promosi diberikan kepada karyawan yang berkinerja baik/ berprestasi</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdC5">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="C5" id="C5" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="C5" id="C5" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="C5" id="C5" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="C5" id="C5" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="C5" id="C5" value="5">5</b>
              	</td>
            </tr>
<!-- END OF - Program Pelatihan Pengembangan Karir Group (C) ================================================================ -->
<!-- START - Hubungan Kerja dengan Atasan Langsung Group (D) ================================================================ -->
			<tr align="center" bgcolor="#F3F3F3" height="20" style="font-size:18px;">
                <td colspan="3" width="80%" height="30" class="tabelBorderBottomJust" ><b>Hubungan Kerja dengan Atasan Langsung</b></td>
                <td width="4%" class="tabelBorderTopNull"><b>STS</b></td>
                <td width="4%" class="tabelBorderTopLeftNull"><b>TS</b></td>
                <td width="4%" class="tabelBorderTopLeftNull"><b>N</b></td>
                <td width="4%" class="tabelBorderTopLeftNull"><b>S</b></td>
                <td width="4%" class="tabelBorderBottomJust"><b>SS</b></td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">1. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Atasan Langsung mengkomunikasikan perencanaan dan sasaran kerja bersama saya</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdD1">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="D1" id="D1" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D1" id="D1" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D1" id="D1" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D1" id="D1" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="D1" id="D1" value="5">5</b>
              	</td>
            </tr>
            <tr>
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">2. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Atasan Langsung memberikan instruksi kerja yang jelas</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdD2">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="D2" id="D2" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D2" id="D2" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D2" id="D2" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D2" id="D2" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="D2" id="D2" value="5">5</b>
              	</td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">3. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Atasan Langsung memberikan saran jika saya menghadapi masalah terkait pekerjaan</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdD3">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="D3" id="D3" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D3" id="D3" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D3" id="D3" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D3" id="D3" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="D3" id="D3" value="5">5</b>
              	</td>
            </tr>
            <tr>
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">4. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Atasan Langsung percaya akan kemampuan kerja yang saya miliki</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdD4">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="D4" id="D4" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D4" id="D4" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D4" id="D4" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D4" id="D4" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="D4" id="D4" value="5">5</b>
              	</td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">5. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Atasan Langsung membantu saya dalam hal pengembangan diri dan kemampuan kerja</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdD5">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="D5" id="D5" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D5" id="D5" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D5" id="D5" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D5" id="D5" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="D5" id="D5" value="5">5</b>
              	</td>
            </tr>
            <tr>
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">6. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Atasan Langsung memberikan tindakan perbaikan jika saya tidak berhasil mencapai target kinerja</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdD6">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="D6" id="D6" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D6" id="D6" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D6" id="D6" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D6" id="D6" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="D6" id="D6" value="5">5</b>
              	</td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">7. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Saya merasa nyaman untuk menyampaikan segala hal secara jujur dan terbuka kepada Atasan Langsung</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdD7">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="D7" id="7" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D7" id="D7" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D7" id="D7" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D7" id="D7" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="D7" id="D7" value="5">5</b>
              	</td>
            </tr>
            <tr>
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">8. </td>
                <td width="72%" class="tabelBorderBottomJust" style="font-size:15px;">&nbsp;Atasan Langsung secara berkala melakukan koordinasi internal untuk memastikan pekerjaan tim dilakukan dengan baik</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdD8">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="D8" id="D8" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D8" id="D8" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D8" id="D8" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D8" id="D8" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="D8" id="D8" value="5">5</b>
              	</td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">9. </td>
                <td width="72%" class="tabelBorderBottomJust" style="font-size:15px;">&nbsp;Melalui koordinasi internal, saya dapat mengetahui perkembangan informasi pekerjaan dan meningkatkan efektivitas kerja</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdD9">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="D9" id="D9" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D9" id="D9" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D9" id="D9" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D9" id="D9" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="D9" id="D9" value="5">5</b>
              	</td>
            </tr>
            <tr>
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">10. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Keputusan yang diambil oleh Atasan Langsung saya sudah efektif</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdD10">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="D10" id="D10" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D10" id="D10" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D10" id="D10" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D10" id="D10" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="D10" id="D10" value="5">5</b>
              	</td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">11. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Atasan Langsung saya mengetahui kondisi kerja di dalam tim kerja</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdD11">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="D11" id="D11" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D11" id="D11" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D11" id="D11" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D11" id="D11" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="D11" id="D11" value="5">5</b>
              	</td>
            </tr>
            <tr>
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">12. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Atasan Langsung saya melakukan pekerjaan dengan baik</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdD12">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="D12" id="D12" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D12" id="D12" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D12" id="D12" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="D12" id="D12" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="D12" id="D12" value="5">5</b>
              	</td>
            </tr>
<!-- END OF - Hubungan Kerja dengan Atasan Langsung Group (D) =============================================================== -->
<!-- START - Hubungan Kerja dengan Rekan Kerja Group (E) ==================================================================== -->
			<tr align="center" bgcolor="#F3F3F3" height="20" style="font-size:18px;">
                <td colspan="3" width="80%" height="30" class="tabelBorderBottomJust" ><b>Hubungan Kerja dengan Rekan Kerja</b></td>
                <td width="4%" class="tabelBorderTopNull"><b>STS</b></td>
                <td width="4%" class="tabelBorderTopLeftNull"><b>TS</b></td>
                <td width="4%" class="tabelBorderTopLeftNull"><b>N</b></td>
                <td width="4%" class="tabelBorderTopLeftNull"><b>S</b></td>
                <td width="4%" class="tabelBorderBottomJust"><b>SS</b></td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">1. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Saya puas dengan cara tim kerja untuk menyelesaikan masalah terkait dengan pekerjaan</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdE1">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="E1" id="E1" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="E1" id="E1" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="E1" id="E1" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="E1" id="E1" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="E1" id="E1" value="5">5</b>
              	</td>
            </tr>
            <tr>
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">2. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Kerja sama dalam tim saya berjalan dengan baik</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdE2">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="E2" id="E2" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="E2" id="E2" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="E2" id="E2" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="E2" id="E2" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="E2" id="E2" value="5">5</b>
              	</td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">3. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Beban pekerjaan didistribusikan dengan baik dalam tim kerja saya</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdE3">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="E3" id="E3" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="E3" id="E3" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="E3" id="E3" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="E3" id="E3" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="E3" id="E3" value="5">5</b>
              	</td>
            </tr>
            <tr>
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">4. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Saya  merasa nyaman untuk menyampaikan segala hal secara jujur dan terbuka kepada Rekan Kerja</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdE4">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="E4" id="E4" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="E4" id="E4" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="E4" id="E4" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="E4" id="E4" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="E4" id="E4" value="5">5</b>
              	</td>
            </tr>
<!-- END OF - Hubungan Kerja dengan Rekan Kerja Group (E) =================================================================== -->
<!-- START - Kondisi Kerja dan Lingkungan Kerja Group (E) =================================================================== -->
			<tr align="center" bgcolor="#F3F3F3" height="20" style="font-size:18px;">
                <td colspan="3" width="80%" height="30" class="tabelBorderBottomJust" ><b>Kondisi Kerja dan Lingkungan Kerja</b></td>
                <td width="4%" class="tabelBorderTopNull"><b>STS</b></td>
                <td width="4%" class="tabelBorderTopLeftNull"><b>TS</b></td>
                <td width="4%" class="tabelBorderTopLeftNull"><b>N</b></td>
                <td width="4%" class="tabelBorderTopLeftNull"><b>S</b></td>
                <td width="4%" class="tabelBorderBottomJust"><b>SS</b></td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">1. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Saya yakin lingkungan kerja saya saat ini aman</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdF1">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="F1" id="F1" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="F1" id="F1" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="F1" id="F1" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="F1" id="F1" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="F1" id="F1" value="5">5</b>
              	</td>
            </tr>
            <tr>
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">2. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Kondisi Perusahaan saat ini baik secara fisik dan infrastruktur</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdF2">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="F2" id="F2" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="F2" id="F2" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="F2" id="F2" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="F2" id="F2" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="F2" id="F2" value="5">5</b>
              	</td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">3. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Target kerja yang ditetapkan saat ini wajar dan realistis</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdF3">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="F3" id="F3" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="F3" id="F3" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="F3" id="F3" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="F3" id="F3" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="F3" id="F3" value="5">5</b>
              	</td>
            </tr>
            <tr>
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">4. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Beban pekerjaan saya saat ini sesuai kemampuan dan masuk akal</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdF4">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="F4" id="F4" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="F4" id="F4" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="F4" id="F4" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="F4" id="F4" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="F4" id="F4" value="5">5</b>
              	</td>
            </tr>
<!-- END OF - Kondisi Kerja dan Lingkungan Kerja Group (F) ================================================================== -->
<!-- START - Kompensasi dan Benefit Group (G) =============================================================================== -->
			<tr align="center" bgcolor="#F3F3F3" height="20" style="font-size:18px;">
                <td colspan="3" width="80%" height="30" class="tabelBorderBottomJust" ><b>Kompensasi dan Benefit</b></td>
                <td width="4%" class="tabelBorderTopNull"><b>STS</b></td>
                <td width="4%" class="tabelBorderTopLeftNull"><b>TS</b></td>
                <td width="4%" class="tabelBorderTopLeftNull"><b>N</b></td>
                <td width="4%" class="tabelBorderTopLeftNull"><b>S</b></td>
                <td width="4%" class="tabelBorderBottomJust"><b>SS</b></td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">1. </td>
                <td width="72%" class="tabelBorderBottomJust" style="font-size:14px;">&nbsp;Saya puas dengan tunjangan kesejahteraan dari Perusahaan seperti tunjangan kesehatan, tunjangan komunikasi, bonus dan lainnya</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdG1">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="G1" id="G1" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="G1" id="G1" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="G1" id="G1" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="G1" id="G1" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="G1" id="G1" value="5">5</b>
              	</td>
            </tr>
            <tr>
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">2. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Saya puas dengan program rekreasi karyawan berupa kegiatan wisata, team building, outing, atau kegiatan lainnya</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdG2">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="G2" id="G2" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="G2" id="G2" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="G2" id="G2" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="G2" id="G2" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="G2" id="G2" value="5">5</b>
              	</td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">3. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Saya puas dengan ketentuan hari istirahat / libur yang diberikan atas ketetapan Perusahaan</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdG3">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="G3" id="G3" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="G3" id="G3" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="G3" id="G3" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="G3" id="G3" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="G3" id="G3" value="5">5</b>
              	</td>
            </tr>
            <tr>
            	<td width="4%" height="25" class="tabelBorderTopLeftNull" align="center">4. </td>
                <td width="72%" class="tabelBorderBottomJust" >&nbsp;Secara umum Perusahaan peduli terhadap kesejahteraan karyawan</td>
                <td width="4%" class="tabelBorderBottomJust" align="right" id="tdIdG4">&nbsp;</td>
                <td width="4%" class="tabelBorderTopNull" align="center">
                	<b><input type="radio" name="G4" id="G4" value="1">1</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="G4" id="G4" value="2">2</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="G4" id="G4" value="3">3</b>
                </td>
                <td width="4%" class="tabelBorderTopLeftNull" align="center">
                	<b><input type="radio" name="G4" id="G4" value="4">4</b>
                </td>
              	<td width="4%" class="tabelBorderBottomJust" align="center">
              		<b><input type="radio" name="G4" id="G4" value="5">5</b>
              	</td>
            </tr>
<!-- END OF - Kompensasi dan Benefit Group (G) ============================================================================== -->
			<tr><td colspan="8" height="5" align="center"><span id="errorMsg" class="errorMsg">&nbsp;</span></td></tr>
            <tr>
            	<td colspan="8" align="center">
                	<button class="btnStandar" onClick="btnSubmit();return false;" onMouseOver="this.className='btnStandarHover'" onMouseOut="this.className='btnStandar'" style="width:80px;height:29px;" title="Submit Survey">
                        <table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0" class="fontBtnStandar" onMouseOver="this.className='fontBtnStandarHover'" onMouseOut="this.className='fontBtnStandar'">
                          <tr>
                            <td align="right" width="25"><img src="../../picture/Floppy-Disk-blue-32.png" height="20"/> </td>
                            <td align="center">Submit</td>
                          </tr>
                        </table>
                    </button>
                </td>
            </tr>
		</table>
        </form>
	</td>
</tr>
</table>
<script language="javascript">
<?php
if($aksiGet == "submitSurvey")
{
	echo "parent.exit();";
}
?>
</script>