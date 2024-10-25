<?php
require_once("configPaymentAdvance.php");

$tempArray = array();

if($aksiPost == "ketikAutoComplSender")
{
	$paramPost = str_replace("'", "''", $_POST['param']);
	$slcAuto = "";

	// $query = $koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "SELECT Acctcode, RTRIM(AcctIndo) AS AcctIndo, RTRIM(Acctname) AS Acctname FROM AccountCode WHERE Acctname LIKE '%".$paramPost."%' AND SUBSTRING(Acctcode ,1,2) = '12' AND LEN(RTRIM(Acctcode))=5 ORDER BY Acctname ASC;");
	$query = $koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "SELECT Acctcode, RTRIM(AcctIndo) AS AcctIndo, RTRIM(Acctname) AS Acctname FROM AccountCode WHERE Acctname LIKE '%".$paramPost."%' AND (LEN(RTRIM(Acctcode)) = 5) AND ((LEFT(Acctcode, 2) = '11') OR (Acctcode >= '50000')) AND acctcode != '99999' ORDER BY Acctcode ASC;");
	while($row = $koneksiOdbcAcc->odbcFetch($query))
	{
		$slcAuto .= "<li onclick=\"dataSelectAutoComplete('".$row['Acctname']."','".$row['Acctcode']."')\" style=\"background-color:#DDDDDD;cursor:pointer;\">".$row['Acctcode']." - ".$row['Acctname']."</li>";
	}
	print json_encode($slcAuto);
}
if($aksiPost == "getDueDate")
{
	$dueDate = "";
	$invDate = $_POST['invDate'];
	$dueDay = $_POST['dueDay'];

	$ds = explode("/", $invDate);

	$y = $ds[2];
	$m = $ds[1];
	$d = $ds[0];

	$Date1 = $y."/".$m."/".$d;
	$date = new DateTime($Date1);
	$date->modify('+'.$dueDay.' day');

	$dueDate = $date->format('d/m/Y');

	print json_encode($dueDate);
}
if($aksiPost == "saveDataPaymentRequest")
{
	$stData = "";
	$data = $_POST;
	$dateNow = date('Y-m-d');
	$userId = $userIdSession;
	$slcUnitName = "";
	$slcComName = "";

	$idEdit = $data['idEdit'];
	$batchno = $data['batchno'];
	$txtEntryDate = $CPublic->convTglDB($data['txtEntryDate']);
	$txtReqName = $data['txtReqName'];
	//$lblSenderVendorAccount = $data['lblSenderVendorAccount'];
	//$txtSenderVendor = $data['txtSenderVendor'];
	$txtBarcode = $data['txtBarcode'];
	$txtBarcodeNo = $data['txtBarcodeNo'];
	$txtInvoiceDate = $CPublic->convTglDB($data['txtInvoiceDate']);
	$txtDueDay = $data['txtDueDay'];
	$txtDueDate = $CPublic->convTglDB($data['txtDueDate']);
	$txtNoInvoice = $data['txtNoInvoice'];
	$txtAmount = str_replace(",","",$data['txtAmount']);
	$txtCurrency = $data['txtCurrency'];
	$txtRemark = $data['txtRemark'];
	$cekFile = $data['cekFile'];
	$vslCode = $data['vslCode'];
	$vslName = $data['vslName'];
	$voyageNo = $data['voyageNo'];

	$chkAdvance = "general";
	if($data['chkAdvance'] == 'true')
	{
		$chkAdvance = "advance";
	}

	$slcInitCompany = $data['slcInitCompany'];
	if($slcInitCompany != "")
	{
		$slcComName = trim($data['slcNameCompany']);
	}

	$slcUnit = $data['slcUnit'];
	if($slcUnit != "")
	{
		$slcUnitName = trim($data['slcUnitName']);
	}

	try {

		if($userId != "")
		{
			if($idEdit == "")
			{
			    $txtBarcodeNo = $CPaymentAdv->getNewBarcode();
			    $txtBarcode = "P".$CPaymentAdv->getFormatNo($txtBarcodeNo,7);

				$CkoneksiPaymentAdv->mysqlQuery("INSERT INTO datapayment (doc_type,batchno,entry_date,request_name,voyage_no,vessel_code,vessel_name,init_company,company_name,divisi,barcode,barcode_no,invoice_date,due_day,invoice_due_date,mailinvno,amount,currency,remark,add_userId,add_userDate,st_submit) VALUES ('".$chkAdvance."','".$batchno."','".$txtEntryDate."','".$txtReqName."','".$voyageNo."','".$vslCode."','".$vslName."','".$slcInitCompany."','".$slcComName."','".$slcUnitName."','".$txtBarcode."','".$txtBarcodeNo."','".$txtInvoiceDate."','".$txtDueDay."','".$txtDueDate."','".$txtNoInvoice."','".$txtAmount."','".$txtCurrency."','".$txtRemark."','".$userId."','".$dateNow."','N')");

				$idInsert = mysql_insert_id();

				if($cekFile != "")
			    {
			        $tmpFile = $_FILES['fileData']['tmp_name'];
			        $fileName = $_FILES['fileData']['name'];
			        $dir = "./templates/fileUpload";
			        $newFileName = "filePaymentRequest_".$idInsert;

			        $dt = explode(".", $fileName);
			        $newFileName = str_replace(array(' ','/','.',',','-'), '', $newFileName).".".trim($dt[count($dt)-1]);

			        move_uploaded_file($tmpFile, $dir."/".$fileName);
			        rename($dir."/".$fileName, $dir."/".$newFileName);

			        $CkoneksiPaymentAdv->mysqlQuery("UPDATE datapayment SET file_upload='".$newFileName."' WHERE id=".$idInsert." AND st_delete = '0' ");
			    }
			}else{
				$fieldfileNya = "";
				if($cekFile != "")
			    {
			        $tmpFile = $_FILES['fileData']['tmp_name'];
			        $fileName = $_FILES['fileData']['name'];
			        $dir = "./templates/fileUpload";
			        $newFileName = "filePaymentRequest_".$idEdit;

			        $dt = explode(".", $fileName);
			        $newFileName = str_replace(array(' ','/','.',',','-'), '', $newFileName).".".trim($dt[count($dt)-1]);

			        move_uploaded_file($tmpFile, $dir."/".$fileName);
			        rename($dir."/".$fileName, $dir."/".$newFileName);

			        $fieldfileNya = ",file_upload='".$newFileName."'";
			    }

			    if($txtBarcodeNo == "0")
			    {
			    	$txtBarcodeNo = $CPaymentAdv->getNewBarcode();
			    	$txtBarcode = "P".$CPaymentAdv->getFormatNo($txtBarcodeNo,7);
			    }

				$CkoneksiPaymentAdv->mysqlQuery("UPDATE datapayment SET doc_type = '".$chkAdvance."',entry_date = '".$txtEntryDate."',request_name = '".$txtReqName."',voyage_no = '".$voyageNo."',vessel_code = '".$vslCode."',vessel_name = '".$vslName."',init_company = '".$slcInitCompany."',company_name = '".$slcComName."',divisi = '".$slcUnitName."',barcode = '".$txtBarcode."',barcode_no = '".$txtBarcodeNo."',invoice_date = '".$txtInvoiceDate."',due_day = '".$txtDueDay."',invoice_due_date = '".$txtDueDate."',mailinvno = '".$txtNoInvoice."',amount = '".$txtAmount."',currency = '".$txtCurrency."',remark = '".$txtRemark."',edit_userId = '".$userId."',edit_userDate = '".$dateNow."' ".$fieldfileNya.",st_submit = 'N',reject_status = 'N',reject_userId = '0',reject_date = '0000-00-00',reject_remark = '' WHERE id='".$idEdit."' AND st_delete = '0' ");
			}
		    $stData = "Success..!!";
		}else{
			$stData = "Session Time Out, Silahkan Login kembali..!!";
		}
	} catch (Exception $ex) {
		$stData = $ex->getMessage();
	}

	print json_encode($stData);
}
if($aksiPost == "delDataPaymentReq")
{
	$idDel = $_POST['idDel'];
	$stData = "";
	$dateNow = date('Y-m-d');
	$userId = $userIdSession;

	try {

		$CkoneksiPaymentAdv->mysqlQuery("UPDATE datapayment SET st_delete='1',del_userId = '".$userId."',del_userDate = '".$dateNow."' WHERE id='".$idDel."' ");
		$stData = "Success..!!";
	} catch (Exception $ex) {
		$stData = $ex->getMessage();
	}

	print json_encode($stData);
}
if($aksiPost == "batchnoChangeThnBln")
{
	$thnBln = $_POST['thnBln'];

	$dayNya = $CPaymentAdv->getsearchBatchNoByThnBln($thnBln);

	print json_encode($dayNya);
}
if($aksiPost == "submitDataRequest")
{
	$dateNow = date('Y-m-d');
	$stData = "";
	$idSubmit = $_POST['idSubmit'];
	$userId = $userIdSession;

	try {
		if($userId != "")
		{
			$CkoneksiPaymentAdv->mysqlQuery("UPDATE datapayment SET st_submit = 'Y',submit_userId = '".$userId."',submit_userDate = '".$dateNow."' WHERE id = ".$idSubmit." AND st_delete = '0' ");
			$stData = "Success..!!";
			$CPaymentAdv->sentNotifMailConfirm($idSubmit);
		}else{
			$stData = "Session Time Out, Silahkan Login kembali..!!";
		}
	} catch (Exception $ex) {
		$stData = $ex->getMessage();
	}
	print json_encode($stData);
}
if($aksiPost == "getBarcodeNo")
{
	$dataOut = array();

	$txtBarcodeNo = $CPaymentAdv->getNewBarcode();
	$txtBarcode = "P".$CPaymentAdv->getFormatNo($txtBarcodeNo,7);

	$dataOut['txtBarcodeNo'] = $txtBarcodeNo;
	$dataOut['txtBarcode'] = $txtBarcode;

	print json_encode($dataOut);
}
if($aksiPost == "getDataConfirm")
{
	$thnBlnTgl = "";
	$thnBln = $_POST['thnBln'];
	$tgl = $_POST['tgl'];

	if($tgl == 'all')
	{
		$thnBlnTgl = $thnBln;
	}else{
		$thnBlnTgl = $thnBln.$tgl;
	}

	$dataNya = $CPaymentAdv->getDataConfirm($thnBlnTgl,$userJenisPaymentAdv,$userIdSession);
	print json_encode($dataNya);
}
if($aksiPost == "confirmData")
{
	$dateNow = date('Y-m-d');
	$stData = "";
	$idChecked = $_POST['idChecked'];
	$userId = $userIdSession;
	$idNya = "";

	try {
		if($userId != "")
		{
			if(count($idChecked) > 0)
			{
				for ($lan=0; $lan < count($idChecked); $lan++)
				{
					if($idNya == "")
					{
						$idNya = "'".$idChecked[$lan]."'";
					}else{
						$idNya .= ",'".$idChecked[$lan]."'";
					}
				}
				$CkoneksiPaymentAdv->mysqlQuery("UPDATE datapayment SET st_confirm = 'Y',confirm_userId = '".$userId."',confirm_userDate = '".$dateNow."' WHERE id IN (".$idNya.") AND st_delete = '0' ");
				$stData = "Success..!!";
				$CPaymentAdv->sentNotifMail($idNya,"Check",$userId);
			}
		}else{
			$stData = "Session Time Out, Silahkan Login kembali..!!";
		}
	} catch (Exception $ex) {
		$stData = $ex->getMessage();
	}
	print json_encode($stData);
}
if($aksiPost == "getDataCheck")
{
	$thnBlnTgl = "";
	$thnBln = $_POST['thnBln'];
	$tgl = $_POST['tgl'];

	if($tgl == 'all')
	{
		$thnBlnTgl = $thnBln;
	}else{
		$thnBlnTgl = $thnBln.$tgl;
	}

	$dataNya = $CPaymentAdv->getDataCheck($thnBlnTgl);
	print json_encode($dataNya);
}
if($aksiPost == "giveCheckData")
{
	$dateNow = date('Y-m-d');
	$stData = "";
	$idPayment = $_POST['txtIdEdit'];
	$txtTypeDoc = $_POST['txtTypeDoc'];
	$userId = $userIdSession;
	//str_replace(",","",$data['txtAmount']);
	try {
		if($userId != "")
		{
			if($txtTypeDoc == "giveSettlement")
			{
				$CkoneksiPaymentAdv->mysqlQuery("DELETE FROM payment_split_settlement WHERE id_payment = '".$idPayment."' ");
				for ($lan=1; $lan <= 5; $lan++)
				{
					if($_POST['accName_'.$lan] != "" AND $_POST['amount_'.$lan] != "")
					{
						$CkoneksiPaymentAdv->mysqlQuery("INSERT INTO payment_split_settlement (id_payment,account_code,account_name,type_dbcr,amount,vessel_code,vessel_name,voyage_no,description) VALUES ('".$idPayment."','".$_POST['accNo_'.$lan]."','".$_POST['accName_'.$lan]."','".$_POST['dbCr_'.$lan]."','".str_replace(",","",$_POST['amount_'.$lan])."','".$_POST['vslCode_'.$lan]."','".$_POST['vslName_'.$lan]."','".$_POST['voy_'.$lan]."','".$_POST['desc_'.$lan]."')");
					}
				}
				$CkoneksiPaymentAdv->mysqlQuery("UPDATE datapayment SET reject_status = 'N',reject_userId = '0',reject_date = '0000-00-00',reject_remark = '',st_settlementCheck = 'Y',settlement_check_userId = '".$userId."',settlement_check_date = '".$dateNow."' WHERE id='".$idPayment."' AND st_delete = '0' ");
			}else{
				$CkoneksiPaymentAdv->mysqlQuery("DELETE FROM payment_split WHERE id_payment = '".$idPayment."' ");
				for ($lan=1; $lan <= 5; $lan++)
				{
					if($_POST['accName_'.$lan] != "" AND $_POST['amount_'.$lan] != "")
					{
						$CkoneksiPaymentAdv->mysqlQuery("INSERT INTO payment_split (id_payment,account_code,account_name,type_dbcr,amount,vessel_code,vessel_name,voyage_no,description) VALUES ('".$idPayment."','".$_POST['accNo_'.$lan]."','".$_POST['accName_'.$lan]."','".$_POST['dbCr_'.$lan]."','".str_replace(",","",$_POST['amount_'.$lan])."','".$_POST['vslCode_'.$lan]."','".$_POST['vslName_'.$lan]."','".$_POST['voy_'.$lan]."','".$_POST['desc_'.$lan]."')");
					}
				}
				$CkoneksiPaymentAdv->mysqlQuery("UPDATE datapayment SET reject_status = 'N',reject_userId = '0',reject_date = '0000-00-00',reject_remark = '',st_check = 'Y',check_userId = '".$userId."',check_userDate = '".$dateNow."' WHERE id='".$idPayment."' AND st_delete = '0' ");
			}
		    $stData = "Success..!!";
		    $CPaymentAdv->sentNotifMail($idPayment,"Approve",$userId);
		}else{
			$stData = "Session Time Out, Silahkan Login kembali..!!";
		}
	} catch (Exception $ex) {
		$stData = $ex->getMessage();
	}
	print json_encode($stData);
}
if($aksiPost == "getDataApprove")
{
	$thnBlnTgl = "";
	$thnBln = $_POST['thnBln'];
	$tgl = $_POST['tgl'];
	if($tgl == 'all')
	{
		$thnBlnTgl = $thnBln;
	}else{
		$thnBlnTgl = $thnBln.$tgl;
	}
	$dataNya = $CPaymentAdv->getDataApprove($thnBlnTgl);
	print json_encode($dataNya);
}
if($aksiPost == "approveData")
{
	$dateNow = date('Y-m-d');
	$stData = "";
	$idChecked = $_POST['idChecked'];
	$typeAprvArr = $_POST['typeAprvArr'];
	$userId = $userIdSession;

	try {
		if($userId != "")
		{
			if(count($idChecked) > 0)
			{
				for ($lan=0; $lan < count($idChecked); $lan++)
				{
					if($typeAprvArr[$lan] == "approve")
					{
						$CkoneksiPaymentAdv->mysqlQuery("UPDATE datapayment SET st_approve = 'Y',approve_userId = '".$userId."',approve_userDate = '".$dateNow."' WHERE id IN (".$idChecked[$lan].") AND st_delete = '0' ");
					}else{
						$CkoneksiPaymentAdv->mysqlQuery("UPDATE datapayment SET  st_settlementApprove  = 'Y',settlement_approve_userId = '".$userId."',settlement_approve_date = '".$dateNow."' WHERE id IN (".$idChecked[$lan].") AND st_delete = '0' ");
					}
					
				}

				$stData = "Success..!!";
				$CPaymentAdv->sentNotifMail($idNya,"Release",$userId);
			}		    
		}else{
			$stData = "Session Time Out, Silahkan Login kembali..!!";
		}
	} catch (Exception $ex) {
		$stData = $ex->getMessage();
	}
	print json_encode($stData);
}
if($aksiPost == "getDataRelease")
{
	$thnBlnTgl = "";
	$thnBln = $_POST['thnBln'];
	$tgl = $_POST['tgl'];

	if($tgl == 'all')
	{
		$thnBlnTgl = $thnBln;
	}else{
		$thnBlnTgl = $thnBln.$tgl;
	}

	$dataNya = $CPaymentAdv->getDataRelease($thnBlnTgl);
	print json_encode($dataNya);
}
if($aksiPost == "releaseData")
{
	$dateNow = date('Y-m-d');
	$stData = "";
	$idChecked = $_POST['idChecked'];
	$idCheckedSettle = $_POST['idCheckedSettle'];
	$remarkNya = $_POST['remarkNya'];
	$userId = $userIdSession;
	$idNya = "";
	$idSettleNya = "";

	$arrTempIdChecked = explode(",",$idChecked);
	$arrTempIdCheckedSettle = explode(",",$idCheckedSettle);

	try {
		if($userId != "")
		{
			if(count($arrTempIdChecked) > 0)
			{
				for ($lan=0; $lan < count($arrTempIdChecked); $lan++)
				{
					if($idNya == "")
					{
						$idNya = "'".$arrTempIdChecked[$lan]."'";
					}else{
						$idNya .= ",'".$arrTempIdChecked[$lan]."'";
					}
				}

				$CkoneksiPaymentAdv->mysqlQuery("UPDATE datapayment SET st_release = 'Y',release_userId = '".$userId."',release_userDate = '".$dateNow."',release_remark = '".$remarkNya."' WHERE id IN (".$idNya.") AND st_delete = '0' ");				
			    $stData = "Success..!!";
			    // $CPaymentAdv->sentNotifMail($idNya,"After Release",$userId);
			}
			if(count($arrTempIdCheckedSettle) > 0)
			{
				for ($lan=0; $lan < count($arrTempIdCheckedSettle); $lan++)
				{
					if($idSettleNya == "")
					{
						$idSettleNya = "'".$arrTempIdCheckedSettle[$lan]."'";
					}else{
						$idSettleNya .= ",'".$arrTempIdCheckedSettle[$lan]."'";
					}
				}

				$CkoneksiPaymentAdv->mysqlQuery("UPDATE datapayment SET st_settlementRelease = 'Y',settlement_release_userId = '".$userId."',settlement_release_date = '".$dateNow."',settlement_release_remark = '".$remarkNya."' WHERE id IN (".$idSettleNya.") AND st_delete = '0' ");				
			    $stData = "Success..!!";
			    // $CPaymentAdv->sentNotifMail($idNya,"After Release",$userId);
			}
		}else{
			$stData = "Session Time Out, Silahkan Login kembali..!!";
		}
	} catch (Exception $ex) {
		$stData = $ex->getMessage();
	}
	print json_encode($stData);
}
if($aksiPost == "getDisplayKet")
{
	$idPayment = $_POST['idPayment'];
	$typeDoc = $_POST['typeDoc'];

	$dataNya = $CPaymentAdv->getDataPaymentRequestSplit($idPayment,$typeDoc);
	print json_encode($dataNya);
}
if($aksiPost == "getVoyageNo")
{
	$thn = $_POST['thn'];
	$vslCode = $_POST['vsl'];

	$dt = explode("/", $thn);

	$dataNya = $CPaymentAdv->getVoyageNo("",$dt[2],$vslCode);
	print json_encode($dataNya);
}
if($aksiPost == "assignTransNo")
{
	$dateNow = date('Y-m-d');
	$stData = "";
	$userId = $userIdSession;
	$idNya = $_POST['idPaymenAll'];
	$typeDoc = $_POST['typeDoc'];

	try {
		if($userId != "")
		{
			if($typeDoc == "giveSettlement")
			{
				$nextTransNo = ($CPaymentAdv->getLastTransNo())+1;
				$CkoneksiPaymentAdv->mysqlQuery("UPDATE datapayment SET settlement_transno = '".$nextTransNo."',settlement_assigntransno_userid = '".$userId."',settlement_assigntransno_date = '".$dateNow."' WHERE id IN (".$idNya.") AND st_delete = '0' ");
			}else{
				$nextTransNo = ($CPaymentAdv->getLastTransNo())+1;
				$CkoneksiPaymentAdv->mysqlQuery("UPDATE datapayment SET transno = '".$nextTransNo."',assigntransno_userId = '".$userId."',assigntransno_userDate = '".$dateNow."' WHERE id IN (".$idNya.") AND st_delete = '0' ");
			}			

			$stData = "Success..!!";
		}else{
			$stData = "Session Time Out, Silahkan Login kembali..!!";
		}
	} catch (Exception $ex) {
		$stData = $ex->getMessage();
	}
	print json_encode($stData);
}
if($aksiPost == "searchDataNya")
{
	$trNya = "";
	$txtSearch = $_POST['txtSearch'];
	$slcSearch = $_POST['slcSearch'];

	$trNya = $CPaymentAdv->getDataPrepareForPayment("search",$txtSearch,$slcSearch);

	print json_encode($trNya);
}
if($aksiPost == "getDisplayKetByTransNo")
{
	$transno = $_POST['transno'];
	$typeDoc = $_POST['typeDoc'];

	$dataNya = $CPaymentAdv->getDataVoucherByTransno($transno,$typeDoc);
	print json_encode($dataNya);
}
if($aksiPost == "saveDataVoucher")
{
	$stData = "";
	$data = $_POST;
	$dateNow = date('Y-m-d');
	$userId = $userIdSession;
	$typeDoc = $data['typeDoc'];

	$txtTransNo = $data['txtTransNo'];
	$slcPayMethod = $data['slcPayMethod'];
	$slcBankCode = $data['slcBankCode'];
	$txtVoucher = $data['txtVoucher'];
	$txtPaidToFrom = $data['txtPaidToFrom'];
	$txtRef = $data['txtRef'];
	$txtChequeNumber = $data['txtChequeNumber'];
	$txtDatePaid = $CPublic->convTglDB($data['txtDatePaid']);
	$txtAmountPaid = str_replace(",","",$data['txtAmountPaid']);
	$slcCurrency = $data['slcCurrency'];

	try {

		if($userId != "")
		{
			if($typeDoc == "giveSettlement")
			{
				$CkoneksiPaymentAdv->mysqlQuery("UPDATE datapayment SET settlement_voucher_status='Y',settlement_voucher_method='".$slcPayMethod."',settlement_voucher_bank='".$slcBankCode."',settlement_voucher_no='".$txtVoucher."',settlement_voucher_referenceno='".$txtRef."',settlement_voucher_datepaid='".$txtDatePaid."',settlement_voucher_amountcurr='".$slcCurrency."',settlement_voucher_amountpaid='".$txtAmountPaid."',settlement_voucher_paidtofrom='".$txtPaidToFrom."',settlement_voucher_cheqno='".$txtChequeNumber."',settlement_voucher_addUserId='".$userId."',settlement_voucher_addDate='".$dateNow."' WHERE settlement_transno=".$txtTransNo." AND st_delete = '0' ");
			}else{
				$CkoneksiPaymentAdv->mysqlQuery("UPDATE datapayment SET voucher_status='Y',voucher_method='".$slcPayMethod."',voucher_bank='".$slcBankCode."',voucher_no='".$txtVoucher."',voucher_referenceno='".$txtRef."',voucher_datepaid='".$txtDatePaid."',voucher_amountcurr='".$slcCurrency."',voucher_amountpaid='".$txtAmountPaid."',voucher_paidtofrom='".$txtPaidToFrom."',voucher_cheqno='".$txtChequeNumber."',voucher_addUserId='".$userId."',voucher_dateUserId='".$dateNow."' WHERE transno=".$txtTransNo." AND st_delete = '0' ");
			}
		    $stData = "Success..!!";
		}else{
			$stData = "Session Time Out, Silahkan Login kembali..!!";
		}
	} catch (Exception $ex) {
		$stData = "Failed => ".$ex->getMessage();
	}

	print json_encode($stData);
}
if($aksiPost == "transToAcct")
{
	$stData = "";
	$data = $_POST;
	$dateNow = date('Y-m-d');
	$userId = $userIdSession;

	$txtTransNo = $data['txtTransNo'];
	$typeDoc = $data['typeDoc'];

	try {

		if($userId != "")
		{			
			$dbAccounting = "";

			if($typeDoc == "giveSettlement")
			{
				$CkoneksiPaymentAdv->mysqlQuery("UPDATE datapayment SET settlement_transferToAcct='Y', settlement_transferToAcct_userId ='".$userId."',settlement_transferToAcct_date ='".$dateNow."' WHERE settlement_transno=".$txtTransNo." AND st_delete = '0' ");

				$query = $CkoneksiPaymentAdv->mysqlQuery("SELECT * FROM datapayment WHERE settlement_transno=".$txtTransNo." AND st_delete = '0'", $CkoneksiPaymentAdv->bukaKoneksi());
				while($row = $CkoneksiPaymentAdv->mysqlFetch($query))
				{
					$initCmp = $row['init_company'];
					$source = $CPaymentAdv->detilBankSource($row['settlement_voucher_bank'], "source");
					$bookDate = $row['settlement_voucher_datepaid'];
					$voucherNo = $row['settlement_voucher_no'];
					$refNumber = $source.$CPublic->cariNilaiTglDB($row['settlement_voucher_datepaid'],'bulan')."-".$CPaymentAdv->getFormatNo($row['settlement_voucher_referenceno']);
					$invNo = "PA-".$CPaymentAdv->getFormatNo($row['settlement_transno']);
					$pono = $row['barcode'];
					$remark = substr($CPublic->ms_escape_string( $row['settlement_voucher_paidtofrom'] ), 0, 70);
					$vslCode = "";
					$accountNo = $row['settlement_voucher_bank'];
					$currency = $row['settlement_voucher_amountcurr'];
					$entryDate = $CPublic->tglServerWithStrip()." ".$CPublic->jamServer();
					$payTypeGet = $row['settlement_voucher_method'];
					$progDebug = "Voucher:".$row['batchno'];
					$transNo = $row['settlement_transno'];
					$voyageNo = $row['voyage_no'];

					$amountPaid = $row['settlement_voucher_amountpaid'];
					$bankCharge = $row['settlement_voucher_bankcharges'];
					$amountPlus = $amountPaid + $bankCharge;
					$amountAll = number_format((float)$amountPlus, 2, '.', '');

					$thnDatePaid = substr($row['settlement_voucher_datepaid'], 0, 4);
					$compNya = $row['init_company'];
					
					$dbAccounting = $compNya.$thnDatePaid;

					$ttlAdvance = $row['voucher_amountpaid'];
					$ttlExpense = $row['settlement_amount'];

					$dbCrNya = "CR";

					if($ttlAdvance > $ttlExpense)
					{
						$dbCrNya = "DB";
					}

					$koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "INSERT INTO ".$dbAccounting." 
						(Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Remark, Entryuser, Progdebug, Jobnumber, voyage) VALUES 
						('".$initCmp."', '".$source."', '".$bookDate."', '".$voucherNo."', '".$refNumber."', '".$invNo."', '".$pono."', '".$remark."', '".$vslCode."', '".$accountNo."', '', '', '".$currency."', '".$dbCrNya."', '".$amountAll."', '".$currency."', '*', '".$entryDate."', '".$payTypeGet."', '".$userName."', '".$progDebug."', '".$transNo."', '".$voyageNo."')");

					$sqlSplit = $CkoneksiPaymentAdv->mysqlQuery("SELECT * FROM payment_split_settlement WHERE id_payment=".$row['id']." AND st_delete = '0'", $CkoneksiPaymentAdv->bukaKoneksi());
					while($rowSplit = $CkoneksiPaymentAdv->mysqlFetch($sqlSplit))
					{
						$remark = substr($CPublic->ms_escape_string($rowSplit['description']), 0, 70);
						$vslCode = $row['vessel_code'];
						if($row['vessel_code'] == '0')
						{
							$vslCode = "";
						}
						$accountNo = $rowSplit['account_code'];
						$currency = $row['currency'];
						$amount = number_format((float)$rowSplit['amount'], 2, '.', '');
						$dbCr = $rowSplit['type_dbcr'];

						$koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "INSERT INTO ".$dbAccounting." 
							(Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Remark, Entryuser, Progdebug, Jobnumber, voyage) VALUES 
							('".$initCmp."', '".$source."', '".$bookDate."', '".$voucherNo."', '".$refNumber."', '".$invNo."', '".$pono."', '".$remark."', '".$vslCode."', '".$accountNo."', '', '', '".$currency."', '".$dbCr."', '".$amount."', '".$currency."', '*', '".$entryDate."', '".$payTypeGet."', '".$userName."', '".$progDebug."', '".$transNo."', '".$voyageNo."')");
					}
				}

			}else{
				$CkoneksiPaymentAdv->mysqlQuery("UPDATE datapayment SET st_transferToAcct='Y',transfer_userId='".$userId."',transfer_userDate='".$dateNow."' WHERE transno=".$txtTransNo." AND st_delete = '0' ");
				$query = $CkoneksiPaymentAdv->mysqlQuery("SELECT * FROM datapayment WHERE transno=".$txtTransNo." AND st_delete = '0'", $CkoneksiPaymentAdv->bukaKoneksi());
				while($row = $CkoneksiPaymentAdv->mysqlFetch($query))
				{
					$initCmp = $row['init_company'];
					$source = $CPaymentAdv->detilBankSource($row['voucher_bank'], "source");
					$bookDate = $row['voucher_datepaid'];
					$voucherNo = $row['voucher_no'];
					$refNumber = $source.$CPublic->cariNilaiTglDB($row['voucher_datepaid'],'bulan')."-".$CPaymentAdv->getFormatNo($row['voucher_referenceno']);
					$invNo = "PA-".$CPaymentAdv->getFormatNo($row['transno']);
					$pono = $row['barcode'];
					$remark = substr($CPublic->ms_escape_string($row['voucher_paidtofrom']), 0, 70);
					$vslCode = "";
					$accountNo = $row['voucher_bank'];
					$currency = $row['voucher_amountcurr'];
					$entryDate = $CPublic->tglServerWithStrip()." ".$CPublic->jamServer();
					$payTypeGet = $row['voucher_method'];
					$progDebug = "Voucher:".$row['batchno'];
					$transNo = $row['transno'];
					$voyageNo = $row['voyage_no'];
					$amountPaid = $row['voucher_amountpaid'];
					$bankCharge = $row['voucher_bankcharges'];
					$amountPlus = $amountPaid + $bankCharge;
					$amountAll = number_format((float)$amountPlus, 2, '.', '');
					$thnDatePaid = substr($row['voucher_datepaid'], 0, 4);
					$compNya = $row['init_company'];
					
					$dbAccounting = $compNya.$thnDatePaid;

					$koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "INSERT INTO ".$dbAccounting." 
						(Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Remark, Entryuser, Progdebug, Jobnumber, voyage) VALUES 
						('".$initCmp."', '".$source."', '".$bookDate."', '".$voucherNo."', '".$refNumber."', '".$invNo."', '".$pono."', '".$remark."', '".$vslCode."', '".$accountNo."', '', '', '".$currency."', 'CR', '".$amountAll."', '".$currency."', '*', '".$entryDate."', '".$payTypeGet."', '".$userName."', '".$progDebug."', '".$transNo."', '".$voyageNo."')");

					$sqlSplit = $CkoneksiPaymentAdv->mysqlQuery("SELECT * FROM payment_split WHERE id_payment=".$row['id']." AND st_delete = '0'", $CkoneksiPaymentAdv->bukaKoneksi());
					while($rowSplit = $CkoneksiPaymentAdv->mysqlFetch($sqlSplit))
					{
						$remark = substr($CPublic->ms_escape_string($rowSplit['description']), 0, 70);
						$vslCode = $row['vessel_code'];
						if($row['vessel_code'] == '0')
						{
							$vslCode = "";
						}
						$accountNo = $rowSplit['account_code'];
						$currency = $row['currency'];
						$amount = number_format((float)$rowSplit['amount'], 2, '.', '');

						$koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "INSERT INTO ".$dbAccounting." 
							(Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Remark, Entryuser, Progdebug, Jobnumber, voyage) VALUES 
							('".$initCmp."', '".$source."', '".$bookDate."', '".$voucherNo."', '".$refNumber."', '".$invNo."', '".$pono."', '".$remark."', '".$vslCode."', '".$accountNo."', '', '', '".$currency."', 'DB', '".$amount."', '".$currency."', '*', '".$entryDate."', '".$payTypeGet."', '".$userName."', '".$progDebug."', '".$transNo."', '".$voyageNo."')");
					}

					if($bankCharge > 0)
					{
						$amountBankCharge = number_format((float)$bankCharge, 2, '.', '');

						$koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "INSERT INTO ".$dbAccounting." 
							(Company, Source, Bookdate, Voucher, Refnumber, Invoiceno, Pono, Bookdesc, Vessel, Account, Subacct, Subcode, Currcy, Booksts, Amount, Diffcur, Codests, Entrydate, Remark, Entryuser, Progdebug, Jobnumber, voyage) VALUES 
							('".$initCmp."', '".$source."', '".$bookDate."', '".$voucherNo."', '".$refNumber."', '".$invNo."', '".$pono."', 'Bank Charges', '', '50021', '', '', '".$currency."', 'DB', '".$amountBankCharge."', '".$currency."', '*', '".$entryDate."', '".$payTypeGet."', '".$userName."', '".$progDebug."', '".$transNo."', '".$voyageNo."')");
					}
				}
			}

		    $stData = "Success..!!";
		}else{
			$stData = "Session Time Out, Silahkan Login kembali..!!";
		}
	} catch (Exception $ex) {
		$stData = "Failed => ".$ex->getMessage();
	}

	print json_encode($stData);
}
if($aksiPost == "searchDataVoucher")
{
	$trNya = "";
	$txtSearch = $_POST['txtSearch'];

	$trNya = $CPaymentAdv->getDataVoucher("search",$txtSearch);

	print json_encode($trNya);
}
if($aksiPost == "ketikAutoComplSenderByCode")
{
	$paramPost = str_replace("'", "''", $_POST['param']);
	$slcAuto = "";
	$idRow = $_POST['idRow'];

	$query = $koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "SELECT Acctcode, RTRIM(AcctIndo) AS AcctIndo, RTRIM(Acctname) AS Acctname FROM AccountCode WHERE Acctcode LIKE '%".$paramPost."%' AND (LEN(RTRIM(Acctcode)) = 5) AND ((LEFT(Acctcode, 2) = '11' OR LEFT(Acctcode, 2) = '15' OR LEFT(Acctcode, 2) = '01' OR LEFT(Acctcode, 2) = '17') OR (Acctcode >= '50000')) AND acctcode != '99999' ORDER BY Acctcode ASC;");
	while($row = $koneksiOdbcAcc->odbcFetch($query))
	{
		$slcAuto .= "<li onclick=\"dataSelectAutoComplete('".$row['Acctcode']."','".$idRow."')\" style=\"background-color:#DDDDDD;cursor:pointer;\">".$row['Acctcode']." - ".$row['Acctname']."</li>";
	}
	print json_encode($slcAuto);
}
if($aksiPost == "saveDataReject")
{
	$stData = "";
	$data = $_POST;
	$dateNow = date('Y-m-d');
	$userId = $userIdSession;
	$slcUnitName = "";
	$slcComName = "";

	$rejectName = $data['rejectName'];

	$idReject = $data['idReject'];
	$remarkReject = $data['txtRemarkReject'];
	$stSubmit = "N";
	$stConfirm = "N";
	$stReject = "Y";
	$stCheck = "N";
	$stApprove = "N";

	try {
		if($userId != "")
		{
			if($rejectName == "confirm")
			{
				$CkoneksiPaymentAdv->mysqlQuery("UPDATE datapayment SET reject_status = '".$stReject."',reject_userId = '".$userId."',reject_date = '".$dateNow."',reject_remark = '".$remarkReject."',st_submit = '".$stSubmit."' WHERE id IN(".$idReject.") AND st_delete = '0' ");
			}
			if($rejectName == "checked")
			{
				$CkoneksiPaymentAdv->mysqlQuery("UPDATE datapayment SET reject_status = '".$stReject."',reject_userId = '".$userId."',reject_date = '".$dateNow."',reject_remark = '".$remarkReject."',st_confirm = '".$stConfirm."',st_submit = '".$stSubmit."' WHERE id IN(".$idReject.") AND st_delete = '0' ");
			}
			if($rejectName == "approve")
			{
				$idNya = "";
				$idSettleNya = "";
				$idReject = $data['idReject'];
				$idRejectSettle = $data['idRejectSettle'];

				$arrTempIdChecked = explode(",",$idReject);
				$arrTempIdCheckedSettle = explode(",",$idRejectSettle);

				if(count($arrTempIdChecked) > 0)
				{
					for ($lan=0; $lan < count($arrTempIdChecked); $lan++)
					{
						if($idNya == "")
						{
							$idNya = "'".$arrTempIdChecked[$lan]."'";
						}else{
							$idNya .= ",'".$arrTempIdChecked[$lan]."'";
						}
					}

					$CkoneksiPaymentAdv->mysqlQuery("UPDATE datapayment SET reject_status = '".$stReject."',reject_userId = '".$userId."',reject_date = '".$dateNow."',reject_remark = '".$remarkReject."',st_check = '".$stCheck."' WHERE id IN(".$idNya.") AND st_delete = '0' ");
				}

				if(count($arrTempIdCheckedSettle) > 0)
				{
					for ($lan=0; $lan < count($arrTempIdCheckedSettle); $lan++)
					{
						if($idSettleNya == "")
						{
							$idSettleNya = "'".$arrTempIdCheckedSettle[$lan]."'";
						}else{
							$idSettleNya .= ",'".$arrTempIdCheckedSettle[$lan]."'";
						}
					}

					$CkoneksiPaymentAdv->mysqlQuery("UPDATE datapayment SET reject_status = '".$stReject."',reject_userId = '".$userId."',reject_date = '".$dateNow."',reject_remark = '".$remarkReject."',st_settlementCheck = '".$stCheck."' WHERE id IN(".$idSettleNya.") AND st_delete = '0' ");
				}				
			}
			if($rejectName == "release")
			{
				$idNya = "";
				$idSettleNya = "";
				$idReject = $data['idReject'];
				$idRejectSettle = $data['idRejectSettle'];

				$arrTempIdChecked = explode(",",$idReject);
				$arrTempIdCheckedSettle = explode(",",$idRejectSettle);

				if(count($arrTempIdChecked) > 0)
				{
					for ($lan=0; $lan < count($arrTempIdChecked); $lan++)
					{
						if($idNya == "")
						{
							$idNya = "'".$arrTempIdChecked[$lan]."'";
						}else{
							$idNya .= ",'".$arrTempIdChecked[$lan]."'";
						}
					}

					$CkoneksiPaymentAdv->mysqlQuery("UPDATE datapayment SET reject_status = '".$stReject."',reject_userId = '".$userId."',reject_date = '".$dateNow."',reject_remark = '".$remarkReject."',st_check = '".$stCheck."',st_approve	= '".$stApprove."' WHERE id IN(".$idNya.") AND st_delete = '0' ");
				}
				if(count($arrTempIdCheckedSettle) > 0)
				{
					for ($lan=0; $lan < count($arrTempIdCheckedSettle); $lan++)
					{
						if($idSettleNya == "")
						{
							$idSettleNya = "'".$arrTempIdCheckedSettle[$lan]."'";
						}else{
							$idSettleNya .= ",'".$arrTempIdCheckedSettle[$lan]."'";
						}
					}

					$CkoneksiPaymentAdv->mysqlQuery("UPDATE datapayment SET reject_status = '".$stReject."',reject_userId = '".$userId."',reject_date = '".$dateNow."',reject_remark = '".$remarkReject."',st_settlementCheck = '".$stCheck."',st_settlementApprove	= '".$stApprove."' WHERE id IN(".$idSettleNya.") AND st_delete = '0' ");
				}
			}

		    $stData = "Success..!!";
		}else{
			$stData = "Session Time Out, Silahkan Login kembali..!!";
		}
	} catch (Exception $ex) {
		$stData = $ex->getMessage();
	}

	print json_encode($stData);
}
if($aksiPost == "saveDataPaymentRequestUploadFile")
{
	$stData = "";
	$data = $_POST;
	$dateNow = date('Y-m-d');
	$userId = $userIdSession;
	$dir = "./templates/fileUploadBukti";

	$idEdit = $data['idEdit'];
	$cekFile = $data['cekFile'];
	$txtRemark = $data['txtRemark'];

	try {

		if($userId != "")
		{
			if($cekFile != "")
		    {
		        $tmpFile = $_FILES['fileData']['tmp_name'];
		        $fileName = $_FILES['fileData']['name'];
		        
		        $newFileName = "buktiFile_".$idEdit;

		        $dt = explode(".", $fileName);
		        $newFileName = str_replace(array(' ','/','.',',','-'), '', $newFileName).".".trim($dt[count($dt)-1]);

		        move_uploaded_file($tmpFile, $dir."/".$fileName);
		        rename($dir."/".$fileName, $dir."/".$newFileName);		    

				$CkoneksiPaymentAdv->mysqlQuery("UPDATE datapayment SET st_bukti = 'Y',bukti_remark = '".$txtRemark."',bukti_userId = '".$userId."',bukti_date = '".$dateNow."',bukti_file='".$newFileName."' WHERE id='".$idEdit."' AND st_delete = '0' ");
			
		    	$stData = "Success..!!";
			}
		}else{
			$stData = "Session Time Out, Please Login Again..!!";
		}
	} catch (Exception $ex) {
		$stData = $ex->getMessage();
	}

	print json_encode($stData);
}
if($aksiPost == "saveDataPaymentRequestSettlement")
{
	$stData = "";
	$data = $_POST;
	$dateNow = date('Y-m-d');
	$userId = $userIdSession;
	$dir = "./templates/fileUploadBukti";

	$idEdit = $data['idEdit'];
	$cekFile = $data['cekFile'];
	$txtExpense = str_replace(",","",$data['txtExpense']);
	$txtRemark = $data['txtRemark'];

	try {

		if($userId != "")
		{
			if($cekFile != "")
		    {
		        $tmpFile = $_FILES['fileData']['tmp_name'];
		        $fileName = $_FILES['fileData']['name'];
		        
		        $newFileName = "settlement_".$idEdit;

		        $dt = explode(".", $fileName);
		        $newFileName = str_replace(array(' ','/','.',',','-'), '', $newFileName).".".trim($dt[count($dt)-1]);

		        move_uploaded_file($tmpFile, $dir."/".$fileName);
		        rename($dir."/".$fileName, $dir."/".$newFileName);		    

				$CkoneksiPaymentAdv->mysqlQuery("UPDATE datapayment SET st_settlement = 'Y',settlement_amount = '".$txtExpense."',settlement_file = '".$newFileName."',settlement_remark = '".$txtRemark."',settlement_addUserId = '".$userId."',settlement_addDate='".$dateNow."' WHERE id='".$idEdit."' AND st_delete = '0' ");
			
		    	$stData = "Success..!!";
			}
		}else{
			$stData = "Session Time Out, Please Login Again..!!";
		}
	} catch (Exception $ex) {
		$stData = $ex->getMessage();
	}

	print json_encode($stData);
}
if($aksiPost == "saveDataPaymentRequestSettlementUpload")
{
	$stData = "";
	$data = $_POST;
	$dateNow = date('Y-m-d');
	$userId = $userIdSession;
	$dir = "./templates/fileUploadBukti";

	$idPaymentAdv = $data['idPaymentAdv'];
	$cekFile = $data['cekFile'];

	try {
		if($userId != "")
		{
			if($cekFile != "")
		    {
		        $tmpFile = $_FILES['fileData']['tmp_name'];
		        $fileName = $_FILES['fileData']['name'];
		        $newFileName = "uploadSettlement_".$idPaymentAdv;
		        $dt = explode(".", $fileName);
		        $newFileName = str_replace(array(' ','/','.',',','-'), '', $newFileName).".".trim($dt[count($dt)-1]);
		        move_uploaded_file($tmpFile, $dir."/".$fileName);
		        rename($dir."/".$fileName, $dir."/".$newFileName);		    
				$CkoneksiPaymentAdv->mysqlQuery("UPDATE datapayment SET settlement_upload_file = '".$newFileName."',settlement_upload_userId = '".$userId."',settlement_upload_date='".$dateNow."' WHERE id='".$idPaymentAdv."' AND st_delete = '0' ");
		    	$stData = "Success..!!";
			}
		}else{
			$stData = "Session Time Out, Please Login Again..!!";
		}
	} catch (Exception $ex) {
		$stData = $ex->getMessage();
	}

	print json_encode($stData);
}
if($aksiPost == "getSearchData")
{
	$trNya = "";
	$typeSearch = $_POST['typeSearch'];
	$txtSearch = $_POST['txtSearch'];
	$sDate = $_POST['sDate'];
	$eDate = $_POST['eDate'];

	$trNya = $CPaymentAdv->getSearchData($typeSearch,$txtSearch,$sDate,$eDate,$userJenisPaymentAdv,$userIdSession);

	print json_encode($trNya);
}
if($aksiPost == "getSearchDataDetail")
{
	$trNya = "";
	$idPaymentAdv = $_POST['idPaymentAdv'];

	$trNya = $CPaymentAdv->getSearchDataDetail($idPaymentAdv);

	print json_encode($trNya);
}
if($aksiPost == "saveDetailSplit")
{
	$stData = "";
	$userId = $userIdSession;
	$data = $_POST;
	$typeDoc = $data['typeDoc'];
	$arrIdPaySplit = array();
	$arrRemark = array();

	$arrIdPaySplit = explode("^",$data['idPayDetSplit']);
	$arrRemark = explode("^",$data['remark']);

	try {

		if($userId != "")
		{
			if($typeDoc == "giveSettlement")
			{
				$tblNya = "payment_split_settlement";
			}else{
				$tblNya = "payment_split";				
			}
			
			for ($lan=0; $lan < count($arrIdPaySplit); $lan++)
			{
				if($arrIdPaySplit[$lan] != "-")
				{
					$CkoneksiPaymentAdv->mysqlQuery("UPDATE ".$tblNya." SET description = '".$arrRemark[$lan]."' WHERE id = ".$arrIdPaySplit[$lan]." AND st_delete = '0' ");
				}
			}
			
		    $stData = "Success..!!";
		}else{
			$stData = "Session Time Out, Silahkan Login kembali..!!";
		}
	} catch (Exception $ex) {
		$stData = "Failed => ".$ex->getMessage();
	}
	print json_encode($stData);
}

