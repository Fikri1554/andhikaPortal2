<!DOCTYPE HTML>
<?php 
require_once("../configVoucher.php");

$aksiGet = $_GET['aksi'];
$idVoucherGet = $_GET['idVoucher'];
$batchnoGet = $_GET['batchno'];
$idDescGet = $_GET['idDesc'];
$bookStsVoucherGet = $_GET['bookStsVoucher'];
//echo $idVoucherGet."<br>";
$CKoneksiVoucher->bukaKoneksi();
?>
<script type="text/javascript" src="../../js/jquery-1.11.2.js"></script>
<script type="text/javascript" src="../js/JavaScriptUtil.js"></script>
<script type="text/javascript" src="../js/Parsers.js"></script>
<script type="text/javascript" src="../js/InputMask.js"></script>
<script type="text/javascript" src="../js/voucher.js"></script>
<script type="text/javascript" src="../js/aksi.js"></script>
<script type="text/javascript" src="../js/masks.js"></script>
<script language="javascript">
window.onload = 
function() 
{
	parent.document.getElementById('loaderImg').style.visibility = "hidden";
	parent.doneWait();
	parent.panggilEnableLeftClick();
	
	loadScroll('halDescList');
}

$(document).keydown(function(event)
{
	if(event.keyCode == 13) // PRESS ENTER
	{ 
		parent.pilihBtnSaveDesc();
	}
});

