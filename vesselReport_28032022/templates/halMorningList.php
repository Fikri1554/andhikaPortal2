<?php require_once("../configVslRep.php"); ?>
<!DOCTYPE HTML>
<script type="text/javascript" src="../../js/jquery-1.11.2.js"></script>
<script type="text/javascript" src="../js/vslRep.js"></script>
<script language="javascript">
function onClickTr(idMorning, rowGuid, bgColor, detailInfo)
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
	parent.document.getElementById('idMorning').value = idMorning;

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
	loadScroll('halIncomingList');
	/*parent.disabledBtn('btnMorningEdit');
	parent.disabledBtn('btnMorningDelete');*/
	parent.doneWait();
	parent.panggilEnableLeftClick();
	//document.getElementById('loaderImg').style.visibility = "hidden";
}
$(window).scroll(function(){
$('#judul').css('left','-'+$(window).scrollLeft()+'px');
});
</script>

<link href="../css/vslRep.css" rel="stylesheet" type="text/css" />
<link href="../css/table.css" rel="stylesheet" type="text/css" />

<body onUnload="saveScroll('halIncomingList')">

<div class="loader" id="loaderImg" style="visibility:hidden;"></div>
<input type="hidden" id="idTrSeb" name="idTrSeb">
<input type="hidden" id="bgColorSeb" name="bgColorSeb">

<?php
$aksiGet = $_GET['aksi'];
$idMorningGet = $_GET['idMorning'];
//$nmVslGet = $_GET['nmVsl'];
$initVslGet = $_GET['initVsl'];

//echo "<br><br>".$nmVslGet;

if($aksiGet == "delete")
{
	//echo "<br><br><br>".$idMorningGet;
	$CKoneksiVslRep->mysqlQuery("UPDATE tblmorning SET deletests=1, delusrdt='".$userWhoActNew."' WHERE idmorning=".$idMorningGet." AND deletests=0 LIMIT 1;", $CKoneksiVslRep->bukaKoneksi());
	$CHistory->updateLogVslRep($userIdLogin, "Simpan DELETE MORNING (idmorning=<b>".$idMorningGet."</b>, deletests=<b>0</b>, delusrdt=<b>".$userWhoAct."</b>)");	
}
?>

<!-- width="425"-->
<table id="judul" width="100%" cellpadding="0" cellspacing="0" style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:11px;position:fixed;left:0px;top:0px;">
<tr align="center">
    <td width="30" height="40" class="">NO</td>
    <td width="100" class="">REF NO</td>
    <td width="95" class="">DATE OF MORNING</td> 
    <td width="180" class="">NAME OF PORT</td>
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
	
	$query = $CKoneksiVslRep->mysqlQuery("SELECT * FROM tblmorning WHERE ".$sqlTambahan." deletests=0 ORDER BY lastreceive DESC;", $CKoneksiVslRep->bukaKoneksi());
    while($row = $CKoneksiVslRep->mysqlFetch($query))
    {
        $i++;
        $rowColor = $CPublic->rowColorCustom($i, "#FFFFFF", "#F0F1FF");
        
		$nextPort = $row['nextport'];
		//$lastReceive = $CVslRep->convLastReceive( $row['lastreceive'] );
		$dateMor = ($row['datemorning'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['datemorning'])."&nbsp;&nbsp;".$row['hourmorning'];
		
		$detailInfo = detailInfo($CKoneksiVslRep, $CPublic, $CVslRep, $row['idmorning'], $row['guid']);
		$onClick = "onClickTr('".$row['idmorning']."', '".$row['guid']."', '".$rowColor."', '".$detailInfo."');";
		$clikTR = $onClick;
        
        $tabel.=""?>

            <tr valign="bottom" align="left" bgcolor="<?php echo $rowColor; ?>"  onMouseOver="this.style.backgroundColor='#D9EDFF';" onMouseOut="this.style.backgroundColor='<?php echo $rowColor; ?>';" id="tr<?php echo $row['guid']; ?>" onClick="<?php echo $clikTR; ?>" 
            style="cursor:pointer;padding-bottom:1px;">
                <td width="30" height="22" class="tabelBorderTopLeftNull" align="center"><?php echo $i; ?></td>
                <td width="100" class="tabelBorderTopLeftNull">&nbsp;<?php echo $row['refno']; ?></td>
                <td width="95" class="tabelBorderTopLeftNull">&nbsp;<?php echo $dateMor; ?></td>   
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
	document.getElementById('tr<?php echo $idMorningGet; ?>').click();
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

function detailInfo($CKoneksiVslRep, $CPublic, $CVslRep, $idMorning, $rowGuid)
{
	$html = "";
	
	$query = $CKoneksiVslRep->mysqlQuery("SELECT * FROM tblmorning WHERE idmorning = '".$idMorning."' AND guid='".$rowGuid."' AND deletests=0;", $CKoneksiVslRep->bukaKoneksi());
    $row = $CKoneksiVslRep->mysqlFetch($query);
	
	$lastEdit = $CVslRep->convTglAddUsrDt($row['updusrdt']);
	$lastReceive = ($row['lastreceive'] == "")?"&nbsp;":$CVslRep->convLastReceive($row['lastreceive']);
	$dateMorning = ($row['datemorning'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['datemorning'])."&nbsp;&nbsp;".$row['hourmorning'];	
	$eta= ($row['eta'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['eta'])."&nbsp;&nbsp;".$row['houreta'];
	
	$arrayTd = array( array("LAST SAVED",$lastEdit,""), 
							array("LAST RECEIVE IN HO",$lastReceive,""), 
							array("&nbsp;","",""), 
							array("REFNO",$row['refno'],""), 
							array("VESSEL NAME",$CPublic->konversiQuotes( $row['namakapal'] ),""), 
							array("DATE OF MORNING",$dateMorning,"HRS"), 
							array("POSITION",$CPublic->konversiQuotes( $row['position'] ),""), 
							array("DIST TO GO",$CPublic->konversiQuotes( $row['disttogo'] ),"NM"), 
							array("SPEED",$CPublic->konversiQuotes( $row['speed'] ),"KTS"), 
							array("ETA",$eta,"HRS"),
							array("NEXT PORT",$CPublic->konversiQuotes( $row['nextport'] ),"")
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
	$html.= "<tr><td>LAST EDIT IN VESSEL : <a>".$lastEdit."</a></td></tr>";
	$html.= "<tr><td>LAST RECEIVE IN HO : <a>".$lastReceive."</a></td></tr>";
	$html.= "<tr><td>&nbsp;</td></tr>";
	$html.= "<tr><td>REFNO : <a>".$row['refno']."</a></td></tr>";
	$html.= "<tr><td>VESSEL NAME : <a>".$row['namakapal']."</a></td></tr>";
	$html.= "<tr><td>DATE OF MORNING : <a>".$dateMorning."</a></td></tr>";
	$html.= "<tr><td>POSITION : <a>".$row['position']."</a> </td></tr>";
	$html.= "<tr><td>DIST TO GO : <a>".$row['disttogo']."</a></td></tr>";
	$html.= "<tr><td>SPEED : <a>".$row['speed']."</a></td></tr>";
	$html.= "<tr><td>ETA : <a>".$row['eta']."</a> </td></tr>";
	$html.= "<tr><td>NEXT PORT :  <a>".$row['nextport']."</a></td></tr>";
	$html.= "</table>";
*/
	return $html;
}

function nl2br2($string) 
{
	$string = str_replace(array("\r\n", "\r", "\n"), "<br />", $string);
	return $string;
} 
?>