if($aksiPost == "searchDataChangeFile")
{
	$trNya = "";
	$txtBarcode = $_POST['txtBarcode'];

	$trNya = $CPaymentAdv->searchDataChangeFile($txtBarcode,$userIdSession,$userJenisPaymentAdv);

	print json_encode($trNya);
}

// AWAL PENAMBAHAN CODE
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['aksi']) && $_POST['aksi'] == 'getDataPaymentSplitById') {
    $result = array();
    $idPayment = $_POST['idPayment'];

    try {
        if (empty($idPayment)) {
            throw new Exception("ID Payment tidak diberikan.");
        }

        $result = $CPaymentAdv->getDataPaymentSplitById($idPayment);
    } catch (Exception $ex) {
        $result = array("status" => "error", "message" => $ex->getMessage());
    }

    echo json_encode($result);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['aksi']) && $_POST['aksi'] == 'getDataPaymentSplitSettlementById') {
    $idPayment = isset($_POST['idPayment']) ? $_POST['idPayment'] : '';

    try {
        if (empty($idPayment)) {
            throw new Exception("ID Payment Split Tidak Diberikan");
        }
        $result = $CPaymentAdv->getDataPaymentSplitSettlementById($idPayment);
        echo json_encode($result);
    } catch (Exception $e) {
        $result = array("status" => "error", "message" => $e->getMessage());
        echo json_encode($result);
    }
}

