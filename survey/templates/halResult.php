<?php
require_once("../../config.php");

	$tgl = $CPublic->zerofill($CPublic->waktuServer("tanggal"), 2); 
	$bln = $CPublic->zerofill($CPublic->waktuServer("bulan"), 2); 
	$thn = $CPublic->waktuServer("tahun");
	$tglSek = $tgl." ".$CPublic->bulanSetengah($bln, "ind")." ".$thn;
	
	$jmlPes = $CSurvey->result($CKoneksi, "idsurvey", "Y");
	$allPin = $CSurvey->result($CKoneksi, "idsurvey", "");
	$jmlPesPer = $jmlPes/$allPin*100;
	if(strlen($jmlPesPer) > 3){$jmlPesPer = round($jmlPesPer,2);}
	
	if($jmlPes > 0)
	{
		$A11 = $CSurvey->resultSkala($CKoneksi, "A1", "1")/$jmlPes*100; if(strlen($A11) > 3){$A11 = round($A11,2);}
		$A12 = $CSurvey->resultSkala($CKoneksi, "A1", "2")/$jmlPes*100; if(strlen($A12) > 3){$A12 = round($A12,2);}
		$A13 = $CSurvey->resultSkala($CKoneksi, "A1", "3")/$jmlPes*100; if(strlen($A13) > 3){$A13 = round($A13,2);}
		$A14 = $CSurvey->resultSkala($CKoneksi, "A1", "4")/$jmlPes*100; if(strlen($A14) > 3){$A14 = round($A14,2);}
		$A15 = $CSurvey->resultSkala($CKoneksi, "A1", "5")/$jmlPes*100; if(strlen($A15) > 3){$A15 = round($A15,2);}
		
		$A21 = $CSurvey->resultSkala($CKoneksi, "A2", "1")/$jmlPes*100; if(strlen($A21) > 3){$A21 = round($A21,2);}
		$A22 = $CSurvey->resultSkala($CKoneksi, "A2", "2")/$jmlPes*100; if(strlen($A22) > 3){$A22 = round($A22,2);}
		$A23 = $CSurvey->resultSkala($CKoneksi, "A2", "3")/$jmlPes*100; if(strlen($A23) > 3){$A23 = round($A23,2);}
		$A24 = $CSurvey->resultSkala($CKoneksi, "A2", "4")/$jmlPes*100; if(strlen($A24) > 3){$A24 = round($A24,2);}
		$A25 = $CSurvey->resultSkala($CKoneksi, "A2", "5")/$jmlPes*100; if(strlen($A25) > 3){$A25 = round($A25,2);}
		
		$A31 = $CSurvey->resultSkala($CKoneksi, "A3", "1")/$jmlPes*100; if(strlen($A31) > 3){$A31 = round($A31,2);}
		$A32 = $CSurvey->resultSkala($CKoneksi, "A3", "2")/$jmlPes*100; if(strlen($A32) > 3){$A32 = round($A32,2);}
		$A33 = $CSurvey->resultSkala($CKoneksi, "A3", "3")/$jmlPes*100; if(strlen($A33) > 3){$A33 = round($A33,2);}
		$A34 = $CSurvey->resultSkala($CKoneksi, "A3", "4")/$jmlPes*100; if(strlen($A34) > 3){$A34 = round($A34,2);}
		$A35 = $CSurvey->resultSkala($CKoneksi, "A3", "5")/$jmlPes*100; if(strlen($A35) > 3){$A35 = round($A35,2);}
		
		$B11 = $CSurvey->resultSkala($CKoneksi, "B1", "1")/$jmlPes*100; if(strlen($B11) > 3){$B11 = round($B11,2);}
		$B12 = $CSurvey->resultSkala($CKoneksi, "B1", "2")/$jmlPes*100; if(strlen($B12) > 3){$B12 = round($B12,2);}
		$B13 = $CSurvey->resultSkala($CKoneksi, "B1", "3")/$jmlPes*100; if(strlen($B13) > 3){$B13 = round($B13,2);}
		$B14 = $CSurvey->resultSkala($CKoneksi, "B1", "4")/$jmlPes*100; if(strlen($B14) > 3){$B14 = round($B14,2);}
		$B15 = $CSurvey->resultSkala($CKoneksi, "B1", "5")/$jmlPes*100; if(strlen($B15) > 3){$B15 = round($B15,2);}
		
		$B11 = $CSurvey->resultSkala($CKoneksi, "B1", "1")/$jmlPes*100; if(strlen($B11) > 3){$B11 = round($B11,2);}
		$B12 = $CSurvey->resultSkala($CKoneksi, "B1", "2")/$jmlPes*100; if(strlen($B12) > 3){$B12 = round($B12,2);}
		$B13 = $CSurvey->resultSkala($CKoneksi, "B1", "3")/$jmlPes*100; if(strlen($B13) > 3){$B13 = round($B13,2);}
		$B14 = $CSurvey->resultSkala($CKoneksi, "B1", "4")/$jmlPes*100; if(strlen($B14) > 3){$B14 = round($B14,2);}
		$B15 = $CSurvey->resultSkala($CKoneksi, "B1", "5")/$jmlPes*100; if(strlen($B15) > 3){$B15 = round($B15,2);}
		
		$B21 = $CSurvey->resultSkala($CKoneksi, "B2", "1")/$jmlPes*100; if(strlen($B21) > 3){$B21 = round($B21,2);}
		$B22 = $CSurvey->resultSkala($CKoneksi, "B2", "2")/$jmlPes*100; if(strlen($B22) > 3){$B22 = round($B22,2);}
		$B23 = $CSurvey->resultSkala($CKoneksi, "B2", "3")/$jmlPes*100; if(strlen($B23) > 3){$B23 = round($B23,2);}
		$B24 = $CSurvey->resultSkala($CKoneksi, "B2", "4")/$jmlPes*100; if(strlen($B24) > 3){$B24 = round($B24,2);}
		$B25 = $CSurvey->resultSkala($CKoneksi, "B2", "5")/$jmlPes*100; if(strlen($B25) > 3){$B25 = round($B25,2);}
		
		$B31 = $CSurvey->resultSkala($CKoneksi, "B3", "1")/$jmlPes*100; if(strlen($B31) > 3){$B31 = round($B31,2);}
		$B32 = $CSurvey->resultSkala($CKoneksi, "B3", "2")/$jmlPes*100; if(strlen($B32) > 3){$B32 = round($B32,2);}
		$B33 = $CSurvey->resultSkala($CKoneksi, "B3", "3")/$jmlPes*100; if(strlen($B33) > 3){$B33 = round($B33,2);}
		$B34 = $CSurvey->resultSkala($CKoneksi, "B3", "4")/$jmlPes*100; if(strlen($B34) > 3){$B34 = round($B34,2);}
		$B35 = $CSurvey->resultSkala($CKoneksi, "B3", "5")/$jmlPes*100; if(strlen($B35) > 3){$B35 = round($B35,2);}
		
		$B41 = $CSurvey->resultSkala($CKoneksi, "B4", "1")/$jmlPes*100; if(strlen($B41) > 3){$B41 = round($B41,2);}
		$B42 = $CSurvey->resultSkala($CKoneksi, "B4", "2")/$jmlPes*100; if(strlen($B42) > 3){$B42 = round($B42,2);}
		$B43 = $CSurvey->resultSkala($CKoneksi, "B4", "3")/$jmlPes*100; if(strlen($B43) > 3){$B43 = round($B43,2);}
		$B44 = $CSurvey->resultSkala($CKoneksi, "B4", "4")/$jmlPes*100; if(strlen($B44) > 3){$B44 = round($B44,2);}
		$B45 = $CSurvey->resultSkala($CKoneksi, "B4", "5")/$jmlPes*100; if(strlen($B45) > 3){$B45 = round($B45,2);}
		
		$B51 = $CSurvey->resultSkala($CKoneksi, "B5", "1")/$jmlPes*100; if(strlen($B51) > 3){$B51 = round($B51,2);}
		$B52 = $CSurvey->resultSkala($CKoneksi, "B5", "2")/$jmlPes*100; if(strlen($B52) > 3){$B52 = round($B52,2);}
		$B53 = $CSurvey->resultSkala($CKoneksi, "B5", "3")/$jmlPes*100; if(strlen($B53) > 3){$B53 = round($B53,2);}
		$B54 = $CSurvey->resultSkala($CKoneksi, "B5", "4")/$jmlPes*100; if(strlen($B54) > 3){$B54 = round($B54,2);}
		$B55 = $CSurvey->resultSkala($CKoneksi, "B5", "5")/$jmlPes*100; if(strlen($B55) > 3){$B55 = round($B55,2);}
		
		$B61 = $CSurvey->resultSkala($CKoneksi, "B6", "1")/$jmlPes*100; if(strlen($B61) > 3){$B61 = round($B61,2);}
		$B62 = $CSurvey->resultSkala($CKoneksi, "B6", "2")/$jmlPes*100; if(strlen($B62) > 3){$B62 = round($B62,2);}
		$B63 = $CSurvey->resultSkala($CKoneksi, "B6", "3")/$jmlPes*100; if(strlen($B63) > 3){$B63 = round($B63,2);}
		$B64 = $CSurvey->resultSkala($CKoneksi, "B6", "4")/$jmlPes*100; if(strlen($B64) > 3){$B64 = round($B64,2);}
		$B65 = $CSurvey->resultSkala($CKoneksi, "B6", "5")/$jmlPes*100; if(strlen($B65) > 3){$B65 = round($B65,2);}
		
		$B71 = $CSurvey->resultSkala($CKoneksi, "B7", "1")/$jmlPes*100; if(strlen($B71) > 3){$B71 = round($B71,2);}
		$B72 = $CSurvey->resultSkala($CKoneksi, "B7", "2")/$jmlPes*100; if(strlen($B72) > 3){$B72 = round($B72,2);}
		$B73 = $CSurvey->resultSkala($CKoneksi, "B7", "3")/$jmlPes*100; if(strlen($B73) > 3){$B73 = round($B73,2);}
		$B74 = $CSurvey->resultSkala($CKoneksi, "B7", "4")/$jmlPes*100; if(strlen($B74) > 3){$B74 = round($B74,2);}
		$B75 = $CSurvey->resultSkala($CKoneksi, "B7", "5")/$jmlPes*100; if(strlen($B75) > 3){$B75 = round($B75,2);}
		
		$B81 = $CSurvey->resultSkala($CKoneksi, "B8", "1")/$jmlPes*100; if(strlen($B81) > 3){$B81 = round($B81,2);}
		$B82 = $CSurvey->resultSkala($CKoneksi, "B8", "2")/$jmlPes*100; if(strlen($B82) > 3){$B82 = round($B82,2);}
		$B83 = $CSurvey->resultSkala($CKoneksi, "B8", "3")/$jmlPes*100; if(strlen($B83) > 3){$B83 = round($B83,2);}
		$B84 = $CSurvey->resultSkala($CKoneksi, "B8", "4")/$jmlPes*100; if(strlen($B84) > 3){$B84 = round($B84,2);}
		$B85 = $CSurvey->resultSkala($CKoneksi, "B8", "5")/$jmlPes*100; if(strlen($B85) > 3){$B85 = round($B85,2);}
		
		$C11 = $CSurvey->resultSkala($CKoneksi, "C1", "1")/$jmlPes*100; if(strlen($C11) > 3){$C11 = round($C11,2);}
		$C12 = $CSurvey->resultSkala($CKoneksi, "C1", "2")/$jmlPes*100; if(strlen($C12) > 3){$C12 = round($C12,2);}
		$C13 = $CSurvey->resultSkala($CKoneksi, "C1", "3")/$jmlPes*100; if(strlen($C13) > 3){$C13 = round($C13,2);}
		$C14 = $CSurvey->resultSkala($CKoneksi, "C1", "4")/$jmlPes*100; if(strlen($C14) > 3){$C14 = round($C14,2);}
		$C15 = $CSurvey->resultSkala($CKoneksi, "C1", "5")/$jmlPes*100; if(strlen($C15) > 3){$C15 = round($C15,2);}
		
		$C21 = $CSurvey->resultSkala($CKoneksi, "C2", "1")/$jmlPes*100; if(strlen($C21) > 3){$C21 = round($C21,2);}
		$C22 = $CSurvey->resultSkala($CKoneksi, "C2", "2")/$jmlPes*100; if(strlen($C22) > 3){$C22 = round($C22,2);}
		$C23 = $CSurvey->resultSkala($CKoneksi, "C2", "3")/$jmlPes*100; if(strlen($C23) > 3){$C23 = round($C23,2);}
		$C24 = $CSurvey->resultSkala($CKoneksi, "C2", "4")/$jmlPes*100; if(strlen($C24) > 3){$C24 = round($C24,2);}
		$C25 = $CSurvey->resultSkala($CKoneksi, "C2", "5")/$jmlPes*100; if(strlen($C25) > 3){$C25 = round($C25,2);}
		
		$C31 = $CSurvey->resultSkala($CKoneksi, "C3", "1")/$jmlPes*100; if(strlen($C31) > 3){$C31 = round($C31,2);}
		$C32 = $CSurvey->resultSkala($CKoneksi, "C3", "2")/$jmlPes*100; if(strlen($C32) > 3){$C32 = round($C32,2);}
		$C33 = $CSurvey->resultSkala($CKoneksi, "C3", "3")/$jmlPes*100; if(strlen($C33) > 3){$C33 = round($C33,2);}
		$C34 = $CSurvey->resultSkala($CKoneksi, "C3", "4")/$jmlPes*100; if(strlen($C34) > 3){$C34 = round($C34,2);}
		$C35 = $CSurvey->resultSkala($CKoneksi, "C3", "5")/$jmlPes*100; if(strlen($C35) > 3){$C35 = round($C35,2);}
		
		$C41 = $CSurvey->resultSkala($CKoneksi, "C4", "1")/$jmlPes*100; if(strlen($C41) > 3){$C41 = round($C41,2);}
		$C42 = $CSurvey->resultSkala($CKoneksi, "C4", "2")/$jmlPes*100; if(strlen($C42) > 3){$C42 = round($C42,2);}
		$C43 = $CSurvey->resultSkala($CKoneksi, "C4", "3")/$jmlPes*100; if(strlen($C43) > 3){$C43 = round($C43,2);}
		$C44 = $CSurvey->resultSkala($CKoneksi, "C4", "4")/$jmlPes*100; if(strlen($C44) > 3){$C44 = round($C44,2);}
		$C45 = $CSurvey->resultSkala($CKoneksi, "C4", "5")/$jmlPes*100; if(strlen($C45) > 3){$C45 = round($C45,2);}
		
		$C51 = $CSurvey->resultSkala($CKoneksi, "C5", "1")/$jmlPes*100; if(strlen($C51) > 3){$C51 = round($C51,2);}
		$C52 = $CSurvey->resultSkala($CKoneksi, "C5", "2")/$jmlPes*100; if(strlen($C52) > 3){$C52 = round($C52,2);}
		$C53 = $CSurvey->resultSkala($CKoneksi, "C5", "3")/$jmlPes*100; if(strlen($C53) > 3){$C53 = round($C53,2);}
		$C54 = $CSurvey->resultSkala($CKoneksi, "C5", "4")/$jmlPes*100; if(strlen($C54) > 3){$C54 = round($C54,2);}
		$C55 = $CSurvey->resultSkala($CKoneksi, "C5", "5")/$jmlPes*100; if(strlen($C55) > 3){$C55 = round($C55,2);}
		
		$D11 = $CSurvey->resultSkala($CKoneksi, "D1", "1")/$jmlPes*100; if(strlen($D11) > 3){$D11 = round($D11,2);}
		$D12 = $CSurvey->resultSkala($CKoneksi, "D1", "2")/$jmlPes*100; if(strlen($D12) > 3){$D12 = round($D12,2);}
		$D13 = $CSurvey->resultSkala($CKoneksi, "D1", "3")/$jmlPes*100; if(strlen($D13) > 3){$D13 = round($D13,2);}
		$D14 = $CSurvey->resultSkala($CKoneksi, "D1", "4")/$jmlPes*100; if(strlen($D14) > 3){$D14 = round($D14,2);}
		$D15 = $CSurvey->resultSkala($CKoneksi, "D1", "5")/$jmlPes*100; if(strlen($D15) > 3){$D15 = round($D15,2);}
		
		$D21 = $CSurvey->resultSkala($CKoneksi, "D2", "1")/$jmlPes*100; if(strlen($D21) > 3){$D21 = round($D21,2);}
		$D22 = $CSurvey->resultSkala($CKoneksi, "D2", "2")/$jmlPes*100; if(strlen($D22) > 3){$D22 = round($D22,2);}
		$D23 = $CSurvey->resultSkala($CKoneksi, "D2", "3")/$jmlPes*100; if(strlen($D23) > 3){$D23 = round($D23,2);}
		$D24 = $CSurvey->resultSkala($CKoneksi, "D2", "4")/$jmlPes*100; if(strlen($D24) > 3){$D24 = round($D24,2);}
		$D25 = $CSurvey->resultSkala($CKoneksi, "D2", "5")/$jmlPes*100; if(strlen($D25) > 3){$D25 = round($D25,2);}
		
		$D31 = $CSurvey->resultSkala($CKoneksi, "D3", "1")/$jmlPes*100; if(strlen($D31) > 3){$D31 = round($D31,2);}
		$D32 = $CSurvey->resultSkala($CKoneksi, "D3", "2")/$jmlPes*100; if(strlen($D32) > 3){$D32 = round($D32,2);}
		$D33 = $CSurvey->resultSkala($CKoneksi, "D3", "3")/$jmlPes*100; if(strlen($D33) > 3){$D33 = round($D33,2);}
		$D34 = $CSurvey->resultSkala($CKoneksi, "D3", "4")/$jmlPes*100; if(strlen($D34) > 3){$D34 = round($D34,2);}
		$D35 = $CSurvey->resultSkala($CKoneksi, "D3", "5")/$jmlPes*100; if(strlen($D35) > 3){$D35 = round($D35,2);}
		
		$D41 = $CSurvey->resultSkala($CKoneksi, "D4", "1")/$jmlPes*100; if(strlen($D41) > 3){$D41 = round($D41,2);}
		$D42 = $CSurvey->resultSkala($CKoneksi, "D4", "2")/$jmlPes*100; if(strlen($D42) > 3){$D42 = round($D42,2);}
		$D43 = $CSurvey->resultSkala($CKoneksi, "D4", "3")/$jmlPes*100; if(strlen($D43) > 3){$D43 = round($D43,2);}
		$D44 = $CSurvey->resultSkala($CKoneksi, "D4", "4")/$jmlPes*100; if(strlen($D44) > 3){$D44 = round($D44,2);}
		$D45 = $CSurvey->resultSkala($CKoneksi, "D4", "5")/$jmlPes*100; if(strlen($D45) > 3){$D45 = round($D45,2);}
		
		$D51 = $CSurvey->resultSkala($CKoneksi, "D5", "1")/$jmlPes*100; if(strlen($D51) > 3){$D51 = round($D51,2);}
		$D52 = $CSurvey->resultSkala($CKoneksi, "D5", "2")/$jmlPes*100; if(strlen($D52) > 3){$D52 = round($D52,2);}
		$D53 = $CSurvey->resultSkala($CKoneksi, "D5", "3")/$jmlPes*100; if(strlen($D53) > 3){$D53 = round($D53,2);}
		$D54 = $CSurvey->resultSkala($CKoneksi, "D5", "4")/$jmlPes*100; if(strlen($D54) > 3){$D54 = round($D54,2);}
		$D55 = $CSurvey->resultSkala($CKoneksi, "D5", "5")/$jmlPes*100; if(strlen($D55) > 3){$D55 = round($D55,2);}
		
		$D61 = $CSurvey->resultSkala($CKoneksi, "D6", "1")/$jmlPes*100; if(strlen($D61) > 3){$D61 = round($D61,2);}
		$D62 = $CSurvey->resultSkala($CKoneksi, "D6", "2")/$jmlPes*100; if(strlen($D62) > 3){$D62 = round($D62,2);}
		$D63 = $CSurvey->resultSkala($CKoneksi, "D6", "3")/$jmlPes*100; if(strlen($D63) > 3){$D63 = round($D63,2);}
		$D64 = $CSurvey->resultSkala($CKoneksi, "D6", "4")/$jmlPes*100; if(strlen($D64) > 3){$D64 = round($D64,2);}
		$D65 = $CSurvey->resultSkala($CKoneksi, "D6", "5")/$jmlPes*100; if(strlen($D65) > 3){$D65 = round($D65,2);}
		
		$D71 = $CSurvey->resultSkala($CKoneksi, "D7", "1")/$jmlPes*100; if(strlen($D71) > 3){$D71 = round($D71,2);}
		$D72 = $CSurvey->resultSkala($CKoneksi, "D7", "2")/$jmlPes*100; if(strlen($D72) > 3){$D72 = round($D72,2);}
		$D73 = $CSurvey->resultSkala($CKoneksi, "D7", "3")/$jmlPes*100; if(strlen($D73) > 3){$D73 = round($D73,2);}
		$D74 = $CSurvey->resultSkala($CKoneksi, "D7", "4")/$jmlPes*100; if(strlen($D74) > 3){$D74 = round($D74,2);}
		$D75 = $CSurvey->resultSkala($CKoneksi, "D7", "5")/$jmlPes*100; if(strlen($D75) > 3){$D75 = round($D75,2);}
		
		$D81 = $CSurvey->resultSkala($CKoneksi, "D8", "1")/$jmlPes*100; if(strlen($D81) > 3){$D81 = round($D81,2);}
		$D82 = $CSurvey->resultSkala($CKoneksi, "D8", "2")/$jmlPes*100; if(strlen($D82) > 3){$D82 = round($D82,2);}
		$D83 = $CSurvey->resultSkala($CKoneksi, "D8", "3")/$jmlPes*100; if(strlen($D83) > 3){$D83 = round($D83,2);}
		$D84 = $CSurvey->resultSkala($CKoneksi, "D8", "4")/$jmlPes*100; if(strlen($D84) > 3){$D84 = round($D84,2);}
		$D85 = $CSurvey->resultSkala($CKoneksi, "D8", "5")/$jmlPes*100; if(strlen($D85) > 3){$D85 = round($D85,2);}
		
		$D91 = $CSurvey->resultSkala($CKoneksi, "D9", "1")/$jmlPes*100; if(strlen($D91) > 3){$D91 = round($D91,2);}
		$D92 = $CSurvey->resultSkala($CKoneksi, "D9", "2")/$jmlPes*100; if(strlen($D92) > 3){$D92 = round($D92,2);}
		$D93 = $CSurvey->resultSkala($CKoneksi, "D9", "3")/$jmlPes*100; if(strlen($D93) > 3){$D93 = round($D93,2);}
		$D94 = $CSurvey->resultSkala($CKoneksi, "D9", "4")/$jmlPes*100; if(strlen($D94) > 3){$D94 = round($D94,2);}
		$D95 = $CSurvey->resultSkala($CKoneksi, "D9", "5")/$jmlPes*100; if(strlen($D95) > 3){$D95 = round($D95,2);}
		
		$D101 = $CSurvey->resultSkala($CKoneksi, "D10", "1")/$jmlPes*100; if(strlen($D101) > 3){$D101 = round($D101,2);}
		$D102 = $CSurvey->resultSkala($CKoneksi, "D10", "2")/$jmlPes*100; if(strlen($D102) > 3){$D102 = round($D102,2);}
		$D103 = $CSurvey->resultSkala($CKoneksi, "D10", "3")/$jmlPes*100; if(strlen($D103) > 3){$D103 = round($D103,2);}
		$D104 = $CSurvey->resultSkala($CKoneksi, "D10", "4")/$jmlPes*100; if(strlen($D104) > 3){$D104 = round($D104,2);}
		$D105 = $CSurvey->resultSkala($CKoneksi, "D10", "5")/$jmlPes*100; if(strlen($D105) > 3){$D105 = round($D105,2);}
		
		$D111 = $CSurvey->resultSkala($CKoneksi, "D11", "1")/$jmlPes*100; if(strlen($D111) > 3){$D111 = round($D111,2);}
		$D112 = $CSurvey->resultSkala($CKoneksi, "D11", "2")/$jmlPes*100; if(strlen($D112) > 3){$D112 = round($D112,2);}
		$D113 = $CSurvey->resultSkala($CKoneksi, "D11", "3")/$jmlPes*100; if(strlen($D113) > 3){$D113 = round($D113,2);}
		$D114 = $CSurvey->resultSkala($CKoneksi, "D11", "4")/$jmlPes*100; if(strlen($D114) > 3){$D114 = round($D114,2);}
		$D115 = $CSurvey->resultSkala($CKoneksi, "D11", "5")/$jmlPes*100; if(strlen($D115) > 3){$D115 = round($D115,2);}
		
		$D121 = $CSurvey->resultSkala($CKoneksi, "D12", "1")/$jmlPes*100; if(strlen($D121) > 3){$D121 = round($D121,2);}
		$D122 = $CSurvey->resultSkala($CKoneksi, "D12", "2")/$jmlPes*100; if(strlen($D122) > 3){$D122 = round($D122,2);}
		$D123 = $CSurvey->resultSkala($CKoneksi, "D12", "3")/$jmlPes*100; if(strlen($D123) > 3){$D123 = round($D123,2);}
		$D124 = $CSurvey->resultSkala($CKoneksi, "D12", "4")/$jmlPes*100; if(strlen($D124) > 3){$D124 = round($D124,2);}
		$D125 = $CSurvey->resultSkala($CKoneksi, "D12", "5")/$jmlPes*100; if(strlen($D125) > 3){$D125 = round($D125,2);}
		
		$E11 = $CSurvey->resultSkala($CKoneksi, "E1", "1")/$jmlPes*100; if(strlen($E11) > 3){$E11 = round($E11,2);}
		$E12 = $CSurvey->resultSkala($CKoneksi, "E1", "2")/$jmlPes*100; if(strlen($E12) > 3){$E12 = round($E12,2);}
		$E13 = $CSurvey->resultSkala($CKoneksi, "E1", "3")/$jmlPes*100; if(strlen($E13) > 3){$E13 = round($E13,2);}
		$E14 = $CSurvey->resultSkala($CKoneksi, "E1", "4")/$jmlPes*100; if(strlen($E14) > 3){$E14 = round($E14,2);}
		$E15 = $CSurvey->resultSkala($CKoneksi, "E1", "5")/$jmlPes*100; if(strlen($E15) > 3){$E15 = round($E15,2);}
		
		$E21 = $CSurvey->resultSkala($CKoneksi, "E2", "1")/$jmlPes*100; if(strlen($E21) > 3){$E21 = round($E21,2);}
		$E22 = $CSurvey->resultSkala($CKoneksi, "E2", "2")/$jmlPes*100; if(strlen($E22) > 3){$E22 = round($E22,2);}
		$E23 = $CSurvey->resultSkala($CKoneksi, "E2", "3")/$jmlPes*100; if(strlen($E23) > 3){$E23 = round($E23,2);}
		$E24 = $CSurvey->resultSkala($CKoneksi, "E2", "4")/$jmlPes*100; if(strlen($E24) > 3){$E24 = round($E24,2);}
		$E25 = $CSurvey->resultSkala($CKoneksi, "E2", "5")/$jmlPes*100; if(strlen($E25) > 3){$E25 = round($E25,2);}
		
		$E31 = $CSurvey->resultSkala($CKoneksi, "E3", "1")/$jmlPes*100; if(strlen($E31) > 3){$E31 = round($E31,2);}
		$E32 = $CSurvey->resultSkala($CKoneksi, "E3", "2")/$jmlPes*100; if(strlen($E32) > 3){$E32 = round($E32,2);}
		$E33 = $CSurvey->resultSkala($CKoneksi, "E3", "3")/$jmlPes*100; if(strlen($E33) > 3){$E33 = round($E33,2);}
		$E34 = $CSurvey->resultSkala($CKoneksi, "E3", "4")/$jmlPes*100; if(strlen($E34) > 3){$E34 = round($E34,2);}
		$E35 = $CSurvey->resultSkala($CKoneksi, "E3", "5")/$jmlPes*100; if(strlen($E35) > 3){$E35 = round($E35,2);}
		
		$E41 = $CSurvey->resultSkala($CKoneksi, "E4", "1")/$jmlPes*100; if(strlen($E41) > 3){$E41 = round($E41,2);}
		$E42 = $CSurvey->resultSkala($CKoneksi, "E4", "2")/$jmlPes*100; if(strlen($E42) > 3){$E42 = round($E42,2);}
		$E43 = $CSurvey->resultSkala($CKoneksi, "E4", "3")/$jmlPes*100; if(strlen($E43) > 3){$E43 = round($E43,2);}
		$E44 = $CSurvey->resultSkala($CKoneksi, "E4", "4")/$jmlPes*100; if(strlen($E44) > 3){$E44 = round($E44,2);}
		$E45 = $CSurvey->resultSkala($CKoneksi, "E4", "5")/$jmlPes*100; if(strlen($E45) > 3){$E45 = round($E45,2);}
		
		$F11 = $CSurvey->resultSkala($CKoneksi, "F1", "1")/$jmlPes*100; if(strlen($F11) > 3){$F11 = round($F11,2);}
		$F12 = $CSurvey->resultSkala($CKoneksi, "F1", "2")/$jmlPes*100; if(strlen($F12) > 3){$F12 = round($F12,2);}
		$F13 = $CSurvey->resultSkala($CKoneksi, "F1", "3")/$jmlPes*100; if(strlen($F13) > 3){$F13 = round($F13,2);}
		$F14 = $CSurvey->resultSkala($CKoneksi, "F1", "4")/$jmlPes*100; if(strlen($F14) > 3){$F14 = round($F14,2);}
		$F15 = $CSurvey->resultSkala($CKoneksi, "F1", "5")/$jmlPes*100; if(strlen($F15) > 3){$F15 = round($F15,2);}
		
		$F21 = $CSurvey->resultSkala($CKoneksi, "F2", "1")/$jmlPes*100; if(strlen($F21) > 3){$F21 = round($F21,2);}
		$F22 = $CSurvey->resultSkala($CKoneksi, "F2", "2")/$jmlPes*100; if(strlen($F22) > 3){$F22 = round($F22,2);}
		$F23 = $CSurvey->resultSkala($CKoneksi, "F2", "3")/$jmlPes*100; if(strlen($F23) > 3){$F23 = round($F23,2);}
		$F24 = $CSurvey->resultSkala($CKoneksi, "F2", "4")/$jmlPes*100; if(strlen($F24) > 3){$F24 = round($F24,2);}
		$F25 = $CSurvey->resultSkala($CKoneksi, "F2", "5")/$jmlPes*100; if(strlen($F25) > 3){$F25 = round($F25,2);}
		
		$F31 = $CSurvey->resultSkala($CKoneksi, "F3", "1")/$jmlPes*100; if(strlen($F31) > 3){$F31 = round($F31,2);}
		$F32 = $CSurvey->resultSkala($CKoneksi, "F3", "2")/$jmlPes*100; if(strlen($F32) > 3){$F32 = round($F32,2);}
		$F33 = $CSurvey->resultSkala($CKoneksi, "F3", "3")/$jmlPes*100; if(strlen($F33) > 3){$F33 = round($F33,2);}
		$F34 = $CSurvey->resultSkala($CKoneksi, "F3", "4")/$jmlPes*100; if(strlen($F34) > 3){$F34 = round($F34,2);}
		$F35 = $CSurvey->resultSkala($CKoneksi, "F3", "5")/$jmlPes*100; if(strlen($F35) > 3){$F35 = round($F35,2);}
		
		$F41 = $CSurvey->resultSkala($CKoneksi, "F4", "1")/$jmlPes*100; if(strlen($F41) > 3){$F41 = round($F41,2);}
		$F42 = $CSurvey->resultSkala($CKoneksi, "F4", "2")/$jmlPes*100; if(strlen($F42) > 3){$F42 = round($F42,2);}
		$F43 = $CSurvey->resultSkala($CKoneksi, "F4", "3")/$jmlPes*100; if(strlen($F43) > 3){$F43 = round($F43,2);}
		$F44 = $CSurvey->resultSkala($CKoneksi, "F4", "4")/$jmlPes*100; if(strlen($F44) > 3){$F44 = round($F44,2);}
		$F45 = $CSurvey->resultSkala($CKoneksi, "F4", "5")/$jmlPes*100; if(strlen($F45) > 3){$F45 = round($F45,2);}
		
		$G11 = $CSurvey->resultSkala($CKoneksi, "G1", "1")/$jmlPes*100; if(strlen($G11) > 3){$G11 = round($G11,2);}
		$G12 = $CSurvey->resultSkala($CKoneksi, "G1", "2")/$jmlPes*100; if(strlen($G12) > 3){$G12 = round($G12,2);}
		$G13 = $CSurvey->resultSkala($CKoneksi, "G1", "3")/$jmlPes*100; if(strlen($G13) > 3){$G13 = round($G13,2);}
		$G14 = $CSurvey->resultSkala($CKoneksi, "G1", "4")/$jmlPes*100; if(strlen($G14) > 3){$G14 = round($G14,2);}
		$G15 = $CSurvey->resultSkala($CKoneksi, "G1", "5")/$jmlPes*100; if(strlen($G15) > 3){$G15 = round($G15,2);}
		
		$G21 = $CSurvey->resultSkala($CKoneksi, "G2", "1")/$jmlPes*100; if(strlen($G21) > 3){$G21 = round($G21,2);}
		$G22 = $CSurvey->resultSkala($CKoneksi, "G2", "2")/$jmlPes*100; if(strlen($G22) > 3){$G22 = round($G22,2);}
		$G23 = $CSurvey->resultSkala($CKoneksi, "G2", "3")/$jmlPes*100; if(strlen($G23) > 3){$G23 = round($G23,2);}
		$G24 = $CSurvey->resultSkala($CKoneksi, "G2", "4")/$jmlPes*100; if(strlen($G24) > 3){$G24 = round($G24,2);}
		$G25 = $CSurvey->resultSkala($CKoneksi, "G2", "5")/$jmlPes*100; if(strlen($G25) > 3){$G25 = round($G25,2);}
		
		$G31 = $CSurvey->resultSkala($CKoneksi, "G3", "1")/$jmlPes*100; if(strlen($G31) > 3){$G31 = round($G31,2);}
		$G32 = $CSurvey->resultSkala($CKoneksi, "G3", "2")/$jmlPes*100; if(strlen($G32) > 3){$G32 = round($G32,2);}
		$G33 = $CSurvey->resultSkala($CKoneksi, "G3", "3")/$jmlPes*100; if(strlen($G33) > 3){$G33 = round($G33,2);}
		$G34 = $CSurvey->resultSkala($CKoneksi, "G3", "4")/$jmlPes*100; if(strlen($G34) > 3){$G34 = round($G34,2);}
		$G35 = $CSurvey->resultSkala($CKoneksi, "G3", "5")/$jmlPes*100; if(strlen($G35) > 3){$G35 = round($G35,2);}
		
		$G41 = $CSurvey->resultSkala($CKoneksi, "G4", "1")/$jmlPes*100; if(strlen($G41) > 3){$G41 = round($G41,2);}
		$G42 = $CSurvey->resultSkala($CKoneksi, "G4", "2")/$jmlPes*100; if(strlen($G42) > 3){$G42 = round($G42,2);}
		$G43 = $CSurvey->resultSkala($CKoneksi, "G4", "3")/$jmlPes*100; if(strlen($G43) > 3){$G43 = round($G43,2);}
		$G44 = $CSurvey->resultSkala($CKoneksi, "G4", "4")/$jmlPes*100; if(strlen($G44) > 3){$G44 = round($G44,2);}
		$G45 = $CSurvey->resultSkala($CKoneksi, "G4", "5")/$jmlPes*100; if(strlen($G45) > 3){$G45 = round($G45,2);}
	}
