<!DOCTYPE HTML>
<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../js/voucher.js"></script>
<?php 
require_once("../configVoucher.php");

$aksiGet = $_GET['aksi'];
$idVoucherGet = $_GET['idVoucher'];
$batchnoGet = $_GET['batchno'];
//echo $aksiGet." / ".$idVoucherGet." / ".$batchnoGet;

$vocJmlAmountDB = $CVoucher->vocJmlAmount($idVoucherGet, "db");
$vocJmlAmountCR = $CVoucher->vocJmlAmount($idVoucherGet, "cr");

$descJmlAmountDB = $CVoucher->descJmlAmount($idVoucherGet, "db");
$descJmlAmountCR = $CVoucher->descJmlAmount($idVoucherGet, "cr");

$jmlAmountDB = ($vocJmlAmountDB + $descJmlAmountDB);
$jmlAmountCR = ($vocJmlAmountCR + $descJmlAmountCR);

if($aksiGet == "cek")
{
	$status = "";
	if(trim($jmlAmountCR) == trim($jmlAmountDB))
	{
		$status = "balance";
	}
	if(trim($jmlAmountCR) != trim($jmlAmountDB))
	{
		$status = "notbalance";
	}
}

if($aksiGet == "cekSebPrint")
{	
	$statusSebPrint = ""; // CEK STATUS BALANCE SEBELUM PRINT
	if(trim($jmlAmountCR) == trim($jmlAmountDB))
	{
		$statusSebPrint = "balance";
	}
	if(trim($jmlAmountCR) != trim($jmlAmountDB))
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