$(document).ready(function()
{
	$(":input").focus(function(e) // DALAM TD ADA BEBERAPA ELEMENT JIKA FOKUS MAKA ELEMENT TSB ROW ADA YANG BERUBAH
	{
    	//$(this).parent().parent().addClass('classSelect');
		//$(this).parent().parent().style.backgroundColor='#D9EDFF';
		var trIdSelect = $(this).parent().parent().parent().closest('tr').prop('id');
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

function getVoyage(idDesc,vslCode)
{
    var thn = parent.document.getElementById('datePaid').value;
    var voyNya = document.getElementById('slcVoyage'+idDesc).value;
    
    $.post('../halPostVoucher.php',
    { aksi:"getVoyageNo", thn:thn, vsl:vslCode, voyNya:voyNya },
        function(data) 
        {
            $("#slcVoyage"+idDesc).empty();
            $("#slcVoyage"+idDesc).append(data);
        },
        "json"
    );
}

function hapusDesc(idDesc, idVoucher)
{
	var bookStsVoucher = $('#idSpanDbCr', parent.document).html();
	var answer  = confirm('Are you sure want to delete?');
	if(answer)
	{	
		parent.pleaseWait();
		parent.document.onmousedown=disableLeftClick;
		
		parent.loadIframe('iframeDescList', '');
		parent.loadIframe('iframeDescList', 'templates/halDescList.php?aksi=hapusDesc&idDesc='+idDesc+'&idVoucher='+idVoucher+'&bookStsVoucher='+bookStsVoucher);
	}
	else
	{	return false;	}
}
</script>
<link href="../css/table.css" rel="stylesheet" type="text/css" />
<link href="../css/button.css" rel="stylesheet" type="text/css" />
<link href="css/voucher.css" rel="stylesheet" type="text/css" />

<body onUnload="saveScroll('halDescList');">

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
						'".$userWhoActNew."')");
						
		$lastInsertId = mysql_insert_id();				
		$CHistory->updateLogVoucher($userIdLogin, "Simpan TAMBAH DESCRIPTION KOSONG BARU Voucher (iddesc=<b>".$lastInsertId."</b>, idvoucher=<b>".$idVoucherGet."</b>, batchno=<b>".$batchnoGet."</b>, addusrdt=<b>".$userWhoActNew."</b>)");
	}
	if($aksiGet == "hapusDesc")
	{
		$CKoneksiVoucher->mysqlQuery("UPDATE tbldesc SET deletests=1, delusrdt='".$userWhoActNew."' WHERE iddesc='".$idDescGet."' AND deletests=0"); 
		$CHistory->updateLogVoucher($userIdLogin, "Simpan HAPUS DESCRIPTION Voucher (iddesc=<b>".$idDescGet."</b>, idvoucher=<b>".$idVoucherGet."</b>, delusrdt=<b>".$userWhoActNew."</b>)");
		
		$descJmlAmountDB = $CVoucher->descJmlAmount($idVoucherGet, "db");
		$descJmlAmountCR = $CVoucher->descJmlAmount($idVoucherGet, "cr");
		if($bookStsVoucherGet == "CR") // JIKA VOUCHER TYPE ADALAH TRANSFER
		{
			$amountVoc = ($descJmlAmountDB - $descJmlAmountCR);
			$rumus = "DEBET - CREDIT";
		}
		if($bookStsVoucherGet == "DB") // JIKA VOUCHER TYPE ADALAH RECEIVE
		{
			$amountVoc = ($descJmlAmountCR - $descJmlAmountDB);
			$rumus = "CREDIT - DEBET";
		}
		
		$CKoneksiVoucher->mysqlQuery("UPDATE tblvoucher SET amount='".$amountVoc."' WHERE idvoucher='".$idVoucherGet."' AND deletests=0"); 
		$CHistory->updateLogVoucher($userIdLogin, "Simpan UBAH Amount Voucher (idvoucher=<b>".$idVoucherGet."</b>, amount=<b>".$amountVoc."</b>, bookStsVoucherGet=<b>".$bookStsVoucherGet."</b>, rumus=<b>".$rumus."</b>)");
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
			//$descBookStsGet = $_GET['descBookSts'.$idDescGet];
			$voyageNo = $_GET['voyageNo'.$idDescGet];
			//echo $expAllIdDesc[$a]."<br>";
			//echo $keteranganGet." | ".$descBookStsGet." | ".$descCurrGet." | ".$descAmountGet." | ".$perkiraanGet." | ".$subAccGet." | ".$descDivGet."<br>";
			$CKoneksiVoucher->mysqlQuery("UPDATE tbldesc SET keterangan='".$keteranganGet."', amount='".$descAmountGet."', perkiraan='".$perkiraanGet."', subacc='".$subAccGet."', unit='".$descDivGet."', unitname='".$descDivName."', vescode='".$vesCodeGet."', vesname='".$vesName."', booksts='".$descBookStsGet."',voyage_no='".$voyageNo."',updusrdt='".$userWhoActNew."' WHERE iddesc='".$idDescGet."' AND deletests=0");
			//$CHistory->updateLogVoucher($userIdLogin, "Simpan HAPUS DESCRIPTION Voucher (iddesc=<b>".$idDescGet."</b>, idvoucher=<b>".$idVoucherGet."</b>, delusrdt=<b>".$userWhoActNew."</b>)");
		}
		
		$descJmlAmountDB = $CVoucher->descJmlAmount($idVoucherGet, "db");
		$descJmlAmountCR = $CVoucher->descJmlAmount($idVoucherGet, "cr");
		if($bookStsVoucherGet == "CR") // JIKA VOUCHER TYPE ADALAH TRANSFER
		{
			$amountVoc = ($descJmlAmountDB - $descJmlAmountCR);
			$rumus = "DEBET - CREDIT";
		}
		if($bookStsVoucherGet == "DB") // JIKA VOUCHER TYPE ADALAH RECEIVE
		{
			$amountVoc = ($descJmlAmountCR - $descJmlAmountDB);
			$rumus = "CREDIT - DEBET";
		}
		
		$CKoneksiVoucher->mysqlQuery("UPDATE tblvoucher SET amount='".$amountVoc."' WHERE idvoucher='".$idVoucherGet."' AND deletests=0"); 
		$CHistory->updateLogVoucher($userIdLogin, "Simpan UBAH Amount Voucher (idvoucher=<b>".$idVoucherGet."</b>, amount=<b>".$amountVoc."</b>, bookStsVoucherGet=<b>".$bookStsVoucherGet."</b>, rumus=<b>".$rumus."</b>)");
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

$width = "986";
if(jmlDesc($CKoneksiVoucher, $idVoucherGet) >= 3)
{	$width = "969";	}

$i = 0;
$tabel = "";
?>
<table id="judul" cellpadding="0" cellspacing="0" width="<?php echo $width; ?>" border="0" style="font:0.7em sans-serif;color:#485a88;">
<?php

$jmlRow = jmlDesc($CKoneksiVoucher, $idVoucherGet);
$disabled = "disabled";
$trMouseOver = "";
$classBtnHapusDesc = "btnStandar";
$disBtnHapusDesc = "";
if($aksiGet == "editDesc")
{
	$disabled = "";
	$trMouseOver = "onMouseOver=\"this.style.backgroundColor='#D9EDFF';\" onmouseout=\"this.style.backgroundColor='#F0F1FF';\"";
	$classBtnHapusDesc = "btnStandarDis";
	$disBtnHapusDesc = "disabled";
}

$trfAcct = $CVoucher->detilVoucher($idVoucherGet, "trfacct");
if($trfAcct == "Y") // JIKA SUDAH DILAKUKAN TRANSFER TO ACCT
{
	$classBtnHapusDesc = "btnStandarDis";
	$disBtnHapusDesc = "disabled";
}
//echo $idVoucherGet; 
$query = $CKoneksiVoucher->mysqlQuery("SELECT iddesc, idvoucher, batchno, urutan, keterangan, amount, perkiraan, subacc, unit, vescode, booksts, voyage_no FROM tbldesc WHERE idvoucher='".$idVoucherGet."' AND deletests=0 ORDER BY urutan;", $CKoneksiVoucher->bukaKoneksi());
while($row = $CKoneksiVoucher->mysqlFetch($query))
{
	$i++;
	$rowColor = $CPublic->rowColorCustom($a, "#FFFFFF", "#F0F1FF");
	$idDesc = $row['iddesc'];
	$allIdDesc .= $idDesc.",";
	
	$tambahanSpasiDesc = "&nbsp;&nbsp;&nbsp;";
	if($i >= 10)
	{
		$tambahanSpasiDesc = "";
	}
	
	$dbSelect = "";
	$crSelect = "";
	if($row['booksts'] == "db")
	{	$dbSelect = "selected";	}
	if($row['booksts'] == "cr")
	{	$crSelect = "selected";	}
	
	$thnAcct = $CVoucher->detilVoucher($idVoucherGet, "datepaid");
	$dt = explode("-", $thnAcct);

	$optVoyage = $CVoucher->getVoyageNo($row['voyage_no'],$dt[0],$row['vescode']);
	
	/*$amountSplit = explode(".", $row['amount']);
	$amount = number_format($row['amount'], 2, ".", ",");
	if($amountSplit[1] == "00") // JIKA DIBELAKANG KOMA ADALAH KOSONG MAKA NILAI DIBELAKANG KOMA TIDAK DITAMPILKAN
	{
		$amount = number_format($row['amount'], 0, ".", ",");
	}*/
	$amount = number_format($row['amount'], 2, ".", ",");
	if($amount == 0.00)
	{	$amount = "";	}
	
	$buttonNaik = "<button class=\"".$classBtnHapusDesc."\" onClick=\"ubahUrutan('naik','".$idDesc."');\" id=\"\" title=\"SEQUENCE UP\" style=\"position:absolute;top:0px;width:35px;height:12px;\" ".$disBtnHapusDesc."><img src=\"../picture/control-090.png\" width=\"15\" style=\"margin-top:-2px;\"/></button>";
	
	$buttonTurun = "<button class=\"".$classBtnHapusDesc."\" onClick=\"ubahUrutan('turun','".$idDesc."');\" id=\"\" title=\"SEQUENCE DOWN\" style=\"position:absolute;top:58px;width:35px;height:12px;\" ".$disBtnHapusDesc."><img src=\"../picture/control-270.png\" width=\"15\" style=\"margin-top:-2px;\"/></button>";
	
	if($i == 1)
		$buttonNaik = "";
	if($i == $CVoucher->jmlRowDesc($idVoucherGet))
		$buttonTurun = "";
	
	$tabel.= "" ?>
	
    <tr id="<?php echo $i; ?>" bgcolor="#F0F1FF" <?php echo $trMouseOver; ?>>
		<td>
			<table width="<?php echo $width; ?>" cellpadding="0" cellspacing="0" class="">
			<tr align="center" valign="middle">
            	<td width="400" height="35" align="left" style="position: relative;">
                	<?php echo $buttonNaik.$buttonTurun; ?>&nbsp;&nbsp;&nbsp;<b>DESCRIPTION</b> <span style="font-size:18px;"><?php echo $i; ?></span><?php echo $tambahanSpasiDesc ?>&nbsp;&nbsp;<input type="text" value="<?php echo $CPublic->konversiQuotes1($row['keterangan']); ?>" id="desc<?php echo $idDesc; ?>" name="desc<?php echo $idDesc; ?>" class="elementInput" style="width:250px;text-transform:uppercase;" maxlength="70" <?php echo $disabled; ?>>
                </td>
                <td width="140" align="left">
                	BOOK STS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                	<select id="descBookSts<?php echo $idDesc; ?>" name="descBookSts<?php echo $idDesc; ?>" class="elementMenu" style="width:45px;height:22px;vertical-align:top;" <?php echo $disabled; ?>>
                    	<option value="XXX">-</option>
						<option value="db" <?php echo $dbSelect; ?>>DB</option>
						<option value="cr" <?php echo $crSelect; ?>>CR</option>
					</select>
                </td>
                <td align="left" style="position: relative;">
                	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;VESSEL&nbsp;&nbsp;
                	<select id="vesCode<?php echo $idDesc; ?>" name="vesCode<?php echo $idDesc; ?>" class="elementMenu" style="width:150px;height:22px;vertical-align:top;" <?php echo $disabled; ?> onchange="getVoyage('<?php echo $idDesc; ?>',$(this).val());">
					<option value="">&nbsp;</option>
					<?php echo $CVoucher->menuVessel($row['vescode']); ?>
					</select>
					&nbsp;VOYAGE&nbsp;
					<select id="slcVoyage<?php echo $idDesc; ?>" name="slcVoyage<?php echo $idDesc; ?>" class="elementMenu" style="width:120px;height:22px;vertical-align:top;" <?php echo $disabled; ?>>
						<?php echo $optVoyage; ?>
					</select>
					<div style="position:absolute; border: solid 0px #CCC;top:35px;right:35px;width:25px;">
                    <button class="<?php echo $classBtnHapusDesc; ?>" id="btnHapusDesc<?php echo $idDesc; ?>" title="ADD NEW DESCRIPTION" onclick="hapusDesc('<?php echo $row['iddesc']."', '".$row['idvoucher']; ?>');return false;" <?php echo $disBtnHapusDesc; ?>>
                          <table width="25" height="23">
                          <tr>
                              <td align="center"><img src="../picture/cross.png"/></td>
                          </tr>
                          </table>
                      </button>
                	</div>
                </td>
			</tr>
			<tr valign="middle">
            	<td height="35">
                	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AMOUNT&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="descAmount<?php echo $idDesc; ?>" name="descAmount<?php echo $idDesc; ?>" value="<?php echo $amount; ?>" class="elementInput" style="width:110px;text-align:right;" onKeyUp="hanyaAngka<?php echo $idDesc; ?>();return false;" <?php echo $disabled; ?>>
                    &nbsp;&nbsp;&nbsp;&nbsp;ACCT #&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="perkiraan<?php echo $idDesc; ?>" name="perkiraan<?php echo $idDesc; ?>" value="<?php echo $row['perkiraan']; ?>" class="elementInput" style="width:60px;" maxlength="5" <?php echo $disabled; ?>>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
                <td align="left">
                	SUB ACCT. <?php echo $i; ?>&nbsp;<input type="text" id="subAcc<?php echo $idDesc; ?>" name="subAcc<?php echo $idDesc; ?>" value="<?php echo $row['subacc']; ?>" class="elementInput" style="width:50px;" maxlength="5" <?php echo $disabled; ?>>
                </td>
                <td>
                	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DIVISION&nbsp;&nbsp;<select id="descDiv<?php echo $idDesc; ?>" name="descDiv<?php echo $idDesc; ?>" class="elementMenu" style="width:220px;" <?php echo $disabled; ?>>
						<option value="">&nbsp;</option>
						<?php echo $CVoucher->menuUnit($row['unit']); ?>
					</select>
                </td>
			</tr>
            <?php
			$rowJeda = "";
			if($i !=  $jmlRow)
			{
				$rowJeda = "<tr><td colspan=\"9\" class=\"tabelBorderLeftRightNull\" style=\"border-style:dotted;\" height=\"5\" bgcolor=\"#FFFFFF\"></td></tr>";
            }
			
			echo $rowJeda;
			?>    
            
 			</table>
		</td>
	</tr>
            
<?php
	"";
}
	echo $tabel;
?>
<input type="hidden" id="jmlDesc" name="jmlDesc" value="<?php echo $jmlRow; ?>"/>
<input type="hidden" id="allIdDesc" name="allIdDesc" value="<?php echo $allIdDesc; ?>"/>
</table>
</body>
</html>

<script type="text/javascript">
//var versionIe = ieVersion();
//parent.enabledBtn('btnLargeView');
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
$htmlHanyaAngka = "";
for($a=0;$a<=($jmlExpAllIdDesc-2);$a++) 
{
	$idDesc = $expAllIdDesc[$a];
	/*$htmlDisPetik .= "
	$('#desc".$idDesc."').keydown(function( eDesc".$idDesc." )
	{
		if(eDesc".$idDesc.".keyCode == 222 || eDesc".$idDesc.".keyCode == 192)
		{	return false;}
	});
	";*/
	
	/*$htmlAmountMask .= "
	var numParser4 = new NumberParser(2, '.', ',', true);
        numParser4.currencySymbol = '';
        numParser4.useCurrency = true;
        numParser4.negativeParenthesis = true;
        numParser4.currencyInside = true;
        var numMask4 = new NumberMask(numParser4, 'descAmount".$idDesc."', 12);";*/
	
	/*$htmlAcct .= "
	new NumberMask(new NumberParser(0, '', '', true), 'perkiraan".$idDesc."', 5);
	";
	
	$htmlSubAcc .= "
	new NumberMask(new NumberParser(0, '', '', true), 'subAcc".$idDesc."', 5);
	";*/
	$htmlAcct .= "
	$('#perkiraan".$idDesc."').keypress(function (e){
	  var charCode = (e.which) ? e.which : e.keyCode;
	  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		return false;
	  }
	});
	";
	$htmlSubAcc .= "
	$('#subAcc".$idDesc."').keypress(function (e){
	  var charCode = (e.which) ? e.which : e.keyCode;
	  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		return false;
	  }
	});
	";
	
	$htmlHanyaAngka .="
	function hanyaAngka".$idDesc."()
	{
		amountMask".$idDesc." = new Mask(\"#,###.##\", \"number\");
		amountMask".$idDesc.".attach(document.getElementById('descAmount".$idDesc."'));
	}
	";
}

echo $htmlDisPetik.$htmlAmountMask.$htmlAcct.$htmlSubAcc.$htmlHanyaAngka;

// ############################################################################

// ISI HTML YANG BERISI JUMLAH DESCRIPTION DI PARENT
// ############################################################################
if($trfAcct == "Y") // JIKA SUDAH DILAKUKAN TRANSFER TO ACCT
{
?>
	parent.disabledBtn('btnSave');
	parent.disabledBtn('btnCheckBalc');
	parent.disabledBtn('btnAddDescAll');
	parent.disabledBtn('btnAddDesc');
	parent.disabledBtn('btnEditDesc');
	parent.enabledBtn('btnLargeView');	
	$('#divStatusTrf', parent.document).css('visibility', 'visible'); // HIDDEN UNTUK TEKS SUKSES TRANSFER TO ACCOUNTING
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
	parent.enabledBtn('btnAddDescAll');
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
	/*$warnaAmount = "333";
	$imgWarningHid = "hidden";
	if($CVoucher->detilVoucher($idVoucherGet, "amount") < 0)
	{
		$warnaAmount = "C00";
		$imgWarningHid = "visible";
	}
	
	$amountParent = number_format($CVoucher->detilVoucher($idVoucherGet, "amount"), 2, ".", ",");
	$amountSplit = explode(".",$CVoucher->detilVoucher($idVoucherGet, "amount"));
	if($amountSplit[1] == "00") // JIKA DIBELAKANG KOMA ADALAH KOSONG MAKA NILAI DIBELAKANG KOMA TIDAK DITAMPILKAN
	{
		$amountParent = number_format($CVoucher->detilVoucher($idVoucherGet, "amount"), 0, ".", ",");
	}
	if($amountParent == 0.00)
	{	$amountParent = "";	}*/
?>
	parent.pilihBtnCancelSaveDesc('<?php echo $idVoucherGet; ?>');
	//$('#spanAmount', parent.document).css('color','<?php echo $warnaAmount; ?>');
	//$('#spanAmount', parent.document).html('<b><?php echo $spanAmount.$imgWarning; ?></b>');
	
	/*$('#idErrorMsg2', parent.document).css('visibility','<?php echo $imgWarningHid; ?>');
	$('#amount', parent.document).css('color','<?php echo $warnaAmount; ?>');
	$('#amount', parent.document).val('<?php echo $amountParent; ?>');
	$('#amountHid', parent.document).val('<?php echo $CVoucher->detilVoucher($idVoucherGet, "amount"); ?>');*/
	
	/*var yearProcess = $('#yearProcess').val();
	var paramCari = $('#elementCariVoucher').val();
	var page = $('#menuPageBatchno').val();
	var aksiSimpanVoucher = $('#aksiSimpanVoucher').val();
	if(aksiSimpanVoucher == "simpanBaruVoucher")
	{
		page = 1;
	}
	
	$('#menuPageBatchno').val(page);
	
	parent.loadIframe('iframeVoucherList', 'templates/halVoucherList.php?aksi=closePopUp&idVoucher='+idVoucher+'&pageBatchno='+page+'&yearProcess='+yearProcess+'&paramCari='+paramCari);*/
<?php
}
if($aksiGet == "editDesc")
{
?>
	parent.disabledBtn('btnCheckBalc');
	parent.disabledBtn('btnAddDescAll');
	parent.disabledBtn('btnAddDesc');
	parent.disabledBtn('btnLargeView');
<?php
}
if($aksiGet == "hapusDesc")
{ 
?>
	parent.pilihBtnCancelSaveDesc('<?php echo $idVoucherGet; ?>');
	//echo "parent.pilihBtnEditDesc();";
<?php 
}
?>

$('#divStatusBlc', parent.document).css("visibility", "hidden"); //  HIDDEN UNTUK TEKS BALANCE
$('#idSpanBalance', parent.document).html('&nbsp;'); // KOSONGKAN TEKS BALANCE
parent.disabledBtn('btnPayTransAcct');
</script>