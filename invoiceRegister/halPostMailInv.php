<?php
require_once("configInvReg.php");

if($aksiPost == "pilihMenuThnBln")
{
	$thnBlnPost = $_POST['thnBln'];	
	$tglPost = $_POST['tgl'];

	$nilaiBatchnoThnBlnAwal = $CInvReg->nilaiBatchnoThnBlnAwal();
	$tglSek = $CPublic->zerofill($CPublic->waktuServer("tanggal"), 2);
	$cariBatchnoSamaDateSekarang = cariBatchnoSamaDateSekarang($CKoneksiInvReg, $thnBlnPost.$tglSek);
	
	$html = "";
	$html.="<select class=\"elementMenu\" id=\"menuBatchnoTgl\" name=\"menuBatchnoTgl\" style=\"width:45px;height:26px;font-size:12px;background-color: #f5f5f5;\" title=\"CHOOSE DATE\" onchange=\"rubahMenuBatchnoTgl()\">";
	
	$htmlTglSek = "";
	if($thnBlnPost == $nilaiBatchnoThnBlnAwal) // JIKA TAHUNBULAN BATCHNO YANG DIKIRIM SAMA DENGAN TAHUNBULAN BATCHNO PALING AWAL DIDATABASE
	{
		if($cariBatchnoSamaDateSekarang == "kosong") // JIKA TIDAK ADA MAKA TAMPILKAN TGL HARI INI
		{
			$htmlTglSek = "<option value=\"".$tglSek."\">".$tglSek."</option>";
		}
		if($cariBatchnoSamaDateSekarang == "ada")
		{
			$htmlTglSek = "";
		}
	}
	
	$html.= $htmlTglSek;
	$query = $CKoneksiInvReg->mysqlQuery("SELECT DISTINCT(SUBSTR(batchno, 7, 2)) AS tgl, SUBSTR(batchno, 1, 6) AS thnbln, batchno FROM mailinvoice WHERE SUBSTR(batchno, 1, 6)='".$thnBlnPost."' AND deletests=0 ORDER BY tgl DESC;", $CKoneksiInvReg->bukaKoneksi());
	while($row = $CKoneksiInvReg->mysqlFetch($query))
	{
		$sel = "";
		if($tglPost == $row['tgl'])
		{
			$sel = "selected";
		}
		$html.="<option value=\"".$row['tgl']."\" ".$sel.">".$row['tgl']."</option>";
	}
	
	$html.= "</select>&nbsp;";
	
	echo $html;
}

if($aksiPost == "pilihMenuThnBlnOutgoing")
{
	$thnBlnPost = $_POST['thnBln'];	
	$tglPost = $_POST['tgl'];

	$nilaiBatchnoThnBlnAwal = $CInvReg->nilaiBatchnoThnBlnAwalOutgoing();
	$tglSek = $CPublic->zerofill($CPublic->waktuServer("tanggal"), 2);
	$cariBatchnoSamaDateSekarang = cariBatchnoSamaDateSekarangOutgoing($CKoneksiInvReg, $thnBlnPost.$tglSek);
	
	$html = "";
	$html.="<select class=\"elementMenu\" id=\"menuBatchnoTgl\" name=\"menuBatchnoTgl\" style=\"width:45px;height:26px;font-size:12px;background-color: #f5f5f5;\" title=\"CHOOSE DATE\" onchange=\"rubahMenuBatchnoTgl()\">";
	
	$htmlTglSek = "";
	if($thnBlnPost == $nilaiBatchnoThnBlnAwal) // JIKA TAHUNBULAN BATCHNO YANG DIKIRIM SAMA DENGAN TAHUNBULAN BATCHNO PALING AWAL DIDATABASE
	{
		if($cariBatchnoSamaDateSekarang == "kosong") // JIKA TIDAK ADA MAKA TAMPILKAN TGL HARI INI
		{
			$htmlTglSek = "<option value=\"".$tglSek."\">".$tglSek."</option>";
		}
		if($cariBatchnoSamaDateSekarang == "ada")
		{
			$htmlTglSek = "";
		}
	}
	
	$html.= $htmlTglSek;
	$query = $CKoneksiInvReg->mysqlQuery("SELECT DISTINCT(SUBSTR(batchno, 7, 2)) AS tgl, SUBSTR(batchno, 1, 6) AS thnbln, batchno FROM outgoinginvoice WHERE SUBSTR(batchno, 1, 6)='".$thnBlnPost."' AND deletests=0 ORDER BY tgl DESC;", $CKoneksiInvReg->bukaKoneksi());
	while($row = $CKoneksiInvReg->mysqlFetch($query))
	{
		$sel = "";
		if($tglPost == $row['tgl'])
		{
			$sel = "selected";
		}
		$html.="<option value=\"".$row['tgl']."\" ".$sel.">".$row['tgl']."</option>";
	}
	
	$html.= "</select>&nbsp;";
	
	echo $html;
}

function cariBatchnoSamaDateSekarangOld($CKoneksiInvReg, $thnBln) // CARI APAKAH BATHCNO TAHUN+BULAN YANG DIPILIH SAMA DENGAN BATHCNO TAHUN_BULAN SEKARANG
{
	$nilai = "kosong";
	$query = $CKoneksiInvReg->mysqlQuery("SELECT DISTINCT(batchno) FROM mailinvoice WHERE CONCAT(YEAR(NOW()), DATE_FORMAT(NOW(), '%m')) = ".$thnBln." AND deletests=0;", $CKoneksiInvReg->bukaKoneksi());		
	$jmlRow = $CKoneksiInvReg->mysqlNRows($query);
	if($jmlRow != 0)
		$nilai = "ada";

	return $nilai;
}

function cariBatchnoSamaDateSekarang($CKoneksiInvReg, $thnBlnDate) // CARI APAKAH BATHCNO TAHUN+BULAN YANG DIPILIH SAMA DENGAN BATHCNO TAHUN_BULAN SEKARANG
{
	$nilai = "kosong";
	$query = $CKoneksiInvReg->mysqlQuery("SELECT * FROM mailinvoice WHERE batchno = ".$thnBlnDate." AND deletests=0;", $CKoneksiInvReg->bukaKoneksi());		
	$jmlRow = $CKoneksiInvReg->mysqlNRows($query);
	if($jmlRow != 0)
		$nilai = "ada";

	return $nilai;
}

function cariBatchnoSamaDateSekarangOutgoing($CKoneksiInvReg, $thnBlnDate) // CARI APAKAH BATHCNO TAHUN+BULAN YANG DIPILIH SAMA DENGAN BATHCNO TAHUN_BULAN SEKARANG
{
	$nilai = "kosong";
	$query = $CKoneksiInvReg->mysqlQuery("SELECT * FROM outgoinginvoice WHERE batchno = ".$thnBlnDate." AND deletests=0;", $CKoneksiInvReg->bukaKoneksi());		
	$jmlRow = $CKoneksiInvReg->mysqlNRows($query);
	if($jmlRow != 0)
		$nilai = "ada";

	return $nilai;
}

if($aksiPost == "cekBarcode")
{
	$barcodePost = $_POST['barcode'];
	
	$nilai = "kosong";
	$query = $CKoneksiInvReg->mysqlQuery("SELECT idmailinv FROM mailinvoice WHERE barcode='".$barcodePost."' AND deletests=0;", $CKoneksiInvReg->bukaKoneksi());
	$jmlRow = $CKoneksiInvReg->mysqlNRows($query);
	if($jmlRow != 0)
	{
		$nilai = "ada";
	}
	
	echo "<input type=\"hidden\" id=\"barcodeSamaAdaTidak\" value=\"".$nilai."\">";
}

if($aksiPost == "cekAckYesNo")
{
	$idMailInvPost = $_POST['idMailInv'];
	
	$nilai = "no";
	$query = $CKoneksiInvReg->mysqlQuery("SELECT idmailinv FROM mailinvoice WHERE idmailinv='".$idMailInvPost."' AND ack=1 AND deletests=0;", $CKoneksiInvReg->bukaKoneksi());
	$jmlRow = $CKoneksiInvReg->mysqlNRows($query);
	if($jmlRow != 0)
	{
		$nilai = "yes";
	}
	
	echo "<input type=\"hidden\" id=\"ackYesNo\" value=\"".$nilai."\" size=\"10\">";
}

if($aksiPost == "cekOutgoingAckYesNo")
{
	$idOutgoingInvPost = $_POST['idOutgoingInv'];
	
	$nilai = "no";
	$query = $CKoneksiInvReg->mysqlQuery("SELECT idoutgoinginv FROM outgoinginvoice WHERE idoutgoinginv='".$idOutgoingInvPost."' AND ack=1 AND deletests=0;", $CKoneksiInvReg->bukaKoneksi());
	$jmlRow = $CKoneksiInvReg->mysqlNRows($query);
	if($jmlRow != 0)
	{
		$nilai = "yes";
	}
	
	echo "<input type=\"hidden\" id=\"ackYesNo\" value=\"".$nilai."\" size=\"10\">";
}

if($aksiPost == "cekRetYesNo")
{
	$idMailInvPost = $_POST['idMailInv'];
	
	$nilai = "no";
	$query = $CKoneksiInvReg->mysqlQuery("SELECT idmailinv FROM mailinvoice WHERE idmailinv='".$idMailInvPost."' AND saveinvret='Y' AND deletests=0;", $CKoneksiInvReg->bukaKoneksi());
	$jmlRow = $CKoneksiInvReg->mysqlNRows($query);
	if($jmlRow != 0)
	{
		$nilai = "yes";
	}
	
	echo $nilai;
}

if($aksiPost == "isiDueDate")
{
	$invoiceDatePost = $_POST['invoiceDate'];
	$dueDayPost = $_POST['dueDay'];
	
	if($dueDayPost == "" || strlen($invoiceDatePost) < 10)
	{
		$dueDate = "";
	}
	else
	{
		$dueDate = $CPublic->convTglNonDB( $CPublic->intervalTanggal( $CPublic->convTglDB($invoiceDatePost), $dueDayPost-1) );
	}
	
	echo "<div id=\"divDueDate\" style=\"display:none;\">".$dueDate."</div>";
}

if($aksiPost == "isiDueDay")
{
	$invoiceDatePost = str_replace( "-","",$CPublic->convTglDB($_POST['invoiceDate']) );
	$dueDatePost = str_replace( "-","",$CPublic->convTglDB($_POST['dueDate']) );
	$dueDay = "";
	if($dueDatePost != "" || $invoiceDatePost != "")
		if($dueDatePost >= $invoiceDatePost)
			$dueDay = $CPublic->perbedaanHari($dueDatePost, $invoiceDatePost);
	
	echo "<div id=\"divDueDay\" style=\"display:none;\">".$dueDay."</div>";
}

if($aksiPost == "cekSudahAck")
{
	$idCekBoxPost = $_POST['idCekBox'];
	$expIdCekBox = explode("-",$idCekBoxPost);
	$idMailInv = $expIdCekBox[1];
	
	$statusAck = "N";
	$ackDB = $CInvReg->detilMailInv($idMailInv, "ack");
	if($ackDB == "1")
		$statusAck = "Y";
			
	echo $statusAck;
}


if($aksiPost == "cekAck")
{
	$nilaiCheckedPost = $_POST['nilaiChecked'];
	$halamanPost = $_POST['halaman'];
	$idCekBoxPost = $_POST['idCekBox'];
	$urutanPost = $_POST['urutan'];
	
	$expIdCekBox = explode("-",$idCekBoxPost);
	$idMailInv = $expIdCekBox[1];
	
	if($nilaiCheckedPost == "true") // JIKA AKAN BERI CENTANG ACKNOWLEDGE
	{
		$CKoneksiInvReg->mysqlQuery("UPDATE mailinvoice SET ack=1, dateack=NOW(), ackby='".$userIdLogin."', updusrdt='".$userWhoAct."' WHERE idmailinv=".$idMailInv." AND deletests=0 LIMIT 1;");
		$CHistory->updateLog2InvReg($userIdLogin, "BERI CENTANG ACKNOWLEDGE(ACK) Mail / Invoice (idmailinv=<b>".$idMailInv."</b>, ack=<b>YES</b>, updusrdt=<b>".$userWhoAct."</b>)");
		
		$checked = "checked";
		$valueSudahAck = "Y";
	}
	if($nilaiCheckedPost == "false") // JIKA AKAN MENGOSONGKAN ACKNOWLEDGE
	{
		$CKoneksiInvReg->mysqlQuery("UPDATE mailinvoice SET ack=0, dateack='0000-00-00', ackby='', updusrdt='".$userWhoAct."' WHERE idmailinv=".$idMailInv." AND deletests=0 LIMIT 1;");
		$CHistory->updateLog2InvReg($userIdLogin, "KOSONGKAN CENTANG ACKNOWLEDGE(ACK) Mail / Invoice (idmailinv=<b>".$idMailInv."</b>, ack=<b>NO</b>, updusrdt=<b>".$userWhoAct."</b>)");
		
		$checked = "";
		$valueSudahAck = "N";
	}	
	
	echo "<input type=\"checkbox\" id=\"".$idCekBoxPost."\" name=\"".$idCekBoxPost."\" class=\"elementMenu\" onClick=\"cekAck(this.id, this.checked, '".$halamanPost."', '".$urutanPost."');\" ".$checked."><input type=\"hidden\" id=\"sudahAck".$urutanPost."\" value=\"".$valueSudahAck."\">";
}

if($aksiPost == "cekKreditAcc")
{
	$kreditAccPost = $_POST['kreditAcc'];
	$idMailInvPost = $_POST['idMailInv'];
	
	$kreditAccDb = $CInvReg->detilMailInv($idMailInvPost, "sendervendor2");
	
	/*$query = $koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "SELECT RTRIM(Acctindo) as Acctindo, RTRIM(Acctname) as Acctname FROM AccountCode WHERE  SUBSTRING(Acctcode ,1,2) = '12' AND LEN(RTRIM(Acctcode))=5 AND Acctcode='".$kreditAccPost."'");
	$row = $koneksiOdbcAcc->odbcFetch($query);
	
	$acctName = $row['Acctname']; // ACCOUNT NAME (dalam inggris) YANG DIDAPAT BERDASARKAN KREDIT ACCOUNT YANG DIKIRIM*/
	
	$acctName = $CInvReg->detilAcctCode($kreditAccPost, "Acctname"); // ACCOUNT NAME (dalam inggris) YANG DIDAPAT BERDASARKAN KREDIT ACCOUNT YANG DIKIRIM
	//$senderVendor1 = $CInvReg->detilMailInv($idMailInvPost, "sendervendor1");
	
	// CEK ACCOUNT CODE DI DATABASE BERDASARKAN PARAMETER KREDIT ACCOUNT YANG DIKIRIM 
	$kreditAccAdaTidak = "kosong";
	if($acctName != "")
	{
		$kreditAccAdaTidak = "";
		$senderVendor1Sama = "tidaksama";
		/*if($acctName == $senderVendor1) // CEK APAKAH ACCOUNT NAME DI DATABASE SQL SERVER SAMA DENGAN FIELD SENDERVENDOR1 DI MYSQL SERVER
		{
			$senderVendor1Sama = "sama";
		}*/
		if($kreditAccPost == $kreditAccDb)// jika input Credit Acc = sendervendor 1
		{
			$senderVendor1Sama = "sama";
		}
	}
	
	echo "<input type=\"hidden\" id=\"kreditAccAdaTidak\" value=\"".$kreditAccAdaTidak.$senderVendor1Sama."\" style=\"width:200px\">";
}

if($aksiPost == "cekDebitAcc")
{
	$debitAccPost = $_POST['debitAcc'];
	$acctName = $CInvReg->detilAcctCode($debitAccPost, "Acctname");
	
	$debitAccAdaTidak = "kosong";
	if($acctName != "")
		$debitAccAdaTidak = "";
	echo "<input type=\"hidden\" id=\"debitAccAdaTidak\" value=\"".$debitAccAdaTidak."\" style=\"width:200px\">";
}

if($aksiPost == "tulisDebitAccName")
{
	$debitAccPost = $_POST['debitAcc'];
	//$query = $koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "SELECT RTRIM(Acctindo) as Acctindo, RTRIM(Acctname) as Acctname FROM AccountCode WHERE LEN(RTRIM(Acctcode))=5 AND Acctcode='".$debitAccPost."'");
	//$row = $koneksiOdbcAcc->odbcFetch($query);
	
	//$acctName = $row['Acctname']; // ACCOUNT NAME YANG DIDAPAT BERDASARKAN DEBIT ACCOUNT YANG DIKIRIM
	$acctName = $CInvReg->detilAcctCode($debitAccPost, "Acctname");
	if($acctName != "")
		echo "&nbsp;(&nbsp;".$acctName."&nbsp;)&nbsp;";
}

if($aksiPost == "tulisKreditAccName")
{
	$kreditAccPost = $_POST['kreditAcc'];
	
	//$query = $koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "SELECT RTRIM(Acctname) as Acctname FROM AccountCode WHERE SUBSTRING(Acctcode ,1,2) = '12' AND LEN(RTRIM(Acctcode))=5 AND Acctcode='".$kreditAccPost."'");
	//$query = $koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "SELECT RTRIM(Acctname) as Acctname FROM AccountCode WHERE LEN(RTRIM(Acctcode))=5 AND Acctcode='".$kreditAccPost."'");
	//$row = $koneksiOdbcAcc->odbcFetch($query);
	
	//$acctName = $row['Acctname']; // ACCOUNT NAME YANG DIDAPAT BERDASARKAN KREDIT ACCOUNT YANG DIKIRIM
	$acctName = $CInvReg->detilAcctCode($kreditAccPost, "Acctname");
	if($acctName != "")
		echo "&nbsp;(&nbsp;".$acctName."&nbsp;)&nbsp;";
}

if($aksiPost == "klikPayMethod")
{
	$payMethodPost = $_POST['payMethod'];
	//$lastSelBankPost = $_POST['lastSelBank'];
	
	$html = "";
	$html.= "<select id=\"bankCode\" name=\"bankCode\" class=\"elementMenu\" style=\"width:351px;\" onchange=\"klikBankMenu(bankCode);return false;\">";
	$html.= "<option value=\"XXX\">-- PLEASE SELECT  --</option>";
	if($payMethodPost == "cash")
	{
		$query = $koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "SELECT Acctcode, Acctname FROM dbo.AccountCode WHERE (LEN(RTRIM(Acctcode)) = 5) AND (LEFT(Acctcode, 2) = '10') AND (Contact IS NULL) AND Acctcode='10000' ORDER BY Acctname");
	}
	else
	{
		$query = $koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "SELECT Acctcode, Acctname FROM dbo.AccountCode WHERE (LEN(RTRIM(Acctcode)) = 5) AND (LEFT(Acctcode, 2) = '10') AND (Contact IS NULL) AND (Acctcode <> '10000') ORDER BY Acctname");
	}
		
	while($row = $koneksiOdbcAcc->odbcFetch($query))
	{
		$acctCode = rtrim($row['Acctcode']);
		
		/*$sel = "";
		if($lastSelBankPost != "")
			if($lastSelBankPost == $acctCode)
				$sel = "selected";*/
		
		$html.= "<option value=\"".$acctCode."\" ".$sel.">".$acctCode." - ".$row['Acctname']."</option>";
	}
	
	$html.= "</select>";
		
	echo $html;
}

if($aksiPost == "cekIdMailInvPrepPay")
{
	$idMailInvPost = $_POST['idMailInv'];
	
	$nilai = "kosong";
	$query = $CKoneksiInvReg->mysqlQuery("SELECT idmailinv FROM tblpreppay WHERE idmailinv='".$idMailInvPost."' AND deletests=0;", $CKoneksiInvReg->bukaKoneksi());
	$jmlRow = $CKoneksiInvReg->mysqlNRows($query);
	if($jmlRow != 0)
	{
		$nilai = "ada";
	}
	
	
	echo "<input type=\"hidden\" id=\"idMailInvSamaAdaTidak\" value=\"".$nilai."\" size=\"10\">";
}

if($aksiPost == "cekAssignYesNo")
{
	$idMailInvPost = $_POST['idMailInv'];
	
	$nilai="no";
	$query=$CKoneksiInvReg->mysqlQuery("SELECT idmailinv FROM mailinvoice WHERE idmailinv='".$idMailInvPost."' AND preparepayment='Y' AND deletests=0;", $CKoneksiInvReg->bukaKoneksi());
	$jmlRow=$CKoneksiInvReg->mysqlNRows($query);
	if($jmlRow != 0)
	{
		$nilai = "yes";
	}
	
	echo "<input type=\"hidden\" id=\"assignYesNo\" value=\"".$nilai."\" size=\"10\">";
}

if($aksiPost == "cekPaidYesNo")
{
	$idMailInvPost = $_POST['idMailInv'];
	
	$nilai = "no";
	$query = $CKoneksiInvReg->mysqlQuery("SELECT idmailinv FROM mailinvoice WHERE idmailinv='".$idMailInvPost."' AND paid='Y' AND deletests=0;", $CKoneksiInvReg->bukaKoneksi());
	$jmlRow = $CKoneksiInvReg->mysqlNRows($query);
	if($jmlRow != 0)
	{
		$nilai = "yes";
	}
	
	echo $nilai;
}