if ($aksiPost == "saveUpdateAmount") {
    $stData = "";
    $data = $_POST;
    $dateNow = date('Y-m-d');
    $userId = $userIdSession;
    $idEdit = $data['idEdit'];

    $idPaymentAdv = $data['idPayment'];
    $slcTypeEdit = $data['slcTypeEdit'];
    $txtAmount = str_replace(",", "", $data['txtAmount']);
    $typeDBCR1input = str_replace(",", "", $data['type_dbcr1_input']);
    $typeDBCR2input = str_replace(",", "", $data['type_dbcr2_input']);

    $idPaymentSplit1 = $data['idPaymentSplit1'];
    $idPaymentSplit2 = $data['idPaymentSplit2'];

    try {
        if ($userId != "") {
            if ($idEdit != "") {
                $CkoneksiPaymentAdv->mysqlQuery("UPDATE datapayment SET amount = '".$txtAmount."' WHERE id = '".$idEdit."' AND st_delete = '0'");

                if (!empty($idPaymentSplit1)) {
                    $CkoneksiPaymentAdv->mysqlQuery("UPDATE payment_split SET amount = '".$typeDBCR1input."' WHERE id_payment = '".$idPaymentAdv."' AND id = '".$idPaymentSplit1."' AND st_delete = '0'");
                }
                if (!empty($idPaymentSplit2)) {
                    $CkoneksiPaymentAdv->mysqlQuery("UPDATE payment_split SET amount = '".$typeDBCR2input."' WHERE id_payment = '".$idPaymentAdv."' AND id = '".$idPaymentSplit2."' AND st_delete = '0'");
                }

                $stData = array(
                    "status" => "success",
                    "message" => "Amount: (IDR) " . number_format($txtAmount, 2, ',', '.') . " updated successfully..!!",
                    "formattedAmount" => "(IDR) " . number_format($txtAmount, 2, ',', '.')
                );
            } else {
                $stData = array("status" => "error", "message" => "Session Time Out, Silahkan Login Kembali..!!");
            }
        }
    } catch (Exception $ex) {
        $stData = array("status" => "error", "message" => $ex->getMessage());
    }

    print json_encode($stData);
}

