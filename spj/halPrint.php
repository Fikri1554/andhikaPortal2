<!DOCTYPE HTML>
<link href="css/cssSpj.css" rel="stylesheet" type="text/css" />
<link href="../css/archives.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/jquery-1.11.2.js"></script>
<script>
$(function() {
	$('textarea').each(function() {
		$(this).height($(this).prop('scrollHeight'));
	});
});
</script>
<?php
require_once("../config.php");
require_once("configSpj.php");

$formIdPost = $_POST['formId'];
$reportIdPost = $_POST['reportId'];
$reportReviseIdPost = $_POST['reportReviseId'];
$halPost = $_POST['hal'];

$fromDate = $_POST['fromDate'];
$tgl =  substr($fromDate,0,2);
$bln =  substr($fromDate,4,2);
$thn =  substr($fromDate,6,4);
$dateForm = $thn."/".$bln."/".$tgl;
$fromDateD = $CPublic->bulanSetengah($bln, "eng")." ".$tgl.", ".$thn;

$toDate = $_POST['toDate'];
$tglTo =  substr($toDate,0,2);
$blnTo =  substr($toDate,4,2);
$thnTo =  substr($toDate,6,4);
$dateTo = $thnTo."/".$blnTo."/".$tglTo;
$toDateD = $CPublic->bulanSetengah($blnTo, "eng")." ".$tglTo.", ".$thnTo;

