<?php
require_once("../../config.php");

$kdDivGet = $_GET['kdDiv'];
$userIdSub = $_GET['userIdSub'];
$idKpi = $_GET['idKpi'];
?>
<script type="text/javascript" src="../../js/main.js"></script>
<script language="javascript">
function onClickTr(trId)
{	
	var idTrSeb = document.getElementById('idTrSeb').value;
	var tdKpiListSeb = document.getElementById('tdKpiListSeb').value;
	
	if(idTrSeb != "" || tdKpiListSeb != "")
	{
		document.getElementById(idTrSeb).onmouseover = function onmouseover(){	this.style.backgroundColor='#D9EDFF';	}
		document.getElementById(idTrSeb).onmouseout = function onmouseout(){	this.style.backgroundColor='#FFFFFF';	}
		document.getElementById(idTrSeb).style.fontWeight='';
		document.getElementById(idTrSeb).style.backgroundColor='#FFFFFF';
		document.getElementById(idTrSeb).style.cursor = 'pointer';	
		document.getElementById(tdKpiListSeb).style.fontWeight='';// FONT TIDAK BOLD UNTUK TD YANG DIPILIH
	}
	
	document.getElementById('tr'+trId).onmouseout = '';
	document.getElementById('tr'+trId).onmouseover ='';
	document.getElementById('tr'+trId).style.fontWeight='';
	document.getElementById('tr'+trId).style.backgroundColor='#B0DAFF';
	document.getElementById('tr'+trId).style.cursor = 'default';
	document.getElementById('tr'+trId).style.fontSize='10px';
	document.getElementById('idTrSeb').value = 'tr'+trId;
	
	document.getElementById('tdKpiList'+trId).style.fontWeight='bold'; // FONT BOLD UNTUK TD YANG DIPILIH
	document.getElementById('tdKpiListSeb').value = 'tdKpiList'+trId; // BERI ISI tdKpiListSeb DENGAN ID TD YANG DIPILIH SEBELUMNYA
	
	var idKpi = document.getElementById('idKpi'+trId).value;
	parent.pilihKpiList(idKpi);
}
</script>

<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportal/css/main.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportal/css/archives.css" rel="stylesheet" type="text/css" />

<style>

</style>

<!--<body bgcolor="#EBEBEB">-->
<body>

<table width="99%">
<input type="hidden" id="idTrSeb" name="idTrSeb">
<input type="hidden" id="tdKpiListSeb" name="tdKpiListSeb">
<?php
if($aksiGet == "addKpi")
{
	$kdDivGet = $_GET['kdDiv'];
	$tahunGet = $_GET['tahun'];
	$modelGet = $_GET['model'];
	$nmKpiOneGet = mysql_real_escape_string($_GET['nmKpiOne']);
	$nmKpiTwoGet = mysql_real_escape_string($_GET['nmKpiTwo']);
	$nmKpiThreeGet = mysql_real_escape_string($_GET['nmKpiThree']);
	
	$query = $CKoneksi->mysqlQuery("SELECT MAX(urutan) FROM tblkpi WHERE kddiv='".$kdDivGet."' AND tahun='".$tahunGet."' AND deletests=0;");
	$row = $CKoneksi->mysqlFetch($query);
	$maxUrutan = $row['MAX(urutan)'];
	$urutan = $maxUrutan + 1;
	
	$kpiTwoReport = "";
	$kpiThreeReport = "";
	if($nmKpiTwoGet!="")
	{
		$kpiTwoReport = ", kpidua = <b>".$nmKpiTwoGet."</b>";
	}
	if($nmKpiThreeGet!="")
	{
		$kpiThreeReport = ", kpitiga = <b>".$nmKpiThreeGet."</b>";
	}
	
	$CKoneksi->mysqlQuery("INSERT INTO tblkpi (urutan, kddiv, tahun, tipebaris, kpisatu, kpidua, kpitiga, addusrdt, deletests)VALUES ('".$urutan."', '".$kdDivGet."', '".$tahunGet."', '".$modelGet."', '".$nmKpiOneGet."', '".$nmKpiTwoGet."', '".$nmKpiThreeGet."',  '".$CPublic->userWhoAct()."', 0);");
	$lastInsertId = mysql_insert_id();
	$CHistory->updateLog($userIdLogin, "Menambah KPI List Divisi (idkpi = <b>".$lastInsertId."</b>, kddiv = <b>".$kdDivGet."</b>, tipebaris = <b>".$modelGet."</b>, kpisatu = <b>".$nmKpiOneGet."</b>".$kpiTwoReport.$kpiThreeReport.")");
}
if($aksiGet == "removeKpi")
{		
	$query = $CKoneksi->mysqlQuery("SELECT * FROM tblkpi WHERE idkpi='".$idKpi."' AND deletests=0;");
	while($row = $CKoneksi->mysqlFetch($query))
	{
		$kdDiv = $row['kddiv'];
		$kpiOne = $row['kpisatu'];
		$kpiTwo= $row['kpidua'];
		$kpiThree = $row['kpitiga'];
	}
	
	$kpiTwoReport = "";
	$kpiThreeReport = "";
	if($kpiTwo!="")
	{
		$kpiTwoReport = ", kpidua = <b>".$kpiTwo."</b>";
	}
	if($kpiThree!="")
	{
		$kpiThreeReport = ", kpitiga = <b>".$kpiThree."</b>";
	}

	$CKoneksi->mysqlQuery("UPDATE tblkpi SET deletests='1', delusrdt = '".$CPublic->userWhoAct()."' WHERE idkpi='".$idKpi."' AND deletests=0;");	
	$CHistory->updateLog($userIdLogin, "Menghapus KPI pada KPI List(idkpi = <b>".$idKpi."</b>, kddiv = <b>".$kdDiv."</b>, kpisatu = <b>".$kpiOne."</b>".$kpiTwoReport.$kpiThreeReport." )");
}

