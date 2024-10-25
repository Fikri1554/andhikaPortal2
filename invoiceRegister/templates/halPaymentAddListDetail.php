<!DOCTYPE HTML>
<?php 
require_once("../configInvReg.php");

$aksiGet = $_GET['aksi'];

$whereCMP = "";
if ($userCompany != "") //jika ada triger companyNya
{
	if($userCompany != "ALL")
	{
		$dC = explode(",", $userCompany);
		if (count($dC) > 1)
		{
			$cmpNya = '';
			for ($lan=0; $lan < count($dC); $lan++) 
			{ 
				if ($cmpNya == "")
				{
					$cmpNya = "'".$dC[$lan]."'";
				}else{
					$cmpNya .= ",'".$dC[$lan]."'";
				}
			}
			$whereCMP = " AND company IN(".$cmpNya.")";
		}else{
			$whereCMP = " AND company = '".$dC[0]."' ";
		}
	}
}

?>
<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../../js/main.js"></script>
<script type="text/javascript" src="../js/payment.js"></script>

<link href="../css/invReg.css" rel="stylesheet" type="text/css" />
<link href="../css/table.css" rel="stylesheet" type="text/css" />
<link href="../css/button.css" rel="stylesheet" type="text/css" />

<body>
<input type="hidden" id="txtIdTrHideLast" value="">
<table cellpadding="0" cellspacing="0" style="font:0.7em sans-serif;color:#333;margin-top:0px;width:1000px;">
	<tr align="center" style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:10px;">
    	<td class="tabelBorderRightJust" style="width:70px;" height="30">BATCH NO</td>
    	<td class="tabelBorderRightJust" style="width:60px;">TRANS NO</td>
    	<td class="tabelBorderRightJust" style="width:80px;">INVOICE NO</td>
    	<td class="tabelBorderRightJust" style="width:100px;">BILLING&nbspCOMPANY</td>
        <td class="tabelBorderRightJust" style="width:200px;">SENDER / VENDOR NAME</td>        
        <td class="tabelBorderRightJust" style="width:140px;font-size:0.9em;">AMOUNT</td>
        <td class="tabelBorderRightJust" style="width:100px;font-size:0.9em;">BANK</td>
        <td class="tabelBorderRightJust" style="width:200px;font-size:0.9em;">REMARK</td>
    </tr>