// ======== START == Print SPJ FORM ===========================================================
if($halPost == "spj")
{
	$tpl = new myTemplate("templates/report.html");
	
	$tpl->prepare();
	
	$html = "";
	$isiReport = "";
	
	$ownerUserJenis = $CSpj->userJenis($CSpj->detilForm($formIdPost, "ownerid"));
	
	$query = $CKoneksiSpj->mysqlQuery("SELECT * FROM form WHERE formid = ".$formIdPost." AND deletests=0;");
	$row = $CKoneksiSpj->mysqlFetch($query);
	//echo "a ".$halamanGet;
	$dateForm = $row['datefrom'];
	$thn =  substr($dateForm,0,4);
	$bln =  substr($dateForm,4,2);
	$tgl =  substr($dateForm,6,2);
	$formDate =  $tgl." ".$CPublic->detilBulanNamaAngka($bln, "ind")." ".$thn;
	
	$dateTo = $row['dateto'];
	$thnTo =  substr($dateTo,0,4);
	$blnTo =  substr($dateTo,4,2);
	$tglTo =  substr($dateTo,6,2);
	$toDate =  $tglTo." ".$CPublic->detilBulanNamaAngka($blnTo, "ind")." ".$thnTo;
	$comp = $row['kdcmp'];
	
	$dtMnyTjui = explode("/",$row['updusrdt']);
	$yearMnyTjui = substr($dtMnyTjui[1],0,4);
	$monthMnyTjui = substr($dtMnyTjui[1],4,2);
	$dayMnyTjui = substr($dtMnyTjui[1],6,2);
	$dateMnyTjuiCmpltNya = $dayMnyTjui."-".$monthMnyTjui."-".$yearMnyTjui." ".$dtMnyTjui[2];

	$dtMngth = explode("/",$row['updusrdtack']);
	$yearMngth = substr($dtMngth[1],0,4);
	$monthMngth = substr($dtMngth[1],4,2);
	$dayMngth = substr($dtMngth[1],6,2);
	$dateMngthCmpltNya = $dayMngth."-".$monthMngth."-".$yearMngth." ".$dtMngth[2];

	$cashAdv = "";
	if($row['cashadvance'] > 0)
	{
	    $cashAdv = "(".$row['currency'].") ".number_format($row['cashadvance'],2);
	}

	$vslNya = "";
	if($row['vessel_name'] != "" AND $row['vessel_code'] != "0")
	{
	    $vslNya = $row['vessel_name'];
	}
	
//HEADER
	$html.= '<tr height="90px"> 
				<td style="border-bottom:solid 3px #000;">
				<table cellpadding="0" cellspacing="0" width="90%">
				<tr><td width="7%" height="2px"></td></tr>
				<tr>
					<td>&nbsp;</td>
					<td width="12%" align="center">
						<img src="company/'.$CSpj->detilCmp($comp, "logokiri").'" width="60px" />
					</td>
					<td width="74%" align="center" rowspan="2">';
					// Menentukan Perusahaan -> Andhika atau Adnyana
						$logo = $CSpj->detilCmp($comp, "logo");
						$img = "<img src=\"company/".$logo."\" height=\"".$CSpj->detilCmp($comp, "sizelogo")."px\"/>";
						
						$logoKanan = "";
						if($CSpj->detilCmp($comp, "logokanan") != "")
						{
							$logoKanan = "<img src=\"company/".$CSpj->detilCmp($comp, "logokanan")."\" height=\"60px\"/>";
						}			
	$html.= '		
						'.$img.'
						</td>
					<td width="7%">'.$logoKanan.'</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td width="12%" style="font-family:Arial;font-size:7px;font-weight:bold;" align="center">';
					
	$html.= '				'.$logo = $CSpj->detilCmp($comp, "ketkiri").'				
					</td>
					<td width="7%"></td>
				</tr>
				</table>
				</td>
			</tr>';
	
	$html.= '<tr><td height="10"></td></tr>';
	
// Nomor SPJ		
	$html.= '<tr>
				<td align="center" class="fontMyFolderList" style="color:#000;">
					<span style="text-decoration:underline;font-size:14px;font-weight:bold;">SURAT PERINTAH JALAN</span>
					<br/>';
						$spjNo = $row['spjno'];
						$nomor = "";
						if($spjNo != "")
						{
							$nomor = "Nomor : ".$row['spjno']."";
						}
					
	$html.= '		'.$nomor.'
					</td>
			</tr>
			<tr><td height="10"></td></tr>';
	
// tabel detil
	$html.= '<tr>
				<td align="center">
				<table class="fontMyFolderList" cellpadding="0" cellspacing="3" width="98%" style="border:solid 2px #000;">
				<tr height="18px">
					<td width="14%" align="left">Nama</td>
					<td width="1%" align="left">:</td>
					<td width="35%" align="left" style="color:#000080;border-bottom:solid 1px #000;">
						&nbsp;'.ucwords(strtolower($row['ownername'])).'
					</td>
					<td width="17%" align="left">Keperluan</td>
					<td width="1%" align="left">:</td>
					<td width="32%" align="left" style="color:#000080;border-bottom:solid 1px #000;">
						&nbsp;'.$row['necessary'].'
					</td>
				</tr>
				
				<tr height="17px">
					<td align="left">Jabatan</td>
					<td align="left">:</td>
					<td align="left" style="color:#000080;border-bottom:solid 1px #000;">
						&nbsp;'.ucwords(strtolower($CEmployee->detilJabatan($CEmployee->detilTblEmpGen($row['ownerempno'], "kdjabatan"), "nmjabatan"))).'
					</td>
					<td align="left">Tanggal Berangkat</td>
					<td align="left">:</td>
					<td align="left" style="color:#000080;border-bottom:solid 1px #000;">
						&nbsp;'.ucwords(strtolower($formDate)).'
					</td>
				</tr>
				
				<tr height="17px">
					<td align="left">Golongan</td>
					<td align="left">:</td>
					<td align="left" style="color:#000080;border-bottom:solid 1px #000;">
						&nbsp;'.ucwords(strtolower($CEmployee->detilPangkat($CEmployee->detilTblEmpGen($row['ownerempno'], "kdpangkat"), "nmpangkat"))).'
					</td>
					<td align="left">Tanggal Kembali</td>
					<td align="left">:</td>
					<td align="left" style="color:#000080;border-bottom:solid 1px #000;">
						&nbsp;'.ucwords(strtolower($toDate)).'
					</td>
				</tr>
				
				<tr height="17px">
					<td align="left">Tempat Tujuan</td>
					<td align="left">:</td>
					<td align="left" style="color:#000080;border-bottom:solid 1px #000;">
						&nbsp;'.$row['destination'].'
					</td>
					<td align="left">Kendaraan</td>
					<td align="left">:</td>
					<td align="left" style="color:#000080;border-bottom:solid 1px #000;">
						&nbsp;'.$row['vehicle'].'
					</td>
				</tr>

				<tr height="17px">
					<td align="left">Vessel</td>
					<td align="left">:</td>
					<td align="left" style="color:#000080;border-bottom:solid 1px #000;">
						&nbsp;'.$vslNya.'
					</td>
					<td align="left">Cash Advance</td>
					<td align="left">:</td>
					<td align="left" style="color:#000080;border-bottom:solid 1px #000;">
						&nbsp;'.$cashAdv.'
					</td>
				</tr>
				
				<tr>
					<td colspan="3">
					<table class="fontMyFolderList" cellpadding="0" cellspacing="0" border="0" width="100%">
						<tr>
							<td width="14%" align="left" valign="top">Catatan</td>
							<td width="1%" align="left" valign="top">:</td>
							<td width="35%" align="left" valign="top">&nbsp;</td>
						</tr>
						<tr>
							<td width="59%" colspan="3" align="left" valign="top" class="borderAll">
								<!--<textarea style="color:#000080;font-family:Tahoma;font-size:12px;width:400px;overflow:hidden;padding-bottom:5px;" readonly>'.$row['note'].'</textarea>
								<textarea style="color:#000080;font-family:Tahoma;font-size:12px;width:98%;border:none;overflow:hidden;" readonly>'.$row['note'].'</textarea>-->
								&nbsp '.$row['note'].'
							</td>
						
					</table>
					</td>
						
					<td align="left" valign="top">Pengikut</td>
	
					<td valign="top">:</td>	
					<td  align="left" style="color:#000080;" valign="top">';
					$follower = "";
					if($CSpj->menuFollower($formIdPost, $db) == "")
					{
						$follower = "&nbsp;-";
					}
					if($CSpj->menuFollower($formIdPost, $db) != "")
					{
						$follower = $CSpj->menuFollower($formIdPost, $db);
					}
	$html.= '		<div style="margin-left:4px;">'.$follower.'</div>
					</td>
					</td>
				</tr>
				<tr><td colspan="4" height="2"></td></tr>
				</table>
				</td>
			</tr>';
	
// Data Approval
	$html.= '<tr><td height="15"></td></tr>
				
				<tr>
				<td align="center">
					<table class="fontMyFolderList" style="font-size:13px;" cellpadding="0" cellspacing="3" width="98%">
					<tr align="left">
						<td width="14%">
							Dikeluarkan di
						</td>
						<td width="1%">:</td>
						<td width="85%" style="color:#000080;">
							Jakarta
						</td>
					</tr>
					<tr align="left">
						<td>
							Pada Tanggal
						</td>
						<td>:</td>
						<td style="color:#000080;">';
							$tglSpj = "<i>Waiting . . .</i>";
							if($row['spjdate'] != "")
							{
								$tglSpjDb = $row['spjdate'];
								$thnSpj =  substr($tglSpjDb,0,4);
								$blnSpj =  substr($tglSpjDb,4,2);
								$tglSpj =  substr($tglSpjDb,6,2);
								$tglSpj =  $tglSpj." ".$CPublic->detilBulanNamaAngka($blnSpj, "ind")." ".$thnSpj;
							}
							
	$html.= '					'.ucwords(strtolower($tglSpj)).'
						</td>
					</tr>
					<tr><td colspan="2" height="10"></td></tr>
					</table>
				</td>
				</tr>';
	//Cek Siapa yg aproved
	if($row['aprvbyadm'] == "N")// Jika sudah Approved
	{
		$aprv = $CSpj->detilLoginSpjByEmpno($row['aprvempno'], "userfullnm", $db);
	}
	if($row['aprvbyadm'] == "Y")// Jika Approved dari Administrator
	{
		$aprv = $CSpj->detilLoginSpjByEmpno($row['kadivempno'], "userfullnm", $db);
	}
	//Jabatan Aprover
	$jabatan = "";
	if($row['kadivempno'] == "00625" || $row['kadivempno'] == "00507")
	{
		$jabatan = "CEO";
		$dateMnyTjuiCmpltNya = "";
		$dateMngthCmpltNya = "";
	}
	if($row['kadivempno'] != "00625" || $row['kadivempno'] != "00507")
	{
		$jbtn = $CSpj->detilDivByDivhead($row['kadivempno'], "nmdiv");
		// $jabatan = "Kadiv. ".$CSpj->detilDivByDivhead($row['kadivempno'], "nmdiv");
		if($jbtn == "")
		{
			$jbtn = $CSpj->detilLoginSpjByEmpno($row['kadivempno'], "nmjabatan", $db);
		}
		if($row['kadivempno'] == '00557')
		{
			$jbtn = $CSpj->detilLoginSpjByEmpno($row['kadivempno'], "nmjabatan", $db);
		}
		// $jabatan = "Kadiv. ".$jbtn;
		$jabatan = $jbtn;
	}
	
	$menyetujui = "Menyetujui,";
	$sign = '<img src="picture/sign.JPG" height="25px"/>';
	$aprover = ucwords(strtolower($aprv));
	$garis = 'style="border-bottom:#000 solid 1px;font-weight:bold;"';
	
	if($ownerUserJenis == "CEO")
	{
		$menyetujui = '';
		$sign = '';
		$aprover = '';
		$garis = '';
		$jabatan = '';
	}
	$html.= '<tr>
				<td align="center">
				<table class="fontMyFolderList" cellpadding="0" cellspacing="0" border="0" width="96%">
					<tr>
						<td width="24%" align="center">'.$menyetujui.'</td>
						<td width="52%">&nbsp;</td>
						<td width="24%" align="center">Mengetahui,</td>
					</tr>
					<tr>
						<td height="50px" align="center">'.$sign.'</td>
						<td height="50px"></td>
						<td height="50px" align="center"><img src="picture/sign.jpg" height="25px"/></td>
					</tr>
					<tr>
						<td width="24%" align="center" '.$garis.'>
						<!--script 1-->	
							'.$aprover.'
						</td>
						<td width="52%">&nbsp;</td>
						<td width="24%" align="center" style="border-bottom:#000 solid 1px;font-weight:bold;">';
						//script 2
							if($row['knowbyadm'] == "N")//jika Sudah Acknowledge
							{
								$know = $CSpj->detilLoginSpjByEmpno($CSpj->detilForm($formIdPost, "knowempno"), "userfullnm", $db);
							}
							if($row['knowbyadm'] == "Y" || $row['kadivempno'] == "00625" || $row['kadivempno'] == "00507")
							{
								$know = $CSpj->detilLoginSpjByEmpno($CEmployee->detilDiv("050", "divhead"), "userfullnm", $db);
							}
	$html.= '					'.ucwords(strtolower($know)).'
						</td>
					</tr>
					<tr>
						<td width="24%" align="center">';
						//script3 
							
	$html.= '					'.$jabatan.'<br><span style=\'font-size:8px;color:grey;\'>'.$dateMnyTjuiCmpltNya.'</span>
						</td>
						<td width="52%">&nbsp;</td>
						<td width="24%" align="center">Kadiv. HR & Support<br><span style=\'font-size:8px;color:grey;\'>'.$dateMngthCmpltNya.'</span></td>
					</tr>
				</table>
				</td>
			</tr>';
	
//Tembusan s/d Tabel Paraf		
	$html.= '<tr><td height="15px"></td></tr> 
				<tr>
					<td align="center">
					<table class="fontMyFolderList" cellpadding="0" cellspacing="0" border="0" width="96%">
					<tr>
						<td colspan="3" align="left">Tembusan	:</td>
					</tr>
					<tr align="left">
						<td width="6%">&nbsp;</td>
						<td width="94%">
							'.$CSpj->listTembusan($formIdPost).'
						</td>	
					</tr>
					<tr><td colspan="3" height="10px;"></td></tr>
					<tr>
						<td colspan="3" align="left">Diisi oleh petugas di Tempat Tujuan (Hanya Digunakan saat Kunjungan Kapal / Kebutuhan Internal Lainnya) :</td>
					</tr>
					</table>
					</td>
				</tr>
				<tr><td height="3px"></td></tr>
				<tr>
					<td align="center">
					<table class="fontMyFolderList" cellpadding="0" cellspacing="0" border="0" width="98%" style="color:#000;">
						<tr height="20px">
							<td width="25%" align="left" class="borderAll">&nbsp; Tanggal Kedatangan</td>
							<td width="25%" align="left" class="borderLeftNull">&nbsp;</td>
							<td width="25%" align="left" class="borderLeftNull">&nbsp; Tanggal Kembali</td>
							<td width="25%" align="left" class="borderLeftNull">&nbsp;</td>
						</tr>
						<tr height="80px" style="font-size:8px;" valign="top">
							<td align="left" class="borderBottomTopNull">&nbsp; Paraf</td>
							<td align="left" class="borderRightJust">&nbsp; Stempel</td>
							<td align="left" class="borderRightJust">&nbsp; Paraf</td>
							<td align="left" class="borderRightJust">&nbsp; Stempel</td>
						</tr>
						<tr height="20px">
							<td width="25%" align="left" class="borderAll">&nbsp; Tanggal Kedatangan</td>
							<td width="25%" align="left" class="borderLeftNull">&nbsp;</td>
							<td width="25%" align="left" class="borderLeftNull">&nbsp; Tanggal Kembali</td>
							<td width="25%" align="left" class="borderLeftNull">&nbsp;</td>
						</tr>
						<tr height="80px" style="font-size:8px;" valign="top">
							<td align="left" class="borderTopNull">&nbsp; Paraf</td>
							<td align="left" class="borderTopLeftNull">&nbsp; Stempel</td>
							<td align="left" class="borderTopLeftNull">&nbsp; Paraf</td>
							<td align="left" class="borderTopLeftNull">&nbsp; Stempel</td>
						</tr>
					</table>
					</td>
				</tr>';
			
	$html.= "<tr>
				<td height=\"30\">&nbsp;</td>
			</tr>";
	// FOOTER		
	/*$html.= "<tr>
				<td style=\"position:absolute;bottom:-0%;left:23%;width:100%;\" align=\"center\">
				<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\" style=\"font:0.6em sans-serif;\">
					<tr><td align=\"center\">
						Head Office : Menara KADIN Indonesia (20<sup>th</sup> Floor), Jl. H.R. Rasuna Said Blok X-5 Kav. 2&amp;3
					</td></tr><tr><td align=\"center\">
						Kuningan, Jakarta 12950, Indonesia. Phone.: +62-(021) 522 7220 Facsimile.: +62-(021) 522 7221
					</td></tr><tr><td align=\"center\">
						Email : ship.marketing@andhika.com Website : http://www.andhika.com/
					</td></tr>
				</table>
				</td>
			</tr>";*/
	$html.= "<tr>
				<!--<td style=\"position:absolute;bottom:-0%;left:".$CSpj->detilCmp($comp, "posisialamat").";width:100%;\" align=\"center\">-->
				<td style=\"position:absolute;bottom:-0%;left:0;width:100%;\" align=\"center\">
				<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\" style=\"font:0.6em sans-serif;\">
					<tr><td align=\"center\">
						<!--Head Office : Menara KADIN Indonesia (20<sup>th</sup> Floor), Jl. H.R. Rasuna Said Blok X-5 Kav. 2&amp;3
						<br/>Kuningan, Jakarta 12950, Indonesia. Phone.: +62-(021) 522 7220 Facsimile.: +62-(021) 522 7221<br/>
						Email : ship.marketing@andhika.com Website : http://www.andhika.com/-->
						".$CSpj->detilCmp($comp, "alamat")."
					</td></tr>
				</table>
				</td>
			</tr>";
				
	$isiReport = $html;
	
	$tpl->Assign("isiReport", $isiReport);
	$tpl->printToScreen();
}
//-- END OF Print SPJ FORM  ===================================================================================================

