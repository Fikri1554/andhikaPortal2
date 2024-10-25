<?php require_once("../configVslRep.php"); ?>
<!DOCTYPE HTML>
<script type="text/javascript" src="../../js/jquery-1.11.2.js"></script>
<script type="text/javascript" src="../js/vslRep.js"></script>
<script language="javascript">
function onClickTrNotif(idMsg, bgColor)
{
	var idTrSeb = document.getElementById('idTrSeb').value;
	var bgColorSeb = document.getElementById('bgColorSeb').value;
	
	if(idTrSeb != "")
	{
		document.getElementById(idTrSeb).onmouseover = function onmouseover(){	this.style.backgroundColor="#D9EDFF";	}
		document.getElementById(idTrSeb).onmouseout = function onmouseout(){	this.style.backgroundColor=bgColorSeb;	}
		document.getElementById(idTrSeb).style.fontWeight="";
		document.getElementById(idTrSeb).style.backgroundColor=bgColorSeb;
		document.getElementById(idTrSeb).style.cursor = "pointer";	
		//document.getElementById(idTrSeb).style.height = "22";
		//document.getElementById(idTrSeb).style.fontWeight="bold"; // FONT TIDAK BOLD UNTUK TD YANG DIPILIH
	}
	
	document.getElementById("tr"+idMsg).onmouseout = "";
	document.getElementById("tr"+idMsg).onmouseover ="";
	document.getElementById("tr"+idMsg).style.fontWeight="normal";
	document.getElementById("tr"+idMsg).style.backgroundColor="#B0DAFF";
	document.getElementById("tr"+idMsg).style.cursor = "default";
	document.getElementById("tr"+idMsg).style.fontSize="11px";
	document.getElementById("idTrSeb").value = "tr"+idMsg;
	//document.getElementById("tr"+trId).style.height = "";
	
	document.getElementById("bgColorSeb").value = bgColor;
	
	$.post( "../halPostVslRep.php", { aksi:"klikTrNotif", userIdLogin:"<?php echo $userIdSession; ?>", idMsg:idMsg }, function( data ){});
	
	
	parent.document.getElementById("idMsg").value = idMsg;
	parent.document.getElementById("divDetailPesan").innerHTML = "";
	parent.document.getElementById("divDetailPesan").innerHTML = document.getElementById("isiPesan"+idMsg).value;
	
	/*var checkNotiff = new parent.parent.checkNotif();
	checkNotiff.stopCheck(checkNotiff);*/
	
	
}
</script>

<link href="../css/vslRep.css" rel="stylesheet" type="text/css" />
<link href="../css/table.css" rel="stylesheet" type="text/css" />

<body onUnload="saveScroll('halNotifList')">

<div class="loader" id="loaderImg" style="visibility:hidden;"></div>
<input type="hidden" id="idTrSeb" name="idTrSeb">
<input type="hidden" id="bgColorSeb" name="bgColorSeb">

<table id="judul" width="100%" cellpadding="0" cellspacing="0" style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:11px;position:fixed;left:0px;top:0px;">
<tr align="center">
    <td width="25" height="30" class="">NO</td>
    <td width="235" class="">MESSAGE</td>
    <td width="" class="">RECEIVED</td>
</tr>
</table>

<table width="100%" cellpadding="0" cellspacing="0" style="font-size:11px;font-family:Arial;color:#333;margin-top:30px;">
<?php
$reportTypeGet = $_GET['reportType'];
$tabel = "";
$i=0;

$sqlReportType = "AND reporttype='".$reportTypeGet."'";
if($reportTypeGet == "allReport")
{ 
	$sqlReportType = "";
}  
    
$query = $CKoneksiVslRep->mysqlQuery("SELECT *, DATE_FORMAT(timerec,'%d, %b %Y %h:%i %p') AS timerec FROM tblpesan WHERE userid='".$userIdSession."' ".$sqlReportType." AND readd='N' AND deletests=0 ORDER BY timerec DESC, idmsg DESC;", $CKoneksiVslRep->bukaKoneksi());
while($row = $CKoneksiVslRep->mysqlFetch($query))
{
	$i++;
	$rowColor = $CPublic->rowColorCustom($i, "#FFFFFF", "#F0F1FF");
	
	$isiPesan = substr($row['isipesan'], 0, 33)."...";
	$clickTR = "onClickTrNotif('".$row['idmsg']."', '".$rowColor."');";
	
	$tabel.=""?>

    <tr valign="bottom" align="left" bgcolor="<?php echo $rowColor; ?>"  onMouseOver="this.style.backgroundColor='#D9EDFF';" onMouseOut="this.style.backgroundColor='<?php echo $rowColor; ?>';" id="tr<?php echo $row['idmsg']; ?>" onClick="<?php echo $clickTR; ?>" style="cursor:pointer;padding-bottom:1px;font-weight:bold;">
        <td width="25" height="22" class="tabelBorderTopLeftNull" align="center"><?php echo $i; ?></td>
        <td width="235" class="tabelBorderTopLeftNull">&nbsp;<?php echo $isiPesan; ?></td> 
        <td width="" class="tabelBorderBottomJust">&nbsp;<?php echo $row['timerec']; ?></td> 
        <input type="hidden" id="isiPesan<?php echo $row['idmsg']; ?>" name="isiPesan<?php echo $row['idmsg']; ?>" value="<?php echo $row['isipesan']; ?>">
    </tr>
    
    <?php echo "";
}
echo $tabel;
?>
</table>


</body>
</html>
