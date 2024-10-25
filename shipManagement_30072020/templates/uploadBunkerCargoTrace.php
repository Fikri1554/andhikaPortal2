<link rel="stylesheet" href="../css/jquery-ui.css">
<script type="text/javascript" src="../js/jquery-1.6.js"></script>
<script type="text/javascript" src="../js/niceEdit.js"></script>
<script type="text/javascript" src="../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<link rel="stylesheet" type="text/css" href="../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></LINK>
<script src="../js/jquery-ui.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#loading").hide();
		//$("#idNavUpload").hide();
		$(".viewExcel").hide();
		$("#idNavUpload").hide();
		$("#idPlannMaintenance").css("display","none");
        $("#idBtnDeficiency").css("display","none");
        $("#idBtnSurveyStatus").css("display","none");

        $("#btnSearch").click(function(){
        	var voyNo = $("#txtSearchVoyageNo").val();
        	if(voyNo == "")
        	{
        		alert("Voyage No Don't empty..!!");
        		return false;
        	}

        	$.post('../shipManagement/class/actionNav.php',
	    	{ actionSearchBunkerCargoTrace : "actionSearchBunkerCargoTrace",voyNo : voyNo },
	        	function(data) 
	            {
	            	$("#tblIdBody").empty();
	            	var html = data;
					$('#tblIdBody').append(html);
	            },
	         "json"
	         );
        });
        $("#btnRefresh").click(function(){
        	location.reload();
        });
        $("#btnExport").click(function(){
        	var voyNo = "";
        	voyNo = $("#txtSearchVoyageNo").val();
			$("#actionVoyNo").val(voyNo);
			formExportBunkerCargoTrace.submit();
        });

		var html = $('<button id ="idBtnDeficiency" class="btnStandarKecil" type="button" onclick="formUploadFileExcel.submit();" style="width:70px;height:40px;float:left;margin-left:10px;" title="Back"><table cellpadding="0" cellspacing="0" width="100%" height="100%"><tr><td align="center"><img src="../picture/arrow-return-180-left.png" height="18"/></td></tr><tr><td align="center" style="font-size:9px;font-weight: bold;">BACK</td></tr></table></button>');
        $("#idNavHome").append(html);
	});
</script>
<style type="text/css">
	.ui-datepicker {
		width: 17%;
	}
</style>
<!DOCTYPE html>
<div class="archivesContainer" style="padding-bottom:20px;margin-top:10px;">
	<div class="navBtn" style="width:100%;min-height:0px;" >
		<img src="../picture/ajax-loader20.gif" style="width: 24px;height: 24px;margin: 10px;" id="loading" class="btnStandar">
		<div id="idNavUpload">
			<input type="file" name="fileOil" id="fileOil" class="btnStandar" style="width:200px;height:29px; margin: 10px;float: left;">
			<input type="text" name="txtSheet" value="" id="txtSheet" class="elementSearch" placeholder="No Sheet" style="width: 70px; margin: 10px; float: left;">
			<button class="btnStandar" id="btnView" style="width:100px;height:29px; margin: 10px; float: left;" title="View Data">
	        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
	            	<tr>
	                	<td align="right" width="25"><img src="../picture/Search-blue-32.png" height="20"/> </td>
	                    <td align="center">View</td>
	              	</tr>
	         	</table>
	     	</button>
    	</div>
    	<div id="idNavSearch">
    		<input type="text" name="txtSearchVoyageNo" id="txtSearchVoyageNo" class="elementSearch" style="width: 10%;margin: 10px;float: left;" placeholder="Voyage No">
    		<button class="btnStandar" id="btnSearch" style="width:100px;height:29px; margin: 10px; float: left;" title="View Data">
	        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
	            	<tr>
	                	<td align="right" width="25"><img src="../picture/Search-blue-32.png" height="20"/> </td>
	                    <td align="center">Search</td>
	              	</tr>
	         	</table>
	     	</button>
    	</div>
    	<div id="idExport">
    		<button class="btnStandar" id="btnRefresh" style="width:100px;height:29px; margin: 10px; float: left;" title="View Data">
	        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
	            	<tr>
	                	<td align="right" width="25"><img src="../picture/Button-Synchronize-blue-32.png" height="20"/> </td>
	                    <td align="center">Refresh</td>
	              	</tr>
	         	</table>
	     	</button>
    		<button class="btnStandar" id="btnExport" style="width:100px;height:29px; margin: 10px; float: left;" title="View Data">
	        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
	            	<tr>
	                	<td align="right" width="25"><img src="../picture/fileXls.png" height="20"/> </td>
	                    <td align="center">Export</td>
	              	</tr>
	         	</table>
	     	</button>
    	</div>
	</div>
	<div class="equipName" style="width:100%;height:480px;overflow:scroll;">
		<table cellpadding="0" cellspacing="0" width="2000" border="1" id="tblEquipName">
			<thead style="font-size: 12px;background-color: #125362;color: #FFFFFF;height:30px;">
				<tr>
					<td style="width: 30px;" align="center">NO</td>
					<td style="width: 200px;" align="center">Voyage No Discharging Port</td>
					<td style="width: 100px;" align="center">Cargo Grade</td>
					<td style="width: 100px;" align="center">B / L</td>
					<td style="width: 100px;" align="center">SF After Loading</td>
					<td style="width: 100px;" align="center">VEF SF AL/BL</td>
					<td style="width: 100px;" align="center">Different</td>
					<td style="width: 100px;" align="center">Diff in %</td>
					<td style="width: 100px;" align="center">SF Before Disch</td>
					<td style="width: 100px;" align="center">Different</td>
					<td style="width: 100px;" align="center">Diff in %</td>
					<td style="width: 100px;" align="center">Transport Loss</td>
					<td style="width: 100px;" align="center"></td>
					<td style="width: 100px;" align="center">Actual Received</td>
					<td style="width: 100px;" align="center">Diff</td>
					<td style="width: 100px;" align="center"></td>
				</tr>
			</thead>
			<tbody style="font-size: 11px;cursor: pointer;" id="tblIdBody">
				{getDataSummaryCargoTrace}
			</tbody>
		</table>
	</div>
</div>
<form name="formExportBunkerCargoTrace" method="POST" action="../shipManagement/class/actionNav.php">
	<input type="hidden" id="actionExportBunkerCargoTrace" name="actionExportBunkerCargoTrace" value="export" />
	<input type="hidden" id="actionVoyNo" name="actionVoyNo" value="" />
</form>