?>
<script type="text/javascript" src="../../js/main.js"></script>
<link href="../../css/main.css" rel="stylesheet" type="text/css"/>
<link href="../../css/report.css" rel="stylesheet" type="text/css"/>
<link href="../../css/archives.css" rel="stylesheet" type="text/css"/>
<link href="../../andhikaportal/css/main.css" rel="stylesheet" type="text/css"/>
<link href="../../andhikaportal/css/archives.css" rel="stylesheet" type="text/css"/>

<body>
<table cellpadding="0" cellspacing="0" width="100%" border="0" style="font-family:'Arial Narrow';"> 
<tr>
	<td width="1%">&nbsp;</td>
	<td valign="top"><br/>
	Jumlah Peserta Survey hingga <span style="text-decoration:underline;color:#00F;font-weight:bold;"><?php echo $tglSek; ?></span>: <b><?php echo $jmlPes; ?></b> (<b><?php echo $jmlPesPer; ?>%</b>) dari <b><?php echo $allPin; ?></b> PIN</td>
    <td width="1%">&nbsp;</td>
</tr>
<tr><td colspan="3">&nbsp;</td></tr>
<tr>
	<td colspan="3">
   	  <table cellpadding="0" cellspacing="0" width="100%" style="font-family:'Arial Narrow';">
