<!DOCTYPE HTML>
<?php 
require_once("../configInvReg.php");
?>
<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../../js/main.js"></script>
<script type="text/javascript" src="../js/payment.js"></script>

<!-- <link href="../css/invReg.css" rel="stylesheet" type="text/css" /> -->
<link href="../css/table.css" rel="stylesheet" type="text/css" />
<link href="../css/button.css" rel="stylesheet" type="text/css" />

<body>
<input type="hidden" id="txtIdTrHideLast" value="">
<table cellpadding="0" cellspacing="0" style="font:0.7em sans-serif;color:#333;margin-top:0px;width:1400px;">
	<tr align="center" style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:10px;">
    	<td class="tabelBorderAll" style="width:50px;" rowspan="2">#</td>
    	<td class="tabelBorderAll" style="width:60px;" rowspan="2">TRANS NO</td>
    	<td class="tabelBorderAll" style="width:100px;" rowspan="2">BILLING&nbspCOMPANY</td>
    	<td class="tabelBorderAll" colspan="3" height="20">INVOICE</td>    	
    	<td class="tabelBorderAll" style="width:100px;" rowspan="2">VESSEL</td>
        <td class="tabelBorderAll" style="width:200px;" rowspan="2">KETERANGAN</td>
        <td class="tabelBorderAll" style="width:70px;" rowspan="2">ACCOUNT</td>
        <td class="tabelBorderAll" style="width:140px;" rowspan="2">AMOUNT</td>
        <td class="tabelBorderAll" style="width:100px;" rowspan="2">BANK&nbspCODE</td>
        <td class="tabelBorderAll" style="width:200px;" rowspan="2">REMARK</td>
    </tr>
    <tr align="center" style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:10px;">
    	<td class="tabelBorderAll" style="width:70px;" height="20">DATE</td>
    	<td class="tabelBorderAll" style="width:70px;">DUE DATE</td>
    	<td class="tabelBorderAll" style="width:70px;">NUMBER</td>
    </tr>