// ======== START == Print SPJ REPORT =======================================================================================
if($halPost == "spjReport" || $halPost == "reportRevise")
{
	$tpl = new myTemplate("templates/report.html");
	
	$tpl->prepare();
	
	$tglSek = $CPublic->tglServer();
	$tglServ = substr($tglSek,6,2);
	$blnServ = substr($tglSek,4,2);
	$thnServ = substr($tglSek,0,4);
	$echoTglSek = $tglServ." ".ucwords(strtolower($CPublic->detilBulanNamaAngka($blnServ, "ind")))." ".$thnServ;
	
	if($halPost == "spjReport")
	{
		$classReport = "detilReport";
		$reportIdPost = $reportIdPost;
		$dbTbl = "reportdetil" ;
		$reportIdField = "reportid";
		$classJmlTglSama = "jmlTglSama";
		$classJmlLocSama = "jmlLocSama";
		$classUrutanAwalTglSm = "urutanAwalTglSm";
		$classUrutanAwalLocSm = "urutanAwalLocSm";
	}
	if($halPost == "reportRevise")
	{
		// $classReport = "detilReportRev";
		$classReport = "detilReport";
		$reportIdPost = $reportReviseIdPost;
		$dbTbl = "reportdetilrevise" ;
		$reportIdField = "reportreviseid";
		$classJmlTglSama = "jmlTglSamaRev";
		$classJmlLocSama = "jmlLocSamaRev";
		$classUrutanAwalTglSm = "urutanAwalTglSmRev";
		$classUrutanAwalLocSm = "urutanAwalLocSmRev";
	}
	
	$formId = $CSpj->$classReport($reportIdPost, "formid");
	$ownerUserJenis = $CSpj->userJenis($CSpj->detilForm($formId, "ownerid"));	
	
	$dateForm = $CSpj->detilForm($formId, "datefrom");
	$thn =  substr($dateForm,0,4);
	$bln =  substr($dateForm,4,2);
	$tgl =  substr($dateForm,6,2);
	$formDate =  $tgl." ".$CPublic->detilBulanNamaAngka($bln, "ind")." ".$thn;
	
	$dateTo = $CSpj->detilForm($formId, "dateto");
	$thnTo =  substr($dateTo,0,4);
	$blnTo =  substr($dateTo,4,2);
	$tglTo =  substr($dateTo,6,2);
	$toDate =  $tglTo." ".$CPublic->detilBulanNamaAngka($blnTo, "ind")." ".$thnTo;
	
	$dateInKrywn = explode("/",$CSpj->$classReport($reportIdPost, "updusrdtrpt"));
	$yearInKrywn = substr($dateInKrywn[1],0,4);
	$monthInKrywn = substr($dateInKrywn[1],4,2);
	$dayInKrywn = substr($dateInKrywn[1],6,2);
	$dateInCmpltNya = $dayInKrywn."-".$monthInKrywn."-".$yearInKrywn." ".$dateInKrywn[2];
	
	$dateInKadiv = explode("/",$CSpj->$classReport($reportIdPost, "updusrdtrptack"));
	$yearInKadiv = substr($dateInKadiv[1],0,4);
	$monthInKadiv = substr($dateInKadiv[1],4,2);
	$dayInKadiv = substr($dateInKadiv[1],6,2);
	$dateInKadivCmpltNya = $dayInKadiv."-".$monthInKadiv."-".$yearInKadiv." ".$dateInKadiv[2];
	
	$dateInHrGa = explode("/",$CSpj->$classReport($reportIdPost, "updusrdtcheck"));
	$yearInHrGa = substr($dateInHrGa[1],0,4);
	$monthInHrGa = substr($dateInHrGa[1],4,2);
	$dayInHrGa = substr($dateInHrGa[1],6,2);
	$dateInHrGaCmpltNya = $dayInHrGa."-".$monthInHrGa."-".$yearInHrGa." ".$dateInHrGa[2];
	
	$kdDiv = $CEmployee->detilTblEmpGen($CSpj->detilForm($formId, "ownerempno"), "kddiv");
	$nmDiv = $CEmployee->detilDiv($kdDiv, "nmdiv");
	
	$kdDept = $CEmployee->detilTblEmpGen($CSpj->detilForm($formId, "ownerempno"), "kddept");
	$nmDept = $CEmployee->detilDept($kdDept, "nmdept");

	$comp = $CSpj->detilForm($CSpj->$classReport($reportIdPost, "formid"), "kdcmp");
	$echoComp = $CSpj->detilCmp($comp, "nmcmp");
	$logoKanan = $CSpj->detilCmp($comp, "logokanan");
	$dispLogoKanan = "";
	if($logoKanan != "")
	{
		$dispLogoKanan = '<img src="company/'.$CSpj->detilCmp($comp, "logokanan").'" height="48px"/>';
	}
	
	$reviseLogo = '';
	if($halPost == "reportRevise")
	{
		$reviseLogo = '<div style="position:absolute;right=0;border:#333 2px solid;font-size:22px;">&nbsp;REVISED&nbsp;</div>';
	}
	
	$html = "";
	$isiReport = "";

	$statusReport = $CSpj->$classReport($reportIdPost, "status");
	if(strtoupper($statusReport) == "DRAFT")
	{
		$dispLogoKanan = '<div style="position:absolute;right=0;border:#FF0000 2px solid;font-size:22px;color:#FF0000;font-weight:bold;">&nbsp;DRAFT&nbsp;</div>';
	}
	
	//header
	$html.= '<tr> 
				<td>
				<table class="fontMyFolderList" cellpadding="0" cellspacing="0" width="100%">
                    <tr height="30px">
                    	<td width="15%" align="center" rowspan="2"><img src="company/'.$CSpj->detilCmp($comp, "logokiri").'" height="48px"/></td>
                        <td width="70%" align="Center" style="font-size:22px"><b>'.$echoComp.'</b></td>
                    	<td width="15%" rowspan="2">
							'.$dispLogoKanan.'</td>
							'.$reviseLogo.'
						</td>
                    </tr>
                    <tr height="20px" align="Center"><td><b>FORM PERTANGGUNG JAWABAN PERJALANAN DINAS</b></td></tr>
				</table>
				</td>
			</tr>
			<tr><td height="15"></td></tr>';
	//tabel detil
	$html.= '<tr>
				<td align="center">
				<table class="fontMyFolderList" cellpadding="0" cellspacing="3" width="100%">
				<tr height="17px" valign="top">
					<td width="13%" align="left" style="font-weight:bold;">
						Nama Karyawan
					</td>
					<td width="49%" align="left">
						'.ucwords(strtolower($CSpj->detilForm($formId, "ownername"))).'
					</td>
					<td width="12%" align="left" style="font-weight:bold;">
						Tujuan
					</td>
					<td width="26%" align="left">
						'.$CSpj->detilForm($formId, "destination").'
					</td>
				</tr>
				
				<tr height="17px" valign="top">
					<td align="left" style="font-weight:bold;">
						Jabatan / Golongan</td>
					<td align="left">
						'.ucwords(strtolower($CEmployee->detilJabatan($CEmployee->detilTblEmpGen($CSpj->detilForm($formId, "ownerempno"), "kdjabatan"), "nmjabatan")))." / ".ucwords(strtolower($CEmployee->detilPangkat($CEmployee->detilTblEmpGen($CSpj->detilForm($formId, "ownerempno"), "kdpangkat"), "nmpangkat"))).'
					</td>
					<td align="left" style="font-weight:bold;">
						Tanggal Berangkat
					</td>
					<td align="left">
						'.ucwords(strtolower($formDate)).'
					</td>
				</tr>
				
				<tr height="17px" valign="top">
					<td align="left" style="font-weight:bold;">Divisi / Departemen</td>
					<td align="left">
						'.$nmDiv." / ".$nmDept.'
					</td>
					<td align="left" style="font-weight:bold;">
						Tanggal Kembali
					</td>
					<td align="left">
						'.ucwords(strtolower($toDate)).'
					</td>
				</tr>
				
				<tr height="17px" valign="top">
					<td align="left" style="font-weight:bold;">Nomor SPJ</td>
					<td align="left">
						'.$CSpj->detilForm($formId, "spjno").'
					</td>
					<td align="left" style="font-weight:bold;">
						Kendaraan
					</td align="left">
					<td align="left">
						'.$CSpj->detilForm($formId, "vehicle").'
					</td>
				</tr>
			
				<tr><td colspan="4" height="2"></td></tr>
				</table>
				</td>
			</tr>
			<tr><td height="5"></td></tr>';
	
	$jml = $CKoneksi->mysqlNRows($CKoneksi->mysqlQuery("SELECT * FROM ".$dbTbl." WHERE ".$reportIdField." = ".$reportIdPost." AND deletests = 0;"));
	$limit = "15"; // LIMIT DARI JUMLAH DETAIL PER SATU HALAMAN
	$maxPage = ceil($jml/$limit);

	$tunjCurrIdr = "";
	$tunjCurrUsd = "";
	$tunjCurr1 = "";
	$tunjCurr2 = "";
	$echoTtlTunjangan = "0";
	$echoTtlTunjanganUsd = "0";
	$echoTtlTunjanganCurr1 = "0";
	$echoTtlTunjanganCurr2 = "0";

	$transCurrIdr = "";
	$transCurrUsd = "";
	$transCurr1 = "";
	$transCurr2 = "";
	$echoTtlTransport = 0;
	$echoTtlTransportUsd = 0;
	$echoTtlTransportCurr1 = 0;
	$echoTtlTransportCurr2 = 0;

	$akomCurrIdr = "";
	$akomCurrUsd = "";
	$akomCurr1 = "";
	$akomCurr2 = "";
	$echoTtlAkomodasi = "0";
	$echoTtlAkomodasiUsd = "0";
	$echoTtlAkomodasiCurr1 = "0";
	$echoTtlAkomodasiCurr2 = "0";

	$konsumCurrIdr = "";
	$konsumCurrUsd = "";
	$konsumCurr1 = "";
	$konsumCurr2 = "";
	$echoTtlKonsumsi = "0";
	$echoTtlKonsumsiUsd = "0";
	$echoTtlKonsumsiCurr1 = "0";
	$echoTtlKonsumsiCurr2 = "0";

	$lainNyaCurrIdr = "";
	$lainNyaCurrUsd = "";
	$lainNyaCurr1 = "";
	$lainNyaCurr2 = "";
	$echoTtlLainNya = "0";
	$echoTtlLainNyaUsd = "0";
	$echoTtlLainNyaCurr1 = "0";
	$echoTtlLainNyaCurr2 = "0";


	for($a=1; $a<=$maxPage; $a++)
	{	
		if($a == 1) // JIKA HALAMAN 1 MAKA NOMOR MULAI DARI 1 (NOMOR HALAMAN)
		{	
			$persenheight = "bottom:2%;";
			
			$pagebreak = "";
			$offset = 0;
			$no = $offset + 1;
		}
		else // JIKA SELAIN HALAMAN SATU MAKA BERTAMBAH SESUAI JUMLAH HALAMAN DAN URUTAN
		{
			$bottom = (($a-1) * 100)-2;
			$persenheight = "bottom:-".$bottom."%;";
			
			$pagebreak = "<tr style=\"page-break-after: left\"></tr><tr height=\"70px\"><td></td></tr>"; // UNTUK PINDAH HALAMAN BERIKUTNYA
			//$html.= "<tr style=\"page-break-after: left\"><td colspan=\"8\"></td></tr>";
			$offset = ($a-1) * $limit;
			$no = $offset+1;
		}
		
		// Customize Tabel for Other Currency
		$otherCur1 = $CSpj->$classReport($reportIdPost, "othercur1");
		$otherCur2 = $CSpj->$classReport($reportIdPost, "othercur2");	
		$other1 = $CSpj->detilCurrency($otherCur1, "currencyname");
		$other2 = $CSpj->detilCurrency($otherCur2, "currencyname");
		
		$colOther1 = 0;
		$colOther2 = 0;
		
		if($otherCur1 != 0)
		{
			$colOther1 = 1;
			$tdCur1 = '<td width="7%" align="center" class="borderTopLeftNull">'.$other1.'</td>';
		}
		if($otherCur2 != 0)
		{
			$colOther2 = 1;
			$tdCur2 = '<td width="7%" align="center" class="borderTopLeftNull"> '.$other2.' </td>';
		}
		$colspanTotal = 2 + $colOther1 + $colOther2 ;
		
		$html.= $pagebreak;
		//tabel Report
		$html.= '<tr>
				<td align="center">
					<table class="spjReportFont" style="font-size:11px;" cellpadding="0" cellspacing="0" width="99%">
						<tr style="background-color:#EFEFEF;" height="20px">
							<td width="7%" rowspan="2" align="center" class="borderAll">Tanggal</td>
							<td width="7%" rowspan="2" align="center" class="borderAll">Nama</td>
							<td width="11%" rowspan="2" align="center" class="borderLeftNull">Lokasi</td>
							<td colspan="5" align="center" class="borderLeftNull">Deskripsi Pengeluaran</td>
							<td colspan="'.$colspanTotal.'" align="center" class="borderLeftNull">Total</td>
						</tr>
						
						<tr style="background-color:#EFEFEF;">
							<td width="10%" align="center" class="borderTopLeftNull">Tunjangan<br/>Harian</td>
							<!--<td width="10%" align="center" class="borderTopLeftNull">PJP2U</td>-->
							<td width="10%" align="center" class="borderTopLeftNull">Transportasi</td>
							<td width="10%" align="center" class="borderTopLeftNull">Akomodasi</td>
							<td width="10%" align="center" class="borderTopLeftNull">Konsumsi</td>
							<td width="10%" align="center" class="borderTopLeftNull">Lainnya</td>
							<td width="7%" align="center" class="borderTopLeftNull">IDR</td>
							<td width="6%" align="center" class="borderTopLeftNull">USD</td>
							'.$tdCur1.$tdCur2.'
						</tr>';			
		$i = 1;
		$tglSeb;
		$urutanNext;
		$sama;
		
		$sql = $CKoneksiSpj->mysqlQuery("SELECT * FROM ".$dbTbl." WHERE ".$reportIdField." = ".$reportIdPost." AND deletests = 0 ORDER BY tgldetil ASC,lokasi ASC, urutan ASC LIMIT ".$offset.", ".$limit.";");
		while($rows = $CKoneksiSpj->mysqlFetch($sql))
		{
			$date = $rows['tgldetil'];
			$tglRows = substr($date,6,2);
			$blnRows = substr($date,4,2);
			$thnRows = substr($date,2,2);
			$rowsDate = $tglRows."-".ucwords(strtolower($CPublic->bulanSetengah($blnRows, "ind")))."-".$thnRows;
			$usrFollow = $CSpj->detilLoginSpj($rows['pengikut'],"userfullnm", $db);
			
			//tunjangan
			$tunj = "&nbsp;";
			$curTunj = "&nbsp;";
			$costTunj = "&nbsp;";
			$ketTunj = "&nbsp;";
			if($rows['curtunj'] != "")
			{
				$curTunj = $CSpj->detilCurrency($rows['curtunj'], "currency")." ";
			}
			if($rows['costtunj'] != 0.00)
			{
				$costTunj = number_format($rows['costtunj'],2);				
			}
			if($rows['kettunj'] != "")
			{
				$br = "";
				if($rows['costtunj'] != "")
				{
					$br = "<br/>";
				}
				$ketTunj = $br.$rows['kettunj'];
			}
			$tunj = $curTunj.$costTunj.$ketTunj;

			if(trim($curTunj) == "IDR")
			{
				$tunjCurrIdr = $curTunj;
				$echoTtlTunjangan = $echoTtlTunjangan + $rows['costtunj'];
			}
			else if(trim($curTunj) == "USD")
			{
				$tunjCurrUsd = $curTunj;
				$echoTtlTunjanganUsd = $echoTtlTunjanganUsd + $rows['costtunj'];
			}else{
				if($tunjCurr1 == "")
				{
					$tunjCurr1 = $curTunj;
				}

				if($curTunj == $tunjCurr1)
				{
					$echoTtlTunjanganCurr1 = $echoTtlTunjanganCurr1 + $rows['costtunj'];
				}else{
					if($tunjCurr2 == "")
					{
						$tunjCurr2 = $curTunj;
					}

					if($curTunj == $tunjCurr2)
					{
						$echoTtlTunjanganCurr2 = $echoTtlTunjanganCurr2 + $rows['costtunj'];
					}
				}
			}
			
			//pjp2u
			/*$pjp = "&nbsp;";
			$curPjp = "&nbsp;";
			$costPjp = "&nbsp;";
			$ketPjp = "&nbsp;";
			if($rows['curpjp'] != "")
			{
				if($rows['curpjp'] == "idr"){	$curPjp = "Rp ";	}
				if($rows['curpjp'] == "usd"){	$curPjp = "$ ";		}
			}
			if($rows['costpjp'] != "")
			{
				$costPjp = number_format($rows['costpjp']);
			}
			if($rows['ketpjp'] != "")
			{
				$br = "";
				if($rows['costpjp'] != "")
				{
					$br = "<br/>";
				}
				$ketPjp = $br.$rows['ketpjp'];
			}
			$pjp = $curPjp.$costPjp.$ketPjp;*/
			
			//transportasi
			$trans = "&nbsp;";
			$curTrans = "&nbsp;";
			$costTrans = "&nbsp;";
			$ketTrans = "&nbsp;";
			if($rows['curtrans'] != "")
			{
				$curTrans = $CSpj->detilCurrency($rows['curtrans'], "currency")." ";
			}
			if($rows['costtrans'] != 0.00)
			{
				$costTrans = number_format($rows['costtrans'],2);				
			}
			if($rows['kettrans'] != "")
			{
				$br = "";
				if($rows['costtrans'] != "")
				{
					$br = "<br/>";
				}
				$ketTrans = $br.$rows['kettrans'];
			}
			$trans = $curTrans.$costTrans.$ketTrans;

			if(trim($curTrans) == "IDR")
			{
				$transCurrIdr = $curTrans;
				$echoTtlTransport = $echoTtlTransport + $rows['costtrans'];
			}
			else if(trim($curTrans) == "USD")
			{
				$transCurrUsd = $curTrans;
				$echoTtlTransportUsd = $echoTtlTransportUsd + $rows['costtrans'];
			}else{
				if($transCurr1 == "")
				{
					$transCurr1 = $curTrans;
				}

				if($curTrans == $transCurr1)
				{
					$echoTtlTransportCurr1 = $echoTtlTransportCurr1 + $rows['costtrans'];
				}else{
					if($transCurr2 == "")
					{
						$transCurr2 = $curTrans;
					}

					if($curTrans == $transCurr2)
					{
						$echoTtlTransportCurr2 = $echoTtlTransportCurr2 + $rows['costtrans'];
					}
				}
			}
			
			//akomodasi
			$akomd = "&nbsp;";
			$curAkomd = "&nbsp;";
			$costAkomd = "&nbsp;";
			$ketAkomd = "&nbsp;";
			if($rows['curakomd'] != "")
			{
				$curAkomd = $CSpj->detilCurrency($rows['curakomd'], "currency")." ";
			}
			if($rows['costakomd'] != 0.00)
			{
				$costAkomd = number_format($rows['costakomd'],2);				
			}
			if($rows['ketakomd'] != "")
			{
				$br = "";
				if($rows['costakomd'] != "")
				{
					$br = "<br/>";
				}
				$ketAkomd = $br.$rows['ketakomd'];
			}
			$akomd = $curAkomd.$costAkomd.$ketAkomd;

			if(trim($curAkomd) == "IDR")
			{
				$akomCurrIdr = $curAkomd;
				$echoTtlAkomodasi = $echoTtlAkomodasi + $rows['costakomd'];
			}
			else if(trim($curAkomd) == "USD")
			{
				$akomCurrUsd = $curAkomd;
				$echoTtlAkomodasiUsd = $echoTtlAkomodasiUsd + $rows['costakomd'];
			}else{
				if($akomCurr1 == "")
				{
					$akomCurr1 = $curAkomd;
				}

				if($curAkomd == $akomCurr1)
				{
					$echoTtlAkomodasiCurr1 = $echoTtlAkomodasiCurr1 + $rows['costakomd'];
				}else{
					if($akomCurr2 == "")
					{
						$akomCurr2 = $curAkomd;
					}

					if($curAkomd == $akomCurr2)
					{
						$echoTtlAkomodasiCurr2 = $echoTtlAkomodasiCurr2 + $rows['costakomd'];
					}
				}
			}
			
			//konsumsi
			$konsm = "&nbsp;";
			$curKonsm = "&nbsp;";
			$costKonsm = "&nbsp;";
			$ketKonsm = "&nbsp;";
			if($rows['curkonsm'] != "")
			{
				$curKonsm = $CSpj->detilCurrency($rows['curkonsm'], "currency")." ";
			}
			if($rows['costkonsm'] != 0.00)
			{
				$costKonsm = number_format($rows['costkonsm'],2);				
			}
			if($rows['ketkonsm'] != "")
			{
				$br = "";
				if($rows['costkonsm'] != "")
				{
					$br = "<br/>";
				}
				$ketKonsm = $br.$rows['ketkonsm'];
			}
			$konsm = $curKonsm.$costKonsm.$ketKonsm;

			if(trim($curKonsm) == "IDR")
			{
				$konsumCurrIdr = $curKonsm;
				$echoTtlKonsumsi = $echoTtlKonsumsi + $rows['costkonsm'];
			}
			else if(trim($curKonsm) == "USD")
			{
				$konsumCurrUsd = $curKonsm;
				$echoTtlKonsumsiUsd = $echoTtlKonsumsiUsd + $rows['costkonsm'];
			}else{
				if($konsumCurr1 == "")
				{
					$konsumCurr1 = $curKonsm;
				}

				if($curKonsm == $konsumCurr1)
				{
					$echoTtlKonsumsiCurr1 = $echoTtlKonsumsiCurr1 + $rows['costkonsm'];
				}else{
					if($konsumCurr2 == "")
					{
						$konsumCurr2 = $curKonsm;
					}

					if($curKonsm == $konsumCurr2)
					{
						$echoTtlKonsumsiCurr2 = $echoTtlKonsumsiCurr2 + $rows['costkonsm'];
					}
				}
			}
			
			//lainnya
			$lain = "&nbsp;";
			$curLain = "&nbsp;";
			$costLain = "&nbsp;";
			$ketLain = "&nbsp;";
			if($rows['curlain']!= "")
			{
				$curLain = $CSpj->detilCurrency($rows['curlain'], "currency")." ";
			}
			if($rows['costlain'] != 0.00)
			{
				$costLain = number_format($rows['costlain'],2);				
			}
			if($rows['ketlain'] != "")
			{
				$br = "";
				if($rows['costlain'] != "")
				{
					$br = "<br/>";
				}
				$ketLain = $br.$rows['ketlain'];
			}
			$lain = $curLain.$costLain.$ketLain;

			if(trim($curLain) == "IDR")
			{
				$lainNyaCurrIdr = $curLain;
				$echoTtlLainNya = $echoTtlLainNya + $rows['costlain'];
			}
			else if(trim($curLain) == "USD")
			{
				$lainNyaCurrUsd = $curLain;
				$echoTtlLainNyaUsd = $echoTtlLainNyaUsd + $rows['costlain'];
			}else{
				if($lainNyaCurr1 == "")
				{
					$lainNyaCurr1 = $curLain;
				}

				if($curLain == $lainNyaCurr1)
				{
					$echoTtlLainNyaCurr1 = $echoTtlLainNyaCurr1 + $rows['costlain'];
				}else{
					if($lainNyaCurr2 == "")
					{
						$lainNyaCurr2 = $curLain;
					}

					if($curLain == $lainNyaCurr2)
					{
						$echoTtlLainNyaCurr2 = $echoTtlLainNyaCurr2 + $rows['costlain'];
					}
				}
			}
			
			$idrTotal = "&nbsp;";
			if($rows['idrtotal'] != "" && $rows['idrtotal'] != 0 )
			{
				$idrTotal = number_format($rows['idrtotal'],2);
			}
			$usdTotal = "&nbsp;";
			if($rows['usdtotal'] != "" && $rows['usdtotal'] != 0 )
			{
				$usdTotal = number_format($rows['usdtotal'],2);
			}
			$cur1Total = "&nbsp;";
			if($rows['othercur1total'] != "" && $rows['othercur1total'] != 0 )
			{
				$cur1Total = number_format($rows['othercur1total'],2);
			}
			$cur2Total = "&nbsp;";
			if($rows['othercur2total'] != "" && $rows['othercur2total'] != 0 )
			{
				$cur2Total = number_format($rows['othercur2total'],2);
			}
			
			$jmlTglSama = "";
			$urutanAwalTglSm = "";
			$jmlTglSama = $CSpj->$classJmlTglSama($reportIdPost, $date);
			$urutanAwalTglSm = $CSpj->$classUrutanAwalTglSm($reportIdPost, $date);
			
			$jmlLocSama = "";
			$urutanAwalLocSm = "";
			$jmlLocSama = $CSpj->$classJmlLocSama($reportIdPost, $rows['lokasi'], $date);
			$urutanAwalLocSm = $CSpj->$classUrutanAwalLocSm($reportIdPost, $rows['lokasi']);
			
			//untuk logic rowspan jika merupakan sambungan dari halaman sebelumnya
			if($tglSeb == $date)
			{
				$urutanNext++;
			}
			if($tglSeb != $date)
			{
				$urutanNext = 1;
			}
			$tglSeb = $date;
			// ====================================================================
			
			//customize other currency
			if($otherCur1 != 0)
			{
				$tdCur1Db = '<td align="center" class="borderTopLeftNull wrapword">'.$cur1Total.'</td>';
			}
			if($otherCur2 != 0)
			{
				$tdCur2Db = '<td align="center" class="borderTopLeftNull wrapword">'.$cur2Total.'</td>';
			}
			//==============
	
		$html.= '<tr id="tr'.$i.'" height="20" style="font-size:10px;cursor:pointer;">';

//Pengaturan Rowspan TANGGAL ====================================================================================================		
			if($jmlTglSama == 1)
			{
				$html.= '<td align="center" class="borderTopNull wrapword">'.$rowsDate.'</td>';
			}
			if($jmlTglSama > 1)
			{
				if($urutanAwalTglSm == $rows['urutan'])//rowspan awal
				{
					$rowspan = $jmlTglSama;
					$sisaRow = $limit - $i + 1;
					if($sisaRow < $jmlTglSama)//jika jumlah rowspan melebihi sisa row yg tersedia, maka dilakukan pengurangan
					{
						$rowspan = $sisaRow;
					}
					$html.= '<td valign="top" align="center" rowspan="'.$rowspan.'" class="borderTopNull wrapword" style="padding-top:5px;">'.$rowsDate.'</td>';
					//echo $sisaRow." ".$jmlTglSama." - ";
				}
				if($urutanAwalTglSm != $rows['urutan'] && $i == 1 && $a!=1)//rowspan lanjutan dari halaman sebelumnya
				{
					$sisaRow = $jmlTglSama - $urutanNext + 1;//hitung rowspan yg dibutuhkan. total rowspan - rowspan yg sudah dipakai di halaman sebelumnya
					$html.= '<td valign="top" align="center" rowspan="'.$sisaRow.'" class="borderTopNull wrapword" style="padding-top:5px;">'.$rowsDate.'</td>';
				}
				//echo "a".$rowspan." b".$sisaRow;
			}
// ==============================================================================================================================
			$html .= '<td align="center" class="borderTopLeftNull wrapword">'.$usrFollow.'</td>';
//Pengaturan Rowspan LOKASI	=====================================================================================================		
			if($jmlLocSama == 1)
			{
				$html.= '<td align="center" class="borderTopLeftNull wrapword">'.$rows['lokasi'].'</td>';
			}
			if($jmlLocSama > 1)
			{
				/*if($jmlTglSama == $jmlLocSama)
				{*/
					// $html.= '<td valign="top" align="center" class="borderTopLeftNull wrapword" style="padding-top:5px;"></td>';
					if($urutanAwalLocSm == $rows['urutan'])
					{
						$rowspan = $jmlLocSama;
						$sisaRow = $limit - $i + 1;
						if($sisaRow < $jmlLocSama)//jika jumlah rowspan melebihi sisa row yg tersedia, maka dilakukan pengurangan
						{
							$rowspan = $sisaRow;
						}
						$html.= '<td valign="top" align="center" rowspan="'.$rowspan.'" class="borderTopLeftNull wrapword" style="padding-top:5px;">'.$rows['lokasi'].'</td>';
					}
					if($urutanAwalLocSm != $rows['urutan'] && $i == 1 && $a == 1)//rowspan lanjutan dari halaman sebelumnya
					{
						$sisaRow = $jmlLocSama - $urutanNext + 1;//hitung rowspan yg dibutuhkan. total rowspan - rowspan yg sudah dipakai di halaman sebelumnya
						$html.= '<td valign="top" align="center" rowspan="'.$sisaRow.'" class="borderTopLeftNull wrapword" style="padding-top:5px;">'.$rows['lokasi'].'</td>';
					}
				/*}
				if($jmlTglSama != $jmlLocSama)
				{
					if($urutanAwalLocSm == $rows['urutan'])
					{
						$rowspan = $jmlLocSama;
						$sisaRow = $limit - $i + 1;
						if($sisaRow < $jmlLocSama)//jika jumlah rowspan melebihi sisa row yg tersedia, maka dilakukan pengurangan
						{
							$rowspan = $sisaRow;
						}
						$html.= '<td valign="top" align="center" rowspan="'.$rowspan.'" class="borderTopLeftNull wrapword" style="padding-top:5px;">'.$rows['lokasi'].'</td>';
					}
					if($urutanAwalLocSm != $rows['urutan'] && $i == 1 && $a != 1)//rowspan lanjutan dari halaman sebelumnya
					{
						$sisaRow = $jmlLocSama - $urutanNext + 1;//hitung rowspan yg dibutuhkan. total rowspan - rowspan yg sudah dipakai di halaman sebelumnya
						$html.= '<td valign="top" align="center" rowspan="'.$sisaRow.'" class="borderTopLeftNull wrapword" style="padding-top:5px;">'.$rows['lokasi'].'</td>';
					}
				}*/
			}
// ==============================================================================================================================
		$html.= '	<td align="center" class="borderTopLeftNull wrapword">'.$tunj.'</td>
					<!--<td width="10%" align="center" class="borderTopLeftNull wrapword">'.$pjp.'</td>-->
					<td align="center" class="borderTopLeftNull wrapword">'.$trans.'</td>
					<td align="center" class="borderTopLeftNull wrapword">'.$akomd.'</td>
					<td align="center" class="borderTopLeftNull wrapword">'.$konsm.'</td>
					<td align="center" class="borderTopLeftNull wrapword">'.$lain.'</td>
					<td align="center" class="borderTopLeftNull wrapword">'.$idrTotal.'</td>
					<td align="center" class="borderTopLeftNull wrapword">'.$usdTotal.'</td>
					'.$tdCur1Db.$tdCur2Db.'
				</tr>
				';
			$i++;
		}
		// print_r($html);exit;
		// exit;
		if($a == $maxPage)
		{
			$echoIdrTotal = "&nbsp;";
			if($CSpj->$classReport($reportIdPost, "idrgrandtotal") != "" && $CSpj->$classReport($reportIdPost, "idrgrandtotal") != 0 )
			{
				$echoIdrTotal = number_format($CSpj->$classReport($reportIdPost, "idrgrandtotal"),2);
			}
			$echoUsdTotal = "&nbsp;";
			if($CSpj->$classReport($reportIdPost, "usdgrandtotal") != "" && $CSpj->$classReport($reportIdPost, "usdgrandtotal") != 0)
			{
				$echoUsdTotal = number_format($CSpj->$classReport($reportIdPost, "usdgrandtotal"),2);
			}
			$echoIdrDp = "";
			if($CSpj->$classReport($reportIdPost, "idrdp") != "")
			{
				$echoIdrDp = number_format($CSpj->$classReport($reportIdPost, "idrdp"),2);
			}
			$echoUsdDp = "";
			if($CSpj->$classReport($reportIdPost, "usddp") != "" )
			{
				$echoUsdDp = number_format($CSpj->$classReport($reportIdPost, "usddp"),2);
			}
			
			$echoIdrKembali = "";
			if($CSpj->$classReport($reportIdPost, "idrtotalkembali") != "")
			{
				$echoIdrKembali = number_format($CSpj->$classReport($reportIdPost, "idrtotalkembali"),2);
			}
			$echoUsdKembali = "";
			if($CSpj->$classReport($reportIdPost, "usdtotalkembali") != "" )
			{
				$echoUsdKembali = number_format($CSpj->$classReport($reportIdPost, "usdtotalkembali"),2);
			}
			$echoOtherCur1Total = "";
			if($CSpj->$classReport($reportIdPost, "othercur1grandtotal") != "" && $CSpj->$classReport($reportIdPost, "othercur1grandtotal") != 0)
			{
				$echoOtherCur1Total = number_format($CSpj->$classReport($reportIdPost, "othercur1grandtotal"),2);
			}
			$echoOtherCur2Total = "";
			if($CSpj->$classReport($reportIdPost, "othercur2grandtotal") != "" && $CSpj->$classReport($reportIdPost, "othercur2grandtotal") != 0)
			{
				$echoOtherCur2Total = number_format($CSpj->$classReport($reportIdPost, "othercur2grandtotal"),2);
			}
			
			// Customize Total Other Currency
			if($otherCur1 != 0)
			{
				$tdCur1Total = '<td align="center" class="borderTopLeftNull" '.$style.'><b>'.$echoOtherCur1Total.'</b></td>';
				$tdCur1TotalAkhir = '<td align="center" '.$style.'><b>'.$echoOtherCur1Total.'</b></td>';
				$tdEmptyLineBottom1 = '<td align="center" class="borderBottomJust"></td>';
			}
			if($otherCur2 != 0)
			{
				$tdCur2Total = '<td align="center" class="borderTopLeftNull" '.$style.'><b>'.$echoOtherCur2Total.'</b></td>';
				$tdCur2TotalAkhir = '<td align="center" '.$style.'><b>'.$echoOtherCur2Total.'</b></td>';
				$tdEmptyLineBottom2 = '<td align="center" class="borderBottomJust"></td>';
			}
			// ===============================

			$tunjNya = "&nbsp;";
			if( $echoTtlTunjangan > 0 ){ $tunjNya = $tunjCurrIdr." ".number_format($echoTtlTunjangan,2); }
			if( $echoTtlTunjanganUsd > 0 )
			{
				if($tunjNya == "&nbsp;")
				{
					$tunjNya = $tunjCurrUsd." ".number_format($echoTtlTunjanganUsd,2);
				}else{
					$tunjNya .= "<br>".$tunjCurrUsd." ".number_format($echoTtlTunjanganUsd,2);
				}
			}
			if( $echoTtlTunjanganCurr1 > 0 )
			{
				if($tunjNya == "&nbsp;")
				{
					$tunjNya = $tunjCurr1." ".number_format($echoTtlTunjanganCurr1,2);
				}else{
					$tunjNya .= "<br>".$tunjCurr1." ".number_format($echoTtlTunjanganCurr1,2);
				}
			}
			if( $echoTtlTunjanganCurr2 > 0 )
			{
				if($tunjNya == "&nbsp;")
				{
					$tunjNya = $tunjCurr2." ".number_format($echoTtlTunjanganCurr2,2);
				}else{
					$tunjNya .= "<br>".$tunjCurr2." ".number_format($echoTtlTunjanganCurr2,2);
				}
			}

			$transNya = "";
			if( $echoTtlTransport > 0 ){ $transNya = $transCurrIdr." ".number_format($echoTtlTransport,2); }
			if( $echoTtlTransportUsd > 0 )
			{
				if($transNya == "")
				{
					$transNya = $transCurrUsd." ".number_format($echoTtlTransportUsd,2);
				}else{
					$transNya .= "<br>".$transCurrUsd." ".number_format($echoTtlTransportUsd,2);
				}
			}
			if( $echoTtlTransportCurr1 > 0 )
			{
				if($transNya == "")
				{
					$transNya = $transCurr1." ".number_format($echoTtlTransportCurr1,2);
				}else{
					$transNya .= "<br>".$transCurr1." ".number_format($echoTtlTransportCurr1,2);
				}
			}
			if( $echoTtlTransportCurr2 > 0 )
			{
				if($transNya == "")
				{
					$transNya = $transCurr2." ".number_format($echoTtlTransportCurr2,2);
				}else{
					$transNya .= "<br>".$transCurr2." ".number_format($echoTtlTransportCurr2,2);
				}
			}

			$akomNya = "&nbsp;";
			if( $echoTtlAkomodasi > 0 ){ $akomNya = $akomCurrIdr." ".number_format($echoTtlAkomodasi,2); }
			if( $echoTtlAkomodasiUsd > 0 )
			{
				if($akomNya == "&nbsp;")
				{
					$akomNya = $akomCurrUsd." ".number_format($echoTtlAkomodasiUsd,2);
				}else{
					$akomNya .= "<br>".$akomCurrUsd." ".number_format($echoTtlAkomodasiUsd,2);
				}
			}
			if( $echoTtlAkomodasiCurr1 > 0 )
			{
				if($akomNya == "&nbsp;")
				{
					$akomNya = $akomCurr1." ".number_format($echoTtlAkomodasiCurr1,2);
				}else{
					$akomNya .= "<br>".$akomCurr1." ".number_format($echoTtlAkomodasiCurr1,2);
				}
			}
			if( $echoTtlAkomodasiCurr2 > 0 )
			{
				if($akomNya == "&nbsp;")
				{
					$akomNya = $akomCurr2." ".number_format($echoTtlAkomodasiCurr2,2);
				}else{
					$akomNya .= "<br>".$akomCurr2." ".number_format($echoTtlAkomodasiCurr2,2);
				}
			}

			$konsNya = "&nbsp;";
			if( $echoTtlKonsumsi > 0 ){ $konsNya = $konsumCurrIdr." ".number_format($echoTtlKonsumsi,2); }
			if( $echoTtlKonsumsiUsd > 0 )
			{
				if($konsNya == "&nbsp;")
				{
					$konsNya = $konsumCurrUsd." ".number_format($echoTtlKonsumsiUsd,2);
				}else{
					$konsNya .= "<br>".$konsumCurrUsd." ".number_format($echoTtlKonsumsiUsd,2);
				}
			}
			if( $echoTtlKonsumsiCurr1 > 0 )
			{
				if($konsNya == "&nbsp;")
				{
					$konsNya = $konsumCurr1." ".number_format($echoTtlKonsumsiCurr1,2);
				}else{
					$konsNya .= "<br>".$konsumCurr1." ".number_format($echoTtlKonsumsiCurr1,2);
				}
			}
			if( $echoTtlKonsumsiCurr2 > 0 )
			{ 
				if($konsNya == "&nbsp;")
				{
					$konsNya = $konsumCurr2." ".number_format($echoTtlKonsumsiCurr2,2);
				}else{
					$konsNya .= "<br>".$konsumCurr2." ".number_format($echoTtlKonsumsiCurr2,2);
				}
			}

			$ttlLainNya = "&nbsp;";
			if( $echoTtlLainNya > 0 ){ $ttlLainNya = $lainNyaCurrIdr." ".number_format($echoTtlLainNya,2); }
			if( $echoTtlLainNyaUsd > 0 )
			{
				if($ttlLainNya == "&nbsp;")
				{
					$ttlLainNya = $lainNyaCurrUsd." ".number_format($echoTtlLainNyaUsd,2);
				}else{
					$ttlLainNya .= "<br>".$lainNyaCurrUsd." ".number_format($echoTtlLainNyaUsd,2);
				}
			}
			if( $echoTtlLainNyaCurr1 > 0 )
			{
				if($ttlLainNya == "&nbsp;")
				{
					$ttlLainNya = $lainNyaCurr1." ".number_format($echoTtlLainNyaCurr1,2);
				}else{
					$ttlLainNya .= "<br>".$lainNyaCurr1." ".number_format($echoTtlLainNyaCurr1,2);
				}
			}
			if( $echoTtlLainNyaCurr2 > 0 )
			{ 
				if($ttlLainNya == "&nbsp;")
				{
					$ttlLainNya = $lainNyaCurr2." ".number_format($echoTtlLainNyaCurr2,2);
				}else{
					$ttlLainNya .= "<br>".$lainNyaCurr2." ".number_format($echoTtlLainNyaCurr2,2);
				}
			}

			$echoSgdDp = "";
	        if($CSpj->detilReport($reportIdPost, "sgddp") != "" )
	        {
	            $echoSgdDp = $CSpj->detilReport($reportIdPost, "sgddp");
	        }

	        $echoTdCurr1Dp = $tdEmptyLineBottom1;
	        $echoCurr1Total = $tdEmptyLineBottom1;
	        if(strtoupper($other1) == "SGD")
	        {
	            if($echoSgdDp != "" AND $echoSgdDp > 0)
	            {
	                $echoTdCurr1Dp = "<td align=\"center\">".number_format((float)$echoSgdDp,2, '.', ',')."</td>";
	            }
	            $echoCurr1Total = "<td align=\"center\" class=\"borderBottomJust\" ".$style.">".$echoOtherCur1Total."</td>";

	            $taCurr1 = number_format((float)$CSpj->detilReport($reportIdPost, "sgddp") - $CSpj->detilReport($reportIdPost, "othercur1grandtotal"),2, '.', ',');
	            $tdCur1TotalAkhir = '<td align="center" '.$style.'><b>'.$taCurr1.'</b></td>';
	        }

	        $echoTdCurr2Dp = $tdEmptyLineBottom2;
	        $echoCurr2Total = $tdEmptyLineBottom2;
	        if(strtoupper($other2) == "SGD")
	        {
	            if($echoSgdDp != "" AND $echoSgdDp > 0)
	            {
	                $echoTdCurr2Dp = "<td align=\"center\">".number_format((float)$echoSgdDp,2, '.', ',')."</td>";
	            }
	            $echoCurr2Total = "<td align=\"center\" class=\"borderBottomJust\" ".$style.">".$echoOtherCur2Total."</td>";

	            $taCurr2 = number_format((float)$CSpj->detilReport($reportIdPost, "sgddp") - $CSpj->detilReport($reportIdPost, "othercur2grandtotal"),2, '.', ',');
	            $tdCur2TotalAkhir = '<td align="center" '.$style.'><b>'.$taCurr2.'</b></td>';
	        }
			
			$html.= '<tr style="font-size:10px;min-height:24px;">
						<td class="borderTopRightNull"colspan="2">&nbsp;</td>
						<td align="right" class="borderTopLeftNull"><b>TOTAL&nbsp;&nbsp;</b></td>
						<td align="center" class="borderTopLeftNull"><b>'.$tunjNya.'</b></td>
						<td align="center" class="borderTopLeftNull"><b>'.$transNya.'</b></td>
						<td align="center" class="borderTopLeftNull"><b>'.$akomNya.'</b></td>
						<td align="center" class="borderTopLeftNull"><b>'.$konsNya.'</b></td>
						<td align="center" class="borderTopLeftNull"><b>'.$ttlLainNya.'</b></td>
						<td align="center" class="borderTopLeftNull"><b>'.$echoIdrTotal.'</b></td>
						<td align="center" class="borderTopLeftNull"><b>'.$echoUsdTotal.'</b></td>
						'.$tdCur1Total.$tdCur2Total.'
					</tr>
					
					<tr height="22" style="font-size:10px;">
						<td colspan="6"></td>
						<td align="left" colspan="2">&nbsp;&nbsp;Uang Muka Biaya Perjalanan Dinas</td>
						<td align="center">'.$echoIdrDp.'</td>
						<td align="center">'.$echoUsdDp.'</td>
						'.$echoTdCurr1Dp.$echoTdCurr2Dp.'
					</tr>
					<tr height="22" style="font-size:10px;">
						<td colspan="6"></td>
						<td align="left" colspan="2">&nbsp;&nbsp;Dikurangi : Total Pengeluaran Perjalanan Dinas</td>
						<td align="center" class="borderBottomJust">'.$echoIdrTotal.'</td>
						<td align="center" class="borderBottomJust">'.$echoUsdTotal.'</td>
						'.$echoCurr1Total.$echoCurr2Total.'
					</tr>
					<tr height="28" style="font-size:10px;">
						<td colspan="6"></td>
						<td align="left" colspan="2">&nbsp;&nbsp;<b>Total Akhir</b></td>
						<td align="center"><b>'.$echoIdrKembali.'</b></td>
						<td align="center"><b>'.$echoUsdKembali.'</b></td>
						'.$tdCur1TotalAkhir.$tdCur2TotalAkhir.'
					</tr>';

			$signCek = "";
			$prosesBy = "";
			if($CSpj->$classReport($reportIdPost, "ackempno") != 00000)
			{
				$signCek = '<img src="picture/sign.JPG" height="25px"/>';
				$prosesBy = 'R. Sylvia Panghuriany';
			}
			$posKarName = "";
			$posProsesName = "";
			//custom print jika Form milik CEO atau bukan. Untuk Ceo tidak ada sign Kadiv
			if($ownerUserJenis == "CEO")// jika Milik CEO
			{
				$tdDiketahui = '';
				$ttdDiketahui = '';
				$tdDik2 = '<td width="2%">&nbsp;</td><td width="16%" align="center">&nbsp;</td>';
				$sign = '<td align="center">&nbsp;</td>';
				$kadivName = '';
				$ketJabatan = '';
				$ketJbtnKsg = '<td align="center">&nbsp;</td>';
				$diBuat = '<td align="center">'.ucwords(strtolower('PRADITYA NIRTARA')).'</td>';
				$diPeriksa = '<td align="center">'.ucwords(strtolower('Pande Nyoman Agus Jaya')).'</td>';
				$posKarName = "";
				$posProsesName = "Kadiv HR & Support";
			}
			if($ownerUserJenis != "CEO")// jika milik bukan CEO
			{
				$posKarName = "Karyawan";
				$posProsesName = "HRGA Dept";
				$tdDiketahui = '<td align="center" width="16%">Disetujui Oleh,</td>';
				$ttdDiketahui = '<td align="center">'.$signCek.'</td>';
				$tdDik2 = '';
				$sign = '<td align="center"><img src="picture/sign.JPG" height="25px"/></td>';
				$kadivName = '<td align="center">'.ucwords(strtolower($CSpj->detilLoginSpjByEmpno($CSpj->$classReport($reportIdPost, "periksaempno"), "userfullnm", $db))).'</td>';
				$nameKosong = '';
				$ackEmpNoNya = $CSpj->$classReport($reportIdPost, "ackempno");
				if($ackEmpNoNya == '00605' || $ackEmpNoNya == '00925')
				{
					$ketJabatan = '<td align="center" class="borderTopJust">HRGA Dept<br><span style=\'font-size:8px;color:grey;\'>'.$dateInKadivCmpltNya.'</span></td>';
				}else if($ackEmpNoNya == '00861' || $ackEmpNoNya == '00557')
				{
					$ketJabatan = '<td align="center" class="borderTopJust">COO<br><span style=\'font-size:8px;color:grey;\'>'.$dateInKadivCmpltNya.'</span></td>';
				}
				else if($ackEmpNoNya == '00425')//pak didet
				{
					$ketJabatan = '<td align="center" class="borderTopJust">CEO<br><span style=\'font-size:8px;color:grey;\'>'.$dateInKadivCmpltNya.'</span></td>';
				}else{
					$ketJabatan = '<td align="center" class="borderTopJust">Kadiv<br><span style=\'font-size:8px;color:grey;\'>'.$dateInKadivCmpltNya.'</span></td>';
				}
				$ketJbtnKsg = '';
				$diBuat = '<td align="center">'.ucwords(strtolower($CSpj->detilLoginSpj($CSpj->$classReport($reportIdPost, "ownerid"), "userfullnm", $db))).'</td>';
				$diPeriksa = '<td align="center">'.ucwords(strtolower($CSpj->detilLoginSpjByEmpno($CSpj->$classReport($reportIdPost, "ackempno"), "userfullnm", $db))).'</td>';
			}
			$html .= '<table width="100%" cellpadding="0" cellspacing="5">';
			$html .= '<tr>';
				$html .= '<td colspan="4">';
					$html.= 'Jakarta, '.$echoTglSek;
				$html .= '</td>';
			$html .= '</tr>';
			$html .= '<tr>';
				$html .= '<td align="center" width="16%">Dibuat Oleh,</td>';
				$html .= $tdDiketahui;
				$html .= '<td align="center" width="16%">Diverifikasi Oleh,</td>';
				$html .= '<td align="center" width="18%">Dibayar Oleh,</td>';
				$html .= '<td align="left" width="27%" class="borderBottomNull">&nbsp;Catatan:</td>';
			$html .= '</tr>';
			$html .= '<tr height="60px">';
				$html .= '<td align="center">'.$signCek.'</td>';
				$html .= $ttdDiketahui;
				$html .= '<td align="center">'.$signCek.'</td>';
				$html .= '<td align="center"></td>';
				$html .= '<td align="center" rowspan="3" valign="top" class="borderTopNull">
							<textarea class="fontMyFolderList" style="font-size:10px;width:250px;border:none;overflow:hidden;" readonly>'.$CSpj->$classReport($reportIdPost, "note").'</textarea>
						  </td>';
			$html .= '</tr>';
			$html .= '<tr>';
				$html .= $diBuat;
				$html .= $diPeriksa;
				$html .= $kadivName;
				$html .= '<td align="center">'.$prosesBy.'</td>';
			$html .= '</tr>';
			$html .= '<tr>';
				$html .= '<td align="center" class="borderTopJust">
							'.$posKarName.' <br><span style=\'font-size:8px;color:grey;\'>'.$dateInCmpltNya.'</span>
						  </td>';
				$html .= $ketJabatan;
				$html .= '<td align="center" class="borderTopJust">
							'.$posProsesName.' <br><span style=\'font-size:8px;color:grey;\'>'.$dateInHrGaCmpltNya.'</span>
						  </td>';
				$html .= '<td align="center" class="borderTopJust" style=\'vertical-align:top;\'>Finance & Accounting Dept</td>';
			$html .= '</tr>';
			$html .= '</table>';

		}
		$html.= '</table>
					</td>
				</tr>';
	}

	$isiReport = $html;	
	
	$tpl->Assign("isiReport", $isiReport);
	$tpl->printToScreen();
}
//-- END OF Print SPJ REPORT  ================================================================================================
?>
</HTML>