<!-- START - Organisasi/ Perusahaan Group (A) =============================================================================== -->
            <tr align="center" bgcolor="#F3F3F3" style="font-size:18px;">
                <td colspan="2" height="30" class="tabelBorderLeftRightNull"><b>Organisasi/ Perusahaan</b></td>
                <td width="6%" class="tabelBorderAll"><b>STS</b></td>
                <td width="6%" class="tabelBorderLeftNull"><b>TS</b></td>
                <td width="6%" class="tabelBorderLeftNull"><b>N</b></td>
                <td width="6%" class="tabelBorderLeftNull"><b>S</b></td>
                <td width="6%" class="tabelBorderLeftRightNull"><b>SS</b></td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">1. </td>
                <td width="67%" class="tabelBorderBottomJust" >&nbsp;Saya bangga menyampaikan kepada orang-orang bahwa saya bekerja untuk Perusahaan ini</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $A11; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $A12; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $A13; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $A14; ?>%
                </td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $A15; ?>%
              	</td>
            </tr>
            <tr>
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">2. </td>
                <td width="67%" class="tabelBorderBottomJust">&nbsp;Perusahaan tempat saya bekerja merupakan salah satu tempat bekerja terbaik</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $A21; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $A22; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $A23; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $A24; ?>%
                </td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $A25; ?>%
              	</td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">3. </td>
                <td width="67%" class="tabelBorderBottomJust" >&nbsp;Perusahaan memperlakukan saya dengan baik</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $A31; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $A32; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $A33; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $A34; ?>%
                </td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $A35; ?>%
              	</td>
            </tr>