if($aksiPost == "ketikAutoComplSender")
{
	$paramPost = str_replace("'", "''", $_POST['param']); // SENDER VENDOR NAME
	$urutSendSelectPost = $_POST['urutSendSelect'];
	
	$html = "";
	$html.= "<table cellpadding=\"1\" cellspacing=\"0\" style=\"width:100%;background-color:#FFF;\" class=\"tabelDetailCari tabelBorderAll\">";
	$i = 0;
	$query = $koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "SELECT Acctcode, RTRIM(AcctIndo) AS AcctIndo, RTRIM(Acctname) AS Acctname FROM AccountCode WHERE Acctname LIKE '%".$paramPost."%' AND SUBSTRING(Acctcode ,1,2) = '12' AND LEN(RTRIM(Acctcode))=5 ORDER BY Acctname ASC;");
	$jmlRow = $koneksiOdbcAcc->odbcNRows($query);
	while($row = $koneksiOdbcAcc->odbcFetch($query))
	{		
		$i++;
		
		//$classSelect = "";
		//if($i == $urutSendSelectPost)
		//{
		//	$classSelect = "class=\"classSelect\"";
		//}
			
		/*$htmlA.="<tr ".$classSelect." id=\"trId_".$i."\" 
		onMouseOver=\"this.style.backgroundColor='#93A070';document.getElementById('aId_".$i."').style.color='#FFFFFF';document.getElementById('trId_".$i."').className ='classSelect';document.getElementById('urutSendSelect').value = '".$i."';\" 
		onMouseOut=\"this.style.backgroundColor='#FFFFFF';document.getElementById('aId_".$i."').style.color='#555';document.getElementById('trId_".$i."').className ='';\" 
		style=\"cursor:pointer;\" onclick=\"clickMenuSend('".$row['Acctcode']."', '".mysql_escape_string( $row['Acctname'] )."');\">";*/
		
		$html.="<tr id=\"trId_".$i."\" 
		onMouseOver=\"this.style.backgroundColor='#93A070';document.getElementById('aId_".$i."').style.color='#FFFFFF';document.getElementById('spanId_".$i."').style.color='#FFFFFF';$('#urutSendSelect').val('".$i."');\" 
		onMouseOut=\"this.style.backgroundColor='#FFFFFF';document.getElementById('aId_".$i."').style.color='#555';document.getElementById('spanId_".$i."').style.color='#485a88';\" 
		style=\"cursor:pointer;\" onclick=\"clickMenuSend('".$row['Acctcode']."', '".mysql_escape_string( $row['Acctname'] )."');\">";
		$html.="	<td height=\"20\">
						<span id=\"spanId_".$i."\" style=\"color:#485a88;\">".$row['Acctcode']."</span><a id=\"aId_".$i."\"> - ".$row['Acctname']."</a>
						<input type=\"hidden\" id=\"acctCode_".$i."\" value=\"".$row['Acctcode']."\">
						<input type=\"hidden\" id=\"actName_".$i."\" value=\"".$row['Acctname']."\"> 
					</td>";
		$html.="</tr>";
	}
	$html.="<input type=\"hidden\" id=\"totalSendVend\" value=\"".$jmlRow."\">";
	$html.= "</table>";
	echo $html;
}

if($aksiPost == "ketikAutoComplCust")
{
	$paramPost = str_replace("'", "''", $_POST['param']); // SENDER VENDOR NAME
	$urutCustSelectPost = $_POST['urutCustSelect'];
	
	$html = "";
	$html.= "<table cellpadding=\"1\" cellspacing=\"0\" style=\"width:100%;background-color:#FFF;\" class=\"tabelDetailCari tabelBorderAll\">";
	$i = 0;
	$query = $koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "SELECT Acctcode, RTRIM(AcctIndo) AS AcctIndo, RTRIM(Acctname) AS Acctname FROM AccountCode WHERE Acctname LIKE '%".$paramPost."%' AND SUBSTRING(Acctcode ,1,2) = '11' AND LEN(RTRIM(Acctcode))=5 ORDER BY Acctname ASC;");
	$jmlRow = $koneksiOdbcAcc->odbcNRows($query);
	while($row = $koneksiOdbcAcc->odbcFetch($query))
	{		
		$i++;
		
		$html.="<tr id=\"trId_".$i."\" 
		onMouseOver=\"this.style.backgroundColor='#93A070';document.getElementById('aId_".$i."').style.color='#FFFFFF';document.getElementById('spanId_".$i."').style.color='#FFFFFF';$('#urutCustSelect').val('".$i."');\" 
		onMouseOut=\"this.style.backgroundColor='#FFFFFF';document.getElementById('aId_".$i."').style.color='#555';document.getElementById('spanId_".$i."').style.color='#485a88';\" 
		style=\"cursor:pointer;\" onclick=\"clickMenuSend('".$row['Acctcode']."', '".mysql_escape_string( $row['Acctname'] )."');\">";
		$html.="	<td height=\"20\">
						<span id=\"spanId_".$i."\" style=\"color:#485a88;\">".$row['Acctcode']."</span><a id=\"aId_".$i."\"> - ".$row['Acctname']."</a>
						<input type=\"hidden\" id=\"acctCode_".$i."\" value=\"".$row['Acctcode']."\">
						<input type=\"hidden\" id=\"actName_".$i."\" value=\"".$row['Acctname']."\"> 
					</td>";
		$html.="</tr>";
	}
	$html.="<input type=\"hidden\" id=\"totalSendVend\" value=\"".$jmlRow."\">";
	$html.= "</table>";
	echo $html;
}

if($aksiPost == "cekSenderVendorCode")
{
	$senderVendorNamePost = str_replace("'", "''", $_POST['senderVendorName']);

	if(rtrim($senderVendorNamePost) != "")
		$acctCode = $CInvReg->detilSenderVendorByName($senderVendorNamePost, "Acctcode");
	echo $acctCode;
}

if($aksiPost == "cariDebAcc")
{
	$debitAccPost = $_POST['debitAcc'];
	$pjgDebAcc = strlen($debitAccPost);
	$html = "";
	$html.="<select id=\"menuDebitAcc\" class=\"elementMenu\" style=\"width:300px;\" onChange=\"pilihMenuDebitAcc(this.value);\">";
	$query = $koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "SELECT Acctcode, RTRIM(AcctIndo) AS AcctIndo, RTRIM(Acctname) AS Acctname FROM AccountCode WHERE  SUBSTRING(Acctcode ,1,".$pjgDebAcc.") = '".$debitAccPost."' AND LEN(RTRIM(Acctcode))=5 AND LEN(RTRIM(Acctname)) != '' ORDER BY Acctcode ASC;");	
	while($row = $koneksiOdbcAcc->odbcFetch($query))
	{		
		$i++;
		$html.="<option value=\"".$row['Acctcode']."\">".$row['Acctcode']." - ".$row['Acctname']."</option>";
	}
    $html.="</select>";
	echo $html;
}

if($aksiPost == "cariKredAcc")
{
	$kreditAccPost = $_POST['kreditAcc'];
	$pjgKredAcc = strlen($kreditAccPost);
	$html = "";
	$html.="<select id=\"menuKreditAcc\" class=\"elementMenu\" style=\"width:300px;\" onChange=\"pilihMenuKreditAcc(this.value);\">";
	$query = $koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "SELECT Acctcode, RTRIM(AcctIndo) AS AcctIndo, RTRIM(Acctname) AS Acctname FROM AccountCode WHERE  SUBSTRING(Acctcode ,1,".$pjgKredAcc.") = '".$kreditAccPost."' AND LEN(RTRIM(Acctcode))=5 AND LEN(RTRIM(Acctname)) != '' ORDER BY Acctcode ASC;");	
	while($row = $koneksiOdbcAcc->odbcFetch($query))
	{		
		$i++;
		$html.="<option value=\"".$row['Acctcode']."\">".$row['Acctcode']." - ".$row['Acctname']."</option>";
	}
    $html.="</select>";
	echo $html;
}

if($aksiPost == "isiDueDateRet")
{
	$invoiceDatePost = $_POST['invoiceDate'];
	$dueDayPost = $_POST['dueDay'];
	$teksBlinkPost = $_POST['teksBlink'];
	
	if($dueDayPost == "")
	{
		$dueDayPost = "0";
	}
	
	if($dueDayPost == "" || strlen($invoiceDatePost) < 10)
	{
		$dueDate = "";
	}
	else
	{
		$dueDate = $CPublic->intervalTanggal( $CPublic->convTglDB($invoiceDatePost), $dueDayPost-1);
	}
	
	$dueDayFromNow = $CPublic->perbedaanHari( "'".$dueDate."'" , "NOW()") ;
	
	if($teksBlinkPost == "no")
	{
		echo $CPublic->convTglNonDB( $dueDate );
	}
	if($teksBlinkPost == "yes")
	{
		echo $CPublic->convTglNonDB( $dueDate )."&nbsp;&nbsp;<span id=\"idSpanDueDay\" class=\"spanKalender\" style=\"position:static;font-size:12px;color:#900;font-size:11px;\">( ".$dueDayFromNow."&nbsp;Day(s) Left )</span>&nbsp;";
	}
}

if($aksiPost == "isiAmtConv")
{	
	$totalAmountPost = $_POST['totalAmount'];
	//$amtConvPost = str_replace(".","",str_replace(",","",$_POST['amtConv']));
	$amtConvPost = str_replace(",","",$_POST['amtConv']);
	$currencyPost = $_POST['currency'];
	$pjgAmtConv = strlen($amtConvPost);
	
	//$amtConv = substr_replace($amtConvPost, ".", ($pjgAmtConv - 2), 0);
	$nilai = "";
	if($amtConvPost != "")
	{
		if($amtConvPost == "0.00")
		{
			$nilai = "";
		}
		else
		{
			//$nilai = round(($amtConvPost - $totalAmountPost), 2);
			//$nilai = number_format((float)($amtConvPost - $totalAmountPost), 2, '.', ',');
			$nilai = str_replace("-", "",  number_format((float)($amtConvPost - $totalAmountPost), 2, '.', ',') );
		}
	}
	if($currencyPost != "IDR") //ADJUSMENT BERLAKU HANYA UNTUK RUPIAH
	{
		$nilai = "";
	}
	
	echo $nilai;
}

if($aksiPost == "simpanPaidToFrom")
{
	$idMailInvPost = $_POST['idMailInv'];
	$paidToFromPost = $_POST['paidToFrom'];
	
	$CKoneksiInvReg->mysqlQuery("UPDATE mailinvoice SET paidtofrom='".$paidToFromPost."' WHERE idmailinv=".$idMailInvPost." AND deletests=0 LIMIT 1;");
	$CHistory->updateLog2InvReg($userIdLogin, "ISI PAIDTOFROM PAYMENT BATCH Mail / Invoice (idmailinv=<b>".$idMailInvPost."</b>, paidtofrom=<b>".$paidToFromPost."</b>)");
	
	echo $paidToFromPost;
}

if($aksiPost == "simpanPaidBy")
{
	$idMailInvPost = $_POST['idMailInv'];
	$cmpPaidByPost = $_POST['cmpPaidBy'];
	
	if($cmpPaidByPost == "XXX")
	{
		$cmpPaidByPost = "";
	}
	
	$CKoneksiInvReg->mysqlQuery("UPDATE mailinvoice SET cmppaidby='".$cmpPaidByPost."' WHERE idmailinv=".$idMailInvPost." AND deletests=0 LIMIT 1;");
	$CHistory->updateLog2InvReg($userIdLogin, "PILIH PAID BY (OPTIONAL) PAYMENT BATCH Mail / Invoice (idmailinv=<b>".$idMailInvPost."</b>, cmppaidby=<b>".$cmpPaidByPost."</b>)");
	
	echo "<select id=\"cmpPaidBy\" name=\"cmpPaidBy\" class=\"elementMenu\" style=\"width:254px;\" onchange=\"klikPaidByMenu(this.value);return false;\" >";
	echo "	<option value=\"XXX\">-- PLEASE SELECT --</option>".$CInvReg->menuCmp($cmpPaidByPost);
	echo "</select>";
}

if($aksiPost == "detilCmpPaidBy")
{
	$idMailInvPost = $_POST['idMailInv'];
	$paidOptYesNoPost = $_POST['paidOptYesNo'];
	$cmpPaidBy = $CInvReg->detilMailInv($idMailInvPost, "cmppaidby");
	$paid = $CInvReg->detilMailInv($idMailInvPost, "paid");
	
	$disabled = "";
	if(trim($cmpPaidBy) == "")
	{
		if($paidOptYesNoPost == "no")
		{
			$disabled = "disabled";
		}
	}
	
	if($paid == "Y")
	{
		$disabled = "disabled";
	}
	
	$html = "";
	
	$html .= "<select id=\"cmpPaidBy\" name=\"cmpPaidBy\" class=\"elementMenu\" style=\"width:254px;\" onchange=\"klikPaidByMenu(this.value);return false;\" ".$disabled.">";
	$html .= "	<option value=\"XXX\">-- PLEASE SELECT --</option>".$CInvReg->menuCmp($cmpPaidBy);
	$html .= "</select>";

	echo $html;
}

if($aksiPost == "detilMailInv")
{
	$idMailInvPost = $_POST['idMailInv'];
	$fieldPost = $_POST['field'];
	
	echo $CInvReg->detilMailInv($idMailInvPost, $fieldPost);
	//echo $idMailInvPost." / ".$fieldPost;
}

if($aksiPost == "insertSummary")
{
	$userIdPost = $_POST['userId'];
	$companyPost = $_POST['company'];	
	$fromDatePost = $_POST['fromDate'];
	$endDatePost = $_POST['endDate'];
	$accountPost = $_POST['account'];
	
	//echo $company." ".$fromDate." ".$userId;
	echo $CAging->insertSummary($userIdPost, $companyPost, $fromDatePost, $endDatePost, $accountPost);
}

if($aksiPost == "ketikVslInfo")
{
	$idMailInvPost = $_POST['idMailInv'];
	$paramNilaiPost = mysql_escape_string( $_POST['paramNilai'] );
	
	$CKoneksiInvReg->mysqlQuery("UPDATE mailinvoice SET vslinfo='".$paramNilaiPost."', updusrdt='".$userWhoAct."' WHERE idmailinv=".$idMailInvPost." AND deletests=0 LIMIT 1;");
	echo $CInvReg->detilMailInv($idMailInvPost, "vslinfo");
}

if($aksiPost == "cekTabelDeduc")
{
	// BERI STATUS SEDANG MEMBUKA HALAMAN OLEH USERID = $USERIDLOGIN
	
	$idMailInvPost = $_POST['idMailInv'];
	//$CKoneksiInvReg->mysqlQuery("DELETE FROM tblsplittemp WHERE idmailinv='".$idMailInvPost."' AND userid='".$userIdLogin."' AND (fieldaksi='additional' OR fieldaksi='deduction');");
	//$CKoneksiInvReg->mysqlQuery("DELETE FROM tblsplittemp WHERE idmailinv='".$idMailInvPost."' AND userid='".$userIdLogin."' AND fieldaksi='memodebit';");
	/*$jmlRowSplitTemp = $CInvReg->jmlRowSplitTemp($idMailInvPost, $userIdLogin, "additional");
	if($jmlRowSplitTemp == 0)
	{
		for($i=1;$i<=5;$i++)
		{

			$CKoneksiInvReg->mysqlQuery("INSERT INTO tblsplittemp (idmailinv, userid, fieldaksi, urutan, booksts, addusrdt) VALUES ('".$idMailInvPost."', '".$userIdLogin."', 'deduction', ".$i.", 'CR', '".$userWhoActNew."')");
			$CKoneksiInvReg->mysqlQuery("INSERT INTO tblsplittemp (idmailinv, userid,  fieldaksi, urutan, booksts, addusrdt) VALUES ('".$idMailInvPost."', '".$userIdLogin."', 'additional', ".$i.", 'DB', '".$userWhoActNew."')");
		}
	}*/
	
	$CKoneksiInvReg->mysqlQuery("UPDATE tblsplittemp SET userid='".$userIdLogin."', openpage='Y' WHERE idmailinv=".$idMailInvPost.";");
	
	$urutanExistsDeduc = explode(",", daftarUrutanSplitTemp($CKoneksiInvReg, $idMailInvPost, $userIdLogin, "deduction") );
	$urutanExistsAddi = explode(",", daftarUrutanSplitTemp($CKoneksiInvReg, $idMailInvPost, $userIdLogin, "additional") );
	for($i=1;$i<=5;$i++)
	{	
		if (in_array($i, $urutanExistsDeduc)) 
		{	/*echo $i." Available\r\n";*/	} 
		else 
		{	//echo $i." Not available\r\n";
			$CKoneksiInvReg->mysqlQuery("INSERT INTO tblsplittemp (idmailinv, userid, fieldaksi, urutan, booksts, addusrdt) VALUES ('".$idMailInvPost."', '".$userIdLogin."', 'deduction', ".$i.", 'CR', '".$userWhoActNew."')");
		}

		if (in_array($i, $urutanExistsAddi)) 
		{	/*echo $i." Available\r\n";*/	} 
		else 
		{	//echo $i." Not available\r\n";
			$CKoneksiInvReg->mysqlQuery("INSERT INTO tblsplittemp (idmailinv, userid,  fieldaksi, urutan, booksts, addusrdt) VALUES ('".$idMailInvPost."', '".$userIdLogin."', 'additional', ".$i.", 'DB', '".$userWhoActNew."')");
		}
	}
}

if($aksiPost == "closePage")
{
	$idMailInvPost = $_POST['idMailInv'];
	// BERI STATUS SEDANG MEMBUKA HALAMAN OLEH USERID = $USERIDLOGIN
	$CKoneksiInvReg->mysqlQuery("UPDATE tblsplittemp SET openpage='N' WHERE idmailinv=".$idMailInvPost." AND userid='".$userIdLogin."';");
}

function daftarUrutanSplitTemp($CKoneksiInvReg, $idMailInv, $userIdLogin, $fieldAksi)
{
	$nilai = "";
	$query = $CKoneksiInvReg->mysqlQuery("SELECT * FROM tblsplittemp WHERE idmailinv='".$idMailInv."' AND userid=".$userIdLogin." AND fieldaksi='".$fieldAksi."' ORDER BY urutan;", $CKoneksiInvReg->bukaKoneksi());
	$jmlRow = $CKoneksiInvReg->mysqlNRows($query);
	while($row = $CKoneksiInvReg->mysqlFetch($query))
	{
		$nilai.= $row['urutan'].",";
	}
	
	return $nilai;
}

if($aksiPost == "emptyTabelSplitTemp")
{
	$idMailInvPost = $_POST['idMailInv'];
	$CKoneksiInvReg->mysqlQuery("DELETE FROM tblsplittemp WHERE idmailinv='".$idMailInvPost."' AND userid='".$userIdLogin."';");
}

if($aksiPost == "simpanSplitDeb")
{
	$idMailInvPost = $_POST['idMailInv'];
	$nilaiElementPost = $_POST['nilaiElement'];
	$urutanPost = $_POST['urutan'];
	$idElementPost = $_POST['idElement'];	
	
	if($idElementPost  == "account")
	{
		$CKoneksiInvReg->mysqlQuery("UPDATE tblsplittemp SET account='".$nilaiElementPost."' WHERE idmailinv=".$idMailInvPost." AND userid=".$userIdLogin." AND urutan=".$urutanPost." AND fieldaksi='memodebit' LIMIT 1;");
		$nilai = $CInvReg->detilSplitTemp($idMailInvPost, $userIdLogin, "memodebit", $urutanPost, "account");
	}
	if($idElementPost == "description")
	{
		$CKoneksiInvReg->mysqlQuery("UPDATE tblsplittemp SET description='".mysql_escape_string($nilaiElementPost)."' WHERE idmailinv=".$idMailInvPost." AND userid=".$userIdLogin." AND urutan=".$urutanPost." AND fieldaksi='memodebit' LIMIT 1;");
		$nilai = $CInvReg->detilSplitTemp($idMailInvPost, $userIdLogin, "memodebit", $urutanPost, "description");
	}
	if($idElementPost == "amount")
	{
		if(trim($nilaiElementPost) == "")
		{
			$nilaiElementPost = 0;
		}
		// SIMPAN AMOUNT KE DALAM TBLSPLIT
		$CKoneksiInvReg->mysqlQuery("UPDATE tblsplittemp SET amount=".str_replace(",","",$nilaiElementPost)." WHERE idmailinv=".$idMailInvPost." AND userid=".$userIdLogin." AND urutan=".$urutanPost." AND fieldaksi='memodebit' LIMIT 1;");

		// MULAI UPDATE AMOUNT DI TBLSPLIT DENGAN URUTAN 1
		//$incomingAmtPost = $_POST['incomingAmt']; // // TOTAL AMOUNT YANG DARI INPUTAN AWAL DI INCOMING MAIL
		//$totalAmount = $CInvReg->totalAmountSplitDeb($idMailInvPost, $userIdLogin, "memodebit"); // TOTAL AMOUNT YANG DI SPLIT SELAIN URUTAN KE 1	
		//$amount1 = ($incomingAmtPost - $totalAmount);// TOTAL AMOUNT YANG DARI INPUTAN AWAL DI INCOMING MAIL DIKURANG TOTAL AMOUNT SPLIT
		//$CKoneksiInvReg->mysqlQuery("UPDATE tblsplittemp SET amount=".$amount1." WHERE idmailinv=".$idMailInvPost." AND userid=".$userIdLogin." AND urutan=1 AND fieldaksi='memodebit' LIMIT 1;");
		
		//$nilai = number_format((float)$amount1, 2, '.', ',');
	}
	if($idElementPost == "vslCode")
	{
		$CKoneksiInvReg->mysqlQuery("UPDATE tblsplittemp SET vslcode='".$nilaiElementPost."' WHERE idmailinv=".$idMailInvPost." AND userid=".$userIdLogin." AND urutan=".$urutanPost." AND fieldaksi='memodebit' LIMIT 1;");
		$nilai = $CInvReg->detilSplitTemp($idMailInvPost, $userIdLogin, "memodebit", $urutanPost, "vslcode");
	}
	
	echo $nilai;
}

