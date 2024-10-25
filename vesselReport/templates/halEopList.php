<?php require_once("../configVslRep.php"); ?>
<!DOCTYPE HTML>
<script type="text/javascript" src="../../js/jquery-1.11.2.js"></script>
<script type="text/javascript" src="../js/vslRep.js"></script>
<script language="javascript">
function onClickTr(idEop, rowGuid, bgColor, detailInfo)
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
	}
	
	document.getElementById('tr'+rowGuid).onmouseout = '';
	document.getElementById('tr'+rowGuid).onmouseover ='';
	document.getElementById('tr'+rowGuid).style.backgroundColor='#B0DAFF';
	document.getElementById('tr'+rowGuid).style.cursor = 'default';
	document.getElementById('tr'+rowGuid).style.fontSize='11px';
	document.getElementById('idTrSeb').value = 'tr'+rowGuid;
	
	document.getElementById('bgColorSeb').value = bgColor;
	parent.document.getElementById('idEop').value = idEop;

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
	/*parent.disabledBtn('btnEopEdit');
	parent.disabledBtn('btnEopDelete');*/
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
$idEopGet = $_GET['idEop'];
//$nmVslGet = $_GET['nmVsl'];
$initVslGet = $_GET['initVsl'];


if($aksiGet == "delete")
{
	//echo "<br><br><br>".$idEopGet;
	$CKoneksiVslRep->mysqlQuery("UPDATE tbleop SET deletests=1, delusrdt='".$userWhoActNew."' WHERE ideop=".$idEopGet." AND deletests=0 LIMIT 1;", $CKoneksiVslRep->bukaKoneksi());
	$CHistory->updateLogVslRep($userIdLogin, "Simpan DELETE EOP (ideop=<b>".$idEopGet."</b>, deletests=<b>0</b>, delusrdt=<b>".$userWhoAct."</b>)");	
}
?>

<!-- width="425"-->
<table id="judul" width="100%" cellpadding="0" cellspacing="0" style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:11px;position:fixed;left:0px;top:0px;">
<tr align="center">
    <td width="30" height="40" class="">NO</td>
    <td width="100" class="">REF NO</td>
    <td width="95" class="">DATE OF EOP</td> 
    <td width="180" class="">NAME OF PORT</td>
    <td width="" class="">&nbsp;</td>
</tr>
</table>