<!-- END OF - Organisasi/ Perusahaan Group (A) ============================================================================== -->
<!-- START - Pekerjaan Group (B) ============================================================================================ -->
            <tr align="center" bgcolor="#F3F3F3" height="20" style="font-size:18px;">
                <td colspan="2" height="30" class="tabelBorderBottomJust" ><b>Pekerjaan</b></td>
                <td width="6%" class="tabelBorderTopNull"><b>STS</b></td>
                <td width="6%" class="tabelBorderTopLeftNull"><b>TS</b></td>
                <td width="6%" class="tabelBorderTopLeftNull"><b>N</b></td>
                <td width="6%" class="tabelBorderTopLeftNull"><b>S</b></td>
                <td width="6%" class="tabelBorderBottomJust"><b>SS</b></td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">1. </td>
                <td width="67%" class="tabelBorderBottomJust" >&nbsp;Saya memahami kemampuan kerja yang saya miliki untuk berkontribusi kepada Perusahaan</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $B11; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $B12; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $B13; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $B14; ?>%
                </td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $B15; ?>%
              	</td>
            </tr>
            <tr>
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">2. </td>
                <td width="67%" class="tabelBorderBottomJust" style="font-size:15px;">&nbsp;Saya mendapat peralatan dan perlengkapan kerja dari Perusahaan untuk mendukung pekerjaan yang saya lakukan</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $B21; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $B22; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $B23; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $B24; ?>%
                </td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $B25; ?>%
              	</td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">3. </td>
                <td width="67%" class="tabelBorderBottomJust" >&nbsp;Saya puas dengan kondisi kerja di Perusahaan</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $B31; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $B32; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $B33; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $B34; ?>%
                </td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $B35; ?>%
              	</td>
            </tr>
            <tr>
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">4. </td>
                <td width="67%" class="tabelBorderBottomJust" >&nbsp;Saya puas dengan pekerjaan dan tugas dari Perusahaan yang saya lakukan selama ini</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $B41; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $B42; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $B43; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $B44; ?>%
                </td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $B45; ?>%
              	</td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">5. </td>
                <td width="67%" class="tabelBorderBottomJust" >&nbsp;Pekerjaan saya saat ini menarik dan menantang</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $B51; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $B52; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $B53; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $B54; ?>%
                </td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $B55; ?>%
              	</td>
            </tr>
            <tr>
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">6. </td>
                <td width="67%" class="tabelBorderBottomJust" >&nbsp;Saya mendapatkan pelatihan yang cukup terkait dengan lingkup pekerjaan saat ini</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $B61; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $B62; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $B63; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $B64; ?>%
                </td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $B65; ?>%
              	</td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">7. </td>
                <td width="67%" class="tabelBorderBottomJust" >&nbsp;Kinerja yang telah saya capai dengan baik diperhatikan dan dihargai oleh Atasan Langsung</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $B71; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $B72; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $B73; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $B74; ?>%
                </td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $B75; ?>%
              	</td>
            </tr>
            <tr>
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">8. </td>
                <td width="67%" class="tabelBorderBottomJust" >&nbsp;Secara keseluruhan, saya puas dengan pekerjaan saat ini</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $B81; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $B82; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $B83; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $B84; ?>%
                </td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $B85; ?>%
              	</td>
            </tr>