if ($aksiPost == "saveUpdateSettlementAmount") {
    $stData = "";
    $data = $_POST;
    $dateNow = date('Y-m-d');
    $userId = $userIdSession;
    $idEdit = $data['idEdit'];

    $idPaymentAdv = $data['idPayment'];
    $slcTypeEdit = $data['slcTypeEdit'];
    $txtSettlementAmount = str_replace(",", "", $data['SettlementAmount']);

    try {
        if ($userId != "") {
            if ($idEdit != "") {

                $CkoneksiPaymentAdv->mysqlQuery("UPDATE datapayment SET settlement_amount = '".$txtSettlementAmount."' WHERE id = '".$idEdit."' AND st_delete = '0'");

                foreach ($data as $key => $value) {
                    if (strpos($key, 'txtIdPaymentSplitSettlement') !== false) {
                        $idPaymentSplitSettlement = $value;
                        $amountKey = str_replace('txtIdPaymentSplitSettlement', 'txtAmountSettle', $key);
                        $amount = str_replace(",", "", $data[$amountKey]);

                        $CkoneksiPaymentAdv->mysqlQuery("UPDATE payment_split_settlement SET amount = '".$amount."' WHERE id_payment = '".$idPaymentAdv."' AND id = '".$idPaymentSplitSettlement."' AND st_delete = '0'");
                    }
                }

                $stData = array(
                    "status" => "success",
                    "message" => "Settlement Amount: (IDR) " . number_format($txtSettlementAmount, 2, ',', '.') . " updated successfully..!!",
                    "formattedAmount" => "(IDR) " . number_format($txtSettlementAmount, 2, ',', '.')
                );
            } else {
                $stData = array("status" => "error", "message" => "Session Time Out, Silahkan Login Kembali..!!");
            }
        }
    } catch (Exception $ex) {
        $stData = array("status" => "error", "message" => $ex->getMessage());
    }

    header('Content-Type: application/json');
    echo json_encode($stData);
}

