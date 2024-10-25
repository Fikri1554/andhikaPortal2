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
	echo $totalEmail."##".$subjectEmail."#####".$message; // YANG DIKIRIM ADALAH TOTAL+SUBJECT EMAIL YANG BARU MASUK, CONTOH DATA YANG DIKIRIMKAN (3##ANDHIKA NARESWARI##eop##20170316111804#####BHShoBAAw4d2lMJnR/Gmco6eK43TT4YjSz4lEfqmGhwQL4kc0lvU5WHI1ofGJrJk48V4nQ6dnahVsNCsrOnMxjLWQtxGU5MeqocjunejnBjtg47qpgG6H+nYH26BylAFDNaDoRYFZBydrMjAfXN23YZZ1II4d4POGUI5U+kMdTj8JJOPoy+GQrDfar5fNPdR3PGaL+gN5xpzTgMfcObkVRSLDdGcSk2IFQxVaDmVSBKFHeDNTBp4RNSf6dXxFTkUtj8mSYKhJaEgLX7lY2K/Jt6LCwInyHxvDIJJZO3UJBLX5p09v8xnlh65WQS+DlRN9IPnehEZmRWu8VPbb2CvdL9kZ340sjKNgG2ec3WTIpNXhWh/m6eccxjRESm1jKSdKoKuhHF+uzLknMVqTwNijgSqWY7DnCwZrvc8boka9/xBEK6fSp2OzKRMZ8NVoTOjoHNQMHMJwWRE/VftlqNcMfE5BybBVwIAbqC8+GLITZKDYvXlT1D/RZS6wbnbAf/w1Kqd85Mw43QDh2KKJHdIMAPN+/woi8wkw4MrO3NKscRgwQSk1GQUkcZik+eFweMNN+oCWF7IFX74XxJkBCh1tnlnzumGqXgn1S6U78PvQo18N1snO4UKqRk3fD+hWL+GOMhl1je5LaOKH4Z3h2Jlx3/uM8d5CKWv8NRbpvvxjlurzxHqOnaMBSr9q44XYwTqI3LRh6GlJ5A+DUF9hI5eA+pTVta8pFmY6Aw1DliaEaY5QbPmqSa7pSK6uuRGz2PbBQ3r11aMUxffwXu06c2S3Yp7YodQTPoaz//j/rTd/Ry9X+wWGjyC7RQ5glGL5s3DRGhvV0n0jmt489Kp9CshVkzeK+WsUEAAE036jKvq2YY+zabSQv95Pd9VNTerem3r+EGi8huBEu7sefSG4I2Di4HKDqeBLDMjTuRBvJlq7/ULQ1Iy5YEKkdGhEZAJPC0XrwbMggscQqDdlvaRdfyb/hKrVWmKA1P3Jwbxit076z1Qq+PuYbDHwRY2ReAv1uWnEewTGrBuoRf9iY3qUzta+G88SSVIKp3BubJHf8OhqSxUJkpFluF0niNiDU2Y6qhmdxl9+jD6TOfmYPhjKyW8IdSrwAZdAOxqOvr+gZNYjNAmlsLBsFOSAfxKY7wVRc3aKJOsqjlcMqeCS3VPYKbZSB0OMvU8Kw+yGVGlSLGOK9FdOqe0W7A2BxmASvB6ZaQ8kAM/28lo8BCEbWb8pg+ccp0mD1vlg5NNvwZmHzaAIAreLprJqmcBEq6ftWFo0+nhrII5GOcFqwDZgpLxWhkAE4u+NJIqeBPzZF2JrnSxK39+Qzb7O3jlUszI/c1WYOx07TIiRexa6xUcxyMq5IfmwOexwYh1Hxda0qXNkEbvXFawujlp8LzLPmebDp0vg/A+iT5mlaIawxVnKao2b9o7/0VGt3g4tDeAeFT3wxPMLEtxgbFOq4U5kBobJeF7wHHROyGNpjPpsTnw9Dq2rfELanWj3fw/UpXSpUunEmuH9YUMTUFmbo6qKWtKcPjXIQSG7K5QH67WotS4bupjcZj/wIc4+Avfe/j8jwAUWWjcbz1PJXRKmYYeJGk6Mvr+s69CzxRJkRpwvGIJNA4sIB6w85rWWJllSzYZiYIVXD8TL6T5iL0VCfM5lV46fRDv7FgGzd4KArr9PihR9FRkwYPajfVxUPlDS/FhIekJoIYJ3+JlxKtO30gvjk1WOSC1wnNsny008i8sb4uj/qJshFwzgPG3XAvo89l8KX88EgQhjv8=)
}
if($aksiPost == "simpanNewEmailEop")
{
	
}
if($aksiPost == "simpanNewEmailEop20170413")
{
	$subjectBaruPost = $_POST['subjectBaru']; // SUBJECT EMAIL YANG BARU SAJA MASUK
	set_time_limit(3600); // SET TIME EXECUTION SKRIP
	
    $imap = new Imapx();
	$sortBy = array("desc","date"); // URUTKAN EMAIL BERDASARKAN YANG PALING TERBARU DIATAS
	$inbox = $imap->getInbox(1, 1, $sortBy); // // AMBIL HANYA SATU (1) EMAIL BERDASARKAN SORTING YANG TERAKHIR DIATAS
	
	$html = "";
	$message = "";
	$subject = "";
	foreach ($inbox as $key => $mail) 
	{
		$subject = $mail->subject;
		if(isset($subject)) 
		{
			if($subjectBaruPost == $mail->subject) // JIKA SUBJECT YANG BARU MASUK ADA DI INBOX / SAMA MAKA TAMPILKAN
			{
				//$message = imap_fetchbody($imap, $key, 0);
				$readMail = $imap->readMail($key);
				$message = $imap->getBody("text", true);
			}
		}
	}

	$encTeks = $CVslRep->decrypted("4ndh1k4", $message);
	$expEncTeks = explode("#-#-", $encTeks);
	for($i=0; $i<( count($expEncTeks)-1) ; $i++)
	{
		$CKoneksiVslRep->mysqlQuery( str_replace("#n#n","\n",str_replace("#r#r","\r",$expEncTeks[$i])), $CKoneksiVslRep->bukaKoneksi());
		//$html.= str_replace("#n#n","\n",str_replace("#r#r","\r",$expEncTeks[$i]))."<br>";;
	}		

	//$html.= $message;
	echo $html;
}

if($aksiGet == "checkNotifEop20170316")
{
	set_time_limit(3600);
    $imap = new Imapx();

	$sortBy = array("desc","date");
    $inbox = $imap->getInbox(1, 20, $sortBy);
	$html = "";
	$totalEmail = 0;
	$i = 0;
	if(!empty( $inbox))
	{
		foreach ($inbox as $key => $mail) 
		{
			if (isset($mail->subject)) 
			{
				if($i++ == 20)break;  
				$html.= $i." ".$mail->subject."<br/>";
				$html.= "<br/>";
			}
		}
		$totalEmail = $imap->totalEmail();
	}
	//echo $html;
	
	echo $totalEmail;
}
?>