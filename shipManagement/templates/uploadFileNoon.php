<link rel="stylesheet" href="../css/jquery-ui.css">
<script type="text/javascript" src="../js/jquery-1.6.js"></script>
<script type="text/javascript" src="../js/niceEdit.js"></script>
<script type="text/javascript" src="../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<link rel="stylesheet" type="text/css" href="../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></LINK>
<script src="../js/jquery-ui.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#loading").hide();
		$("#idNavUpload").hide();
		$(".viewExcel").hide();
		$("#idPlannMaintenance").css("display","none");
        $("#idBtnDeficiency").css("display","none");
        $("#idBtnSurveyStatus").css("display","none");

        $( "#txtSDate" ).datepicker({
			dateFormat: 'yy-mm-dd',
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            defaultDate: new Date(),
		});
		$( "#txtEDate" ).datepicker({
			dateFormat: 'yy-mm-dd',
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            defaultDate: new Date(),
		});

        $("#btnView").click(function(){
        	$("#loading").show();
        	var sheetNya = $("#txtSheet").val();
        	var fileData = $("#fileNoon").prop('files')[0];
			var formData = new FormData();
			if(sheetNya == "")
			{
				alert("no shhet can't empty..!!");
				$("#loading").hide();
				return false;
			}
			formData.append('file', fileData);
			formData.append('actionUploadExcelNoon', "uploadExcelNoon");
			formData.append('sheetNya',sheetNya);
			$.ajax({
		        url: '../shipManagement/class/actionNav.php',
		        dataType: 'text',
		        cache: false,
		        contentType: false,
		        processData: false,
		        data: formData,
		        type: 'post',
		        success: function(dataNya){
		        	setView(dataNya); 
		        	$(".viewExcel").show(300);
		            // location.reload();
		        }
		        ,dataType:"json"
		     });
			$("#loading").hide();
        });
		$("#btnSave").click(function(){
			var insSheet = $("#txtSheet").val();
			var fileIns = $("#fileNoon").prop('files')[0];
			var insData = new FormData();
			insData.append('file', fileIns);
			insData.append('actionInsExcelNoon', "insExcelNoon");
			insData.append('sheetNya',insSheet);
			$.ajax({
		        url: '../shipManagement/class/actionNav.php',
		        dataType: 'text',
		        cache: false,
		        contentType: false,
		        processData: false,
		        data: insData,
		        type: 'post',
		        success: function(data){		            
		            alert(data);
		            location.reload();
		        }
		        ,dataType:"json"
		     });
		});
		$("#btnNavUpload").click(function(){
			$("#idNavSearch").hide(300);
			$(".equipName").hide(300);
			$("#idNavUpload").show(300);
		});
		$("#btnSearch").click(function(){
			$("#loading").show();
			$("#tblIdBody").empty();
			var sDate = $("#txtSDate").val();
			var eDate = $("#txtEDate").val();
			$.post('../shipManagement/class/actionNav.php',
	    	{ actionGetSearchUploadNoon : "searchUploadNoon",sDate : sDate,eDate : eDate},
	        	function(data) 
	            { 
	            	var html = data;
					$('#tblIdBody').append(html);
	            },
	         "json"
	         );
			$("#loading").hide();
		});
		var html = $('<button id ="idBtnDeficiency" class="btnStandarKecil" type="button" onclick="formUploadFileExcel.submit();" style="width:70px;height:40px;float:left;margin-left:10px;" title="Back"><table cellpadding="0" cellspacing="0" width="100%" height="100%"><tr><td align="center"><img src="../picture/arrow-return-180-left.png" height="18"/></td></tr><tr><td align="center" style="font-size:9px;font-weight: bold;">BACK</td></tr></table></button>');
        $("#idNavHome").append(html);
	});
	function setView(dataNya)
	{
		$("#txtNameMaster").text(dataNya.masterName);
		$("#txtSatNo").text(dataNya.satelitePhone);
		$("#txtSatEmail").text(dataNya.sateliteEmail);
		$("#txtNoonPosition").text(dataNya.noonPosition);
		$("#txtVoyageNo").text(dataNya.voyageNo);
		$("#txtVoyageDate").text(dataNya.voyageDate);
		$("#txtVoyageFrom").text(dataNya.voyageFrom);
		$("#txtVoyageTo").text(dataNya.voyageTo);
		$("#txtCurrenLoc").text(dataNya.currentLoc);
		$("#txtLat").text(dataNya.lat);
		$("#txtLong").text(dataNya.long);
		$("#txtCourse").text(dataNya.course);
		$("#txtDistToRun").text(dataNya.distancetoRun);
		$("#txtTotalDistToRun").text(dataNya.totalDistance);
		$("#txtStreamingTime").text(dataNya.streamingTime);
		$("#txtTotalStreamTime").text(dataNya.totalSteaming);
		$("#txtAvarageSpeed").text(dataNya.averageSpeed);
		$("#txtGeneralAverageSpeed").text(dataNya.generalAverageSpeed);
		$("#txtDistanceToGo").text(dataNya.distanceToGo);
		$("#txtEtaDate").text(dataNya.etaDate);
		$("#txtEtaHours").text(dataNya.etaHours);
		$("#txtBySpeed").text(dataNya.bySpeed);
		$("#txtRpm").text(dataNya.rpm);
		$("#txtWeatherCondition").text(dataNya.weather);
		$("#txtRobMFO").text(dataNya.robMfo);
		$("#txtRobMDO").text(dataNya.robMdo);
		$("#txtRobHSD").text(dataNya.robHsd);
		$("#txtRobLo").text(dataNya.robLo);
		$("#txtRobFW").text(dataNya.robFw);
		$("#txtSupplyMFO").text(dataNya.supplyMfo);
		$("#txtSupplyMDO").text(dataNya.supplyMdo);
		$("#txtSupplyHSD").text(dataNya.supplyHsd);
		$("#txtSupplyLO").text(dataNya.supplyLo);
		$("#txtSupplyFW").text(dataNya.supplyFw);
		$("#txtCurrentMFO").text(dataNya.currentMfo);
		$("#txtCurrentMDO").text(dataNya.currentMdo);
		$("#txtCurrentHSD").text(dataNya.currentHsd);
		$("#txtCurrentLO").text(dataNya.currentLo);
		$("#txtCurrentFW").text(dataNya.currentFw);
		$("#txtGradeAName").text(dataNya.gradeAName);
		$("#txtGradeACurrentROB").text(dataNya.gradeACurrentRob);
		$("#txtGradeAPumpRate").text(dataNya.gradeAPumpRate);
		var dataEstA = dataNya.gradeAEstCompl;
		var arrA = dataEstA.split(' ');
		$("#txtGradeAEstDate").text(arrA[0]);
		$("#txtGradeAEstHours").text(arrA[1]);
		$("#txtGradeBName").text(dataNya.gradeBName);
		$("#txtGradeBCurrentROB").text(dataNya.gradeBCurrentRob);
		$("#txtGradeBPumpRate").text(dataNya.gradeBPumpRate);
		var dataEstB = dataNya.gradeBEstCompl;
		var arrB = dataEstB.split(' ');
		$("#txtGradeBEstDate").text(arrB[0]);
		$("#txtGradeBEstHours").text(arrB[1]);
		$("#txtGradeCName").text(dataNya.gradeCName);
		$("#txtGradeCCurrentROB").text(dataNya.gradeCCurrentRob);
		$("#txtGradeCPumpRate").text(dataNya.gradeCPumpRate);
		var dataEstC = dataNya.gradeCEstCompl;
		var arrC = dataEstC.split(' ');
		$("#txtGradeCEstDate").text(arrC[0]);
		$("#txtGradeCEstHours").text(arrC[1]);
		$("#txtGradeDName").text(dataNya.gradeDName);
		$("#txtGradeDCurrentROB").text(dataNya.gradeDCurrentRob);
		$("#txtGradeDPumpRate").text(dataNya.gradeDPumpRate);
		var dataEstD = dataNya.gradeDEstCompl;
		var arrD = dataEstD.split(' ');
		$("#txtGradeDEstDate").text(arrD[0]);
		$("#txtGradeDEstHours").text(arrD[1]);
	}
	function onClickView(id)
	{
		$.post('../shipManagement/class/actionNav.php',
    	{ actionGetDataUploadNoonById : id},
        	function(data) 
            { 
            	setView(data);
            	$("#btnSave").hide();
            	$("#idBtnCancel").text("Close");
            	$(".equipName").hide();
            	$("#idNavSearch").hide();
            	$(".viewExcel").show(250);
            },
         "json"
         );
	}
