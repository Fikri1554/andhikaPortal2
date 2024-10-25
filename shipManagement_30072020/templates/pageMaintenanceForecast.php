<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/jquery-ui.js"></script>
<script src="../js/jquery.table2excel.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#loading").hide();
		$("#idBtnDeficiency").css("display","none");
        $("#idBtnSurveyStatus").css("display","none");
		var html = $('<button id ="idBtnDeficiency" class="btnStandarKecil" type="button" onclick="formPlannMaintenance.submit();" style="width:70px;height:40px;float:left;margin-left:10px;" title="Back"><table cellpadding="0" cellspacing="0" width="100%" height="100%"><tr><td align="center"><img src="../picture/arrow-return-180-left.png" height="18"/></td></tr><tr><td align="center" style="font-size:9px;font-weight: bold;">BACK</td></tr></table></button>');
        $("#idNavHome").append(html);
        
		$("#yearNow").text((new Date()).getFullYear());
		$("#nextYear").text((new Date()).getFullYear()+1);
		$("#lblPeriode").text("FOR "+(new Date()).getFullYear()+" - "+((new Date()).getFullYear()+1));
		$("#btnExport").click(function(){
			$("#loading").show();
		 	getExcel();
		 	$("#loading").hide();
		});
	});
	function getExcel()
	{
		$("#mtncForecast").table2excel({
	        name: "Table2Excel",  
	        filename: "maintenanceForecast.xls",  
	        fileext: ".xls"  
        });  
	}
</script>
<!DOCTYPE html>
<div class="archivesContainer" style="padding-bottom:50px;margin-top:10px;">
	<div class="navBtn" style="width:100%;min-height:0px;" >
		<img src="../picture/ajax-loader20.gif" style="width: 24px;height: 24px;margin: 10px;" id="loading" class="btnStandar">
		<button class="btnStandar" id="btnExport" style="width:100px;height:29px; margin: 10px; float: right;" title="Cetak" {stBtnExport}>
        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
            	<tr>
                	<td align="right" width="25"><img src="../picture/fileXls.png" height="20"/> </td>
                    <td align="center">Export</td>
              	</tr>
         	</table>
     	</button>
	</div>
	<div class="mtncForecast" id="mtncForecast" style="width:100%;max-height:500px;overflow:scroll;">
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td align="center">
					<label style="font-size: 28px;font-weight: bold;">{getCompVsl}</label>
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
					<label id="lblPeriode"></label>
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
					<td colspan="12" align="center" id="yearNow"></td>
					<td colspan="6" align="center" id="nextYear"></td>
				</tr>
				<tr align="center" style="background-color: #D2D2D2;color: #000000;font-size: 10pt;">
					<td style="width: 2.5%;">1</td>
					<td style="width: 2.5%;">2</td>
					<td style="width: 2.5%;">3</td>
					<td style="width: 2.5%;">4</td>
					<td style="width: 2.5%;">5</td>
					<td style="width: 2.5%;">6</td>
					<td style="width: 2.5%;">7</td>
					<td style="width: 2.5%;">8</td>
					<td style="width: 2.5%;">9</td>
					<td style="width: 2.5%;">10</td>
					<td style="width: 2.5%;">11</td>
					<td style="width: 2.5%;">12</td>
					<td style="width: 2.5%;">1</td>
					<td style="width: 2.5%;">2</td>
					<td style="width: 2.5%;">3</td>
					<td style="width: 2.5%;">4</td>
					<td style="width: 2.5%;">5</td>
					<td style="width: 2.5%;">6</td>
				</tr>
			</thead>
			<tbody style="font-size: 9pt;cursor: pointer;" id="tblIdBodyMaintenanceForecast">
				{getDataMaintenanceForecast}
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