if ($aksiPost == "updategroup") {
    $stData = "";
    $data = $_POST;
    $dateNow = date('Y-m-d');
    $userId = $userIdSession;
    $idEdit = $data['idEdit'];

    $idPaymentAdv = $data['idPayment'];
    $transNo = isset($data['transNo']) ? $data['transNo'] : "";

    try {
        if ($userId != "") {
            if ($idEdit != "") {
                // Update transNo in datapayment table
                $CkoneksiPaymentAdv->mysqlQuery("UPDATE datapayment SET transno = '".$transNo."' WHERE id = '".$idPaymentAdv."' AND st_delete = '0'");

                $stData = array(
                    "status" => "success",
                    "message" => "TransNo: " . htmlspecialchars($transNo) . " updated successfully..!!",
                    "transNo" => htmlspecialchars($transNo)
                );
            } else {
                $stData = array("status" => "error", "message" => "Session Time Out, Silahkan Login Kembali..!!");
            }
        }
    } catch (Exception $ex) {
        $stData = array("status" => "error", "message" => $ex->getMessage());
    }

    header('Content-Type: application/json');
    echo json_encode($stData);
}

if($aksiPost == "updategroupsettlement") {
    $stData = "";
    $data = $_POST;
    $dateNow = date('Y-m-d');
    $userId = $userIdSession;
    $idEdit = $data['idEdit'];

    $idPaymentAdv = $data['idPayment'];
    $settlementTransNo = isset($data['settlementTransNo']) ? $data['settlementTransNo'] : "";

    try {
        if ($userId != "") {
            if ($idEdit != "") {
                // Update transNo in datapayment table
                $CkoneksiPaymentAdv->mysqlQuery("UPDATE datapayment SET settlement_transno = '".$settlementTransNo."' WHERE id = '".$idPaymentAdv."' AND st_delete = '0'");

                $stData = array(
                    "status" => "success",
                    "message" => "TransNo: " . htmlspecialchars($settlementTransNo) . " updated successfully..!!",
                    "settlementtransno" => htmlspecialchars($settlementTransNo)
                );
            } else {
                $stData = array("status" => "error", "message" => "Session Time Out, Silahkan Login Kembali..!!");
            }
        }
    } catch (Exception $ex) {
        $stData = array("status" => "error", "message" => $ex->getMessage());
    }

    header('Content-Type: application/json');
    echo json_encode($stData);
}

