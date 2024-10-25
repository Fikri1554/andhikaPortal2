<!DOCTYPE HTML>
<?php 
require_once("../configVoucher.php");

$aksiGet = $_GET['aksi'];
$idVoucherGet = $_GET['idVoucher'];
$batchnoGet = $_GET['batchno'];
$idDescGet = $_GET['idDesc'];
$bookStsVoucherGet = $_GET['bookStsVoucher'];
//echo $aksiGet;
?>

<script type="text/javascript" src="../../js/jquery-1.11.2.js"></script>
<script type="text/javascript" src="../js/JavaScriptUtil.js"></script>
<script type="text/javascript" src="../js/Parsers.js"></script>
<script type="text/javascript" src="../js/InputMask.js"></script>
<script type="text/javascript" src="../js/voucher.js"></script>
<script type="text/javascript" src="../js/aksi.js"></script>
<script language="javascript">
window.onload = 
function() 
{
	parent.document.getElementById('loaderImg').style.visibility = "hidden";
	parent.doneWait();
	parent.panggilEnableLeftClick();
	
	loadScroll('halPopupDescList');
	//parent.disabledBtn('btnAckDetail');
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
	$(":input").focus(function(e)
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
			/*$('#'+idTrSeb+' table').css("border-color","CCC");
			$('#'+idTrSeb+' table').css("border-width","1px");*/
			
			/*$('#'+idTrSeb+' #table'+idTrSeb).css("border-color","none");
			$('#'+idTrSeb+' #table'+idTrSeb).css("border-style","none");
			$('#'+idTrSeb+' #table'+idTrSeb).css("border-width","0px");*/
			
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
			
			
			//$('#'+trIdSelect).css("text-decoration","underline");
			/*$('#'+trIdSelect+' table').css("border-color","485a88");
			$('#'+trIdSelect+' table').css("border-bottom-style","solid");*/
			
			/*$('#'+trIdSelect+' #table'+trIdSelect).css("border-color","485a88");
			$('#'+trIdSelect+' #table'+trIdSelect).css("border-style","solid");
			$('#'+trIdSelect+' #table'+trIdSelect).css("border-width","2px");*/
			
			$('#'+trIdSelect).css("font-weight","bold");
        	e.stopPropagation(); 
		}

		
	}).blur(function(e)
	{
    	//$(this).parent().parent().removeClass('classSelect');
	});
	//var trIdSelect = $(this).closest('tr').find('.classSelect').id();
	//alert(trIdSelect);
	
	parent.$('#loaderImg').css('visibility','hidden');
});


function hapusDesc(idDesc, idVoucher)
{
	var answer  = confirm('Are you sure want to delete?');
	if(answer)
	{	
		//pleaseWait();
		//document.onmousedown=disableLeftClick;
		
		parent.loadIframe('iframeDescList', '');
		parent.loadIframe('iframeDescList', '../templates/halPopupDescList.php?aksi=hapusDesc&idDesc='+idDesc+'&idVoucher='+idVoucher);
	}
	else
	{	return false;	}
}
</script>

<link href="../css/table.css" rel="stylesheet" type="text/css" />
<link href="../css/button.css" rel="stylesheet" type="text/css" />

<body onUnload="saveScroll('halPopupDescList')">

<input type="hidden" id="idTrSeb" name="idTrSeb">
<input type="hidden" id="idTrSek" name="idTrSek">
<input type="hidden" id="bgColorSeb" name="bgColorSeb">
<?php
if($aksiGet == "tambahDesc" || $aksiGet == "hapusDesc" || $aksiGet == "saveDesc")
{
	if($aksiGet == "tambahDesc")
	{
		$urutan = $CVoucher->cariUrutanMaxDesc($idVoucherGet)+1;
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
			/*$descCurrGet = $_GET['descCurr'.$idDescGet];*/
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
			$CHistory->updateLogVoucher($userIdLogin, "Simpan UBAH DESCRIPTION Voucher (iddesc=<b>".$idDescGet."</b>, idvoucher=<b>".$idVoucherGet."</b>, keterangan=<b>".$keteranganGet."</b>, amount=<b>".$descAmountGet."</b>, perkiraan=<b>".$perkiraanGet."</b>, subacc=<b>".$subAccGet."</b>, unit=<b>".$descDivGet."</b>, unitname=<b>".$descDivName."</b>, vescode=<b>".$vesCodeGet."</b>, vesname=<b>".$vesName."</b>, booksts=<b>".$descBookStsGet."</b>, updusrdt=<b>".$userWhoAct."</b>)");
		}
	}
}

