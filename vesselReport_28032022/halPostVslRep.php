<?php
require_once("configVslRep.php");
require_once "data/imapx/src/imapxPHP.php";
require_once("class/clsencrypt.php");

if($aksiGet == "checkEmailEop") // SECARA PERIODIK CEK INBOX EMAIL DI VIS@ANDHIKA.COM
{
	set_time_limit(3600);
	
    $imap = new Imapx();

	$sortBy = array("desc","date");
    $inbox = $imap->getInbox(1, 1000, $sortBy); // AMBIL INBOX EMAIL DARI BARIS SATU SAMPAI 1000 DIURUTKAN BERDASARKAN DATE MASUK DAN DESCENDING
	$html = "";
	$totalEmail = 0;
	$subjectEmail = "";
	$i = 0;
	if(!empty( $inbox)) // JIKA INBOX TIDAK KOSONG
	{
		foreach ($inbox as $key => $mail) 
		{
			$subject = $mail->subject;
			if (isset($subject)) 
			{
				$explSubject = explode("##",$subject); // PISAHKAN SUBJECT BERDASARKAN KARAKTER ##, CONTOH (ANDHIKA NARESWARI##eop##20170316110851)
				if($explSubject[1] == "eop") // JIKA SUBJECT EMAIL ADALAH EOP MAKA MULAI PERHITUNGAN PERULANGAN $i
				{
					$i++;
				}
			}
			if($i == 1)
			{
				$subjectEmail = $mail->subject; // AMBIL SUBJECT EMAIL YANG BERADA DI BARIS PERTAMA
				$readMail = $imap->readMail($key); // BACA EMAIL YANG BERADA DI BARIS PERTAMA
				$message = $imap->getBody("text", true); // AMBIL TEKS YANG BERADA DI BODY EMAIL TSB (TEKS BERUPA HASIL ENCRYPT DARI KAPAL)
			}
		}
		$totalEmail = $i; // TOTAL EMAIL ADALAH TOTAL PERULANGAN $i
	}
	echo $totalEmail."#####".$subjectEmail."#####".$message; // YANG DIKIRIM ADALAH TOTAL+SUBJECT EMAIL YANG BARU MASUK, CONTOH DATA YANG DIKIRIMKAN (3##ANDHIKA NARESWARI##eop##20170316111804#####BHShoBAAw4d2lMJnR/Gmco6eK43TT4YjSz4lEfqmGhwQL4kc0lvU5WHI1ofGJrJk48V4nQ6dnahVsNCsrOnMxjLWQtxGU5MeqocjunejnBjtg47qpgG6H+nYH26BylAFDNaDoRYFZBydrMjAfXN23YZZ1II4d4POGUI5U+kMdTj8JJOPoy+GQrDfar5fNPdR3PGaL+gN5xpzTgMfcObkVRSLDdGcSk2IFQxVaDmVSBKFHeDNTBp4RNSf6dXxFTkUtj8mSYKhJaEgLX7lY2K/Jt6LCwInyHxvDIJJZO3UJBLX5p09v8xnlh65WQS+DlRN9IPnehEZmRWu8VPbb2CvdL9kZ340sjKNgG2ec3WTIpNXhWh/m6eccxjRESm1jKSdKoKuhHF+uzLknMVqTwNijgSqWY7DnCwZrvc8boka9/xBEK6fSp2OzKRMZ8NVoTOjoHNQMHMJwWRE/VftlqNcMfE5BybBVwIAbqC8+GLITZKDYvXlT1D/RZS6wbnbAf/w1Kqd85Mw43QDh2KKJHdIMAPN+/woi8wkw4MrO3NKscRgwQSk1GQUkcZik+eFweMNN+oCWF7IFX74XxJkBCh1tnlnzumGqXgn1S6U78PvQo18N1snO4UKqRk3fD+hWL+GOMhl1je5LaOKH4Z3h2Jlx3/uM8d5CKWv8NRbpvvxjlurzxHqOnaMBSr9q44XYwTqI3LRh6GlJ5A+DUF9hI5eA+pTVta8pFmY6Aw1DliaEaY5QbPmqSa7pSK6uuRGz2PbBQ3r11aMUxffwXu06c2S3Yp7YodQTPoaz//j/rTd/Ry9X+wWGjyC7RQ5glGL5s3DRGhvV0n0jmt489Kp9CshVkzeK+WsUEAAE036jKvq2YY+zabSQv95Pd9VNTerem3r+EGi8huBEu7sefSG4I2Di4HKDqeBLDMjTuRBvJlq7/ULQ1Iy5YEKkdGhEZAJPC0XrwbMggscQqDdlvaRdfyb/hKrVWmKA1P3Jwbxit076z1Qq+PuYbDHwRY2ReAv1uWnEewTGrBuoRf9iY3qUzta+G88SSVIKp3BubJHf8OhqSxUJkpFluF0niNiDU2Y6qhmdxl9+jD6TOfmYPhjKyW8IdSrwAZdAOxqOvr+gZNYjNAmlsLBsFOSAfxKY7wVRc3aKJOsqjlcMqeCS3VPYKbZSB0OMvU8Kw+yGVGlSLGOK9FdOqe0W7A2BxmASvB6ZaQ8kAM/28lo8BCEbWb8pg+ccp0mD1vlg5NNvwZmHzaAIAreLprJqmcBEq6ftWFo0+nhrII5GOcFqwDZgpLxWhkAE4u+NJIqeBPzZF2JrnSxK39+Qzb7O3jlUszI/c1WYOx07TIiRexa6xUcxyMq5IfmwOexwYh1Hxda0qXNkEbvXFawujlp8LzLPmebDp0vg/A+iT5mlaIawxVnKao2b9o7/0VGt3g4tDeAeFT3wxPMLEtxgbFOq4U5kBobJeF7wHHROyGNpjPpsTnw9Dq2rfELanWj3fw/UpXSpUunEmuH9YUMTUFmbo6qKWtKcPjXIQSG7K5QH67WotS4bupjcZj/wIc4+Avfe/j8jwAUWWjcbz1PJXRKmYYeJGk6Mvr+s69CzxRJkRpwvGIJNA4sIB6w85rWWJllSzYZiYIVXD8TL6T5iL0VCfM5lV46fRDv7FgGzd4KArr9PihR9FRkwYPajfVxUPlDS/FhIekJoIYJ3+JlxKtO30gvjk1WOSC1wnNsny008i8sb4uj/qJshFwzgPG3XAvo89l8KX88EgQhjv8=)
}

