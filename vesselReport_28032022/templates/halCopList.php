<?php require_once("../configVslRep.php"); ?>
<!DOCTYPE HTML>
<script type="text/javascript" src="../../js/jquery-1.11.2.js"></script>
<script type="text/javascript" src="../js/vslRep.js"></script>
<script language="javascript">
function onClickTr(idCop, rowGuid, bgColor, detailInfo)
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
	document.getElementById('tr'+rowGuid).style.backgroundColor='#B0DAFF';
	document.getElementById('tr'+rowGuid).style.cursor = 'default';
	document.getElementById('tr'+rowGuid).style.fontSize='11px';
	document.getElementById('idTrSeb').value = 'tr'+rowGuid;
	
	document.getElementById('bgColorSeb').value = bgColor;
	parent.document.getElementById('idCop').value = idCop;

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
	loadScroll('halCopList');
	parent.doneWait();
	parent.panggilEnableLeftClick();
}
$(window).scroll(function(){
$('#judul').css('left','-'+$(window).scrollLeft()+'px');
});
</script>

<link href="../css/vslRep.css" rel="stylesheet" type="text/css" />
<link href="../css/table.css" rel="stylesheet" type="text/css" />

<body onUnload="saveScroll('halCopList')">

<div class="loader" id="loaderImg" style="visibility:hidden;"></div>
<input type="hidden" id="idTrSeb" name="idTrSeb">
<input type="hidden" id="bgColorSeb" name="bgColorSeb">

<?php
$aksiGet = $_GET['aksi'];
$idCopGet = $_GET['idCop'];
//$nmVslGet = $_GET['nmVsl'];
$initVslGet = $_GET['initVsl'];

//echo "<br><br>".$nmVslGet;

if($aksiGet == "delete")
{
	//echo "<br><br><br>".$idCopGet;
	$CKoneksiVslRep->mysqlQuery("UPDATE tblcop SET deletests=1, delusrdt='".$userWhoActNew."' WHERE idcop=".$idCopGet." AND deletests=0 LIMIT 1;", $CKoneksiVslRep->bukaKoneksi());
	$CHistory->updateLogVslRep($userIdLogin, "Simpan DELETE EOP (idcop=<b>".$idCopGet."</b>, deletests=<b>0</b>, delusrdt=<b>".$userWhoAct."</b>)");	
}
?>

