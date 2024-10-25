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

if($aksiGet == "cek")
{
	$vocJmlAmountDB = vocJmlAmount($CKoneksiVoucher, $idVoucherGet, "db");
	$vocJmlAmountCR = vocJmlAmount($CKoneksiVoucher, $idVoucherGet, "cr");
	
	$descJmlAmountDB = descJmlAmount($CKoneksiVoucher, $idVoucherGet, "db");
	$descJmlAmountCR = descJmlAmount($CKoneksiVoucher, $idVoucherGet, "cr");
	
	$jmlAmountDB = $vocJmlAmountDB+$descJmlAmountDB;
	$jmlAmountCR= $vocJmlAmountCR+$descJmlAmountCR;
	
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
		<?php //echo number_format((float)$amount, 2, '.', ','); ?>

		<?php
		if($status == "balance")
		{
		?>
			//$('#transferVcr', parent.document).attr("disabled", true);
			//$('#receiveVcr', parent.document).attr("disabled", true);
			/*$('#transferVcr', parent.document).click(function ()
			{	return false });
			$('#receiveVcr', parent.document).click(function ()
			{	return false });*/
			/*$('input[name="voucherType"]', parent.document).click(function ()
			{	return false });*/
			//$('#receiveVcr').find('*').each(function () { $(this).attr("disabled", true); });
/*			$(".readonly:radio").on("click", function(){
  return false;
});*/
			//$('#transferVcr', parent.document).css("opacity","10");
			//$('#transferVcr', parent.document).css("pointer-events","none");
			//$('#transferVcr', parent.document).attr("disabled", true);
			//$('#receiveVcr', parent.document).css("opacity","10");
			//$('#receiveVcr', parent.document).css("pointer-events","none");
			//$('#receiveVcr', parent.document).attr("disabled", true);
			
			/*$('input[name="voucherType"]', parent.document).attr("disabled", true);
			$('#transferVcrText', parent.document).attr("disabled", true);
			$('#receiveVcrText', parent.document).attr("disabled", true);
			$('#paidTo', parent.document).attr("readonly", true);
			$('#company option', parent.document).attr("disabled", true);
			$('#company option[value='+$('#companyHid', parent.document).val()+']', parent.document).attr("disabled", false);
			$('#payMethod option', parent.document).attr("disabled", true);
			$('#payMethod option[value='+$('#payTypeHid', parent.document).val()+']', parent.document).attr("disabled", false);
			$('#bankCode option', parent.document).attr("disabled", true);
			$('#bankCode option[value='+$('#bankCodeHid', parent.document).val()+']', parent.document).attr("disabled", false);
			$('#voucher', parent.document).attr("readonly", true);
			$('#reference', parent.document).attr("readonly", true);
			$('#chequeNumber', parent.document).attr("readonly", true);
			$('#invNo', parent.document).attr("readonly", true);
			$('#jobNo', parent.document).attr("readonly", true);
			$('#datePaid', parent.document).attr("readonly", true);
			$('#currency option', parent.document).attr("disabled", true);
			$('#currency option[value='+$('#currHid', parent.document).val()+']', parent.document).attr("disabled", false);
			$('#imgCalDatePaid', parent.document).attr("disabled", true);
			$('#amount', parent.document).attr("readonly", true);*/
			
			parent.allElemReadOnlyTrue();
			
			$('#company option[value='+$('#companyHid', parent.document).val()+']', parent.document).attr("disabled", false);
			$('#payMethod option[value='+$('#payTypeHid', parent.document).val()+']', parent.document).attr("disabled", false);
			$('#bankCode option[value='+$('#bankCodeHid', parent.document).val()+']', parent.document).attr("disabled", false);
			
			$('#divStatusBlc', parent.document).css("visibility", "visible");
			$('#idSpanBalance', parent.document).css("font-size", "28px");
			$('#idSpanBalance', parent.document).html('BALANCE');
			//$('#statusBalance', parent.document).val('Y');
			
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
		{
		?>
			//$('#transferVcr', parent.document).attr("disabled", true);
			//$('#receiveVcr', parent.document).attr("disabled", true);
			/*$('#transferVcr', parent.document).click(function ()
			{	return true });
			$('#receiveVcr', parent.document).click(function ()
			{	return true });*/
			/*$('input[name="voucherType"]').click(function ()
			{	return true });*/
			//$(':radio:not(:checked)').attr('disabled', false);
			
			/*$('input[name="voucherType"]', parent.document).attr("disabled", false);
			$('#transferVcrText', parent.document).attr("disabled", false);
			$('#receiveVcrText', parent.document).attr("disabled", false);
			$('#amount', parent.document).attr("readonly", false);*/
			
			$('#divStatusBlc', parent.document).css("visibility", "visible");
			$('#idSpanBalance', parent.document).css("font-size", "21px");
			$('#idSpanBalance', parent.document).html('NOT BALANCE');
			//$('#statusBalance', parent.document).val('N');
			
			parent.disabledBtn('btnPayTransAcct');
			
		<?php		
		}
		?>
	}, 500);
}

</script>
</HTML>

