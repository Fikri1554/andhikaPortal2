<?php require_once("../configVslRep.php"); ?>
<!DOCTYPE HTML>
<script type="text/javascript" src="../../js/jquery-1.11.2.js"></script>
<script type="text/javascript" src="../js/vslRep.js"></script>
<script language="javascript">
function onClickTr(idNoon, rowGuid, bgColor, detailInfo)
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
	parent.document.getElementById('idNoon').value = idNoon;

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
	loadScroll('halNoonList');
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
$idNoonGet = $_GET['idNoon'];
//$nmVslGet = $_GET['nmVsl'];
$initVslGet = $_GET['initVsl'];

//echo "<br><br>".$nmVslGet;

if($aksiGet == "delete")
{
	//echo "<br><br><br>".$idNoonGet;
	$CKoneksiVslRep->mysqlQuery("UPDATE tblnoon SET deletests=1, delusrdt='".$userWhoActNew."' WHERE idnoon=".$idNoonGet." AND deletests=0 LIMIT 1;", $CKoneksiVslRep->bukaKoneksi());
	$CHistory->updateLogVslRep($userIdLogin, "Simpan DELETE EOP (idnoon=<b>".$idNoonGet."</b>, deletests=<b>0</b>, delusrdt=<b>".$userWhoAct."</b>)");	
}
?>

