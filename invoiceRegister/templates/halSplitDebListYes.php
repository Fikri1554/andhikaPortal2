<?php require_once("../configInvReg.php"); ?>
<!DOCTYPE HTML>
<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../../js/main.js"></script>
<script type="text/javascript" src="../js/process.js"></script>
<script type="text/javascript" src="../js/masks.js"></script>
<script language="javascript">

</script>

<link href="../css/invReg.css" rel="stylesheet" type="text/css" />
<link href="../css/table.css" rel="stylesheet" type="text/css" />
<link href="../css/button.css" rel="stylesheet" type="text/css"/>

<body onUnload="saveScroll('halInvProcessList')">
<div class="loader" id="loaderImg" style="visibility:hidden;"></div>
<input type="hidden" id="idTrSeb" name="idTrSeb">
<input type="hidden" id="bgColorSeb" name="bgColorSeb">

<?php
$aksiGet = $_GET['aksi'];
$idMailInvGet = $_GET['idMailInv'];
?>

<table cellpadding="0" cellspacing="0" width="100%" style="border:solid 0px #666;font:12px sans-serif;font-weight:bold;color:#485a88;position:fixed;left:0px;top:0px;z-index:1;">
<tr align="center" style="background-color:#F2FAFF;">
	<td height="20" width="25" class="tabelBorderBottomJust">No</td>
    <td width="62" class="tabelBorderBottomJust">Account</td>
    <td width="129" class="tabelBorderBottomJust">Amount (DB)</td>
    <td width="52" class="tabelBorderBottomJust">Vsl Code</td>
    <td width="" class="tabelBorderBottomJust">Description</td>
</tr>
</table>

<table cellpadding="0" cellspacing="0" width="100%" style="font:0.7em sans-serif;color:#333;margin-top:20px;" onKeyPress="">
<?php
$totalAmt = 0;
$tabel = "";
$i=0;
$query = $CKoneksiInvReg->mysqlQuery("SELECT * FROM tblsplit WHERE idmailinv='".$idMailInvGet."' AND fieldaksi='memodebit' ORDER BY urutan;", $CKoneksiInvReg->bukaKoneksi());
$jmlRow = $CKoneksiInvReg->mysqlNRows($query);
while($row = $CKoneksiInvReg->mysqlFetch($query))
{
	$i++;
	if($i == 1)
	{
		$tabel.= "
		<tr align=\"center\" valign=\"bottom\">
			<td height=\"23\" width=\"25\" valign=\"middle\">".$i."</td>
			<td width=\"62\" align=\"right\"><input type=\"text\" class=\"elementInput3\" maxlength=\"5\" style=\"width:99%;\" value=\"".$row['account']."\" readonly></td>
			<td width=\"129\" align=\"right\"><input type=\"text\" class=\"elementInput3\" style=\"width:99%;text-align:right;\" value=\"".number_format((float)$row['amount'], 2, '.', ',')."\" readonly></td>
			<td width=\"52\" align=\"right\"><input type=\"text\" class=\"elementInput3\" style=\"width:98%;text-align:right;\" value=\"".$row['vslcode']."\" readonly></td>
			<td width=\"\"><input type=\"text\" class=\"elementInput3\" maxlength=\"70\" style=\"width:99%;\" value=\"".$row['description']."\" readonly></td>
		</tr>";
	}
	else
	{
		$tabel.= "
		<tr align=\"center\" valign=\"bottom\">
			<td height=\"22\" width=\"25\" valign=\"middle\">".$i."</td>
			<td width=\"62\"  align=\"right\"><input type=\"text\" class=\"elementInput3\" maxlength=\"5\" style=\"width:99%;\" value=\"".$row['account']."\" readonly></td>
			<td align=\"right\"><input type=\"text\" class=\"elementInput3\" style=\"width:99%;text-align:right;\" value=\"".number_format((float)$row['amount'], 2, '.', ',')."\" readonly></td>
			<td width=\"52\" align=\"right\"><input type=\"text\" class=\"elementInput3\" style=\"width:98%;text-align:right;\" value=\"".$row['vslcode']."\" readonly></td>
			<td><input type=\"text\" class=\"elementInput3\" maxlength=\"70\" style=\"width:99%\" value=\"".$row['description']."\" readonly></td>
		</tr>";
	}
	
	$totalAmt += $row['amount'];
}
$totalAmt = $totalAmt;
echo $tabel;	
?>
</table>
</body>
</HTML>

<script>
window.onload = function()
{
	setTimeout(function()
	{
		$("#spanTotalDebAmt", parent.document).html('<?php echo number_format((float)$totalAmt, 2, '.', ','); ?>');
	},50);
}
</script>