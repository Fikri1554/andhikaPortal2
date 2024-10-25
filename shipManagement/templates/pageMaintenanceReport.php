<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/jquery-ui.js"></script>
<script type="text/javascript">
	var stPage = "";
	$(document).ready(function() {
		$("#idBtnDeficiency").css("display","none");
		$("#idPlannMaintenance").css("display","none");
        $("#idBtnSurveyStatus").css("display","none");
		var html = $('<button id ="idBtnDeficiency" class="btnStandarKecil" type="button" onclick="formPlannMaintenance.submit();" style="width:70px;height:40px;float:left;margin-left:10px;" title="Back"><table cellpadding="0" cellspacing="0" width="100%" height="100%"><tr><td align="center"><img src="../picture/arrow-return-180-left.png" height="18"/></td></tr><tr><td align="center" style="font-size:9px;font-weight: bold;">BACK</td></tr></table></button>');
        $("#idNavHome").append(html);
		
		var vesselName = $( "#namaKapal option:selected" ).text();
		$("#btnFirst").attr("disabled","disabled");
		$("#btnPrev").attr("disabled","disabled");
		stPage = $("#txtStartPage").val();
		getData(vesselName,stPage,"startUp");
		$("#btnCetak").click(function(){
			$("#loading").show();
		 	getPrint();
		 	$("#loading").hide();
		 	window.location.reload(true);
		});
		$("#btnExport").click(function(){
			$("#loading").show();
		 	getExport();
		 	$("#loading").hide();
		});
		$("#btnNext").click(function(){
			stPage = $("#txtStartPage").val();
			$("#btnPrev").attr("disabled","");
			$("#btnFirst").attr("disabled","");
			getData(vesselName,stPage,"next");
		});
		$("#btnPrev").click(function(){
			stPage = $("#txtStartPage").val();
			getData(vesselName,stPage,"prev");
			if (stPage <= 2) 
			{
				stPage = 1;
				$("#btnFirst").attr("disabled","disabled");
				$(this).attr("disabled","disabled");
			}
			$("#btnNext").attr("disabled","");
			$("#btnLast").attr("disabled","");
		});
		$("#btnFirst").click(function(){
			$(this).attr("disabled","disabled");
			$("#btnPrev").attr("disabled","disabled");
			$("#btnNext").attr("disabled","");
			$("#btnLast").attr("disabled","");
			$("#txtStartPage").val("0");
			stPage = 1;
			getData(vesselName,stPage,"first");
		});
		$("#btnLast").click(function(){
			$(this).attr("disabled","disabled");
			$("#btnNext").attr("disabled","disabled");
			$("#btnPrev").attr("disabled","");
			$("#btnFirst").attr("disabled","");
			$("#txtStartPage").val($("#txtCountData").val());
			stPage = $("#txtCountData").val();
			getData(vesselName,stPage,"last");
		});
	});

	function getData(vesselName,stPageNya,actionPageNya)
	{
		$("#lblVesselName").text(vesselName);
		$("#loading").show();
		$.post('../shipManagement/class/actionNav.php',
		{ actionMaintenanceReport : "maintenanceReport",actionStPage : stPageNya,actionPage : actionPageNya},
			function(data) 
			{	
				$('#valJobDesc').empty();
				$('#valJobDesc').append(data.jobdesc);

				$("#lblCountPage").text(data.jml);
				$("#lblJobCodeHead").text(data.jobcode);
				$("#lblJobCode").text(data.jobcode);
				$("#lblCompCode").text(data.compcode);
				$("#lblCompName").text(data.compname);
				$("#jdlJobDesc").text(data.jobhead);
				$("#txtStartPage").val(data.nextPage);
				$("#txtCountData").val(data.countData);
				$("#txtSno").val(data.sNo);
				$("#loading").hide();
			},
		"json"
		);
	}
	// function getPrint()
	// {
	// 	var printContents = document.getElementById("maintenanceReport").innerHTML;
	//     var originalContents = document.body.innerHTML;
	//     document.body.innerHTML = printContents;
	//     window.print();
	//     document.body.innerHTML = originalContents;
	// }
	function getExport()
	{
		var sNo = $("#txtSno").val();
		var vn = $( "#namaKapal option:selected" ).text();
		vn = vn.replace(/\s+/g, '-');
		var url = window.location.pathname+"pdf/?aksi="+sNo+"&vn="+vn+"";
		window.open(url, '_blank');
	}