if ($aksiPost == "saveUpdateTypeData") {
    $stData = "";
    $data = $_POST;
    $dateNow = date('Y-m-d');
    $userId = $userIdSession;
    $idEdit = $data['idEdit'];

    $idPaymentAdv = $data['idPayment'];
    $slcTypeEdit = $data['slcTypeEdit']; 
    $docType = isset($data['docType']) ? $data['docType'] : '';

    try {
        if ($userId != "") {
            if ($idEdit != "") {
                $query = "UPDATE datapayment SET doc_type = '".$slcTypeEdit."'";
                if ($slcTypeEdit == 'tipedata' && !empty($docType)) {
                    $query .= ", doc_type = '".$docType."'";
                }
                $query .= " WHERE id = '".$idPaymentAdv."' AND st_delete = '0'";
                $CkoneksiPaymentAdv->mysqlQuery($query);
                $stData = array(
                    "status" => "success",
                    "message" => "Data has been successfully updated.",
                    "docType" => $docType
                );
            } else {
                $stData = array("status" => "error", "message" => "Session Time Out, Silahkan Login Kembali..!!");
            }
        }
    } catch (Exception $ex) {
        $stData = array("status" => "error", "message" => $ex->getMessage());
    }

    header('Content-Type: application/json');
    echo json_encode($stData);
}