<?php
	$i = 0;
	$tabel = "";
	$tempData = array();

	if($aksiGet == "ketikSearchByBatchNo")
	{
		$tempThnBln = $_GET['thnbln'];
		$tglNya = $_GET['tgl'];
		$thnNya = substr($tempThnBln, 0,4);
		$blnNya = substr($tempThnBln, 4,2);

		$whereCMP .= " AND date_send_paymentlist = '".$thnNya."-".$blnNya."-".$tglNya."' ";
	}

	$sql = "SELECT idmailinv, mailinvno, sendervendor1, sendervendor2name, company, companyname, currency, amount, addi, deduc, transno, datepreppay, paid, date_send_paymentlist,remark_paymentlist, file_upload, bankcode, remark, st_tobepaid
			FROM mailinvoice 
			WHERE SUBSTR(barcode, 1, 1)='A' AND (preparepayment = 'Y' OR st_tobepaid = 'Y') AND deletests=0 AND paid = 'N' AND st_payment_list = 'Y' ".$whereCMP." ORDER BY companyname ASC,date_send_paymentlist DESC,0+transno DESC,idmailinv DESC ;";
	
	$query = $CKoneksiInvReg->mysqlQuery($sql, $CKoneksiInvReg->bukaKoneksi());

	while($row = $CKoneksiInvReg->mysqlFetch($query))
	{
		if($row['st_tobepaid'] == 'Y' AND $row['transno'] < 0)
		{
			$row['transno'] = $row['idmailinv'];
		}

		$tempData[$row['transno']]['idmailinv'][] = $row['idmailinv'];
		$tempData[$row['transno']]['companyname'][] = $row['companyname'];

		if($row['sendervendor1'] == "")
		{
			$tempData[$row['transno']]['senderVendor'][] = $row['sendervendor2name'];
		}else{
			$tempData[$row['transno']]['senderVendor'][] = $row['sendervendor1'];
		}

		$tempData[$row['transno']]['invNo'][] = $row['mailinvno'];
		$tempData[$row['transno']]['currency'][] = $row['currency'];
		$tempData[$row['transno']]['amount'][] = $row['amount'];
		$tempData[$row['transno']]['addi'][] = $row['addi'];
		$tempData[$row['transno']]['deduc'][] = $row['deduc'];
		$tempData[$row['transno']]['file_upload'][] = $row['file_upload'];
		$tempData[$row['transno']]['date_send_paymentlist'][] = $row['date_send_paymentlist'];
		$tempData[$row['transno']]['remark_paymentlist'][] = $row['remark_paymentlist'];
		$tempData[$row['transno']]['remark'][] = $row['remark'];
		$tempData[$row['transno']]['bankcode'][] = $row['bankcode'];
		$tempData[$row['transno']]['st_tobepaid'][] = $row['st_tobepaid'];
	}
	
	foreach ($tempData as $key => $val)
	{
		$idmailinv = 0;
		$companyNya = "";
		$invNoNya = "";
		$curr = "";
		$total = 0;
		$deduc = 0;
		$dateSend = "";
		$senderVendor = "";
		$remarkNya = "";
		$bankCode = "";
		$stTobePaid = "";

		for ($lan = 0; $lan < count($val['companyname']); $lan++)
		{
			$idmailinv = $val['idmailinv'][$lan];
			$companyNya = $val['companyname'][$lan];
			$curr = $val['currency'][$lan];
			$total = $total + $val['amount'][$lan];
			$total = $total + $val['addi'][$lan];
			$deduc = $deduc + $val['deduc'][$lan];
			$dateSend = $val['date_send_paymentlist'][$lan];
			$senderVendor = $val['senderVendor'][$lan];
			//$remarkNya = $val['remark_paymentlist'][$lan];
			$bankCode = $val['bankcode'][$lan];
			$stTobePaid = $val['st_tobepaid'][$lan];

			if($invNoNya == "")
			{
				$invNoNya = "- ".$val['invNo'][$lan];
				if($val['file_upload'][$lan] != "")
				{
					$invNoNya = "- "."<a href=\"./fileUpload/".$val['file_upload'][$lan]."\" target=\"_blank\" title=\"View File\">".$val['invNo'][$lan]."</a>";
				}
			}else{
				if($val['file_upload'][$lan] != "")
				{
					$invNoNya .= "<br>- "."<a href=\"./fileUpload/".$val['file_upload'][$lan]."\" target=\"_blank\" title=\"View File\">".$val['invNo'][$lan]."</a>";
				}else{
					$invNoNya .= "<br>- ".$val['invNo'][$lan];
				}
			}

			if($remarkNya == "")
			{
				$remarkNya = "- ".$val['remark'][$lan];
			}else{
				$remarkNya .= "<br>- ".$val['remark'][$lan];
			}
		}

		$bankName = "";
		if($bankCode != "")
		{
			$bankName = $CInvReg->detilAcctCode($bankCode,"Addrs1st");

			if($bankName == "")
			{
				$bankName = $CInvReg->detilAcctCode($bankCode,"Acctname");
			}
		}

		$onClick = "clickTrNya('".$key."','invreg','');";

		if(strtolower($companyNya) == "pt. andhika lines" AND $stTobePaid == "Y")
		{
			// $key = $idmailinv;
			// $onClick = "clickTrNya('".$idmailinv."','invregByPass','".$userBtnPaymentListPaid."');";
		}

		$total = $total - $deduc;

		if($total < 0){ $total = $total *-1; }

		$tabel .= "<tr id=\"idTr_".$key."\" align=\"center\" valign=\"bottom\" bgcolor=\"".$rowColor."\" onMouseOver=\"this.style.backgroundColor='#D9EDFF';\" onMouseOut=\"this.style.backgroundColor='".$rowColor."';\" style=\"cursor:pointer;padding-bottom:1px;\" onClick=\"".$onClick."\">";
			$tabel .= "<td height=\"22\" class=\"tabelBorderTopLeftNull\" style=\"font-size:11px;width:85px;vertical-align:top;\">";
				$tabel .= str_replace("-", "", $dateSend); 
			$tabel .= "</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" style=\"font-size:11px;color:#096;font-weight:bold;width:80px;vertical-align:top;\">".$key."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:10px;width:80px;vertical-align:top;\">".$invNoNya."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:11px;width:200px;vertical-align:top;\">".$companyNya."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:11px;width:200px;vertical-align:top;\">".$senderVendor."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"right\" style=\"font-size:11px;width:140px;vertical-align:top;\"><label style=\"padding-left:5px;float: left;\">(".$curr.")</label> <label style=\"padding-right:5px;\">".number_format($total,2)."</label></td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:11px;width:100px;vertical-align:top;\">".$bankName."</td>";
			$tabel .= "<td class=\"tabelBorderBottomJust\" align=\"left\" style=\"font-size:11px;width:200px;vertical-align:top;\">".$remarkNya."</td>";
		$tabel .= "</tr>";

	}

	if($aksiGet == "ketikSearchByBatchNo")
	{
		$tempThnBln = $_GET['thnbln'];
		$tglNya = $_GET['tgl'];
		$thnNya = substr($tempThnBln, 0,4);
		$blnNya = substr($tempThnBln, 4,2);

		$whereNya = " AND date_send_paymentlist = '".$thnNya."-".$blnNya."-".$tglNya."' ";
	
		$queryVoucher = $CKoneksiVoucher->mysqlQuery("SELECT * FROM tblvoucher WHERE deletests = '0' AND trfacct = 'N' AND st_payment_list = 'Y' ".$whereNya." ORDER BY batchno DESC;", $CKoneksiVoucher->bukaKoneksi());
	}
	else
	{
		$queryVoucher = $CKoneksiVoucher->mysqlQuery("SELECT * FROM tblvoucher WHERE deletests = '0' AND trfacct = 'N' AND st_payment_list = 'Y' ORDER BY date_send_paymentlist DESC,batchno DESC;", $CKoneksiVoucher->bukaKoneksi());
	}

	while($rows = $CKoneksiVoucher->mysqlFetch($queryVoucher))
	{
		$senderVendor = $rows['kepada'];

		$invNo = $CPublic->potongKarakter($CPublic->zerofill($rows['invno']), 18);

		if($rows['barcode'] != "")
		{
			$qInvReg = $CKoneksiInvReg->mysqlQuery("SELECT file_upload FROM mailinvoice WHERE deletests = '0' AND barcode = '".$rows['barcode']."' ;", $CKoneksiInvReg->bukaKoneksi());

			while($rInv = $CKoneksiInvReg->mysqlFetch($qInvReg))
			{
				if($rInv['file_upload'] != "")
				{
					$invNo = "<a href=\"./fileUpload/".$rInv['file_upload']."\" target=\"_blank\" title=\"View File\">".$CPublic->potongKarakter($CPublic->zerofill($rows['invno']), 18)."</a>";
				}
			}
		}

		if($rows['file_upload'] != '')
		{
			$invNo = "<a href=\"./../../voucher/templates/fileUpload/".$rows['file_upload']."\" target=\"_blank\" title=\"View File\">".$CPublic->potongKarakter($CPublic->zerofill($rows['invno']), 18)."</a>";
		}

		$bankName = "";
		if($rows['bankcode'] != "")
		{
			$bankName = $CInvReg->detilAcctCode($rows['bankcode'],"Addrs1st");

			if($bankName == "")
			{
				$bankName = $CInvReg->detilAcctCode($rows['bankcode'],"Acctname");
			}
		}

		$remark = "";
		$sqlDesc = $CKoneksiVoucher->mysqlQuery("SELECT keterangan FROM tbldesc WHERE deletests = '0' AND idvoucher = ".$rows['idvoucher']." ;", $CKoneksiVoucher->bukaKoneksi());
		while($rowDes = $CKoneksiVoucher->mysqlFetch($sqlDesc))
		{
			if($remark == "")
			{
				$remark = "- ".$rowDes['keterangan'];
			}else{
				$remark .= "<br>- ".$rowDes['keterangan'];
			}
		}

		$onClick = "clickTrNya('".$rows['idvoucher']."','voucher','');";
		
		$tabel .= "<tr id=\"idTr_".$rows['idvoucher']."\" align=\"center\" valign=\"bottom\" bgcolor=\"".$rowColor."\" onMouseOver=\"this.style.backgroundColor='#D9EDFF';\" onMouseOut=\"this.style.backgroundColor='".$rowColor."';\" style=\"cursor:pointer;padding-bottom:1px;\" onClick=\"".$onClick."\">";
			$tabel .= "<td height=\"22\" class=\"tabelBorderTopLeftNull\" style=\"font-size:11px;width:85px;vertical-align:top;\">";
				$tabel .= str_replace("-", "", $rows['date_send_paymentlist']); 
			$tabel .= "</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" style=\"font-size:11px;color:#096;font-weight:bold;width:80px;vertical-align:top;\">VC-".$rows['batchno']."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:10px;width:80px;vertical-align:top;\">".$invNo."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:11px;width:200px;vertical-align:top;\">".$rows['companyname']."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:11px;width:200px;vertical-align:top;\">".$senderVendor."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"right\" style=\"font-size:11px;width:140px;vertical-align:top;\"><label style=\"padding-left:5px;float: left;\">(".$rows['currency'].")</label> <label style=\"padding-right:5px;\">".number_format($rows['amount'],2)."</label></td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:11px;width:100px;vertical-align:top;\">".$bankName."</td>";
			$tabel .= "<td class=\"tabelBorderBottomJust\" align=\"left\" style=\"font-size:11px;width:200px;\">".$remark."</td>";
		$tabel .= "</tr>";
	}

	$wherePA = " WHERE st_delete = '0' AND ((voucher_status = 'Y' AND st_payment_list = 'Y' AND st_transferToAcct = 'N') OR (settlement_voucher_status = 'Y' AND settlement_st_payment_list = 'Y' AND settlement_transferToAcct = 'N')) ";

	$tempDataPA = array();
	if($aksiGet == "ketikSearchByBatchNo")
	{
		$tempThnBln = $_GET['thnbln'];
		$tglNya = $_GET['tgl'];
		$thnNya = substr($tempThnBln, 0,4);
		$blnNya = substr($tempThnBln, 4,2);

		$wherePA .= " AND (date_send_paymentlist = '".$thnNya."-".$blnNya."-".$tglNya."' OR settlement_date_send_paymentlist = '".$thnNya."-".$blnNya."-".$tglNya."') ";
	}

	$queryPA = $CKoneksiPaymentVoucher->mysqlQuery("SELECT * FROM datapayment ".$wherePA." ORDER BY batchno DESC;", $CKoneksiPaymentVoucher->bukaKoneksi());

	while($rowPA = $CKoneksiInvReg->mysqlFetch($queryPA))
	{
		if($rowPA['settlement_transno'] > 0)
		{
			$rowPA['transno'] = $rowPA['settlement_transno'];
		}

		$tempDataPA[$rowPA['transno']]['idPayment'][] = $rowPA['id'];
		$tempDataPA[$rowPA['transno']]['companyname'][] = $rowPA['company_name'];
		$tempDataPA[$rowPA['transno']]['invNo'][] = $rowPA['mailinvno'];
		$tempDataPA[$rowPA['transno']]['currency'][] = $rowPA['currency'];
		$tempDataPA[$rowPA['transno']]['amount'][] = $rowPA['amount'];
		$tempDataPA[$rowPA['transno']]['file_upload'][] = $rowPA['file_upload'];
		$tempDataPA[$rowPA['transno']]['tglexp'][] = $rowPA['invoice_due_date'];
		$tempDataPA[$rowPA['transno']]['bankcode'][] = $rowPA['voucher_bank'];
		$tempDataPA[$rowPA['transno']]['date_send_paymentlist'][] = $rowPA['date_send_paymentlist'];
		$tempDataPA[$rowPA['transno']]['remark_paymentlist'][] = $rowPA['remark'];
		$tempDataPA[$rowPA['transno']]['barcode'][] = $rowPA['barcode'];
		$tempDataPA[$rowPA['transno']]['senderVendor'][] = $rowPA['request_name'];
		$tempDataPA[$rowPA['transno']]['settlementTransno'][] = $rowPA['settlement_transno'];
		$tempDataPA[$rowPA['transno']]['settlementRemark'][] = $rowPA['settlement_remark_paymentlist'];
		$tempDataPA[$rowPA['transno']]['settlementDateSendList'][] = $rowPA['settlement_date_send_paymentlist'];
		$tempDataPA[$rowPA['transno']]['amountSettlement'][] = $rowPA['settlement_voucher_amountpaid'];
		$tempDataPA[$rowPA['transno']]['bukti_file'][] = $rowPA['bukti_file'];
		$tempDataPA[$rowPA['transno']]['settlement_file'][] = $rowPA['settlement_file'];
		$tempDataPA[$rowPA['transno']]['settlement_upload_file'][] = $rowPA['settlement_upload_file'];
		$tempDataPA[$rowPA['transno']]['settlement_voucher_bank'][] = $rowPA['settlement_voucher_bank'];
		$tempDataPA[$rowPA['transno']]['settlement_amount'][] = $rowPA['settlement_amount'];
		$tempDataPA[$rowPA['transno']]['paidToFrom'][] = $rowPA['voucher_paidtofrom'];
		$tempDataPA[$rowPA['transno']]['paidToFromSettlement'][] = $rowPA['settlement_voucher_paidtofrom'];
	}

	foreach ($tempDataPA as $key => $val)
	{
		$companyNya = "";
		$invNoNya = "";
		$curr = "";
		$total = 0;
		$deduc = 0;
		$dateSend = "";
		$senderVendor = "";
		$remarkNya = "";
		$bankCode = "";
		$transNoSettlement = 0;
		$totalSett = 0;
		$amountNya = 0;
		$fileSettlement = "";
		$amountSettNya = 0;

		for ($lan = 0; $lan < count($val['companyname']); $lan++)
		{
			$companyNya = $val['companyname'][$lan];
			$curr = $val['currency'][$lan];
			$total = $total + $val['amount'][$lan];
			// $dateSend = $val['date_send_paymentlist'][$lan];
			//$senderVendor = $val['senderVendor'][$lan];
			$bankCode = $val['bankcode'][$lan];
			$transNoSettlement = $val['settlementTransno'][$lan];
			// $totalSett = $totalSett + $val['amountSettlement'][$lan];
			$totalSett = $val['amountSettlement'][$lan];
			$amountSettNya = $amountSettNya + $val['settlement_amount'][$lan];

			if($val['settlementTransno'][$lan] > 0)
			{
				$dateSend = $val['settlementDateSendList'][$lan];
				// $remarkNya = "- ".$val['settlementRemark'][$lan];
				$bankCode = $val['settlement_voucher_bank'][$lan];
				$senderVendor = $val['paidToFromSettlement'][$lan];

				$qRmrk = $CKoneksiPaymentVoucher->mysqlQuery("SELECT * FROM payment_split_settlement WHERE id_payment = '".$val['idPayment'][$lan]."' AND type_dbcr = 'DB' ;", $CKoneksiPaymentVoucher->bukaKoneksi());

				while($rowRmrk = $CKoneksiInvReg->mysqlFetch($qRmrk))
				{
					if($remarkNya == "")
					{
						$remarkNya = "- ".$rowRmrk['description'];
					}else{
						$remarkNya .= "<br>- ".$rowRmrk['description'];
					}
				}

				if($val['settlement_file'][$lan] != "")
				{
					$fileSettlement .= "<a href=\"./../../paymentAdvance/templates/fileUploadBukti/".$val['settlement_file'][$lan]."\" target=\"_blank\" title=\"File Settlement\">";
					$fileSettlement .= "<img src=\"../picture/arrow-skip-270.png\" width=\"12\" title=\"Settlement\"></a>";
				}
			}else{
				$qRmrk = $CKoneksiPaymentVoucher->mysqlQuery("SELECT * FROM payment_split WHERE id_payment = '".$val['idPayment'][$lan]."' AND type_dbcr = 'DB' ;", $CKoneksiPaymentVoucher->bukaKoneksi());

				while($rowRmrk = $CKoneksiInvReg->mysqlFetch($qRmrk))
				{
					if($remarkNya == "")
					{
						$remarkNya = "- ".$rowRmrk['description'];
					}else{
						$remarkNya .= "<br>- ".$rowRmrk['description'];
					}
				}

				$dateSend = $val['date_send_paymentlist'][$lan];
				$senderVendor = $val['paidToFrom'][$lan];
				// if($remarkNya == "")
				// {
				// 	$remarkNya = "- ".$val['remark_paymentlist'][$lan];
				// }else{
				// 	$remarkNya .= "<br>- ".$val['remark_paymentlist'][$lan];
				// } 
			}

			if($invNoNya == "")
			{
				$invNoFormat = "";
				if($val['invNo'][$lan] == "")
				{
					$invNoFormat = $val['barcode'][$lan];
				}else{
					$invNoFormat = $val['invNo'][$lan];
				}
				
				$invNoNya = "- ".$invNoFormat;
				
				if($val['file_upload'][$lan] != "")
				{
					$invNoNya = "- <a href=\"./../../paymentAdvance/templates/fileUpload/".$val['file_upload'][$lan]."\" target=\"_blank\" title=\"View File\">".$invNoFormat."</a>";
				}
			}else{
				$invNoFormat = "";
				if($val['invNo'][$lan] == "")
				{
					$invNoFormat = $val['barcode'][$lan];
				}else{
					$invNoFormat = $val['invNo'][$lan];
				}
				
				if($val['file_upload'][$lan] != "")
				{
					$invNoNya .= "<br>- <a href=\"./../../paymentAdvance/templates/fileUpload/".$val['file_upload'][$lan]."\" target=\"_blank\" title=\"View File\">".$CPublic->potongKarakter($CPublic->zerofill($invNoFormat), 18)."</a>";
				}else{
					$invNoNya .= "<br>- ".$invNoFormat;
				}
			}
		}

		$bankName = "";
		if($bankCode != "")
		{
			$bankName = $CInvReg->detilAcctCode($bankCode,"Addrs1st");

			if($bankName == "")
			{
				$bankName = $CInvReg->detilAcctCode($bankCode,"Acctname");
			}
		}

		$cekTransNo = $key;
		$trnsNo = 0;

		if($transNoSettlement > 0)
		{
			$trnsNo = "PA-".$CPublic->zerofill($transNoSettlement);
			$amountNya = $totalSett;
			$cekTransNo = $transNoSettlement;

			if($total > $amountSettNya)
			{
				$amountNya = $amountNya * -1;
			}
			$advType = "settlement";
		}else{
			$trnsNo = "PA-".$CPublic->zerofill($key);
			$amountNya = $total;
			$advType = "payment";
		}

		if($amountNya < 0){ $amountNya = $amountNya *-1; }

		$onClick = "clickTrNya('".$cekTransNo."','paymentAdvance','".$advType."');";

		$tabel .= "<tr id=\"idTr_".$cekTransNo."\" align=\"center\" valign=\"bottom\" bgcolor=\"".$rowColor."\" onMouseOver=\"this.style.backgroundColor='#D9EDFF';\" onMouseOut=\"this.style.backgroundColor='".$rowColor."';\" style=\"cursor:pointer;padding-bottom:1px;\" onClick=\"".$onClick."\">";
			$tabel .= "<td height=\"22\" class=\"tabelBorderTopLeftNull\" style=\"font-size:11px;width:85px;vertical-align:top;\">";
				$tabel .= str_replace("-", "", $dateSend); 
			$tabel .= "</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" style=\"font-size:11px;color:#096;font-weight:bold;width:80px;vertical-align:top;\">".$trnsNo."<br>".$fileSettlement."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:10px;width:80px;vertical-align:top;\">".$invNoNya."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:11px;width:200px;vertical-align:top;\">".$companyNya."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:11px;width:200px;vertical-align:top;\">".$senderVendor."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"right\" style=\"font-size:11px;width:140px;vertical-align:top;\"><label style=\"padding-left:5px;float: left;\">(".$curr.")</label> <label style=\"padding-right:5px;\">".number_format($amountNya,2)."</label></td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:11px;width:100px;vertical-align:top;\">".$bankName."</td>";
			$tabel .= "<td class=\"tabelBorderBottomJust\" align=\"left\" style=\"font-size:11px;width:200px;vertical-align:top;\">".$remarkNya."</td>";
		$tabel .= "</tr>";

	}

	echo $tabel;
