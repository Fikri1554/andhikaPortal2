<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/jquery-ui.js"></script>
<!-- <script src="../js/jquery.table2excel.js"></script> -->
<script type="text/javascript">
	$(document).ready(function() {
		$("#loading").hide();
		$("#lblVesselName").text($("#namaKapal option:selected").text());
		$("#idBtnDeficiency").css("display","none");
        $("#idBtnSurveyStatus").css("display","none");
		var html = $('<button id ="idBtnDeficiency" class="btnStandarKecil" type="button" onclick="formPlannMaintenance.submit();" style="width:70px;height:40px;float:left;margin-left:10px;" title="Back"><table cellpadding="0" cellspacing="0" width="100%" height="100%"><tr><td align="center"><img src="../picture/arrow-return-180-left.png" height="18"/></td></tr><tr><td align="center" style="font-size:9px;font-weight: bold;">BACK</td></tr></table></button>');
        $("#idNavHome").append(html);

		$("#btnCetak").click(function(){
			$("#loading").show();
		 	getPrint();
		 	$("#loading").hide();
		 	window.location.reload(true);
		});

		$("#btnExport").click(function(){
			$("#loading").show();
		 	getExcel();
		 	$("#loading").hide();
		});
	});

	function getExcel()
	{
		var nmVslNya = $.trim($("#namaKapal option:selected").text());
		$("#actionVslName").val(nmVslNya);
		formExportRoutinJob.submit();
	}

	// function getExcel()
	// {
	// 	$("#routinJob").table2excel({  
	//         name: "Table2Excel",  
	//         filename: "routinJob.xls",  
	//         fileext: ".xls"  
 //        });  
	// }
	function getPrint()
	{
		var printContents = document.getElementById("routinJob").innerHTML;
	    var originalContents = document.body.innerHTML;
	    document.body.innerHTML = printContents;
	    window.print();
	    document.body.innerHTML = originalContents;
	}

</script>
<!DOCTYPE html>
<div class="archivesContainer" style="padding-bottom:50px;margin-top:10px;">
	<div class="navBtn" style="width:100%;min-height:0px;" >
		<img src="../picture/ajax-loader20.gif" style="width: 24px;height: 24px;margin: 10px;" id="loading" class="btnStandar">
		<button class="btnStandar" id="btnCetak" style="width:100px;height:29px; margin: 10px; float: left;" title="Cetak" {stBtnExport}>
        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
            	<tr>
                	<td align="right" width="25"><img src="../picture/Printer-blue-32.png" height="20"/> </td>
                    <td align="center">Cetak</td>
              	</tr>
         	</table>
     	</button>
     	<button class="btnStandar" id="btnExport" style="width:100px;height:29px; margin: 10px; float: left;" title="Export" {stBtnExport}>
        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
            	<tr>
                	<td align="right" width="25"><img src="../picture/fileXls.png" height="20"/> </td>
                    <td align="center">Export</td>
              	</tr>
         	</table>
     	</button>
	</div>
	<div class="routinJob" id="routinJob" style="width:100%;max-height:500px;overflow:scroll;">		
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
					<label> EQUIPMENT/COMPONENT MAINTENANCE (LESS THAN 1 MONTH)</label>
				</td>
			</tr>
			<tr><td></td></tr>
		</table>
		<table cellpadding="0" cellspacing="0" width="100%" border="1" id="tblEquipName">
			<thead>
				<tr style="background-color: #D2D2D2;color: #000000;height:30px;font-size: 12pt;">
					<td style="width: 10%;" align="center">PMS CODE</td>
					<td style="width: 10%;" align="center">COMP CODE</td>
					<td style="width: 10%;" align="center">JOB CODE</td>
					<td style="width: 10%;" align="center">FREQ</td>
					<td style="width: 60%;" align="center">COMPONENT & JOB HEADING</td>
				</tr>
			</thead>
			<tbody style="font-size: 9pt;cursor: pointer;" id="tblIdBodyWorkClass">
				{getDataRoutinJob}
			</tbody>
		</table>
	</div>
	<iframe id="printing-frame" name="print_frame" src="about:blank" style="display:none;"></iframe>

</div>
<form name="formExportRoutinJob" method="post" action="../shipManagement/class/actionNav.php">
	<input type="hidden" id="actionExportRoutinJob" name="actionExportRoutinJob" value="export" />
	<input type="hidden" id="actionVslName" name="actionVslName" />
</form>

