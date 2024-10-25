<!DOCTYPE HTML>
<?php 
require_once("../configVoucher.php");

$aksiGet = $_GET['aksi'];
$idVoucherGet = $_GET['idVoucher'];
$batchnoGet = $_GET['batchno'];
$idDescGet = $_GET['idDesc'];
//echo $aksiGet;
?>
<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../../js/JavaScriptUtil.js"></script>
<script type="text/javascript" src="../../js/Parsers.js"></script>
<script type="text/javascript" src="../../js/InputMask.js"></script>
<script type="text/javascript" src="../js/voucher.js"></script>
<script type="text/javascript" src="../js/aksi.js"></script>
<script language="javascript">
window.onload = 
function() 
{
	parent.document.getElementById('loaderImg').style.visibility = "hidden";
	parent.doneWait();
	parent.panggilEnableLeftClick();
	
	loadScroll('halDescList');
	//parent.disabledBtn('btnAckDetail');
	
	parent.$('#loaderImgDesc').css('visibility','hidden');
}

$(document).ready(function()
{
	$(":input").focus(function(e) // DALAM TD ADA BEBERAPA ELEMENT JIKA FOKUS MAKA ELEMENT TSB ROW ADA YANG BERUBAH
	{
    	//$(this).parent().parent().addClass('classSelect');
		//$(this).parent().parent().style.backgroundColor='#D9EDFF';
		var trIdSelect = $(this).parent().parent().parent().closest('tr').attr('id');
		$('#idTrSeb').val( $('#idTrSek').val() );
		$('#idTrSek').val(trIdSelect);
		
		var idTrSeb = $('#idTrSeb').val();
		var idTrSek = $('#idTrSek').val();
			
		if(idTrSeb != "")
		{
			$('#'+idTrSeb).css('background-color','F0F1FF');		
			document.getElementById(idTrSeb).onmouseover = function onmouseover(){	this.style.backgroundColor='#D9EDFF';	}
			document.getElementById(idTrSeb).onmouseout = function onmouseout(){	this.style.backgroundColor='#F0F1FF';	}
			document.getElementById(idTrSeb).style.fontWeight='';
		}
		
		if(trIdSelect != "")
		{
			$('#'+trIdSelect).css('background-color','D9EDFF');
			document.getElementById(trIdSelect).onmouseover = function onmouseover(){	this.style.backgroundColor='#D9EDFF';	}
			document.getElementById(trIdSelect).onmouseout = function onmouseout(){	this.style.backgroundColor='#D9EDFF';	}
			
			$('#'+trIdSelect).css("font-weight","bold");
        	e.stopPropagation(); 
		}

		
	}).blur(function(e)
	{
    	//$(this).parent().parent().removeClass('classSelect');
	});
	//var trIdSelect = $(this).closest('tr').find('.classSelect').id();
	//alert(trIdSelect);
});


function hapusDesc(idDesc, idVoucher)
{
	var answer  = confirm('Are you sure want to delete?');
	if(answer)
	{	
		//pleaseWait();
		//document.onmousedown=disableLeftClick;
		
		parent.loadIframe('iframeDescList', '');
		parent.loadIframe('iframeDescList', 'templates/halDescList.php?aksi=hapusDesc&idDesc='+idDesc+'&idVoucher='+idVoucher);
		//document.getElementById('iframeDescList').src = "";
		//document.getElementById('iframeDescList').src = "templates/halDescList.php?aksi=tambahDesc&idVoucher="+idVoucher;
	}
	else
	{	return false;	}
}
</script>
<link href="../css/table.css" rel="stylesheet" type="text/css" />
<link href="../css/button.css" rel="stylesheet" type="text/css" />
<link href="css/voucher.css" rel="stylesheet" type="text/css" />

<body onUnload="saveScroll('halDescList')">