<!-- width="425"-->
<table id="judul" width="100%" cellpadding="0" cellspacing="0" style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:11px;position:fixed;left:0px;top:0px;">
<tr align="center">
    <td width="30" height="40" class="">NO</td>
    <td width="110" class="">REF NO</td>
    <td width="95" class="">DATE OF NOON</td> 
    <td width="135" class="">LAST PORT</td>
    <td width="135" class="">NEXT PORT</td>
    <td width="" class="">&nbsp;</td>
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
	
	$query = $CKoneksiVslRep->mysqlQuery("SELECT * FROM tblnoon WHERE ".$sqlTambahan." deletests=0 ORDER BY lastreceive DESC;", $CKoneksiVslRep->bukaKoneksi());
    while($row = $CKoneksiVslRep->mysqlFetch($query))
    {
        $i++;
        $rowColor = $CPublic->rowColorCustom($i, "#FFFFFF", "#F0F1FF");
        
		$dateNoon = ($row['datenoon'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['datenoon'])."&nbsp;&nbsp;".$row['hournoon'];
		$lastPort = $row['lastport'];
		$nextPort = $row['nextport'];
		
		$detailInfo = detailInfo($CKoneksiVslRep, $CPublic, $CVslRep, $row['idnoon'], $row['guid']);
		$onClick = "onClickTr('".$row['idnoon']."', '".$row['guid']."', '".$rowColor."', '".$detailInfo."');";
		$clikTR = $onClick;
        
        $tabel.=""?>

            <tr valign="bottom" align="left" bgcolor="<?php echo $rowColor; ?>"  onMouseOver="this.style.backgroundColor='#D9EDFF';" onMouseOut="this.style.backgroundColor='<?php echo $rowColor; ?>';" id="tr<?php echo $row['guid']; ?>" onClick="<?php echo $clikTR; ?>" 
            style="cursor:pointer;padding-bottom:1px;">
                <td width="30" height="22" class="tabelBorderTopLeftNull" align="center"><?php echo $i; ?></td>
                <td width="110" class="tabelBorderTopLeftNull">&nbsp;<?php echo $row['refno']; ?></td>
                <td width="95" class="tabelBorderTopLeftNull">&nbsp;<?php echo $dateNoon; ?></td>   
                <td width="135" class="tabelBorderTopLeftNull">&nbsp;<?php echo $lastPort; ?></td>
                <td width="" class="tabelBorderBottomJust">&nbsp;<?php echo $nextPort; ?></td>                  
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
	document.getElementById('tr<?php echo $idNoonGet; ?>').click();
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

function detailInfo($CKoneksiVslRep, $CPublic, $CVslRep, $idNoon, $rowGuid)
{
	$html = "";
	
	$query = $CKoneksiVslRep->mysqlQuery("SELECT * FROM tblnoon WHERE idnoon = '".$idNoon."' AND guid='".$rowGuid."' AND deletests=0;", $CKoneksiVslRep->bukaKoneksi());
    $row = $CKoneksiVslRep->mysqlFetch($query);
	
	$dateNoon = ($row['datenoon'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['datenoon'])."&nbsp;&nbsp;".$row['hournoon'];
	$pilotOnBoard = ($row['pilotonboard'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['pilotonboard'])."&nbsp;&nbsp;".$row['hourpiloton'];
	$standByEngine = ($row['standbyengine'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['standbyengine'])."&nbsp;&nbsp;".$row['hourstandby'];
	$anchorUp = ($row['anchorup'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['anchorup'])."&nbsp;&nbsp;".$row['houranchorup'];
	$pilotOff = ($row['pilotoff'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['pilotoff'])."&nbsp;&nbsp;".$row['hourpilotoff'];
	$fullAway = ($row['fullaway'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['fullaway'])."&nbsp;&nbsp;".$row['hourfullaway'];
	$eta = ($row['eta'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['eta'])."&nbsp;&nbsp;".$row['houreta'];
	
	$lastEdit = $CVslRep->convTglAddUsrDt($row['updusrdt']);
	$lastReceive = ($row['lastreceive'] == "")?"&nbsp;":$CVslRep->convLastReceive($row['lastreceive']);
	
	$arrayTd = array( array("LAST SAVED",$lastEdit,""), 
							array("LAST RECEIVE IN HO",$lastReceive,""), 
							array("&nbsp;","",""), 
							array("REFNO",$row['refno'],""), 
							array("VESSEL NAME",$CPublic->konversiQuotes( $row['namakapal'] ),""), 
							array("DATE OF NOON",$dateNoon,""), 
							array("LAST PORT",$CPublic->konversiQuotes( $row['lastport'] ),""), 
							array("NEXT PORT",$CPublic->konversiQuotes( $row['nextport'] ),""), 
							array("POSITION",$CPublic->konversiQuotes( $row['position'] ),""), 
							array("COURSE",$CPublic->konversiQuotes( $row['course'] ),"DEG"), 
							array("DISTANCE RUN",$CPublic->konversiQuotes( $row['distancerun'] ),"NM"), 
							array("STEAMING TIME",$CPublic->konversiQuotes( $row['steamingtime'] ),"HRS "), 
							array("SPEED",$CPublic->konversiQuotes( $row['speed'] ),"KTS"), 
							array("DIST TO GO",$CPublic->konversiQuotes( $row['disttogo'] ),"NM"), 
							array("ME RPM/DAY / FULL AWAY",$CPublic->konversiQuotes( $row['merpmday']),""), 
							
							array("ENG SLIP",$CPublic->konversiQuotes( $row['engslip'] ),"NM"), 
							array("WIND DIR/FORCE",$CPublic->konversiQuotes( $ro['winddir'] ),"HRS"), 
							array("SEA SCALE",$CPublic->konversiQuotes( $row['seascale'] ),"MTR"),
							array("WEATHER COND",$CPublic->konversiQuotes( $row['weathercond'] ),"MTR"),
							array("ETA",$eta,"HRS"),
							array("ROB MFO",$CPublic->konversiQuotes( $row['robmfo'] ),"MT"),
							array("ROB MDO",$CPublic->konversiQuotes( $row['robmdo'] ),"MT"), 
							array("ROB ME CYL OIL",$CPublic->konversiQuotes( $row['robmecyloil'] ),"LTRS"), 
							array("ROB ME SYS OIL",$CPublic->konversiQuotes( $row['robmesysoil'] ),"LTRS"), 
							array("ROB SUMP TANK",$CPublic->konversiQuotes( $row['robsumptank'] ),"LTRS"), 
							
							array("ROB AE / LO",$CPublic->konversiQuotes( $row['robae'] ),"LTRS"), 
							array("ROB FW",$CPublic->konversiQuotes( $row['robfw'] ),"MT"), 
							array("MFO CONSUMP ME",$CPublic->konversiQuotes( $row['mfoconsumpme'] ),"MT"),
							array("MDO CONSUMP",$CPublic->konversiQuotes( $row['mdoconsump'] ),"MT"),
							array("ME CYL LO CONSUMP",$CPublic->konversiQuotes( $row['mecylloconsump'] ),"LTRS"),
							array("ME SYS LO CONSUMP",$CPublic->konversiQuotes( $row['mesysloconsump'] ),"LTRS"),
							array("A/E LO CONSUMP",$CPublic->konversiQuotes( $row['aeloconsump'] ),"LTRS"),
							array("SUMP TANK CONSUMP",$CPublic->konversiQuotes( $row['sumptankconsump'] ),"MT"),
							array("FW CONSUMP",$CPublic->konversiQuotes( $row['fwconsump'] ),"MT"),
							array("FW PRODUCT",$CPublic->konversiQuotes( $row['fwproduct'] ),"MT"),
							array("REMARKS",$CPublic->konversiQuotes( $row['remarks'] ),"")
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
			
	/*$html.= "<table style=\'width:400px;\' class=\'tabelDetailInfo\'>";
	$html.= "<tr><td>LAST SAVED : <a>".$lastEdit."</a></td></tr>";
	$html.= "<tr><td>LAST RECEIVE IN HO : <a>".$lastReceive."</a></td></tr>";
	$html.= "<tr><td>&nbsp;</td></tr>";
	$html.= "<tr><td>REFNO : <a>".$row['refno']."</a></td></tr>";
	$html.= "<tr><td>VESSEL NAME : <a>".$CPublic->konversiQuotes( $row['namakapal'] )."</a></td></tr>";
	$html.= "<tr><td>DATE OF NOON : <a>".$dateNoon."</a></td></tr>";
	$html.= "<tr><td>LAST PORT : <a>".$row['lastport']."</a></td></tr>";
	$html.= "<tr><td>NEXT PORT :  <a>".$row['nextport']."</a></td></tr>";
	$html.= "<tr><td>POSITION : <a>".$row['position']."</a> </td></tr>";
	$html.= "<tr><td>COURSE : <a>".$row['course']."</a></td></tr>";
	$html.= "<tr><td>DISTANCE RUN : <a>".$row['distancerun']."</a></td></tr>";
	$html.= "<tr><td>STEAMING TIME : <a>".$row['steamingtime']."</a></td></tr>";
	$html.= "<tr><td>SPEED : <a>".$row['speed']."</a></td></tr>";
	$html.= "<tr><td>DIST TO GO : <a>".$row['disttogo']."</a></td></tr>";
	$html.= "<tr><td>ME RPM/DAY : <a>".$row['merpmday']."</a></td></tr>";
	$html.= "<tr><td>ENG SLIP : <a>".$row['engslip']."</a></td></tr>";
	$html.= "<tr><td>WIND DIR/FORCE : <a>".$row['winddir']."</a></td></tr>";
	$html.= "<tr><td>SEA SCALE : <a>".$row['seascale']."</a></td></tr>";
	$html.= "<tr><td>WEATHER COND : <a>".$row['weathercond']."</a> </td></tr>";
	$html.= "<tr><td>ETA : <a>".$row['eta']."</a> </td></tr>";
	$html.= "<tr><td>ROB MFO : <a>".$row['robmfo']."</a> </td></tr>";
	$html.= "<tr><td>ROB MDO : <a>".$row['robmdo']."</a> </td></tr>";
	$html.= "<tr><td>ROB ME CYL OIL : <a>".$row['robmecyloil']."</a> </td></tr>";
	$html.= "<tr><td>ROB ME SYS OIL : <a>".$row['robmesysoil']."</a></td></tr>";
	$html.= "<tr><td>ROB SUMP TANK : <a>".$row['robsumptank']."</a></td></tr>";
	$html.= "<tr><td>ROB AE / LO : <a>".$row['robae']."</a></td></tr>";
	$html.= "<tr><td>ROB FW : <a>".$row['robfw']."</a></td></tr>";
	$html.= "<tr><td>MFO CONSUMP ME : <a>".$row['mfoconsumpme']."</a></td></tr>";
	$html.= "<tr><td>MDO CONSUMP : <a>".$row['mdoconsump']."</a></td></tr>";
	$html.= "<tr><td>ME CYL LO CONSUMP : <a>".$row['mecylloconsump']."</a></td></tr>";
	$html.= "<tr><td>ME SYS LO CONSUMP : <a>".$row['mesysloconsump']."</a></td></tr>";
	$html.= "<tr><td>A/E LO CONSUMP : <a>".$row['aeloconsump']."</a></td></tr>";
	$html.= "<tr><td>SUMP TANK CONSUMP : <a>".$row['sumptankconsump']."</a></td></tr>";
	$html.= "<tr><td>FW CONSUMP : <a>".$row['fwconsump']."</a></td></tr>";
	$html.= "<tr><td>FW PRODUCT : <a>".$row['fwproduct']."</a></td></tr>";
	$html.= "<tr><td>REMARKS : <a>".nl2br2( $row['remarks'])."</a></td></tr>";
	$html.= "</table>";*/

	return $html;
}

function nl2br2($string) 
{
	$string = str_replace(array("\r\n", "\r", "\n"), "<br />", $string);
	return $string;
} 
?>
