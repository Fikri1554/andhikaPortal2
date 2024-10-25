<?php
require_once("../../config.php");

$userIdHAccess = $_GET['userId'];
$appName = $_GET['appName'];
$idAppGet = $_GET['idApp'];
?>
<script language="javascript">
function onClickTr(trId, idApp) {
    var idTrSeb = document.getElementById('idTrSeb').value;
    var idTdUserFullNmSeb = document.getElementById('idTdUserFullNmSeb').value;

    if (idTrSeb != "" || idTdUserFullNmSeb != "") {
        document.getElementById(idTrSeb).onmouseover = function onmouseover() {
            this.style.backgroundColor = '#D9EDFF';
        }
        document.getElementById(idTrSeb).onmouseout = function onmouseout() {
            this.style.backgroundColor = '#FFFFFF';
        }
        document.getElementById(idTrSeb).style.fontWeight = '';
        document.getElementById(idTrSeb).style.backgroundColor = '#FFFFFF';
        document.getElementById(idTrSeb).style.cursor = 'pointer';
        document.getElementById(idTdUserFullNmSeb).style.fontWeight = ''; // FONT TIDAK BOLD UNTUK TD YANG DIPILIH
    }

    document.getElementById('tr' + trId).onmouseout = '';
    document.getElementById('tr' + trId).onmouseover = '';
    document.getElementById('tr' + trId).style.fontWeight = '';
    document.getElementById('tr' + trId).style.backgroundColor = '#B0DAFF';
    document.getElementById('tr' + trId).style.cursor = 'default';
    document.getElementById('tr' + trId).style.fontSize = '10px';
    document.getElementById('idTrSeb').value = 'tr' + trId;

    document.getElementById('tdUserFullNm' + trId).style.fontWeight = 'bold'; // FONT BOLD UNTUK TD YANG DIPILIH
    document.getElementById('idTdUserFullNmSeb').value = 'tdUserFullNm' +
        trId; // BERI ISI idTdUserFullNmSeb DENGAN ID TD YANG DIPILIH SEBELUMNYA

    var userIdSelect = document.getElementById('userIdSelect' + trId).value;
    parent.pilihUserHaveAccess(userIdSelect, idApp);
}
</script>

<link href="../../css/main.css" rel="stylesheet" type="text/css" />
<link href="../../css/archives.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportaltes/css/main.css" rel="stylesheet" type="text/css" />
<link href="../../andhikaportaltes/css/archives.css" rel="stylesheet" type="text/css" />

<style>

</style>

<body>

    <table width="99%">
        <input type="hidden" id="idTrSeb" name="idTrSeb">
        <input type="hidden" id="idTdUserFullNmSeb" name="idTdUserFullNmSeb">
        <?php
if($aksiGet == "addOtherApp")
{
	$userFullNmHA = $CLogin->detilLogin($userIdHAccess, "userfullnm");
	$appFullNm = "";
	if($appName == "invoiceRegister")
	{
		$appFullNm = "INVOICE REGISTER";
	}
	if($appName == "budget")
	{
		$appFullNm = "Budget";
	}
	if($appName == "qhse")
	{
		$appFullNm = "QHSE";
	}
	if($appName == "survey")
	{
		$appFullNm = "SURVEY";
	}
	if($appName == "safir")
	{
		$appFullNm = "SAFIR";
	}
	if($appName == "atkrequest")
	{
		$appFullNm = "ATK REQUEST";
	}
	if($appName == "otherReport")
	{
		$appFullNm = "OTHER REPORT";
	}
	if($appName == "voucher")
	{
		$appFullNm = "VOUCHER";
	}
	
	$CKoneksi->mysqlQuery("INSERT INTO otherapp (userid, userfullnm, nmapp, appfullnm, addusrdt) VALUES ('".$userIdHAccess."', '".$userFullNmHA."', '".$appName."', '".$appFullNm."', '".$CPublic->userWhoAct()."')");
	$CHistory->updateLog($userIdLogin, "Beri akses ".$appFullNm." kepada (userid = <b>".$userIdHAccess."</b>, userfullnm = <b>".$userFullNmHA."</b>)");
}
if($aksiGet == "removeOtherApp")
{
	$otherAppDB = $COtherApp->detilOtherApp($idAppGet, "appfullnm");

	$userFullNmHA = $CLogin->detilLogin($userIdHAccess, "userfullnm");
	
	$CKoneksi->mysqlQuery("UPDATE otherapp SET deletests='1', updusrdt = '".$CPublic->userWhoAct()."' WHERE idapp = '".$idAppGet."' AND deletests=0;");
	$CHistory->updateLog($userIdLogin, "Hapus akses ".$otherAppDB." dari (userid = <b>".$userIdHAccess."</b>, userfullnm = <b>".$userFullNmHA."</b>)");
}

$html = "";
$urutan = 1;
$i=0;

$query = $CKoneksi->mysqlQuery("SELECT * FROM otherapp WHERE deletests=0 ORDER by userfullnm ASC");
while($row = $CKoneksi->mysqlFetch($query))
{	
	$i++;
	$onClickTr = "onClickTr('".$i."', '".$row['idapp']."');";
	$html.= "";
?>

        <tr onMouseOver="this.style.backgroundColor='#DDF0FF';" onMouseOut="this.style.backgroundColor='#FFFFFF';"
            onClick="<?php echo $onClickTr; ?>" id="tr<?php echo $i; ?>">
            <td class="tdMyFolder">
                <table width="100%">
                    <tr class="fontMyFolderList">
                        <td width="7%" align="center" style="color:#000080;font-weight:bold;font-family:Tahoma;">
                            <?php echo $urutan++; ?></td>
                        <td id="tdUserFullNm<?php echo $i; ?>" title="Please Click to setting access">&nbsp;
                            <?php echo $row['userfullnm']; ?>&nbsp;[ <?php echo $row['appfullnm'];?> ]
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <input type="hidden" id="userIdSelect<?php echo $i; ?>" name="userIdSelect<?php echo $i; ?>"
            value="<?php echo $row['userid']; ?>">
        <?php
	$html.= "";
}
echo $html;
?>
    </table>
</body>

<script language="javascript">
<?php
if($aksiGet == "addOtherApp" || $aksiGet == "removeOtherApp")
{
	echo "parent.refreshPage();";
}
?>
</script>