// AKSI DIBAWAH INI DIPANGGIL OLEH PROGRAM JAVA (VESSEL REPORT LISTENER) KETIKA ADA EMAIL MASUK DARI PROGRAM VESSEL REPORT DI KAPAL
if($aksiGet == "simpanNewEmail")
{
	$jenisReportGet = $_GET['jenisReport'];
	$idtrmencGet = $_GET['idtrmenc'];
	
	// ---------------------- TAHAP 1 ------------------------------------------	
	$queryEnc = $CKoneksiVslRep->mysqlQuery( "SELECT isienc FROM tblterimaenc WHERE idtrmenc='".$idtrmencGet."' AND reporttype='".$jenisReportGet."' AND usee='N' LIMIT 1;" );
	$rowEnc = $CKoneksiVslRep->mysqlFetch($queryEnc);
	$messageEnc = $rowEnc['isienc'];
	
	// DECRYPT MESSAGE DENGAN KATA SANDI "4NDH1K4"
	$decTeks = $CVslRep->decrypted("andhikalain", $messageEnc); 
	$expDecTeks = explode("#-#-", $decTeks);
	$expVslNm = "";
	if ($jenisReportGet == "maintenance") 
	{
		$expVslNm = explode("::", $expDecTeks[0]);
		$isiPesan = "New ".strtoupper($jenisReportGet)." Data From ".$expVslNm[0].", Date : ".date("d-m-Y");
		for($a=2; $a<( count($expDecTeks)-1) ; $a++)
		{
			$conODBCNya->odbcExec($conODBCASMnya,str_replace("#n#n","\n",str_replace("#r#r","\r",$expDecTeks[$a])));
		}
	}
	else
	{
		for($i=0; $i<( count($expDecTeks)-1) ; $i++)
		{
			$CKoneksiVslRep->mysqlQuery( str_replace("#n#n","\n",str_replace("#r#r","\r",$expDecTeks[$i])), $CKoneksiVslRep->bukaKoneksi());
			//$html.= str_replace("#n#n","\n",str_replace("#r#r","\r",$expEncTeks[$i]))."<br>";
			//echo str_replace("#n#n","\n",str_replace("#r#r","\r",$expDecTeks[$i])) ."<br>";
		}
		
	// -------------------------------------------------------------------------

	// ---------------------- TAHAP 2 ------------------------------------------
		// DATA YANG LAMA SIMPAN KE TABEL tbleop_del
		$CKoneksiVslRep->mysqlQuery( "INSERT INTO tbl".$jenisReportGet."_del SELECT * FROM tbl".$jenisReportGet." WHERE deletests=1;", $CKoneksiVslRep->bukaKoneksi() ); 
		// HAPUS DATA DI tbleop YANG TIDAK DIPERLUKAN
		$CKoneksiVslRep->mysqlQuery( "DELETE FROM tbl".$jenisReportGet." WHERE deletests=1;", $CKoneksiVslRep->bukaKoneksi() );	
	// -------------------------------------------------------------------------	

	// ---------------------- TAHAP 3 ------------------------------------------
		// QUERY DATA EOP YANG BARU SAJA DISIMPAN DARI VESSEL
		$query = $CKoneksiVslRep->mysqlQuery( "SELECT * FROM tbl".$jenisReportGet." WHERE deletests=0 ORDER BY lastreceive DESC LIMIT 1;", $CKoneksiVslRep->bukaKoneksi() ); 
		$row = $CKoneksiVslRep->mysqlFetch($query);

		if($jenisReportGet == "eop")
		{
			$isiPesan = "New ".strtoupper($jenisReportGet)." Data From ".$row['namakapal'].", Ref No : ".$row['refno'].", Port : ".$row['nameport'];
		}
		else if($jenisReportGet == "port")
		{
			$isiPesan = "New ".strtoupper($jenisReportGet)." Data From ".$row['namakapal'].", Ref No : ".$row['refno'].", Port : ".$row['port'];
		}else{
			$isiPesan = "New ".strtoupper($jenisReportGet)." Data From ".$row['namakapal'].", Ref No : ".$row['refno'].", Last Port : ".$row['lastport'].", Next Port : ".$row['nextport'];
		}
	}
	$queryUser = $CKoneksiVslRep->mysqlQuery("SELECT userid, username FROM tbluserjenis WHERE terimapesan='Y';", $CKoneksiVslRep->bukaKoneksi() );
	while($rowUser = $CKoneksiVslRep->mysqlFetch($queryUser))
	{
		$CKoneksiVslRep->mysqlQuery("INSERT INTO tblpesan (userid, reporttype, isipesan, idtrmenc, timerec) VALUES ('".$rowUser['userid']."', '".$jenisReportGet."', '".$isiPesan."', '".$idtrmencGet."', NOW());", $CKoneksiVslRep->bukaKoneksi() );
	}
// -------------------------------------------------------------------------

// ---------------------- TAHAP 4 ------------------------------------------	
	$queryEnc = $CKoneksiVslRep->mysqlQuery( "UPDATE tblterimaenc SET usee='Y' WHERE idtrmenc='".$idtrmencGet."';" );
// -------------------------------------------------------------------------

// ---------------------- TAHAP 5 KIRIM EMAIL CC ------------------------------------------	
	$CVslRep->sendEmailCC($jenisReportGet, $row['guid'],$expVslNm);
// -------------------------------------------------------------------------
	
	echo $isiPesan;
}

