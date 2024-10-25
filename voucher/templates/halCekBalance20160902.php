<!DOCTYPE HTML>
<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../js/voucher.js"></script>
<?php 
require_once("../configVoucher.php");

$aksiGet = $_GET['aksi'];
$idVoucherGet = $_GET['idVoucher'];
$batchnoGet = $_GET['batchno'];
//echo $aksiGet." / ".$idVoucherGet." / ".$batchnoGet;

function descJmlAmount($CKoneksiVoucher, $idVoucher, $bookSts)
{
	$query = $CKoneksiVoucher->mysqlQuery("SELECT SUM(amount) AS jmlamount FROM tbldesc WHERE idvoucher='".$idVoucher."' AND booksts='".$bookSts."' AND deletests=0;", $CKoneksiVoucher->bukaKoneksi());
	$row = $CKoneksiVoucher->mysqlFetch($query);
	return $row['jmlamount'];
}

function vocJmlAmount($CKoneksiVoucher, $idVoucherGet, $bookSts)
{
	$query = $CKoneksiVoucher->mysqlQuery("SELECT amount AS jmlamount FROM tblvoucher WHERE idvoucher='".$idVoucherGet."' AND booksts='".$bookSts."' AND deletests=0;", $CKoneksiVoucher->bukaKoneksi());
	$row = $CKoneksiVoucher->mysqlFetch($query);
	return $row['jmlamount'];
}

$vocJmlAmountDB = vocJmlAmount($CKoneksiVoucher, $idVoucherGet, "db");
$vocJmlAmountCR = vocJmlAmount($CKoneksiVoucher, $idVoucherGet, "cr");

$descJmlAmountDB = descJmlAmount($CKoneksiVoucher, $idVoucherGet, "db");
$descJmlAmountCR = descJmlAmount($CKoneksiVoucher, $idVoucherGet, "cr");

$jmlAmountDB = $vocJmlAmountDB+$descJmlAmountDB;
$jmlAmountCR= $vocJmlAmountCR+$descJmlAmountCR;

if($aksiGet == "cek")
{
	$status = "";
	if($jmlAmountCR == $jmlAmountDB)
	{
		$status = "balance";
	}
	if($jmlAmountCR != $jmlAmountDB)
	{
		$status = "notbalance";
	}
}

if($aksiGet == "cekSebPrint")
{	
	$statusSebPrint = ""; // CEK STATUS BALANCE SEBELUM PRINT
	if($jmlAmountCR == $jmlAmountDB)
	{
		$statusSebPrint = "balance";
	}
	if($jmlAmountCR != $jmlAmountDB)
	{
		$statusSebPrint = "notbalance";
	}
}

//$amount = $CVoucher->detilVoucher($idVoucherGet, "amount");
?>

<script type="text/javascript">
window.onload = 
function() 
{
	setTimeout(function()
	{
		parent.doneWait();
		parent.panggilEnableLeftClick();
		<?php
		if($status == "balance")
		{?>			
			parent.allElemReadOnlyTrue();
			
			$('#company option[value='+$('#companyHid', parent.document).val()+']', parent.document).attr("disabled", false);
			$('#payMethod option[value='+$('#payTypeHid', parent.document).val()+']', parent.document).attr("disabled", false);
			$('#bankCode option[value='+$('#bankCodeHid', parent.document).val()+']', parent.document).attr("disabled", false);
			
			$('#divStatusBlc', parent.document).css("visibility", "visible");
			$('#idSpanBalance', parent.document).css("font-size", "28px");
			$('#idSpanBalance', parent.document).html('BALANCE');
			
			parent.disabledBtn('btnSave');
			parent.disabledBtn('btnCheckBalc');
			parent.enabledBtn('btnPayTransAcct');
			
			parent.disabledBtn('btnAddDesc');
			parent.disabledBtn('btnEditDesc');
			parent.disabledBtn('btnSaveDesc');
			parent.disabledBtn('btnCancelDesc');
			parent.disabledBtn('btnLargeView');
			
		<?php	
		}
		if($status == "notbalance")
		{?>
			$('#divStatusBlc', parent.document).css("visibility", "visible");
			$('#idSpanBalance', parent.document).css("font-size", "21px");
			$('#idSpanBalance', parent.document).html('NOT BALANCE');
			
			parent.disabledBtn('btnPayTransAcct');
			
		<?php		
		}
		if($statusSebPrint == "balance")
		{ ?>
			parent.klikBtnPrintVoucher('yes');
		<?php	
		}
		else if($statusSebPrint == "notbalance")
		{ ?>
			parent.klikBtnPrintVoucher('no');
		<?php
		}
		?>
	}, 250);
}

</script>
</HTML>

