<?php
require_once("configVoucher.php");
require_once($pathInvReg.'/class/CNumberWordsInd.php');


if($aksiPost == "klikPayMethod")
{
	$payMethodPost = $_POST['payMethod'];
	$lastSelBankPost = $_POST['lastSelBank'];
	
	$html = "";
	$html.= "<select id=\"bankCode\" name=\"bankCode\" class=\"elementMenu\" style=\"width:300px;height:20px;\" onchange=\"klikBankMenu(bankCode);return false;\">";
	$html.= "<option value=\"XXX\">-- PLEASE SELECT  --</option>";
	if($payMethodPost == "cash")
	{
		$query = $koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "SELECT RTRIM(Acctcode) AS Acctcode, RTRIM(Acctname) AS Acctname, source FROM dbo.AccountCode WHERE (LEN(RTRIM(Acctcode)) = 5) AND (LEFT(Acctcode, 2) = '10') AND (Contact IS NULL) AND Acctcode='10000' ORDER BY Acctname");
	}
	else
	{
		$query = $koneksiOdbcAcc->odbcExec($koneksiOdbcAccId, "SELECT RTRIM(Acctcode) AS Acctcode, RTRIM(Acctname) AS Acctname, source FROM dbo.AccountCode WHERE (LEN(RTRIM(Acctcode)) = 5) AND (LEFT(Acctcode, 2) = '10') AND (Contact IS NULL) AND (Acctcode <> '10000') ORDER BY Acctname");
	}
		
	while($row = $koneksiOdbcAcc->odbcFetch($query))
	{
		$acctCode = rtrim($row['Acctcode']);
		
		$sel = "";
		if($lastSelBankPost != "")
			if($lastSelBankPost == $acctCode)
				$sel = "selected";
		
		$html.= "<option value=\"".$acctCode."\" ".$sel.">".$acctCode."&nbsp;-&nbsp;".$row['Acctname']."&nbsp;(&nbsp;".$row['source']."&nbsp;)</option>";
		$tabel.="<option value=\"".$acctCode."\" ".$sel.">".$acctCode."&nbsp;-&nbsp;".$row['Acctname']."&nbsp;(&nbsp;".$row['source']."&nbsp;)</option>";
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
	{
		$nilai = $nilai1." dan ".$nilai2;
	}
	
	echo "<p>".strtoupper($nilai." saja")."</p>";
}

if($aksiPost == "ubahUrutan")
{
	$reqPost = $_POST['req'];
	$idDescPost = $_POST['idDesc'];
	
	$CVoucher->ubahUrutan($reqPost, $idDescPost);
}
?>