if($aksiPost == "checkBtnNotif")
{
	$jenisReportPost = $_POST['jenisReport'];
	
	$query = $CKoneksiVslRep->mysqlQuery( "SELECT idmsg FROM tblpesan WHERE userid='".$userIdLogin."' AND reporttype='".$jenisReportPost."' AND readd='N' AND deletests=0;" );
	$jmlRow = $CKoneksiVslRep->mysqlNRows($query);
	
	$icon = ($jmlRow > 0)?"mail--exclamation.png":"mail.png";
	$width = ($jmlRow > 10)?"103":"99"; // 105
	
	echo "<button type=\"button\" class=\"btnStandar\" id=\"btnNotif\" onclick=\"klikBtnNotif('".$jenisReportPost."');return false;\" title=\"MESSAGE FROM SYSTEM\">
            <table cellpadding=\"0\" cellspacing=\"0\" width=\"".$width."\" height=\"24\"><tr><td align=\"center\"><img src=\"picture/".$icon."\" style=\"vertical-align:middle;\"/><span style=\"vertical-align:middle;margin-left:4px;\">".$jenisReportGet." MESSAGE (".$jmlRow.")</span></td></tr></table>
        </button>";
}

if($aksiPost == "klikTrNotif")
{
	$idMsgPost = $_POST['idMsg'];
	$userIdPost = $userIdSession;
	
	$query = $CKoneksiVslRep->mysqlQuery( "UPDATE tblpesan SET readd='Y' WHERE userid='".$userIdPost."' AND idmsg='".$idMsgPost."' AND readd='N' AND deletests=0;" );
	//echo $aksiPost;
}

