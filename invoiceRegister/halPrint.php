<?php
require_once("./configInvReg.php");

//$CLogin->getSecure();
//$idDataGet = $_GET['idData'];

if($aksiGet == "printResult")
{
	$tpl = new myTemplate($tplDir."laporanPrint.html");
}
if($aksiGet == "printDetail")
{
	$tpl = new myTemplate($tplDir."laporanPrint.html");
}
if($aksiGet == "printVoucher")
{
	$tpl = new myTemplate($tplDir."laporanPrintVoucher.html");
}
if($aksiGet == "printMailInv")
{
	$tpl = new myTemplate($tplDir."laporanPrint.html");
	//$tpl = new myTemplate($tplDir."laporanPrintA4.html");
}
if($aksiGet == "printAging")
{
	$tpl = new myTemplate($tplDir."laporanPrint.html");
}
if($aksiGet == "printOutstanding")
{
	$tpl = new myTemplate($tplDir."laporanPrint.html");
}

$tpl->prepare();

if($aksiGet == "printResult")
{
	$dataList = $CLapCari->printResult($_GET);
	$tpl->Assign("dataList", $dataList);
}
if($aksiGet == "printDetail")
{
	$dataList = $CLapCari->printDetail($_GET, $CLogin);
	$tpl->Assign("dataList", $dataList);
}

