<?php
require_once("configVoucher.php");
require_once($pathInvReg.'/class/CNumberWordsInd.php');


if($aksiPost == "klikPayMethod")
{
	$payMethodPost = $_POST['payMethod'];
	$lastSelBankPost = $_POST['lastSelBank'];
	
	$html = "";
	$html.= "<select id=\"bankCode\" name=\"bankCode\" class=\"elementMenu\" style=\"width:312px;height:20px;\" onchange=\"klikBankMenu(bankCode);return false;\">";
	$html.= "<option value=\"XXX\">-- PLEASE SELECT  --</option>";
	if($payMethodPost == "cash")
	{
		$query = $koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "SELECT RTRIM(Acctcode) AS Acctcode, RTRIM(Acctname) AS Acctname, source FROM dbo.AccountCode WHERE (LEN(RTRIM(Acctcode)) = 5) AND (LEFT(Acctcode, 2) = '10') AND (Contact IS NULL) AND Acctcode='10000' ORDER BY Acctname");
	}
	else
	{
		$query = $koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "SELECT RTRIM(Acctcode) AS Acctcode, RTRIM(Acctname) AS Acctname, source FROM dbo.AccountCode WHERE ((LEN(RTRIM(Acctcode)) = 5) AND (LEFT(Acctcode, 2) = '10') AND (Contact IS NULL) AND (Acctcode <> '10000')) OR Acctcode='10011' ORDER BY Acctname");
	}
		
	while($row = $koneksiOdbcAcc->odbcFetch($query))
	{
		$acctCode = rtrim($row['Acctcode']);
		
		$sel = "";
		if($lastSelBankPost != "")
		{
			if($lastSelBankPost == $acctCode)
			{	$sel = "selected";	}
		}
		$html.= "<option value=\"".$acctCode."\" ".$sel.">".$acctCode."&nbsp;-&nbsp;".$row['Acctname']."&nbsp;(&nbsp;".$row['source']."&nbsp;)</option>";
	}
	
	$html.= "</select>";
	
	echo $html;
}

if($aksiPost == "ketikAmount")
{
	$html = "";
	
	$expAmount = explode(".", $_POST['amountAngka']);
	$amountDepanKoma = $expAmount[0];
	$amountBlkgKoma = $expAmount[1];
	
	$amountAngka1 = str_replace(",","",$amountDepanKoma);
	//$amountAngka1 = $amountBlkgKoma;
	//$amountAngkaPostt = str_replace(".","",$amountAngkaPost);
	
	$CNumberWordsInd = new CNumberWordsInd("", false);
	$nilai1 = $CNumberWordsInd->convert($amountAngka1);
	$nilai2 = $CNumberWordsInd->convert($amountBlkgKoma);
	
	$nilai = $nilai1;
	if($nilai2 != "nol")
	{	$nilai = $nilai1." dan ".$nilai2;	}
	
	echo "<p>".strtoupper($nilai." saja")."</p>";
}

if($aksiPost == "ubahUrutan")
{
	$reqPost = $_POST['req'];
	$idDescPost = $_POST['idDesc'];
	
	$CVoucher->ubahUrutan($reqPost, $idDescPost);
}

if($aksiPost == "cekBarcode")
{
	$dataOut = array();
	$statusNya = "";
	$invNo = "";
	$barcodeNo = trim($_POST['barcodeNo']);

	$query = $CKoneksiInvReq->mysqlQuery("SELECT mailinvno FROM mailinvoice WHERE barcode = '".$barcodeNo."' AND deletests=0;", $CKoneksiInvReq->bukaKoneksi());
	while($row = $CKoneksiInvReq->mysqlFetch($query))
	{
		$invNo = $row['mailinvno'];
		if(strtoupper(substr($barcodeNo, 0,1)) == "S")
		{
			$invNo = $barcodeNo;
		}
		$statusNya = "ada";
	}
	
	$dataOut['invNo'] = $invNo;
	$dataOut['statusNya'] = $statusNya;

	print json_encode($dataOut);
}

if($aksiPost == "searchBatchNo")
{
	$dataOut = array();
	$trNya = "";
	$batchno = trim($_POST['batchno']);
	$no = 1;

	$sql = "SELECT * FROM tblvoucher WHERE deletests=0 AND trfacct = 'Y' AND batchno LIKE '%".$batchno."%' ORDER BY batchno ASC;";
	$query = $CKoneksiVoucher->mysqlQuery($sql, $CKoneksiVoucher->bukaKoneksi());

	while($row = $CKoneksiVoucher->mysqlFetch($query))
	{
		$btnAct = "<button class=\"btnStandar\" onClick=\"cancelVoucher('".$row['idvoucher']."');\" style=\"margin-left:10px;cursor:pointer;\">Cancel</button>";

		$datePaid = $CPublic->convTglPO($row['datepaid']);

		$trNya .= "<tr>";
			$trNya .= "<td align=\"center\" style=\"font-size:11px;width:20px;padding:5px;\">".$no."</td>";
			$trNya .= "<td align=\"center\" style=\"font-size:11px;width:100px;padding:5px;\">".$CPublic->zerofill($row['batchno'],6)."</td>";
			$trNya .= "<td style=\"font-size:11px;width:200px;padding:5px;\">".$row['kepada']."</td>";
			$trNya .= "<td style=\"font-size:11px;width:150px;padding:5px;\">".$row['companyname']."</td>";
			$trNya .= "<td align=\"center\" style=\"font-size:11px;width:70px;padding:5px;\">".$datePaid."</td>";
			$trNya .= "<td align=\"right\" style=\"font-size:11px;width:100px;padding:5px;\">".number_format($row['amount'],2)."</td>";
			$trNya .= "<td align=\"center\" style=\"font-size:11px;width:25px;padding:5px;\">".$btnAct."</td>";
		$trNya .= "</tr>";

		$no++;
	}
	
	$dataOut['trNya'] = $trNya;

	print json_encode($dataOut);
}