if($aksiGet == "checkPesanEop")
{
	$query = $CKoneksiVslRep->mysqlQuery( "SELECT idmsg FROM tblpesan WHERE userid='".$userIdLogin."' AND reporttype='eop' AND readd='N' AND deletests=0;" );
	$jmlRow = $CKoneksiVslRep->mysqlNRows($query);
	
	echo $jmlRow;
}

if($aksiGet == "checkPesanCop")
{
	$query = $CKoneksiVslRep->mysqlQuery( "SELECT idmsg FROM tblpesan WHERE userid='".$userIdLogin."' AND reporttype='cop' AND readd='N' AND deletests=0;" );
	$jmlRow = $CKoneksiVslRep->mysqlNRows($query);
	
	echo $jmlRow;
}

if($aksiGet == "checkPesanNoon")
{
	$query = $CKoneksiVslRep->mysqlQuery( "SELECT idmsg FROM tblpesan WHERE userid='".$userIdLogin."' AND reporttype='noon' AND readd='N' AND deletests=0;" );
	$jmlRow = $CKoneksiVslRep->mysqlNRows($query);
	
	echo $jmlRow;
}

if($aksiGet == "checkPesan")
{
	$jenisReportGet = $_GET['jenisReport'];
	
	$query = $CKoneksiVslRep->mysqlQuery( "SELECT idmsg FROM tblpesan WHERE userid='".$userIdLogin."' AND reporttype='".$jenisReportGet."' AND readd='N' AND deletests=0;" );
	$jmlRow = $CKoneksiVslRep->mysqlNRows($query);
	
	echo $jmlRow;
}

if($aksiGet == "checkPesanAll")
{
	$query = $CKoneksiVslRep->mysqlQuery( "SELECT idmsg FROM tblpesan WHERE userid='".$userIdLogin."' AND readd='N' AND deletests=0;" );
	$jmlRow = $CKoneksiVslRep->mysqlNRows($query);
	
	echo $jmlRow;
}

if($aksiPost == "simpanCCEmail")
{
	$emailAddressPost = $_POST['emailAddress'];
	
	$query = $CKoneksiVslRep->mysqlQuery( "SELECT * FROM tblsetting;" );
	$jmlRow = $CKoneksiVslRep->mysqlNRows($query);
	
	if($jmlRow == 0)
	{
		$CKoneksiVslRep->mysqlQuery( "INSERT INTO tblsetting (emailaddress ,updusrdt) VALUES ('".$emailAddressPost."', '".$userWhoAct."');" );
	}
	else
	{
		$CKoneksiVslRep->mysqlQuery( "UPDATE tblsetting SET emailaddress = '".$emailAddressPost."', updusrdt = '".$userWhoAct."' LIMIT 1;" );
	}
	
	//echo $emailAddressPost;
}
?>