<!-- width="425"-->
<table id="judul" width="100%" cellpadding="0" cellspacing="0" style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:11px;position:fixed;left:0px;top:0px;">
<tr align="center">
    <td width="30" height="40" class="">NO</td>
    <td width="100" class="">REF NO</td>
    <td width="95" class="">DATE OF COP</td> 
    <td width="140" class="">LAST PORT</td>
    <td width="140" class="">NEXT PORT</td>
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
	//echo $initVslGet;
	$query = $CKoneksiVslRep->mysqlQuery("SELECT * FROM tblcop WHERE ".$sqlTambahan." deletests=0 ORDER BY lastreceive DESC;", $CKoneksiVslRep->bukaKoneksi());
    while($row = $CKoneksiVslRep->mysqlFetch($query))
    {
        $i++;
        $rowColor = $CPublic->rowColorCustom($i, "#FFFFFF", "#F0F1FF");
        
		$dateCop = ($row['datecop'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['datecop'])."&nbsp;&nbsp;".$row['hourcop'];
		$lastPort = $row['lastport'];
		$nextPort = $row['nextport'];
		
		$detailInfo = detailInfo($CKoneksiVslRep, $CPublic, $CVslRep, $row['idcop'], $row['guid']);
		$onClick = "onClickTr('".$row['idcop']."', '".$row['guid']."', '".$rowColor."', '".$detailInfo."');";
		$clikTR = $onClick;
        
        $tabel.=""?>

            <tr valign="bottom" align="left" bgcolor="<?php echo $rowColor; ?>"  onMouseOver="this.style.backgroundColor='#D9EDFF';" onMouseOut="this.style.backgroundColor='<?php echo $rowColor; ?>';" id="tr<?php echo $row['guid']; ?>" onClick="<?php echo $clikTR; ?>" 
            style="cursor:pointer;padding-bottom:1px;">
                <td width="30" height="22" class="tabelBorderTopLeftNull" align="center"><?php echo $i; ?></td>
                <td width="100" class="tabelBorderTopLeftNull">&nbsp;<?php echo $row['refno']; ?></td>
                <td width="95" class="tabelBorderTopLeftNull" align="center"><?php echo $dateCop; ?></td>   
                <td width="140" class="tabelBorderTopLeftNull">&nbsp;<?php echo $lastPort; ?></td>
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
	document.getElementById('tr<?php echo $idCopGet; ?>').click();
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

function detailInfo($CKoneksiVslRep, $CPublic, $CVslRep, $idCop, $rowGuid)
{
	$html = "";
	
	$query = $CKoneksiVslRep->mysqlQuery("SELECT * FROM tblcop WHERE idcop = '".$idCop."' AND guid='".$rowGuid."' AND deletests=0;", $CKoneksiVslRep->bukaKoneksi());
    $row = $CKoneksiVslRep->mysqlFetch($query);
	
	$dateCop = ($row['datecop'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['datecop'])."&nbsp;&nbsp;".$row['hourcop'];
	$pilotOnBoard = ($row['pilotonboard'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['pilotonboard'])."&nbsp;&nbsp;".$row['hourpiloton'];
	$standByEngine = ($row['standbyengine'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['standbyengine'])."&nbsp;&nbsp;".$row['hourstandby'];
	$anchorUp = ($row['anchorup'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['anchorup'])."&nbsp;&nbsp;".$row['houranchorup'];
	$pilotOff = ($row['pilotoff'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['pilotoff'])."&nbsp;&nbsp;".$row['hourpilotoff'];
	$fullAway = ($row['fullaway'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['fullaway'])."&nbsp;&nbsp;".$row['hourfullaway'];
	$etaDest = ($row['etadest'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['etadest'])."&nbsp;&nbsp;".$row['houretadest'];
	
	$lastEdit = $CVslRep->convTglAddUsrDt($row['updusrdt']);
	$lastReceive = ($row['lastreceive'] == "")?"&nbsp;":$CVslRep->convLastReceive($row['lastreceive']);
	
	$arrayTd = array( array("LAST SAVED",$lastEdit,""), 
							array("LAST RECEIVE IN HO",$lastReceive,""), 
							array("&nbsp;","",""), 
							array("REFNO",$row['refno'],""), 
							array("VESSEL NAME",$CPublic->konversiQuotes( $row['namakapal'] ),""), 
							array("DATE OF COP",$dateCop,""), 
							array("LAST PORT",$CPublic->konversiQuotes( $row['lastport'] ),""), 
							array("NEXT PORT",$CPublic->konversiQuotes( $row['nextport'] ),""), 
							array("PILOT ON BOARD",$pilotOnBoard,"HRS"), 
							array("STAND BY ENGINE",$standByEngine,"HRS"), 
							array("UN BERTH",$CPublic->konversiQuotes( $row['unberth'] ),""), 
							array("DROP ANCHOR",$CPublic->konversiQuotes( $row['dropanchor'] ),""), 
							array("ANCHOR UP",$anchorUp,"HRS"), 
							array("PILOT OFF",$pilotOff,"HRS"), 
							array("COP / FULL AWAY",$fullAway,"HRS"), 
							array("DTG",$row['dtg'],"NM"), 
							array("ETA DESTINATION",$etaDest,"HRS"), 
							array("ARR DRAFT F",$row['draft_f'],"MTR"),
							array("ARR DRAFT M ",$row['draft_m'],"MTR"),
							array("ARR DRAFT A",$row['draft_a'],"MTR"),
							array("ROB MFO",$CPublic->konversiQuotes( $row['robmfo'] ),"MT"),
							array("ROB MGO",$CPublic->konversiQuotes( $row['robmgo'] ),"MT"), 
							array("ROB CYLINDER OIL",$CPublic->konversiQuotes( $row['robcyloil'] ),"LTRS"), 
							array("ROW SYSTEM OIL",$CPublic->konversiQuotes( $row['robsysoil'] ),"LTRS"), 
							array("ROB AE LO",$CPublic->konversiQuotes( $row['robaelo'] ),"LTRS"), 
							array("ROB SUMP TANK",$CPublic->konversiQuotes( $row['robsumptank'] ),"LTRS"), 
							array("ROB FW",$CPublic->konversiQuotes( $row['robfw'] ),"MT"), 
							array("RECEIVED MFO",$CPublic->konversiQuotes( $row['recmfo'] ),"MT"),
							array("RECEIVED MGO",$CPublic->konversiQuotes( $row['recmdo'] ),"MT"),
							array("RECEIVED CYL OIL",$CPublic->konversiQuotes( $row['reccyloil'] ),"LTRS"),
							array("RECEIVED SYS OIL",$CPublic->konversiQuotes( $row['recsysoil'] ),"LTRS"),
							array("RECEIVED AE LO",$CPublic->konversiQuotes( $row['recaelo'] ),"LTRS"),
							array("RECEIVED FW",$CPublic->konversiQuotes( $row['recfw'] ),"MT"),
							array("CONSUMPTION MFO",$CPublic->konversiQuotes( $row['conmfo'] ),"MT"),
							array("CONSUMPTION MDO",$CPublic->konversiQuotes( $row['conmdo'] ),"MT"),
							array("CONSUMPTION CYL OIL",$CPublic->konversiQuotes( $row['concyloil'] ),"LTRS"),
							array("CONSUMPTION SYS OIL",$CPublic->konversiQuotes( $row['consysoil'] ),"LTRS"),
							array("CONSUMPTION AE LO",$CPublic->konversiQuotes( $row['conaelo'] ),"LTRS"),
							array("CONSUMPTION FW",$CPublic->konversiQuotes( $row['confw'] ),"MT"),
							array("CARGO NAME",$CPublic->konversiQuotes( $row['cargoname'] ),""),
							array("CARGO QTY",$CPublic->konversiQuotes( $row['cargoqty'] ),"MT"),
							array("REMARKS",$CPublic->konversiQuotes( nl2br2($row['remarks']) ),"")
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
	$html.= "<tr><td>DATE OF COP : <a>".$dateCop."</a></td></tr>";
	$html.= "<tr><td>LAST PORT : <a>".$CPublic->konversiQuotes( $row['lastport'] )."</a></td></tr>";
	$html.= "<tr><td>NEXT PORT : <a>".$CPublic->konversiQuotes( $row['nextport'] )."</a></td></tr>";
	$html.= "<tr><td>PILOT ON BOARD :  <a>".$pilotOnBoard."</a></td></tr>";
	$html.= "<tr><td>STAND BY ENGINE : <a>".$standByEngine."</a> </td></tr>";
	$html.= "<tr><td>UN BERTH : <a>".$CPublic->konversiQuotes( $row['unberth'] )."</a></td></tr>";
	$html.= "<tr><td>DROP ANCHOR : <a>".$CPublic->konversiQuotes( $row['dropanchor'] )."</a></td></tr>";
	$html.= "<tr><td>ANCHOR UP : <a>".$anchorUp."</a> </td></tr>";
	$html.= "<tr><td>PILOT OFF : <a>".$pilotOff."</a> </td></tr>";
	$html.= "<tr><td>COP / FULL AWAY : <a>".$fullAway."</a> </td></tr>";
	$html.= "<tr><td>DTG : <a>".$row['dtg']."</a> </td></tr>";
	$html.= "<tr><td>ETA DESTINATION : <a>".$etaDest."</a> </td></tr>";
	$html.= "<tr><td>DRAFT : <a>".$CPublic->konversiQuotes( $row['draft'] )."</a> </td></tr>";
	$html.= "<tr><td>ROB MFO : <a>".$CPublic->konversiQuotes( $row['robmfo'] )."</a> </td></tr>";
	$html.= "<tr><td>ROB MGO : <a>".$CPublic->konversiQuotes( $row['robmgo'] )."</a> </td></tr>";
	$html.= "<tr><td>ROB CYLINDER OIL : <a>".$CPublic->konversiQuotes( $row['robcyloil'] )."</a> </td></tr>";
	$html.= "<tr><td>ROW SYSTEM OIL : <a>".$CPublic->konversiQuotes( $row['robsysoil'] )."</a> </td></tr>";
	$html.= "<tr><td>ROB AE LO : <a>".$CPublic->konversiQuotes( $row['robaelo'] )."</a> </td></tr>";
	$html.= "<tr><td>ROB SUMP TANK : <a>".$CPublic->konversiQuotes( $row['robsumptank'] )."</a> </td></tr>";
	$html.= "<tr><td>ROB FW : <a>".$CPublic->konversiQuotes( $row['robfw'] )."</a> </td></tr>";
	$html.= "<tr><td>&nbsp;</td></tr>";
	
	$html.= "<tr><td>RECEIVED MFO : <a>".$CPublic->konversiQuotes( $row['recmfo'] )."</a></td></tr>";
	$html.= "<tr><td>RECEIVED MGO : <a>".$CPublic->konversiQuotes( $row['recmdo'] )."</a></td></tr>";
	$html.= "<tr><td>RECEIVED CYL OIL : <a>".$CPublic->konversiQuotes( $row['reccyloil'] )."</a></td></tr>";
	$html.= "<tr><td>RECEIVED SYS OIL : <a>".$CPublic->konversiQuotes( $row['recsysoil'] )."</a></td></tr>";
	$html.= "<tr><td>RECEIVED AE LO : <a>".$CPublic->konversiQuotes( $row['recaelo'] )."</a></td></tr>";
	$html.= "<tr><td>RECEIVED FW : <a>".$CPublic->konversiQuotes( $row['recfw'] )."</a></td></tr>";
	$html.= "<tr><td>&nbsp;</td></tr>";
	
	$html.= "<tr><td>CONSUMPTION MFO : <a>".$CPublic->konversiQuotes( $row['conmfo'] )."</a></td></tr>";
	$html.= "<tr><td>CONSUMPTION MDO : <a>".$CPublic->konversiQuotes( $row['conmdo'] )."</a> </td></tr>";
	$html.= "<tr><td>CONSUMPTION CYL OIL : <a>".$CPublic->konversiQuotes( $row['concyloil'] )."</a> </td></tr>";
	$html.= "<tr><td>CONSUMPTION SYS OIL : <a>".$CPublic->konversiQuotes( $row['consysoil'] )."</a> </td></tr>";
	$html.= "<tr><td>CONSUMPTION AE LO : <a>".$CPublic->konversiQuotes( $row['conaelo'] )."</a></td></tr>";
	$html.= "<tr><td>CONSUMPTION FW : <a>".$CPublic->konversiQuotes( $row['confw'] )."</a> </td></tr>";
	$html.= "<tr><td>CARGO : <a>".$CPublic->konversiQuotes( $row['cargo'] )."</a> </td></tr>";
	$html.= "<tr><td>REMARKS : <a>".$CPublic->konversiQuotes( $row['remarks'] )."</a></td></tr>";
	$html.= "</table>";*/

	return $html;
}

function nl2br2($string) 
{
	$string = str_replace(array("\r\n", "\r", "\n"), "<br />", $string);
	return $string;
} 
?>
