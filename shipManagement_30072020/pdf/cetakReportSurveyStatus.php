<?php
require_once("../../config.php");

$nama_dokumen = $aksiGet;
define('_MPDF_PATH','mpdf60/');
require(_MPDF_PATH . "mpdf.php");
$mpdf = new mPDF('utf-8', 'A4-L');
$stDisplayCert = "";
$stDisplayVessel = "";
$idCheckNya = "";
$typeData = $_GET['typeData'];
$dataPdf = "";
$dataPdfVessel = array();
if ($typeData == "c")
{
	$idCheckNya = $_GET['dc'];
	$stDisplayVessel = "display: none;";
	$dataPdf = $CGetDataSurveyStatus->getDataReportCertificate($idCheckNya);
}else{
	$sqlPrint = $_GET['pl'];
	$stDisplayCert = "display: none;";
	$dataPdfVessel = $CGetDataSurveyStatus->getDataReportVessel($sqlPrint);
	// print_r($dataPdfVessel["htmlNya"]);exit;
	// $dataPdfVessel = $dataPdfVessel["htmlNya"];
}

ob_start(); 
?>
<!DOCTYPE html>
<head>
	<title>Report Cetak Survey Status</title>
</head>
<body>
	<div class="contentReport" id="contentReport" style="width:100%;min-height:500px;margin-bottom:100px; overflow:scroll;">		
		<div class="reportPDF" style="width:100%;min-height:0px;<?php echo $stDisplayCert;?>">
			<table width="100%">
				<tr>
					<td width="18%"><img src="picture/andhika.gif" style="width:12%;float: left;padding-left: 50px;"></td>
					<td align="left" style="font-size: 24px;padding-left: 190px; padding-top: -20px;">
						<label><b>PT. ANDHIKA LINES</b></label><br>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center" style="border-bottom: double;padding-bottom:40px;font-size: 11px; padding-top: -60px;">
						<label> Menara Kadin Indonesia 20th Floor</label><br>
						<label>Jl. HR. Rasuna Said Blok X-5 Kav. 2&3, Kuningan, Jakarta 12950</label>
						<label></label>
					</td>
				</tr>
			</table>
			<table cellpadding="0" cellspacing="0" width="100%" border="1" id="tblDataCert" style="margin-top:1px;">
				<thead>
					<tr>
						<td style="width: 150px;" align="center" rowspan="2" colspan="2">Statutory Certificates</td>
						<td style="width: 110px;" align="center" colspan="2">Mv.A Arsanti</td>
						<td style="width: 110px;" align="center" colspan="2">Mt. John Caine</td>
						<td style="width: 110px;" align="center" colspan="2">Mv.A Kanishka</td>
						<td style="width: 110px;" align="center" colspan="2">Mv.A Kalyani</td>
						<td style="width: 110px;" align="center" colspan="2">Mt.A Larasati</td>
						<td style="width: 120px;" align="center" colspan="2">Mv.A Nareswari</td>
						<td style="width: 110px;" align="center" colspan="2">Mv.A Paramesti</td>
					</tr>
					<tr>
						<td align="center" style="width: 55px;">Issued</td><td align="center" style="width: 55px;">Expired</td>
						<td align="center" style="width: 55px;">Issued</td><td align="center" style="width: 55px;">Expired</td>
						<td align="center" style="width: 55px;">Issued</td><td align="center" style="width: 55px;">Expired</td>
						<td align="center" style="width: 55px;">Issued</td><td align="center" style="width: 55px;">Expired</td>
						<td align="center" style="width: 55px;">Issued</td><td align="center" style="width: 55px;">Expired</td>
						<td align="center" style="width: 55px;">Issued</td><td align="center" style="width: 55px;">Expired</td>
						<td align="center" style="width: 55px;">Issued</td><td align="center" style="width: 55px;">Expired</td>
					</tr>
				</thead>
				<tbody style="font-size: 12px;cursor: pointer;" id="tblIdBody1">
					<?php echo $dataPdf; ?>
				</tbody>
			</table>
		</div>
		<div class="reportPDFByVessel" style="width:100%;min-height:0px;<?php echo $stDisplayVessel;?>">
			<table width="100%">
				<tr>
					<td width="18%"><img src="picture/andhika.gif" style="width:12%;float: left;padding-left: 50px;"></td>
					<td align="left" style="font-size: 24px;padding-left: 190px; padding-top: -20px;">
						<label><b>PT. ANDHIKA LINES</b></label><br>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center" style="border-bottom: double;padding-bottom:40px;font-size: 11px; padding-top: -60px;">
						<label> Menara Kadin Indonesia 20th Floor</label><br>
						<label>Jl. HR. Rasuna Said Blok X-5 Kav. 2&3, Kuningan, Jakarta 12950</label>
						<label></label>
					</td>
				</tr>
			</table>
			<table cellpadding="0" cellspacing="0" width="100%" border="1" id="tblDataVessel" style="padding-left: 1px;">
			<thead style="background-color: #125362;color: #FFFFFF;height:30px;">
				<tr>
					<td style="width: 15%;" align="center">Vessel Name</td>
					<td style="width: 20%;" align="center">Survey Item</td>
					<td style="width: 15%;" align="center">Range Dates</td>
					<td style="width: 5%;" align="center">Day Due</td>
					<td style="width: 15%;" align="center">Department Reponsible</td>
					<td style="width: 25%;" align="center">Remarks</td>
				</tr>
			</thead>
			<tbody style="font-size: 12px;cursor: pointer;" id="tblIdBody2">
				<?php echo $dataPdfVessel["htmlNya"]; ?>
			</tbody>
		</table>
		</div>
	</div>
</body>
</html>
 
<?php
$html = ob_get_contents(); //Proses untuk mengambil hasil dari OB..
ob_end_clean();
//Here convert the encode for UTF-8, if you prefer the ISO-8859-1 just change for $mpdf->WriteHTML($html);
$mpdf->WriteHTML(utf8_encode($html));
$mpdf->Output($nama_dokumen.".pdf" ,'I');
exit;
?>