function jmlDesc($CKoneksiVoucher, $idVoucherGet)
{
	$query = $CKoneksiVoucher->mysqlQuery("SELECT iddesc, idvoucher, batchno FROM tbldesc WHERE idvoucher='".$idVoucherGet."' AND deletests=0;", $CKoneksiVoucher->bukaKoneksi());
	$jmlRow = $CKoneksiVoucher->mysqlNRows($query);
	return $jmlRow;
}

if($aksiGet == "saveDesc")
{
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

//
//708 JIKA LEBIH DARI 2

$width = "960";
if(jmlDesc($CKoneksiVoucher, $idVoucherGet) >= 7)
{	$width = "943";	}
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
$query = $CKoneksiVoucher->mysqlQuery("SELECT iddesc, idvoucher, batchno, keterangan, amount, perkiraan, subacc, unit, vescode, booksts FROM tbldesc WHERE idvoucher='".$idVoucherGet."' AND deletests=0 ORDER BY urutan;", $CKoneksiVoucher->bukaKoneksi());
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
	
	$amount = number_format($row['amount'], 2, ".", ",");
	if($amount == 0.00)
	{	$amount = "";	}
	
	$buttonNaik = "<button class=\"".$classBtnHapusDesc."\" onClick=\"ubahUrutanPopUp('naik','".$idDesc."');\" id=\"\" title=\"SEQUENCE UP\" style=\"position:absolute;top:0px;width:35px;height:12px;\" ".$disabled."><img src=\"../picture/control-090.png\" width=\"15\" style=\"margin-top:-2px;\"/></button>";
	
	$buttonTurun = "<button class=\"".$classBtnHapusDesc."\" onClick=\"ubahUrutanPopUp('turun','".$idDesc."');\" id=\"\" title=\"SEQUENCE DOWN\" style=\"position:absolute;top:58px;width:35px;height:12px;\" ".$disabled."><img src=\"../picture/control-270.png\" width=\"15\" style=\"margin-top:-2px;\"/></button>";
	
	if($i == 1)
		$buttonNaik = "";
	if($i == $CVoucher->jmlRowDesc($idVoucherGet))
		$buttonTurun = "";
	
	
	$tabel.= "" ?>
    <tr id="<?php echo $i; ?>" bgcolor="#F0F1FF" <?php echo $trMouseOver; ?>>
		<td>
			<table width="<?php echo $width; ?>" cellpadding="0" cellspacing="0" class="">
			<tr align="center" valign="middle">
            	<td width="433" height="35" align="left" style="position: relative;">
                	<?php echo $buttonNaik.$buttonTurun; ?>&nbsp;&nbsp;&nbsp;<b>DESCRIPTION</b> <span style="font-size:18px;"><?php echo $i; ?></span><?php echo $tambahanSpasiDesc ?>&nbsp;&nbsp;<input type="text" value="<?php echo $CPublic->konversiQuotes1($row['keterangan']); ?>" id="desc<?php echo $idDesc; ?>" name="desc<?php echo $idDesc; ?>" class="elementInput" style="width:290px;text-transform:uppercase;" maxlength="70" <?php echo $disabled; ?>>
                </td>
                <td width="159" align="right">
                	BOOK STS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select id="descBookSts<?php echo $idDesc; ?>" name="descBookSts<?php echo $idDesc; ?>" class="elementMenu" style="width:45px;height:22px;vertical-align:top;" <?php echo $disabled; ?>>
                    	<option value="XXX">-</option>
						<option value="db" <?php echo $dbSelect; ?>>DB</option>
						<option value="cr" <?php echo $crSelect; ?>>CR</option>
					</select>
                </td>
                <td align="left" style="position: relative;">
                	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;VESSEL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select id="vesCode<?php echo $idDesc; ?>" name="vesCode<?php echo $idDesc; ?>" class="elementMenu" style="width:220px;height:22px;vertical-align:top;" <?php echo $disabled; ?>>
					<option value="">&nbsp;</option>
					<?php echo $CVoucher->menuVessel($row['vescode']); ?>
					</select>
					<div style="position:absolute; border: solid 0px #CCC;top:5px;right:5px;width:25px;">
                    <button class="<?php echo $classBtnHapusDesc; ?>" id="btnHapusDesc<?php echo $idDesc; ?>" title="ADD NEW DESCRIPTION" onclick="hapusDesc('<?php echo $row['iddesc']."', '".$row['idvoucher']; ?>');return false;" <?php echo $disabled; ?>>
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
                	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;AMOUNT&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="descAmount<?php echo $idDesc; ?>" name="descAmount<?php echo $idDesc; ?>" value="<?php echo $amount; ?>" class="elementInput" style="width:110px;text-align:right;" <?php echo $disabled; ?>>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ACCT #&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="perkiraan<?php echo $idDesc; ?>" name="perkiraan<?php echo $idDesc; ?>" value="<?php echo $row['perkiraan']; ?>" class="elementInput" style="width:60px;" maxlength="5" <?php echo $disabled; ?>>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
                <td align="right">
                	SUB ACCT. <?php echo $i; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="subAcc<?php echo $idDesc; ?>" name="subAcc<?php echo $idDesc; ?>" value="<?php echo $row['subacc']; ?>" class="elementInput" style="width:62px;" maxlength="5" <?php echo $disabled; ?>>
                </td>
                <td>
                	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DIVISION&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select id="descDiv<?php echo $idDesc; ?>" name="descDiv<?php echo $idDesc; ?>" class="elementMenu" style="width:220px;" <?php echo $disabled; ?>>
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
	/*$htmlDisPetik .= "
	$('#desc".$idDesc."').keydown(function( eDesc".$idDesc." )
	{
		if(eDesc".$idDesc.".keyCode == 222)
		{	return false;}
	});
	";*/
	
	$htmlAmountMask .= "
	var descAmount".$idDesc."Maskk = new NumberParser(2, '.', ',', true);
	var descAmount".$idDesc."Mask = new NumberMask(descAmount".$idDesc."Maskk, 'descAmount".$idDesc."', 12);
	";
	
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
}
echo $htmlDisPetik.$htmlAmountMask.$htmlAcct.$htmlSubAcc;
// ############################################################################

