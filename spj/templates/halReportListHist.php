<?php
require_once("../../config.php");
require_once("../configSpj.php");
?>

<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<link href="../css/cssSpj.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportaltes/css/main.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportaltes/css/archives.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../../js/main.js"></script>

<script language="javascript">
$(document).ready(function(){
	parent.doneWait();
});

function onClickTr(trId, jmlRow, status, reportReviseId)
{	
	var idTrSeb = document.getElementById('idTrSeb').value;
	var idTdNameSeb = document.getElementById('idTdNameSeb').value;
	var idTdTglSeb = document.getElementById('idTdTglSeb').value;
	var user = parent.document.getElementById('tipeUser').value;
	var halaman = "";
	
	if(idTrSeb != "" || idTdNameSeb != "")
	{
		document.getElementById(idTrSeb).onmouseover = function onmouseover(){	this.style.backgroundColor='#D9EDFF';	}
		document.getElementById(idTrSeb).onmouseout = function onmouseout(){	this.style.backgroundColor='#FFFFFF';	}
		document.getElementById(idTrSeb).style.fontWeight='';
		document.getElementById(idTrSeb).style.backgroundColor='#FFFFFF';
		document.getElementById(idTrSeb).style.cursor = 'pointer';	
		document.getElementById(idTdNameSeb).style.fontWeight='';// FONT TIDAK BOLD UNTUK TD YANG DIPILIH
		document.getElementById(idTdTglSeb).style.fontWeight='';
	}
	
	document.getElementById('tr'+trId).onmouseout = '';
	document.getElementById('tr'+trId).onmouseover ='';
	document.getElementById('tr'+trId).style.fontWeight='';
	document.getElementById('tr'+trId).style.backgroundColor='#B0DAFF';
	document.getElementById('tr'+trId).style.cursor = 'default';
	document.getElementById('tr'+trId).style.fontSize='10px';
	document.getElementById('idTrSeb').value = 'tr'+trId;
	
	document.getElementById('tdName'+trId).style.fontWeight='bold'; // FONT BOLD UNTUK TD YANG DIPILIH
	document.getElementById('tdTgl'+trId).style.fontWeight='bold';
	document.getElementById('idTdNameSeb').value = 'tdName'+trId; // BERI ISI idTdNameSeb DENGAN ID TD YANG DIPILIH SEBELUMNYA
	document.getElementById('idTdTglSeb').value = 'tdTgl'+trId;
	
	parent.document.getElementById('reportReviseId').value = reportReviseId ;
	parent.document.getElementById('trActive').value = trId ;
	parent.detailSpj(reportReviseId,halaman);
	parent.btnAksi(status, $('#userJenisSpj').val());
}

function aprvRefresh(id)
{
	document.getElementById('tr'+id).click();
}
</script>

<body onLoad="loadScroll('transList');" onUnload="saveScroll('transList');">

<table width="99%">
<input type="hidden" id="idTrSeb" name="idTrSeb">
<input type="hidden" id="idTdNameSeb" name="idTdNameSeb">
<input type="hidden" id="idTdTglSeb" name="idTdTglSeb">
<input type="hidden" id="userJenisSpj" name="userJenisSpj" value="<?php echo $userJenisSpj;?>">
<?php
$kadivHrEmpno =  $CEmployee->detilDiv("050", "divhead");
$i=1;
if($userJenisSpj == "admin")
{
	$query = $CKoneksiSpj->mysqlQuery("SELECT * FROM reportrevise WHERE deletests = 0 ORDER BY reportreviseid DESC;");
}
else
{
	$query = $CKoneksiSpj->mysqlQuery("SELECT * FROM reportrevise WHERE (ownerid = ".$userIdLogin." OR kadivid = ".$userIdLogin.") AND deletests = 0 ORDER BY reportreviseid DESC;");
}
$jmlRow = $CKoneksiSpj->mysqlNRows($query);
while($row = $CKoneksiSpj->mysqlFetch($query))
{	
	if($userJenisSpj == "admin")
	{
		$display = $CPublic->potongKarakter($CSpj->detilLoginSpj($row['ownerid'], "userfullnm", $db), 18);
	}
	else
	{
		if($row['ownerid'] == $userIdLogin)
		{
			$spjNo = $CSpj->detilForm($CSpj->detilReport($row['reportid'],"formid"),"spjno");
			$display = $CPublic->potongKarakter($spjNo, 18);
		}
		else
		{
			$display = $CPublic->potongKarakter($CSpj->detilLoginSpj($row['ownerid'], "userfullnm", $db), 18);
		}
	}
	$reportReviseId = $row['reportreviseid'];
	$tglRevise = $row['tglrevise'];
	$thn =  substr($tglRevise,0,4);
	$bln =  substr($tglRevise,4,2);
	$tgl =  substr($tglRevise,6,2);
	$echoTglRevise = $CPublic->bulanSetengah($bln, "eng")." ".$tgl.", ".$thn;
	
	$onClickTr = "onClickTr('".$i."', '".$jmlRow."','".$periksa."','".$reportReviseId."'); parent.pleaseWait();";
	//$onClickTr = "onClickTr('".$i."', '".$jmlRow."','".$status."','".$reportId."');";
?>

    <tr style="cursor:pointer;" id="tr<?php echo $i;?>" onMouseOver="this.style.backgroundColor='#DDF0FF';" onMouseOut="this.style.backgroundColor='#FFFFFF';" onClick="<?php echo $onClickTr;?>">
        <td class="spjTdMyFolder">
            <table width="100%">
            <tr class="fontMyFolderList" height="17">
                <td width="7%" align="center" style="color:#000080;font-weight:bold;font-family:Tahoma;"><?php echo $i; ?></td>
                <td width="58%" id="tdName<?php echo $i;?>" title="">
                	<?php echo $display;?>&nbsp;
                </td>
                <td id="tdTgl<?php echo $i;?>" width="35%" align="left">
                	<?php echo $echoTglRevise;?>
                </td>
                <!--<td width="7%" align="center" style=" <?php echo $stsDisp;?>"><?php echo $stsDisp1;?></td>-->
            </tr>
            </table>
        </td>
    </tr>
<?php	
$i++;}
if($jmlRow == 0)
{
?>
	<tr class="fontMyFolderList" height="17">
        <td style="color:red;">* There are no SPJ Report need your Check</td>
    </tr>
<?php
}

?>

</table>
</body>
<script language="javascript">
<?php
if($aksiGet == "exm")
{
	echo "parent.report('SPJ Report Succesfully Checked');
		  parent.klikTr('".$trActive."');";
}
?>
</script>