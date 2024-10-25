<?php require_once("../configVslRep.php"); ?>
<!DOCTYPE HTML>
<script type="text/javascript" src="../../js/jquery-1.11.2.js"></script>
<script type="text/javascript" src="../js/vslRep.js"></script>
<script language="javascript">
function onClickTr(idPort, rowGuid, bgColor, detailInfo)
{	
	var idTrSeb = document.getElementById('idTrSeb').value;
	var bgColorSeb = document.getElementById('bgColorSeb').value;
	
	if(idTrSeb != "")
	{
		document.getElementById(idTrSeb).onmouseover = function onmouseover(){	this.style.backgroundColor='#D9EDFF';	}
		document.getElementById(idTrSeb).onmouseout = function onmouseout(){	this.style.backgroundColor=bgColorSeb;	}
		document.getElementById(idTrSeb).style.fontWeight='';
		document.getElementById(idTrSeb).style.backgroundColor=bgColorSeb;
		document.getElementById(idTrSeb).style.cursor = 'pointer';	
		//document.getElementById(idTrSeb).style.height = "22";
		//document.getElementById(idTdNameDivSeb).style.fontWeight=''; // FONT TIDAK BOLD UNTUK TD YANG DIPILIH
	}
	
	document.getElementById('tr'+rowGuid).onmouseout = '';
	document.getElementById('tr'+rowGuid).onmouseover ='';
	//document.getElementById('tr'+trId).style.fontWeight='bold';
	document.getElementById('tr'+rowGuid).style.backgroundColor='#B0DAFF';
	document.getElementById('tr'+rowGuid).style.cursor = 'default';
	document.getElementById('tr'+rowGuid).style.fontSize='11px';
	document.getElementById('idTrSeb').value = 'tr'+rowGuid;
	//document.getElementById('tr'+trId).style.height = "";
	
	document.getElementById('bgColorSeb').value = bgColor;
	parent.document.getElementById('idPort').value = idPort;

	parent.enabledBtn('btnPrint');
	
	$('#divDetailInfo', parent.document).html("&nbsp;");
	$('#divDetailInfo', parent.document).html(detailInfo);
}

window.onload = 
function() 
{
	var userJenis = "<?php echo $userJenis; ?>";
	if(userJenis != "admin")
	{
		document.oncontextmenu = function(){	return false;	}; 
	}
	loadScroll('halPortList');
	parent.doneWait();
	parent.panggilEnableLeftClick();
}
$(window).scroll(function(){
$('#judul').css('left','-'+$(window).scrollLeft()+'px');
});
</script>

<link href="../css/vslRep.css" rel="stylesheet" type="text/css" />
<link href="../css/table.css" rel="stylesheet" type="text/css" />

<body onUnload="saveScroll('halNoonList')">

<div class="loader" id="loaderImg" style="visibility:hidden;"></div>
<input type="hidden" id="idTrSeb" name="idTrSeb">
<input type="hidden" id="bgColorSeb" name="bgColorSeb">

<?php
$aksiGet = $_GET['aksi'];
$idPortGet = $_GET['idPort'];
//$nmVslGet = $_GET['nmVsl'];
$initVslGet = $_GET['initVsl'];

//echo "<br><br>".$nmVslGet;

if($aksiGet == "delete")
{
	//echo "<br><br><br>".$idNoonGet;
	$CKoneksiVslRep->mysqlQuery("UPDATE tblport SET deletests=1, delusrdt='".$userWhoActNew."' WHERE idport=".$idPortGet." AND deletests=0 LIMIT 1;", $CKoneksiVslRep->bukaKoneksi());
	$CHistory->updateLogVslRep($userIdLogin, "Simpan DELETE PORT (idport=<b>".$idPortGet."</b>, deletests=<b>0</b>, delusrdt=<b>".$userWhoAct."</b>)");	
}
?>

<!-- width="425"-->
<table id="judul" width="100%" cellpadding="0" cellspacing="0" style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:11px;position:fixed;left:0px;top:0px;">
<tr align="center">
    <td width="30" height="40" class="">NO</td>
    <td width="110" class="">REF NO</td>
    <td width="95" class="">DATE OF PORT</td> 
    <td width="135" class="">PORT</td>
</tr>
</table>

