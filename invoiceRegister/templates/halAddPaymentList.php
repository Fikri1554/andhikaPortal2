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
$whereCMP .= " AND YEAR(tglexp) > 2017 ";
?>
<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>
<script type="text/javascript" src="../../js/main.js"></script>
<script type="text/javascript" src="../js/payment.js"></script>

<link href="../css/invReg.css" rel="stylesheet" type="text/css" />
<link href="../css/table.css" rel="stylesheet" type="text/css" />
<link href="../css/button.css" rel="stylesheet" type="text/css" />

<body>
    <table cellpadding="0" cellspacing="0" style="font:0.7em sans-serif;color:#333;width:750px;">
        <tr align="center"
            style="background-color:#8A8A8A;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:10px;">
            <td width="50" class="tabelBorderRightJust" height="30" colspan="2">#</td>
            <td width="60" class="tabelBorderRightJust">TRANS / BATCH&nbspNO</td>
            <td width="70" class="tabelBorderRightJust">DUE&nbspDATE</td>
            <td width="100" class="tabelBorderRightJust">INV. NUMBER</td>
            <td width="150" class="tabelBorderRightJust">COMPANY</td>
            <td width="150" class="tabelBorderRightJust" style="font-size:0.9em;">AMOUNT</td>
            <td width="150" class="tabelBorderRightJust">BANK</td>
        </tr>
        <?php
	$i = 0;
	$tabel = "";
	$tempData = array();

	$whereCMP .= " AND YEAR(receivedate) > 2010 ";

	if($aksiGet == "ketikSearchTemp")
	{
		$paramCariGet = $_GET['paramCari'];
		$paramCompany = $_GET['paramCompany'];

		if($paramCariGet != "")
		{
			$whereCMP .= " AND transno LIKE '%".$paramCariGet."%'";
		}

		if($paramCompany != "all")
		{
			$whereCMP .= " AND company LIKE '%".$paramCompany."%'";
		}
	}

	$sql = "SELECT idmailinv, sendervendor1, sendervendor2name, company, companyname, currency, mailinvno, amount, addi, deduc, transno, file_upload, tglexp, bankcode, st_tobepaid
			FROM mailinvoice 
			WHERE SUBSTR(barcode, 1, 1)='A' AND ((bankcode != '' AND preparepayment='Y') OR st_tobepaid = 'Y') AND deletests=0 AND paid = 'N' AND st_payment_list = 'N' ".$whereCMP." ORDER BY 0+transno DESC, idmailinv DESC ;";

	$rsl = $CKoneksiInvReg->mysqlQuery($sql, $CKoneksiInvReg->bukaKoneksi());

	while($row = $CKoneksiInvReg->mysqlFetch($rsl))
	{
		$transNo = $row['transno'];
		if($row['st_tobepaid'] == 'Y' AND $row['transno'] < 0)
		{
			$transNo = $row['idmailinv'];
		}
		$tempData[$transNo]['idmailinv'][] = $row['idmailinv'];
		$tempData[$transNo]['companyname'][] = $row['companyname'];

		if($row['sendervendor1'] == "")
		{
			$tempData[$transNo]['senderVendor'][] = $row['sendervendor2name'];
		}else{
			$tempData[$transNo]['senderVendor'][] = $row['sendervendor1'];
		}

		$tempData[$transNo]['invNo'][] = $row['mailinvno'];
		$tempData[$transNo]['currency'][] = $row['currency'];
		$tempData[$transNo]['amount'][] = $row['amount'];
		$tempData[$transNo]['addi'][] = $row['addi'];
		$tempData[$transNo]['deduc'][] = $row['deduc'];
		$tempData[$transNo]['file_upload'][] = $row['file_upload'];
		$tempData[$transNo]['tglexp'][] = $row['tglexp'];
		$tempData[$transNo]['bankcode'][] = $row['bankcode'];
		$tempData[$transNo]['st_tobepaid'][] = $row['st_tobepaid'];
	}
	
	foreach ($tempData as $key => $val)
	{
		$idmailinv = 0;
		$companyNya = "";
		$invNoNya = "";
		$curr = "";
		$total = 0;
		$deduc = 0;
		$btnNya = "";
		$dueDate = "";
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
			$dueDate = $val['tglexp'][$lan];
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
			if($val['file_upload'][$lan] == "")
			{
				$btnNya = "<button class=\"btnStandar\" id=\"btnAddFile\" title=\"ADD FILE\" onClick=\"parent.showFormUploadInvReg('".$key."','invreg');\" style=\"\"> File </button>";
			}
		}
		// echo"<pre>";print_r($key);exit;
		$bankName = "";
		if($bankCode != "")
		{
			$bankName = $CInvReg->detilAcctCode($bankCode,"Addrs1st");

			if($bankName == "")
			{
				$bankName = $CInvReg->detilAcctCode($bankCode,"Acctname");
			}
		}

		$total = $total - $deduc;

		$onclickAddDataList = "parent.addDataListPayment('".$key."','invreg');";
		$key = $CPublic->zerofill($key);

		if(strtolower($companyNya) == "pt. andhika lines" AND $stTobePaid == "Y")
		{
			//$key = "-";
			// $onclickAddDataList = "parent.addDataListPayment('".$idmailinv."','invregByPass');";
		}

		$tabel .= "<tr align=\"center\" valign=\"bottom\" bgcolor=\"".$rowColor."\" onMouseOver=\"this.style.backgroundColor='#D9EDFF';\" onMouseOut=\"this.style.backgroundColor='".$rowColor."';\" style=\"cursor:pointer;padding-bottom:1px;\">";
			$tabel .= "<td width=\"30\" height=\"22\" class=\"tabelBorderBottomJust\" style=\"font-size:10px;vertical-align:top;\">";
				$tabel .= "<button class=\"btnStandar\" id=\"btnAddList\" title=\"ADD LIST\" onClick=\"".$onclickAddDataList."\" style=\"\"> Add </button>";
			$tabel .= "</td>";
			$tabel .= "<td width=\"30\" height=\"22\" class=\"tabelBorderBottomJust\" style=\"font-size:10px;vertical-align:top;\">".$btnNya."</td>";
			$tabel .= "<td width=\"55\" class=\"tabelBorderBottomJust\" style=\"font-size:10px;color:#096;font-weight:bold;vertical-align:top;\">".$key."</td>";
			$tabel .= "<td width=\"50\" class=\"tabelBorderBottomJust\" align=\"center\" style=\"font-size:10px;vertical-align:top;\">".$CPublic->convTglNonDB($dueDate)."</td>";
			$tabel .= "<td width=\"100\" class=\"tabelBorderBottomJust\" align=\"left\" style=\"font-size:10px;vertical-align:top;\">".$invNoNya."</td>";
			$tabel .= "<td width=\"150\" class=\"tabelBorderBottomJust\" align=\"left\" style=\"font-size:10px;vertical-align:top;\">".$companyNya."</td>";
			$tabel .= "<td width=\"100\" class=\"tabelBorderBottomJust\" align=\"right\" style=\"font-size:10px;vertical-align:top;\">(".$curr.")&nbsp".number_format($total,2)."</td>";
			$tabel .= "<td width=\"150\" class=\"tabelBorderBottomJust\" align=\"right\" style=\"font-size:10px;vertical-align:top;\">".$bankName."</td>";
		$tabel .= "</tr>";
	}

	$whereNya = " AND YEAR(datepaid) > 2020 ";

	if($aksiGet == "ketikSearchTemp")
	{
		$paramCariGet = $_GET['paramCari'];
		$paramCompany = $_GET['paramCompany'];

		if($paramCariGet != "")
		{
			$whereNya .= " AND batchno like '%".$paramCariGet."%' ";
		}
		
		if($paramCompany != "all")
		{
			$whereNya .= " AND company LIKE '%".$paramCompany."%'";
		}
	}

	$queryVoucher = $CKoneksiVoucher->mysqlQuery("SELECT * FROM tblvoucher WHERE deletests = '0' AND bankcode != '' AND trfacct = 'N' AND st_payment_list = 'N' AND approve_voucher = 'Y' ".$whereNya." ORDER BY batchno DESC;", $CKoneksiVoucher->bukaKoneksi());

	while($rows = $CKoneksiVoucher->mysqlFetch($queryVoucher))
	{
		$btnAddFile = "";
		$cekFileInv = "";
		$invNo = $CPublic->potongKarakter($CPublic->zerofill($rows['invno']), 18);

		if($rows['barcode'] != "")
		{
			$qInvReg = $CKoneksiInvReg->mysqlQuery("SELECT file_upload FROM mailinvoice WHERE deletests = '0' AND barcode = '".$rows['barcode']."' ;", $CKoneksiInvReg->bukaKoneksi());

			while($rInv = $CKoneksiInvReg->mysqlFetch($qInvReg))
			{
				if($rInv['file_upload'] != "")
				{
					$cekFileInv = "<a href=\"./fileUpload/".$rInv['file_upload']."\" target=\"_blank\" title=\"View File\">".$CPublic->potongKarakter($CPublic->zerofill($rows['invno']), 18)."</a>";
				}
			}
		}

		if($cekFileInv != "")
		{
			$invNo = $cekFileInv;
		}else{
			if($rows['file_upload'] != "")
			{
				$invNo = "<a href=\"./../../voucher/templates/fileUpload/".$rows['file_upload']."\" target=\"_blank\" title=\"View File\">".$CPublic->potongKarakter($CPublic->zerofill($rows['invno']), 18)."</a>";
			}else{
				$btnAddFile = "<button class=\"btnStandar\" id=\"btnAddFile\" title=\"ADD FILE\" onClick=\"parent.showFormUploadInvReg('".$rows['idvoucher']."','voucher');\" style=\"\"> File </button>";
			}
		}

		$dueDate = "";
		if($rows['due_date'] != "0000-00-00")
		{
			$dueDate = $CPublic->convTglNonDB($rows['due_date']);
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

		$tabel .= "<tr align=\"center\" valign=\"bottom\" bgcolor=\"".$rowColor."\" onMouseOver=\"this.style.backgroundColor='#D9EDFF';\" onMouseOut=\"this.style.backgroundColor='".$rowColor."';\" style=\"cursor:pointer;padding-bottom:1px;\">";
			$tabel .= "<td height=\"22\" class=\"tabelBorderBottomJust\" style=\"font-size:10px;vertical-align:middle;\">";
				$tabel .= "<button class=\"btnStandar\" id=\"btnAddList\" title=\"ADD LIST\" onClick=\"parent.addDataListPayment('".$rows['idvoucher']."','voucher');\" style=\"\"> Add </button>";
			$tabel .= "</td>";
			$tabel .= "<td height=\"22\" class=\"tabelBorderBottomJust\" style=\"font-size:10px;vertical-align:middle;\">";
				$tabel .= $btnAddFile;
			$tabel .= "</td>";
			$tabel .= "<td width=\"50\" class=\"tabelBorderBottomJust\" style=\"font-size:10px;color:#096;font-weight:bold;vertical-align:top;\">".$CPublic->zerofill($rows['batchno'])."</td>";
			$tabel .= "<td width=\"50\" class=\"tabelBorderBottomJust\" style=\"font-size:10px;font-weight:bold;vertical-align:top;\">".$dueDate."</td>";
			$tabel .= "<td width=\"120\" class=\"tabelBorderBottomJust\" align=\"left\" style=\"font-size:10px;vertical-align:top;\">".$invNo."</td>";
			$tabel .= "<td width=\"150\" class=\"tabelBorderBottomJust\" align=\"left\" style=\"font-size:10px;vertical-align:top;\">".$rows['companyname']."</td>";
			$tabel .= "<td width=\"150\" class=\"tabelBorderBottomJust\" align=\"right\" style=\"font-size:10px;vertical-align:top;\">(".$rows['currency'].") ".number_format($rows['amount'],2)."</td>";
			$tabel .= "<td width=\"150\" class=\"tabelBorderBottomJust\" align=\"right\" style=\"font-size:10px;vertical-align:top;\">".$bankName."</td>";
		$tabel .= "</tr>";
	}

	$tempDataPA = array();
	// $wherePA = " WHERE st_delete = '0' AND voucher_status = 'Y' AND (st_transferToAcct = 'N' OR settlement_transferToAcct = 'N') AND st_payment_list = 'N' ";
	$wherePA = " WHERE st_delete = '0' AND ((voucher_status = 'Y' AND st_transferToAcct = 'N' AND st_payment_list = 'N') OR (settlement_voucher_status = 'Y' AND settlement_transferToAcct = 'N' AND settlement_st_payment_list = 'N')) ";
	if($aksiGet == "ketikSearchTemp")
	{
		$paramCariGet = $_GET['paramCari'];
		$paramCompany = $_GET['paramCompany'];

		if($paramCariGet != "")
		{
			$wherePA .= " AND transno like '%".$paramCariGet."%' OR settlement_transno like '%".$paramCariGet."%' ";
		}
		
		if($paramCompany != "all")
		{
			$wherePA .= " AND company_name LIKE '%".$paramCompany."%'";
		}
	}
	$sqlPA = $CKoneksiPaymentVoucher->mysqlQuery("SELECT * FROM  datapayment ".$wherePA." ORDER BY batchno DESC;", $CKoneksiPaymentVoucher->bukaKoneksi());
	while($rowPA = $CKoneksiPaymentVoucher->mysqlFetch($sqlPA))
	{
		if($rowPA['settlement_transno'] > 0)
		{
			$rowPA['transno'] = $rowPA['settlement_transno'];
			$tempDataPA[$rowPA['transno']]['amount'][] = $rowPA['settlement_voucher_amountpaid'];
		}else{
			$tempDataPA[$rowPA['transno']]['amount'][] = $rowPA['amount'];
		}

		$tempDataPA[$rowPA['transno']]['companyname'][] = $rowPA['company_name'];
		$tempDataPA[$rowPA['transno']]['senderVendor'][] = $rowPA['sendervendor'];
		$tempDataPA[$rowPA['transno']]['invNo'][] = $rowPA['mailinvno'];
		$tempDataPA[$rowPA['transno']]['currency'][] = $rowPA['currency'];
		// $tempDataPA[$rowPA['transno']]['amount'][] = $rowPA['amount'];
		$tempDataPA[$rowPA['transno']]['file_upload'][] = $rowPA['file_upload'];
		$tempDataPA[$rowPA['transno']]['tglexp'][] = $rowPA['invoice_due_date'];
		$tempDataPA[$rowPA['transno']]['bankcode'][] = $rowPA['voucher_bank'];
		$tempDataPA[$rowPA['transno']]['transNoSettlement'][] = $rowPA['settlement_transno'];
		$tempDataPA[$rowPA['transno']]['settlement_file'][] = $rowPA['settlement_file'];
	}

	foreach ($tempDataPA as $key => $val)
	{
		$companyNya = "";
		$invNoNya = "";
		$curr = "";
		$total = 0;
		$btnNya = "";
		$dueDate = "";
		$bankCode = "";
		$transNoSettlement = 0;
		$fileSettlement = "";

		for ($lan = 0; $lan < count($val['companyname']); $lan++)
		{
			$companyNya = $val['companyname'][$lan];
			$curr = $val['currency'][$lan];
			$total = $total + $val['amount'][$lan];
			$dueDate = $val['tglexp'][$lan];
			$bankCode = $val['bankcode'][$lan];
			$transNoSettlement = $val['transNoSettlement'][$lan];

			if($invNoNya == "")
			{
				$invNoNya = "- ".$val['invNo'][$lan];
				if($val['file_upload'][$lan] != "")
				{
					$invNoNya = "<a href=\"./../../paymentAdvance/templates/fileUpload/".$val['file_upload'][$lan]."\" target=\"_blank\" title=\"View File\">".$CPublic->potongKarakter($CPublic->zerofill($val['invNo'][$lan]), 18)."</a>";
				}
			}else{
				if($val['file_upload'][$lan] != "")
				{
					$invNoNya .= "<br>- "."<a href=\"./../../paymentAdvance/templates/fileUpload/".$val['file_upload'][$lan]."\" target=\"_blank\" title=\"View File\">".$CPublic->potongKarakter($CPublic->zerofill($val['invNo'][$lan]), 18)."</a>";
				}else{
					$invNoNya .= "<br>- ".$val['invNo'][$lan];
				}
			}

			if($transNoSettlement > 0)
			{
				$total = $val['amount'][$lan];
				if($val['settlement_file'][$lan] != "")
				{
					$fileSettlement = "<a href=\"./../../paymentAdvance/templates/fileUploadBukti/".$val['settlement_file'][$lan]."\" target=\"_blank\" title=\"File Settlement\">";
					$fileSettlement .= "<img src=\"../picture/arrow-skip-270.png\" width=\"12\" title=\"Settlement\"></a>";
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

		$trnsNo = 0;
		$cekTransNo = $key;

		if($transNoSettlement > 0)
		{
			$trnsNo = "PA-".$CPublic->zerofill($transNoSettlement);
			$cekTransNo = $transNoSettlement;
		}else{
			$trnsNo = "PA-".$CPublic->zerofill($key);
		}

		$tabel .= "<tr align=\"center\" valign=\"bottom\" bgcolor=\"".$rowColor."\" onMouseOver=\"this.style.backgroundColor='#D9EDFF';\" onMouseOut=\"this.style.backgroundColor='".$rowColor."';\" style=\"cursor:pointer;padding-bottom:1px;\">";
			$tabel .= "<td width=\"30\" height=\"22\" class=\"tabelBorderBottomJust\" style=\"font-size:10px;vertical-align:top;\">";
				$tabel .= "<button class=\"btnStandar\" id=\"btnAddList\" title=\"ADD LIST\" onClick=\"parent.addDataListPayment('".$cekTransNo."','paymentAdvance');\" style=\"\"> Add </button>";
			$tabel .= "</td>";
			$tabel .= "<td width=\"30\" height=\"22\" class=\"tabelBorderBottomJust\" style=\"font-size:10px;vertical-align:top;\">".$btnNya."</td>";
			$tabel .= "<td width=\"55\" class=\"tabelBorderBottomJust\" style=\"font-size:10px;color:#096;font-weight:bold;vertical-align:top;\">".$trnsNo."<br>".$fileSettlement."</td>";
			$tabel .= "<td width=\"50\" class=\"tabelBorderBottomJust\" align=\"center\" style=\"font-size:10px;vertical-align:top;\">".$CPublic->convTglNonDB($dueDate)."</td>";
			$tabel .= "<td width=\"100\" class=\"tabelBorderBottomJust\" align=\"left\" style=\"font-size:10px;vertical-align:top;\">".$invNoNya."</td>";
			$tabel .= "<td width=\"150\" class=\"tabelBorderBottomJust\" align=\"left\" style=\"font-size:10px;vertical-align:top;\">".$companyNya."</td>";
			$tabel .= "<td width=\"100\" class=\"tabelBorderBottomJust\" align=\"right\" style=\"font-size:10px;vertical-align:top;\">(".$curr.")&nbsp".number_format($total,2)."</td>";
			$tabel .= "<td width=\"150\" class=\"tabelBorderBottomJust\" align=\"right\" style=\"font-size:10px;vertical-align:top;\">".$bankName."</td>";
		$tabel .= "</tr>";
	}

	echo $tabel;
	$CKoneksiInvReg->bukaKoneksi();
?>
    </table>
</body>

</HTML>
<script language="javascript">
window.onload =
    function() {
        parent.doneWait();
        parent.panggilEnableLeftClick();
    }
</script>