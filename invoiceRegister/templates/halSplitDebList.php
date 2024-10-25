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

<body onUnload="saveScroll('halInvProcessList')" bgcolor="#FFFFFF">
<div class="loader" id="loaderImg" style="visibility:hidden;"></div>
<input type="hidden" id="idTrSeb" name="idTrSeb">
<input type="hidden" id="bgColorSeb" name="bgColorSeb">

<?php
$aksiGet = $_GET['aksi'];
$idMailInvGet = $_GET['idMailInv'];

if($aksiGet == "tambahSplit")
{
	$amount = $CInvReg->detilMailInv($idMailInvGet, "amount");
	$description = mysql_escape_string( $CInvReg->detilMailInv($idMailInvGet, "remark") );
	
	$CKoneksiInvReg->mysqlQuery("INSERT INTO tblsplittemp (idmailinv, userid, fieldaksi, urutan, booksts, account, amount, description, addusrdt) VALUES ('".$idMailInvGet."', '".$userIdLogin."', 'memodebit', (SELECT CASE WHEN a.urutan IS NOT NULL THEN (MAX(a.urutan+1)) ELSE '1' END AS urutann FROM tblsplittemp a WHERE a.idmailinv='".$idMailInvGet."' AND a.userid=".$userIdLogin." AND a.fieldaksi='memodebit'), 'DB', '', '', '".$description."', '".$userWhoActNew."')");
}
if($aksiGet == "hapusDesc")
{
	$urutanGet = $_GET['urutan'];
	//echo "<br><br>".$aksiGet." / ".$idMailInvGet." / ".$urutanGet;
	$CKoneksiInvReg->mysqlQuery("DELETE FROM tblsplittemp WHERE  idmailinv=".$idMailInvGet." AND userid=".$userIdLogin." AND fieldaksi='memodebit' AND urutan=".$urutanGet." LIMIT 1;");
}
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
$tabel = "";
$i=0;
$query = $CKoneksiInvReg->mysqlQuery("SELECT * FROM tblsplittemp WHERE idmailinv='".$idMailInvGet."' AND userid=".$userIdLogin." AND fieldaksi='memodebit' ORDER BY urutan;", $CKoneksiInvReg->bukaKoneksi());
$jmlRow = $CKoneksiInvReg->mysqlNRows($query);
while($row = $CKoneksiInvReg->mysqlFetch($query))
{
	$i++;
	$urutan = $row['urutan'];
	$allUrutan .= $urutan.",";
	
	if($i == 1)
	{	
		//$amount1 = ($CInvReg->detilMailInv($idMailInvGet, "amount") - $CInvReg->totalAmountSplitDeb($idMailInvGet));
		
		$tabel.= "
		<tr align=\"center\" valign=\"bottom\">
			<td height=\"23\" width=\"25\" valign=\"middle\">".$i."</td>
			<td width=\"62\" align=\"right\"><input type=\"text\" id=\"account".$urutan."\" name=\"account".$urutan."\" class=\"elementInput3\" maxlength=\"5\" style=\"width:99%;\" value=\"".$row['account']."\" onBlur=\"simpanSplitDeb(this.value, '".$urutan."', 'account');\" disabled></td>
			<td width=\"129\" align=\"right\"><input type=\"text\" id=\"amount".$urutan."\" name=\"amount".$urutan."\" class=\"elementInput3\" style=\"width:99%;text-align:right;\" value=\"".number_format((float)$row['amount'], 2, '.', ',')."\" onKeyUp=\"simpanAmountSplitDeb(this.value, '".$urutan."');return false;\" onFocus=\"hanyaAngka".$urutan."();return false;\"></td>
			<td width=\"52\" align=\"right\"><input type=\"text\" id=\"vslCode".$urutan."\" name=\"vslCode".$urutan."\" class=\"elementInput3\" maxlength=\"3\" style=\"width:98%;text-align:right;\" value=\"".$row['vslcode']."\" onBlur=\"simpanSplitDeb(this.value, '".$urutan."', 'vslCode');\" disabled></td>
			<td width=\"\"><input type=\"text\" id=\"description".$urutan."\" name=\"description".$urutan."\" class=\"elementInput3\" maxlength=\"70\" style=\"width:99%;\" value=\"".$row['description']."\" onBlur=\"simpanSplitDeb(this.value, '".$urutan."', 'description');\"></td>
		</tr>";
	}
	else
	{
		$tabel.= "
		<tr align=\"center\" valign=\"bottom\">
			<td height=\"22\" valign=\"middle\" style=\"position:relative;\" onMouseOver=\"mouseOverTd('".$urutan."');\" onMouseOut=\"mouseOutTd('".$urutan."');\">".$i."
				<button type=\"button\" class=\"btnStandar\" id=\"btnDelete".$urutan."\" title=\"\" onclick=\"klikBtnDelete('".$urutan."');\" style=\"position:absolute;left:1px;top:1px;display:none;\">
					<table cellpadding=\"0\" cellspacing=\"0\" width=\"22\" height=\"19\">
					<tr>
						<td align=\"center\" valign=\"bottom\"><img src=\"../picture/cross.png\" height=\"14\" /></td>
					</tr>
					</table>
				</button>
			</td>
			<td align=\"right\"><input type=\"text\" id=\"account".$urutan."\" name=\"account".$urutan."\" class=\"elementInput3\" maxlength=\"5\" style=\"width:99%;\" value=\"".$row['account']."\" onBlur=\"simpanSplitDeb(this.value, '".$urutan."', 'account');\"></td>
			<td align=\"right\"><input type=\"text\" id=\"amount".$urutan."\" name=\"amount".$urutan."\" class=\"elementInput3\" style=\"width:99%;text-align:right;\" value=\"".number_format((float)$row['amount'], 2, '.', ',')."\" onKeyUp=\"simpanAmountSplitDeb(this.value, '".$urutan."');return false;\" onFocus=\"hanyaAngka".$urutan."();return false;\"></td>
			<td width=\"52\" align=\"right\"><input type=\"text\" id=\"vslCode".$urutan."\" name=\"vslCode".$urutan."\" class=\"elementInput3\" maxlength=\"3\" style=\"width:98%;text-align:right;\" value=\"".$row['vslcode']."\" onBlur=\"simpanSplitDeb(this.value, '".$urutan."', 'vslCode');\"></td>
			<td><input type=\"text\" id=\"description".$urutan."\" name=\"description".$urutan."\" class=\"elementInput3\" maxlength=\"70\" style=\"width:99%\" value=\"".$row['description']."\" onBlur=\"simpanSplitDeb(this.value, '".$urutan."', 'description');\"></td>
		</tr>";
	}
}