$html = "";
$urutan = 1;
$i=0;

$query = $CKoneksi->mysqlQuery("SELECT * FROM tblkpi WHERE kddiv='".$kdDivGet."' AND deletests=0 ORDER BY urutan ASC");
while($row = $CKoneksi->mysqlFetch($query))
{	
	$kpiSatuEnter = "";
	$kpiDuaEnter = "";
	$kpitigaEnter = "";
	if($row['tipebaris']!= "A")
	{
		$kpiSatuEnter = "<td width=\"9%\" valign=\"top\"><b>(One)</b>&nbsp;</td>";
	}
	if($row['kpidua']!="")
	{
		$kpiDuaEnter = "<tr class=\"fontMyFolderList\" valign=\"top\">
							<td width=\"9%\" valign=\"top\"><b>(Two)</b>&nbsp;</td>
                        	<td width=\"84%\" valign=\"top\">".$row['kpidua']."</td>
						</tr>";
	}
	if($row['kpitiga']!="")
	{
		$kpitigaEnter = "<tr class=\"fontMyFolderList\" valign=\"top\">
							<td width=\"9%\" valign=\"top\"><b>(Three)</b>&nbsp;</td>
                        	<td width=\"84%\" valign=\"top\">".$row['kpitiga']."</td>
						</tr>";
	}

	$i++;
	$html.= "";
?>

    <tr onMouseOver="this.style.backgroundColor='#DDF0FF';" onMouseOut="this.style.backgroundColor='#FFFFFF';" onClick="onClickTr('<?php echo $i; ?>')"  id="tr<?php echo $i; ?>" valign="top">
        <td class="tdMyFolder">
            <table width="100%" cellspacing="0">
            <tr class="fontMyFolderList">
                <td width="100%" id="tdKpiList<?php echo $i; ?>">
                <table  width="100%" cellspacing="0">
                	<tr class="fontMyFolderList" valign="top">
                    	<td rowspan="3" width="7%" align="center" style="color:#000080;font-weight:bold;font-family:Tahoma;"><?php echo $urutan++; ?></td>
                        <?php echo $kpiSatuEnter; ?>
                        <td width="93%" valign="top"><?php echo $row['kpisatu']; ?></td>
                    </tr>
					<?php echo $kpiDuaEnter; ?><?php echo $kpitigaEnter; ?>
                </table>
                </td>
            </tr>
            </table>
        </td>
    </tr>
    <input type="hidden" id="idKpi<?php echo $i; ?>" name="idKpi<?php echo $i; ?>" value="<?php echo $row['idkpi']; ?>">
<?php
	$html.= "";
}
echo $html;
?>
<div id="idHalCentangSubCustom" style="visibility:hidden;"></div>
</table>
</body>
<script language="javascript">
<?php
if($aksiGet == "addKpi" || $aksiGet == "removeKpi")
{
	echo "parent.refreshKpiForm();";
}
?>
</script>