<!-- END OF - Pekerjaan Group (B) =========================================================================================== -->
<!-- START - Program Pelatihan Pengembangan Karir Group (C) ================================================================= -->
            <tr align="center" bgcolor="#F3F3F3" height="20" style="font-size:18px;">
                <td colspan="2" height="30" class="tabelBorderBottomJust" ><b>Program Pelatihan Pengembangan Karir</b></td>
                <td width="6%" class="tabelBorderTopNull"><b>STS</b></td>
                <td width="6%" class="tabelBorderTopLeftNull"><b>TS</b></td>
                <td width="6%" class="tabelBorderTopLeftNull"><b>N</b></td>
                <td width="6%" class="tabelBorderTopLeftNull"><b>S</b></td>
                <td width="6%" class="tabelBorderBottomJust"><b>SS</b></td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">1. </td>
                <td width="67%" class="tabelBorderBottomJust" >&nbsp;Saya mendapatkan evaluasi atas kinerja secara berkala</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $C11; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $C12; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $C13; ?>%
                </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $C14; ?>%
                </td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $C15; ?>%
              	</td>
            </tr>
            <tr>
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">2. </td>
                <td width="67%" class="tabelBorderBottomJust" style="font-size:15px;">&nbsp;Saya puas dengan kesempatan untuk mendapatkan pelatihan yang relevan dengan pekerjaan dan tuntutan bisnis</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $C21; ?>%
            </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $C22; ?>%
            </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $C23; ?>%
            </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $C24; ?>%
            </td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $C25; ?>%
           	  </td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">3. </td>
                <td width="67%" class="tabelBorderBottomJust" style="font-size:14px;">&nbsp;Perusahaan mengutamakan pemenuhan posisi lowong melalui suksesi internal dibandingkan dengan merekrut karyawan baru</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $C31; ?>%
            </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $C32; ?>%
            </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $C33; ?>%
            </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $C34; ?>%
            </td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $C35; ?>%
           	  </td>
            </tr>
            <tr>
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">4. </td>
                <td width="67%" class="tabelBorderBottomJust" >&nbsp;Saya puas dengan kesempatan berkarir yang diberikan oleh Perusahaan</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $C41; ?>%
            </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $C42; ?>%
            </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $C43; ?>%
            </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $C44; ?>%
            </td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $C45; ?>%
           	  </td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">5. </td>
                <td width="67%" class="tabelBorderBottomJust" >&nbsp;Promosi diberikan kepada karyawan yang berkinerja baik/ berprestasi</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $C51; ?>%
            </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $C52; ?>%
            </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $C53; ?>%
            </td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $C54; ?>%
            </td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $C55; ?>%
           	  </td>
            </tr>
