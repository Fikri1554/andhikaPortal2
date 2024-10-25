<?php
require_once("../../config.php");
ini_set('memory_limit', '768M');

$nama_dokumen = $aksiGet;
define('_MPDF_PATH','mpdf60/');
require(_MPDF_PATH . "mpdf.php");
// $mpdf = new mPDF('utf-8', 'A4-L');
$mpdf = new mPDF('','A4-L',10,'',5,5,5,5,9,9,'L');

$tNow = date('Y');
$tAfter = $tNow +1;

$vessel = str_replace('-',' ', $_GET['vessel']);

$getCompany = $CGetData->getCompanyVesselByVslCode($_SESSION["vesselCode"]);
$dataPdf = $CGetData->getDataMaintenanceForecaset();

ob_start(); 
?>
<!DOCTYPE html>
<head>
	<title>Report Cetak Maintenance Forecast</title>
</head>
<body>
	<div class="contentReport" id="contentReport" style="width:100%;min-height:500px;margin-bottom:100px; overflow:scroll;">		
		<div class="reportPDF" style="width:100%;min-height:0px;<?php echo $stDisplayCert;?>">
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td align="center">
						<label style="font-size:28px;font-weight: bold;"><?php echo $getCompany;?></label>
					</td>
				</tr>
				<tr>
					<td align="center">
						<label id="lblVesselName"></label>
					</td>
				</tr>
				<tr>
					<td align="center">
						<label> EQUIPMENT/COMPONENT MAINTENANCE FORECAST</label>
					</td>
				</tr>
				<tr>
					<td align="center">
						<label id="lblPeriode">FOR <?php echo $tNow." - ".$tAfter." (".$vessel.") "; ?></label>
					</td>
				</tr>
				<tr><td></td></tr>
			</table>
			<table cellpadding="0" cellspacing="0" width="100%" border="1" id="tblEquipName">
				<thead>
					<tr style="background-color: #D2D2D2;color: #000000;height:30px;font-size: 10pt;">
						<td rowspan="2" style="width: 5%; text-align: center;" >PMS CODE</td>
						<td rowspan="2" style="width: 7%;" align="center">COMP CODE</td>
						<td rowspan="2" style="width: 5%;" align="center">JOB CODE</td>
						<td rowspan="2" style="width: 5%;" align="center">FREQ</td>
						<td rowspan="2" style="width: 25%;" align="center">COMPONENT & JOB HEADING</td>
						<td colspan="12" align="center" id="yearNow"><?php echo $tNow; ?></td>
						<td colspan="6" align="center" id="nextYear"><?php echo $tAfter; ?></td>
					</tr>
					<tr style="background-color: #D2D2D2;color: #000000;font-size: 10pt;">
						<td style="width: 2.5%;" align="center">1</td>
						<td style="width: 2.5%;" align="center">2</td>
						<td style="width: 2.5%;" align="center">3</td>
						<td style="width: 2.5%;" align="center">4</td>
						<td style="width: 2.5%;" align="center">5</td>
						<td style="width: 2.5%;" align="center">6</td>
						<td style="width: 2.5%;" align="center">7</td>
						<td style="width: 2.5%;" align="center">8</td>
						<td style="width: 2.5%;" align="center">9</td>
						<td style="width: 2.5%;" align="center">10</td>
						<td style="width: 2.5%;" align="center">11</td>
						<td style="width: 2.5%;" align="center">12</td>
						<td style="width: 2.5%;" align="center">1</td>
						<td style="width: 2.5%;" align="center">2</td>
						<td style="width: 2.5%;" align="center">3</td>
						<td style="width: 2.5%;" align="center">4</td>
						<td style="width: 2.5%;" align="center">5</td>
						<td style="width: 2.5%;" align="center">6</td>
					</tr>
				</thead>
				<tbody style="font-size: 9pt;cursor: pointer;" id="tblIdBodyMaintenanceForecast">
					<?php echo $dataPdf; ?>
				</tbody>
			</table>
			<table cellpadding="0" cellspacing="0" id="tblNote" style="margin-top: 10px;font-size: 10px;">
				<tr></tr>
				<tr>
					<td>Legend </td><td>:</td>
				</tr>
				<tr>
					<td></td><td>D - Maintenance Done</td>
				</tr>
				<tr>
					<td></td><td>* - Forcasted Maintenance</td>
				</tr>
				<tr>
					<td>Note </td><td>:</td>
				</tr>
				<tr>
					<td></td><td>PMS Code may notbe in running, squence because jobs that are not applicable to this vessel are not printed out.</td>
				</tr>
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