if ($aksiPost == "saveUpdateCompany") {
    $stData = "";
    $data = $_POST;
    $dateNow = date('Y-m-d');
    $userId = $userIdSession;
    $idEdit = $data['idEdit'];

    $idPaymentAdv = $data['idPayment'];
    $slcTypeEdit = $data['slcTypeEdit']; 
    $companyname = $data['companyName'];
	$initCompany = $data['initCompany'];

    try {
        if ($userId != "") {
            if ($idEdit != "") {
                $query = "UPDATE datapayment SET company_name = '".$companyname."', init_company  = '".$slcTypeEdit."' ";
				if($slcTypeEdit == 'updatecompany' && !empty($initCompany) && !empty($companyname)){
					$query .= ", company_name = '".$companyname."', init_company = '".$initCompany."'";
				}
				$query .= " WHERE id = '".$idPaymentAdv."' AND st_delete = '0'";

                $CkoneksiPaymentAdv->mysqlQuery($query);
                $stData = array(
                    "status" => "success",
                    "message" => "Data has been successfully updated.",
                    "companyname" => $companyname,
					"initCompany" => $initCompany
                );
            } else {
                $stData = array("status" => "error", "message" => "Session Time Out, Silahkan Login Kembali..!!");
            }
        }
    } catch (Exception $ex) {
        $stData = array("status" => "error", "message" => $ex->getMessage());
    }

    header('Content-Type: application/json');
    echo json_encode($stData);
}
// AKHIR PENAMBAHAN CODE