<table width="100%" cellpadding="0" cellspacing="0" style="font-size:11px;font-family:Arial;color:#333;margin-top:40px;">
<?php
if($aksiGet == "display" || $aksiGet == "delete" || $aksiGet == "afterGen" || $aksiGet == "afterSendHo")
{
	//echo "<br><br>".$initVslGet;
	$tabel = "";
    $i=0;
	
	$sqlTambahan="";
	if($initVslGet != "all")
	{
		$sqlTambahan = " initvsl='".$initVslGet."' AND";
	}
	
	//echo "SELECT * FROM tbleop WHERE ".$sqlTambahan." deletests=0 ORDER BY lastreceive DESC;";
	//SELECT * FROM tbleop WHERE namakapal = 'ANDHIKA KANISHKA' AND deletests=0 ORDER BY lastreceive DESC;
	$query = $CKoneksiVslRep->mysqlQuery("SELECT * FROM tbleop WHERE ".$sqlTambahan." deletests=0 ORDER BY lastreceive DESC;", $CKoneksiVslRep->bukaKoneksi());
    while($row = $CKoneksiVslRep->mysqlFetch($query))
    {
        $i++;
        $rowColor = $CPublic->rowColorCustom($i, "#FFFFFF", "#F0F1FF");
        
		$namePort = $row['nameport'];
		//$lastReceive = $CVslRep->convLastReceive( $row['lastreceive'] );
		$dateEop = ($row['dateeop'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['dateeop'])."&nbsp;&nbsp;".$row['houreop'];
		
		$detailInfo = detailInfo($CKoneksiVslRep, $CPublic, $CVslRep, $row['ideop'], $row['guid']);
		$onClick = "onClickTr('".$row['ideop']."','".$row['guid']."', '".$rowColor."', '".$detailInfo."');";
		$clikTR = $onClick;

        $tabel.=""?>

            <tr valign="bottom" align="left" bgcolor="<?php echo $rowColor; ?>"  onMouseOver="this.style.backgroundColor='#D9EDFF';" onMouseOut="this.style.backgroundColor='<?php echo $rowColor; ?>';" id="tr<?php echo $row['guid']; ?>" onClick="<?php echo $clikTR; ?>" 
            style="cursor:pointer;padding-bottom:1px;">
                <td width="30" height="22" class="tabelBorderTopLeftNull" align="center"><?php echo $i; ?></td>
                <td width="100" class="tabelBorderTopLeftNull">&nbsp;<?php echo $row['refno']; ?></td>
                <td width="95" class="tabelBorderTopLeftNull">&nbsp;<?php echo $dateEop; ?></td>   
                <td width="" class="tabelBorderBottomJust">&nbsp;<?php echo $namePort; ?></td> 
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
	document.getElementById('tr<?php echo $idEopGet; ?>').click();
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

function detailInfo($CKoneksiVslRep, $CPublic, $CVslRep, $idEop, $rowGuid)
{
	$html = "";
	
	$query = $CKoneksiVslRep->mysqlQuery("SELECT * FROM tbleop WHERE ideop = '".$idEop."' AND guid='".$rowGuid."' AND deletests=0;", $CKoneksiVslRep->bukaKoneksi());
    $row = $CKoneksiVslRep->mysqlFetch($query);
	
	$namePort = $row['nameport'];	
	$dateEop = ($row['dateeop'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['dateeop'])."&nbsp;&nbsp;".$row['houreop'];
	$arrivalTimes = ($row['arrivaltimes'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['arrivaltimes'])."&nbsp;&nbsp;".$row['hourarrtimes'];
	$norTime = ($row['nortime'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['nortime'])."&nbsp;&nbsp;".$row['hournortime'];
	$droppedAnchor = ($row['droppedanchor'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['droppedanchor'])."&nbsp;&nbsp;".$row['hourdroppedanchor'];
	$fwe = ($row['fwe'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['fwe'])."&nbsp;&nbsp;".$row['hourfwe'];
	$robEop = ($row['robeop'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['robeop'])."&nbsp;&nbsp;".$row['hourrobeop'];
	$robFwe = ($row['robfwe'] == "0000-00-00")?"":$CPublic->convTglNonDB($row['robfwe'])."&nbsp;&nbsp;".$row['hourrobfwe'];
	
	$lastEdit = $CVslRep->convTglAddUsrDt($row['updusrdt']);
	$lastReceive = ($row['lastreceive'] == "")?"&nbsp;":$CVslRep->convLastReceive($row['lastreceive']);
	
	$arrayTd = array( array("LAST SAVED",$lastEdit,""), 
							array("LAST RECEIVED",$lastReceive,""),
							array("&nbsp;","",""), 
							array("REFNO",$row['refno'],""), 
							array("VESSEL NAME",$row['namakapal'],""), 
							array("DATE OF EOP",$dateEop,"HRS"),
							array("NAME OF PORT",$row['nameport'],""),
							array("ARRIVAL TIME",$arrivalTimes,"HRS"),
							array("NOR TIME",$norTime,"HRS"),
							array("ARR DRAFT F",$row['arrdraft_f'],"MTR"),
							array("ARR DRAFT M ",$row['arrdraft_m'],"MTR"),
							array("ARR DRAFT A",$row['arrdraft_a'],"MTR"),
							array("DROPPED ANCHOR",$droppedAnchor,"HRS"),
							array("POSITION",$row['position'],"HRS"),
							array("FWE",$fwe,"HRS"),
							array("ROB EOP",$robEop,"HRS"),
							array("MFO EOP",$row['mfoeop'],"MT"),
							array("MDO EOP",$row['mdoeop'],"MT"),
							array("ME CYL LO EOP",$row['mecyleop'],"LTRS"),
							array("ME SYS LO EOP",$row['mesyseop'],"LTRS"),
							array("AE LO EOP",$row['aeloeop'],"LTRS"),
							array("SUMP TK EOP",$row['sumptkeop'],"LTRS"),
							array("FW EOP",$row['fweop'],"MT"),
							array("TOTAL DIST",$row['totaldist'],"NM"),
							array("TOTAL TIME",$row['totaltime'],"HRS"),
							array("AV SPD",$row['avspd'],"KNOTS"),
							array("TOTAL MFO CONSUMP (ME/AE)",$row['totalmfo'],"MT"),
							array("AV MFO COMSUMP (ME/AE)",$row['avmfo'],"MT/ DAY"),
							array("TOTAL MDO CONSUMP (AE)",$row['totalmdo'],"MT"),
							array("AV MDO CONSUMP",$row['avmdo'],"MT/ DAY"),
							array("AV RPM",$row['avrpm'],""),
							array("AV WEATHER COND FOR VOYAGE",$row['avweather'],""),
							array("ROB FWE",$robFwe,"HRS"),
							array("MFO FWE",$row['mfofwe'],"MT"),
							array("MDO FWE",$row['mdofwe'],"MT"),
							array("ME CYL LO FWE",$row['mecyllofwe'],"LTRS"),
							array("ME SYS LO FWE",$row['mesyslofwe'],"LTRS"),
							array("AE LO FWE",$row['aelofwe'],"LTRS"),
							array("SUMP TK FWE",$row['sumptkfwe'],"LTRS"),
							array("FW FWE",$row['fwfwe'],"MT"),
							array("REMARKS",nl2br2( $row['remarks']),"")
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
	$html.= "<tr><td>DATE OF EOP : <a>".$dateEop."</a></td></tr>";
	$html.= "<tr><td>NAME OF PORT : <a>".$namePort."</a></td></tr>";
	$html.= "<tr><td>ARRIVAL TIME :  <a>".$arrivalTimes."</a></td></tr>";
	$html.= "<tr><td>NOR TIME : <a>".$norTime."</a> </td></tr>";
	$html.= "<tr><td>ARR DRAFT : <a>".$row['arrdraft']."</a></td></tr>";
	$html.= "<tr><td>DROPPED ANCHOR : <a>".$row['droppedanchor']."</a></td></tr>";
	$html.= "<tr><td>POSITION : <a>".$row['position']."</a></td></tr>";
	$html.= "<tr><td>FWE : <a>".$row['fwe']."</a></td></tr>";
	$html.= "<tr><td>&nbsp;</td></tr>";
	$html.= "<tr><td>ROB EOP : <a>".$row['robeop']."</a></td></tr>";
	$html.= "<tr><td>MFO EOP : <a>".$row['mfoeop']."</a></td></tr>";
	$html.= "<tr><td>MDO EOP : <a>".$row['mdoeop']."</a></td></tr>";
	$html.= "<tr><td>ME CYL LO EOP : <a>".$row['mecyleop']."</a></td></tr>";
	$html.= "<tr><td>ME SYS LO EOP : <a>".$row['mesyseop']."</a></td></tr>";
	$html.= "<tr><td>AE LO EOP : <a>".$row['aeloeop']."</a> </td></tr>";
	$html.= "<tr><td>SUMP TK EOP : <a>".$row['sumptkeop']."</a> </td></tr>";
	$html.= "<tr><td>FW EOP : <a>".$row['fweop']."</a> </td></tr>";
	$html.= "<tr><td>&nbsp;</td></tr>";
	$html.= "<tr><td>TOTAL DIST : <a>".$row['totaldist']."</a> </td></tr>";
	$html.= "<tr><td>TOTAL TIME : <a>".$row['totaltime']."</a> </td></tr>";
	$html.= "<tr><td>AV SPD : <a>".$row['avspd']."</a></td></tr>";
	$html.= "<tr><td>&nbsp;</td></tr>";
	$html.= "<tr><td>TOTAL MFO CONSUMP (ME/AE) : <a>".$row['totalmfo']."</a></td></tr>";
	$html.= "<tr><td>AV MFO COMSUMP (ME/AE) : <a>".$row['avmfo']."</a></td></tr>";
	$html.= "<tr><td>TOTAL MDO CONSUMP (AE) : <a>".$row['totalmdo']."</a></td></tr>";
	$html.= "<tr><td>AV MDO CONSUMP : <a>".$row['avmdo']."</a></td></tr>";
	$html.= "<tr><td>AV RPM : <a>".$row['avrpm']."</a></td></tr>";
	$html.= "<tr><td>AV WEATHER COND FOR VOYAGE : <a>".$row['avweather']."</a></td></tr>";
	$html.= "<tr><td>&nbsp;</td></tr>";
	$html.= "<tr><td>ROB FWE : <a>".$row['robfwe']."</a></td></tr>";
	$html.= "<tr><td>MFO FWE : <a>".$row['mfofwe']."</a></td></tr>";
	$html.= "<tr><td>MDO FWE : <a>".$row['mdofwe']."</a></td></tr>";
	$html.= "<tr><td>ME CYL LO FWE : <a>".$row['mecyllofwe']."</a></td></tr>";
	$html.= "<tr><td>ME SYS LO FWE : <a>".$row['mesyslofwe']."</a></td></tr>";
	$html.= "<tr><td>AE LO FWE : <a>".$row['aelofwe']."</a></td></tr>";
	$html.= "<tr><td>SUMP TK FWE : <a>".$row['sumptkfwe']."</a></td></tr>";
	$html.= "<tr><td>FW FWE : <a>".$row['fwfwe']."</a></td></tr>";
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