</script>
<style type="text/css">
	.ui-datepicker {
		width: 17%;
	}
</style>
<!DOCTYPE html>
<div class="archivesContainer" style="padding-bottom:50px;margin-top:10px;">
	<div class="navBtn" style="width:100%;min-height:0px;" >
		<img src="../picture/ajax-loader20.gif" style="width: 24px;height: 24px;margin: 10px;" id="loading" class="btnStandar">
		<div id="idNavUpload">
			<input type="file" name="fileNoon" id="fileNoon" class="btnStandar" style="width:200px;height:29px; margin: 10px;float: left;">
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
    		<input type="text" autocomplete="off" name="txtSDate" id="txtSDate" class="elementSearch" style="width: 10%;margin: 10px;float: left;" placeholder="SDate Voyage">
    		<input type="text" autocomplete="off" name="txtEDate" id="txtEDate" class="elementSearch" style="width: 10%;margin: 10px;float: left;" placeholder="EDate Voyage">
    		<button class="btnStandar" id="btnSearch" style="width:100px;height:29px; margin: 10px; float: left;" title="View Data">
	        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
	            	<tr>
	                	<td align="right" width="25"><img src="../picture/Search-blue-32.png" height="20"/> </td>
	                    <td align="center">Search</td>
	              	</tr>
	         	</table>
	     	</button>
	     	<button class="btnStandar" id="btnNavUpload" style="width:100px;height:29px; margin: 10px; float: left;" title="View Data">
	        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
	            	<tr>
	                	<td align="right" width="25"><img src="../picture/Outbox-blue-32.png" height="20"/> </td>
	                    <td align="center">Upload File</td>
	              	</tr>
	         	</table>
	     	</button>
    	</div>
	</div>
	<div class="equipName" style="width:100%;max-height:300px;overflow:scroll;">
		<table cellpadding="0" cellspacing="0" width="100%" border="1" id="tblEquipName">
			<thead style="font-size: 12px;background-color: #125362;color: #FFFFFF;height:30px;">
				<tr>
					<td style="width: 3%;" align="center">NO</td>
					<td style="width: 12%;" align="center">NAME OF MASTER</td>
					<td style="width: 10%;" align="center">NOON POSITION</td>
					<td style="width: 10%;" align="center">VOYAGE NO</td>
					<td style="width: 10%;" align="center">DATE</td>
					<td style="width: 15%;" align="center">FROM - TO</td>
					<td style="width: 10%;" align="center">CURRENT LOC.</td>
					<td style="width: 15%;" align="center">WEATHER</td>
				</tr>
			</thead>
			<tbody style="font-size: 11px;cursor: pointer;" id="tblIdBody">
				{getDataUploadNoon}
			</tbody>
		</table>
	</div>
	<div class="viewExcel" style="width:100%;min-height:300px;overflow:scroll;" align="center">
		<fieldset>
			<legend style="padding: 10px;"><h3 style="font:1em sans-serif;font-weight:100;color:#485a88;">View Data File Upload</h3></legend>
			<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tabelBorderTopJust" style="font:0.9em sans-serif;font-weight:100;color:#485a88;border-style:dotted;padding: 10px;">
				<tr>
	                <td width="220" height="22" class="tabelBorderBottomJust" style="border-style:dotted;color: #000;">NAME OF MASTER</td>
	                <td class="tabelBorderBottomJust elementTeks" colspan="4" id="txtNameMaster"></td>
	            </tr>
	            <tr>
	                <td width="220" height="22" class="tabelBorderBottomJust" style="border-style:dotted;color: #000;">SATELLITE TELEPHONE</td>
	                <td class="tabelBorderBottomJust elementTeks" colspan="4" id="txtSatNo"></td>
	            </tr>
	            <tr>
	                <td width="220" height="22" class="tabelBorderBottomJust" style="border-style:dotted;color: #000;">SATELLITE EMAIL</td>
	                <td class="tabelBorderBottomJust elementTeks" colspan="4" id="txtSatEmail"></td>
	            </tr>
	            <tr>
	                <td width="220" height="22" class="tabelBorderBottomJust" style="border-style:dotted;color: #000;">NOON POSITION REPORT OF</td>
	                <td class="tabelBorderBottomJust elementTeks" colspan="4" id="txtNoonPosition"></td>
	            </tr>
	            <tr>
	                <td width="220" height="22" class="tabelBorderBottomJust" style="border-style:dotted;color: #000;">VOYAGE NUMBER</td>
	                <td class="tabelBorderBottomJust elementTeks" width="150" id="txtVoyageNo"></td>
	                <td width="100" height="22" align="center" style="color: #000;">DATE</td>
	                <td class="tabelBorderBottomJust elementTeks" colspan="2" id="txtVoyageDate"></td>
	            </tr>
	            <tr>
	                <td width="220" height="22" class="tabelBorderBottomJust" style="border-style:dotted;color: #000;">FROM</td>
	                <td class="tabelBorderBottomJust elementTeks" id="txtVoyageFrom"></td>
	                <td width="100" height="22" align="center" style="color: #000;">TO</td>
	                <td class="tabelBorderBottomJust elementTeks" colspan="2" id="txtVoyageTo"></td>
	            </tr>
	            <tr>
	                <td width="220" height="22" class="tabelBorderBottomJust" style="border-style:dotted;color: #000;">CURRENT LOCATION</td>
	                <td class="tabelBorderBottomJust elementTeks" colspan="4" id="txtCurrenLoc"></td>
	            </tr>
	            <tr>
	                <td width="220" height="22" class="tabelBorderBottomJust" style="border-style:dotted;color: #000;">LATITUDE</td>
	                <td class="tabelBorderBottomJust elementTeks" colspan="4" id="txtLat"></td>
	                <td width="30" align="center" height="22" style="color: #000;">N/S</td>
	            </tr>
	            <tr>
	                <td width="220" height="22" class="tabelBorderBottomJust" style="border-style:dotted;color: #000;">LONGITUDE</td>
	                <td class="tabelBorderBottomJust elementTeks" colspan="4" id="txtLong"></td>
	                <td width="30" align="center" height="22" style="color: #000;">E/W</td>
	            </tr>
	            <tr>
	                <td width="220" height="22" class="tabelBorderBottomJust" style="border-style:dotted;color: #000;">COURSE</td>
	                <td class="tabelBorderBottomJust elementTeks" colspan="4" id="txtCourse"></td>
	                <td width="30" align="center" height="22" style="color: #000;">DEGREE</td>
	            </tr>
	            <tr>
	                <td width="220" height="22" class="tabelBorderBottomJust" style="border-style:dotted;color: #000;">DISTANCE TO RUN</td>
	                <td class="tabelBorderBottomJust elementTeks" colspan="4" id="txtDistToRun"></td>
	                <td width="30" align="center" height="22" style="color: #000;">NM</td>
	            </tr>
	            <tr>
	                <td width="220" height="22" class="tabelBorderBottomJust" style="border-style:dotted;color: #000;">TOTAL DISTANCE TO RUN</td>
	                <td class="tabelBorderBottomJust elementTeks" colspan="4" id="txtTotalDistToRun"></td>
	                <td width="30" align="center" height="22" style="color: #000;">NM</td>
	            </tr>
	            <tr>
	                <td width="220" height="22" class="tabelBorderBottomJust" style="border-style:dotted;color: #000;">STEAMING TIME</td>
	                <td class="tabelBorderBottomJust elementTeks" colspan="4" id="txtStreamingTime"></td>
	                <td width="30" align="center" height="22" style="color: #000;">HOUR</td>
	            </tr>
	            <tr>
	                <td width="220" height="22" class="tabelBorderBottomJust" style="border-style:dotted;color: #000;">TOTAL STEAMING TIME</td>
	                <td class="tabelBorderBottomJust elementTeks" colspan="4" id="txtTotalStreamTime"></td>
	                <td width="30" align="center" height="22" style="color: #000;">HOUR</td>
	            </tr>
	            <tr>
	                <td width="220" height="22" class="tabelBorderBottomJust" style="border-style:dotted;color: #000;">AVERAGE SPEED</td>
	                <td class="tabelBorderBottomJust elementTeks" colspan="4" id="txtAvarageSpeed"></td>
	                <td width="30" align="center" height="22" style="color: #000;">KNOT</td>
	            </tr>
	            <tr>
	                <td width="220" height="22" class="tabelBorderBottomJust" style="border-style:dotted;color: #000;">GENERAL AVERAGE SPEED</td>
	                <td class="tabelBorderBottomJust elementTeks" colspan="4" id="txtGeneralAverageSpeed"></td>
	                <td width="30" align="center" height="22" style="color: #000;">KNOT</td>
	            </tr>
	            <tr>
	                <td width="220" height="22" class="tabelBorderBottomJust" style="border-style:dotted;color: #000;">DISTANCE TO GO</td>
	                <td class="tabelBorderBottomJust elementTeks" colspan="4" id="txtDistanceToGo"></td>
	                <td width="30" align="center" height="22" style="color: #000;">NM</td>
	            </tr>
	            <tr>
	                <td width="220" height="22" class="tabelBorderBottomJust" style="border-style:dotted;color: #000;">ETA DATE</td>
	                <td class="tabelBorderBottomJust elementTeks" width="150" id="txtEtaDate"></td>
	                <td width="100" height="22" align="center" style="color: #000;">ETA HOUR</td>
	                <td class="tabelBorderBottomJust elementTeks" colspan="2" id="txtEtaHours"></td>
	            </tr>
	            <tr>
	                <td width="220" height="22" class="tabelBorderBottomJust" style="border-style:dotted;color: #000;">BY SPEED</td>
	                <td class="tabelBorderBottomJust elementTeks" colspan="4" id="txtBySpeed"></td>
	                <td width="30" align="center" height="22" style="color: #000;">KNOT</td>
	            </tr>
	            <tr>
	                <td width="220" height="22" class="tabelBorderBottomJust" style="border-style:dotted;color: #000;">RPM (REV PER MINUTE)</td>
	                <td class="tabelBorderBottomJust elementTeks" colspan="4" id="txtRpm"></td>
	                <td width="30" align="center" height="22" style="color: #000;">RPM</td>
	            </tr>
	            <tr>
	                <td width="220" height="22" class="tabelBorderBottomJust" style="border-style:dotted;color: #000;">WEATHER CONDITION</td>
	                <td class="tabelBorderBottomJust elementTeks" colspan="4" id="txtWeatherCondition"></td>
	            </tr>
			</table>
			<table border="1" cellpadding="0" cellspacing="0" width="100%" class="tabelBorderAll" style="font:0.9em sans-serif;font-weight:100;color:#485a88;border-style:dotted;padding: 10px;">
				<tr style="background-color: #bdbdbd;">
	                <td height="22" class="tabelBorderAll" align="center" colspan="3" style="color: #000;font-weight: bold;">CURRENT ROB (REMAIN ON BOARD)</td>
	            </tr>
	            <tr>
	                <td width="150" height="22" class="tabelBorderAll" style="color: #000;" rowspan="3" align="center">BUNKER</td>
	                <td width="50" height="22" class="tabelBorderAll" style="color: #000;">MFO</td>
	                <td height="22" class="tabelBorderAll" id="txtRobMFO"></td>
	                <td class="tabelBorderAllNull" width="50" align="center" height="22" style="color: #000;">MT</td>
	            </tr>
	            <tr>
	                <td height="22" class="tabelBorderAll" style="color: #000;">MDO</td>
	                <td height="22" class="tabelBorderAll" id="txtRobMDO"></td>
	                <td class="tabelBorderAllNull" align="center" height="22" style="color: #000;">MT</td>
	            </tr>
	            <tr>
	                <td height="22" class="tabelBorderAll" style="color: #000;">HSD</td>
	                <td height="22" class="tabelBorderAll" id="txtRobHSD"></td>
	                <td class="tabelBorderAllNull" align="center" height="22" style="color: #000;">MT</td>
	            </tr>
	            <tr>
	                <td width="150" height="22" class="tabelBorderAll" style="color: #000;" align="center">LUBRICATING OIL</td>
	                <td width="50" height="22" class="tabelBorderAll" style="color: #000;">LO</td>
	                <td height="22" class="tabelBorderAll" id="txtRobLo"></td>
	                <td class="tabelBorderAllNull" width="50" align="center" height="22" style="color: #000;">LITRE</td>
	            </tr>
	            <tr>
	                <td width="150" height="22" class="tabelBorderAll" style="color: #000;" align="center">FRESH WATER</td>
	                <td width="50" height="22" class="tabelBorderAll" style="color: #000;">FW</td>
	                <td height="22" class="tabelBorderAll" id="txtRobFW"></td>
	                <td class="tabelBorderAllNull" width="50" align="center" height="22" style="color: #000;">MT</td>
	            </tr>
			</table>
			<table border="1" cellpadding="0" cellspacing="0" width="100%" class="tabelBorderAll" style="font:0.9em sans-serif;font-weight:100;color:#485a88;border-style:dotted;padding: 10px;">
				<tr style="background-color: #bdbdbd;">
	                <td height="22" class="tabelBorderAll" align="center" colspan="3" style="color: #000;font-weight: bold;">SUPPLY ACTUAL RECEIVED (IN LAST 24 HOUR)</td>
	            </tr>
	            <tr>
	                <td width="150" height="22" class="tabelBorderAll" style="color: #000;" rowspan="3" align="center">BUNKER</td>
	                <td width="50" height="22" class="tabelBorderAll" style="color: #000;">MFO</td>
	                <td height="22" class="tabelBorderAll" id="txtSupplyMFO"></td>
	                <td class="tabelBorderAllNull" width="50" align="center" height="22" style="color: #000;">MT</td>
	            </tr>
	            <tr>
	                <td height="22" class="tabelBorderAll" style="color: #000;">MDO</td>
	                <td height="22" class="tabelBorderAll" id="txtSupplyMDO"></td>
	                <td class="tabelBorderAllNull" align="center" height="22" style="color: #000;">MT</td>
	            </tr>
	            <tr>
	                <td height="22" class="tabelBorderAll" style="color: #000;">HSD</td>
	                <td height="22" class="tabelBorderAll" id="txtSupplyHSD"></td>
	                <td class="tabelBorderAllNull" align="center" height="22" style="color: #000;">MT</td>
	            </tr>
	            <tr>
	                <td width="150" height="22" class="tabelBorderAll" style="color: #000;" align="center">LUBRICATING OIL</td>
	                <td width="50" height="22" class="tabelBorderAll" style="color: #000;">LO</td>
	                <td height="22" class="tabelBorderAll" id="txtSupplyLO"></td>
	                <td class="tabelBorderAllNull" width="50" align="center" height="22" style="color: #000;">LITRE</td>
	            </tr>
	            <tr>
	                <td width="150" height="22" class="tabelBorderAll" style="color: #000;" align="center">FRESH WATER</td>
	                <td width="50" height="22" class="tabelBorderAll" style="color: #000;">FW</td>
	                <td height="22" class="tabelBorderAll" id="txtSupplyFW"></td>
	                <td class="tabelBorderAllNull" width="50" align="center" height="22" style="color: #000;">MT</td>
	            </tr>
			</table>
			<table border="1" cellpadding="0" cellspacing="0" width="100%" class="tabelBorderAll" style="font:0.9em sans-serif;font-weight:100;color:#485a88;border-style:dotted;padding: 10px;">
				<tr style="background-color: #bdbdbd;">
	                <td height="22" class="tabelBorderAll" align="center" colspan="3" style="color: #000;font-weight: bold;">CURRENT CONSUMPTION RATE</td>
	            </tr>
	            <tr>
	                <td width="150" height="22" class="tabelBorderAll" style="color: #000;" rowspan="3" align="center">BUNKER</td>
	                <td width="50" height="22" class="tabelBorderAll" style="color: #000;">MFO</td>
	                <td height="22" class="tabelBorderAll" id="txtCurrentMFO"></td>
	                <td class="tabelBorderAllNull" width="50" align="center" height="22" style="color: #000;">MT/DAY</td>
	            </tr>
	            <tr>
	                <td height="22" class="tabelBorderAll" style="color: #000;">MDO</td>
	                <td height="22" class="tabelBorderAll" id="txtCurrentMDO"></td>
	                <td class="tabelBorderAllNull" align="center" height="22" style="color: #000;">MT/DAY</td>
	            </tr>
	            <tr>
	                <td height="22" class="tabelBorderAll" style="color: #000;">HSD</td>
	                <td height="22" class="tabelBorderAll" id="txtCurrentHSD"></td>
	                <td class="tabelBorderAllNull" align="center" height="22" style="color: #000;">MT/DAY</td>
	            </tr>
	            <tr>
	                <td width="150" height="22" class="tabelBorderAll" style="color: #000;" align="center">LUBRICATING OIL</td>
	                <td width="50" height="22" class="tabelBorderAll" style="color: #000;">LO</td>
	                <td height="22" class="tabelBorderAll" id="txtCurrentLO"></td>
	                <td class="tabelBorderAllNull" width="50" align="center" height="22" style="color: #000;">LITRE/DAY</td>
	            </tr>
	            <tr>
	                <td width="150" height="22" class="tabelBorderAll" style="color: #000;" align="center">FRESH WATER</td>
	                <td width="50" height="22" class="tabelBorderAll" style="color: #000;">FW</td>
	                <td height="22" class="tabelBorderAll" id="txtCurrentFW"></td>
	                <td class="tabelBorderAllNull" width="50" align="center" height="22" style="color: #000;">MT/DAY</td>
	            </tr>
			</table>
			<table border="1" cellpadding="0" cellspacing="0" width="100%" class="tabelBorderAll" style="font:0.9em sans-serif;font-weight:100;color:#485a88;border-style:dotted;padding: 10px;">
				<tr style="background-color: #bdbdbd;">
	                <td height="22" class="tabelBorderAll" align="center" colspan="6" style="color: #000;font-weight: bold;">NOON CARGO OPERATION REPORT</td>
	            </tr>
	            <tr style="background-color: #e4e4e4;">
	                <td width="150" height="22" class="tabelBorderAll" style="color: #000;" rowspan="2" align="center">CARGO</td>
	                <td width="150" height="22" class="tabelBorderAll" style="color: #000;" rowspan="2" align="center" >CARGO NAME</td>
	                <td height="22" style="color: #000;" align="center" class="tabelBorderAll" >CURRENT ROB</td>
	                <td height="22" style="color: #000;" align="center" class="tabelBorderAll" >PUMP RATE</td>
	                <td height="22" style="color: #000;" align="center" class="tabelBorderAll" colspan="2" >EST COMPLETION</td>
	            </tr>
	            <tr style="background-color: #e4e4e4;">
	                <td height="22" style="color: #000;" align="center" class="tabelBorderAll" >KL OBS</td>
	                <td height="22" style="color: #000;" align="center" class="tabelBorderAll" >KL / HOUR</td>
	                <td height="22" style="color: #000;" align="center" class="tabelBorderAll" >DATE</td>
	                <td height="22" style="color: #000;" align="center" class="tabelBorderAll" >HOUR</td>
	            </tr>
	            <tr>
	            	<td height="22" style="color: #000;" align="center" class="tabelBorderAll" >GRADE A</td>
	            	<td height="22" class="tabelBorderAll" id="txtGradeAName"></td>
	            	<td height="22" class="tabelBorderAll" id="txtGradeACurrentROB"></td>
	            	<td height="22" class="tabelBorderAll" id="txtGradeAPumpRate"></td>
	            	<td height="22" class="tabelBorderAll" id="txtGradeAEstDate"></td>
	            	<td height="22" class="tabelBorderAll" id="txtGradeAEstHours"></td>
	            </tr>
	            <tr>
	            	<td height="22" style="color: #000;" align="center" class="tabelBorderAll" >GRADE B</td>
	            	<td height="22" class="tabelBorderAll" id="txtGradeBName"></td>
	            	<td height="22" class="tabelBorderAll" id="txtGradeBCurrentROB"></td>
	            	<td height="22" class="tabelBorderAll" id="txtGradeBPumpRate"></td>
	            	<td height="22" class="tabelBorderAll" id="txtGradeBEstDate"></td>
	            	<td height="22" class="tabelBorderAll" id="txtGradeBEstHours"></td>
	            </tr>
	            <tr>
	            	<td height="22" style="color: #000;" align="center" class="tabelBorderAll" >GRADE C</td>
	            	<td height="22" class="tabelBorderAll" id="txtGradeCName"></td>
	            	<td height="22" class="tabelBorderAll" id="txtGradeCCurrentROB"></td>
	            	<td height="22" class="tabelBorderAll" id="txtGradeCPumpRate"></td>
	            	<td height="22" class="tabelBorderAll" id="txtGradeCEstDate"></td>
	            	<td height="22" class="tabelBorderAll" id="txtGradeCEstHours"></td>
	            </tr>
	            <tr>
	            	<td height="22" style="color: #000;" align="center" class="tabelBorderAll" >GRADE D</td>
	            	<td height="22" class="tabelBorderAll" id="txtGradeDName"></td>
	            	<td height="22" class="tabelBorderAll" id="txtGradeDCurrentROB"></td>
	            	<td height="22" class="tabelBorderAll" id="txtGradeDPumpRate"></td>
	            	<td height="22" class="tabelBorderAll" id="txtGradeDEstDate"></td>
	            	<td height="22" class="tabelBorderAll" id="txtGradeDEstHours"></td>
	            </tr>	            
			</table>
		</fieldset>
		<button class="btnStandar" id="btnSave" style="width:100px;height:29px; margin: 10px; " title="Save Data">
        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
            	<tr>
                	<td align="right" width="25"><img src="../picture/Floppy-Disk-blue-32.png" height="20"/> </td>
                    <td align="center">Save</td>
              	</tr>
         	</table>
     	</button>
     	<button onclick="location.reload(true);" class="btnStandar" id="btnCancel" style="width:100px;height:29px; margin: 10px; " title="Cancel Data">
        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
            	<tr>
                	<td align="right" width="25"><img src="../picture/Button-Cross-red-32.png" height="20"/> </td>
                    <td align="center" id="idBtnCancel">Cancel</td>
              	</tr>
         	</table>
     	</button>
	</div>
</div>


