<?php
require_once("../../config.php");

$nama_dokumen='Maintenance Report';
define('_MPDF_PATH','mpdf60/');
require(_MPDF_PATH . "mpdf.php");
$mpdf = new mPDF('utf-8', 'A4');
$dataPdf = $CGetData->getDataMaintenance($aksiGet);
$vslName = explode("-",$_GET['vn']);
$vslName = $vslName[1]." ".$vslName[2];
ob_start(); 
?>
<!DOCTYPE html>
<head>
	<title>Export Maintenance Report</title>
</head>
<body>
	<div class="maintenanceReport" id="maintenanceReport" style="width:100%;min-height:500px;margin-bottom:100px; overflow:scroll;">		
		<div class="reportPDF" style="width:100%;min-height:0px;" align="center" >
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td align="center">
						<label style="font-size: 20px;font-weight: bold;">PLANNED MAINTENANCE REPORT FORM</label>
					</td>
				</tr>
				<tr>
					<td align="center">
						<label style="font-size: 20px;" id="lblVesselName"><?php echo $vslName; ?></label>
					</td>
				</tr>
				<tr>
					<td align="center" style="border-bottom: double;padding-bottom:10px;">
						<label> FORM NO :</label>
						<label id="lblJobCodeHead"><?php echo $dataPdf["jobcode"]; ?></label>
					</td>
				</tr>
			</table>
			<table align="left" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td style="float: left; padding:2px 20px 2px 20px;font-size: 12px;" colspan="2">
						<label> Please Tick the Following :</label>
					</td>
				</tr>
				<tr>
					<td style="float: left; padding:2px 20px 2px 20px;font-size: 12px;" colspan="2">
						<input type="checkbox" name="sm"> Scheduled Maintenance
					</td>
				</tr>
				<tr>
					<td style="float: left; padding:2px 20px 2px 20px;font-size: 12px;" colspan="2">
						<input type="checkbox" name="um"> Unscheduled Maintenance ___________ Due to :
						<input type="checkbox" name="bd"> Breakdown	
						<input type="checkbox" name="cw"> Create Worrk for Crew 
						<input type="checkbox" name="other"> Other ___________
					</td>
				</tr>
				<tr>
					<td style="float: left; padding:2px 20px 2px 20px;font-size: 12px;" colspan="2">
						PMS Code ___________
					</td>
				</tr>
				<tr>
					<td style="padding:2px 20px 2px 20px;font-size: 12px;">
						<label>Component Code :</label>
						<label id="lblCompCode"><?php echo $dataPdf["compcode"]; ?></label>
					</td>
					<td style="font-size: 12px;">
						<label style="padding-left: 300px;">Job Code :</label>
						<label id="lblJobCode"><?php echo $dataPdf["jobcode"]; ?></label>
					</td>
				</tr>
				<tr>
					<td style="padding:2px 20px 2px 20px;font-size: 12px;" colspan="2">
						<label>Component Name :</label>
						<label id="lblCompName"><?php echo $dataPdf["compname"]; ?></label>
					</td>
				</tr>
				<tr>
					<td align="center" style="text-align: justify; padding:10px 20px 2px 20px;" colspan="2">
						<fieldset style="padding:15px;">
							<div style="font-weight: bold;text-align: center;">JOB DESCRIPTION</div>
						</fieldset>
					</td>
				</tr>
				<tr>
					<td align="center" style="padding:10px 20px 2px 20px;font-size: 12px;" colspan="2">
						<div id="jdlJobDesc" align="center"><?php echo $dataPdf["jobhead"]; ?></div>
					</td>
				</tr>
				<tr>
					<td align="justify" style="border-bottom: double;padding-bottom:10px;padding:10px 20px 2px 20px;font-size: 12px;" colspan="2">
						<div id="valJobDesc" style=" padding-top: 10px;">
								<?php echo $dataPdf["jobdesc"]; ?></div>
					</td>
				</tr>
				<tr>
					<td style="padding:10px 20px 2px 20px;font-size: 12px;" colspan="2">
						<label>Date work carried out :</label>
						<label style="padding-left: 300px;">Work done by :</label>
					</td>
				</tr>
				<tr>
					<td style="padding:2px 20px 2px 20px;font-size: 12px;" colspan="2">
						<label>Man hours :</label>
					</td>
				</tr>
				<tr>
					<td style="padding:2px 20px 2px 20px;font-size: 12px;" colspan="2">
						<label>Sparepart used :</label>
					</td>
				</tr>
			</table>
			<table align="left" cellpadding="0" cellspacing="0" width="100%" border="1">
				<thead>
					<tr>
						<th style="width: 10%;font-size: 12px;">Part No</th>
						<th style="width: 10%;font-size: 12px;">Drawing No</th>
						<th style="width: 50%;font-size: 12px;">Part Name</th>
						<th style="width: 10%;font-size: 12px;">Working Qty</th>
						<th style="width: 10%;font-size: 12px;">Qty Used</th>
						<th style="width: 10%;font-size: 12px;">ROB</th>
					</tr>
				</thead>
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