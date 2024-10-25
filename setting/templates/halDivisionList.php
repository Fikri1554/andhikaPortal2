<?php
require_once("../../config.php");
$kdDivGet = $_GET['kdDiv'];
?>
<script language="javascript">
function onClickTr(trId, kdDiv, nmDivSent, jmlRow)
{	
	var idTrSeb = document.getElementById('idTrSeb').value;
	var idTdNameDivSeb = document.getElementById('idTdNameDivSeb').value;
	
	if(idTrSeb != "" || idTdNameDivSeb != "")
	{
		document.getElementById(idTrSeb).onmouseover = function onmouseover(){	this.style.backgroundColor='#D9EDFF';	}
		document.getElementById(idTrSeb).onmouseout = function onmouseout(){	this.style.backgroundColor='#FFFFFF';	}
		document.getElementById(idTrSeb).style.fontWeight='';
		document.getElementById(idTrSeb).style.backgroundColor='#FFFFFF';
		document.getElementById(idTrSeb).style.cursor = 'pointer';	
		document.getElementById(idTdNameDivSeb).style.fontWeight='';// FONT TIDAK BOLD UNTUK TD YANG DIPILIH
	}
	
	document.getElementById('tr'+trId).onmouseout = '';
	document.getElementById('tr'+trId).onmouseover ='';
	document.getElementById('tr'+trId).style.fontWeight='';
	document.getElementById('tr'+trId).style.backgroundColor='#B0DAFF';
	document.getElementById('tr'+trId).style.cursor = 'default';
	document.getElementById('tr'+trId).style.fontSize='10px';
	document.getElementById('idTrSeb').value = 'tr'+trId;
	
	document.getElementById('tdNameDivisi'+trId).style.fontWeight='bold'; // FONT BOLD UNTUK TD YANG DIPILIH
	document.getElementById('idTdNameDivSeb').value = 'tdNameDivisi'+trId; // BERI ISI idTdNameDivSeb DENGAN ID TD YANG DIPILIH SEBELUMNYA
	
	parent.pilihNameDivisi(kdDiv, nmDivSent, jmlRow);
}
</script>

<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportal/css/main.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportal/css/archives.css" rel="stylesheet" type="text/css" />

<style>

</style>

<body>

<table width="99%">
<input type="hidden" id="idTrSeb" name="idTrSeb">
<input type="hidden" id="idTdNameDivSeb" name="idTdNameDivSeb">

<?php
if($aksiGet == "addDivisi")
{
	$nameDivisi = mysql_real_escape_string($_GET['nmDiv']);
	//echo "name div : ".$nameDivisi;
	$CKoneksi->mysqlQuery("INSERT INTO tblmstdiv (nmdiv, deletests, addusrdt) VALUES ('".$nameDivisi."', '0', '".$CPublic->userWhoAct()."')");
	$lastInsertId = mysql_insert_id();
	$CHistory->updateLog($userIdLogin, "Menambah nama divisi ke dalam database (kdDiv = <b>".$lastInsertId."</b>, nameDiv = <b>".$nameDivisi."</b>)");
}
if($aksiGet == "removeDivisi")
{
	$query = $CKoneksi->mysqlQuery("SELECT nmdiv FROM tblmstdiv WHERE kddiv = '".$kdDivGet."'");
	while($row = $CKoneksi->mysqlFetch($query))
	{
		$nameDivisi = $row['nmdiv'];
	}
	//echo $nameDivisi;
	$CKoneksi->mysqlQuery("UPDATE tblmstdiv SET deletests='1', delusrdt = '".$CPublic->userWhoAct()."' WHERE kddiv = '".$kdDivGet."' AND deletests=0;");
	$CHistory->updateLog($userIdLogin, "Hapus nama Divisi (kdDiv = <b>".$kdDivGet."</b>, nameDiv = <b>".$nameDivisi."</b>)");
}

$html = "";
$urutan = 1;
$i=0;

$query = $CKoneksi->mysqlQuery("SELECT * FROM tblmstdiv WHERE deletests=0 ORDER BY kddiv ASC");
while($row = $CKoneksi->mysqlFetch($query))
{	
	$kdDiv = $row['kddiv'];
	$queryJml = $CKoneksi->mysqlQuery("SELECT idkpi FROM tblkpi WHERE kddiv='".$kdDiv."' AND deletests=0");
	$jmlRow = $CKoneksi->mysqlNRows($queryJml);
	
	$i++;
	$onClickTr = "onClickTr('".$i."', '".$kdDiv."', '".mysql_real_escape_string($row['nmdiv'])."', '".$jmlRow."');";
	
	$html.= "";
?>

    <tr onMouseOver="this.style.backgroundColor='#DDF0FF';" onMouseOut="this.style.backgroundColor='#FFFFFF';" onClick="<?php echo $onClickTr; ?>" id="tr<?php echo $i; ?>">
        <td class="tdMyFolder">
            <table width="100%">
            <tr class="fontMyFolderList">
                <td width="7%" align="center" style="color:#000080;font-weight:bold;font-family:Tahoma;"><?php echo $urutan++; ?></td>
                <td id="tdNameDivisi<?php echo $i; ?>" title="Please Click to add or remove KPI">
                <?php echo $row['nmdiv']; ?>&nbsp;
                </td>
            </tr>
            </table>
        </td>
    </tr>
    <input type="hidden" id="kdDiv<?php echo $i; ?>" name="kdDiv<?php echo $i; ?>" value="<?php echo $row['kddiv']; ?>">
    <input type="hidden" id="nmDiv<?php echo $i; ?>" name="nmDiv<?php echo $i; ?>" value="<?php echo $row['nmdiv']; ?>">
<?php
	$html.= "";	
}
echo $html;
?>
</table>
</body>

<script language="javascript">
<?php
if($aksiGet == "addDivisi" || $aksiGet == "removeDivisi")
{
	echo "parent.refreshPage();";
}
?>
</script>