if($aksiPost == "cancelVoucherByBatchNo")
{
	$CKoneksiVoucher->bukaKoneksi();
	$statusNya = "";
	$idVoucher = trim($_POST['idVoucher']);

	$CKoneksiVoucher->mysqlQuery("UPDATE tblvoucher SET trfacct = 'N',trfacctby = '',trfacctdate = '0000-00-00' WHERE idvoucher='".$idVoucher."' AND deletests=0");
	$statusNya = "Cancel Success..!!";

	print json_encode($statusNya);
}

if($aksiPost == "getVoyageNo")
{
	$thn = $_POST['thn'];
	$vslCode = $_POST['vsl'];
	$voyNya = $_POST['voyNya'];

	$dt = explode("/", $thn);

	$dataNya = $CVoucher->getVoyageNo($voyNya,$dt[2],$vslCode);
	print json_encode($dataNya);
}

if($aksiPost == "uploadFileBuktiTransferVoucher")
{
	$stData = "";
	$data = $_POST;
	$dateNow = date('Y-m-d');
	$userId = $userIdSession;
	$dir = "./templates/fileBuktiBayar";

	$idVoucher = $data['idVoucher'];
	$cekFile = $data['cekFile'];

	try {

		if($userId != "")
		{
			if($cekFile != "")
		    {
		    	$CKoneksiVoucher->bukaKoneksi();
		        $tmpFile = $_FILES['fileData']['tmp_name'];
		        $fileName = $_FILES['fileData']['name'];
		        
		        $newFileName = "buktiFile_".$idVoucher;

		        $dt = explode(".", $fileName);
		        $newFileName = str_replace(array(' ','/','.',',','-'), '', $newFileName).".".trim($dt[count($dt)-1]);

		        move_uploaded_file($tmpFile, $dir."/".$fileName);
		        rename($dir."/".$fileName, $dir."/".$newFileName);		    

				$CKoneksiVoucher->mysqlQuery("UPDATE tblvoucher SET buktibayar_file ='".$newFileName."' WHERE idvoucher='".$idVoucher."' AND deletests = '0' ");
			
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

if($aksiPost == "approveVoucher")
{	
	$CKoneksiVoucher->bukaKoneksi();
	$statusNya = "";
	$idVoucher = trim($_POST['idVoucher']);
	//$userId = $userIdSession;
	$aprvUserId = $userIdSession."/".$userWhoAct;

	$CKoneksiVoucher->mysqlQuery("UPDATE tblvoucher SET approve_voucher = 'Y',add_approve = '".$aprvUserId."' WHERE idvoucher='".$idVoucher."' AND deletests=0");

	$statusNya = "Approve Success..!!";

	print json_encode($statusNya);
}

if($aksiPost == "unApproveDataVoucher")
{	
	$CKoneksiVoucher->bukaKoneksi();
	$statusNya = "";
	$idVoucher = trim($_POST['idVoucher']);

	$CKoneksiVoucher->mysqlQuery("UPDATE tblvoucher SET approve_voucher = 'N',add_approve = '' WHERE idvoucher='".$idVoucher."' AND deletests=0");

	$statusNya = "Un Approve Success..!!";

	print json_encode($statusNya);
}

if($aksiPost == "cekStatusApprove")
{
	$dataOut = array();
	$idVoucher = trim($_POST['idVoucher']);
	$displayBtn = "N";

	$sql = "SELECT approve_voucher FROM tblvoucher WHERE deletests=0 AND idvoucher='".$idVoucher."' ";
	$query = $CKoneksiVoucher->mysqlQuery($sql, $CKoneksiVoucher->bukaKoneksi());
	$rows = $CKoneksiVoucher->mysqlFetch($query);

	if($userJenis == "admin")
	{
		$displayBtn = "Y";
	}else{
		if($userName == "marita")
		{
			$displayBtn = "Y";
		}		
	}
	$dataOut['statusNya'] = $rows['approve_voucher'];
	$dataOut['displayBtn'] = $displayBtn;

	print json_encode($dataOut);
}


?>