<input type="hidden" id="idTrSeb" name="idTrSeb">
<input type="hidden" id="idTrSek" name="idTrSek">
<input type="hidden" id="bgColorSeb" name="bgColorSeb">
<?php
if($aksiGet == "tambahDesc" || $aksiGet == "hapusDesc" || $aksiGet == "saveDesc")
{
	if($aksiGet == "tambahDesc")
	{
		$urutan = $CVoucher->cariUrutanMaxDesc($idVoucherGet)+1;
		//$urutan = $CVoucher->jmlRowDesc($idVoucherGet)+1;
		$CKoneksiVoucher->mysqlQuery("INSERT INTO tbldesc 
						 (idvoucher, batchno, urutan, addusrdt) 
						 VALUES 
						( '".$idVoucherGet."', 
						'".$batchnoGet."', 
						".$urutan.",
						'".$userWhoAct."')");
						
		$lastInsertId = mysql_insert_id();				
		$CHistory->updateLogVoucher($userIdLogin, "Simpan TAMBAH DESCRIPTION KOSONG BARU Voucher (iddesc=<b>".$lastInsertId."</b>, idvoucher=<b>".$idVoucherGet."</b>, batchno=<b>".$batchnoGet."</b>, addusrdt=<b>".$userWhoAct."</b>)");
	}
	if($aksiGet == "hapusDesc")
	{
		$CKoneksiVoucher->mysqlQuery("UPDATE tbldesc SET deletests=1, delusrdt='".$userWhoAct."' WHERE iddesc='".$idDescGet."' AND deletests=0"); 
		$CHistory->updateLogVoucher($userIdLogin, "Simpan HAPUS DESCRIPTION Voucher (iddesc=<b>".$idDescGet."</b>, idvoucher=<b>".$idVoucherGet."</b>, delusrdt=<b>".$userWhoAct."</b>)");
	}
	if($aksiGet == "saveDesc")
	{
		$allIdDescGet = $_GET['allIdDesc'];
		$expAllIdDesc = explode(",",$allIdDescGet);
		$jmlExpAllIdDesc = count($expAllIdDesc);
		for($a=0;$a<=($jmlExpAllIdDesc-2);$a++)
		{
			$idDescGet = $expAllIdDesc[$a];
			$keteranganGet = strtoupper( mysql_escape_string($_GET['desc'.$idDescGet]) );
			$descBookStsGet = $_GET['descBookSts'.$idDescGet];
			$descAmountGet = str_replace(",","",$_GET['descAmount'.$idDescGet]);
			$perkiraanGet = $_GET['perkiraan'.$idDescGet];
			$subAccGet = $_GET['subAcc'.$idDescGet];
			$descDivGet = $_GET['descDiv'.$idDescGet];
			$descDivName = $CVoucher->detilUnit($descDivGet, "nmunit");
			$vesCodeGet = $_GET['vesCode'.$idDescGet];
			$vesName = $CVoucher->detilVessel($vesCodeGet, "Fullname");
			$descBookStsGet = $_GET['descBookSts'.$idDescGet];
			//echo $expAllIdDesc[$a]."<br>";
			//echo $keteranganGet." | ".$descBookStsGet." | ".$descCurrGet." | ".$descAmountGet." | ".$perkiraanGet." | ".$subAccGet." | ".$descDivGet."<br>";
			$CKoneksiVoucher->mysqlQuery("UPDATE tbldesc SET keterangan='".$keteranganGet."', amount='".$descAmountGet."', perkiraan='".$perkiraanGet."', subacc='".$subAccGet."', unit='".$descDivGet."', unitname='".$descDivName."', vescode='".$vesCodeGet."', vesname='".$vesName."', booksts='".$descBookStsGet."', updusrdt='".$userWhoAct."' WHERE iddesc='".$idDescGet."' AND deletests=0"); 
			//$CHistory->updateLogVoucher($userIdLogin, "Simpan HAPUS DESCRIPTION Voucher (iddesc=<b>".$idDescGet."</b>, idvoucher=<b>".$idVoucherGet."</b>, delusrdt=<b>".$userWhoAct."</b>)");
		}
		//echo $allIdDescGet." / ".$idVoucherGet." / ".$batchnoGet;
	}
}

function jmlDesc($CKoneksiVoucher, $idVoucherGet)
{
	$query = $CKoneksiVoucher->mysqlQuery("SELECT iddesc, idvoucher, batchno FROM tbldesc WHERE idvoucher='".$idVoucherGet."' AND deletests=0;", $CKoneksiVoucher->bukaKoneksi());
	$jmlRow = $CKoneksiVoucher->mysqlNRows($query);
	return $jmlRow;
}

//
//708 JIKA LEBIH DARI 2

$width = "725";
if(jmlDesc($CKoneksiVoucher, $idVoucherGet) >= 3)
{	$width = "708";	}

$i = 0;
$tabel = "";
?>
<table id="judul" cellpadding="0" cellspacing="0" width="<?php echo $width; ?>" border="0" style="font:0.7em sans-serif;color:#485a88;">
<?php
$jmlRow = jmlDesc($CKoneksiVoucher, $idVoucherGet);
$disabled = "disabled";
$trMouseOver = "";
$classBtnHapusDesc = "btnStandarDis";
if($aksiGet == "editDesc")
{
	$disabled = "";
	$trMouseOver = "onMouseOver=\"this.style.backgroundColor='#D9EDFF';\" onmouseout=\"this.style.backgroundColor='#F0F1FF';\"";
	$classBtnHapusDesc = "btnStandar";
}

//echo $idVoucherGet; 
$query = $CKoneksiVoucher->mysqlQuery("SELECT iddesc, idvoucher, batchno, urutan, keterangan, amount, perkiraan, subacc, unit, vescode, booksts FROM tbldesc WHERE idvoucher='".$idVoucherGet."' AND deletests=0 ORDER BY urutan;", $CKoneksiVoucher->bukaKoneksi());
while($row = $CKoneksiVoucher->mysqlFetch($query))
{
	$i++;
	$rowColor = $CPublic->rowColorCustom($a, "#FFFFFF", "#F0F1FF");
	$idDesc = $row['iddesc'];
	$allIdDesc .= $idDesc.",";
	
	$dbSelect = "";
	$crSelect = "";
	if($row['booksts'] == "db")
	{	$dbSelect = "selected";	}
	if($row['booksts'] == "cr")
	{	$crSelect = "selected";	}
	
	$buttonNaik = "<button class=\"".$classBtnHapusDesc."\" onClick=\"ubahUrutan('naik','".$idDesc."');\" id=\"\" title=\"SEQUENCE UP\" style=\"position:absolute;top:0px;width:35px;height:12px;\" ".$disabled."><img src=\"../picture/control-090.png\" width=\"15\" style=\"margin-top:-2px;\"/></button>";
	
	$buttonTurun = "<button class=\"".$classBtnHapusDesc."\" onClick=\"ubahUrutan('turun','".$idDesc."');\" id=\"\" title=\"SEQUENCE DOWN\" style=\"position:absolute;top:58px;width:35px;height:12px;\" ".$disabled."><img src=\"../picture/control-270.png\" width=\"15\" style=\"margin-top:-2px;\"/></button>";
	
	if($i == 1)
		$buttonNaik = "";
	if($i == $CVoucher->jmlRowDesc($idVoucherGet))
		$buttonTurun = "";
	
	$tabel.= "
	<tr id=\"".$i."\" bgcolor=\"#F0F1FF\" ".$trMouseOver.">
		<td>
			<table cellpadding=\"0\" cellspacing=\"0\">
			<tr align=\"center\" valign=\"bottom\">
				<td class=\"\" height=\"30\" align=\"left\" colspan=\"9\" style=\"position: relative;\">
				".$buttonNaik.$buttonTurun."
					
				&nbsp;<b>DESCRIPTION</b> <span style=\"font-size:18px;\">".$i."</span>&nbsp;&nbsp;<input type=\"text\" value=\"".$row['keterangan']."\" id=\"desc".$idDesc."\" name=\"desc".$idDesc."\" class=\"elementInput\" style=\"width:250px;text-transform:uppercase;\" maxlength=\"110\" ".$disabled.">&nbsp;&nbsp;
					BOOK STS&nbsp;
					<select id=\"descBookSts".$idDesc."\" name=\"descBookSts".$idDesc."\" class=\"elementMenu\" style=\"width:40px;height:22px;vertical-align:top;\" ".$disabled.">
						<option value=\"db\" ".$dbSelect.">DB</option>
						<option value=\"cr\" ".$crSelect.">CR</option>
					</select>&nbsp;
					VESSEL&nbsp;
					<select id=\"vesCode".$idDesc."\" name=\"vesCode".$idDesc."\" class=\"elementMenu\" style=\"width:160px;height:22px;vertical-align:top;\" ".$disabled.">
					<option value=\"\">&nbsp;</option>
					".$CVoucher->menuVessel($row['vescode'])."
					</select>
					<div style=\"position:absolute; border: solid 0px #CCC;top:5px;right:5px;width:25px;\">
                    <button class=\"".$classBtnHapusDesc."\" id=\"btnHapusDesc".$idDesc."\" title=\"ADD NEW DESCRIPTION\" onclick=\"hapusDesc('".$row['iddesc']."', '".$row['idvoucher']."');return false;\" ".$disabled.">
                          <table width=\"25\" height=\"23\">
                          <tr>
                              <td align=\"center\"><img src=\"../picture/cross.png\"/></td>
                          </tr>
                          </table>
                      </button>
                </div>
				</td>
			</tr>
			<tr valign=\"bottom\">
				<td width=\"92\" height=\"35\" style=\"text-align:right;\">AMOUNT&nbsp;&nbsp;&nbsp;</td>
				<td width=\"130\">
					<!--<select id=\"descCurr".$idDesc."\" name=\"descCurr".$idDesc."\" class=\"elementMenu\" style=\"width:50px;height:22px;vertical-align:top;\" ".$disabled.">
					".$CVoucher->menuCurrencyCode($row['subacc'])."
					</select>-->
					<input type=\"text\" id=\"descAmount".$idDesc."\" name=\"descAmount".$idDesc."\" value=\"".number_format($row['amount'], 2, ".", ",")."\" class=\"elementInput\" style=\"width:100px;text-align:right;\" ".$disabled.">
				</td>
				<td width=\"48\">&nbsp;ACCT #</td>
				<td width=\"46\"><input type=\"text\" id=\"perkiraan".$idDesc."\" name=\"perkiraan".$idDesc."\" value=\"".$row['perkiraan']."\" class=\"elementInput\" style=\"width:30px;\" maxlength=\"5\" ".$disabled."></td>
				<td width=\"75\">&nbsp;SUB ACCT. ".$i."</td>
				<td width=\"46\"><input type=\"text\" id=\"subAcc".$idDesc."\" name=\"subAcc".$idDesc."\" value=\"".$row['subacc']."\" class=\"elementInput\" style=\"width:30px;\" maxlength=\"5\" ".$disabled."></td>
				<td width=\"52\">&nbsp;DIVISION</td>
				<td width=\"219\">
					<select id=\"descDiv".$idDesc."\" name=\"descDiv".$idDesc."\" class=\"elementMenu\" style=\"width:210px;\" ".$disabled.">
						<option value=\"\">&nbsp;</option>
						".$CVoucher->menuUnit($row['unit'])."
					</select>
				</td>
			</tr>
			<tr><td colspan=\"9\" class=\"tabelBorderBottomJust\" style=\"border-style:dotted;height:5px;\"></td></tr>";
	if($i !=  $jmlRow)
	{		
		$tabel.="<tr><td colspan=\"9\" class=\"tabelBorderBottomJust\" style=\"border-style:dotted;\" height=\"5\" bgcolor=\"#FFFFFF\"></td></tr>";
	}
	$tabel.="</table>
		</td>
	</tr>
	";
	
	
}
	echo $tabel;
?>
<input type="hidden" id="jmlDesc" name="jmlDesc" value="<?php echo $jmlRow; ?>"/>
<input type="hidden" id="allIdDesc" name="allIdDesc" value="<?php echo $allIdDesc; ?>"/>
</table>
</body>
</html>

<script type="text/javascript">
//alert('<?php echo $aksiGet; ?>');
<?php
// PERULANGAN UNTUK KETIKA KETIK TANDA " ' DI FIELD DESCRIPTION MAKA TIDAK BISA
// ############################################################################
$expAllIdDesc = explode(",",$allIdDesc);
$jmlExpAllIdDesc = count($expAllIdDesc);
$htmlDisPetik = "";
$htmlAmountMask = "";
$htmlAcct = "";
$htmlSubAcc = "";
for($a=0;$a<=($jmlExpAllIdDesc-2);$a++) 
{
	$idDesc = $expAllIdDesc[$a];
	$htmlDisPetik .= "
	$('#desc".$idDesc."').keydown(function( eDesc".$idDesc." )
	{
		if(eDesc".$idDesc.".keyCode == 222 || eDesc".$idDesc.".keyCode == 192)
		{	return false;}
	});
	";
	
	$htmlAmountMask .= "
	var descAmount".$idDesc."Maskk = new NumberParser(2, '.', ',', true);
	var descAmount".$idDesc."Mask = new NumberMask(descAmount".$idDesc."Maskk, 'descAmount".$idDesc."', 12);
	";
	
	$htmlAcct .= "
	new NumberMask(new NumberParser(0, '', '', true), 'perkiraan".$idDesc."', 5);
	";
	
	$htmlSubAcc .= "
	new NumberMask(new NumberParser(0, '', '', true), 'subAcc".$idDesc."', 5);
	";
}
echo $htmlDisPetik.$htmlAmountMask.$htmlAcct.$htmlSubAcc;
// ############################################################################

// ISI HTML YANG BERISI JUMLAH DESCRIPTION DI PARENT
// ############################################################################
$trfAcct = $CVoucher->detilVoucher($idVoucherGet, "trfacct");
if($trfAcct == "Y") // JIKA SUDAH DILAKUKAN TRANSFER TO ACCT
{
?>
	parent.disabledBtn('btnSave');
	parent.disabledBtn('btnCheckBalc');
	parent.disabledBtn('btnAddDesc');
	parent.disabledBtn('btnEditDesc');
	$('#divStatusTrf', parent.document).css('visibility', 'visible'); //  HIDDEN UNTUK TEKS SUKSES TRANSFER TO ACCOUNTING
<?php
}
if($trfAcct == "N")// JIKA BELUM DILAKUKAN TRANSFER TO ACCT
{
	if($jmlRow != "0")
	{
		echo "parent.enabledBtn('btnCheckBalc');";
	}
	
	if($jmlRow == "0")
	{
		echo "parent.disabledBtn('btnCheckBalc');";
	}

?>
	parent.enabledBtn('btnAddDesc');
	parent.enabledBtn('btnEditDesc');
	parent.enabledBtn('btnLargeView');
<?php
}

if($jmlRow != "0")
{
	echo "$('#spanJmlDesc', parent.document).html('".$jmlRow." Items');";
}
// ############################################################################

if($aksiGet == "saveDesc")
{
	echo "parent.pilihBtnCancelSaveDesc();";
}
if($aksiGet == "editDesc")
{
?>
	parent.disabledBtn('btnCheckBalc');
	parent.disabledBtn('btnAddDesc');
	parent.disabledBtn('btnLargeView');
<?php
}
if($aksiGet == "hapusDesc")
{
	echo "parent.pilihBtnEditDesc();";
}
?>

$('#divStatusBlc', parent.document).css("visibility", "hidden"); //  HIDDEN UNTUK TEKS BALANCE
$('#idSpanBalance', parent.document).html('&nbsp;'); // KOSONGKAN TEKS BALANCE
parent.disabledBtn('btnPayTransAcct');
</script>