// ISI HTML YANG BERISI JUMLAH DESCRIPTION DI PARENT
// ############################################################################
if($jmlRow != "0")
{
	echo "$('#spanJmlDesc', parent.document).html('".$jmlRow." Items');";
}
// ############################################################################

if($aksiGet == "saveDesc")
{
	$spanAmount = "( ".$CVoucher->detilVoucher($idVoucherGet, "currency")." )&nbsp;&nbsp;".number_format($CVoucher->detilVoucher($idVoucherGet, "amount"), 2, ".", ",");
	$imgWarning = "";
	$warnaAmount = "333";
	if($CVoucher->detilVoucher($idVoucherGet, "amount") < 0)
	{
		$warnaAmount = "C00";
		$imgWarning = "<div id=\"idErrorMsg\" style=\"visibility:visible;position:absolute;bottom:0px;left:-18px;\"><img src=\"../picture/exclamation-red.png\"/></div>";
	}
?>
	parent.pilihBtnCancelSaveDesc();
	
	$('#spanAmount', parent.document).css('color','<?php echo $warnaAmount; ?>');
	$('#spanAmount', parent.document).html('<b><?php echo $spanAmount.$imgWarning; ?></b>');
	$('#amount', parent.document).val('<?php echo number_format($CVoucher->detilVoucher($idVoucherGet, "amount"), 2, ".", ","); ?>');
	$('#amountHid', parent.document).val('<?php echo $CVoucher->detilVoucher($idVoucherGet, "amount"); ?>');
<?php
}
if($aksiGet == "display")
{
?>
	$('#amount', parent.document).val('<?php echo number_format($CVoucher->detilVoucher($idVoucherGet, "amount"), 2, ".", ","); ?>');
	$('#amountHid', parent.document).val('<?php echo number_format($CVoucher->detilVoucher($idVoucherGet, "amount"), 2, ".", ","); ?>');
<?php
}
if($aksiGet == "hapusDesc")
{
	echo "parent.pilihBtnEditDesc();";
}
?>
</script>