if($aksiPost == "saveUploadChangeFile")
{
	$stData = "";
	$data = $_POST;
	$dateNow = date('Y-m-d');
	$userId = $userIdSession;
	$dir = "./templates/fileUploadBukti";

	$idPaymentAdv = $data['idPayment'];
	$cekFile = $data['cekFile'];
	$slcTypeFile = $data['slcTypeFile'];
	$setNya = "";

	try {

		if($userId != "")	
		{
			if($cekFile != "")
		    {
		    	$tmpFile = $_FILES['fileData']['tmp_name'];
		        $fileName = $_FILES['fileData']['name'];
		        $dt = explode(".", $fileName);

		    	if($slcTypeFile == "pengajuan")
		    	{
		    		$dir = "./templates/fileUpload";
			        $newFileName = "filePaymentRequest_".$idPaymentAdv;
			        $newFileName = str_replace(array(' ','/','.',',','-'), '', $newFileName).".".trim($dt[count($dt)-1]);

			        move_uploaded_file($tmpFile, $dir."/".$fileName);
		        	rename($dir."/".$fileName, $dir."/".$newFileName);

			        $setNya = "file_upload = '".$newFileName."' ";
		    	}
		    	else if($slcTypeFile == "buktitransfer")
		    	{
		    		$dir = "./templates/fileUploadBukti";
			        $newFileName = "buktiFile_".$idPaymentAdv;
			        $newFileName = str_replace(array(' ','/','.',',','-'), '', $newFileName).".".trim($dt[count($dt)-1]);

			        move_uploaded_file($tmpFile, $dir."/".$fileName);
		        	rename($dir."/".$fileName, $dir."/".$newFileName);

			        $setNya = "bukti_file = '".$newFileName."' ";
		    	}
		    	else if($slcTypeFile == "settlement")
		    	{
		    		$dir = "./templates/fileUploadBukti";
			        $newFileName = "settlement_".$idPaymentAdv;
			        $newFileName = str_replace(array(' ','/','.',',','-'), '', $newFileName).".".trim($dt[count($dt)-1]);

			        move_uploaded_file($tmpFile, $dir."/".$fileName);
		        	rename($dir."/".$fileName, $dir."/".$newFileName);

			        $setNya = "settlement_file = '".$newFileName."' ";
		    	}
		    	else if($slcTypeFile == "uploadSettlement")
		    	{
		    		$dir = "./templates/fileUploadBukti";
			        $newFileName = "uploadSettlement_".$idPaymentAdv;
			        $newFileName = str_replace(array(' ','/','.',',','-'), '', $newFileName).".".trim($dt[count($dt)-1]);

			        move_uploaded_file($tmpFile, $dir."/".$fileName);
		        	rename($dir."/".$fileName, $dir."/".$newFileName);

			        $setNya = "settlement_upload_file = '".$newFileName."' ";
		    	}

		        if($setNya != "")
		        {
					$CkoneksiPaymentAdv->mysqlQuery("UPDATE datapayment SET ".$setNya." WHERE id='".$idPaymentAdv."' AND st_delete = '0' ");
					
			    	$stData = "Success..!!";
			    }
			}
		}else{
			$stData = "Session Time Out, Please Login Again..!!";
		}
	} catch (Exception $ex) {
		$stData = $ex->getMessage();
	}

	print json_encode($stData);
}


?>