<!-- END OF - Program Pelatihan Pengembangan Karir Group (C) ================================================================ -->
<!-- START - Hubungan Kerja dengan Atasan Langsung Group (D) ================================================================ -->
			<tr align="center" bgcolor="#F3F3F3" height="20" style="font-size:18px;">
                <td colspan="2" height="30" class="tabelBorderBottomJust" ><b>Hubungan Kerja dengan Atasan Langsung</b></td>
                <td width="6%" class="tabelBorderTopNull"><b>STS</b></td>
                <td width="6%" class="tabelBorderTopLeftNull"><b>TS</b></td>
                <td width="6%" class="tabelBorderTopLeftNull"><b>N</b></td>
                <td width="6%" class="tabelBorderTopLeftNull"><b>S</b></td>
                <td width="6%" class="tabelBorderBottomJust"><b>SS</b></td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">1. </td>
                <td width="67%" class="tabelBorderBottomJust" >&nbsp;Atasan Langsung mengkomunikasikan perencanaan dan sasaran kerja bersama saya</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $D11; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D12; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D13; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D14; ?>%
            	</td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $D15; ?>%
           	  	</td>
            </tr>
            <tr>
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">2. </td>
                <td width="67%" class="tabelBorderBottomJust" >&nbsp;Atasan Langsung memberikan instruksi kerja yang jelas</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $D21; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D22; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D23; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D24; ?>%
            	</td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $D25; ?>%
           	  	</td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">3. </td>
                <td width="67%" class="tabelBorderBottomJust" >&nbsp;Atasan Langsung memberikan saran jika saya menghadapi masalah terkait pekerjaan</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $D31; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D32; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D33; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D34; ?>%
            	</td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $D35; ?>%
           	  	</td>
            </tr>
            <tr>
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">4. </td>
                <td width="67%" class="tabelBorderBottomJust" >&nbsp;Atasan Langsung percaya akan kemampuan kerja yang saya miliki</td>
               <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $D41; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D42; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D43; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D44; ?>%
            	</td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $D45; ?>%
           	  	</td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">5. </td>
                <td width="67%" class="tabelBorderBottomJust" >&nbsp;Atasan Langsung membantu saya dalam hal pengembangan diri dan kemampuan kerja</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $D51; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D52; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D53; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D54; ?>%
            	</td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $D55; ?>%
           	  	</td>
            </tr>
            <tr>
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">6. </td>
                <td width="67%" class="tabelBorderBottomJust" >&nbsp;Atasan Langsung memberikan tindakan perbaikan jika saya tidak berhasil mencapai target kinerja</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $D61; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D62; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D63; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D64; ?>%
            	</td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $D65; ?>%
           	  	</td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">7. </td>
                <td width="67%" class="tabelBorderBottomJust" >&nbsp;Saya merasa nyaman untuk menyampaikan segala hal secara jujur dan terbuka kepada Atasan Langsung</td>
               <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $D71; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D72; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D73; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D74; ?>%
            	</td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $D75; ?>%
           	  	</td>
            </tr>
            <tr>
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">8. </td>
                <td width="67%" class="tabelBorderBottomJust" style="font-size:14px;">&nbsp;Atasan Langsung secara berkala melakukan koordinasi internal untuk memastikan pekerjaan tim dilakukan dengan baik</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $D81; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D82; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D83; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D84; ?>%
            	</td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $D85; ?>%
           	  	</td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">9. </td>
                <td width="67%" class="tabelBorderBottomJust" style="font-size:14px;">&nbsp;Melalui koordinasi internal, saya dapat mengetahui perkembangan informasi pekerjaan dan meningkatkan efektivitas kerja</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $D91; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D92; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D93; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D94; ?>%
            	</td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $D95; ?>%
           	  </td>
            </tr>
            <tr>
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">10. </td>
                <td width="67%" class="tabelBorderBottomJust" >&nbsp;Keputusan yang diambil oleh Atasan Langsung saya sudah efektif</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $D101; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D102; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D103; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D104; ?>%
            	</td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $D105; ?>%
           	  	</td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">11. </td>
                <td width="67%" class="tabelBorderBottomJust" >&nbsp;Atasan Langsung saya mengetahui kondisi kerja di dalam tim kerja</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $D111; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D112; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D113; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D114; ?>%
            	</td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $D115; ?>%
           	  </td>
            	</tr>
            <tr>
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">12. </td>
                <td width="67%" class="tabelBorderBottomJust" >&nbsp;Atasan Langsung saya melakukan pekerjaan dengan baik</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $D121; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D122; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D123; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $D124; ?>%
            	</td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $D125; ?>%
           	  </td>
            </tr>
