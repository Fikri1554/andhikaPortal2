<?php
	require_once("../configInvReg.php");
	$get = $_GET;

	$thnBln = $get['thnBln'];
	$tgl = $get['tgl'];

	$tempData = array();
	$trNya = "";
	$whereNya = "";
	$tempThnBln = $thnBln;
	$ttlIdr = 0;
	$ttlUsd = 0;
	$ttlOther1 = 0;
	$ttlOther2 = 0;
	$currIdr = "";
	$currUsd = "";
	$currOther1 = "";
	$currOther2 = "";
	$noTemp = 1;
	$wherePA = "";

	if($tempThnBln == "all")
	{
		$whereNya = "";
	}else{			
		$thn = substr($tempThnBln, 0,4);
		$bln = substr($tempThnBln, 4,2);

		$tglblnthn = $thn."-".$bln."-".$tgl;
		$whereNya = " AND date_send_paymentlist = '".$tglblnthn."'";

		$wherePA .= " AND (date_send_paymentlist = '".$tglblnthn."' OR settlement_date_send_paymentlist = '".$tglblnthn."') ";
	}

	$sql = " SELECT idmailinv, sendervendor1, sendervendor2name, company, companyname, currency, amount, addi, deduc, transno, date_send_paymentlist, remark_paymentlist,bankcode,remark,st_tobepaid FROM mailinvoice WHERE SUBSTR(barcode, 1, 1)='A' AND (preparepayment='Y' OR st_tobepaid = 'Y') AND deletests=0 AND paid = 'N' AND st_payment_list = 'Y' ".$whereNya." ORDER BY companyname ASC,date_send_paymentlist DESC ";
		
	$query = $CKoneksiInvReg->mysqlQuery($sql, $CKoneksiInvReg->bukaKoneksi());
	while($row = $CKoneksiInvReg->mysqlFetch($query))
	{
		$senderVendor = $row['sendervendor1'];

		if($row['sendervendor1'] == "")
		{
			$senderVendor = $row['sendervendor2name'];
		}
		$tglblnthn = str_replace("-", "", $row['date_send_paymentlist']);

		$bankName = "";
		if($row['bankcode'] != "")
		{
			$bankName = $CInvReg->detilAcctCode($row['bankcode'],"Addrs1st");

			if($bankName == "")
			{
				$bankName = $CInvReg->detilAcctCode($row['bankcode'],"Acctname");
			}

			$bankName = $CInvReg->convertBankNameToExcel($bankName);
		}

		$remark = "";
		if($row['transno'] > 0 AND $row['st_tobepaid'] == 'N')
		{
			$remark = $CInvReg->getRemarkMailInv($row['transno']);
		}

		if($row['st_tobepaid'] == 'Y')
		{
			$remark = $row['remark'];
			//$row['transno'] = $row['idmailinv'];
		}

		$tempData[strtoupper($bankName)][strtoupper($row['companyname'])][$row['transno']][$noTemp]['companyname'] = $row['companyname'];
		$tempData[strtoupper($bankName)][strtoupper($row['companyname'])][$row['transno']][$noTemp]['currency'] = $row['currency'];
		$tempData[strtoupper($bankName)][strtoupper($row['companyname'])][$row['transno']][$noTemp]['amount'] = ($row['amount'] + $row['addi']) - $row['deduc'];
		$tempData[strtoupper($bankName)][strtoupper($row['companyname'])][$row['transno']][$noTemp]['addi'] = $row['addi'];
		$tempData[strtoupper($bankName)][strtoupper($row['companyname'])][$row['transno']][$noTemp]['deduc'] = $row['deduc'];
		$tempData[strtoupper($bankName)][strtoupper($row['companyname'])][$row['transno']][$noTemp]['transno'] = $row['transno'];
		$tempData[strtoupper($bankName)][strtoupper($row['companyname'])][$row['transno']][$noTemp]['batchno'] = $tglblnthn;
		$tempData[strtoupper($bankName)][strtoupper($row['companyname'])][$row['transno']][$noTemp]['date_send_paymentlist'] = $row['date_send_paymentlist'];
		$tempData[strtoupper($bankName)][strtoupper($row['companyname'])][$row['transno']][$noTemp]['remark_paymentlist'] = $remark;
		$tempData[strtoupper($bankName)][strtoupper($row['companyname'])][$row['transno']][$noTemp]['senderVendor'] = $senderVendor;
		$tempData[strtoupper($bankName)][strtoupper($row['companyname'])][$row['transno']][$noTemp]['bankcode'] = $row['bankcode'];
		$tempData[strtoupper($bankName)][strtoupper($row['companyname'])][$row['transno']][$noTemp]['settlementTransNo'] = "0";
		$noTemp++;
	}
	// echo "<pre>";print_r($tempData);exit;
	$sqlVoucher = " SELECT * FROM tblvoucher WHERE deletests = '0' AND trfacct = 'N' AND st_payment_list = 'Y' ".$whereNya." ORDER BY batchno DESC; ";
	$queryVoucher = $CKoneksiVoucher->mysqlQuery($sqlVoucher, $CKoneksiVoucher->bukaKoneksi());

	while($rows = $CKoneksiVoucher->mysqlFetch($queryVoucher))
	{
		$tglblnthn = str_replace("-", "", $rows['date_send_paymentlist']);

		$bankName = "";
		if($rows['bankcode'] != "")
		{
			$bankName = $CInvReg->detilAcctCode($rows['bankcode'],"Addrs1st");

			if($bankName == "")
			{
				$bankName = $CInvReg->detilAcctCode($rows['bankcode'],"Acctname");
			}

			$bankName = $CInvReg->convertBankNameToExcel($bankName);
		}

		$remarkVoucher = "";
		$sqlDesc = $CKoneksiVoucher->mysqlQuery("SELECT keterangan FROM tbldesc WHERE deletests = '0' AND idvoucher = ".$rows['idvoucher']." ;", $CKoneksiVoucher->bukaKoneksi());
		while($rowDes = $CKoneksiVoucher->mysqlFetch($sqlDesc))
		{
			if($remarkVoucher == "")
			{
				$remarkVoucher = $rowDes['keterangan'];
			}else{
				$remarkVoucher .= "<br>".$rowDes['keterangan'];
			}
		}

		$tempData[strtoupper($bankName)][strtoupper($rows['companyname'])][$noTemp][$noTemp]['companyname'] = $rows['companyname'];
		$tempData[strtoupper($bankName)][strtoupper($rows['companyname'])][$noTemp][$noTemp]['currency'] = $rows['currency'];
		$tempData[strtoupper($bankName)][strtoupper($rows['companyname'])][$noTemp][$noTemp]['amount'] = $rows['amount'];
		$tempData[strtoupper($bankName)][strtoupper($rows['companyname'])][$noTemp][$noTemp]['transno'] = "-";
		$tempData[strtoupper($bankName)][strtoupper($rows['companyname'])][$noTemp][$noTemp]['batchno'] = $tglblnthn;
		$tempData[strtoupper($bankName)][strtoupper($rows['companyname'])][$noTemp][$noTemp]['date_send_paymentlist']=$rows['date_send_paymentlist'];
		$tempData[strtoupper($bankName)][strtoupper($rows['companyname'])][$noTemp][$noTemp]['remark_paymentlist'] = $remarkVoucher;
		$tempData[strtoupper($bankName)][strtoupper($rows['companyname'])][$noTemp][$noTemp]['senderVendor'] = $rows['kepada'];
		$tempData[strtoupper($bankName)][strtoupper($rows['companyname'])][$noTemp][$noTemp]['bankcode'] = $rows['bankcode'];
		$tempData[strtoupper($bankName)][strtoupper($rows['companyname'])][$noTemp][$noTemp]['settlementTransNo'] = "0";
		$noTemp++;
	}

	$sqlPA = "SELECT * FROM datapayment WHERE st_delete = '0' AND ((voucher_status = 'Y' AND st_transferToAcct = 'N' AND st_payment_list = 'Y') OR (settlement_voucher_status = 'Y' AND settlement_transferToAcct = 'N' AND settlement_st_payment_list = 'Y')) ".$wherePA." ORDER BY batchno DESC;";
	$queryPA = $CKoneksiPaymentVoucher->mysqlQuery($sqlPA, $CKoneksiPaymentVoucher->bukaKoneksi());
	while($rowPA = $CKoneksiInvReg->mysqlFetch($queryPA))
	{
		$remarkNya = "";
		//$tglblnthn = str_replace("-", "", $rowPA['date_send_paymentlist']);
		$transNoSettlement = $rowPA['settlement_transno'];
		$bankName = "";
		$trnsNo = $rowPA['transno'];
		$amountNya = $rowPA['amount'];
		//$remarkNya = $rowPA['remark_paymentlist'];
		$bankCode = "";

		if($transNoSettlement > 0)
		{
			$bankCode = $rowPA['settlement_voucher_bank'];

			$trnsNo = "PA-".$CPublic->zerofill($transNoSettlement);
			$amountNya = $rowPA['settlement_voucher_amountpaid'];
			// $remarkNya = $rowPA['settlement_remark_paymentlist'];

			$tglblnthn = str_replace("-", "", $rowPA['settlement_date_send_paymentlist']);
			if($rowPA['settlement_voucher_bank'] != "")
			{
				$bankName = $CInvReg->detilAcctCode($rowPA['settlement_voucher_bank'],"Addrs1st");

				if($bankName == "")
				{
					$bankName = $CInvReg->detilAcctCode($rowPA['settlement_voucher_bank'],"Acctname");
				}

				$bankName = $CInvReg->convertBankNameToExcel($bankName);
			}
			if($rowPA['amount'] > $rowPA['settlement_amount'])
			{
				$amountNya = $amountNya * -1;
			}
			$qRmrk = $CKoneksiPaymentVoucher->mysqlQuery("SELECT * FROM payment_split_settlement WHERE id_payment = '".$rowPA['id']."' AND type_dbcr = 'DB' ;", $CKoneksiPaymentVoucher->bukaKoneksi());

			while($rowRmrk = $CKoneksiInvReg->mysqlFetch($qRmrk))
			{
				if($remarkNya == "")
				{
					$remarkNya = $rowRmrk['description'];
				}else{
					$remarkNya .= "<br>".$rowRmrk['description'];
				}
			}
		}else{
			$bankCode = $rowPA['voucher_bank'];

			$trnsNo = "PA-".$CPublic->zerofill($trnsNo);
			$tglblnthn = str_replace("-", "", $rowPA['date_send_paymentlist']);
			if($rowPA['voucher_bank'] != "")
			{
				$bankName = $CInvReg->detilAcctCode($rowPA['voucher_bank'],"Addrs1st");

				if($bankName == "")
				{
					$bankName = $CInvReg->detilAcctCode($rowPA['voucher_bank'],"Acctname");
				}

				$bankName = $CInvReg->convertBankNameToExcel($bankName);
			}

			$qRmrk = $CKoneksiPaymentVoucher->mysqlQuery("SELECT * FROM payment_split WHERE id_payment = '".$rowPA['id']."' AND type_dbcr = 'DB' ;", $CKoneksiPaymentVoucher->bukaKoneksi());

			while($rowRmrk = $CKoneksiInvReg->mysqlFetch($qRmrk))
			{
				if($remarkNya == "")
				{
					$remarkNya = $rowRmrk['description'];
				}else{
					$remarkNya .= "<br>".$rowRmrk['description'];
				}
			}
		}

		$tempData[strtoupper($bankName)][strtoupper($rowPA['company_name'])][$trnsNo][$noTemp]['bankcode'] = $bankCode;
		$tempData[strtoupper($bankName)][strtoupper($rowPA['company_name'])][$trnsNo][$noTemp]['companyname'] = $rowPA['company_name'];
		$tempData[strtoupper($bankName)][strtoupper($rowPA['company_name'])][$trnsNo][$noTemp]['currency'] = $rowPA['currency'];		
		$tempData[strtoupper($bankName)][strtoupper($rowPA['company_name'])][$trnsNo][$noTemp]['batchno'] = $tglblnthn;//$rowPA['batchno'];
		$tempData[strtoupper($bankName)][strtoupper($rowPA['company_name'])][$trnsNo][$noTemp]['date_send_paymentlist'] = $rowPA['date_send_paymentlist'];
		$tempData[strtoupper($bankName)][strtoupper($rowPA['company_name'])][$trnsNo][$noTemp]['senderVendor'] = $rowPA['request_name'];
		$tempData[strtoupper($bankName)][strtoupper($rowPA['company_name'])][$trnsNo][$noTemp]['settlementTransNo'] = $rowPA['settlement_transno'];
		$tempData[strtoupper($bankName)][strtoupper($rowPA['company_name'])][$trnsNo][$noTemp]['transno'] = $trnsNo;
		$tempData[strtoupper($bankName)][strtoupper($rowPA['company_name'])][$trnsNo][$noTemp]['amount'] = $amountNya;
		$tempData[strtoupper($bankName)][strtoupper($rowPA['company_name'])][$trnsNo][$noTemp]['remark_paymentlist'] = $remarkNya;

		$noTemp++;
	}
	// echo "<pre>";print_r($tempData);exit;
	foreach ($tempData as $key => $val)
	{
		$countRowCmp = 1;
		$tempTr = "";
		foreach ($val as $keys => $valzx)
		{
			$noDet = 1;
			foreach ($valzx as $keyzx => $valZa)
			{				
				$remakExport = "";
				$batchNo = "";
				$transNo = "";
				$senderVendor = "";
				$currNya = "";
				$amountNya = 0;
				foreach ($valZa as $keyZa => $vals)
				{
					// $remakExport = "<b>&bull; ".$vals['senderVendor']."</b>";
					if($remakExport == "")
					{
						if($vals['remark_paymentlist'] != "")
						{
							$remakExport = "<i>- ".$vals['remark_paymentlist']."</i>";
						}
					}else{
						if($vals['remark_paymentlist'] != "")
						{
							$remakExport .= "<br><i>- ".$vals['remark_paymentlist']."</i>";
						}
					}
					
					$batchNo = $vals['batchno'];
					$transNo = $vals['transno'];
					$senderVendor = $vals['senderVendor'];
					$currNya = $vals['currency'];
					$amountNya = $amountNya + $vals['amount'];

					if($vals['settlementTransNo'] > 0)
					{
						$amountNya = $vals['amount'];
					}
				}

				if($amountNya < 0){ $amountNya = $amountNya *-1; }

				$tempTr .= "<tr>";
				if($noDet == 1)
				{
					$tempTr .= "<td rowspan=\"".count($valzx)."\">".$keys."</td>";
				}
					$tempTr .= "<td height=\"15\"  align=\"center\">".$batchNo."</td>";
					$tempTr .= "<td align=\"center\">".$transNo."</td>";
					$tempTr .= "<td>".$senderVendor."</td>";
					$tempTr .= "<td><label style=\"padding-left:3px;float: left;\">(".$currNya.")</label> <label style=\"padding-right:3px;float: right;\">".number_format($amountNya,2)."</label></td>";
					$tempTr .= "<td>".$remakExport."</td>";
				$tempTr .= "</tr>";
				$noDet++;
				$countRowCmp++;
			}			
		}

		$trNya .= "<tr>";
			$trNya .= "<td style=\"vertical-align:top;padding:10px;\" rowspan=\"".$countRowCmp."\">".$key."</td>";
			$trNya .= $tempTr;
		$trNya .= "</tr>";
	}