echo $tabel;	
?>
<input type="hidden" id="allUrutan" name="allUrutan" value="<?php echo $allUrutan; ?>"/>
</table>


</body>
</HTML>

<script>
<?php
$htmlHanyaAngka = "";
$htmlOnKeyPress = "";
$expAllUrutan = explode(",",$allUrutan);
$jmlExpAllUrutan = count($expAllUrutan);
?>
window.onload = function()
{
<?php
	for($aa=0;$aa<=($jmlExpAllUrutan-2);$aa++) 
	{
		$urutan = $expAllUrutan[$aa];
		$htmlOnKeyPress.= "
		$('#account".$urutan."').keypress(function (e)
		{
		  var charCode = (e.which) ? e.which : e.keyCode;
		  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		  }
		});
		
		$('#vslCode".$urutan."').keypress(function (e)
		{
		  var charCode = (e.which) ? e.which : e.keyCode;
		  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		  }
		});
		
		$('#amount".$urutan."').keypress(function (e)
		{
		  var charCode = (e.which) ? e.which : e.keyCode;
		  if (charCode > 31 && (charCode < 48 || charCode > 57))  // HANYA BISA ANGKA
		  {
			  if(charCode != 46) // TITIK DIPERBOLEHKAN UNTUK ANGKA DIBELAKANG KOMA
			  {
				 return false;
			  }
		  }
		});
		";
	}
	
	echo $htmlOnKeyPress;
?>
	
	//updateTotalDebAmt('<?php echo $jmlRow; ?>');
	updateTotalSplitDeb('<?php echo $jmlRow; ?>');
	updateTotalDebAmt();
	setTimeout(function()
	{
		parent.totalBalanceSplit();
	},100);
}

<?php
if($aksiGet == "hapusDesc")
{
?>
	
	updateTotalSplitDeb('<?php echo $jmlRow; ?>');
	updateTotalDebAmt();
	setTimeout(function()
	{
		parent.totalBalanceSplit();
	},100);
<?php	
}