if($aksiPost == "simpanSplitCre")
{
	$idMailInvPost = $_POST['idMailInv'];
	$nilaiElementPost = $_POST['nilaiElement'];
	$urutanPost = $_POST['urutan'];
	$idElementPost = $_POST['idElement'];	
	
	if($idElementPost  == "account")
	{
		$CKoneksiInvReg->mysqlQuery("UPDATE tblsplittemp SET account='".$nilaiElementPost."' WHERE idmailinv=".$idMailInvPost." AND userid=".$userIdLogin." AND urutan=".$urutanPost." AND fieldaksi='memocredit' LIMIT 1;");
		$nilai = $CInvReg->detilSplitTemp($idMailInvPost, $userIdLogin, "memodebit", $urutanPost, "account");
	}
	if($idElementPost == "description")
	{
		$CKoneksiInvReg->mysqlQuery("UPDATE tblsplittemp SET description='".mysql_escape_string($nilaiElementPost)."' WHERE idmailinv=".$idMailInvPost." AND userid=".$userIdLogin." AND urutan=".$urutanPost." AND fieldaksi='memocredit' LIMIT 1;");
		$nilai = $CInvReg->detilSplitTemp($idMailInvPost, $userIdLogin, "memocredit", $urutanPost, "description");
	}
	if($idElementPost == "amount")
	{
		if(trim($nilaiElementPost) == "")
		{
			$nilaiElementPost = 0;
		}
		// SIMPAN AMOUNT KE DALAM TBLSPLIT
		$CKoneksiInvReg->mysqlQuery("UPDATE tblsplittemp SET amount=".str_replace(",","",$nilaiElementPost)." WHERE idmailinv=".$idMailInvPost." AND userid=".$userIdLogin." AND urutan=".$urutanPost." AND fieldaksi='memocredit' LIMIT 1;");

	}
	if($idElementPost == "vslCode")
	{
		$CKoneksiInvReg->mysqlQuery("UPDATE tblsplittemp SET vslcode='".$nilaiElementPost."' WHERE idmailinv=".$idMailInvPost." AND userid=".$userIdLogin." AND urutan=".$urutanPost." AND fieldaksi='memocredit' LIMIT 1;");
		$nilai = $CInvReg->detilSplitTemp($idMailInvPost, $userIdLogin, "memocredit", $urutanPost, "vslcode");
	}
	
	echo $nilai;
}

if($aksiPost == "simpanSplitCred")
{
	$idMailInvPost = $_POST['idMailInv'];
	$fieldAksiPost = $_POST['fieldAksi'];
	$nilaiElementPost = $_POST['nilaiElement'];
	$urutanPost = $_POST['urutan'];
	$idElementPost = $_POST['idElement'];	
	
	if($idElementPost  == "account")
	{
		$CKoneksiInvReg->mysqlQuery("UPDATE tblsplittemp SET account='".$nilaiElementPost."' WHERE idmailinv=".$idMailInvPost." AND userid=".$userIdLogin." AND urutan=".$urutanPost." AND fieldaksi='".$fieldAksiPost."' LIMIT 1;");
	}
	if($idElementPost == "vslCode")
	{
		$CKoneksiInvReg->mysqlQuery("UPDATE tblsplittemp SET vslcode='".$nilaiElementPost."' WHERE idmailinv=".$idMailInvPost." AND userid=".$userIdLogin." AND urutan=".$urutanPost." AND fieldaksi='".$fieldAksiPost."' LIMIT 1;");
	}
	if($idElementPost  == "description")
	{
		$CKoneksiInvReg->mysqlQuery("UPDATE tblsplittemp SET description='".mysql_escape_string($nilaiElementPost)."' WHERE idmailinv=".$idMailInvPost." AND userid=".$userIdLogin." AND urutan=".$urutanPost." AND fieldaksi='".$fieldAksiPost."' LIMIT 1;");
	}
	if($idElementPost == "amount")
	{
		if(trim($nilaiElementPost) == "")
		{
			$nilaiElementPost = 0;
		}
		$CKoneksiInvReg->mysqlQuery("UPDATE tblsplittemp SET amount=".str_replace(",","",$nilaiElementPost)." WHERE idmailinv=".$idMailInvPost." AND userid=".$userIdLogin." AND urutan=".$urutanPost." AND fieldaksi='".$fieldAksiPost."' LIMIT 1;");
	}
	
	//echo $idMailInvPost;
}

if($aksiPost == "cekSplitAcc")
{
	$idMailInvPost = $_POST['idMailInv'];
	$debitAccPost = $_POST['debitAcc'];
	$vesselCodePost = $_POST['vesselCode'];
	$amountPost = $_POST['amount'];
	$descPost = $_POST['desc'];
	
	$jmlRowSplit = $CInvReg->jmlRowSplitTemp($idMailInvPost, $userIdLogin, "memodebit");
	
	if($jmlRowSplit == 0) // JIKA BELUM ADA DATA DI TBLSPLITTEMP MAKA TAMBAHKAN DATA UNTUK URUTAN 1
	{
		$CKoneksiInvReg->mysqlQuery("INSERT INTO tblsplittemp (idmailinv, userid, fieldaksi, urutan, booksts, account, vslcode, amount, description, addusrdt) VALUES ('".$idMailInvPost."', '".$userIdLogin."',  'memodebit', 1, 'DB', '".$debitAccPost."', '".$vesselCodePost."', '".$amountPost."', '".mysql_escape_string($descPost)."', '".$userWhoActNew."')");
	}
	else // JIKA SUDAH ADA DATA DI TBLSPLITTEMP MAKA UPDATE HANYA ACCOUNT YANG NILAINYA DIAMBIL DARI DEBIT ACCOUNT
	{
		$CKoneksiInvReg->mysqlQuery("UPDATE tblsplittemp SET account='".$debitAccPost."', vslcode='".$vesselCodePost."' WHERE idmailinv=".$idMailInvPost." AND userid=".$userIdLogin." AND urutan=1 AND fieldaksi='memodebit' LIMIT 1;");
	}
}

if($aksiPost == "cekSplitCreAcc")
{
	$idMailInvPost = $_POST['idMailInv'];
	$creditAccPost = $_POST['creditAcc'];
	$vesselCodePost = $_POST['vesselCode'];
	$amountPost = $_POST['amount'];
	$descPost = $_POST['desc'];
	
	$jmlRowSplit = $CInvReg->jmlRowSplitTemp($idMailInvPost, $userIdLogin, "memocredit");
	
	if($jmlRowSplit == 0) // JIKA BELUM ADA DATA DI TBLSPLITTEMP MAKA TAMBAHKAN DATA UNTUK URUTAN 1
	{
		$CKoneksiInvReg->mysqlQuery("INSERT INTO tblsplittemp (idmailinv, userid, fieldaksi, urutan, booksts, account, vslcode, amount, description, addusrdt) VALUES ('".$idMailInvPost."', '".$userIdLogin."',  'memocredit', 1, 'CR', '".$creditAccPost."', '".$vesselCodePost."', '".$amountPost."', '".mysql_escape_string($descPost)."', '".$userWhoActNew."')");
	}
	else // JIKA SUDAH ADA DATA DI TBLSPLITTEMP MAKA UPDATE HANYA ACCOUNT YANG NILAINYA DIAMBIL DARI DEBIT ACCOUNT
	{
		$CKoneksiInvReg->mysqlQuery("UPDATE tblsplittemp SET account='".$creditAccPost."', vslcode='".$vesselCodePost."' WHERE idmailinv=".$idMailInvPost." AND userid=".$userIdLogin." AND urutan=1 AND fieldaksi='memocredit' LIMIT 1;");
	}
}

if($aksiPost == "simpanCekPay")
{
	$idMailInvPost = $_POST['idMailInv'];
	$nilaiCheckedPost = $_POST['nilaiChecked']=="true" ? "Y" : "N";
	$urutanPost = $_POST['urutan'];
	$CKoneksiInvReg->mysqlQuery("UPDATE tblsplittemp SET pay='".$nilaiCheckedPost."' WHERE idmailinv=".$idMailInvPost." AND userid=".$userIdLogin." AND urutan=".$urutanPost." AND fieldaksi='additional' LIMIT 1;");
}

if($aksiPost == "statusOpenPage")
{
	$idMailInvPost = $_POST['idMailInv'];
	$query = $CKoneksiInvReg->mysqlQuery("SELECT DISTINCT(openpage) AS openpage, userid FROM tblsplittemp WHERE idmailinv='".$idMailInvPost."';",$CKoneksiInvReg->bukaKoneksi());
	$row = $CKoneksiInvReg->mysqlFetch($query);
	
	$openPage = $row['openpage'];
	if($openPage == "Y" && $userIdLogin == $row['userid']) 
	{	// JIKA STATUS PAGE MASIH TERBUKA DAN YANG BUKA MEMO N ADALAH USER YANG MELAKUKAN OPENPAGE='Y' MAKA STATUS OPENPAGE='N' (DIKEMBALIKAN KE N)
		$openPage = "N";
	}
	echo $openPage;
	//echo $row['openpage']=="Y"?"Y":"N";
}

if($aksiPost == "unlockPage")
{
	$idMailInvPost = $_POST['idMailInv'];
	$CKoneksiInvReg->mysqlQuery("UPDATE tblsplittemp SET openpage='N' WHERE idmailinv='".$idMailInvPost."';");
	echo "sukses";
}

if ($aksiPost == "cekSpliteCredit") 
{
	$idMailInvPost = $_POST['idMailInv'];
	$queryCek = $CKoneksiInvReg->mysqlQuery("SELECT * FROM tblsplit WHERE idmailinv = '".$idMailInvPost."' AND fieldaksi = 'memocredit' ORDER BY urutan ASC;", $CKoneksiInvReg->bukaKoneksi());
	$dataCek = $CKoneksiInvReg->mysqlNRows($queryCek);
	echo $dataCek;
}

if($aksiPost == "cekInvoiceNo")
{
	$noInvoice = $_POST['noInvoice'];
	
	$nilai = "kosong";
	$query = $CKoneksiInvReg->mysqlQuery("SELECT idmailinv FROM mailinvoice WHERE mailinvno	='".$noInvoice."' AND deletests=0;", $CKoneksiInvReg->bukaKoneksi());
	$jmlRow = $CKoneksiInvReg->mysqlNRows($query);
	if($jmlRow != 0)
	{
		$nilai = "ada";
	}
	print $nilai;
}

if($aksiPost == "cekLockDataNya")
{
	$id = $_POST['id'];
	$bookDate = "";
	$initCom = "";
	$cekNya = "";

	$query = $CKoneksiInvReg->mysqlQuery("SELECT * FROM mailinvoice WHERE idmailinv	='".$id."' AND deletests=0;", $CKoneksiInvReg->bukaKoneksi());
	$row = $CKoneksiInvReg->mysqlFetch($query);

	$jmlRow = $CKoneksiInvReg->mysqlNRows($query);

	if($jmlRow > 0)
	{
		$bookDate = $row['tgljurnal'];
		$initCom = $row['company'];

		//$bookDate = "2022-03-31";

		$sql = $koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "SELECT TOP 1 * FROM Lockquarter WHERE company = '".$initCom."' ORDER BY datelock DESC;");
		$rows = $koneksiOdbcAcc->odbcFetch($sql);

		$dateLockNya = $rows['Datelock'];

		$cnvBookDate = new DateTime($bookDate);
		$cnvLockDate = new DateTime($dateLockNya);

		if($cnvBookDate <= $cnvLockDate)
		{
			$cekNya = "lock";
		}

	}

	print $cekNya;
}

if($aksiPost == "getVoyageNo")
{
	$vsl = $_POST['vsl'];
	$thn = $_POST['thn'];
	$trNya = "";

	$thnNya = substr($thn, 6, 4);
	$trNya .= "<option value=\"\">- SELECT VOYAGE -</option>";

	$sql = $koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "SELECT * FROM vesselvoy WHERE vslcode = '".$vsl."' AND voyyear = '".$thnNya."' ORDER BY voyage ASC;");
	while($row = $koneksiOdbcAcc->odbcFetch($sql))
	{
		$trNya.="<option value=\"".$row['voyage']."\">".$row['voyage']."</option>";
	}

	print $trNya;
}

if($aksiPost == "cekDateLockNya")
{
	$id = $_POST['id'];
	$jurnalDate = $_POST['jurnalDate'];
	$bookDate = "";
	$initCom = "";
	$cekNya = "";

	$query = $CKoneksiInvReg->mysqlQuery("SELECT * FROM mailinvoice WHERE idmailinv	='".$id."' AND deletests=0;", $CKoneksiInvReg->bukaKoneksi());
	$row = $CKoneksiInvReg->mysqlFetch($query);

	$jmlRow = $CKoneksiInvReg->mysqlNRows($query);

	if($jmlRow > 0)
	{
		$bookDate = $CPublic->convTglDB($jurnalDate);
		$initCom = $row['company'];

		$sql = $koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "SELECT TOP 1 * FROM Lockquarter WHERE company= '".$initCom."' ORDER BY datelock DESC;");
		$rows = $koneksiOdbcAcc->odbcFetch($sql);

		$dateLockNya = $rows['Datelock'];

		$cnvBookDate = new DateTime($bookDate);
		$cnvLockDate = new DateTime($dateLockNya);

		if($cnvBookDate->getTimestamp() <= $cnvLockDate->getTimestamp())
		{
			$cekNya = "lock";
		}
	}

	print $cekNya;
}