?>
<html>
<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script language="javascript">


function printpr()
{
	var OLECMDID =7;
	/* OLECMDID values:
	* 6 - print
	* 7 - print preview
	* 1 - open window
	* 4 - Save As
	*/
	
	var PROMPT = 1; // 2 DONTPROMPTUSER
	var WebBrowser = '<OBJECT ID="WebBrowser1" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>';
	document.body.insertAdjacentHTML('beforeEnd', WebBrowser);
	
	WebBrowser1.ExecWB(OLECMDID, PROMPT); 
	WebBrowser1.outerHTML = "";
} 


function printWindow( winObj ){
  var msg = 'Would you like to preview your print request?';
  if(confirm(msg)){
    winObj.printpreview();
  } else {
    winObj.print();    
  }
}
</script>
<!--<script type="text/javascript" src="js/main.js"></script>
<script type="text/javascript" src="../js/main.js"></script>-->

<style>
	#idFontCode{
		font-family: Code39;
		src: url('../css/font/Code39r.ttf');
	}
</style>

<link href="css/laporanInv.css" rel="stylesheet" type="text/css">
<link href="../css/laporanInv.css" rel="stylesheet" type="text/css">
<link href="css/table.css" rel="stylesheet" type="text/css">
<link href="../css/table.css" rel="stylesheet" type="text/css">

<body onLoad="printpr();" onBlur="open('', '_self').close();">
<center>

<table width="99%" cellpadding="0" cellspacing="0" style="font-size:10px;" id="tblPrint">
	<tr>
		<td height="40" align="center" colspan="5">
			<span style="font-family:Tahoma;font-size:18px;font-weight:bold;"> PAYMENT LIST </span>
		</td>
	</tr>
</table>
<table width="99%" cellpadding="0" cellspacing="0" style="font-family:Arial;font-weight:bold;font-size:10px;" id="tblPrint" border="1">	
	<thead>
	<tr>
		<td width="20%" align="center">
			BANK
		</td>
		<td width="17%" height="20" align="center">
			BILLING COMPANY
		</td>		
		<td width="10%" align="center">
			BATCH NO
		</td>
		<td width="8%" align="center">
			TRANS NO
		</td>		
		<td width="25%" align="center">
			SENDER / VENDOR NAME
		</td>
		<td width="15%" align="center">
			AMOUNT
		</td>		
		<td width="20%" align="center">
			REMARK
		</td>
	</tr>
	</thead>
	<tbody>
	<?php echo $trNya; ?>
	</tbody>
</table>
  
</center>
</body>
</html>