<!-- END OF - Hubungan Kerja dengan Atasan Langsung Group (D) =============================================================== -->
<!-- START - Hubungan Kerja dengan Rekan Kerja Group (E) ==================================================================== -->
			<tr align="center" bgcolor="#F3F3F3" height="20" style="font-size:18px;">
                <td colspan="2" height="30" class="tabelBorderBottomJust" ><b>Hubungan Kerja dengan Rekan Kerja</b></td>
                <td width="6%" class="tabelBorderTopNull"><b>STS</b></td>
                <td width="6%" class="tabelBorderTopLeftNull"><b>TS</b></td>
                <td width="6%" class="tabelBorderTopLeftNull"><b>N</b></td>
                <td width="6%" class="tabelBorderTopLeftNull"><b>S</b></td>
                <td width="6%" class="tabelBorderBottomJust"><b>SS</b></td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">1. </td>
                <td width="67%" class="tabelBorderBottomJust" >&nbsp;Saya puas dengan cara tim kerja untuk menyelesaikan masalah terkait dengan pekerjaan</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $E11; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $E12; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $E13; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $E14; ?>%
            	</td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $E15; ?>%
           	 	 </td>
            </tr>
            <tr>
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">2. </td>
                <td width="67%" class="tabelBorderBottomJust" >&nbsp;Kerja sama dalam tim saya berjalan dengan baik</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $E21; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $E22; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $E23; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $E24; ?>%
            	</td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $E25; ?>%
           	 	 </td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">3. </td>
                <td width="67%" class="tabelBorderBottomJust" >&nbsp;Beban pekerjaan didistribusikan dengan baik dalam tim kerja saya</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $E31; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $E32; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $E33; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $E34; ?>%
            	</td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $E35; ?>%
           	 	 </td>
            </tr>
            <tr>
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">4. </td>
                <td width="67%" class="tabelBorderBottomJust" >&nbsp;Saya  merasa nyaman untuk menyampaikan segala hal secara jujur dan terbuka kepada Rekan Kerja</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $E41; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $E42; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $E43; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $E44; ?>%
            	</td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $E45; ?>%
           	 	 </td>
            </tr>
