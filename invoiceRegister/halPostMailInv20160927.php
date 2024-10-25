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
		$dueDate = $CPublic->convTglNonDB( $CPublic->intervalTanggal( $CPublic->convTglDB($invoiceDatePost), $dueDayPost) );
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
		$dueDate = $CPublic->intervalTanggal( $CPublic->convTglDB($invoiceDatePost), $dueDayPost);
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
			$nilai = number_format((float)($amtConvPost - $totalAmountPost), 2, '.', ',');
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
	$accountPost = $_POST['account'];
	
	//echo $company." ".$fromDate." ".$userId;
	echo $CAging->insertSummary($userIdPost, $companyPost, $fromDatePost, $accountPost);
}
?>