if($aksiGet == "printVoucher")
{
	$idMailInvGet = $_GET['idMailInv'];
	$datePaidGet = $_GET['datePaid'];
	$transNo =  $CInvReg->detilMailInv($idMailInvGet, "transno");
		
	$datePaid = $CPublic->jikaParamSmDgNilai($CInvReg->detilMailInv($idMailInvGet, "datepaid"), "0000-00-00", $datePaidGet, $CPublic->convTglNonDB($CInvReg->detilMailInv($idMailInvGet, "datepaid")));
	
	//$tpl->Assign("dataList", $CLapVcr->printVcr());
	$compName = $CInvReg->detilMailInv($idMailInvGet, "companyname");
	$cmpPaidBy = $CInvReg->detilMailInv($idMailInvGet, "cmppaidby");
	if($cmpPaidBy != "")
	{
		$compName = trim($CInvReg->detilComp($cmpPaidBy, "compname"));
	}
	
	$tpl->Assign("compName", $compName);
	$tpl->Assign("transNo", $CPublic->zerofill($transNo, 6));
	
	$voucher = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	if($CInvReg->detilMailInv($idMailInvGet, "voucher") != "")
	{
		$voucher = $CPublic->jikaParamSmDgNilai($CInvReg->detilMailInv($idMailInvGet, "voucher"), "0", "", $CInvReg->detilMailInv($idMailInvGet, "voucher"));
	}
	$tpl->Assign("voucher",  $voucher);
	$tpl->Assign("reference", $CPublic->jikaParamSmDgNilai($CInvReg->detilMailInv($idMailInvGet, "reference"), "0", "", $CInvReg->detilMailInv($idMailInvGet, "reference")) );
	$tpl->Assign("datePaid", $datePaid);
	
	$credAccName = $CInvReg->detilMailInv($idMailInvGet, "kreditaccname");
	if($CInvReg->detilMailInv($idMailInvGet, "kreditacc") == "12300") // JIKA ACCOUNT PAYABLE MAKA TAMPILKAN SENDERVENDOR NAME
	{
		$credAccName = $CInvReg->detilMailInv($idMailInvGet, "sendervendor1");
	}
	if(str_replace(" ","",$CInvReg->detilMailInv($idMailInvGet, "paidtofrom")) != "") // JIKA ACCOUNT PAYABLE MAKA TAMPILKAN SENDERVENDOR NAME
	{
		$credAccName = $CInvReg->detilMailInv($idMailInvGet, "paidtofrom");
	}
					
	$tpl->Assign("credAccName", $credAccName);
	$tpl->Assign("credAcc", $CInvReg->detilMailInv($idMailInvGet, "kreditacc"));
	
	$grupItem = "";
	$remarkItem = "";
	$queryGroup = $CKoneksiInvReg->mysqlQuery("SELECT urutan, vesname, sendervendor1, tipesenven, sendervendor2name, tglinvoice, barcode, mailinvno, currency, addi, deduc, amount, tglexp, companyname, vescode, description, kreditacc, vslinfo,remark FROM mailinvoice WHERE transno='".$transNo."' AND deletests=0 ORDER BY urutan ASC;", $CKoneksiInvReg->bukaKoneksi());
	while($rowGroup = $CKoneksiInvReg->mysqlFetch($queryGroup))
	{
		
		if($rowGroup['vescode'] == "XXX")
			$vesCode = "&nbsp;";
		$amount = (($rowGroup['amount'] - $rowGroup['deduc']) + $rowGroup['addi']);
		$totalCurr = $rowGroup['currency'];
		$totalAmount += $amount;
		//WALAUPUN AMOUNT TOBE PAID DIISI TETAP NILAI YANG DIAMBIL ADALAH TOTAL AMOUNT
		
		$vesName = $rowGroup['vesname'];
		$vesCode = $rowGroup['vescode'];
		if(trim($rowGroup['vslinfo']) != "")
		{
			$vesName = $rowGroup['vslinfo'];
			$vesCode = "";
		}

		if($rowGroup['remark'] != "")
		{
			if($remarkItem == "")
			{
				$remarkItem = "- ".$rowGroup['remark'];
			}else{
				$remarkItem .= "<br>- ".$rowGroup['remark'];
			}			
		}
		
		$grupItem.= "<tr style=\"font-size:14px;\">
        	<td class=\"tabelBorderTopRightNull\" height=\"20\">
            	<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"font-size:14px;\">
                <tr>
                	<td width=\"17%\">".$CPublic->convTglNonDB( $rowGroup['tglinvoice'] )."</td>
                    <td width=\"17%\">".$CPublic->convTglNonDB( $rowGroup['tglexp'] )."</td>
                    <td width=\"30%\">".$rowGroup['mailinvno']."</td>
					<td width=\"36%\">".$vesName."</td>
                </tr>
                </table>
            </td>
            <td class=\"tabelBorderTopRightNull\" align=\"center\">&nbsp;".$vesCode."&nbsp;</td>
            <td class=\"tabelBorderTopRightNull\">&nbsp;</td>
            <td class=\"tabelBorderTopNull\">
                <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"font-size:16px;\">
                    <tr>
                        <td align=\"right\" width=\"25%\">".$rowGroup['currency']."</td><td align=\"right\" width=\"75%\">".number_format((float)$amount, 2, '.', ',')."&nbsp;&nbsp;&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>";
	}
	
	$tpl->Assign("grupItem", $grupItem);
	$tpl->Assign("totalCurr", $totalCurr);
	$tpl->Assign("remarkItem", $remarkItem);
	$tpl->Assign("totalAmount", number_format((float)$totalAmount, 2, '.', ','));
	$tpl->Assign("paymentMethod", "By ".ucfirst( $CInvReg->detilMailInv($idMailInvGet, "paytype") ));
	
	/*if(strtolower($totalCurr) == "usd")
		$akhir = " us dollar only";
	else if(strtolower($totalCurr) == "idr")
		$akhir = " rupiah only";
	//$tpl->Assign("totalAmountWords", strtoupper( $CNumberWordsEng->convert_number_to_words($totalCurr, $totalAmount).$akhir ));
	$tpl->Assign("totalAmountWords", ucwords(strtolower( $CNumberWordsEng->convert_number_to_words($totalCurr, $totalAmount).$akhir )));*/
	
	$expAmount = explode(".", $totalAmount);
	$amountDepanKoma = str_replace(",","",$expAmount[0]);
	$amountBlkgKoma = $expAmount[1];
	
	$CNumberWordsInd = new CNumberWordsInd("", false);
	$nilai1 = $CNumberWordsInd->convert($amountDepanKoma);
	$nilai2 = $CNumberWordsInd->convert($amountBlkgKoma);
	
	$totalAmountWords = $nilai1;
	if($nilai2 != "nol")
	{
		$totalAmountWords = $nilai1." dan ".$nilai2." Sen";
	}
	$tpl->Assign("totalAmountWords", ucwords(strtolower( $totalAmountWords)));
	
	/*if($totalCurr == "USD")
	{
		$tpl->Assign("totalAmountWords", ucwords(strtolower( $CNumberWordsEng->convert_number_to_words($totalCurr, str_replace(",","",$totalAmount))))." US Dollar Only" );
	}
	else
	{
		$teksBelkg = "";
		if($totalCurr == "IDR")
		{
			$teksBelkg = "Rupiah";
		}
		$amountAngka1 = str_replace(",","",$totalAmount);
		//$amountAngka1 = $amountBlkgKoma;
		//$amountAngkaPostt = str_replace(".","",$amountAngkaPost);
		
		$CNumberWordsInd = new CNumberWordsInd("", false);
		$nilai1 = $CNumberWordsInd->convert($amountAngka1);
		$nilai2 = $CNumberWordsInd->convert($amountBlkgKoma);
		
		$totalAmountWords = $nilai1;
		if($nilai2 != "nol")
		{
			$totalAmountWords = $nilai1." dan ".$nilai2;
		}
		$tpl->Assign("totalAmountWords", ucwords(strtolower( $totalAmountWords." ".$teksBelkg." Saja" )));
	}*/
	
	$tpl->Assign("bankCode", $CInvReg->detilMailInv($idMailInvGet, "bankcode"));
	
	$printBy = "Printed by ".ucfirst($userName)." ".$datePaid." ".substr($CPublic->jamServer(),0,5);
	$tpl->Assign("printBy", $printBy);
}

if($aksiGet == "printMailInv")
{
	$reportTypeGet = $_GET['reportType'];
	$printModelGet = $_GET['printModel'];
	if($reportTypeGet == "distList")
	{
		if($printModelGet == "model1")
		{
			$dataList = $CLapPrint->distributionListModel1($_GET);
		}
		if($printModelGet == "model2")
		{
			$dataList = $CLapPrint->distributionListModel2($_GET);
		}
	}
	if($reportTypeGet == "covNote")
	{
		$dataList = $CLapPrint->covNote($_GET);
	}
	
	$tpl->Assign("dataList", $dataList);
}

if($aksiGet == "printOutstanding")
{
	$tpl->Assign("dataList", $CLapPayment->printOutstanding($_GET));
}

if($aksiGet == "printAging")
{
	$dataList = $CLapAging->printAging($_GET);
	$tpl->Assign("dataList", $dataList);
}

$tpl->printToScreen();

$CKoneksi->tutupKoneksi($CKoneksi->bukaKoneksi());
?>