if($aksiPost == "actionExportAgingPayable")
{
	$cmpPost = $_POST['txtCompanyHid'];
	$sDatePost = $_POST['txtSdateHid'];
	$eDatePost = $_POST['txtEdateHid'];
	$rbAccPost = $_POST['rbAccountHid'];
	$userId = $_POST['txtUserIdHid'];
	$thNya = "";
	$trNya = "";
	$typeAcc = "";

	if($rbAccPost == "pay")
	{
		$typeAcc = "Payable";
	}else{
		$typeAcc = "Receivable";
	}

	$tgl = substr($eDatePost,0,2);
	$bln = substr($eDatePost,3,2);
	$thn = substr($eDatePost,6,4);
	$dateDisp = ucfirst(strtolower($CPublic->detilBulanNamaAngka($bln, "eng")))." ".$tgl.", ".$thn;

	$frmDateConv = $CPublic->convTglDB($sDatePost);
    $endDateConv = $CPublic->convTglDB($eDatePost);

	ob_start();

	$sql = "SELECT *
            FROM (
            SELECT kreditacc,vendor,currency,urutan,urutangrup,idmailinv,company,datedisp,mailinvno,barcode,receivedate,tglinvoice,dueday,tglexp,subaccount,amount,rangesatu,rangedua,rangetiga,rangeempat,rangelima FROM summaryaging 
            WHERE userid = '".$userId."'
            UNION ALL
            SELECT kreditacc,vendor,currency,urutan,urutangrup,idmailinv,company,datedisp,mailinvno,barcode,receivedate,tglinvoice,dueday,tglexp,subaccount,amount,rangesatu,rangedua,rangetiga,rangeempat,rangelima FROM tempsummaryaging
            WHERE paid = 'N' AND company = '".$cmpPost."' AND receivedate BETWEEN '".$frmDateConv."' AND '".$endDateConv."'
            ) A ORDER BY kreditacc,subaccount ASC
            ";

	$query = $CKoneksiInvReg->mysqlQuery($sql, $CKoneksiInvReg->bukaKoneksi());
	// $query = $CKoneksiInvReg->mysqlQuery("SELECT * FROM summaryaging WHERE userid = ".$userId." ORDER BY urutan ASC;", $CKoneksiInvReg->bukaKoneksi());

	header("Content-Type: application/vnd.ms-excel");
	echo "<table width=\"100%\">";
		echo "<tr>
				<td colspan=\"15\" align=\"left\" style=\"font:1em Arial Narrow;font-weight:bold;\">
					<label>".$CInvReg->detilComp($cmpPost, "compname")."</label>
				</td>
			</tr>";
		echo "<tr>
				<td colspan=\"15\" align=\"left\">
					<label style=\"font-size: 18pt;font-weight: bold;\">Account ".$typeAcc." Aging Report</label>
				</td>
			</tr>";
		echo "<tr>
				<td colspan=\"15\" align=\"left\">
					<label style=\"font-size: 18pt;font-weight: bold;\">as of ".$dateDisp."</label>
				</td>
			</tr>";
	echo "</table>";

	echo "<table border=\"1\">";

	$thNya .= "<tr>";
		$thNya .= "<td align=\"center\" rowspan=\"2\" style=\"vertical-align:middle;width:50px;\">NO</td>";
		$thNya .= "<td align=\"center\" rowspan=\"2\" style=\"vertical-align:middle;\">INVOICE NO</td>";
		$thNya .= "<td align=\"center\" rowspan=\"2\" style=\"vertical-align:middle;\">BARCODE</td>";
		$thNya .= "<td align=\"center\" rowspan=\"2\" style=\"vertical-align:middle;\">RECEIVED DATE</td>";
		$thNya .= "<td align=\"center\" rowspan=\"2\" style=\"vertical-align:middle;\">INVOICE DATE</td>";
		$thNya .= "<td align=\"center\" rowspan=\"2\" style=\"vertical-align:middle;\">TERM</td>";
		$thNya .= "<td align=\"center\" rowspan=\"2\" style=\"vertical-align:middle;\">DUE DATE</td>";
		$thNya .= "<td align=\"center\" rowspan=\"2\" style=\"vertical-align:middle;\">CURRENCY</td>";
		$thNya .= "<td align=\"center\" rowspan=\"2\" style=\"vertical-align:middle;\">CURRENT</td>";
		$thNya .= "<td align=\"center\" colspan=\"5\">OVERDUE</td>";
		$thNya .= "<td align=\"center\" rowspan=\"2\" style=\"vertical-align:middle;\">TOTAL</td>";
	$thNya .= "</tr>";
	$thNya .= "<tr>";
		$thNya .= "<td align=\"center\">1 - 30 DAYS</td>";
		$thNya .= "<td align=\"center\">31 - 60 DAYS</td>";
		$thNya .= "<td align=\"center\">61 - 90 DAYS</td>";
		$thNya .= "<td align=\"center\">91 - 360 DAYS</td>";
		$thNya .= "<td align=\"center\">> 360 DAYS</td>";
	$thNya .= "</tr>";

	echo $thNya;
	
	$currOther1 = "";
	$totalOther1Current = 0;
    $totalOther1RangeSatu = 0;
    $totalOther1RangeDua = 0;
    $totalOther1RangeTiga = 0;
    $totalOther1RangeEmpat = 0;
    $totalOther1RangeLima = 0;
    $currOther2 = "";
    $totalOther2Current = 0;
    $totalOther2RangeSatu = 0;
    $totalOther2RangeDua = 0;
    $totalOther2RangeTiga = 0;
    $totalOther2RangeEmpat = 0;
    $totalOther2RangeLima = 0;

    $totalCurrentIdr = 0;
    $totalRange1Idr = 0;
    $totalRange2Idr = 0;
    $totalRange3Idr = 0;
    $totalRange4Idr = 0;
    $totalRange5Idr = 0;
    $totalCurrentUsd = 0;
    $totalRange1Usd = 0;
    $totalRange2Usd = 0;
    $totalRange3Usd = 0;
    $totalRange4Usd = 0;
    $totalRange5Usd = 0;
    $noTemp = 1;

    $tempData = array();

    while($row = $CKoneksiInvReg->mysqlFetch($query))
    {
        $txtSubAcct = "";

        if($row['subaccount'] != "")
        {
            $ds = $CInvReg->getSubAccount($row['company'],$row['kreditacc'],$row['subaccount']);

            if($ds != "")
            {
                $txtSubAcct = $row['subaccount']." - ".$ds;
            }
        }

        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['urutangrup'] = $row['urutangrup'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['idmailinv'] = $row['idmailinv'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['company'] = $row['company'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['datedisp'] = $row['datedisp'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['kreditacc'] = $row['kreditacc'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['vendor'] = $row['vendor'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['currency'] = $row['currency'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['mailinvno'] = $row['mailinvno'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['barcode'] = $row['barcode'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['receivedate'] = $row['receivedate'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['tglinvoice'] = $row['tglinvoice'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['dueday'] = $row['dueday'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['tglexp'] = $row['tglexp'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['subaccount'] = $row['subaccount'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['amount'] = $row['amount'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['rangesatu'] = $row['rangesatu'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['rangedua'] = $row['rangedua'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['rangetiga'] = $row['rangetiga'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['rangeempat'] = $row['rangeempat'];
        $tempData[trim($row['kreditacc'])." - ".trim($row['vendor'])." - ".trim($row['currency'])][$txtSubAcct][$noTemp]['rangelima'] = $row['rangelima'];
        $noTemp++;
    }

    // echo "<pre>";print_r($tempData);exit;
    foreach ($tempData as $key => $val)
    {
        $trNya .= "<tr>";
            $trNya .= "<td class=\"tabelBorderBottomJust\" colspan=\"15\" height=\"28\" style=\"font:1em Arial Narrow;font-weight:bold;\">";
                $trNya .= $key;
            $trNya .= "</td>";
        $trNya .= "</tr>";

        foreach ($val as $keys => $vals)
        {
            $no=1;
            $subTotalCurrent = 0;
            $subTotalRangeSatu = 0;
            $subTotalRangeDua = 0;
            $subTotalRangeTiga = 0;
            $subTotalRangeEmpat = 0;
            $subTotalRangeLima = 0;
            
            if($keys == "")
            {
                $keys = "--";
            }

            $trNya .= "<tr>";
                $trNya .= "<td class=\"tabelBorderBottomJust\" colspan=\"15\" height=\"22\" style=\"padding-left:30px;font:0.8em Arial Narrow;font-weight:bold;\" align=\"left\">";
                    $trNya .= $keys;
                $trNya .= "</td>";
            $trNya .= "</tr>";
            
            foreach ($vals as $keyd => $value)
            {
                $bgClr = "background-color:#F0F1FF;";
                $currentNya = 0;
                $rangeSatu = 0;
                $rangeDua = 0;
                $rangeTiga = 0;
                $rangeEmpat = 0;
                $rangeLima = 0;
                $currentNyaView = "";
                $rangeSatuView = "";
                $rangeDuaView = "";
                $rangeTigaView = "";
                $rangeEmpatView = "";
                $rangeLimaView = "";

                if($value['urutangrup'] %2 == 0) { $bgClr = "background-color:#FFFFF;"; }

                $interval = $CPublic->perbedaanHari(str_replace("-","",$endDateConv),str_replace("-","",$value['tglexp']));

                if($interval < 1)
                {
                    $currentNya = $value['amount'];
                    $currentNyaView = number_format($currentNya, 2, ",", ".");

                    if(strtoupper($value['currency']) == "IDR")
                    {
                        $totalCurrentIdr = $totalCurrentIdr + $currentNya;
                    }
                    if(strtoupper($value['currency']) == "USD")
                    {
                        $totalCurrentUsd = $totalCurrentUsd + $currentNya;
                    }

                    if(strtoupper($value['currency']) != "IDR" AND strtoupper($value['currency']) != "USD")
                    {
                        if($currOther1 == "")
                        {
                            $currOther1 = $value['currency'];
                            $totalOther1Current = $totalOther1Current + $currentNya;
                        }else{
                            if($currOther1 == $value['currency'])
                            {
                                $totalOther1Current = $totalOther1Current + $currentNya;
                            }else{
                                if($currOther2 == "")
                                {
                                    $currOther2 = $value['currency'];
                                }
                                $totalOther2Current = $totalOther2Current + $currentNya;
                            }
                        }
                    }
                }
                if($interval >= 1 && $interval <= 30)
                {
                    $rangeSatu = $value['amount'];
                    $rangeSatuView = number_format($rangeSatu, 2, ",", ".");

                    if(strtoupper($value['currency']) == "IDR")
                    {
                        $totalRange1Idr = $totalRange1Idr + $rangeSatu;
                    }
                    if(strtoupper($value['currency']) == "USD")
                    {
                        $totalRange1Usd = $totalRange1Usd + $rangeSatu;
                    }

                    if(strtoupper($value['currency']) != "IDR" AND strtoupper($value['currency']) != "USD")
                    {
                        if($currOther1 == "")
                        {
                            $currOther1 = $value['currency'];
                            $totalOther1RangeSatu = $totalOther1RangeSatu + $rangeSatu;
                        }else{
                            if($currOther1 == $value['currency'])
                            {
                                $totalOther1RangeSatu = $totalOther1RangeSatu + $rangeSatu;
                            }else{
                                if($currOther2 == "")
                                {
                                    $currOther2 = $value['currency'];
                                }
                                $totalOther2RangeSatu = $totalOther2RangeSatu + $rangeSatu;
                            }
                        }
                    }
                }
                if($interval >= 31 && $interval <= 60)
                {
                    $rangeDua = $value['amount'];
                    $rangeDuaView = number_format($rangeDua, 2, ",", ".");

                    if(strtoupper($value['currency']) == "IDR")
                    {
                        $totalRange2Idr = $totalRange2Idr + $rangeDua;
                    }
                    if(strtoupper($value['currency']) == "USD")
                    {
                        $totalRange2Usd = $totalRange2Usd + $rangeDua;
                    }

                    if(strtoupper($value['currency']) != "IDR" AND strtoupper($value['currency']) != "USD")
                    {
                        if($currOther1 == "")
                        {
                            $currOther1 = $value['currency'];
                            $totalOther1RangeDua = $totalOther1RangeDua + $rangeDua;
                        }else{
                            if($currOther1 == $value['currency'])
                            {
                                $totalOther1RangeDua = $totalOther1RangeDua + $rangeDua;
                            }else{
                                if($currOther2 == "")
                                {
                                    $currOther2 = $value['currency'];
                                }
                                $totalOther2RangeDua = $totalOther2RangeDua + $rangeDua;
                            }
                        }
                    }
                }
                if($interval >= 61 && $interval <= 90)
                {
                    $rangeTiga = $value['amount'];
                    $rangeTigaView = number_format($rangeTiga, 2, ",", ".");

                    if(strtoupper($value['currency']) == "IDR")
                    {
                        $totalRange3Idr = $totalRange3Idr + $rangeTiga;
                    }
                    if(strtoupper($value['currency']) == "USD")
                    {
                        $totalRange3Usd = $totalRange3Usd + $rangeTiga;
                    }

                    if(strtoupper($value['currency']) != "IDR" AND strtoupper($value['currency']) != "USD")
                    {
                        if($currOther1 == "")
                        {
                            $currOther1 = $value['currency'];
                            $totalOther1RangeTiga = $totalOther1RangeTiga + $rangeTiga;
                        }else{
                            if($currOther1 == $value['currency'])
                            {
                                $totalOther1RangeTiga = $totalOther1RangeTiga + $rangeTiga;
                            }else{
                                if($currOther2 == "")
                                {
                                    $currOther2 = $value['currency'];
                                }
                                $totalOther2RangeTiga = $totalOther2RangeTiga + $rangeTiga;
                            }
                        }
                    }
                }
                 if($interval >= 91 && $interval <= 360)
                {
                    $rangeEmpat = $value['amount'];
                    $rangeEmpatView = number_format($rangeEmpat, 2, ",", ".");

                    if(strtoupper($value['currency']) == "IDR")
                    {
                        $totalRange4Idr = $totalRange4Idr + $rangeEmpat;
                    }
                    if(strtoupper($value['currency']) == "USD")
                    {
                        $totalRange4Usd = $totalRange4Usd + $rangeEmpat;
                    }

                    if(strtoupper($value['currency']) != "IDR" AND strtoupper($value['currency']) != "USD")
                    {
                        if($currOther1 == "")
                        {
                            $currOther1 = $value['currency'];
                            $totalOther1RangeEmpat = $totalOther1RangeEmpat + $rangeEmpat;
                        }else{
                            if($currOther1 == $value['currency'])
                            {
                                $totalOther1RangeEmpat = $totalOther1RangeEmpat + $rangeEmpat;
                            }else{
                                if($currOther2 == "")
                                {
                                    $currOther2 = $value['currency'];
                                }
                                $totalOther2RangeEmpat = $totalOther2RangeEmpat + $rangeEmpat;
                            }
                        }
                    }
                }
                if($interval >= 361)
                {
                    $rangeLima = $value['amount'];
                    $rangeLimaView = number_format($rangeLima, 2, ",", ".");

                    if(strtoupper($value['currency']) == "IDR")
                    {
                        $totalRange5Idr = $totalRange5Idr + $rangeLima;
                    }
                    if(strtoupper($value['currency']) == "USD")
                    {
                        $totalRange5Usd = $totalRange5Usd + $rangeLima;
                    }

                    if(strtoupper($value['currency']) != "IDR" AND strtoupper($value['currency']) != "USD")
                    {
                        if($currOther1 == "")
                        {
                            $currOther1 = $value['currency'];
                            $totalOther1RangeLima = $totalOther1RangeLima + $rangeLima;
                        }else{
                            if($currOther1 == $value['currency'])
                            {
                                $totalOther1RangeLima = $totalOther1RangeLima + $rangeLima;
                            }else{
                                if($currOther2 == "")
                                {
                                    $currOther2 = $value['currency'];
                                }
                                $totalOther2RangeLima = $totalOther2RangeLima + $rangeLima;
                            }
                        }
                    }
                }

                 $rowTotal = $currentNya + $rangeSatu + $rangeDua + $rangeTiga + $rangeEmpat + $rangeLima;
                
                $trNya .= "<tr style=\"font:0.8em Arial Narrow;font-weight:normal;\">";
                     $trNya .= "<td height=\"22\" width=\"30\" style=\"".$bgClr."\" class=\"tabelBorderRightJust\" align=\"center\">";
                        $trNya .= $no;
                    $trNya .= "</td>";
                    $trNya .= "<td width=\"200\" style=\"padding-left:3px;".$bgClr."\" class=\"tabelBorderRightJust\">";
                        $trNya .= "<div style=\"width:120px; word-wrap:break-word;\">".$value['mailinvno']."</div>";
                    $trNya .= "</td>";
                    $trNya .= "<td width=\"120\" style=\"".$bgClr."\" class=\"tabelBorderRightJust\" align=\"center\">";
                        $trNya .= $value['barcode'];
                    $trNya .= "</td>";
                    $trNya .= "<td width=\"150\" style=\"".$bgClr."\" class=\"tabelBorderRightJust\" align=\"center\">";
                        $trNya .= $CPublic->convTglNonDB($value['receivedate']);
                    $trNya .= "</td>";
                    $trNya .= "<td width=\"150\" style=\"".$bgClr."\" class=\"tabelBorderRightJust\" align=\"center\">";
                        $trNya .= $CPublic->convTglNonDB($value['tglinvoice']);
                    $trNya .= "</td>";
                    $trNya .= "<td width=\"100\" style=\"".$bgClr."\" class=\"tabelBorderRightJust\" align=\"center\">";
                        $trNya .= $value['dueday'];
                    $trNya .= "</td>";
                    $trNya .= "<td width=\"150\" style=\"".$bgClr."\" class=\"tabelBorderRightJust\" align=\"center\">";
                        $trNya .= $CPublic->isWeekendNya($value['tglexp']);
                    $trNya .= "</td>";
                    $trNya .= "<td width=\"100\" style=\"".$bgClr."\" class=\"tabelBorderRightJust\" align=\"right\">";
                        $trNya .= $value['currency'];
                    $trNya .= "</td>";
                    $trNya .= "<td width=\"100\" style=\"".$bgClr."\" class=\"tabelBorderRightJust\" align=\"right\">";
                        $trNya .= $currentNyaView;
                    $trNya .= "</td>";
                    $trNya .= "<td width=\"100\" style=\" <?php echo $bgClr;?>\" class=\"tabelBorderRightJust\"
align=\"right\">";
$trNya .= $rangeSatuView;
$trNya .= "</td>";
$trNya .= "<td width=\"100\" style=\" <?php echo $bgClr;?>\" class=\"tabelBorderRightJust\" align=\"right\">";
    $trNya .= $rangeDuaView;
    $trNya .= "</td>";
$trNya .= "<td width=\"100\" style=\" <?php echo $bgClr;?>\" class=\"tabelBorderRightJust\" align=\"right\">";
    $trNya .= $rangeTigaView;
    $trNya .= "</td>";
$trNya .= "<td width=\"100\" style=\" <?php echo $bgClr;?>\" class=\"tabelBorderRightJust\" align=\"right\">";
    $trNya .= $rangeEmpatView;
    $trNya .= "</td>";
$trNya .= "<td width=\"100\" style=\" <?php echo $bgClr;?>\" class=\"tabelBorderRightJust\" align=\"right\">";
    $trNya .= $rangeLimaView;
    $trNya .= "</td>";
$trNya .= "<td width=\"100\" style=\"".$bgClr."\" class=\"tabelBorderRightJust\" align=\"right\">";
    $trNya .= number_format($rowTotal, 2, ",", ".");
    $trNya .= "</td>";
$trNya .= "</tr>";

$subTotalCurrent = $subTotalCurrent + $currentNya;
$subTotalRangeSatu = $subTotalRangeSatu + $rangeSatu;
$subTotalRangeDua = $subTotalRangeDua + $rangeDua;
$subTotalRangeTiga = $subTotalRangeTiga + $rangeTiga;
$subTotalRangeEmpat = $subTotalRangeEmpat + $rangeEmpat;
$subTotalRangeLima = $subTotalRangeLima + $rangeLima;

$no++;
}

$subRowTotal = 0;
if($subTotalCurrent > 0)
{
$subRowTotal = $subRowTotal + $subTotalCurrent;
$subTotalCurrent = number_format($subTotalCurrent, 2, ",", ".");
}else{
$subTotalCurrent = "";
}

if($subTotalRangeSatu > 0)
{
$subRowTotal = $subRowTotal + $subTotalRangeSatu;
$subTotalRangeSatu = number_format($subTotalRangeSatu, 2, ",", ".");
}else{
$subTotalRangeSatu = "";
}

if($subTotalRangeDua > 0)
{
$subRowTotal = $subRowTotal + $subTotalRangeDua;
$subTotalRangeDua = number_format($subTotalRangeDua, 2, ",", ".");
}else{
$subTotalRangeDua = "";
}

if($subTotalRangeTiga > 0)
{
$subRowTotal = $subRowTotal + $subTotalRangeTiga;
$subTotalRangeTiga = number_format($subTotalRangeTiga, 2, ",", ".");
}else{
$subTotalRangeTiga = "";
}

if($subTotalRangeEmpat > 0)
{
$subRowTotal = $subRowTotal + $subTotalRangeEmpat;
$subTotalRangeEmpat = number_format($subTotalRangeEmpat, 2, ",", ".");
}else{
$subTotalRangeEmpat = "";
}

if($subTotalRangeLima > 0)
{
$subRowTotal = $subRowTotal + $subTotalRangeLima;
$subTotalRangeLima = number_format($subTotalRangeLima, 2, ",", ".");
}else{
$subTotalRangeLima = "";
}

$trNya .= "<tr style=\"font:0.9em Arial Narrow;font-weight:bold;\" height=\"20\">";
    $trNya .= "<td class=\"tabelBorderLeftRightNull\" colspan=\"7\" height=\"24\">&nbsp;</td>";
    $trNya .= "<td class=\"tabelBorderLeftNull\">&nbsp;Sub Total</td>";
    $trNya .= "<td class=\"tabelBorderLeftNull\" align=\"right\">".$subTotalCurrent."</td>";
    $trNya .= "<td class=\"tabelBorderLeftNull\" align=\"right\">".$subTotalRangeSatu."</td>";
    $trNya .= "<td class=\"tabelBorderLeftNull\" align=\"right\">".$subTotalRangeDua."</td>";
    $trNya .= "<td class=\"tabelBorderLeftNull\" align=\"right\">".$subTotalRangeTiga."</td>";
    $trNya .= "<td class=\"tabelBorderLeftNull\" align=\"right\">".$subTotalRangeEmpat."</td>";
    $trNya .= "<td class=\"tabelBorderLeftNull\" align=\"right\">".$subTotalRangeLima."</td>";
    $trNya .= "<td class=\"tabelBorderLeftNull\" align=\"right\">".number_format($subRowTotal, 2, ",", ".")."</td>";
    $trNya .= "</tr>";
}
}

$totalRowIdr = $totalCurrentIdr + $totalRange1Idr + $totalRange2Idr + $totalRange3Idr + $totalRange4Idr +
$totalRange5Idr;
$totalRowUsd = $totalCurrentUsd + $totalRange1Usd + $totalRange2Usd + $totalRange3Usd + $totalRange4Usd +
$totalRange5Usd;

if($totalCurrentIdr > 0) { $totalCurrentIdr = number_format($totalCurrentIdr, 2, ",", "."); } else { $totalCurrentIdr =
""; }
if($totalRange1Idr > 0) { $totalRange1Idr = number_format($totalRange1Idr, 2, ",", "."); } else { $totalRange1Idr = "";
}
if($totalRange2Idr > 0) { $totalRange2Idr = number_format($totalRange2Idr, 2, ",", "."); } else { $totalRange2Idr = "";
}
if($totalRange3Idr > 0) { $totalRange3Idr = number_format($totalRange3Idr, 2, ",", "."); } else { $totalRange3Idr = "";
}
if($totalRange4Idr > 0) { $totalRange4Idr = number_format($totalRange4Idr, 2, ",", "."); } else { $totalRange4Idr = "";
}
if($totalRange5Idr > 0) { $totalRange5Idr = number_format($totalRange5Idr, 2, ",", "."); } else { $totalRange5Idr = "";
}
if($totalCurrentUsd > 0) { $totalCurrentUsd = number_format($totalCurrentUsd, 2, ",", "."); } else { $totalCurrentUsd =
""; }
if($totalRange1Usd > 0) { $totalRange1Usd = number_format($totalRange1Usd, 2, ",", "."); } else { $totalRange1Usd = "";
}
if($totalRange2Usd > 0) { $totalRange2Usd = number_format($totalRange2Usd, 2, ",", "."); } else { $totalRange2Usd = "";
}
if($totalRange3Usd > 0) { $totalRange3Usd = number_format($totalRange3Usd, 2, ",", "."); } else { $totalRange3Usd = "";
}
if($totalRange4Usd > 0) { $totalRange4Usd = number_format($totalRange4Usd, 2, ",", "."); } else { $totalRange4Usd = "";
}
if($totalRange5Usd > 0) { $totalRange5Usd = number_format($totalRange5Usd, 2, ",", "."); } else { $totalRange5Usd = "";
}

$trNya .= "<tr style=\"font:0.8em Arial Narrow;font-weight:bold;background-color:#F5F5DC;\">";
    $trNya .= "<td colspan=\"7\" height=\"24\">&nbsp;</td>";
    $trNya .= "<td class=\"tabelBorderRightJust\" style=\"font:1.1em Arial Narrow;font-weight:bold;\">&nbsp;Total - IDR
    </td>";
    $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalCurrentIdr."</td>";
    $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalRange1Idr."</td>";
    $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalRange2Idr."</td>";
    $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalRange3Idr."</td>";
    $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalRange4Idr."</td>";
    $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalRange5Idr."</td>";
    $trNya .= "<td align=\"right\">".number_format($totalRowIdr, 2, ",", ".")."</td>";
    $trNya .= "</tr>";
$trNya .= "<tr style=\"font:0.8em Arial Narrow;font-weight:bold;background-color:#F5F5DC;\">";
    $trNya .= "<td colspan=\"7\" height=\"24\">&nbsp;</td>";
    $trNya .= "<td class=\"tabelBorderRightJust\" style=\"font:1.1em Arial Narrow;font-weight:bold;\">&nbsp;Total - USD
    </td>";
    $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalCurrentUsd."</td>";
    $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalRange1Usd."</td>";
    $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalRange2Usd."</td>";
    $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalRange3Usd."</td>";
    $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalRange4Usd."</td>";
    $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalRange5Usd."</td>";
    $trNya .= "<td align=\"right\">".number_format($totalRowUsd, 2, ",", ".")."</td>";
    $trNya .= "</tr>";

if($currOther1 != "")
{
$totalRowOther1 = $totalOther1Current + $totalOther1RangeSatu + $totalOther1RangeDua + $totalOther1RangeTiga +
$totalOther1RangeEmpat + $totalOther1RangeLima;

if($totalOther1Current > 0) { $totalOther1Current = number_format($totalOther1Current, 2, ",", "."); } else {
$totalOther1Current = ""; }
if($totalOther1RangeSatu > 0) { $totalOther1RangeSatu= number_format($totalOther1RangeSatu, 2, ",", "."); } else {
$totalOther1RangeSatu = ""; }
if($totalOther1RangeDua > 0) { $totalOther1RangeDua = number_format($totalOther1RangeDua, 2, ",", "."); } else {
$totalOther1RangeDua = ""; }
if($totalOther1RangeTiga > 0) { $totalOther1RangeTiga= number_format($totalOther1RangeTiga, 2, ",", "."); } else {
$totalOther1RangeTiga = ""; }
if($totalOther1RangeEmpat > 0){ $totalOther1RangeEmpat=number_format($totalOther1RangeEmpat, 2, ",", "."); }else{
$totalOther1RangeEmpat = ""; }
if($totalOther1RangeLima > 0) { $totalOther1RangeLima=number_format($totalOther1RangeLima, 2, ",", "."); } else {
$totalOther1RangeLima = ""; }

$trNya .= "<tr style=\"font:0.8em Arial Narrow;font-weight:bold;background-color:#F5F5DC;\">";
    $trNya .= "<td colspan=\"7\" height=\"24\">&nbsp;</td>";
    $trNya .= "<td class=\"tabelBorderRightJust\" style=\"font:1.1em Arial Narrow;font-weight:bold;\">&nbsp;Total -
        ".$currOther1."</td>";
    $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalOther1Current."</td>";
    $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalOther1RangeSatu."</td>";
    $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalOther1RangeDua."</td>";
    $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalOther1RangeTiga."</td>";
    $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalOther1RangeEmpat."</td>";
    $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalOther1RangeLima."</td>";
    $trNya .= "<td align=\"right\">".number_format($totalRowOther1, 2, ",", ".")."</td>";
    $trNya .= "</tr>";
}

if($currOther2 != "")
{
$totalRowOther2 = $totalOther2Current + $totalOther2RangeSatu + $totalOther2RangeDua + $totalOther2RangeTiga +
$totalOther2RangeEmpat + $totalOther2RangeLima;

if($totalOther2Current > 0) { $totalOther2Current = number_format($totalOther2Current, 2, ",", "."); } else {
$totalOther2Current = ""; }
if($totalOther2RangeSatu > 0) { $totalOther2RangeSatu= number_format($totalOther2RangeSatu, 2, ",", "."); } else {
$totalOther2RangeSatu = ""; }
if($totalOther2RangeDua > 0) { $totalOther2RangeDua = number_format($totalOther2RangeDua, 2, ",", "."); } else {
$totalOther2RangeDua = ""; }
if($totalOther2RangeTiga > 0) { $totalOther2RangeTiga= number_format($totalOther2RangeTiga, 2, ",", "."); } else {
$totalOther2RangeTiga = ""; }
if($totalOther2RangeEmpat > 0){ $totalOther2RangeEmpat=number_format($totalOther2RangeEmpat, 2, ",", "."); }else{
$totalOther2RangeEmpat = ""; }
if($totalOther2RangeLima > 0) { $totalOther2RangeLima= number_format($totalOther2RangeLima, 2, ",", "."); } else {
$totalOther2RangeLima = ""; }

$trNya .= "<tr style=\"font:0.8em Arial Narrow;font-weight:bold;background-color:#F5F5DC;\">";
    $trNya .= "<td colspan=\"7\" height=\"24\">&nbsp;</td>";
    $trNya .= "<td class=\"tabelBorderRightJust\" style=\"font:1.1em Arial Narrow;font-weight:bold;\">&nbsp;Total -
        ".$currOther2."</td>";
    $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalOther2Current."</td>";
    $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalOther2RangeSatu."</td>";
    $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalOther2RangeDua."</td>";
    $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalOther2RangeTiga."</td>";
    $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalOther2RangeEmpat."</td>";
    $trNya .= "<td class=\"tabelBorderRightJust\" align=\"right\">".$totalOther2RangeLima."</td>";
    $trNya .= "<td align=\"right\">".number_format($totalRowOther2, 2, ",", ".")."</td>";
    $trNya .= "</tr>";
}

echo $trNya;
echo "</table>";

header("Content-disposition: attachment; filename=summaryreport.xls");
ob_end_flush();
}

if($aksiPost == "actionExportAgingPayable_04082022")
{
$cmpPost = $_POST['txtCompanyHid'];
$sDatePost = $_POST['txtSdateHid'];
$eDatePost = $_POST['txtEdateHid'];
$rbAccPost = $_POST['rbAccountHid'];
$userId = $_POST['txtUserIdHid'];
$thNya = "";
$trNya = "";
$typeAcc = "";

if($rbAccPost == "pay")
{
$typeAcc = "Payable";
}else{
$typeAcc = "Receivable";
}

$tgl = substr($eDatePost,0,2);
$bln = substr($eDatePost,3,2);
$thn = substr($eDatePost,6,4);
$dateDisp = ucfirst(strtolower($CPublic->detilBulanNamaAngka($bln, "eng")))." ".$tgl.", ".$thn;

ob_start();

$query = $CKoneksiInvReg->mysqlQuery("SELECT * FROM summaryaging WHERE userid = ".$userId." ORDER BY urutan ASC;",
$CKoneksiInvReg->bukaKoneksi());

header("Content-Type: application/vnd.ms-excel");
echo "<table width=\"100%\">";
    echo "<tr>
        <td colspan=\"13\" align=\"left\" style=\"font:1em Arial Narrow;font-weight:bold;\">
            <label>".$CInvReg->detilComp($cmpPost, "compname")."</label>
        </td>
    </tr>";
    echo "<tr>
        <td colspan=\"13\" align=\"left\">
            <label style=\"font-size: 18pt;font-weight: bold;\">Account ".$typeAcc." Aging Report</label>
        </td>
    </tr>";
    echo "<tr>
        <td colspan=\"13\" align=\"left\">
            <label style=\"font-size: 18pt;font-weight: bold;\">as of ".$dateDisp."</label>
        </td>
    </tr>";
    echo "</table>";

echo "<table border=\"1\">";

    $thNya .= "<tr>";
        $thNya .= "<td align=\"center\" rowspan=\"2\" style=\"vertical-align:middle;width:50px;\">NO</td>";
        $thNya .= "<td align=\"center\" rowspan=\"2\" style=\"vertical-align:middle;\">INVOICE NO</td>";
        $thNya .= "<td align=\"center\" rowspan=\"2\" style=\"vertical-align:middle;\">BARCODE</td>";
        $thNya .= "<td align=\"center\" rowspan=\"2\" style=\"vertical-align:middle;\">RECEIVED DATE</td>";
        $thNya .= "<td align=\"center\" rowspan=\"2\" style=\"vertical-align:middle;\">INVOICE DATE</td>";
        $thNya .= "<td align=\"center\" rowspan=\"2\" style=\"vertical-align:middle;\">TERM</td>";
        $thNya .= "<td align=\"center\" rowspan=\"2\" style=\"vertical-align:middle;\">DUE DATE</td>";
        $thNya .= "<td align=\"center\" rowspan=\"2\" style=\"vertical-align:middle;\">CURRENT</td>";
        $thNya .= "<td align=\"center\" colspan=\"5\">OVERDUE</td>";
        $thNya .= "</tr>";
    $thNya .= "<tr>";
        $thNya .= "<td align=\"center\">1 - 30 DAYS</td>";
        $thNya .= "<td align=\"center\">31 - 60 DAYS</td>";
        $thNya .= "<td align=\"center\">61 - 90 DAYS</td>";
        $thNya .= "<td align=\"center\">91 - 360 DAYS</td>";
        $thNya .= "<td align=\"center\">> 360 DAYS</td>";
        $thNya .= "</tr>";

    echo $thNya;

    $currOther1 = "";
    $totalOther1RangeSatu = "";
    $totalOther1RangeDua = "";
    $totalOther1RangeTiga = "";
    $totalOther1RangeEmpat = "";
    $totalOther1RangeLima = "";
    $currOther2 = "";
    $totalOther2RangeSatu = "";
    $totalOther2RangeDua = "";
    $totalOther2RangeTiga = "";
    $totalOther2RangeEmpat = "";
    $totalOther2RangeLima = "";

    while($row = $CKoneksiInvReg->mysqlFetch($query))
    {
    $current = $row['currency'];

    $rangeSatu = "";
    if($row['rangesatu'] != 0.00){ $rangeSatu = number_format($row['rangesatu'], 2, ",", "."); }
    $rangeDua = "";
    if($row['rangedua'] != 0.00){ $rangeDua = number_format($row['rangedua'], 2, ",", "."); }
    $rangeTiga = "";
    if($row['rangetiga'] != 0.00){ $rangeTiga = number_format($row['rangetiga'], 2, ",", "."); }
    $rangeEmpat = "";
    if($row['rangeempat'] != 0.00){ $rangeEmpat = number_format($row['rangeempat'], 2, ",", "."); }
    $rangeLima = "";
    if($row['rangelima'] != 0.00){ $rangeLima = number_format($row['rangelima'], 2, ",", "."); }

    $subRangeSatu = "";
    if($CAging->hitungSubTotal($userId, $row['kreditacc'], $row['currency'], "satu") != 0.00)
    {
    $subRangeSatu = number_format($CAging->hitungSubTotal($userId, $row['kreditacc'], $row['currency'], "satu"), 2, ",",
    ".");
    }
    $subRangeDua = "";
    if($CAging->hitungSubTotal($userId, $row['kreditacc'], $row['currency'], "dua") != 0.00)
    {
    $subRangeDua = number_format($CAging->hitungSubTotal($userId, $row['kreditacc'], $row['currency'], "dua"), 2, ",",
    ".");
    }
    $subRangeTiga = "";
    if($CAging->hitungSubTotal($userId, $row['kreditacc'], $row['currency'], "tiga") != 0.00)
    {
    $subRangeTiga = number_format($CAging->hitungSubTotal($userId, $row['kreditacc'], $row['currency'], "tiga"), 2, ",",
    ".");
    }
    $subRangeEmpat = "";
    if($CAging->hitungSubTotal($userId, $row['kreditacc'], $row['currency'], "empat") != 0.00)
    {
    $subRangeEmpat = number_format($CAging->hitungSubTotal($userId, $row['kreditacc'], $row['currency'], "empat"), 2,
    ",", ".");
    }
    $subRangeLima = "";
    if($CAging->hitungSubTotal($userId, $row['kreditacc'], $row['currency'], "lima") != 0.00)
    {
    $subRangeLima = number_format($CAging->hitungSubTotal($userId, $row['kreditacc'], $row['currency'], "lima"), 2, ",",
    ".");
    }

    $totalIdr = "";
    if($CAging->hitungTotal($userId, "IDR", "") != 0.00)
    {
    $totalIdr = number_format($CAging->hitungTotal($userId, "IDR", "satu"), 2, ",", ".");
    }
    $totalRangeSatuIdr = "";
    if($CAging->hitungTotal($userId, "IDR", "satu") != 0.00)
    {
    $totalRangeSatuIdr = number_format($CAging->hitungTotal($userId, "IDR", "satu"), 2, ",", ".");
    }
    $totalRangeDuaIdr = "";
    if($CAging->hitungTotal($userId, "IDR", "dua") != 0.00)
    {
    $totalRangeDuaIdr = number_format($CAging->hitungTotal($userId, "IDR", "dua"), 2, ",", ".");
    }
    $totalRangeTigaIdr = "";
    if($CAging->hitungTotal($userId, "IDR", "tiga") != 0.00)
    {
    $totalRangeTigaIdr = number_format($CAging->hitungTotal($userId, "IDR", "tiga"), 2, ",", ".");
    }
    $totalRangeEmpatIdr = "";
    if($CAging->hitungTotal($userId, "IDR", "empat") != 0.00)
    {
    $totalRangeEmpatIdr = number_format($CAging->hitungTotal($userId, "IDR", "empat"), 2, ",", ".");
    }
    $totalRangeLimaIdr = "";
    if($CAging->hitungTotal($userId, "IDR", "lima") != 0.00)
    {
    $totalRangeLimaIdr = number_format($CAging->hitungTotal($userId, "IDR", "lima"), 2, ",", ".");
    }

    $totalUsd = "";
    if($CAging->hitungTotal($userId, "USD", "") != 0.00)
    {
    $totalUsd = number_format($CAging->hitungTotal($userId, "USD", ""), 2, ",", ".");
    }
    $totalRangeSatuUsd = "";
    if($CAging->hitungTotal($userId, "USD", "satu") != 0.00)
    {
    $totalRangeSatuUsd = number_format($CAging->hitungTotal($userId, "USD", "satu"), 2, ",", ".");
    }
    $totalRangeDuaUsd = "";
    if($CAging->hitungTotal($userId, "USD", "dua") != 0.00)
    {
    $totalRangeDuaUsd = number_format($CAging->hitungTotal($userId, "USD", "dua"), 2, ",", ".");
    }
    $totalRangeTigaUsd = "";
    if($CAging->hitungTotal($userId, "USD", "tiga") != 0.00)
    {
    $totalRangeTigaUsd = number_format($CAging->hitungTotal($userId, "USD", "tiga"), 2, ",", ".");
    }
    $totalRangeEmpatUsd = "";
    if($CAging->hitungTotal($userId, "USD", "empat") != 0.00)
    {
    $totalRangeEmpatUsd = number_format($CAging->hitungTotal($userId, "USD", "empat"), 2, ",", ".");
    }
    $totalRangeLimaUsd = "";
    if($CAging->hitungTotal($userId, "USD", "lima") != 0.00)
    {
    $totalRangeLimaUsd = number_format($CAging->hitungTotal($userId, "USD", "lima"), 2, ",", ".");
    }

    if($current != "IDR" AND $current != "USD")
    {
    if($currOther1 == "")
    {
    $currOther1 = $current;

    if($CAging->hitungTotal($userId, $current, "satu") != 0.00)
    {
    $totalOther1RangeSatu = number_format($CAging->hitungTotal($userId, $current, "satu"), 2, ",", ".");
    }
    if($CAging->hitungTotal($userId, $current, "dua") != 0.00)
    {
    $totalOther1RangeDua = number_format($CAging->hitungTotal($userId, $current, "dua"), 2, ",", ".");
    }
    if($CAging->hitungTotal($userId, $current, "tiga") != 0.00)
    {
    $totalOther1RangeTiga = number_format($CAging->hitungTotal($userId, $current, "tiga"), 2, ",", ".");
    }
    if($CAging->hitungTotal($userId, $current, "empat") != 0.00)
    {
    $totalOther1RangeEmpat = number_format($CAging->hitungTotal($userId, $current, "empat"), 2, ",", ".");
    }
    if($CAging->hitungTotal($userId, $current, "lima") != 0.00)
    {
    $totalOther1RangeLima = number_format($CAging->hitungTotal($userId, $current, "lima"), 2, ",", ".");
    }
    }else{
    if($currOther1 == $current)
    {
    if($CAging->hitungTotal($userId, $current, "satu") != 0.00)
    {
    $totalOther1RangeSatu = number_format($CAging->hitungTotal($userId, $current, "satu"), 2, ",", ".");
    }
    if($CAging->hitungTotal($userId, $current, "dua") != 0.00)
    {
    $totalOther1RangeDua = number_format($CAging->hitungTotal($userId, $current, "dua"), 2, ",", ".");
    }
    if($CAging->hitungTotal($userId, $current, "tiga") != 0.00)
    {
    $totalOther1RangeTiga = number_format($CAging->hitungTotal($userId, $current, "tiga"), 2, ",", ".");
    }
    if($CAging->hitungTotal($userId, $current, "empat") != 0.00)
    {
    $totalOther1RangeEmpat = number_format($CAging->hitungTotal($userId, $current, "empat"), 2, ",", ".");
    }
    if($CAging->hitungTotal($userId, $current, "lima") != 0.00)
    {
    $totalOther1RangeLima = number_format($CAging->hitungTotal($userId, $current, "lima"), 2, ",", ".");
    }
    }else{
    if($currOther2 == "")
    {
    $currOther2 = $current;
    }
    if($CAging->hitungTotal($userId, $current, "satu") != 0.00)
    {
    $totalOther2RangeSatu = number_format($CAging->hitungTotal($userId, $current, "satu"), 2, ",", ".");
    }
    if($CAging->hitungTotal($userId, $current, "dua") != 0.00)
    {
    $totalOther2RangeDua = number_format($CAging->hitungTotal($userId, $current, "dua"), 2, ",", ".");
    }
    if($CAging->hitungTotal($userId, $current, "tiga") != 0.00)
    {
    $totalOther2RangeTiga = number_format($CAging->hitungTotal($userId, $current, "tiga"), 2, ",", ".");
    }
    if($CAging->hitungTotal($userId, $current, "empat") != 0.00)
    {
    $totalOther2RangeEmpat = number_format($CAging->hitungTotal($userId, $current, "empat"), 2, ",", ".");
    }
    if($CAging->hitungTotal($userId, $current, "lima") != 0.00)
    {
    $totalOther2RangeLima = number_format($CAging->hitungTotal($userId, $current, "lima"), 2, ",", ".");
    }
    }
    }
    }

    if($row['urutangrup'] == 1)
    {
    $trNya .= "<tr>";
        $trNya .="<td colspan=\"13\" height=\"28\" style=\"font:1em Arial Narrow;font-weight:bold;\">
            ".$row['kreditacc']." - ".$row['vendor']." - ".$row['currency']."</td>";
        $trNya .= "</tr>";
    }

    $trNya .= "<tr>";
        $trNya .= "<td align=\"center\">".$row['urutangrup']."</td>";
        $trNya .= "<td align=\"left\">".$row['mailinvno']."</td>";
        $trNya .= "<td align=\"center\">".$row['barcode']."</td>";
        $trNya .= "<td align=\"center\">".$CPublic->convTglNonDB($row['receivedate'])."</td>";
        $trNya .= "<td align=\"center\">".$CPublic->convTglNonDB($row['tglinvoice'])."</td>";
        $trNya .= "<td align=\"center\">".$row['dueday']."</td>";
        $trNya .= "<td align=\"center\">".$CPublic->convTglNonDB($row['tglexp'])."</td>";
        $trNya .= "<td align=\"center\">".$row['currency']."</td>";
        $trNya .= "<td align=\"right\">".$rangeSatu."</td>";
        $trNya .= "<td align=\"right\">".$rangeDua."</td>";
        $trNya .= "<td align=\"right\">".$rangeTiga."</td>";
        $trNya .= "<td align=\"right\">".$rangeEmpat."</td>";
        $trNya .= "<td align=\"right\">".$rangeLima."</td>";
        $trNya .= "</tr>";

    if($row['urutangrup'] == $CAging->jmlInvoice($userId, $row['kreditacc'], $row['currency']))
    {
    $trNya .= "<tr style=\"font:0.9em Arial Narrow;font-weight:bold;\" height=\"20\">";
        $trNya .= "<td colspan=\"6\" height=\"24\"></td>";
        $trNya .= "<td> Sub Total</td>";
        $trNya .= "<td></td>";
        $trNya .= "<td align=\"right\">".$subRangeSatu."</td>";
        $trNya .= "<td align=\"right\">".$subRangeDua."</td>";
        $trNya .= "<td align=\"right\">".$subRangeTiga."</td>";
        $trNya .= "<td align=\"right\">".$subRangeEmpat."</td>";
        $trNya .= "<td align=\"right\">".$subRangeLima."</td>";
        $trNya .= "</tr>";
    }
    }

    $trNya .= "<tr style=\"font:0.8em Arial Narrow;font-weight:bold;background-color:#F5F5DC;\">";
        $trNya .= "<td colspan=\"6\" height=\"24\"> </td>";
        $trNya .= "<td style=\"font:1em Arial Narrow;font-weight:bold;\"> Total - IDR</td>";
        $trNya .= "<td></td>";
        $trNya .= "<td align=\"right\">".$totalRangeSatuIdr."</td>";
        $trNya .= "<td align=\"right\">".$totalRangeDuaIdr."</td>";
        $trNya .= "<td align=\"right\">".$totalRangeTigaIdr."</td>";
        $trNya .= "<td align=\"right\">".$totalRangeEmpatIdr."</td>";
        $trNya .= "<td align=\"right\">".$totalRangeLimaIdr."</td>";
        $trNya .= "</tr>";
    $trNya .= "<tr style=\"font:0.8em Arial Narrow;font-weight:bold;background-color:#F5F5DC;\">";
        $trNya .= "<td colspan=\"6\" height=\"24\"> </td>";
        $trNya .= "<td style=\"font:1em Arial Narrow;font-weight:bold;\"> Total - USD</td>";
        $trNya .= "<td></td>";
        $trNya .= "<td align=\"right\">".$totalRangeSatuUsd."</td>";
        $trNya .= "<td align=\"right\">".$totalRangeDuaUsd."</td>";
        $trNya .= "<td align=\"right\">".$totalRangeTigaUsd."</td>";
        $trNya .= "<td align=\"right\">".$totalRangeEmpatUsd."</td>";
        $trNya .= "<td align=\"right\">".$totalRangeLimaUsd."</td>";
        $trNya .= "</tr>";

    if($currOther1 != "")
    {
    $trNya .= "<tr style=\"font:0.8em Arial Narrow;font-weight:bold;background-color:#F5F5DC;\">";
        $trNya .= "<td colspan=\"6\" height=\"24\"> </td>";
        $trNya .= "<td style=\"font:1em Arial Narrow;font-weight:bold;\"> Total - ".$currOther1."</td>";
        $trNya .= "<td></td>";
        $trNya .= "<td align=\"right\">".$totalOther1RangeSatu."</td>";
        $trNya .= "<td align=\"right\">".$totalOther1RangeDua."</td>";
        $trNya .= "<td align=\"right\">".$totalOther1RangeTiga."</td>";
        $trNya .= "<td align=\"right\">".$totalOther1RangeEmpat."</td>";
        $trNya .= "<td align=\"right\">".$totalOther1RangeLima."</td>";
        $trNya .= "</tr>";
    }

    if($currOther2 != "")
    {
    $trNya .= "<tr style=\"font:0.8em Arial Narrow;font-weight:bold;background-color:#F5F5DC;\">";
        $trNya .= "<td colspan=\"6\" height=\"24\"> </td>";
        $trNya .= "<td style=\"font:1em Arial Narrow;font-weight:bold;\"> Total - ".$currOther2."</td>";
        $trNya .= "<td></td>";
        $trNya .= "<td align=\"right\">".$totalOther2RangeSatu."</td>";
        $trNya .= "<td align=\"right\">".$totalOther2RangeDua."</td>";
        $trNya .= "<td align=\"right\">".$totalOther2RangeTiga."</td>";
        $trNya .= "<td align=\"right\">".$totalOther2RangeEmpat."</td>";
        $trNya .= "<td align=\"right\">".$totalOther2RangeLima."</td>";
        $trNya .= "</tr>";
    }

    echo $trNya;
    echo "</table>";

header("Content-disposition: attachment; filename=summaryreport.xls");
ob_end_flush();
}

if($aksiPost == "displaySubAccountNya")
{
$cmp = $_POST['cmp'];
$account = $_POST['account'];
$subaccount = $_POST['subaccount'];
$descNya = "";

$sql = $koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "SELECT * FROM sub_account WHERE company = '".$cmp."' AND account =
'".$account."' AND subaccount = '".$subaccount."';");
while($row = $koneksiOdbcAcc->odbcFetch($sql))
{
$descNya = $row['descsub'];
}

print $descNya;
}

if($aksiPost == "getTempPaymentList")
{
$arrTransNos = $_POST['arrTransNos'];
$arrIdVoucher = $_POST['idVoucher'];
$arrIdPaymentAdv = $_POST['arrIdPaymentAdv'];
$arrIdMailInv = $_POST['arrIdMailInv'];
$trNya = "";
$idTransNoNya = "";
$idVoucher = "";
$idPaymentAdv = "";
$idMailInv = "";
$tempData = array();
$tempDataPA = array();
// echo"
//
//
// <pre>";print_r($_POST);exit;
	if(count($arrTransNos) > 0 AND $arrTransNos != "")
	{
		for ($lan=0; $lan < count($arrTransNos); $lan++)
		{
			if($idTransNoNya == "")
			{
				$idTransNoNya .= "'".$arrTransNos[$lan]."'";
			}else{
				$idTransNoNya .= ",'".$arrTransNos[$lan]."'";
			}
		}

		$sql = "SELECT idmailinv, sendervendor1, sendervendor2name, company, companyname, currency, mailinvno, amount, addi, deduc, transno, file_upload 
			FROM mailinvoice 
			WHERE transno IN (".$idTransNoNya.") AND paid='N' AND st_payment_list = 'N' AND deletests=0 ORDER BY 0+transno DESC, idmailinv DESC;";
			
		$query = $CKoneksiInvReg->mysqlQuery($sql, $CKoneksiInvReg->bukaKoneksi());

		while($row = $CKoneksiInvReg->mysqlFetch($query))
		{
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
		}

		foreach ($tempData as $key => $val)
		{
			$companyNya = "";
			$invNoNya = "";
			$curr = "";
			$total = 0;
			$deduc = 0;
			$addi = 0;
			$btnNya = "";

			for ($lan = 0; $lan < count($val['companyname']); $lan++)
			{
				$companyNya = $val['companyname'][$lan];
				$curr = $val['currency'][$lan];
				$total = $total + $val['amount'][$lan];
				$deduc = $deduc + $val['deduc'][$lan];
				$addi = $addi + $val['addi'][$lan];

				if($invNoNya == "")
				{
					$invNoNya = "- ".$val['invNo'][$lan];
					if($val['file_upload'][$lan] != "")
					{
						$invNoNya = "- "."<a href=\"./templates/fileUpload/".$val['file_upload'][$lan]."\" target=\"_blank\" title=\"View File\">".$val['invNo'][$lan]."</a>";
					}
				}else{
					if($val['file_upload'][$lan] != "")
					{
						$invNoNya .= "<br>- "."<a href=\"./templates/fileUpload/".$val['file_upload'][$lan]."\" target=\"_blank\" title=\"View File\">".$val['invNo'][$lan]."</a>";
					}else{
						$invNoNya .= "<br>- ".$val['invNo'][$lan];
					}
				}

				if($val['file_upload'][$lan] == "")
				{
					$btnNya = "<button class=\"btnStandar\" id=\"btnAddFile\" title=\"ADD FILE\" onClick=\"parent.showFormUploadInvReg('".$key."','invreg');\" style=\"\"> File </button>";
				}
			}

			$total = ($total - $deduc) + $addi;

			$trNya .= "<tr align=\"center\" valign=\"bottom\" bgcolor=\"".$rowColor."\" onMouseOver=\"this.style.backgroundColor='#D9EDFF';\" onMouseOut=\"this.style.backgroundColor='".$rowColor."';\" style=\"cursor:pointer;padding-bottom:1px;\">";
				$trNya .= "<td height=\"22\" class=\"tabelBorderTopLeftNull\" style=\"font-size:10px;vertical-align:top;\">";
					$trNya .= "<button class=\"btnStandar\" id=\"btnDelList\" title=\"DELETE LIST\" onClick=\"delTempGetData('".$key."','invreg');\"> Del </button>";
				$trNya .= "</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font-size:10px;color:#096;font-weight:bold;vertical-align:top;\">".$CPublic->zerofill($key)."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:10px;vertical-align:top;\">".$invNoNya."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:10px;vertical-align:top;\">".$companyNya."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"right\" style=\"font-size:10px;vertical-align:top;\">(".$curr.") ".number_format($total,2)."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font-size:10px;vertical-align:top;\" align=\"right\">
								<input type=\"hidden\" id=\"txtTransNo_".$key."\" name=\"txtTransNo[]\" value=\"".$key."\">
								<input type=\"text\" id=\"txtRemark_".$key."\" name=\"txtRemark[]\" placeholder=\"Remark\" value=\"\" style=\"margin-left:3px;font-size:10px;height:18px;\">
							</td>";
			$trNya .= "</tr>";
		}
	}

	if(count($arrIdMailInv) > 0 AND $arrIdMailInv != "")
	{
		for ($lan=0; $lan < count($arrIdMailInv); $lan++)
		{
			if($idMailInv == "")
			{
				$idMailInv .= "'".$arrIdMailInv[$lan]."'";
			}else{
				$idMailInv .= ",'".$arrIdMailInv[$lan]."'";
			}
		}

		$sql = "SELECT idmailinv, sendervendor1, sendervendor2name, company, companyname, currency, mailinvno, amount, addi, deduc, transno, file_upload 
			FROM mailinvoice 
			WHERE idmailinv IN (".$idMailInv.") AND paid='N' AND st_payment_list = 'N' AND st_tobepaid = 'Y' AND deletests=0;";

		$query = $CKoneksiInvReg->mysqlQuery($sql, $CKoneksiInvReg->bukaKoneksi());

		while($row = $CKoneksiInvReg->mysqlFetch($query))
		{
			$onclickDelDataList = "delTempGetData('".$row['idmailinv']."','invregByPass');";

			$total = ($row['amount'] + $row['addi']) - $row['deduc'];

			$trNya .= "<tr align=\"center\" valign=\"bottom\" bgcolor=\"".$rowColor."\" onMouseOver=\"this.style.backgroundColor='#D9EDFF';\" onMouseOut=\"this.style.backgroundColor='".$rowColor."';\" style=\"cursor:pointer;padding-bottom:1px;\">";
				$trNya .= "<td height=\"22\" class=\"tabelBorderTopLeftNull\" style=\"font-size:10px;vertical-align:top;\">";
					$trNya .= "<button class=\"btnStandar\" id=\"btnDelList\" title=\"DELETE LIST\" onClick=\"".$onclickDelDataList."\"> Del </button>";
				$trNya .= "</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font-size:10px;color:#096;font-weight:bold;vertical-align:top;\">-</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:10px;vertical-align:top;\">".$row['mailinvno']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:10px;vertical-align:top;\">".$row['companyname']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"right\" style=\"font-size:10px;vertical-align:top;\">(".$row['currency'].") ".number_format($total,2)."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font-size:10px;vertical-align:top;\" align=\"right\">
								<input type=\"hidden\" id=\"txtIdMailInv_".$row['idmailinv']."\" name=\"txtIdMailInv[]\" value=\"".$row['idmailinv']."\">
								<input type=\"text\" id=\"txtIdMailRemark_".$row['idmailinv']."\" name=\"txtIdMailRemark[]\" placeholder=\"Remark\" value=\"\" style=\"margin-left:3px;font-size:10px;height:18px;\">
							</td>";
			$trNya .= "</tr>";
		}
	}

	if(count($arrIdVoucher) > 0 AND $arrIdVoucher != "")
	{
		for ($lan=0; $lan < count($arrIdVoucher); $lan++)
		{
			if($idVoucher == "")
			{
				$idVoucher .= "'".$arrIdVoucher[$lan]."'";
			}else{
				$idVoucher .= ",'".$arrIdVoucher[$lan]."'";
			}
		}

		$sqlVoucher = "SELECT * FROM tblvoucher WHERE idvoucher IN (".$idVoucher.") AND deletests = '0' AND trfacct = 'N' AND st_payment_list = 'N' ORDER BY batchno DESC;";

		$queryVoucher = $CKoneksiVoucher->mysqlQuery($sqlVoucher, $CKoneksiVoucher->bukaKoneksi());
		while($rows = $CKoneksiVoucher->mysqlFetch($queryVoucher))
		{
			$batchNoNya = $CPublic->zerofill($rows['batchno']);
			if($rows['file_upload'] != "")
			{
				$batchNoNya = "<a href=\"./../voucher/templates/fileUpload/".$rows['file_upload']."\" target=\"_blank\">".$batchNoNya."</a>";
			}

			$trNya .= "<tr align=\"center\" valign=\"bottom\" bgcolor=\"".$rowColor."\" onMouseOver=\"this.style.backgroundColor='#D9EDFF';\" onMouseOut=\"this.style.backgroundColor='".$rowColor."';\" style=\"cursor:pointer;padding-bottom:1px;\">";
				$trNya .= "<td height=\"22\" class=\"tabelBorderTopLeftNull\" style=\"font-size:10px;vertical-align:top;\">";
					$trNya .= "<button class=\"btnStandar\" id=\"btnDelList\" title=\"DELETE LIST\" onClick=\"delTempGetData('".$rows['idvoucher']."','voucher');\"> Del </button>";
				$trNya .= "</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font-size:10px;color:#096;font-weight:bold;vertical-align:top;\">".$batchNoNya."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:10px;vertical-align:top;\">".$CPublic->potongKarakter($CPublic->zerofill($rows['invno']), 18)."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:10px;vertical-align:top;\">".$rows['companyname']."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font-size:10px;vertical-align:top;\" align=\"right\">(".$rows['currency'].") ".number_format($rows['amount'],2)."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font-size:10px;vertical-align:top;\" align=\"right\">
								<input type=\"hidden\" id=\"txtIdVoucher_".$rows['idvoucher']."\" name=\"txtIdVoucher[]\" value=\"".$rows['idvoucher']."\">
								<input type=\"text\" id=\"txtRemarkVoucher_".$rows['idvoucher']."\" name=\"txtRemarkVoucher[]\" placeholder=\"Remark\" value=\"\" style=\"margin-left:3px;font-size:10px;height:18px;\">
							</td>";
			$trNya .= "</tr>";
		}
		$CKoneksiInvReg->bukaKoneksi();
	}

	if(count($arrIdPaymentAdv) > 0 AND $arrIdPaymentAdv != "")
	{
		for ($lan=0; $lan < count($arrIdPaymentAdv); $lan++)
		{
			if($idPaymentAdv == "")
			{
				$idPaymentAdv .= "'".$arrIdPaymentAdv[$lan]."'";
			}else{
				$idPaymentAdv .= ",'".$arrIdPaymentAdv[$lan]."'";
			}
		}

		$sqlPA = "SELECT * FROM datapayment 
				  WHERE st_delete = '0' AND ((voucher_status = 'Y' AND st_transferToAcct = 'N' AND st_payment_list = 'N') OR (settlement_voucher_status = 'Y' AND settlement_transferToAcct = 'N')) AND (transno IN (".$idPaymentAdv.") AND settlement_transno IN (".$idPaymentAdv.")) ORDER BY 0+transno DESC;";

		$queryPA = $CKoneksiPaymentVoucher->mysqlQuery($sqlPA, $CKoneksiPaymentVoucher->bukaKoneksi());

		while($row = $CKoneksiPaymentVoucher->mysqlFetch($queryPA))
		{
			if($row['settlement_transno'] > 0)
			{
				$row['transno'] = $row['settlement_transno'];
			}

			$tempDataPA[$row['transno']]['companyname'][] = $row['company_name'];
			$tempDataPA[$row['transno']]['sendervendor'][] = $row['sendervendor'];
			$tempDataPA[$row['transno']]['invNo'][] = $row['mailinvno'];
			$tempDataPA[$row['transno']]['currency'][] = $row['currency'];
			$tempDataPA[$row['transno']]['amount'][] = $row['amount'];
			$tempDataPA[$row['transno']]['file_upload'][] = $row['file_upload'];
			$tempDataPA[$row['transno']]['tglexp'][] = $row['invoice_due_date'];
			$tempDataPA[$row['transno']]['bankcode'][] = $row['voucher_bank'];
			$tempDataPA[$row['transno']]['transNoSettlement'][] = $row['settlement_transno'];
			$tempDataPA[$row['transno']]['amountSettlement'][] = $row['settlement_voucher_amountpaid'];
		}

		foreach ($tempDataPA as $key => $val)
		{
			$companyNya = "";
			$invNoNya = "";
			$curr = "";
			$total = 0;
			$deduc = 0;
			$btnNya = "";
			$transNoSettlement = 0;
			$totalSett = 0;
			$amountNya = 0;

			for ($lan = 0; $lan < count($val['companyname']); $lan++)
			{
				$companyNya = $val['companyname'][$lan];
				$curr = $val['currency'][$lan];
				$total = $total + $val['amount'][$lan];
				$transNoSettlement = $val['transNoSettlement'][$lan];
				// $totalSett = $totalSett + $val['amountSettlement'][$lan];
				$totalSett = $val['amountSettlement'][$lan];

				if($invNoNya == "")
				{
					$invNoNya = "- ".$val['invNo'][$lan];
					if($val['file_upload'][$lan] != "")
					{
						$invNoNya = "- <a href=\"./../../paymentAdvance/templates/fileUpload/".$val['file_upload'][$lan]."\" target=\"_blank\" title=\"View File\">".$CPublic->potongKarakter($CPublic->zerofill($val['invNo'][$lan]), 18)."</a>";
					}
				}else{
					if($val['file_upload'][$lan] != "")
					{
						$invNoNya .= "<br>- <a href=\"./../../paymentAdvance/templates/fileUpload/".$val['file_upload'][$lan]."\" target=\"_blank\" title=\"View File\">".$CPublic->potongKarakter($CPublic->zerofill($val['invNo'][$lan]), 18)."</a>";
					}else{
						$invNoNya .= "<br>- ".$val['invNo'][$lan];
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
			}else{
				$trnsNo = "PA-".$CPublic->zerofill($key);
				$amountNya = $total;
			}

			$trNya .= "<tr align=\"center\" valign=\"bottom\" bgcolor=\"".$rowColor."\" onMouseOver=\"this.style.backgroundColor='#D9EDFF';\" onMouseOut=\"this.style.backgroundColor='".$rowColor."';\" style=\"cursor:pointer;padding-bottom:1px;\">";
				$trNya .= "<td height=\"22\" class=\"tabelBorderTopLeftNull\" style=\"font-size:10px;vertical-align:top;\">";
					$trNya .= "<button class=\"btnStandar\" id=\"btnDelList\" title=\"DELETE LIST\" onClick=\"delTempGetData('".$cekTransNo."','paymentAdvance');\"> Del </button>";
				$trNya .= "</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font-size:10px;color:#096;font-weight:bold;vertical-align:top;\">".$trnsNo."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:10px;vertical-align:top;\">".$invNoNya."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"left\" style=\"font-size:10px;vertical-align:top;\">".$companyNya."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" align=\"right\" style=\"font-size:10px;vertical-align:top;\">(".$curr.") ".number_format($amountNya,2)."</td>";
				$trNya .= "<td class=\"tabelBorderTopLeftNull\" style=\"font-size:10px;vertical-align:top;\" align=\"right\">
								<input type=\"hidden\" id=\"txtTransNoPA_".$key."\" name=\"txtTransNoPA[]\" value=\"".$key."\">
								<input type=\"text\" id=\"txtRemarkPA_".$key."\" name=\"txtRemarkPA[]\" placeholder=\"Remark\" value=\"\" style=\"margin-left:3px;font-size:10px;height:18px;\">
							</td>";
			$trNya .= "</tr>";
		}
	}

	print $trNya;
}

if($aksiPost == "sendToPaymentList")
{
	$arrTransNo = $_POST['arrTransNos'];
	$arrRemark = $_POST['arrRemark'];
	$arrVoucher = $_POST['arrVoucher'];
	$arrRemarkVoucher = $_POST['arrRemarkVoucher'];
	$ttlPaymentAdv = $_POST['ttlPaymentAdv'];
	$remarkPaymentAdv = $_POST['remarkPaymentAdv'];
	$txtDateBatch = $_POST['txtDateBatch'];
	$arrMailInvId = $_POST['arrMailInvId'];
	$remarkByPass = $_POST['remarkByPass'];
	// $dateNow = date("Y-m-d");
	$dateNow = $txtDateBatch;
	$tempTransNo = array();
	$tempRemark = array();
	$tempVoucher = array();
	$tempRemarkVoucher = array();
	$tempTransNoPaymentAdv = array();
	$tempRemarkPaymentAdv = array();
	$tempInvMailId = array();
	$tempRemarkByPass = array();

	$tempTransNo = explode("*",$arrTransNo);
	$tempRemark = explode("*",$arrRemark);
	$tempVoucher = explode("*",$arrVoucher);
	$tempRemarkVoucher = explode("*",$arrRemarkVoucher);
	$tempTransNoPaymentAdv = explode("*",$ttlPaymentAdv);
	$tempRemarkPaymentAdv = explode("*",$remarkPaymentAdv);
	$tempInvMailId = explode("*",$arrMailInvId);
	$tempRemarkByPass = explode("*",$remarkByPass);

	for ($lan=0; $lan < count($tempTransNo); $lan++)
	{
		$txtRemark = "";
		if($tempRemark[$lan] != "-")
		{
			$txtRemark = $tempRemark[$lan];
		}

		$sql = "UPDATE mailinvoice SET st_payment_list = 'Y', date_send_paymentlist = '".$dateNow."', remark_paymentlist = '".$txtRemark."', delete_user_paymentlist = '', delete_date_paymentlist = '0000-00-00'
			WHERE transno = '".$tempTransNo[$lan]."' AND paid='N' AND st_payment_list = 'N' AND deletests=0";

		$CKoneksiInvReg->mysqlQuery($sql);
	}

	for ($lan=0; $lan < count($tempInvMailId); $lan++)
	{
		$txtRemark = "";
		if($tempRemarkByPass[$lan] != "-")
		{
			$txtRemark = $tempRemarkByPass[$lan];
		}

		$sql = "UPDATE mailinvoice SET st_payment_list = 'Y', date_send_paymentlist = '".$dateNow."', remark_paymentlist = '".$txtRemark."', delete_user_paymentlist = '', delete_date_paymentlist = '0000-00-00'
			WHERE idmailinv = '".$tempInvMailId[$lan]."' AND paid='N' AND st_payment_list = 'N' AND deletests=0";

		$CKoneksiInvReg->mysqlQuery($sql);
	}

	for ($lan=0; $lan < count($tempVoucher); $lan++)
	{
		$txtRemarkVoucher = "";
		if($tempRemarkVoucher[$lan] != "-")
		{
			$txtRemarkVoucher = $tempRemarkVoucher[$lan];
		}

		$sqlVoucher = "UPDATE tblvoucher SET st_payment_list = 'Y', date_send_paymentlist = '".$dateNow."', remark_paymentlist = '".$txtRemarkVoucher."', delete_user_paymentlist = '', delete_date_paymentlist = '0000-00-00'
			WHERE idvoucher = '".$tempVoucher[$lan]."' AND trfacct='N' AND st_payment_list = 'N' AND deletests=0";

		$CKoneksiVoucher->bukaKoneksi();
		$CKoneksiVoucher->mysqlQuery($sqlVoucher);
	}

	for ($lan=0; $lan < count($tempTransNoPaymentAdv); $lan++)
	{
		$CKoneksiPaymentVoucher->bukaKoneksi();
		$txtRemark = "";
		if($tempRemarkPaymentAdv[$lan] != "-")
		{
			$txtRemark = $tempRemarkPaymentAdv[$lan];
		}

		$sqlPA = "SELECT settlement_transno FROM datapayment WHERE st_delete = '0' AND (transno IN (".$tempTransNo[$lan].") OR settlement_transno IN (".$tempTransNo[$lan]."))";
		$valPA = $CKoneksiPaymentVoucher->mysqlQuery($sqlPA, $CKoneksiPaymentVoucher->bukaKoneksi());

		while($row = $CKoneksiPaymentVoucher->mysqlFetch($valPA))
		{
			if($row['settlement_transno'] > 0)
			{
				$sql = "UPDATE datapayment SET settlement_st_payment_list = 'Y', settlement_date_send_paymentlist = '".$dateNow."', settlement_remark_paymentlist = '".$txtRemark."'
						WHERE st_delete = '0' AND settlement_transno = '".$row['settlement_transno']."' ";
			}else{
				$sql = "UPDATE datapayment SET st_payment_list = 'Y', date_send_paymentlist = '".$dateNow."', remark_paymentlist = '".$txtRemark."'
						WHERE st_delete = '0' AND voucher_status = 'Y' AND st_transferToAcct = 'N' AND st_payment_list = 'N' AND transno = '".$tempTransNo[$lan]."' ";
			}
			$CKoneksiPaymentVoucher->mysqlQuery($sql);
		}
	}

	$CKoneksiInvReg->bukaKoneksi();
}

if($aksiPost == "getSearchThnBlnBatch")
{
	$tempThnBln = array();
	$opt = "";

	$opt.="<option value=\"all\">All</option>";

	$sql = "SELECT date_send_paymentlist FROM mailinvoice WHERE SUBSTR(barcode, 1, 1)='A' AND preparepayment='Y' AND deletests=0 AND paid = 'N' AND st_payment_list = 'Y' GROUP BY YEAR(date_send_paymentlist),MONTH(date_send_paymentlist) ORDER BY date_send_paymentlist DESC ;";
		
	$query = $CKoneksiInvReg->mysqlQuery($sql, $CKoneksiInvReg->bukaKoneksi());
	while($row = $CKoneksiInvReg->mysqlFetch($query))
	{
		$tempDate = explode("-",$row['date_send_paymentlist']);
		$thn = $tempDate[0];
		$bln = $tempDate[1];
		
		$tempThnBln[] = $thn.$bln;
	}

	$sqlVoucher = "	SELECT date_send_paymentlist FROM tblvoucher
					WHERE deletests = '0'
					AND trfacct = 'N'
					AND st_payment_list = 'Y'
					GROUP BY YEAR( date_send_paymentlist ) , MONTH( date_send_paymentlist )
					ORDER BY date_send_paymentlist DESC ";
	$queryVoucher = $CKoneksiVoucher->mysqlQuery($sqlVoucher, $CKoneksiVoucher->bukaKoneksi());

	while($rowv = $CKoneksiVoucher->mysqlFetch($queryVoucher))
	{
		$tempDates = explode("-",$rowv['date_send_paymentlist']);
		$thns = $tempDates[0];
		$blns = $tempDates[1];

		if(!in_array($thns.$blns,$tempThnBln))
		{
			$tempThnBln[] = $thns.$blns;
		}
	}

	arsort($tempThnBln);//order by value arraynya

	foreach ($tempThnBln as $key => $val)
	{
		$opt.="<option value=\"".$val."\">".$val."</option>";
	}

	$CKoneksiInvReg->bukaKoneksi();
	print $opt;
}

if($aksiPost == "getBatchNoTglPaymentList")
{
	$tempThnBln = $_POST['thnBln'];
	$tempTgl = array();

	$thn = substr($tempThnBln, 0,4);
	$bln = substr($tempThnBln, 4,2);

	$opt = "";

	$sql = "SELECT date_send_paymentlist FROM mailinvoice WHERE SUBSTR(barcode, 1, 1)='A' AND preparepayment='Y' AND deletests=0 AND paid = 'N' AND st_payment_list = 'Y' AND YEAR(date_send_paymentlist) = '".$thn."' AND MONTH(date_send_paymentlist) = '".$bln."' GROUP BY date_send_paymentlist ORDER BY date_send_paymentlist DESC ;";
		
	$query = $CKoneksiInvReg->mysqlQuery($sql, $CKoneksiInvReg->bukaKoneksi());
	while($row = $CKoneksiInvReg->mysqlFetch($query))
	{
		$tempDate = explode("-",$row['date_send_paymentlist']);

		$tgl = $tempDate[2];

		$tempTgl[] = $tgl;
	}

	$sqlVoucher = "	SELECT date_send_paymentlist FROM tblvoucher
					WHERE deletests = '0'
					AND YEAR(date_send_paymentlist) = '".$thn."' AND MONTH(date_send_paymentlist) = '".$bln."'
					AND trfacct = 'N'
					AND st_payment_list = 'Y'
					GROUP BY date_send_paymentlist ORDER BY date_send_paymentlist DESC ";

	$queryVoucher = $CKoneksiVoucher->mysqlQuery($sqlVoucher, $CKoneksiVoucher->bukaKoneksi());

	while($rowv = $CKoneksiVoucher->mysqlFetch($queryVoucher))
	{
		$tempDates = explode("-",$rowv['date_send_paymentlist']);
		$tgl = $tempDates[2];

		if(!in_array($tgl,$tempTgl))
		{
			$tempTgl[] = $tgl;
		}
	}

	arsort($tempTgl);//order by value arraynya

	foreach ($tempTgl as $key => $val)
	{
		$opt.="<option value=\"".$val."\">".$val."</option>";
	}
	$CKoneksiInvReg->bukaKoneksi();
	print $opt;
}

if($aksiPost == "delPaymentList")
{
	$status = "Success..!!";
	$idDel = $_POST['transNoDel'];
	$typeNya = $_POST['type'];
	$dateNow = date("Y-m-d");
	
	if($typeNya == "invreg")
	{
		$sql = "UPDATE mailinvoice SET st_payment_list = 'N', date_send_paymentlist = '0000-00-00', remark_paymentlist = '', delete_user_paymentlist = '".$userFullnm."', delete_date_paymentlist = '".$dateNow."'
				WHERE transno = ".$idDel." AND deletests=0";

		$CKoneksiInvReg->mysqlQuery($sql);
	}
	if($typeNya == "invregByPass")
	{
		$sql = "UPDATE mailinvoice SET st_payment_list = 'N', date_send_paymentlist = '0000-00-00', remark_paymentlist = '', delete_user_paymentlist = '".$userFullnm."', delete_date_paymentlist = '".$dateNow."'
				WHERE idmailinv = ".$idDel." AND deletests=0";

		$CKoneksiInvReg->mysqlQuery($sql);
	}
	else if($typeNya == "paymentAdvance")
	{
		$CKoneksiPaymentVoucher->bukaKoneksi();

		$sqlPA = "SELECT settlement_transno FROM datapayment WHERE st_delete = '0' AND (transno IN (".$idDel.") OR settlement_transno IN (".$idDel."))";
		$valPA = $CKoneksiPaymentVoucher->mysqlQuery($sqlPA, $CKoneksiPaymentVoucher->bukaKoneksi());
		while($row = $CKoneksiPaymentVoucher->mysqlFetch($valPA))
		{
			if($row['settlement_transno'] > 0)
			{
				$sql = "UPDATE datapayment SET settlement_st_payment_list = 'N', st_payment_list = 'N', settlement_date_send_paymentlist = '0000-00-00', settlement_remark_paymentlist = '' 
				WHERE settlement_transno = ".$idDel." AND st_delete=0";
			}else{
				$sql = "UPDATE datapayment SET st_payment_list = 'N', date_send_paymentlist = '0000-00-00', remark_paymentlist = '' 
				WHERE transno = ".$idDel." AND st_delete=0";
			}
			$CKoneksiPaymentVoucher->mysqlQuery($sql);
		}
	}else{
		$sql = "UPDATE tblvoucher SET st_payment_list = 'N', date_send_paymentlist = '0000-00-00', remark_paymentlist = '', delete_user_paymentlist = '".$userFullnm."', delete_date_paymentlist = '".$dateNow."',approve_voucher = 'N'
				WHERE idvoucher = ".$idDel." AND deletests=0";

		$CKoneksiVoucher->bukaKoneksi();
		$CKoneksiVoucher->mysqlQuery($sql);
	}
	$CKoneksiInvReg->bukaKoneksi();
	print_r($status);
}

if($aksiPost == "actionExportPaymentList")
{
	$txtBatchNoExport = $_POST['txtBatchNoExport'];
	$tempData = array();
	$whereNya = "";
	$wherePA = "";
	$lblTglPeriode = "DATA PAYMENT LIST";
	$noTemp = 1;

	if($txtBatchNoExport == "all")
	{
		$whereNya = "";
	}else{
		$tglblnthn = $txtBatchNoExport;

		$whereNya = " AND date_send_paymentlist = '".$tglblnthn."'";
		$wherePA .= " AND (date_send_paymentlist = '".$tglblnthn."' OR settlement_date_send_paymentlist = '".$tglblnthn."') ";
	}
	ob_start();

	// $sql = " SELECT idmailinv, sendervendor1, sendervendor2name, company, companyname, currency, SUM( amount ) AS amount, SUM( addi ) AS addi, SUM( deduc ) AS deduc, transno, date_send_paymentlist, remark_paymentlist,bankcode FROM mailinvoice WHERE SUBSTR(barcode, 1, 1)='A' AND (preparepayment='Y' OR st_tobepaid = 'Y') AND deletests=0 AND paid = 'N' AND st_payment_list = 'Y' ".$whereNya." GROUP BY transno ORDER BY companyname ASC,date_send_paymentlist DESC ";

	$sql = " SELECT idmailinv, sendervendor1, sendervendor2name, company, companyname, currency, SUM( amount ) AS amount, SUM( addi ) AS addi, SUM( deduc ) AS deduc, transno, date_send_paymentlist, remark_paymentlist,bankcode,st_tobepaid,remark,tglexp FROM mailinvoice WHERE SUBSTR(barcode, 1, 1)='A' AND (preparepayment='Y' OR st_tobepaid = 'Y') AND deletests=0 AND paid = 'N' AND st_payment_list = 'Y' ".$whereNya." GROUP BY if( transno =0, idmailinv, transno ) ORDER BY companyname ASC,date_send_paymentlist DESC ";

	$query = $CKoneksiInvReg->mysqlQuery($sql, $CKoneksiInvReg->bukaKoneksi());
	while($row = $CKoneksiInvReg->mysqlFetch($query))
	{
		$senderVendor = $row['sendervendor1'];
		$trnsNo = $row['transno'];

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
		$tglExp = $CPublic->convTglPO($row['tglexp']);

		// if($row['transno'] > 0 AND $row['st_tobepaid'] == "N")
		if($row['transno'] > 0)
		{
			$remark = $CInvReg->getRemarkMailInv($row['transno']);
		}

		if($row['st_tobepaid'] == 'Y' AND $row['transno'] < 0)
		{
			$remark = $row['remark'];
		}

		if($row['transno'] > 0)
		{
			$tglExp = $CInvReg->getExpDateMailInv($row['transno']);
		}

		$tempData[strtoupper($row['companyname'])][$bankName][$trnsNo][$noTemp]['companyname'] = $row['companyname'];
		$tempData[strtoupper($row['companyname'])][$bankName][$trnsNo][$noTemp]['currency'] = $row['currency'];
		$tempData[strtoupper($row['companyname'])][$bankName][$trnsNo][$noTemp]['amount'] = ($row['amount'] + $row['addi']) - $row['deduc'];
		$tempData[strtoupper($row['companyname'])][$bankName][$trnsNo][$noTemp]['addi'] = $row['addi'];
		$tempData[strtoupper($row['companyname'])][$bankName][$trnsNo][$noTemp]['deduc'] = $row['deduc'];
		$tempData[strtoupper($row['companyname'])][$bankName][$trnsNo][$noTemp]['transno'] = $row['transno'];
		$tempData[strtoupper($row['companyname'])][$bankName][$trnsNo][$noTemp]['batchno'] = $tglblnthn;
		$tempData[strtoupper($row['companyname'])][$bankName][$trnsNo][$noTemp]['date_send_paymentlist'] = $row['date_send_paymentlist'];
		// $tempData[strtoupper($row['companyname'])][$bankName][$noTemp]['remark_paymentlist'] = $row['remark_paymentlist'];
		$tempData[strtoupper($row['companyname'])][$bankName][$trnsNo][$noTemp]['remark_paymentlist'] = $remark;
		$tempData[strtoupper($row['companyname'])][$bankName][$trnsNo][$noTemp]['senderVendor'] = $senderVendor;
		$tempData[strtoupper($row['companyname'])][$bankName][$trnsNo][$noTemp]['bankcode'] = $row['bankcode'];
		$tempData[strtoupper($row['companyname'])][$bankName][$trnsNo][$noTemp]['settlementTransNo'] = "0";
		$tempData[strtoupper($row['companyname'])][$bankName][$trnsNo][$noTemp]['tglexp'] = $tglExp;
		$noTemp++;
	}	
	// echo "<pre>";print_r($tempData);exit;
	$sqlVoucher = "SELECT * FROM tblvoucher WHERE deletests = '0' AND trfacct = 'N' AND st_payment_list = 'Y' ".$whereNya." ORDER BY batchno DESC; ";
	
	$queryVoucher = $CKoneksiVoucher->mysqlQuery($sqlVoucher, $CKoneksiVoucher->bukaKoneksi());
	while($rows = $CKoneksiVoucher->mysqlFetch($queryVoucher))
	{
		$tglblnthn = str_replace("-", "", $rows['date_send_paymentlist']);
		$trnsNo = $rows['idvoucher'];

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

		$tempData[strtoupper($rows['companyname'])][$bankName][$trnsNo][$noTemp]['companyname'] = $rows['companyname'];
		$tempData[strtoupper($rows['companyname'])][$bankName][$trnsNo][$noTemp]['currency'] = $rows['currency'];
		$tempData[strtoupper($rows['companyname'])][$bankName][$trnsNo][$noTemp]['amount'] = $rows['amount'];
		$tempData[strtoupper($rows['companyname'])][$bankName][$trnsNo][$noTemp]['transno'] = "-";
		$tempData[strtoupper($rows['companyname'])][$bankName][$trnsNo][$noTemp]['batchno'] = $tglblnthn;
		$tempData[strtoupper($rows['companyname'])][$bankName][$trnsNo][$noTemp]['date_send_paymentlist'] = $rows['date_send_paymentlist'];
		// $tempData[strtoupper($rows['companyname'])][$bankName][$noTemp]['remark_paymentlist'] = $rows['remark_paymentlist'];
		$tempData[strtoupper($rows['companyname'])][$bankName][$trnsNo][$noTemp]['remark_paymentlist'] = $remarkVoucher;
		$tempData[strtoupper($rows['companyname'])][$bankName][$trnsNo][$noTemp]['senderVendor'] = $rows['kepada'];
		$tempData[strtoupper($rows['companyname'])][$bankName][$trnsNo][$noTemp]['bankcode'] = $rows['bankcode'];
		$tempData[strtoupper($rows['companyname'])][$bankName][$trnsNo][$noTemp]['settlementTransNo'] = "0";
		$tempData[strtoupper($rows['companyname'])][$bankName][$trnsNo][$noTemp]['tglexp'] = "-";
		$noTemp++;
	}

	$sqlPA = "SELECT * FROM datapayment WHERE st_delete = '0' AND ((voucher_status = 'Y' AND st_transferToAcct = 'N' AND st_payment_list = 'Y') OR (settlement_voucher_status = 'Y' AND settlement_transferToAcct = 'N' AND settlement_st_payment_list = 'Y')) ".$wherePA." ORDER BY batchno DESC;";

	$queryPA = $CKoneksiPaymentVoucher->mysqlQuery($sqlPA, $CKoneksiPaymentVoucher->bukaKoneksi());
	while($rowPA = $CKoneksiInvReg->mysqlFetch($queryPA))
	{
		$remarkNya = "";
		$tglblnthn = str_replace("-", "", $rowPA['date_send_paymentlist']);
		$transNoSettlement = $rowPA['settlement_transno'];
		$bankName = "";
		$senderVendor = "";

		$trnsNo = $rowPA['transno'];
		$amountNya = $rowPA['amount'];
		// if($rowPA['settlementTransno'][$lan] > 0)
		if($transNoSettlement > 0)
		{
			$trnsNo = $rowPA['settlement_transno'];			
			if($rowPA['settlement_voucher_bank'] != "")
			{
				$bankName = $CInvReg->detilAcctCode($rowPA['settlement_voucher_bank'],"Addrs1st");

				if($bankName == "")
				{
					$bankName = $CInvReg->detilAcctCode($rowPA['settlement_voucher_bank'],"Acctname");
				}

				$bankName = $CInvReg->convertBankNameToExcel($bankName);
			}
		}else{
			if($rowPA['voucher_bank'] != "")
			{
				$bankName = $CInvReg->detilAcctCode($rowPA['voucher_bank'],"Addrs1st");

				if($bankName == "")
				{
					$bankName = $CInvReg->detilAcctCode($rowPA['voucher_bank'],"Acctname");
				}

				$bankName = $CInvReg->convertBankNameToExcel($bankName);
			}
		}					

		if($transNoSettlement > 0)
		{
			// $tempData[strtoupper($rowPA['company_name'])][$bankName][$trnsNo][$noTemp]['bankcode'] = $rowPA['settlement_voucher_bank'];
			$bankCodeNya = $rowPA['settlement_voucher_bank'];
			$senderVendor = $rowPA['settlement_voucher_paidtofrom'];
			$trnsNo = "PA-".$CPublic->zerofill($transNoSettlement);
			$amountNya = $rowPA['settlement_voucher_amountpaid'];
			// $remarkNya = $rowPA['settlement_remark_paymentlist'];
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
			// $tempData[strtoupper($rowPA['company_name'])][$bankName][$trnsNo][$noTemp]['bankcode'] = $rowPA['voucher_bank'];
			$bankCodeNya = $rowPA['voucher_bank'];
			$senderVendor = $rowPA['voucher_paidtofrom'];
			$trnsNo = "PA-".$CPublic->zerofill($trnsNo);
			// $remarkNya = $rowPA['remark'];
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

		if($amountNya < 0){ $amountNya = $amountNya *-1; }

		$tempData[strtoupper($rowPA['company_name'])][$bankName][$trnsNo][$noTemp]['bankcode'] = $bankCodeNya;
		$tempData[strtoupper($rowPA['company_name'])][$bankName][$trnsNo][$noTemp]['companyname'] = $rowPA['company_name'];
		$tempData[strtoupper($rowPA['company_name'])][$bankName][$trnsNo][$noTemp]['currency'] = $rowPA['currency'];		
		$tempData[strtoupper($rowPA['company_name'])][$bankName][$trnsNo][$noTemp]['batchno'] = $rowPA['batchno'];
		$tempData[strtoupper($rowPA['company_name'])][$bankName][$trnsNo][$noTemp]['date_send_paymentlist'] = $rowPA['date_send_paymentlist'];
		$tempData[strtoupper($rowPA['company_name'])][$bankName][$trnsNo][$noTemp]['senderVendor'] = $senderVendor;
		$tempData[strtoupper($rowPA['company_name'])][$bankName][$trnsNo][$noTemp]['settlementTransNo'] = $rowPA['settlement_transno'];
		$tempData[strtoupper($rowPA['company_name'])][$bankName][$trnsNo][$noTemp]['transno'] = $trnsNo;
		$tempData[strtoupper($rowPA['company_name'])][$bankName][$trnsNo][$noTemp]['amount'] = $amountNya;
		$tempData[strtoupper($rowPA['company_name'])][$bankName][$trnsNo][$noTemp]['remark_paymentlist'] = $remarkNya;
		$tempData[strtoupper($rowPA['company_name'])][$bankName][$trnsNo][$noTemp]['tglexp'] = "-";

		$noTemp++;
	}

	header("Content-Type: application/vnd.ms-excel");
	echo "<table width=\"100%\">";
		echo "<tr>
				<td colspan=\"5\" rowspan=\"2\" align=\"center\" style=\"font-family:Arial;font-weight:Bold;\">
					<label>".$lblTglPeriode."</label>
				</td>
			</tr>";
	echo "</table>";

	echo "<table>";
	foreach ($tempData as $key => $val)
	{
		$grTotal = 0;
		echo "<tr style=\"border-width: 4px;background-color:#0293BA;font-weight:bold;color:#FFF;\">";
			echo "<td align=\"center\" style=\"font-family:Arial;font-weight:Bold;border-style: solid;height:30px;\">BANK</td>";
			echo "<td align=\"center\" style=\"font-family:Arial;font-weight:Bold;border-style: solid;\">".$key."</td>";
			echo "<td align=\"center\" style=\"font-family:Arial;font-weight:Bold;border-style: solid;\">KETERANGAN</td>";
			echo "<td align=\"center\" style=\"font-family:Arial;font-weight:Bold;border-style: solid;\">DUE AP</td>";
			echo "<td align=\"center\" style=\"font-family:Arial;font-weight:Bold;border-style: solid;\">DIRECT EXPENSE</td>";
		echo "</tr>";

		foreach ($val as $keys => $vals)
		{
			$countNya = count($vals);
			foreach ($vals as $keyz => $valx)
			{
				$dueAp = "-";
				$directExp = "-";
				$currNya = "";
				$amountNya = 0;
				$senderVendor = "";
				$remarkExport = "";
				$amountFormat = "";

				foreach ($valx as $keyx => $valz)
				{					
					$senderVendor = "<b>&bull; ".$valz['senderVendor']."</b>";
					$currNya = $valz['currency'];
					$amountNya = $amountNya + $valz['amount'];

					if($valz['settlementTransNo'] > 0)
					{
						$amountNya = $valz['amount'];
					}

					if($remarkExport == "")
					{
						if($valz['remark_paymentlist'] != "")
						{
							$remarkExport = "<i>- ".strtolower($valz['remark_paymentlist'])."</i>";
						}
					}else{
						if($valz['remark_paymentlist'] != "")
						{
							$remarkExport .= "<br><i>- ".strtolower($valz['remark_paymentlist'])."</i>";
						}
					}

					if($valz['tglexp'] == "-")
					{
						$directExp = "&#x2713;";
					}else{
						$dueAp = $valz['tglexp'];
						$directExp = "-";
					}

					$grTotal = $grTotal + $valz['amount'];
				}

				if(strtolower($currNya) == "idr")
				{
					$amountFormat = number_format($amountNya,0);
					$amountFormat = str_replace(",", ".", $amountFormat);
				}
				else if(strtolower($currNya) == "usd")
				{
					$amountFormat = number_format($amountNya,2);
					$amountFormat = str_replace(".", "-", $amountFormat);
					$amountFormat = str_replace(",", ".", $amountFormat);
					$amountFormat = str_replace("-", ",", $amountFormat);
				}else{
					$amountFormat = number_format($amountNya,2);
					$amountFormat = str_replace(".", "-", $amountFormat);
					$amountFormat = str_replace(",", ".", $amountFormat);
					$amountFormat = str_replace("-", ",", $amountFormat);
				}

				echo "<tr style=\"border-width: 1px;\">";

				if($countNya == count($vals))
				{
					echo "<td rowspan=\"".$countNya."\" align=\"center\" style=\"font-family:Arial;border-style: solid;\">".$keys."</td>";
				}
					echo "<td align=\"right\" style=\"font-family:Arial;border-style: solid;\"><span style=\"margin-right:100px;\">(".$valz['currency'].")</span> ".$amountFormat." </td>";
					echo "<td align=\"left\" style=\"font-family:Arial;border-style: solid;vertical-align: top;\">".$senderVendor."<br>".$remarkExport."</td>";
					echo "<td align=\"center\" style=\"font-family:Arial;border-style: solid;vertical-align: top;\">".$dueAp."</td>";
					echo "<td align=\"center\" style=\"font-family:Arial;border-style: solid;vertical-align: top;\">".$directExp."</td>";
					echo "</tr>";

				$grTotal = $grTotal + $valz['amount'];
				$countNya--;
			}
		}

		$grTotal = number_format($grTotal,0);
		$grTotal = str_replace(",", ".", $grTotal);
		// echo "<tr style=\"border-width: 1px;background-color:#CCCCCC;font-weight:bold;\">";
		// 	echo "<td style=\"border-style: solid;height:25px;\"></td>";
		// 	echo "<td align=\"right\" style=\"font-family:Arial;font-weight:Bold;border-style: solid;height:25px;\">TOTAL : ".$grTotal."</td>";
		// 	echo "<td style=\"border-style: solid;\"></td>";
		// echo "</tr>";
		echo "<tr><td colspan=\"3\"></td></tr>";
	}
	echo "</table>";
	
	header("Content-disposition: attachment; filename=paymentList.xls");
	ob_end_flush();
}

if($aksiPost == "actionExportPaymentListByBank")
{
	$txtBatchNoExport = $_POST['txtBatchNoExportByBank'];
	$tempData = array();
	$whereNya = "";
	$wherePA = "";
	$lblTglPeriode = "DATA PAYMENT LIST";
	$noTemp = 1;

	if($txtBatchNoExport == "all")
	{
		$whereNya = "";
	}else{
		$tglblnthn = $txtBatchNoExport;

		$whereNya = " AND date_send_paymentlist = '".$tglblnthn."'";
		$wherePA .= " AND (date_send_paymentlist = '".$tglblnthn."' OR settlement_date_send_paymentlist = '".$tglblnthn."') ";
	}
	ob_start();

	$sql = " SELECT idmailinv, sendervendor1, sendervendor2name, company, companyname, currency, SUM( amount ) AS amount, SUM( addi ) AS addi, SUM( deduc ) AS deduc, transno, date_send_paymentlist, remark_paymentlist,bankcode,st_tobepaid,remark FROM mailinvoice WHERE SUBSTR(barcode, 1, 1)='A' AND (preparepayment='Y' OR st_tobepaid = 'Y') AND deletests=0 AND paid = 'N' AND st_payment_list = 'Y' ".$whereNya." GROUP BY if( transno =0, idmailinv, transno ) ORDER BY companyname ASC,date_send_paymentlist DESC ";

	$query = $CKoneksiInvReg->mysqlQuery($sql, $CKoneksiInvReg->bukaKoneksi());
	while($row = $CKoneksiInvReg->mysqlFetch($query))
	{
		$senderVendor = $row['sendervendor1'];
		$trnsNo = $row['transno'];

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
		if($row['transno'] > 0 AND $row['st_tobepaid'] == "N")
		{
			$remark = $CInvReg->getRemarkMailInv($row['transno']);
		}

		if($row['st_tobepaid'] == 'Y')
		{
			$remark = $row['remark'];
		}

		$tempData[strtoupper($bankName)][strtoupper($row['companyname'])][$trnsNo][$noTemp]['companyname'] = $row['companyname'];
		$tempData[strtoupper($bankName)][strtoupper($row['companyname'])][$trnsNo][$noTemp]['currency'] = $row['currency'];
		$tempData[strtoupper($bankName)][strtoupper($row['companyname'])][$trnsNo][$noTemp]['amount'] = ($row['amount'] + $row['addi']) - $row['deduc'];
		$tempData[strtoupper($bankName)][strtoupper($row['companyname'])][$trnsNo][$noTemp]['addi'] = $row['addi'];
		$tempData[strtoupper($bankName)][strtoupper($row['companyname'])][$trnsNo][$noTemp]['deduc'] = $row['deduc'];
		$tempData[strtoupper($bankName)][strtoupper($row['companyname'])][$trnsNo][$noTemp]['transno'] = $row['transno'];
		$tempData[strtoupper($bankName)][strtoupper($row['companyname'])][$trnsNo][$noTemp]['batchno'] = $tglblnthn;
		$tempData[strtoupper($bankName)][strtoupper($row['companyname'])][$trnsNo][$noTemp]['date_send_paymentlist'] = $row['date_send_paymentlist'];
		$tempData[strtoupper($bankName)][strtoupper($row['companyname'])][$trnsNo][$noTemp]['remark_paymentlist'] = $remark;
		$tempData[strtoupper($bankName)][strtoupper($row['companyname'])][$trnsNo][$noTemp]['senderVendor'] = $senderVendor;
		$tempData[strtoupper($bankName)][strtoupper($row['companyname'])][$trnsNo][$noTemp]['bankcode'] = $row['bankcode'];
		$tempData[strtoupper($bankName)][strtoupper($row['companyname'])][$trnsNo][$noTemp]['settlementTransNo'] = "0";
		$noTemp++;
	}
	
	$sqlVoucher = "SELECT * FROM tblvoucher WHERE deletests = '0' AND trfacct = 'N' AND st_payment_list = 'Y' ".$whereNya." ORDER BY batchno DESC; ";
	
	$queryVoucher = $CKoneksiVoucher->mysqlQuery($sqlVoucher, $CKoneksiVoucher->bukaKoneksi());
	while($rows = $CKoneksiVoucher->mysqlFetch($queryVoucher))
	{
		$tglblnthn = str_replace("-", "", $rows['date_send_paymentlist']);
		$trnsNo = $rows['idvoucher'];

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

		$tempData[strtoupper($bankName)][strtoupper($rows['companyname'])][$trnsNo][$noTemp]['companyname'] = $rows['companyname'];
		$tempData[strtoupper($bankName)][strtoupper($rows['companyname'])][$trnsNo][$noTemp]['currency'] = $rows['currency'];
		$tempData[strtoupper($bankName)][strtoupper($rows['companyname'])][$trnsNo][$noTemp]['amount'] = $rows['amount'];
		$tempData[strtoupper($bankName)][strtoupper($rows['companyname'])][$trnsNo][$noTemp]['transno'] = "-";
		$tempData[strtoupper($bankName)][strtoupper($rows['companyname'])][$trnsNo][$noTemp]['batchno'] = $tglblnthn;
		$tempData[strtoupper($bankName)][strtoupper($rows['companyname'])][$trnsNo][$noTemp]['date_send_paymentlist'] = $rows['date_send_paymentlist'];
		$tempData[strtoupper($bankName)][strtoupper($rows['companyname'])][$trnsNo][$noTemp]['remark_paymentlist'] = $remarkVoucher;
		$tempData[strtoupper($bankName)][strtoupper($rows['companyname'])][$trnsNo][$noTemp]['senderVendor'] = $rows['kepada'];
		$tempData[strtoupper($bankName)][strtoupper($rows['companyname'])][$trnsNo][$noTemp]['bankcode'] = $rows['bankcode'];
		$tempData[strtoupper($bankName)][strtoupper($rows['companyname'])][$trnsNo][$noTemp]['settlementTransNo'] = "0";
		$noTemp++;
	}
	
	$sqlPA = "SELECT * FROM datapayment WHERE st_delete = '0' AND ((voucher_status = 'Y' AND st_transferToAcct = 'N' AND st_payment_list = 'Y') OR (settlement_voucher_status = 'Y' AND settlement_transferToAcct = 'N' AND settlement_st_payment_list = 'Y')) ".$wherePA." ORDER BY batchno DESC;";

	$queryPA = $CKoneksiPaymentVoucher->mysqlQuery($sqlPA, $CKoneksiPaymentVoucher->bukaKoneksi());
	while($rowPA = $CKoneksiInvReg->mysqlFetch($queryPA))
	{
		$remarkNya = "";
		$tglblnthn = str_replace("-", "", $rowPA['date_send_paymentlist']);
		$transNoSettlement = $rowPA['settlement_transno'];
		$bankName = "";
		$senderVendor = "";

		if($transNoSettlement > 0)
		{
			if($rowPA['settlement_voucher_bank'] != "")
			{
				$bankName = $CInvReg->detilAcctCode($rowPA['settlement_voucher_bank'],"Addrs1st");

				if($bankName == "")
				{
					$bankName = $CInvReg->detilAcctCode($rowPA['settlement_voucher_bank'],"Acctname");
				}

				$bankName = $CInvReg->convertBankNameToExcel($bankName);
			}
		}else{
			if($rowPA['voucher_bank'] != "")
			{
				$bankName = $CInvReg->detilAcctCode($rowPA['voucher_bank'],"Addrs1st");

				if($bankName == "")
				{
					$bankName = $CInvReg->detilAcctCode($rowPA['voucher_bank'],"Acctname");
				}

				$bankName = $CInvReg->convertBankNameToExcel($bankName);
			}
		}
		
		$trnsNo = $rowPA['transno'];
		$amountNya = $rowPA['amount'];		

		if($transNoSettlement > 0)
		{			
			// $tempData[strtoupper($bankName)][strtoupper($rowPA['company_name'])][$noTemp]['bankcode'] = $rowPA['settlement_voucher_bank'];
			$bankCodeNya = $rowPA['settlement_voucher_bank'];
			$senderVendor = $rowPA['settlement_voucher_paidtofrom'];

			$trnsNo = "PA-".$CPublic->zerofill($transNoSettlement);
			$amountNya = $rowPA['settlement_voucher_amountpaid'];
			//$remarkNya = $rowPA['settlement_remark_paymentlist'];
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
			// $tempData[strtoupper($bankName)][strtoupper($rowPA['company_name'])][$noTemp]['bankcode'] = $rowPA['voucher_bank'];
			$bankCodeNya = $rowPA['voucher_bank'];
			$senderVendor = $rowPA['voucher_paidtofrom'];
			
			$trnsNo = "PA-".$CPublic->zerofill($trnsNo);
			//$remarkNya = $rowPA['remark'];
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

		if($amountNya < 0){ $amountNya = $amountNya *-1; }

		$tempData[strtoupper($bankName)][strtoupper($rowPA['company_name'])][$trnsNo][$noTemp]['bankcode'] = $bankCodeNya;
		$tempData[strtoupper($bankName)][strtoupper($rowPA['company_name'])][$trnsNo][$noTemp]['companyname'] = $rowPA['company_name'];
		$tempData[strtoupper($bankName)][strtoupper($rowPA['company_name'])][$trnsNo][$noTemp]['currency'] = $rowPA['currency'];		
		$tempData[strtoupper($bankName)][strtoupper($rowPA['company_name'])][$trnsNo][$noTemp]['batchno'] = $rowPA['batchno'];
		$tempData[strtoupper($bankName)][strtoupper($rowPA['company_name'])][$trnsNo][$noTemp]['date_send_paymentlist'] = $rowPA['date_send_paymentlist'];
		$tempData[strtoupper($bankName)][strtoupper($rowPA['company_name'])][$trnsNo][$noTemp]['senderVendor'] = $senderVendor;
		$tempData[strtoupper($bankName)][strtoupper($rowPA['company_name'])][$trnsNo][$noTemp]['settlementTransNo'] = $rowPA['settlement_transno'];
		$tempData[strtoupper($bankName)][strtoupper($rowPA['company_name'])][$trnsNo][$noTemp]['transno'] = $trnsNo;
		$tempData[strtoupper($bankName)][strtoupper($rowPA['company_name'])][$trnsNo][$noTemp]['amount'] = $amountNya;
		$tempData[strtoupper($bankName)][strtoupper($rowPA['company_name'])][$trnsNo][$noTemp]['remark_paymentlist'] = $remarkNya;

		$noTemp++;
	}
	
	header("Content-Type: application/vnd.ms-excel");
	echo "<table width=\"100%\">";
		echo "<tr>
				<td colspan=\"3\" rowspan=\"2\" align=\"center\" style=\"font-family:Arial;font-weight:Bold;\">
					<label>".$lblTglPeriode."</label>
				</td>
			</tr>";
	echo "</table>";

	echo "<table>";
	
	foreach ($tempData as $key => $val)
	{
		$grTotal = 0;
		echo "<tr style=\"border-width: 4px;background-color:#0293BA;font-weight:bold;color:#FFF;\">";
			echo "<td align=\"center\" style=\"font-family:Arial;font-weight:Bold;border-style: solid;height:30px;\">COMPANY</td>";
			echo "<td align=\"center\" style=\"font-family:Arial;font-weight:Bold;border-style: solid;\">BANK ".$key."</td>";
			echo "<td align=\"center\" style=\"font-family:Arial;font-weight:Bold;border-style: solid;\">KETERANGAN</td>";
		echo "</tr>";
		foreach ($val as $keys => $vals)
		{
			$countNya = count($vals);
			foreach ($vals as $keyz => $valx)
			{
				$dueAp = "-";
				$directExp = "-";
				$currNya = "";
				$amountNya = 0;
				$senderVendor = "";
				$remarkExport = "";
				$amountFormat = "";

				foreach ($valx as $keyx => $valz)
				{
					$senderVendor = "<b>&bull; ".$valz['senderVendor']."</b>";
					$currNya = $valz['currency'];
					$amountNya = $amountNya + $valz['amount'];

					if($valz['settlementTransNo'] > 0)
					{
						$amountNya = $valz['amount'];
					}

					if($remarkExport == "")
					{
						if($valz['remark_paymentlist'] != "")
						{
							$remarkExport = "<i>- ".strtolower($valz['remark_paymentlist'])."</i>";
						}
					}else{
						if($valz['remark_paymentlist'] != "")
						{
							$remarkExport .= "<br><i>- ".strtolower($valz['remark_paymentlist'])."</i>";
						}
					}

					if($valz['tglexp'] == "-")
					{
						$directExp = "&#x2713;";
					}else{
						$dueAp = $valz['tglexp'];
						$directExp = "-";
					}

					$grTotal = $grTotal + $valz['amount'];
				}

				if(strtolower($currNya) == "idr")
				{
					$amountFormat = number_format($amountNya,0);
					$amountFormat = str_replace(",", ".", $amountFormat);
				}
				else if(strtolower($currNya) == "usd")
				{
					$amountFormat = number_format($amountNya,2);
					$amountFormat = str_replace(".", "-", $amountFormat);
					$amountFormat = str_replace(",", ".", $amountFormat);
					$amountFormat = str_replace("-", ",", $amountFormat);
				}else{
					$amountFormat = number_format($amountNya,2);
					$amountFormat = str_replace(".", "-", $amountFormat);
					$amountFormat = str_replace(",", ".", $amountFormat);
					$amountFormat = str_replace("-", ",", $amountFormat);
				}

				echo "<tr style=\"border-width: 1px;\">";

				if($countNya == count($vals))
				{
					echo "<td rowspan=\"".$countNya."\" align=\"center\" style=\"font-family:Arial;border-style: solid;\">".$keys."</td>";
				}
					echo "<td align=\"right\" style=\"font-family:Arial;border-style: solid;\"><span style=\"margin-right:100px;\">(".$currNya.")</span> ".$amountFormat." </td>";
					echo "<td align=\"left\" style=\"font-family:Arial;border-style: solid;\">".$senderVendor."<br>".$remarkExport."</td>";
				echo "</tr>";

				$grTotal = $grTotal + $valz['amount'];
				$countNya--;
			}
		}

		$grTotal = number_format($grTotal,0);
		$grTotal = str_replace(",", ".", $grTotal);
		echo "<tr><td colspan=\"3\"></td></tr>";
	}
	echo "</table>";

	header("Content-disposition: attachment; filename=paymentList.xls");
	ob_end_flush();
}

if($aksiPost == "paidByPass")
{
	$status = "Success..!!";
	$id = $_POST['id'];
	$type = $_POST['type'];
	$dateNow = date("Y-m-d");

	if($type == "invreg")
	{
		$sql = "UPDATE mailinvoice SET paid = 'Y', datepaid = '".$dateNow."', paidby = '".$userIdSession."' WHERE transno = ".$id." AND deletests=0 ";
		$CKoneksiInvReg->mysqlQuery($sql);
	}
	else if($type == "voucher")
	{
		$sql = "UPDATE tblvoucher SET trfacct = 'Y', trfacctdate = '".$dateNow."', trfacctby = '".$userIdSession."' WHERE idvoucher = ".$id." AND deletests=0 ";

		$CKoneksiVoucher->bukaKoneksi();
		$CKoneksiVoucher->mysqlQuery($sql);
	}
	else if($type == "paymentAdvance")
	{
		$advType = $_POST['typeAdv'];

		if($advType == "settlement")
		{
			$updatePlus = " settlement_transferToAcct = 'Y',settlement_transferToAcct_userId = '".$userIdSession."',settlement_transferToAcct_date = '".$dateNow."' ";
			$wherePA = " st_delete = 0 AND settlement_transno = '".$id."'";
		}else{
			$updatePlus = " st_transferToAcct = 'Y',transfer_userId = '".$userIdSession."',transfer_userDate = '".$dateNow."' ";
			$wherePA = " st_delete = 0 AND transno = '".$id."'";
		}
		$sql = "UPDATE datapayment SET ".$updatePlus." WHERE ".$wherePA;

		$CKoneksiPaymentVoucher->bukaKoneksi();
		$CKoneksiPaymentVoucher->mysqlQuery($sql);
	}
	$CKoneksiInvReg->bukaKoneksi();

	print_r($status);
}

if($aksiPost == "verifyData")
{
	$status = "Success..!!";
	$id = $_POST['id'];
	$type = $_POST['type'];
	$dateNow = date("Y-m-d");

	// print_r($id." => ".$type);exit;

	if($type == "invReg")
	{
		$CKoneksiInvReg->bukaKoneksi();
		$sql = "UPDATE mailinvoice SET st_verify = 'Y', date_verify = '".$dateNow."', idUser_verify = '".$userIdSession."' WHERE transno = ".$id." AND deletests=0 ";
		$CKoneksiInvReg->mysqlQuery($sql);
	}

	if($type == "voucher")
	{
		$CKoneksiVoucher->bukaKoneksi();
		$sql = "UPDATE tblvoucher SET st_verify = 'Y', date_verify = '".$dateNow."', idUser_verify = '".$userIdSession."' WHERE idvoucher = ".$id." AND deletests=0 ";
		$CKoneksiVoucher->mysqlQuery($sql);
	}

	print_r($status);
}



?>