?>
    </table>
</body>

</HTML>
<script language="javascript">
	
	window.onload = 
	function() 
	{
		parent.doneWait();
		parent.panggilEnableLeftClick();
	}

	function clickTrNya(idTrNya,type,btnPaid)
	{
		var idTr = document.getElementById('txtIdTrHideLast').value;

		if(idTr != "")
		{
			document.getElementById('idTr_'+idTr).style.backgroundColor='';
			document.getElementById('idTr_'+idTr).style.cursor = 'pointer';
		}	

		document.getElementById('idTr_'+idTrNya).onmouseout = '';
		document.getElementById('idTr_'+idTrNya).onmouseover ='';
		document.getElementById('idTr_'+idTrNya).style.backgroundColor='#B0DAFF';
		document.getElementById('idTr_'+idTrNya).style.cursor = 'default';

		parent.document.getElementById('btnPrintPaymentDelList').setAttribute('onclick',"delPaymentList("+idTrNya+",'"+type+"')")
		parent.enabledBtn('btnPrintPaymentDelList');
		
		$("#txtIdTrHideLast").val(idTrNya);

		parent.document.getElementById('btnPaymentListPaid').setAttribute('onclick',"paidPaymentList('"+idTrNya+"','"+type+"','"+btnPaid+"')");
		parent.enabledBtn('btnPaymentListPaid');
		// if(type == "invregByPass" && btnPaid == "Y")
		// {
		// 	parent.document.getElementById('btnPaymentListPaid').setAttribute('onclick',"paidPaymentList('"+idTrNya+"')")
		// 	parent.enabledBtn('btnPaymentListPaid');
		// }else{
		// 	parent.disabledBtn('btnPaymentListPaid');
		// }
		
	}
</script>