</script>
<!DOCTYPE html>
<div class="archivesContainer" style="padding-bottom:50px;margin-top:10px;">
	<div class="navBtn" style="width:100%;min-height:0px;" >
		<img src="../picture/ajax-loader20.gif" style="width: 24px;height: 24px;margin: 10px;" id="loading" class="btnStandar">
		<button class="btnStandar" id="btnFirst" style="width:40px;height:29px; margin:10px 0px 5px 10px; float: left;" title="First">
        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
            	<tr>
                	<td align="center" width="40">First</td>
              	</tr>
         	</table>
     	</button>
     	<button class="btnStandar" id="btnPrev" style="width:29px;height:29px; margin:10px 5px 5px 3px; float: left;" title="Prev">
        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
            	<tr>
                	<td align="right" width="25"><img src="../picture/control-091.PNG" height="20"/> </td>
              	</tr>
         	</table>
     	</button>
     	<input type="hidden" name="txtSno" id="txtSno" value="0">
     	<input type="hidden" name="txtStartPage" id="txtStartPage" value="0">
     	<input type="hidden" name="txtCountData" id="txtCountData" value="0">
     	<label id="lblCountPage" style="height:29px; margin:15px 0px 5px 0px; float: left;"></label>
     	<button class="btnStandar" id="btnNext" style="width:29px;height:29px; margin:10px 3px 5px 5px; float: left;" title="Next">
        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
            	<tr>
                	<td align="right" width="25"><img src="../picture/control-092.PNG" height="20"/> </td>
              	</tr>
         	</table>
     	</button>
     	<button class="btnStandar" id="btnLast" style="width:40px;height:29px; margin:10px 10px 5px 0px; float: left;" title="Last">
        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
            	<tr>
                	<td align="center" width="40">Last</td>
              	</tr>
         	</table>
     	</button>
     	<!-- <button class="btnStandar" id="btnCetak" style="width:100px;height:29px; margin: 10px; float: right;" title="Cetak">
        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
            	<tr>
                	<td align="right" width="25"><img src="../picture/Printer-blue-32.png" height="20"/> </td>
                    <td align="center">Cetak</td>
              	</tr>
         	</table>
     	</button> -->
     	<button class="btnStandar" id="btnExport" style="width:100px;height:29px; margin: 10px; float: right;" title="Export" {stBtnExport}>
        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
            	<tr>
                	<td align="right" width="25"><img src="../picture/filePdf.png" height="20"/> </td>
                    <td align="center">Export</td>
              	</tr>
         	</table>
     	</button>
	</div>
	<div class="maintenanceReport" id="maintenanceReport" style="width:100%;min-height:500px;margin-bottom:100px; overflow:scroll;">		
		<div class="txtLabel" style="width:100%;min-height:0px;" align="center" >
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td align="center">
						<label style="font-size: 20px;font-weight: bold;">PLANNED MAINTENANCE REPORT FORM</label>
					</td>
				</tr>
				<tr>
					<td align="center">
						<label style="font-size: 20px;" id="lblVesselName"></label>
					</td>
				</tr>
				<tr>
					<td align="center" style="border-bottom: solid;padding-bottom:10px;">
						<label> FORM NO :</label>
						<label id="lblJobCodeHead"></label>
					</td>
				</tr>
			</table>
			<table align="left" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td style="float: left; padding:2px 20px 2px 20px;">
						<label> Please Tick the Following :</label>
					</td>
				</tr>
				<tr>
					<td style="float: left; padding:2px 20px 2px 20px;">
						<input type="checkbox" name="sm"> Scheduled Maintenance
					</td>
				</tr>
				<tr>
					<td style="float: left; padding:2px 20px 2px 20px;">
						<input type="checkbox" name="um"> Unscheduled Maintenance ________ Due to :
						<input type="checkbox" name="bd"> Breakdown	&nbsp
						<input type="checkbox" name="cw"> Create Work for Crew &nbsp
						<input type="checkbox" name="other"> Other _______________
					</td>
				</tr>
				<tr>
					<td style="float: left; padding:2px 20px 2px 20px;">
						PMS Code ____________
					</td>
				</tr>
				<tr>
					<td style="padding:2px 20px 2px 20px;">
						<label>Component Code :</label>
						<label id="lblCompCode"></label>
						<label style="padding-left: 300px;">Job Code :</label>
						<label id="lblJobCode"></label>
					</td>
				</tr>
				<tr>
					<td style="padding:2px 20px 2px 20px;">
						<label>Component Name :</label>
						<label id="lblCompName"></label>
					</td>
				</tr>
				<tr>
					<td style="padding:2px 20px 2px 20px;border-bottom: solid;padding-bottom:10px;">
						<fieldset style="padding:5px;">
							<legend align="center" style="font-weight: bold;">JOB DESCRIPTION</legend>
							<div id="jdlJobDesc" align="center"></div>
							<div id="valJobDesc" style="text-align: justify; padding-top: 10px;"></div>
							<!-- <label id="lblJobDesc"></label> -->
						</fieldset>
					</td>
				</tr>
				<tr>
					<td style="padding:10px 20px 2px 20px;">
						<label>Date work carried out :</label>
						<label style="padding-left: 300px;">Work done by :</label>
					</td>
				</tr>
				<tr>
					<td style="padding:2px 20px 2px 20px;">
						<label>Man hours :</label>
					</td>
				</tr>
				<tr>
					<td style="padding:2px 20px 2px 20px;">
						<label>Sparepart used :</label>
					</td>
				</tr>

			</table>
			<table align="left" cellpadding="0" cellspacing="0" width="100%" border="1">
				<thead>
					<tr>
						<th style="width: 10%;">Part No</th>
						<th style="width: 10%;">Drawing No</th>
						<th style="width: 50%;">Part Name</th>
						<th style="width: 10%;">Working Qty</th>
						<th style="width: 10%;">Qty Used</th>
						<th style="width: 10%;">ROB</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>

</div>