<!-- END OF - Hubungan Kerja dengan Rekan Kerja Group (E) =================================================================== -->
<!-- START - Kondisi Kerja dan Lingkungan Kerja Group (E) =================================================================== -->
			<tr align="center" bgcolor="#F3F3F3" height="20" style="font-size:18px;">
                <td colspan="2" height="30" class="tabelBorderBottomJust" ><b>Kondisi Kerja dan Lingkungan Kerja</b></td>
                <td width="6%" class="tabelBorderTopNull"><b>STS</b></td>
                <td width="6%" class="tabelBorderTopLeftNull"><b>TS</b></td>
                <td width="6%" class="tabelBorderTopLeftNull"><b>N</b></td>
                <td width="6%" class="tabelBorderTopLeftNull"><b>S</b></td>
                <td width="6%" class="tabelBorderBottomJust"><b>SS</b></td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">1. </td>
                <td width="67%" class="tabelBorderBottomJust" >&nbsp;Saya yakin lingkungan kerja saya saat ini aman</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $F11; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $F12; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $F13; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $F14; ?>%
            	</td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $F15; ?>%
           	 	 </td>
            </tr>
            <tr>
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">2. </td>
                <td width="67%" class="tabelBorderBottomJust" >&nbsp;Kondisi Perusahaan saat ini baik secara fisik dan infrastruktur</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $F21; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $F22; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $F23; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $F24; ?>%
            	</td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $F25; ?>%
           	 	 </td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">3. </td>
                <td width="67%" class="tabelBorderBottomJust" >&nbsp;Target kerja yang ditetapkan saat ini wajar dan realistis</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $F31; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $F32; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $F33; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $F34; ?>%
            	</td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $F35; ?>%
           	 	 </td>
            </tr>
            <tr>
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">4. </td>
                <td width="67%" class="tabelBorderBottomJust" >&nbsp;Beban pekerjaan saya saat ini sesuai kemampuan dan masuk akal</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $F41; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $F42; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $F43; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $F44; ?>%
            	</td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $F45; ?>%
           	 	 </td>
            </tr>
<!-- END OF - Kondisi Kerja dan Lingkungan Kerja Group (F) ================================================================== -->
<!-- START - Kompensasi dan Benefit Group (G) =============================================================================== -->
			<tr align="center" bgcolor="#F3F3F3" height="20" style="font-size:18px;">
                <td colspan="2" height="30" class="tabelBorderBottomJust" ><b>Kompensasi dan Benefit</b></td>
                <td width="6%" class="tabelBorderTopNull"><b>STS</b></td>
                <td width="6%" class="tabelBorderTopLeftNull"><b>TS</b></td>
                <td width="6%" class="tabelBorderTopLeftNull"><b>N</b></td>
                <td width="6%" class="tabelBorderTopLeftNull"><b>S</b></td>
                <td width="6%" class="tabelBorderBottomJust"><b>SS</b></td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">1. </td>
                <td width="67%" class="tabelBorderBottomJust" style="font-size:13px;">&nbsp;Saya puas dengan tunjangan kesejahteraan dari Perusahaan seperti tunjangan kesehatan, tunjangan komunikasi, bonus dan lainnya</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $G11; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $G12; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $G13; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $G14; ?>%
            	</td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $G15; ?>%
           	 	 </td>
            </tr>
            <tr>
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">2. </td>
                <td width="67%" class="tabelBorderBottomJust" style="font-size:14px;">&nbsp;Saya puas dengan program rekreasi karyawan berupa kegiatan wisata, team building, outing, atau kegiatan lainnya</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $G21; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $G22; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $G23; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $G24; ?>%
            	</td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $G25; ?>%
           	 	 </td>
            </tr>
            <tr bgcolor="#F6FFFF">
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">3. </td>
                <td width="67%" class="tabelBorderBottomJust" >&nbsp;Saya puas dengan ketentuan hari istirahat / libur yang diberikan atas ketetapan Perusahaan</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $G31; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $G32; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $G33; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $G34; ?>%
            	</td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $G35; ?>%
           	 	 </td>
            </tr>
            <tr>
            	<td width="3%" height="25" class="tabelBorderTopLeftNull" align="center">4. </td>
                <td width="67%" class="tabelBorderBottomJust" >&nbsp;Secara umum Perusahaan peduli terhadap kesejahteraan karyawan</td>
                <td width="6%" class="tabelBorderTopNull" align="center">
                	 <?php echo $G41; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $G42; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $G43; ?>%
            	</td>
                <td width="6%" class="tabelBorderTopLeftNull" align="center">
                	<?php echo $G44; ?>%
            	</td>
              	<td width="6%" class="tabelBorderBottomJust" align="center">
              		<?php echo $G45; ?>%
           	 	 </td>
            </tr>
<!-- END OF - Kompensasi dan Benefit Group (G) ============================================================================== -->
			<tr height="5"><td colspan="7" align="center"></td></tr>
		</table>
	</td>
</tr>
</table>