<table width="100%" cellpadding="0" cellspacing="0" style="font-size:11px;font-family:Arial;color:#333;margin-top:40px;">
<?php

if($aksiGet == "display" || $aksiGet == "delete" || $aksiGet == "afterGen" || $aksiGet == "afterSendHo")
{
	$tabel = "";
    $i=0;
	
	$sqlTambahan="";
	if($initVslGet != "all")
	{
		$sqlTambahan = " initvsl='".$initVslGet."' AND";
	}
	
	$query = $CKoneksiVslRep->mysqlQuery("SELECT * FROM tblport WHERE ".$sqlTambahan." deletests=0 ORDER BY lastreceive DESC;", $CKoneksiVslRep->bukaKoneksi());
    while($row = $CKoneksiVslRep->mysqlFetch($query))
    {
        $i++;
        $rowColor = $CPublic->rowColorCustom($i, "#FFFFFF", "#F0F1FF");
        
		$datePort = ($row['dateport'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['dateport'])."&nbsp;&nbsp;".$row['hourport'];
		$port = $row['port'];
		
		$detailInfo = detailInfo($CKoneksiVslRep, $CPublic, $CVslRep, $row['idport'], $row['guid']);
		$onClick = "onClickTr('".$row['idport']."', '".$row['guid']."', '".$rowColor."', '".$detailInfo."');";
		$clikTR = $onClick;
        
        $tabel.=""?>

            <tr valign="bottom" align="left" bgcolor="<?php echo $rowColor; ?>"  onMouseOver="this.style.backgroundColor='#D9EDFF';" onMouseOut="this.style.backgroundColor='<?php echo $rowColor; ?>';" id="tr<?php echo $row['guid']; ?>" onClick="<?php echo $clikTR; ?>" 
            style="cursor:pointer;padding-bottom:1px;">
                <td width="30" height="22" class="tabelBorderTopLeftNull" align="center"><?php echo $i; ?></td>
                <td width="110" class="tabelBorderTopLeftNull">&nbsp;<?php echo $row['refno']; ?></td>
                <td width="95" class="tabelBorderTopLeftNull">&nbsp;<?php echo $datePort; ?></td>   
                <td width="135" class="tabelBorderTopLeftNull">&nbsp;<?php echo $port; ?></td>      
            </tr>
        <?php echo "";
    }
    echo $tabel;
}
if($aksiGet == "" || $nmVslGet == "000") // HALAMAN AWAL ADALAH KOSONG
{ 
?>
    <tr>
    	<td align="center">
            
                <table cellpadding="0" cellspacing="0" width="100%">
                <tr align="center">
                    <td height="423" style="font-family:sans-serif;font-weight:bold;font-size:30px;color:#CCC;">PLEASE SELECT VESSEL</td>
                </tr>
                </table>
            </td>
    </tr>
<?php
}
?>
</table>
</body>

<script type="text/javascript">
<?php
if($aksiGet == "afterGen" || $aksiGet == "afterSendHo")
{ ?>
	document.getElementById('tr<?php echo $idPortGet; ?>').click();
	//echo "parent.klikBtnDisplay();";
<?php
}
?>
</script>
</HTML>

<?php
function jikaParamSamaDenganNilai1($param, $nilai1, $nilai2)
{
	//ex: param = "02/03/2012", nilai1 = "00/00/0000", nilai2 = "";
	//jika nilai $param sama dengan $nilai 1 maka berikan $nilai = $nilai2, namun jika $param tidak sama dengan 
	$nilai = "";
	if($param == $nilai1)
	{
		$nilai = $nilai2; 
	}
	elseif($param != $nilai1)
	{
		$nilai = $param;	
	}
	return $nilai;	
}

function detailInfo($CKoneksiVslRep, $CPublic, $CVslRep, $idPort, $rowGuid)
{
	$html = "";
	
	$query = $CKoneksiVslRep->mysqlQuery("SELECT *,(robmfo + memfoconsump + aemfoconsump + boilermfoconsump) as totalMfo,(robmdo+memdoconsump+aemdoconsump+boilermdoconsump) as totalMdo FROM tblport WHERE idport = '".$idPort."' AND guid='".$rowGuid."' AND deletests=0;", $CKoneksiVslRep->bukaKoneksi());
    $row = $CKoneksiVslRep->mysqlFetch($query);
	
	$datePort = ($row['dateport'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['dateport'])."&nbsp;&nbsp;".$row['hourport'];
	
	$lastReceive = $CVslRep->convLastReceive( $row['lastreceive'] );
	$lastEdit = $CVslRep->convTglAddUsrDt($row['updusrdt']);
	$lastGen = ($row['lastgen'] == "")?"":$CVslRep->convLastReceive($row['lastgen']);
	
	$arrayTd = array( 	array("LAST SAVED",$lastEdit,""), 
						array("LAST RECEIVED",$lastReceive,""), 
						array("&nbsp;","",""), 
						array("REFNO",$row['refno'],""), 
						array("VESSEL NAME",$CPublic->konversiQuotes( $row['namakapal'] ),""), 
						array("DATE OF PORT",$datePort,""),
						array("POSITION",$CPublic->konversiQuotes( $row['position'] ),""), 
						array("WEATHER",$CPublic->konversiQuotes($row['weather']),""),
						array("SHIP ACTIVITY",$CPublic->konversiQuotes($row['shipactivity']),""),
						array("DRAFT",$CPublic->konversiQuotes($row['draft']),""),
						array("ROB MFO",$CPublic->konversiQuotes($row['robmfo'])." MT",""),
						array("ME MFO CONSUMP",$CPublic->konversiQuotes($row['memfoconsump'])." MT",""),
						array("AE MFO CONSUMP",$CPublic->konversiQuotes($row['aemfoconsump'])." MT",""),
						array("BOILER MFO CONSUMP",$CPublic->konversiQuotes($row['boilermfoconsump'])." MT",""),
						array("TOTAL MFO CONSUMP",$CPublic->konversiQuotes($row['totalMfo'])." MT",""),
						array("ROB MDO",$CPublic->konversiQuotes($row['robmdo'])." MT",""),
						array("ME MDO CONSUMP",$CPublic->konversiQuotes($row['memdoconsump'])." MT",""),
						array("AE MDO CONSUMP",$CPublic->konversiQuotes($row['aemdoconsump'])." MT",""),
						array("BOILER MDO CONSUMP",$CPublic->konversiQuotes($row['boilermdoconsump'])." MT",""),
						array("TOTAL MDO CONSUMP",$CPublic->konversiQuotes($row['totalMdo'])." MT",""),
						array("ROB ME CYL LO",$CPublic->konversiQuotes($row['robmecyllo'])." MT",""),
						array("ME CYL LO DAILY CONSUMP",$CPublic->konversiQuotes($row['mecyllodailyconsump'])." MT",""),
						array("ROB ME SYS LO",$CPublic->konversiQuotes($row['robmesyslo'])." MT",""),
						array("ME SYS LO DAILY CONSUMP",$CPublic->konversiQuotes($row['mesyslodailyconsump'])." MT",""),
						array("ROB AE LO",$CPublic->konversiQuotes($row['robaelo'])." LTRS",""),
						array("AE LO DAILY CONSUMP",$CPublic->konversiQuotes($row['aelodailyconsump'])." LTRS",""),
						array("ROB SUMP TK",$CPublic->konversiQuotes($row['robsumptk'])." LTRS",""),
						array("ROB FW",$CPublic->konversiQuotes($row['robfw'])." MT",""),
						array("FW CONSUMP",$CPublic->konversiQuotes($row['fwconsump'])." MT",""),
						array("REMARKS ",nl2br2($CPublic->konversiQuotes($row['remark'])),"")
					);
						
	$html.= "<table style=\'width:400px;\' class=\'tabelDetailInfo\'>";
	for($i = 0; $i < count($arrayTd); $i++)
	{
		$judul = $arrayTd[$i][0];
		$isi = $arrayTd[$i][1];
		$isiSpan = $arrayTd[$i][2];
		
		if($i == 2)
		{
			$html.= "<tr><td>".$arrayTd[$i][0]."</td></tr>";
		}
		else
		{
			$html.= "<tr><td>".$judul." : <a>".$isi."</a> <span>".$isiSpan."</span></td></td></tr>";
		}
	}
	$html.= "</table>";
			
	return $html;
}

function nl2br2($string) 
{
	$string = str_replace(array("\r\n", "\r", "\n"), "<br />", $string);
	return $string;
} 
?>
