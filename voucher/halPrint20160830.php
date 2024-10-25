<?php
require_once("configVoucher.php");
require_once($pathInvReg.'/class/CNumberWordsInd.php');

if($aksiGet == "printVoucher")
{
	$tpl = new myTemplate($tplDir."laporanPrintVoucher.html");
}

$tpl->prepare();

if($aksiGet == "printVoucher")
{
	$idVoucherGet = $_GET['idVoucher'];
	$batchnoGet = $_GET['batchno'];
	$datePaidGet = $_GET['datePaid'];
	
	$vocType = "Transfer";
	if($CVoucher->detilVoucher($idVoucherGet, "voctype") == "R")
	{
		$vocType = "Receive";
	}
	
	$batchNo = $CVoucher->detilVoucher($idVoucherGet, "batchno");	
	$datePaid = $CPublic->jikaParamSmDgNilai($CVoucher->detilVoucher($idVoucherGet, "datepaid"), "0000-00-00", $datePaidGet, $CPublic->convTglNonDB($CVoucher->detilVoucher($idVoucherGet, "datepaid")));
	$currency = $CVoucher->detilVoucher($idVoucherGet, "currency");
	//$tpl->Assign("dataList", $CLapVcr->printVcr());

	$voucher = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	if($CVoucher->detilVoucher($idVoucherGet, "voucher") != "")
	{
		$voucher = $CPublic->jikaParamSmDgNilai($CVoucher->detilVoucher($idVoucherGet, "voucher"), "0", "", $CVoucher->detilVoucher($idVoucherGet, "voucher"));
	}
	$credAccName = $CVoucher->detilVoucher($idVoucherGet, "kepada");
	
	$tpl->Assign("vocType",  $vocType);
	$tpl->Assign("compName",  $CVoucher->detilVoucher($idVoucherGet, "companyname"));
	$tpl->Assign("batchNo", $CPublic->zerofill($batchNo, 6));
	$tpl->Assign("voucher",  $voucher);
	$tpl->Assign("reference", $CPublic->jikaParamSmDgNilai($CVoucher->detilVoucher($idVoucherGet, "reference"), "0", "", $CVoucher->detilVoucher($idVoucherGet, "reference")) );
	$tpl->Assign("datePaid", $datePaid);
	
	//$credAccName = $CInvReg->detilMailInv($idMailInvGet, "kreditaccname");
	//if($CInvReg->detilMailInv($idMailInvGet, "kreditacc") == "12300") // JIKA ACCOUNT PAYABLE MAKA TAMPILKAN SENDERVENDOR NAME
					
	$tpl->Assign("credAccName", $credAccName);
	$tpl->Assign("credAcc", "");
	
	$descItem = "";
	$queryDesc = $CKoneksiVoucher->mysqlQuery("SELECT iddesc, idvoucher, batchno, keterangan, amount, perkiraan, subacc, unit, vescode, booksts FROM tbldesc WHERE idvoucher='".$idVoucherGet."' AND deletests=0 ORDER BY urutan;", $CKoneksiVoucher->bukaKoneksi());
	
	//"SELECT urutan, vesname, sendervendor1, tipesenven, sendervendor2name, tglinvoice, barcode, mailinvno, currency, addi, deduc, amount, tglexp, companyname, vescode, description, kreditacc FROM mailinvoice WHERE transno='".$transNo."' AND deletests=0 ORDER BY urutan ASC;", $CKoneksiInvReg->bukaKoneksi());
	while($rowDesc = $CKoneksiVoucher->mysqlFetch($queryDesc))
	{
		$vesCode = $rowDesc['vescode'];
		if($rowDesc['vescode'] == "XXX")
		{
			$vesCode = "&nbsp;";
		}
		//$totalCurr = $rowGroup['currency'];
		$totalAmount += $rowDesc['amount'];
		
		$descItem.= "<tr style=\"font-size:14px;\">
        	<td class=\"tabelBorderLeftJust\" height=\"20\">".$rowDesc['keterangan']."&nbsp;</td>
            <td class=\"tabelBorderLeftJust\" align=\"center\">&nbsp;".$vesCode."&nbsp;</td>
            <td class=\"tabelBorderLeftJust\">
			 <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"font-size:16px;\">
                    <tr>
                        <td align=\"left\" width=\"70%\" style=\"letter-spacing:10px;font-size:16px;\">".$rowDesc['perkiraan']."&nbsp;</td>
						<td align=\"right\">".strtoupper($rowDesc['booksts'])."&nbsp;&nbsp;</td>
                    </tr>
                </table>
			</td>
            <td class=\"tabelBorderTopBottomNull\">
                <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"font-size:16px;\">
                    <tr>
                        <td align=\"left\" width=\"20%\">&nbsp;&nbsp;".$currency."</td>
						<td align=\"right\">".number_format((float)$rowDesc['amount'], 2, '.', ',')."&nbsp;&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>";
	}
	
	$tpl->Assign("descItem", $descItem);
	
	$tpl->Assign("totalCurr", $currency);
	$tpl->Assign("totalAmount", number_format((float)$totalAmount, 2, '.', ','));
	$tpl->Assign("source", $CVoucher->detilVoucher($idVoucherGet, "banksource"));
	$tpl->Assign("bankCode", $CVoucher->detilVoucher($idVoucherGet, "bankcode"));
	$tpl->Assign("bookSts", strtoupper($CVoucher->detilVoucher($idVoucherGet, "booksts")));
	$tpl->Assign("paymentMethod", "By ".ucfirst( $CVoucher->detilVoucher($idVoucherGet, "paytype") ));
	
	$expAmount = explode(".", $totalAmount);
	$amountDepanKoma = $expAmount[0];
	$amountBlkgKoma = $expAmount[1];
	
	$amountAngka1 = str_replace(",","",$totalAmount);
	//$amountAngka1 = $amountBlkgKoma;
	//$amountAngkaPostt = str_replace(".","",$amountAngkaPost);
	
	$CNumberWordsInd = new CNumberWordsInd("", false);
	$nilai1 = $CNumberWordsInd->convert($amountAngka1);
	$nilai2 = $CNumberWordsInd->convert($amountBlkgKoma);
	
	$nilai = $nilai1;
	if($nilai2 != "nol")
	{
		$nilai = $nilai1." dan ".$nilai2;
	}
	
	
	$tpl->Assign("totalAmountWords", ucwords(strtolower( $nilai." Rupiah Saja" )));
	
	$printBy = "Printed by ".ucfirst($userName)." ".$datePaid." ".substr($CPublic->jamServer(),0,5);
	$tpl->Assign("printBy", $printBy);
}

$tpl->printToScreen();

$CKoneksi->tutupKoneksi($CKoneksi->bukaKoneksi());
?>