<?php
	$i = 0;
	$tabel = "";
	$tempData = array();
	$tempDataPA = array();
	$whereNya = "";

	if($aksiGet == "ketikSearchByBatchNo")
	{
		$whereNya .= " ";
	}

	$sql = "SELECT idmailinv, mailinvno, sendervendor1, sendervendor2name, company, companyname, currency, amount, addi, deduc, transno, datepreppay, paid, date_send_paymentlist,remark_paymentlist, file_upload, bankcode, remark, tglinvoice, tglexp, vescode, vesname, kreditacc, kreditaccname
			FROM mailinvoice 
			WHERE SUBSTR(barcode, 1, 1)='A' AND deletests=0 AND paid = 'N' AND st_verify = 'N' AND transno > 0 AND paytype != '' ".$whereNya." ORDER BY 0+transno DESC,companyname ASC;";
	
	$query = $CKoneksiInvReg->mysqlQuery($sql, $CKoneksiInvReg->bukaKoneksi());

	while($row = $CKoneksiInvReg->mysqlFetch($query))
	{
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
		$tempData[$row['transno']]['remark'][] = $row['remark'];
		$tempData[$row['transno']]['bankcode'][] = $row['bankcode'];
		$tempData[$row['transno']]['tglinvoice'][] = $row['tglinvoice'];
		$tempData[$row['transno']]['tglexp'][] = $row['tglexp'];
		$tempData[$row['transno']]['vescode'][] = $row['vescode'];
		$tempData[$row['transno']]['vesname'][] = $row['vesname'];
		$tempData[$row['transno']]['kreditacc'][] = $row['kreditacc'];
		$tempData[$row['transno']]['kreditaccname'][] = $row['kreditaccname'];
	}

	foreach ($tempData as $key => $val)
	{
		$idmailinv = 0;
		$companyNya = "";
		$invNoNya = "";
		$curr = "";
		$total = 0;
		$deduc = 0;
		$remarkNya = "";
		$bankCode = "";
		$tglInv = "";
		$dueDate = "";
		$vslNya = "";
		$keterangan = "";
		$accountCode = "";

		for ($lan = 0; $lan < count($val['companyname']); $lan++)
		{
			$idmailinv = $val['idmailinv'][$lan];
			$companyNya = $val['companyname'][$lan];
			$curr = $val['currency'][$lan];
			$total = $total + $val['amount'][$lan];
			$total = $total + $val['addi'][$lan];
			$deduc = $deduc + $val['deduc'][$lan];
			$keterangan = $val['kreditaccname'][$lan];
			$accountCode = $val['kreditacc'][$lan];
			$bankCode = $val['bankcode'][$lan];

			if($invNoNya == "")
			{
				$invNoNya = "-&nbsp".$val['invNo'][$lan];
				if($val['file_upload'][$lan] != "")
				{
					$invNoNya = "-&nbsp"."<a href=\"./fileUpload/".$val['file_upload'][$lan]."\" target=\"_blank\" title=\"View File\">".$val['invNo'][$lan]."</a>";
				}
			}else{
				if($val['file_upload'][$lan] != "")
				{
					$invNoNya .= "<br>-&nbsp"."<a href=\"./fileUpload/".$val['file_upload'][$lan]."\" target=\"_blank\" title=\"View File\">".$val['invNo'][$lan]."</a>";
				}else{
					$invNoNya .= "<br>-&nbsp".$val['invNo'][$lan];
				}
			}

			if($tglInv == "")
			{
				$tglInv = "-&nbsp".$CPublic->convTglNonDB($val['tglinvoice'][$lan]);
			}else{
				$tglInv .= "<br>-&nbsp".$CPublic->convTglNonDB($val['tglinvoice'][$lan]);
			}

			if($dueDate == "")
			{
				$dueDate = "-&nbsp".$CPublic->convTglNonDB($val['tglexp'][$lan]);
			}else{
				$dueDate .= "<br>-&nbsp".$CPublic->convTglNonDB($val['tglexp'][$lan]);
			}

			if($vslNya == "")
			{
				if($val['vesname'][$lan] == "")
				{
					$vslNya = "";
				}else{
					$vslNya = "-&nbsp".$val['vesname'][$lan];
				}
			}else{
				$vslNya .= "<br>-&nbsp".$val['vesname'][$lan];
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

		$onClick = "clickTrNya('".$key."','invReg');";

		$btnVerifyView = "<button class=\"btnStandar\" id=\"btnVerify\" title=\"Verify Data\" onClick=\"parent.viewData('".$key."','invReg');\">";
        	$btnVerifyView .= "<table width=\"50\" height=\"20\">";
            	$btnVerifyView .= "<tr>";
                	$btnVerifyView .= "<td align=\"center\" width=\"20\"><img src=\"../picture/tick.png\"/></td>";
                	$btnVerifyView .= "<td align=\"left\">View</td>";
            	$btnVerifyView .= "</tr>";
            $btnVerifyView .= "</table>";
        $btnVerifyView .= "</button>";

		$total = $total - $deduc;

		$tabel .= "<tr id=\"idTr_".$key."\" align=\"center\" valign=\"bottom\" bgcolor=\"".$rowColor."\" onMouseOver=\"this.style.backgroundColor='#D9EDFF';\" onMouseOut=\"this.style.backgroundColor='".$rowColor."';\" style=\"cursor:pointer;padding-bottom:1px;\" onClick=\"".$onClick."\">";
			$tabel .= "<td height=\"22\" class=\"tabelBorderTopLeftNull\" style=\"font-size:11px;width:50px;vertical-align:top;\">";
				$tabel .= $btnVerifyView;
			$tabel .= "</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" style=\"font-size:11px;color:#096;font-weight:bold;width:80px;vertical-align:top;\">".$key."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:11px;width:200px;vertical-align:top;\">".$companyNya."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:10px;width:80px;vertical-align:top;\">".$tglInv."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:10px;width:80px;vertical-align:top;\">".$dueDate."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:10px;width:80px;vertical-align:top;\">".$invNoNya."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:10px;width:80px;vertical-align:top;\">".$vslNya."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:11px;width:200px;vertical-align:top;\">".$keterangan."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"center\" style=\"font-size:11px;width:80px;vertical-align:top;\">".$accountCode."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"right\" style=\"font-size:11px;width:140px;vertical-align:top;\"><label style=\"padding-left:5px;float: left;\">(".$curr.")</label> <label style=\"padding-right:5px;\">".number_format($total,2)."</label></td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"center\" style=\"font-size:11px;width:100px;vertical-align:top;\">".$bankCode."</td>";
			$tabel .= "<td class=\"tabelBorderBottomJust\" align=\"left\" style=\"font-size:11px;width:200px;vertical-align:top;\">".$remarkNya."</td>";
		$tabel .= "</tr>";
	}

	$sqlVoucher = "SELECT * FROM tblvoucher WHERE deletests = '0' AND amount != 0 AND trfacct = 'N' AND st_verify = 'N' ".$whereNya." ORDER BY batchno DESC;";
	$queryVoucher = $CKoneksiVoucher->mysqlQuery($sqlVoucher, $CKoneksiVoucher->bukaKoneksi());

	while($rows = $CKoneksiVoucher->mysqlFetch($queryVoucher))
	{
		$onClick = "clickTrNya('".$rows['idvoucher']."','voucher','');";

		$btnVerifyView = "<button class=\"btnStandar\" id=\"btnVerify\" title=\"Verify Data\" onClick=\"parent.viewData('".$rows['idvoucher']."','voucher');\">";
        	$btnVerifyView .= "<table width=\"50\" height=\"20\">";
            	$btnVerifyView .= "<tr>";
                	$btnVerifyView .= "<td align=\"center\" width=\"20\"><img src=\"../picture/tick.png\"/></td>";
                	$btnVerifyView .= "<td align=\"left\">View</td>";
            	$btnVerifyView .= "</tr>";
            $btnVerifyView .= "</table>";
        $btnVerifyView .= "</button>";

        $dueDate = "";
        $vslNya = "";
        $keterangan = "";
        $accountCode = "";
        $remarkNya = "";

        if($rows['due_date'] != "0000-00-00")
        {
        	$dueDate = $CPublic->convTglNonDB($rows['due_date']);
        }

        $sqlDesc = "SELECT vesname,perkiraan,keterangan FROM tbldesc WHERE deletests = '0' AND idvoucher = ".$rows['idvoucher'];
		$valDesc = $CKoneksiVoucher->mysqlQuery($sqlDesc, $CKoneksiVoucher->bukaKoneksi());

		while($rowz = $CKoneksiVoucher->mysqlFetch($valDesc))
		{
			if($rowz['vesname'] != "")
			{
				if($vslNya == "")
				{
					$vslNya = "- ".$rowz['vesname'];
				}else{
					$vslNya .= "<br>- ".$rowz['vesname'];
				}
			}

			if($rowz['perkiraan'] != "")
			{
				if($accountCode == "")
				{
					$accountCode = "".$rowz['perkiraan'];
				}else{
					$accountCode .= "<br>".$rowz['perkiraan'];
				}
			}

			if($rowz['keterangan'] != "")
			{
				if($remarkNya == "")
				{
					$remarkNya = "- ".$rowz['keterangan'];
				}else{
					$remarkNya .= "<br>- ".$rowz['keterangan'];
				}
			}
		}

		$keterangan = $rows['kepada'];
		
		$tabel .= "<tr id=\"idTr_".$rows['idvoucher']."\" align=\"center\" valign=\"bottom\" bgcolor=\"".$rowColor."\" onMouseOver=\"this.style.backgroundColor='#D9EDFF';\" onMouseOut=\"this.style.backgroundColor='".$rowColor."';\" style=\"cursor:pointer;padding-bottom:1px;\" onClick=\"".$onClick."\">";
			$tabel .= "<td height=\"22\" class=\"tabelBorderTopLeftNull\" style=\"font-size:11px;width:50px;vertical-align:top;\">";
				$tabel .= $btnVerifyView;
			$tabel .= "</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" style=\"font-size:11px;color:#096;font-weight:bold;width:80px;vertical-align:top;\">".$rows['batchno']."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:11px;width:200px;vertical-align:top;\">".$rows['companyname']."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:10px;width:80px;vertical-align:top;\"></td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:10px;width:80px;vertical-align:top;\">".$dueDate."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:10px;width:80px;vertical-align:top;\">".$rows['invno']."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:10px;width:80px;vertical-align:top;\">".$vslNya."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:11px;width:200px;vertical-align:top;\">".$keterangan."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"center\" style=\"font-size:11px;width:80px;vertical-align:top;\">".$accountCode."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"right\" style=\"font-size:11px;width:140px;vertical-align:top;\"><label style=\"padding-left:5px;float: left;\">(".$rows['currency'].")</label> <label style=\"padding-right:5px;\">".number_format($rows['amount'],2)."</label></td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"center\" style=\"font-size:11px;width:100px;vertical-align:top;\">".$rows['bankcode']."</td>";
			$tabel .= "<td class=\"tabelBorderBottomJust\" align=\"left\" style=\"font-size:11px;width:200px;vertical-align:top;\">".$remarkNya."</td>";
		$tabel .= "</tr>";
	}
	$tabel = "";
	$wherePA = " WHERE st_delete = '0' AND ((voucher_status = 'Y' AND st_payment_list = 'N' AND st_transferToAcct = 'N') OR (settlement_voucher_status = 'Y' AND settlement_st_payment_list = 'N' AND settlement_transferToAcct = 'N')) ";

	$queryPA = $CKoneksiPaymentVoucher->mysqlQuery("SELECT * FROM datapayment ".$wherePA." ORDER BY batchno DESC;", $CKoneksiPaymentVoucher->bukaKoneksi());

	while($rowPA = $CKoneksiPaymentVoucher->mysqlFetch($queryPA))
	{
		$tempDataPA[$rowPA['transno']]['companyname'][] = $rowPA['company_name'];
		$tempDataPA[$rowPA['transno']]['invNo'][] = $rowPA['mailinvno'];
		$tempDataPA[$rowPA['transno']]['currency'][] = $rowPA['currency'];
		$tempDataPA[$rowPA['transno']]['amount'][] = $rowPA['amount'];
		$tempDataPA[$rowPA['transno']]['file_upload'][] = $rowPA['file_upload'];
		$tempDataPA[$rowPA['transno']]['tglInv'][] = $rowPA['invoice_date'];
		$tempDataPA[$rowPA['transno']]['tglexp'][] = $rowPA['invoice_due_date'];
		$tempDataPA[$rowPA['transno']]['bankcode'][] = $rowPA['voucher_bank'];
		$tempDataPA[$rowPA['transno']]['remark_paymentlist'][] = $rowPA['remark'];
		$tempDataPA[$rowPA['transno']]['barcode'][] = $rowPA['barcode'];
		$tempDataPA[$rowPA['transno']]['senderVendor'][] = $rowPA['request_name'];
		$tempDataPA[$rowPA['transno']]['settlementTransno'][] = $rowPA['settlement_transno'];
		$tempDataPA[$rowPA['transno']]['settlementRemark'][] = $rowPA['settlement_remark_paymentlist'];
		$tempDataPA[$rowPA['transno']]['amountSettlement'][] = $rowPA['settlement_voucher_amountpaid'];
		$tempDataPA[$rowPA['transno']]['bukti_file'][] = $rowPA['bukti_file'];
		$tempDataPA[$rowPA['transno']]['settlement_file'][] = $rowPA['settlement_file'];
		$tempDataPA[$rowPA['transno']]['settlement_upload_file'][] = $rowPA['settlement_upload_file'];
		$tempDataPA[$rowPA['transno']]['settlement_voucher_bank'][] = $rowPA['settlement_voucher_bank'];
		$tempDataPA[$rowPA['transno']]['settlement_amount'][] = $rowPA['settlement_amount'];
		$tempDataPA[$rowPA['transno']]['vesselName'][] = $rowPA['vessel_name'];
	}

	foreach ($tempDataPA as $key => $val)
	{
		$companyNya = "";
		$invNoNya = "";
		$invDate = "";
		$dueDate = "";
		$curr = "";
		$total = 0;
		$vslNya = "";
		$senderVendor = "";
		$remarkNya = "";
		$bankCode = "";
		$transNoSettlement = 0;
		$totalSett = 0;
		$amountNya = 0;
		$fileSettlement = "";
		$amountSettNya = 0;
		$keterangan = "";
		

		for ($lan = 0; $lan < count($val['companyname']); $lan++)
		{
			$companyNya = $val['companyname'][$lan];
			$curr = $val['currency'][$lan];
			$total = $total + $val['amount'][$lan];
			$senderVendor = $val['senderVendor'][$lan];
			$bankCode = $val['bankcode'][$lan];
			$transNoSettlement = $val['settlementTransno'][$lan];
			$totalSett = $totalSett + $val['amountSettlement'][$lan];
			$amountSettNya = $amountSettNya + $val['settlement_amount'][$lan];

			if($val['settlementTransno'][$lan] > 0)
			{
				$remarkNya = "- ".$val['settlementRemark'][$lan];
				$bankCode = $val['settlement_voucher_bank'][$lan];

				if($val['settlement_file'][$lan] != "")
				{
					$fileSettlement = "<a href=\"./../../paymentAdvance/templates/fileUploadBukti/".$val['settlement_file'][$lan]."\" target=\"_blank\" title=\"File Settlement\">";
					$fileSettlement .= "<img src=\"../picture/arrow-skip-270.png\" width=\"12\" title=\"Settlement\"></a>";
				}
			}else{
				if($remarkNya == "")
				{
					$remarkNya = "- ".$val['remark_paymentlist'][$lan];
				}else{
					$remarkNya .= "<br>- ".$val['remark_paymentlist'][$lan];
				} 
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
			}else{
				$invNoFormat = "";
				if($val['invNo'][$lan] == "")
				{
					$invNoFormat = $val['barcode'][$lan];
				}else{
					$invNoFormat = $val['invNo'][$lan];
				}
				
				$invNoNya .= "<br>- ".$invNoFormat;
			}

			if($vslNya == "")
			{				
				$vslNya = "- ".$val['vesselName'][$lan];
			}else{				
				$vslNya .= "<br>- ".$val['vesselName'][$lan];
			}

			if($val['tglInv'][$lan] != "0000-00-00")
			{
				if($invDate == "")
				{
					$invDate = "- ".$CPublic->convTglNonDB($val['tglInv'][$lan]);
				}else{
					$invDate .= "<br>- ".$CPublic->convTglNonDB($val['tglInv'][$lan]);
				}
			}

			if($val['tglexp'][$lan] != "0000-00-00")
			{
				if($dueDate == "")
				{
					$dueDate = "- ".$CPublic->convTglNonDB($val['tglexp'][$lan]);
				}else{
					$dueDate .= "<br>- ".$CPublic->convTglNonDB($val['tglexp'][$lan]);
				}
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
		}else{
			$trnsNo = "PA-".$CPublic->zerofill($key);
			$amountNya = $total;
		}		

		$onClick = "clickTrNya('".$cekTransNo."','paymentAdvance','');";

		

		// if($rows['invoice_date'] != "0000-00-00")
  //       {
  //       	$invDate = $CPublic->convTglNonDB($rows['invoice_date']);
  //       }

  //       if($rows['invoice_due_date'] != "0000-00-00")
  //       {
  //       	$dueDate = $CPublic->convTglNonDB($rows['invoice_due_date']);
  //       }

		$btnVerifyView = "<button class=\"btnStandar\" id=\"btnVerify\" title=\"Verify Data\" onClick=\"parent.viewData('".$cekTransNo."','paymentAdvance');\">";
        	$btnVerifyView .= "<table width=\"50\" height=\"20\">";
            	$btnVerifyView .= "<tr>";
                	$btnVerifyView .= "<td align=\"center\" width=\"20\"><img src=\"../picture/tick.png\"/></td>";
                	$btnVerifyView .= "<td align=\"left\">View</td>";
            	$btnVerifyView .= "</tr>";
            $btnVerifyView .= "</table>";
        $btnVerifyView .= "</button>";

        $tabel .= "<tr id=\"idTr_".$cekTransNo."\" align=\"center\" valign=\"bottom\" bgcolor=\"".$rowColor."\" onMouseOver=\"this.style.backgroundColor='#D9EDFF';\" onMouseOut=\"this.style.backgroundColor='".$rowColor."';\" style=\"cursor:pointer;padding-bottom:1px;\" onClick=\"".$onClick."\">";
			$tabel .= "<td height=\"22\" class=\"tabelBorderTopLeftNull\" style=\"font-size:11px;width:50px;vertical-align:top;\">";
				$tabel .= $btnVerifyView;
			$tabel .= "</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" style=\"font-size:11px;color:#096;font-weight:bold;width:80px;vertical-align:top;\">".$trnsNo."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:11px;width:200px;vertical-align:top;\">".$companyNya."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:10px;width:80px;vertical-align:top;\">".$invDate."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:10px;width:80px;vertical-align:top;\">".$dueDate."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:10px;width:80px;vertical-align:top;\">".$invNoNya."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:10px;width:80px;vertical-align:top;\">".$vslNya."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:11px;width:200px;vertical-align:top;\">".$keterangan."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"center\" style=\"font-size:11px;width:80px;vertical-align:top;\">".$accountCode."</td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"right\" style=\"font-size:11px;width:140px;vertical-align:top;\"><label style=\"padding-left:5px;float: left;\">(".$rows['currency'].")</label> <label style=\"padding-right:5px;\">".number_format($rows['amount'],2)."</label></td>";
			$tabel .= "<td class=\"tabelBorderTopLeftNull\" align=\"center\" style=\"font-size:11px;width:100px;vertical-align:top;\">".$rows['bankcode']."</td>";
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
		// parent.panggilEnableLeftClick();
	}

	function clickTrNya(idTrNya,type)
	{
		var idTrLast = document.getElementById('txtIdTrHideLast').value;

		if(idTrLast != "")
		{
			document.getElementById('idTr_'+idTrLast).onmouseover = function onmouseover(){	this.style.backgroundColor='#D9EDFF';	}
			document.getElementById('idTr_'+idTrLast).onmouseout = function onmouseout(){	this.style.backgroundColor='#FFFFFF';	}
			document.getElementById('idTr_'+idTrLast).style.backgroundColor='';
			document.getElementById('idTr_'+idTrLast).style.cursor = 'pointer';
		}	

		document.getElementById('idTr_'+idTrNya).onmouseout = '';
		document.getElementById('idTr_'+idTrNya).onmouseover ='';
		document.getElementById('idTr_'+idTrNya).style.backgroundColor='#B0DAFF';
		document.getElementById('idTr_'+idTrNya).style.cursor = 'default';

		$("#txtIdTrHideLast").val(idTrNya);
		parent.document.getElementById('txtIdVerify').value=idTrNya;
		parent.document.getElementById('txtTypeVerify').value=type;
		// $("#txtIdVerify").val(idTrNya);
		// $("#txtTypeVerify").val(type);
		parent.enabledBtn('btnVerifyAction');
	}

</script>