for($a=0;$a<=($jmlExpAllUrutan-2);$a++) 
{
	$urutan = $expAllUrutan[$a];
	$htmlHanyaAngka .="
	function hanyaAngka".$urutan."()
	{
		amountMask".$urutan." = new Mask(\"#,###.##\", \"number\");
		amountMask".$urutan.".attach(document.getElementById('amount".$urutan."'));
	}
	";
}
echo $htmlHanyaAngka;
?>

function mouseOverTd(urutan)
{
	$("#btnDelete"+urutan).css("display","inline");
}

function mouseOutTd(urutan)
{
	$("#btnDelete"+urutan).css("display","none");
}

function klikBtnDelete(urutan)
{
	var answer = confirm('Are you sure want to delete?');
	if(answer)
	{	
		parent.loadIframe("iframeSplitDebList", "");
		parent.loadIframe("iframeSplitDebList", "../templates/halSplitDebList.php?aksi=hapusDesc&urutan="+urutan+"&idMailInv=<?php echo $idMailInvGet; ?>");
	}
	else
	{	return false;	}
}

function simpanSplitDeb(nilaiElement, urutan, idElement)
{
	$.post( "../halPostMailInv.php", { aksi:"simpanSplitDeb", idMailInv:"<?php echo $idMailInvGet; ?>", nilaiElement:nilaiElement, urutan:urutan, idElement:idElement }, function( data )
	{	});
}

function simpanAmountSplitDeb(nilaiElement, urutan)
{
	var incomingAmt = $("#incomingAmt", parent.document).val();
	$.post( "../halPostMailInv.php", { aksi:"simpanSplitDeb", idMailInv:"<?php echo $idMailInvGet; ?>", nilaiElement:nilaiElement, urutan:urutan, idElement:"amount" }, function( data )
	{});
	
	setTimeout(function()
	{
		//updateTotalDebAmt('<?php echo $jmlRow; ?>');
		updateTotalSplitDeb('<?php echo $jmlRow; ?>');
		updateTotalDebAmt();
	},100);
	
	setTimeout(function()
	{
		parent.totalBalanceSplit();
	},100);
}

function simpanAmountSplitDebOld(nilaiElement, urutan) // DIGUNAKAN KETIKA ROW 1 READONLY (TIDAK BISA EDIT) DAN ROW 1 MENJADI PENGURANG DARI ROW DIBAWAHNYA
{
	var incomingAmt = $("#incomingAmt", parent.document).val();
	$.post( "../halPostMailInv.php", { aksi:"simpanSplitDeb", idMailInv:"<?php echo $idMailInvGet; ?>", nilaiElement:nilaiElement, urutan:urutan, idElement:"amount", incomingAmt:incomingAmt }, function( data )
	{
		$("#amount1").val(data); // AMOUNT YANG BERUBAH HANYA AMOUNT YANG BERADA DI ROW URUTAN 1
	});
}

function updateTotalDebAmtOld(totalRow)
{
	var totalDebAmt = 0;
	var allUrutan = $("#allUrutan").val();
	var splitAllUrutan = allUrutan.split(",");
	var totalRow = parseInt(totalRow);
	for(var i=0; i<=(totalRow-1);i++)
	{
		var amount = $("#amount"+splitAllUrutan[i]).val().replace(/,/g,"");
		if($.trim(amount) == "")
		{
			amount = 0;
		}
		
		totalDebAmt += parseFloat( amount );
	}
	//alert(totalDebAmt);
	$("#spanTotalDebAmt", parent.document).html( "" );
	$("#spanTotalDebAmt", parent.document).html( formatNumber(totalDebAmt) );
	$("#totalDebAmt", parent.document).val( "" );
	$("#totalDebAmt", parent.document).val( totalDebAmt );
}

function updateTotalSplitDeb(totalRow)
{
	var totalSplitDeb = 0;
	var allUrutan = $("#allUrutan").val();
	var splitAllUrutan = allUrutan.split(",");
	var totalRow = parseInt(totalRow);
	for(var i=0; i<=(totalRow-1);i++)
	{
		var amount = $("#amount"+splitAllUrutan[i]).val().replace(/,/g,"");
		if($.trim(amount) == "")
		{
			amount = 0;
		}
		
		totalSplitDeb += parseFloat( amount );
	}
	$("#totalSplitDeb", parent.document).val( "" );
	$("#totalSplitDeb", parent.document).val( totalSplitDeb );
}

function updateTotalDebAmt()
{
	var totalDebAmt = 0;
	var totalSplitDeb = parseFloat($("#totalSplitDeb", parent.document).val());
	var totalAddi = parseFloat($("#totalAddi", parent.document).val());
	
	totalDebAmt = (totalSplitDeb + totalAddi);
	
	$("#spanTotalDebAmt", parent.document).html( "" );
	$("#spanTotalDebAmt", parent.document).html( formatNumber(totalDebAmt.toFixed(2)) );
	$("#totalDebAmt", parent.document).val( "" );
	$("#totalDebAmt", parent.document).val( totalDebAmt );
}
</script>