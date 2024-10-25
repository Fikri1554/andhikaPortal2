<link rel="stylesheet" href="../css/jquery-ui.css">
<script type="text/javascript" src="../js/jquery-1.6.js"></script>
<script type="text/javascript" src="../js/niceEdit.js"></script>
<script type="text/javascript" src="../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<link rel="stylesheet" type="text/css" href="../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></LINK>
<script src="../js/jquery-ui.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('input[type="text"]').attr('autocomplete', 'off');
		$("#loading").hide();
		//$("#idNavUpload").hide();
		$(".viewExcel").hide();
		$("#idNavUpload").hide();
		$("#idPlannMaintenance").css("display","none");
        $("#idBtnDeficiency").css("display","none");
        $("#idBtnSurveyStatus").css("display","none");

        $( "#txtSdate" ).datepicker({
			dateFormat: 'yy-mm-dd',
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            defaultDate: new Date(),
		});
		$( "#txtEdate" ).datepicker({
			dateFormat: 'yy-mm-dd',
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            defaultDate: new Date(),
		});

        $("#fileOil").change(function(){
			var filename = $(this)[0].files[0];
			var ext = filename.name.split(".");
			ext = ext[ext.length-1].toLowerCase();
			if(ext != "xls")
			{
				$("#btnView").attr("disabled",true);
				alert("Only file .xls");
				$(this).val("");
				return false;
			}
			$("#btnView").attr("disabled",false);
		});
		$("#btnView").click(function(){
        	$("#loading").show();
        	var sheetNya = $("#txtSheet").val();
        	var fileData = $("#fileOil").prop('files')[0];
			var formData = new FormData();
			if(sheetNya == "")
			{
				alert("no shhet can't empty..!!");
				$("#loading").hide();
				return false;
			}
			formData.append('file', fileData);
			formData.append('actionUploadExcelOil', "uploadExcelOil");
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
			$("#loading").show();
			$(window).scrollTop(0);
			var insSheet = $("#txtSheet").val();
			var fileIns = $("#fileOil").prop('files')[0];
			var insData = new FormData();
			insData.append('file', fileIns);
			insData.append('actionInsExcelOil', "insExcelOil");
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
			$("#loading").hide();
		});
		$("#btnNavUpload").click(function(){
			$("#idNavSearch").hide(300);
			$(".equipName").hide(300);
			$("#idNavUpload").show(300);
		});
		$("#btnSearch").click(function(){
			$("#loading").show();
			$("#tblIdBody").empty();
			var vNo = $("#txtSearchVoyageNo").val();
			var sDate = $("#txtSdate").val();
			var eDate = $("#txtEdate").val();
			$.post('../shipManagement/class/actionNav.php',
	    	{ actionGetSearchUploadOil : "searchUploadOil",vNo : vNo,sDate : sDate,eDate : eDate},
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
		var gradeName = "";
		var typeNya = dataNya.voyageNo;
		var cekTypeData = typeNya.includes("D");

		$("#txtNameMaster").text(dataNya.masterName);
		$("#txtSatNo").text(dataNya.satelitePhone);
		$("#txtSatEmail").text(dataNya.sateliteEmail);
		$("#txtOilPosition").text(dataNya.oilPosition);
		$("#txtVoyageNo").text(dataNya.voyageNo);
		$("#txtInPOrt").text(dataNya.inPort);
		$("#txtAADate").text(dataNya.codeAADate);
		$("#txtAATime").text(dataNya.codeAATime);
		$("#txtPreviousPort").text(dataNya.previousPort);
		$("#txtActualDist").text(dataNya.actualDistance);
		$("#txtBBDate").text(dataNya.codeBBDate);
		$("#txtBBTime").text(dataNya.codeBBTime);
		$("#txtCCDate").text(dataNya.codeCCDate);
		$("#txtCCTime").text(dataNya.codeCCTime);
		$("#txtDDDate").text(dataNya.codeDDDate);
		$("#txtDDTime").text(dataNya.codeDDTime);
		$("#txtGradeA").text(dataNya.gradeA);
		$("#txtGradeB").text(dataNya.gradeB);
		$("#txtGradeC").text(dataNya.gradeC);
		$("#txtGradeD").text(dataNya.gradeD);

		for (var hal = 1; hal <= 4; hal++) 
		{
			if(hal == 1)
			{
				gradeName = "A";
			}
			else if(hal == 2)
			{
				gradeName = "B";
			}
			else if(hal == 3)
			{
				gradeName = "C";
			}
			else if(hal == 4)
			{
				gradeName = "D";
			}
			$("#txtConn"+gradeName+"Date").text(dataNya["grade"+gradeName+"HoseConnDate"]);
			$("#txtConn"+gradeName+"Time").text(dataNya["grade"+gradeName+"HoseConnTime"]);
			$("#txtStart"+gradeName+"1Date").text(dataNya["grade"+gradeName+"1StartDate"]);
			$("#txtStart"+gradeName+"1Time").text(dataNya["grade"+gradeName+"1StartTime"]);
			$("#txtStop"+gradeName+"1Date").text(dataNya["grade"+gradeName+"1StopDate"]);
			$("#txtStop"+gradeName+"1Time").text(dataNya["grade"+gradeName+"1StopTime"]);
			$("#txtNoteGrade"+gradeName+"1").text(dataNya["grade"+gradeName+"1StopNote"]);
			$("#txtStart"+gradeName+"2Date").text(dataNya["grade"+gradeName+"2StartDate"]);
			$("#txtStart"+gradeName+"2Time").text(dataNya["grade"+gradeName+"2StartTime"]);
			$("#txtStop"+gradeName+"2Date").text(dataNya["grade"+gradeName+"2StopDate"]);
			$("#txtStop"+gradeName+"2Time").text(dataNya["grade"+gradeName+"2StopTime"]);
			$("#txtNoteGrade"+gradeName+"2").text(dataNya["grade"+gradeName+"2StopNote"]);
			$("#txtStart"+gradeName+"3Date").text(dataNya["grade"+gradeName+"3StartDate"]);
			$("#txtStart"+gradeName+"3Time").text(dataNya["grade"+gradeName+"3StartTime"]);
			$("#txtStop"+gradeName+"3Date").text(dataNya["grade"+gradeName+"3StopDate"]);
			$("#txtStop"+gradeName+"3Time").text(dataNya["grade"+gradeName+"3StopTime"]);
			$("#txtNoteGrade"+gradeName+"3").text(dataNya["grade"+gradeName+"3StopNote"]);
			$("#txtDisConn"+gradeName+"Date").text(dataNya["grade"+gradeName+"HoseDisConnDate"]);
			$("#txtDisConn"+gradeName+"Time").text(dataNya["grade"+gradeName+"HoseDisConnTime"]);
			//teks loading/discharge
			for(var asl = 1;asl <= 8; asl++)
			{
				if(cekTypeData == true)
				{
					$("#idTypeNya"+gradeName+asl).text("DISCHARGE");
				}else{
					$("#idTypeNya"+gradeName+asl).text("LOADING");
				}
			}
		}

		$("#unberthedDate").text(dataNya.unBerthedDate);
		$("#unberthedTimes").text(dataNya.unBerthedTime);
		$("#txtAnchorDate").text(dataNya.anchorDate);
		$("#txtAnchorTime").text(dataNya.anchorTime);
		$("#txtActualLineDate").text(dataNya.actualLineDate);
		$("#txtActualLineTime").text(dataNya.actualLineTime);
		$("#txtAtdOuterDate").text(dataNya.atdOuterDate);
		$("#txtAtdOuterTimes").text(dataNya.atdOuterTime);
		$("#txtBolGradeNameA").text(dataNya.bolAGradeName);
		$("#txtBolGradeDocNoA").text(dataNya.bolADocNo);
		$("#txtBolGradeDateA").text(dataNya.bolADate);
		$("#txtBolGradeKLObsA").text(dataNya.bolAKlObs);
		$("#txtBolGradeKl15oCA").text(dataNya.bolAKL15C);
		$("#txtBolGradeBblsA").text(dataNya.bolABBLS);
		$("#txtBolGradeLtA").text(dataNya.bolALT);
		$("#txtBolGradeMtA").text(dataNya.bolAMT);
		$("#txtBolGradeNameB").text(dataNya.bolBGradeName);
		$("#txtBolGradeDocNoB").text(dataNya.bolBDocNo);
		$("#txtBolGradeDateB").text(dataNya.bolBDate);
		$("#txtBolGradeKLObsB").text(dataNya.bolBKlObs);
		$("#txtBolGradeKl15oCB").text(dataNya.bolBKL15C);
		$("#txtBolGradeBblsB").text(dataNya.bolBBBLS);
		$("#txtBolGradeLtB").text(dataNya.bolBLT);
		$("#txtBolGradeMtB").text(dataNya.bolBMT);
		$("#txtBolGradeNameC").text(dataNya.bolCGradeName);
		$("#txtBolGradeDocNoC").text(dataNya.bolCDocNo);
		$("#txtBolGradeDateC").text(dataNya.bolCDate);
		$("#txtBolGradeKLObsC").text(dataNya.bolCKlObs);
		$("#txtBolGradeKl15oCC").text(dataNya.bolCKL15C);
		$("#txtBolGradeBblsC").text(dataNya.bolCBBLS);
		$("#txtBolGradeLtC").text(dataNya.bolCLT);
		$("#txtBolGradeMtC").text(dataNya.bolCMT);
		$("#txtBolGradeNameD").text(dataNya.bolDGradeName);
		$("#txtBolGradeDocNoD").text(dataNya.bolDDocNo);
		$("#txtBolGradeDateD").text(dataNya.bolDDate);
		$("#txtBolGradeKLObsD").text(dataNya.bolDKlObs);
		$("#txtBolGradeKl15oCD").text(dataNya.bolDKL15C);
		$("#txtBolGradeBblsD").text(dataNya.bolDBBLS);
		$("#txtBolGradeLtD").text(dataNya.bolDLT);
		$("#txtBolGradeMtD").text(dataNya.bolDMT);
		$("#txtSFALGradeNameA").text(dataNya.sfalAGradeName);
		$("#txtSFALGradeDocNoA").text(dataNya.sfalADocNo);
		$("#txtSFALGradeDateA").text(dataNya.sfalADate);
		$("#txtSFALGradeKLObsA").text(dataNya.sfalAKLObs);
		$("#txtSFALGradeKl15oCA").text(dataNya.sfalAKL15C);
		$("#txtSFALGradeBblsA").text(dataNya.sfalABBLS);
		$("#txtSFALGradeLtA").text(dataNya.sfalALT);
		$("#txtSFALGradeMtA").text(dataNya.sfalAMT);
		$("#txtSFALGradeNameB").text(dataNya.sfalBGradeName);
		$("#txtSFALGradeDocNoB").text(dataNya.sfalBDocNo);
		$("#txtSFALGradeDateB").text(dataNya.sfalBDate);
		$("#txtSFALGradeKLObsB").text(dataNya.sfalBKLObs);
		$("#txtSFALGradeKl15oCB").text(dataNya.sfalBKL15C);
		$("#txtSFALGradeBblsB").text(dataNya.sfalBBBLS);
		$("#txtSFALGradeLtB").text(dataNya.sfalBLT);
		$("#txtSFALGradeMtB").text(dataNya.sfalBMT);
		$("#txtSFALGradeNameC").text(dataNya.sfalCGradeName);
		$("#txtSFALGradeDocNoC").text(dataNya.sfalCDocNo);
		$("#txtSFALGradeDateC").text(dataNya.sfalCDate);
		$("#txtSFALGradeKLObsC").text(dataNya.sfalCKLObs);
		$("#txtSFALGradeKl15oCC").text(dataNya.sfalCKL15C);
		$("#txtSFALGradeBblsC").text(dataNya.sfalCBBLS);
		$("#txtSFALGradeLtC").text(dataNya.sfalCLT);
		$("#txtSFALGradeMtC").text(dataNya.sfalCMT);
		$("#txtSFALGradeNameD").text(dataNya.sfalDGradeName);
		$("#txtSFALGradeDocNoD").text(dataNya.sfalDDocNo);
		$("#txtSFALGradeDateD").text(dataNya.sfalDDate);
		$("#txtSFALGradeKLObsD").text(dataNya.sfalDKLObs);
		$("#txtSFALGradeKl15oCD").text(dataNya.sfalDKL15C);
		$("#txtSFALGradeBblsD").text(dataNya.sfalDBBLS);
		$("#txtSFALGradeLtD").text(dataNya.sfalDLT);
		$("#txtSFALGradeMtD").text(dataNya.sfalDMT);
		$("#txtSFBLGradeNameA").text(dataNya.sfblAGradeName);
		$("#txtSFBLGradeDocNoA").text(dataNya.sfblADocNo);
		$("#txtSFBLGradeDateA").text(dataNya.sfblADate);
		$("#txtSFBLGradeKLObsA").text(dataNya.sfblAKLObs);
		$("#txtSFBLGradeKl15oCA").text(dataNya.sfblAKL15C);
		$("#txtSFBLGradeBblsA").text(dataNya.sfblABBLS);
		$("#txtSFBLGradeLtA").text(dataNya.sfblALT);
		$("#txtSFBLGradeMtA").text(dataNya.sfblAMT);
		$("#txtSFBLGradeNameB").text(dataNya.sfblBGradeName);
		$("#txtSFBLGradeDocNoB").text(dataNya.sfblBDocNo);
		$("#txtSFBLGradeDateB").text(dataNya.sfblBDate);
		$("#txtSFBLGradeKLObsB").text(dataNya.sfblBKLObs);
		$("#txtSFBLGradeKl15oCB").text(dataNya.sfblBKL15C);
		$("#txtSFBLGradeBblsB").text(dataNya.sfblBBBLS);
		$("#txtSFBLGradeLtB").text(dataNya.sfblBLT);
		$("#txtSFBLGradeMtB").text(dataNya.sfblBMT);
		$("#txtSFBLGradeNameC").text(dataNya.sfblCGradeName);
		$("#txtSFBLGradeDocNoC").text(dataNya.sfblCDocNo);
		$("#txtSFBLGradeDateC").text(dataNya.sfblCDate);
		$("#txtSFBLGradeKLObsC").text(dataNya.sfblCKLObs);
		$("#txtSFBLGradeKl15oCC").text(dataNya.sfblCKL15C);
		$("#txtSFBLGradeBblsC").text(dataNya.sfblCBBLS);
		$("#txtSFBLGradeLtC").text(dataNya.sfblCLT);
		$("#txtSFBLGradeMtC").text(dataNya.sfblCMT);
		$("#txtSFBLGradeNameD").text(dataNya.sfblDGradeName);
		$("#txtSFBLGradeDocNoD").text(dataNya.sfblDDocNo);
		$("#txtSFBLGradeDateD").text(dataNya.sfblDDate);
		$("#txtSFBLGradeKLObsD").text(dataNya.sfblDKLObs);
		$("#txtSFBLGradeKl15oCD").text(dataNya.sfblDKL15C);
		$("#txtSFBLGradeBblsD").text(dataNya.sfblDBBLS);
		$("#txtSFBLGradeLtD").text(dataNya.sfblDLT);
		$("#txtSFBLGradeMtD").text(dataNya.sfblDMT);
		$("#txtNewBolGradeNameA").text(dataNya.newBolAGradeName);
		$("#txtNewBolGradeDocNoA").text(dataNya.newBolADocNo);
		$("#txtNewBolGradeDateA").text(dataNya.newBolADate);
		$("#txtNewBolGradeKLObsA").text(dataNya.newBolAKlObs);
		$("#txtNewBolGradeKl15oCA").text(dataNya.newBolAKL15C);
		$("#txtNewBolGradeBblsA").text(dataNya.newBolABBLS);
		$("#txtNewBolGradeLtA").text(dataNya.newBolALT);
		$("#txtNewBolGradeMtA").text(dataNya.newBolAMT);
		$("#txtNewBolGradeNameB").text(dataNya.newBolBGradeName);
		$("#txtNewBolGradeDocNoB").text(dataNya.newBolBDocNo);
		$("#txtNewBolGradeDateB").text(dataNya.newBolBDate);
		$("#txtNewBolGradeKLObsB").text(dataNya.newBolBKlObs);
		$("#txtNewBolGradeKl15oCB").text(dataNya.newBolBKL15C);
		$("#txtNewBolGradeBblsB").text(dataNya.newBolBBBLS);
		$("#txtNewBolGradeLtB").text(dataNya.newBolBLT);
		$("#txtNewBolGradeMtB").text(dataNya.newBolBMT);
		$("#txtNewBolGradeNameC").text(dataNya.newBolCGradeName);
		$("#txtNewBolGradeDocNoC").text(dataNya.newBolCDocNo);
		$("#txtNewBolGradeDateC").text(dataNya.newBolCDate);
		$("#txtNewBolGradeKLObsC").text(dataNya.newBolCKlObs);
		$("#txtNewBolGradeKl15oCC").text(dataNya.newBolCKL15C);
		$("#txtNewBolGradeBblsC").text(dataNya.newBolCBBLS);
		$("#txtNewBolGradeLtC").text(dataNya.newBolCLT);
		$("#txtNewBolGradeMtC").text(dataNya.newBolCMT);
		$("#txtNewBolGradeNameD").text(dataNya.newBolDGradeName);
		$("#txtNewBolGradeDocNoD").text(dataNya.newBolDDocNo);
		$("#txtNewBolGradeDateD").text(dataNya.newBolDDate);
		$("#txtNewBolGradeKLObsD").text(dataNya.newBolDKlObs);
		$("#txtNewBolGradeKl15oCD").text(dataNya.newBolDKL15C);
		$("#txtNewBolGradeBblsD").text(dataNya.newBolDBBLS);
		$("#txtNewBolGradeLtD").text(dataNya.newBolDLT);
		$("#txtNewBolGradeMtD").text(dataNya.newBolDMT);
		$("#txtSFBDGradeNameA").text(dataNya.sfbdAGradeName);
		$("#txtSFBDGradeDocNoA").text(dataNya.sfbdADocNo);
		$("#txtSFBDGradeDateA").text(dataNya.sfbdADate);
		$("#txtSFBDGradeKLObsA").text(dataNya.sfbdAKLObs);
		$("#txtSFBDGradeKl15oCA").text(dataNya.sfbdAKL15C);
		$("#txtSFBDGradeBblsA").text(dataNya.sfbdABBLS);
		$("#txtSFBDGradeLtA").text(dataNya.sfbdALT);
		$("#txtSFBDGradeMtA").text(dataNya.sfbdAMT);
		$("#txtSFBDGradeNameB").text(dataNya.sfbdBGradeName);
		$("#txtSFBDGradeDocNoB").text(dataNya.sfbdBDocNo);
		$("#txtSFBDGradeDateB").text(dataNya.sfbdBDate);
		$("#txtSFBDGradeKLObsB").text(dataNya.sfbdBKLObs);
		$("#txtSFBDGradeKl15oCB").text(dataNya.sfbdBKL15C);
		$("#txtSFBDGradeBblsB").text(dataNya.sfbdBBBLS);
		$("#txtSFBDGradeLtB").text(dataNya.sfbdBLT);
		$("#txtSFBDGradeMtB").text(dataNya.sfbdBMT);
		$("#txtSFBDGradeNameC").text(dataNya.sfbdCGradeName);
		$("#txtSFBDGradeDocNoC").text(dataNya.sfbdCDocNo);
		$("#txtSFBDGradeDateC").text(dataNya.sfbdCDate);
		$("#txtSFBDGradeKLObsC").text(dataNya.sfbdCKLObs);
		$("#txtSFBDGradeKl15oCC").text(dataNya.sfbdCKL15C);
		$("#txtSFBDGradeBblsC").text(dataNya.sfbdCBBLS);
		$("#txtSFBDGradeLtC").text(dataNya.sfbdCLT);
		$("#txtSFBDGradeMtC").text(dataNya.sfbdCMT);
		$("#txtSFBDGradeNameD").text(dataNya.sfbdDGradeName);
		$("#txtSFBDGradeDocNoD").text(dataNya.sfbdDDocNo);
		$("#txtSFBDGradeDateD").text(dataNya.sfbdDDate);
		$("#txtSFBDGradeKLObsD").text(dataNya.sfbdDKLObs);
		$("#txtSFBDGradeKl15oCD").text(dataNya.sfbdDKL15C);
		$("#txtSFBDGradeBblsD").text(dataNya.sfbdDBBLS);
		$("#txtSFBDGradeLtD").text(dataNya.sfbdDLT);
		$("#txtSFBDGradeMtD").text(dataNya.sfbdDMT);
		$("#txtSFADGradeNameA").text(dataNya.sfadAGradeName);
		$("#txtSFADGradeDocNoA").text(dataNya.sfadADocNo);
		$("#txtSFADGradeDateA").text(dataNya.sfadADate);
		$("#txtSFADGradeKLObsA").text(dataNya.sfadAKLObs);
		$("#txtSFADGradeKl15oCA").text(dataNya.sfadAKL15C);
		$("#txtSFADGradeBblsA").text(dataNya.sfadABBLS);
		$("#txtSFADGradeLtA").text(dataNya.sfadALT);
		$("#txtSFADGradeMtA").text(dataNya.sfadAMT);
		$("#txtSFADGradeNameB").text(dataNya.sfadBGradeName);
		$("#txtSFADGradeDocNoB").text(dataNya.sfadBDocNo);
		$("#txtSFADGradeDateB").text(dataNya.sfadBDate);
		$("#txtSFADGradeKLObsB").text(dataNya.sfadBKLObs);
		$("#txtSFADGradeKl15oCB").text(dataNya.sfadBKL15C);
		$("#txtSFADGradeBblsB").text(dataNya.sfadBBBLS);
		$("#txtSFADGradeLtB").text(dataNya.sfadBLT);
		$("#txtSFADGradeMtB").text(dataNya.sfadBMT);
		$("#txtSFADGradeNameC").text(dataNya.sfadCGradeName);
		$("#txtSFADGradeDocNoC").text(dataNya.sfadCDocNo);
		$("#txtSFADGradeDateC").text(dataNya.sfadCDate);
		$("#txtSFADGradeKLObsC").text(dataNya.sfadCKLObs);
		$("#txtSFADGradeKl15oCC").text(dataNya.sfadCKL15C);
		$("#txtSFADGradeBblsC").text(dataNya.sfadCBBLS);
		$("#txtSFADGradeLtC").text(dataNya.sfadCLT);
		$("#txtSFADGradeMtC").text(dataNya.sfadCMT);
		$("#txtSFADGradeNameD").text(dataNya.sfadDGradeName);
		$("#txtSFADGradeDocNoD").text(dataNya.sfadDDocNo);
		$("#txtSFADGradeDateD").text(dataNya.sfadDDate);
		$("#txtSFADGradeKLObsD").text(dataNya.sfadDKLObs);
		$("#txtSFADGradeKl15oCD").text(dataNya.sfadDKL15C);
		$("#txtSFADGradeBblsD").text(dataNya.sfadDBBLS);
		$("#txtSFADGradeLtD").text(dataNya.sfadDLT);
		$("#txtSFADGradeMtD").text(dataNya.sfadDMT);
		$("#txtShoreGradeNameA").text(dataNya.sarAGradeName);
		$("#txtShoreGradeDocNoA").text(dataNya.sarADocNo);
		$("#txtShoreGradeDateA").text(dataNya.sarADate);
		$("#txtShoreGradeKLObsA").text(dataNya.sarAKLObs);
		$("#txtShoreGradeKl15oCA").text(dataNya.sarAKL15C);
		$("#txtShoreGradeBblsA").text(dataNya.sarABBLS);
		$("#txtShoreGradeLtA").text(dataNya.sarALT);
		$("#txtShoreGradeMtA").text(dataNya.sarAMT);
		$("#txtShoreGradeNameB").text(dataNya.sarBGradeName);
		$("#txtShoreGradeDocNoB").text(dataNya.sarBDocNo);
		$("#txtShoreGradeDateB").text(dataNya.sarBDate);
		$("#txtShoreGradeKLObsB").text(dataNya.sarBKLObs);
		$("#txtShoreGradeKl15oCB").text(dataNya.sarBKL15C);
		$("#txtShoreGradeBblsB").text(dataNya.sarBBBLS);
		$("#txtShoreGradeLtB").text(dataNya.sarBLT);
		$("#txtShoreGradeMtB").text(dataNya.sarBMT);
		$("#txtShoreGradeNameC").text(dataNya.sarCGradeName);
		$("#txtShoreGradeDocNoC").text(dataNya.sarCDocNo);
		$("#txtShoreGradeDateC").text(dataNya.sarCDate);
		$("#txtShoreGradeKLObsC").text(dataNya.sarCKLObs);
		$("#txtShoreGradeKl15oCC").text(dataNya.sarCKL15C);
		$("#txtShoreGradeBblsC").text(dataNya.sarCBBLS);
		$("#txtShoreGradeLtC").text(dataNya.sarCLT);
		$("#txtShoreGradeMtC").text(dataNya.sarCMT);
		$("#txtShoreGradeNameD").text(dataNya.sarDGradeName);
		$("#txtShoreGradeDocNoD").text(dataNya.sarDDocNo);
		$("#txtShoreGradeDateD").text(dataNya.sarDDate);
		$("#txtShoreGradeKLObsD").text(dataNya.sarDKLObs);
		$("#txtShoreGradeKl15oCD").text(dataNya.sarDKL15C);
		$("#txtShoreGradeBblsD").text(dataNya.sarDBBLS);
		$("#txtShoreGradeLtD").text(dataNya.sarDLT);
		$("#txtShoreGradeMtD").text(dataNya.sarDMT);
		$("#txtBunkerAtaHSFO").text(dataNya.bunkerRobAtaHSFO);
		$("#txtBunkerAtaLSFO").text(dataNya.bunkerRobAtaLSFO);
		$("#txtBunkerAtaMDO").text(dataNya.bunkerRobAtaMDO);
		$("#txtBunkerAtaHSD").text(dataNya.bunkerRobAtaHSD);
		$("#txtBunkerAtaFWMT").text(dataNya.bunkerRobAtaFWMT);
		$("#txtBunkerAtaOWST").text(dataNya.bunkerRobAtaOWST);
		$("#txtBunkerReplHSFO").text(dataNya.bunkerReplHSFO);
		$("#txtBunkerReplLSFO").text(dataNya.bunkerReplLSFO);
		$("#txtBunkerReplMDO").text(dataNya.bunkerReplMDO);
		$("#txtBunkerReplHSD").text(dataNya.bunkerReplHSD);
		$("#txtBunkerReplFWMT").text(dataNya.bunkerReplFWMT);
		$("#txtBunkerReplOWST").text(dataNya.bunkerReplOWST);
		$("#txtBunkerAtdHSFO").text(dataNya.bunkerRobAtdHSFO);
		$("#txtBunkerAtdLSFO").text(dataNya.bunkerRobAtdLSFO);
		$("#txtBunkerAtdMDO").text(dataNya.bunkerRobAtdMDO);
		$("#txtBunkerAtdHSD").text(dataNya.bunkerRobAtdHSD);
		$("#txtBunkerAtdFWMT").text(dataNya.bunkerRobAtdFWMT);
		$("#txtBunkerAtdOWST").text(dataNya.bunkerRobAtdOWST);
		$("#txtDraftFWD").text(dataNya.draftFWD);
		$("#txtDraftAFT").text(dataNya.draftAFT);
		$("#txtDraftMEAN").text(dataNya.draftMean);
		$("#txtArrivalEtaDate").text(dataNya.arrivalETADate);
		$("#txtArrivalEtaHour").text(dataNya.arrivalETATime);
		$("#txtArrivalEtaPort").text(dataNya.arrivalETAPort);
		$("#txtArrivalDevDate").text(dataNya.arrivalDeviationDate);
		$("#txtArrivalDevHour").text(dataNya.arrivalDeviationTime);
		$("#txtArrivalDevPort").text(dataNya.arrivalDeviationPort);
		$("#txtVesselDueInstall").text(dataNya.vesselDelayInstallation);
		$("#txtVesselDueVessel").text(dataNya.vesselDelayVessel);
		$("#txtVesselDueAgent").text(dataNya.vesselDelayAgent);
		$("#txtVesselDueOther").text(dataNya.vesselDelayOther);
		$("#txtRemarkDestLoc1").text(dataNya.remarkDestPortLokasi1);
		$("#txtRemarkDestLoc2").text(dataNya.remarkDestPortLokasi2);
		$("#txtRemarkDestLoc3").text(dataNya.remarkDestPortLokasi3);
		$("#txtRemarkDevLoc1").text(dataNya.remarkDevPortLokasi1);
		$("#txtRemarkDevLoc2").text(dataNya.remarkDevPortLokasi2);
		$("#txtRemarkDevLoc3").text(dataNya.remarkDevPortLokasi3);
		$("#txtRemarkInPort").text(dataNya.remarkInPort);
		$("#txtRemarkCurrent").text(dataNya.remarkCurrentPosition);
		$("#txtVesselCapacityHourA").text(dataNya.vesselCapacityAHour);
		$("#txtVesselCapacityKgA").text(dataNya.vesselCapacityAKg);
		$("#txtVesselCapacityHourB").text(dataNya.vesselCapacityBHour);
		$("#txtVesselCapacityKgB").text(dataNya.vesselCapacityBKg);
		$("#txtVesselCapacityHourC").text(dataNya.vesselCapacityCHour);
		$("#txtVesselCapacityKgC").text(dataNya.vesselCapacityCKg);
		$("#txtVesselCapacityHourD").text(dataNya.vesselCapacityDHour);
		$("#txtVesselCapacityKgD").text(dataNya.vesselCapacityDKg);
		$("#txtShoreCapacityHourA").text(dataNya.shoreCapacityAHour);
		$("#txtShoreCapacityKgA").text(dataNya.shoreCapacityAKg);
		$("#txtShoreCapacityHourB").text(dataNya.shoreCapacityBHour);
		$("#txtShoreCapacityKgB").text(dataNya.shoreCapacityBKg);
		$("#txtShoreCapacityHourC").text(dataNya.shoreCapacityCHour);
		$("#txtShoreCapacityKgC").text(dataNya.shoreCapacityCKg);
		$("#txtShoreCapacityHourD").text(dataNya.shoreCapacityDHour);
		$("#txtShoreCapacityKgD").text(dataNya.shoreCapacityDKg);
		$("#txtLoadingCapacityHourA").text(dataNya.loadingAHour);
		$("#txtLoadingCapacityKgA").text(dataNya.loadingAKg);
		$("#txtLoadingCapacityHourB").text(dataNya.loadingBHour);
		$("#txtLoadingCapacityKgB").text(dataNya.loadingBKg);
		$("#txtLoadingCapacityHourC").text(dataNya.loadingCHour);
		$("#txtLoadingCapacityKgC").text(dataNya.loadingCKg);
		$("#txtLoadingCapacityHourD").text(dataNya.loadingDHour);
		$("#txtLoadingCapacityKgD").text(dataNya.loadingDKg);
		$("#txtAverageCapacityHourA").text(dataNya.averageAHour);
		$("#txtAverageCapacityKgA").text(dataNya.averageAKg);
		$("#txtAverageCapacityHourB").text(dataNya.averageBHour);
		$("#txtAverageCapacityKgB").text(dataNya.averageBKg);
		$("#txtAverageCapacityHourC").text(dataNya.averageCHour);
		$("#txtAverageCapacityKgC").text(dataNya.averageCKg);
		$("#txtAverageCapacityHourD").text(dataNya.averageDHour);
		$("#txtAverageCapacityKgD").text(dataNya.averageDKg);
	}
	function onClickView(id)
	{
		$.post('../shipManagement/class/actionNav.php',
    	{ actionGetDataUploadOilById : id},
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
			<input type="file" name="fileOil" id="fileOil" class="btnStandar" style="width:200px;height:29px; margin: 10px;float: left;" title="Only File .xls">
			<input type="text" name="txtSheet" value="" id="txtSheet" class="elementSearch" placeholder="No Sheet" style="width: 70px; margin: 10px; float: left;">
			<button class="btnStandar" id="btnView" style="width:100px;height:29px; margin: 10px; float: left;" disabled="disabled" title="View Data">
	        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
	            	<tr>
	                	<td align="right" width="25"><img src="../picture/Search-blue-32.png" height="20"/> </td>
	                    <td align="center">View</td>
	              	</tr>
	         	</table>
	     	</button>
    	</div>
    	<div id="idNavSearch">
    		<input type="text" name="txtSdate" id="txtSdate" class="elementSearch" style="width: 10%;margin: 10px;float: left;" placeholder="Start Upload">
    		<input type="text" name="txtEdate" id="txtEdate" class="elementSearch" style="width: 10%;margin: 10px;float: left;" placeholder="End Upload">
    		<input type="text" name="txtSearchVoyageNo" id="txtSearchVoyageNo" class="elementSearch" style="width: 10%;margin: 10px;float: left;" placeholder="Voyage No">
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
					<td style="width: 10%;" align="center">VOYAGE NO</td>
					<td style="width: 10%;" align="center">POSITION</td>
					<td style="width: 15%;" align="center">IN PORT</td>
					<td style="width: 10%;" align="center">DATE UPLOAD</td>
				</tr>
			</thead>
			<tbody style="font-size: 11px;cursor: pointer;" id="tblIdBody">
				{getDataUploadOil}
			</tbody>
		</table>
	</div>
	<div class="viewExcel" style="width:100%;min-height:300px;overflow:scroll;" align="center">
		<fieldset>
			<legend style="padding: 10px;"><h3 style="font:1em sans-serif;font-weight:100;color:#485a88;">View Data File Upload</h3></legend>
			<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tabelBorderTopJust" style="font:0.9em sans-serif;font-weight:100;color:#485a88;border-style:dotted;padding: 10px;">
				<tr>
	                <td width="220" height="22" class="tabelBorderBottomJust" style="border-style:dotted;color: #000;">NAME OF MASTER</td>
	                <td class="tabelBorderBottomJust elementTeks" colspan="6" id="txtNameMaster"></td>
	            </tr>
	            <tr>
	                <td width="220" height="22" class="tabelBorderBottomJust" style="border-style:dotted;color: #000;">SATELLITE TELEPHONE</td>
	                <td class="tabelBorderBottomJust elementTeks" colspan="6" id="txtSatNo"></td>
	            </tr>
	            <tr>
	                <td width="220" height="22" class="tabelBorderBottomJust" style="border-style:dotted;color: #000;">SATELLITE EMAIL</td>
	                <td class="tabelBorderBottomJust elementTeks" colspan="6" id="txtSatEmail"></td>
	            </tr>
	           <!--  <tr>
	                <td width="220" height="22" class="tabelBorderBottomJust" style="border-style:dotted;color: #000;">NOON POSITION REPORT OF</td>
	                <td class="tabelBorderBottomJust elementTeks" colspan="4" id="txtNoonPosition"></td>
	            </tr> -->
	            <tr>
	                <td width="220" height="22" class="tabelBorderBottomJust" style="border-style:dotted;color: #000;">VOYAGE NO</td>
	                <td class="tabelBorderBottomJust elementTeks" width="150" id="txtVoyageNo"></td>
	                <td width="100" height="22" align="center" style="color: #000;">Position</td>
	                <td class="tabelBorderBottomJust elementTeks" colspan="2" id="txtOilPosition"></td>
	                <td width="100" height="22" align="center" style="color: #000;">IN PORT</td>
	                <td class="tabelBorderBottomJust elementTeks" colspan="2" id="txtInPOrt"></td>
	            </tr>
	            
			</table>
			<table border="1" cellpadding="0" cellspacing="0" width="100%" class="tabelBorderAll" style="font:0.9em sans-serif;font-weight:100;color:#485a88;border-style:dotted;padding: 10px;">
	            <tr style="background-color: #bdbdbd;">
	            	<td height="22" style="color: #000;width: 10%;" align="center" class="tabelBorderAll">CODE</td>
	            	<td height="22" style="color: #000;width: 10%;" align="center" class="tabelBorderAll">DATE</td>
	            	<td height="22" style="color: #000;width: 10%;" align="center" class="tabelBorderAll">TIME</td>
	            	<td class="tabelBorderAll" colspan="4"></td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" align="center" style="background-color: #e4e4e4;">AA</td>
	            	<td height="22" class="tabelBorderAll" align="center" id="txtAADate"></td>
	            	<td height="22" class="tabelBorderAll" align="center" id="txtAATime"></td>
	            	<td height="22" class="tabelBorderAll" style="width:25%;background-color: #e4e4e4;"> &nbsp ATD AT PREVIOUS PORT</td>
	            	<td height="22" class="tabelBorderAll" style="width:20%;background-color: #e4e4e4;" align="right">PREVIOUS PORT</td>
	            	<td height="22" class="tabelBorderAll" colspan="2" id="txtPreviousPort"></td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" align="right" colspan="5" style="background-color: #e4e4e4;"><i>ACTUAL DISTANCE FROM "PREVIOUS PORT" TO "IN PORT"</i></td>
	            	<td height="22" class="tabelBorderAll" align="center" id="txtActualDist"></td>
	            	<td height="22" class="tabelBorderAll" style="width:7%;background-color: #e4e4e4;">&nbsp NMILE</td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" align="center" style="background-color: #e4e4e4;">BB</td>
	            	<td height="22" class="tabelBorderAll" align="center" id="txtBBDate"></td>
	            	<td height="22" class="tabelBorderAll" align="center" id="txtBBTime"></td>
	            	<td height="22" class="tabelBorderAll" colspan="4" style="width:25%;background-color: #e4e4e4;">&nbsp ATA OUTER BAR (SBE) STAND BY ENGINE / (EOSV) END OF SEA VOYAGE</td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" align="center" style="background-color: #e4e4e4;">CC</td>
	            	<td height="22" class="tabelBorderAll" align="center" id="txtCCDate"></td>
	            	<td height="22" class="tabelBorderAll" align="center" id="txtCCTime"></td>
	            	<td height="22" class="tabelBorderAll" colspan="4" style="width:25%;background-color: #e4e4e4;">&nbsp ATA AT INNER ROAD (ANCHORED)</td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" align="center" style="background-color: #e4e4e4;">DD</td>
	            	<td height="22" class="tabelBorderAll" align="center" id="txtDDDate"></td>
	            	<td height="22" class="tabelBorderAll" align="center" id="txtDDTime"></td>
	            	<td height="22" class="tabelBorderAll" colspan="4" style="width:25%;background-color: #e4e4e4;">&nbsp BERTHED JETTY / CBM / SPM (ALL FAST)</td>
	            </tr>
			</table>
			<table border="1" cellpadding="0" cellspacing="0" width="100%" class="tabelBorderAll" style="font:0.9em sans-serif;font-weight:100;color:#485a88;border-style:dotted;padding: 10px;">
	            <tr>
	            	<td height="22" class="tabelBorderAll" align="right" style="width:25%;background-color: #e4e4e4;">GRADE A &nbsp</td>
	            	<td height="22" class="tabelBorderAll" id="txtGradeA"></td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" align="right" style="width:25%;background-color: #e4e4e4;">GRADE B &nbsp</td>
	            	<td height="22" class="tabelBorderAll" id="txtGradeB"></td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" align="right" style="width:25%;background-color: #e4e4e4;">GRADE C &nbsp</td>
	            	<td height="22" class="tabelBorderAll" id="txtGradeC"></td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" align="right" style="width:25%;background-color: #e4e4e4;">GRADE D &nbsp</td>
	            	<td height="22" class="tabelBorderAll" id="txtGradeD"></td>
	            </tr>
			</table>
			<table border="1" cellpadding="0" cellspacing="0" width="100%" class="tabelBorderAll" style="font:0.9em sans-serif;font-weight:100;color:#485a88;border-style:dotted;padding: 10px;">
				<tr style="background-color: #bdbdbd;">
	                <td height="22" class="tabelBorderAll" align="center" colspan="4" style="color: #000;font-weight: bold;">OPERATION OF CARGO GRADE A  (CODE EE - HH)</td>
	            </tr>
	            <tr style="background-color: #bdbdbd;">
	            	<td height="22" style="color: #000;width: 10%;" align="center" class="tabelBorderAll">DATE</td>
	            	<td height="22" style="color: #000;width: 7%;" align="center" class="tabelBorderAll">TIME</td>
	            	<td height="22" style="color: #000;width: 53%;" align="center" class="tabelBorderAll">ACTIVITY</td>
	            	<td height="22" style="color: #000;width: 30%;" align="center" class="tabelBorderAll">NOTE</td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" id="txtConnADate" align="center"></td>
	            	<td height="22" class="tabelBorderAll" id="txtConnATime" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;" >HOSE CONNECTED FOR <label id="idTypeNyaA1"></label> GRADE A</td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;"></td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" id="txtStartA1Date" align="center"></td>
	            	<td height="22" class="tabelBorderAll" id="txtStartA1Time" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;" >START <label id="idTypeNyaA2"></label> GRADE A (1)</td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;"></td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" id="txtStopA1Date" align="center"></td>
	            	<td height="22" class="tabelBorderAll" id="txtStopA1Time" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;" >STOP <label id="idTypeNyaA3"></label> GRADE A (1)</td>
	            	<td height="22" class="tabelBorderAll" id="txtNoteGradeA1"></td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" id="txtStartA2Date" align="center"></td>
	            	<td height="22" class="tabelBorderAll" id="txtStartA2Time" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;" >START <label id="idTypeNyaA4"></label> GRADE A (2)</td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;"></td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" id="txtStopA2Date" align="center"></td>
	            	<td height="22" class="tabelBorderAll" id="txtStopA2Time" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;" >STOP <label id="idTypeNyaA5"></label> GRADE A (2)</td>
	            	<td height="22" class="tabelBorderAll" id="txtNoteGradeA2"></td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" id="txtStartA3Date" align="center"></td>
	            	<td height="22" class="tabelBorderAll" id="txtStartA3Time" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;" >START <label id="idTypeNyaA6"></label> GRADE A (3)</td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;"></td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" id="txtStopA3Date" align="center"></td>
	            	<td height="22" class="tabelBorderAll" id="txtStopA3Time" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;" >STOP <label id="idTypeNyaA7"></label> A (3)</td>
	            	<td height="22" class="tabelBorderAll" id="txtNoteGradeA3"></td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" id="txtDisConnADate" align="center"></td>
	            	<td height="22" class="tabelBorderAll" id="txtDisConnATime" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;" >HOSE DISCONNECTED AFTER <label id="idTypeNyaA8"></label> GRADE A</td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;"></td>
	            </tr>
			</table>
			<table border="1" cellpadding="0" cellspacing="0" width="100%" class="tabelBorderAll" style="font:0.9em sans-serif;font-weight:100;color:#485a88;border-style:dotted;padding: 10px;">
				<tr style="background-color: #bdbdbd;">
	                <td height="22" class="tabelBorderAll" align="center" colspan="4" style="color: #000;font-weight: bold;">OPERATION OF CARGO GRADE B  (CODE EE - HH)</td>
	            </tr>
	            <tr style="background-color: #bdbdbd;">
	            	<td height="22" style="color: #000;width: 10%;" align="center" class="tabelBorderAll">DATE</td>
	            	<td height="22" style="color: #000;width: 7%;" align="center" class="tabelBorderAll">TIME</td>
	            	<td height="22" style="color: #000;width: 53%;" align="center" class="tabelBorderAll">ACTIVITY</td>
	            	<td height="22" style="color: #000;width: 30%;" align="center" class="tabelBorderAll">NOTE</td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" id="txtConnBDate" align="center"></td>
	            	<td height="22" class="tabelBorderAll" id="txtConnBTime" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;" >HOSE CONNECTED FOR <label id="idTypeNyaB1"></label> GRADE B</td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;"></td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" id="txtStartB1Date" align="center"></td>
	            	<td height="22" class="tabelBorderAll" id="txtStartB1Time" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;" >START <label id="idTypeNyaB2"></label> GRADE B (1)</td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;"></td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" id="txtStopB1Date" align="center"></td>
	            	<td height="22" class="tabelBorderAll" id="txtStopB1Time" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;" >STOP <label id="idTypeNyaB3"></label> GRADE B (1)</td>
	            	<td height="22" class="tabelBorderAll" id="txtNoteGradeB1"></td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" id="txtStartB2Date" align="center"></td>
	            	<td height="22" class="tabelBorderAll" id="txtStartB2Time" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;" >START <label id="idTypeNyaB4"></label> GRADE B (2)</td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;"></td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" id="txtStopB2Date" align="center"></td>
	            	<td height="22" class="tabelBorderAll" id="txtStopB2Time" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;" >STOP <label id="idTypeNyaB5"></label> GRADE B (2)</td>
	            	<td height="22" class="tabelBorderAll" id="txtNoteGradeB2"></td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" id="txtStartB3Date" align="center"></td>
	            	<td height="22" class="tabelBorderAll" id="txtStartB3Time" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;" >START <label id="idTypeNyaB6"></label> GRADE B (3)</td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;"></td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" id="txtStopB3Date" align="center"></td>
	            	<td height="22" class="tabelBorderAll" id="txtStopB3Time" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;" >STOP <label id="idTypeNyaB7"></label> GRADE B (3)</td>
	            	<td height="22" class="tabelBorderAll" id="txtNoteGradeB3"></td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" id="txtDisConnBDate" align="center"></td>
	            	<td height="22" class="tabelBorderAll" id="txtDisConnBTime" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;" >HOSE DISCONNECTED AFTER <label id="idTypeNyaB8"></label> GRADE B</td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;"></td>
	            </tr>
			</table>
			<table border="1" cellpadding="0" cellspacing="0" width="100%" class="tabelBorderAll" style="font:0.9em sans-serif;font-weight:100;color:#485a88;border-style:dotted;padding: 10px;">
				<tr style="background-color: #bdbdbd;">
	                <td height="22" class="tabelBorderAll" align="center" colspan="4" style="color: #000;font-weight: bold;">OPERATION OF CARGO GRADE C  (CODE EE - HH)</td>
	            </tr>
	            <tr style="background-color: #bdbdbd;">
	            	<td height="22" style="color: #000;width: 10%;" align="center" class="tabelBorderAll">DATE</td>
	            	<td height="22" style="color: #000;width: 7%;" align="center" class="tabelBorderAll">TIME</td>
	            	<td height="22" style="color: #000;width: 53%;" align="center" class="tabelBorderAll">ACTIVITY</td>
	            	<td height="22" style="color: #000;width: 30%;" align="center" class="tabelBorderAll">NOTE</td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" id="txtConnCDate" align="center"></td>
	            	<td height="22" class="tabelBorderAll" id="txtConnCTime" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;" >HOSE CONNECTED FOR <label id="idTypeNyaC1"></label> GRADE C</td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;"></td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" id="txtStartC1Date" align="center"></td>
	            	<td height="22" class="tabelBorderAll" id="txtStartC1Time" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;" >START <label id="idTypeNyaC2"></label> GRADE C (1)</td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;"></td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" id="txtStopC1Date" align="center"></td>
	            	<td height="22" class="tabelBorderAll" id="txtStopC1Time" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;" >STOP <label id="idTypeNyaC3"></label> GRADE C (1)</td>
	            	<td height="22" class="tabelBorderAll" id="txtNoteGradeC1"></td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" id="txtStartC2Date" align="center"></td>
	            	<td height="22" class="tabelBorderAll" id="txtStartC2Time" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;" >START <label id="idTypeNyaC4"></label> GRADE C (2)</td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;"></td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" id="txtStopC2Date" align="center"></td>
	            	<td height="22" class="tabelBorderAll" id="txtStopC2Time" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;" >STOP <label id="idTypeNyaC5"></label> GRADE C (2)</td>
	            	<td height="22" class="tabelBorderAll" id="txtNoteGradeC2" ></td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" id="txtStartC3Date" align="center"></td>
	            	<td height="22" class="tabelBorderAll" id="txtStartC3Time" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;" >START <label id="idTypeNyaC6"></label> GRADE C (3)</td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;"></td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" id="txtStopC3Date" align="center"></td>
	            	<td height="22" class="tabelBorderAll" id="txtStopC3Time" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;" >STOP <label id="idTypeNyaC7"></label> GRADE C (3)</td>
	            	<td height="22" class="tabelBorderAll" id="txtNoteGradeC3"></td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" id="txtDisConnCDate" align="center"></td>
	            	<td height="22" class="tabelBorderAll" id="txtDisConnCTime" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;" >HOSE DISCONNECTED AFTER <label id="idTypeNyaC8"></label> GRADE C</td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;"></td>
	            </tr>
			</table>
			<table border="1" cellpadding="0" cellspacing="0" width="100%" class="tabelBorderAll" style="font:0.9em sans-serif;font-weight:100;color:#485a88;border-style:dotted;padding: 10px;">
				<tr style="background-color: #bdbdbd;">
	                <td height="22" class="tabelBorderAll" align="center" colspan="4" style="color: #000;font-weight: bold;">OPERATION OF CARGO GRADE D  (CODE EE - HH)</td>
	            </tr>
	            <tr style="background-color: #bdbdbd;">
	            	<td height="22" style="color: #000;width: 10%;" align="center" class="tabelBorderAll">DATE</td>
	            	<td height="22" style="color: #000;width: 7%;" align="center" class="tabelBorderAll">TIME</td>
	            	<td height="22" style="color: #000;width: 53%;" align="center" class="tabelBorderAll">ACTIVITY</td>
	            	<td height="22" style="color: #000;width: 30%;" align="center" class="tabelBorderAll">NOTE</td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" id="txtConnDDate" align="center"></td>
	            	<td height="22" class="tabelBorderAll" id="txtConnDTime" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;" >HOSE CONNECTED FOR LOADING / DISCHARGE GRADE D</td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;"></td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" id="txtStartD1Date" align="center"></td>
	            	<td height="22" class="tabelBorderAll" id="txtStartD1Time" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;" >START LOADING / DISCHARGE GRADE D (1)</td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;"></td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" id="txtStopD1Date" align="center"></td>
	            	<td height="22" class="tabelBorderAll" id="txtStopD1Time" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;" >STOP LOADING / DISCHARGE GRADE D (1)</td>
	            	<td height="22" class="tabelBorderAll" id="txtNoteGradeD1"></td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" id="txtStartD2Date" align="center"></td>
	            	<td height="22" class="tabelBorderAll" id="txtStartD2Time" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;" >START LOADING / DISCHARGE GRADE D (2)</td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;"></td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" id="txtStopD2Date" align="center"></td>
	            	<td height="22" class="tabelBorderAll" id="txtStopD2Time" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;" >STOP LOADING / DISCHARGE GRADE D (2)</td>
	            	<td height="22" class="tabelBorderAll" id="txtNoteGradeD2"></td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" id="txtStartD3Date" align="center"></td>
	            	<td height="22" class="tabelBorderAll" id="txtStartD3Time" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;" >START LOADING / DISCHARGE GRADE D (3)</td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;"></td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" id="txtStopD3Date" align="center"></td>
	            	<td height="22" class="tabelBorderAll" id="txtStopD3Time" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;" >STOP LOADING / DISCHARGE GRADE D (3)</td>
	            	<td height="22" class="tabelBorderAll" id="txtNoteGradeD3"></td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" id="txtDisConnDDate" align="center"></td>
	            	<td height="22" class="tabelBorderAll" id="txtDisConnDTime" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;" >HOSE DISCONNECTED AFTER LOADING / DISCHARGE GRADE D</td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;"></td>
	            </tr>
			</table>
			<table border="1" cellpadding="0" cellspacing="0" width="100%" class="tabelBorderAll" style="font:0.9em sans-serif;font-weight:100;color:#485a88;border-style:dotted;padding: 10px;">				
	            <tr style="background-color: #bdbdbd;">
	            	<td height="22" style="color: #000;width: 10%;" align="center" class="tabelBorderAll">CODE</td>
	            	<td height="22" style="color: #000;width: 10%;" align="center" class="tabelBorderAll">DATE</td>
	            	<td height="22" style="color: #000;width: 10%;" align="center" class="tabelBorderAll">TIME</td>
	            	<td height="22" style="color: #000;width: 70%;" align="center" class="tabelBorderAll"></td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" align="center" style="background-color:#e4e4e4;">II</td>
	            	<td height="22" class="tabelBorderAll" id="unberthedDate"></td>
	            	<td height="22" class="tabelBorderAll" id="unberthedTimes" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color:#e4e4e4;">UNBERTHED JETTY / CBM / SPM</td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" align="center" style="background-color:#e4e4e4;">JJ</td>
	            	<td height="22" class="tabelBorderAll" id="txtAnchorDate"></td>
	            	<td height="22" class="tabelBorderAll" id="txtAnchorTime" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color:#e4e4e4;">ANCHOR AT INNER ROAD</td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" align="center" style="background-color:#e4e4e4;">KK</td>
	            	<td height="22" class="tabelBorderAll" id="txtActualLineDate"></td>
	            	<td height="22" class="tabelBorderAll" id="txtActualLineTime" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color:#e4e4e4;">ACTUAL LINE DEPARTURE INNER ROAD</td>
	            </tr>
	            <tr>
	            	<td height="22" class="tabelBorderAll" align="center" style="background-color:#e4e4e4;">LL</td>
	            	<td height="22" class="tabelBorderAll" id="txtAtdOuterDate"></td>
	            	<td height="22" class="tabelBorderAll" id="txtAtdOuterTimes" align="center"></td>
	            	<td height="22" class="tabelBorderAll" style="background-color: #e4e4e4;" id="txtNoteGradeD3">ATD OUTER BAR (FA) FULL AWAY/ (BOSV) BEGIN OF SEA VOYAGE</td>
	            </tr>
			</table>
			<table border="1" cellpadding="0" cellspacing="0" width="100%" class="tabelBorderAll" style="font:0.9em sans-serif;font-weight:100;color:#485a88;border-style:dotted;padding: 10px;">				
	            <tr style="background-color: #bdbdbd;font-size: 12px;">
	            	<td height="22" style="color:#000;" colspan="3" align="center" class="tabelBorderAll">AT LOADING PORT</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" class="tabelBorderAll">GRADE NAME</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" class="tabelBorderAll">DOC NUMBER</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" class="tabelBorderAll">DATE</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" class="tabelBorderAll">KL Obs</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" class="tabelBorderAll">KL 15oC</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" class="tabelBorderAll">BBLS 60oF</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" class="tabelBorderAll">MT</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" class="tabelBorderAll">LT</td>
	            </tr>   
	            <tr style="font-size: 11px;">
	            	<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">MMA</td>
	            	<td height="22" style="color:#000;width:28%;background-color: #e4e4e4;" colspan="2" class="tabelBorderAll">&nbsp BILL OF LADING GRADE A</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtBolGradeNameA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtBolGradeDocNoA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtBolGradeDateA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtBolGradeKLObsA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtBolGradeKl15oCA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtBolGradeBblsA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtBolGradeMtA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtBolGradeLtA" class="tabelBorderAll"></td>
	            </tr>
	            <tr style="font-size: 11px;">
	            	<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">MMB</td>
	            	<td height="22" style="color:#000;width:28%;background-color: #e4e4e4;" colspan="2" class="tabelBorderAll">&nbsp BILL OF LADING GRADE B</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtBolGradeNameB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtBolGradeDocNoB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtBolGradeDateB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtBolGradeKLObsB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtBolGradeKl15oCB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtBolGradeBblsB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtBolGradeMtB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtBolGradeLtB" class="tabelBorderAll"></td>
	            </tr>
	            <tr style="font-size: 11px;">
	            	<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">MMC</td>
	            	<td height="22" style="color:#000;width:28%;background-color: #e4e4e4;" colspan="2" class="tabelBorderAll">&nbsp BILL OF LADING GRADE C</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtBolGradeNameC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtBolGradeDocNoC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtBolGradeDateC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtBolGradeKLObsC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtBolGradeKl15oCC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtBolGradeBblsC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtBolGradeMtC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtBolGradeLtC" class="tabelBorderAll"></td>
	            </tr>
	            <tr style="font-size: 11px;">
	            	<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">MMD</td>
	            	<td height="22" style="color:#000;width:28%;background-color: #e4e4e4;" colspan="2" class="tabelBorderAll">&nbsp BILL OF LADING GRADE D</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtBolGradeNameD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtBolGradeDocNoD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtBolGradeDateD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtBolGradeKLObsD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtBolGradeKl15oCD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtBolGradeBblsD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtBolGradeMtD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtBolGradeLtD" class="tabelBorderAll"></td>
	            </tr>
	            <tr style="font-size: 11px;">
	            	<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">MNA</td>
	            	<td height="22" style="color:#000;width:28%;background-color: #e4e4e4;" colspan="2" class="tabelBorderAll">&nbspSHIP'S FIGURE AFTER LOADING (SFAL) GRADE A</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFALGradeNameA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFALGradeDocNoA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFALGradeDateA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFALGradeKLObsA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFALGradeKl15oCA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFALGradeBblsA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFALGradeMtA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFALGradeLtA" class="tabelBorderAll"></td>
	            </tr>
	            <tr style="font-size: 11px;">
	            	<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">MNB</td>
	            	<td height="22" style="color:#000;width:28%;background-color: #e4e4e4;" colspan="2" class="tabelBorderAll">&nbspSHIP'S FIGURE AFTER LOADING (SFAL) GRADE B</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFALGradeNameB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFALGradeDocNoB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFALGradeDateB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFALGradeKLObsB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFALGradeKl15oCB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFALGradeBblsB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFALGradeMtB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFALGradeLtB" class="tabelBorderAll"></td>
	            </tr>
	            <tr style="font-size: 11px;">
	            	<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">MNC</td>
	            	<td height="22" style="color:#000;width:28%;background-color: #e4e4e4;" colspan="2" class="tabelBorderAll">&nbspSHIP'S FIGURE AFTER LOADING (SFAL) GRADE C</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFALGradeNameC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFALGradeDocNoC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFALGradeDateC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFALGradeKLObsC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFALGradeKl15oCC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFALGradeBblsC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFALGradeMtC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFALGradeLtC" class="tabelBorderAll"></td>
	            </tr>
	            <tr style="font-size: 11px;">
	            	<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">MND</td>
	            	<td height="22" style="color:#000;width:28%;background-color: #e4e4e4;" colspan="2" class="tabelBorderAll">&nbspSHIP'S FIGURE AFTER LOADING (SFAL) GRADE D</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFALGradeNameD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFALGradeDocNoD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFALGradeDateD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFALGradeKLObsD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFALGradeKl15oCD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFALGradeBblsD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFALGradeMtD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFALGradeLtD" class="tabelBorderAll"></td>
	            </tr>
	            <tr style="font-size: 11px;">
	            	<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">MN1A</td>
	            	<td height="22" style="color:#000;width:28%;background-color: #e4e4e4;" colspan="2" class="tabelBorderAll">&nbspSHIP'S FIGURE BEFORE LOADING (SFBL) GRADE A</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBLGradeNameA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBLGradeDocNoA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBLGradeDateA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBLGradeKLObsA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBLGradeKl15oCA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBLGradeBblsA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBLGradeMtA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBLGradeLtA" class="tabelBorderAll"></td>
	            </tr>
	            <tr style="font-size: 11px;">
	            	<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">MN1B</td>
	            	<td height="22" style="color:#000;width:28%;background-color: #e4e4e4;" colspan="2" class="tabelBorderAll">&nbspSHIP'S FIGURE BEFORE LOADING (SFBL) GRADE B</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBLGradeNameB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBLGradeDocNoB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBLGradeDateB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBLGradeKLObsB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBLGradeKl15oCB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBLGradeBblsB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBLGradeMtB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBLGradeLtB" class="tabelBorderAll"></td>
	            </tr>
	            <tr style="font-size: 11px;">
	            	<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">MN1C</td>
	            	<td height="22" style="color:#000;width:28%;background-color: #e4e4e4;" colspan="2" class="tabelBorderAll">&nbspSHIP'S FIGURE BEFORE LOADING (SFBL) GRADE C</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBLGradeNameC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBLGradeDocNoC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBLGradeDateC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBLGradeKLObsC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBLGradeKl15oCC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBLGradeBblsC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBLGradeMtC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBLGradeLtC" class="tabelBorderAll"></td>
	            </tr>
	            <tr style="font-size: 11px;">
	            	<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">MN1D</td>
	            	<td height="22" style="color:#000;width:28%;background-color: #e4e4e4;" colspan="2" class="tabelBorderAll">&nbspSHIP'S FIGURE BEFORE LOADING (SFBL) GRADE D</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBLGradeNameD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBLGradeDocNoD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBLGradeDateD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBLGradeKLObsD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBLGradeKl15oCD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBLGradeBblsD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBLGradeMtD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBLGradeLtD" class="tabelBorderAll"></td>
	            </tr>
			</table>
			<table border="1" cellpadding="0" cellspacing="0" width="100%" class="tabelBorderAll" style="font:0.9em sans-serif;font-weight:100;color:#485a88;border-style:dotted;padding: 10px;">
	            <tr style="background-color: #bdbdbd;font-size: 12px;">
	            	<td height="22" style="color:#000;" colspan="3" align="center" class="tabelBorderAll">AT DISCHARGING PORT</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" class="tabelBorderAll">GRADE NAME</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" class="tabelBorderAll">DOC NUMBER</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" class="tabelBorderAll">DATE</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" class="tabelBorderAll">KL Obs</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" class="tabelBorderAll">KL 15oC</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" class="tabelBorderAll">BBLS 60oF</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" class="tabelBorderAll">MT</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" class="tabelBorderAll">LT</td>
	            </tr>   
	            <tr style="font-size: 11px;">
	            	<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">MMA</td>
	            	<td height="22" style="color:#000;width:29%;background-color: #e4e4e4;" colspan="2" class="tabelBorderAll">&nbsp NEW BILL OF LADING GRADE A</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtNewBolGradeNameA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtNewBolGradeDocNoA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtNewBolGradeDateA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtNewBolGradeKLObsA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtNewBolGradeKl15oCA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtNewBolGradeBblsA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtNewBolGradeMtA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtNewBolGradeLtA" class="tabelBorderAll"></td>
	            </tr>
	            <tr style="font-size: 11px;">
	            	<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">MMB</td>
	            	<td height="22" style="color:#000;width:29%;background-color: #e4e4e4;" colspan="2" class="tabelBorderAll">&nbsp NEW BILL OF LADING GRADE B</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtNewBolGradeNameB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtNewBolGradeDocNoB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtNewBolGradeDateB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtNewBolGradeKLObsB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtNewBolGradeKl15oCB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtNewBolGradeBblsB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtNewBolGradeMtB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtNewBolGradeLtB" class="tabelBorderAll"></td>
	            </tr>
	            <tr style="font-size: 11px;">
	            	<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">MMC</td>
	            	<td height="22" style="color:#000;width:29%;background-color: #e4e4e4;" colspan="2" class="tabelBorderAll">&nbsp NEW BILL OF LADING GRADE C</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtNewBolGradeNameC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtNewBolGradeDocNoC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtNewBolGradeDateC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtNewBolGradeKLObsC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtNewBolGradeKl15oCC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtNewBolGradeBblsC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtNewBolGradeMtC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtNewBolGradeLtC" class="tabelBorderAll"></td>
	            </tr>
	            <tr style="font-size: 11px;">
	            	<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">MMD</td>
	            	<td height="22" style="color:#000;width:29%;background-color: #e4e4e4;" colspan="2" class="tabelBorderAll">&nbsp NEW BILL OF LADING GRADE D</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtNewBolGradeNameD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtNewBolGradeDocNoD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtNewBolGradeDateD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtNewBolGradeKLObsD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtNewBolGradeKl15oCD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtNewBolGradeBblsD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtNewBolGradeMtD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtNewBolGradeLtD" class="tabelBorderAll"></td>
	            </tr>
	            <tr style="font-size: 11px;">
	            	<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">MNA</td>
	            	<td height="22" style="color:#000;width:29%;background-color: #e4e4e4;" colspan="2" class="tabelBorderAll">&nbsp SHIP'S FIGURE BEFORE DISCHARGE (SFBD) GRADE A</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBDGradeNameA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBDGradeDocNoA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBDGradeDateA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBDGradeKLObsA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBDGradeKl15oCA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBDGradeBblsA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBDGradeMtA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBDGradeLtA" class="tabelBorderAll"></td>
	            </tr>
	            <tr style="font-size: 11px;">
	            	<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">MNB</td>
	            	<td height="22" style="color:#000;width:29%;background-color: #e4e4e4;" colspan="2" class="tabelBorderAll">&nbsp SHIP'S FIGURE BEFORE DISCHARGE (SFBD) GRADE B</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBDGradeNameB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBDGradeDocNoB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBDGradeDateB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBDGradeKLObsB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBDGradeKl15oCB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBDGradeBblsB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBDGradeMtB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBDGradeLtB" class="tabelBorderAll"></td>
	            </tr>
	            <tr style="font-size: 11px;">
	            	<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">MNC</td>
	            	<td height="22" style="color:#000;width:29%;background-color: #e4e4e4;" colspan="2" class="tabelBorderAll">&nbsp SHIP'S FIGURE BEFORE DISCHARGE (SFBD) GRADE C</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBDGradeNameC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBDGradeDocNoC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBDGradeDateC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBDGradeKLObsC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBDGradeKl15oCC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBDGradeBblsC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBDGradeMtC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBDGradeLtC" class="tabelBorderAll"></td>
	            </tr>
	            <tr style="font-size: 11px;">
	            	<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">MND</td>
	            	<td height="22" style="color:#000;width:29%;background-color: #e4e4e4;" colspan="2" class="tabelBorderAll">&nbsp SHIP'S FIGURE BEFORE DISCHARGE (SFBD) GRADE D</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBDGradeNameD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBDGradeDocNoD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBDGradeDateD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBDGradeKLObsD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBDGradeKl15oCD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBDGradeBblsD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBDGradeMtD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFBDGradeLtD" class="tabelBorderAll"></td>
	            </tr>
	            <tr style="font-size: 11px;">
	            	<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">MN1A</td>
	            	<td height="22" style="color:#000;width:29%;background-color: #e4e4e4;" colspan="2" class="tabelBorderAll">&nbsp SHIP'S FIGURE AFTER DISCHARGE (SFAD) GRADE A</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFADGradeNameA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFADGradeDocNoA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFADGradeDateA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFADGradeKLObsA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFADGradeKl15oCA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFADGradeBblsA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFADGradeMtA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFADGradeLtA" class="tabelBorderAll"></td>
	            </tr>
	            <tr style="font-size: 11px;">
	            	<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">MN1B</td>
	            	<td height="22" style="color:#000;width:29%;background-color: #e4e4e4;" colspan="2" class="tabelBorderAll">&nbsp SHIP'S FIGURE AFTER DISCHARGE (SFAD) GRADE B</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFADGradeNameB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFADGradeDocNoB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFADGradeDateB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFADGradeKLObsB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFADGradeKl15oCB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFADGradeBblsB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFADGradeMtB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFADGradeLtB" class="tabelBorderAll"></td>
	            </tr>
	            <tr style="font-size: 11px;">
	            	<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">MN1C</td>
	            	<td height="22" style="color:#000;width:29%;background-color: #e4e4e4;" colspan="2" class="tabelBorderAll">&nbsp SHIP'S FIGURE AFTER DISCHARGE (SFAD) GRADE C</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFADGradeNameC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFADGradeDocNoC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFADGradeDateC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFADGradeKLObsC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFADGradeKl15oCC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFADGradeBblsC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFADGradeMtC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFADGradeLtC" class="tabelBorderAll"></td>
	            </tr>
	            <tr style="font-size: 11px;">
	            	<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">MN1D</td>
	            	<td height="22" style="color:#000;width:29%;background-color: #e4e4e4;" colspan="2" class="tabelBorderAll">&nbsp SHIP'S FIGURE AFTER DISCHARGE (SFAD) GRADE D</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFADGradeNameD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFADGradeDocNoD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFADGradeDateD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFADGradeKLObsD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFADGradeKl15oCD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFADGradeBblsD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFADGradeMtD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtSFADGradeLtD" class="tabelBorderAll"></td>
	            </tr>
	            <tr style="font-size: 11px;">
	            	<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">MN2A</td>
	            	<td height="22" style="color:#000;width:29%;background-color: #e4e4e4;" colspan="2" class="tabelBorderAll">&nbsp SHORE ACTUAL RECEIVED GRADE A</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtShoreGradeNameA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtShoreGradeDocNoA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtShoreGradeDateA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtShoreGradeKLObsA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtShoreGradeKl15oCA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtShoreGradeBblsA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtShoreGradeMtA" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtShoreGradeLtA" class="tabelBorderAll"></td>
	            </tr>
	            <tr style="font-size: 11px;">
	            	<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">MN2B</td>
	            	<td height="22" style="color:#000;width:29%;background-color: #e4e4e4;" colspan="2" class="tabelBorderAll">&nbsp SHORE ACTUAL RECEIVED GRADE B</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtShoreGradeNameB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtShoreGradeDocNoB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtShoreGradeDateB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtShoreGradeKLObsB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtShoreGradeKl15oCB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtShoreGradeBblsB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtShoreGradeMtB" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtShoreGradeLtB" class="tabelBorderAll"></td>
	            </tr>
	            <tr style="font-size: 11px;">
	            	<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">MN2C</td>
	            	<td height="22" style="color:#000;width:29%;background-color: #e4e4e4;" colspan="2" class="tabelBorderAll">&nbsp SHORE ACTUAL RECEIVED GRADE C</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtShoreGradeNameC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtShoreGradeDocNoC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtShoreGradeDateC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtShoreGradeKLObsC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtShoreGradeKl15oCC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtShoreGradeBblsC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtShoreGradeMtC" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtShoreGradeLtC" class="tabelBorderAll"></td>
	            </tr>
	            <tr style="font-size: 11px;">
	            	<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">MN2D</td>
	            	<td height="22" style="color:#000;width:29%;background-color: #e4e4e4;" colspan="2" class="tabelBorderAll">&nbsp SHORE ACTUAL RECEIVED GRADE D</td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtShoreGradeNameD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtShoreGradeDocNoD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtShoreGradeDateD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtShoreGradeKLObsD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtShoreGradeKl15oCD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtShoreGradeBblsD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtShoreGradeMtD" class="tabelBorderAll"></td>
	            	<td height="22" style="color:#000;width:10%;" align="center" id="txtShoreGradeLtD" class="tabelBorderAll"></td>
	            </tr>
			</table>
			<table border="1" cellpadding="0" cellspacing="0" width="100%" class="tabelBorderAll" style="font:0.9em sans-serif;font-weight:100;color:#485a88;border-style:dotted;padding: 10px;">
				<tr style="background-color: #bdbdbd;font-size: 12px;">
					<td height="22" style="color:#000;" colspan="5" align="center" class="tabelBorderAll">
						BUNKER (MT)
					</td>
					<td height="22" style="color:#000;" rowspan="2" align="center" class="tabelBorderAll">
						FW (MT)
					</td>
					<td height="22" style="color:#000;" rowspan="2" align="center" class="tabelBorderAll">
						OW/ST (MT)
					</td>
					<td height="22" style="color:#000;width: 50%;" rowspan="2" align="center" class="tabelBorderAll"></td>
				</tr>
				<tr style="background-color: #bdbdbd;font-size: 12px;">
					<td height="22" style="color:#000;" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;" align="center" class="tabelBorderAll">
						HSFO
					</td>
					<td height="22" style="color:#000;" align="center" class="tabelBorderAll">
						LSFO
					</td>
					<td height="22" style="color:#000;" align="center" class="tabelBorderAll">
						MDO
					</td>
					<td height="22" style="color:#000;" align="center" class="tabelBorderAll">
						HSD
					</td>					
				</tr>
				<tr>
					<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">OO</td>
					<td height="22" style="color:#000;width:10%;" id="txtBunkerAtaHSFO" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:10%;" id="txtBunkerAtaLSFO" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:10%;" id="txtBunkerAtaMDO" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:10%;" id="txtBunkerAtaHSD" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:10%;" id="txtBunkerAtaFWMT" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:10%;" id="txtBunkerAtaOWST" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:10%;background-color: #e4e4e4;" class="tabelBorderAll">&nbsp ROB BUNKER ATA AT OUTER BAR (STAND BY ENGINE)</td>
				</tr>
				<tr>
					<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">PP</td>
					<td height="22" style="color:#000;width:10%;" id="txtBunkerReplHSFO" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:10%;" id="txtBunkerReplLSFO" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:10%;" id="txtBunkerReplMDO" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:10%;" id="txtBunkerReplHSD" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:10%;" id="txtBunkerReplFWMT" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:10%;" id="txtBunkerReplOWST" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:10%;background-color: #e4e4e4;" class="tabelBorderAll">&nbsp REPLENISHMENT (REQ. LIST/ ROB ATA/ FUEL OIL TANK CAPASITY)</td>
				</tr>
				<tr>
					<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">QQ</td>
					<td height="22" style="color:#000;width:10%;" id="txtBunkerAtdHSFO" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:10%;" id="txtBunkerAtdLSFO" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:10%;" id="txtBunkerAtdMDO" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:10%;" id="txtBunkerAtdHSD" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:10%;" id="txtBunkerAtdFWMT" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:10%;" id="txtBunkerAtdOWST" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:10%;background-color: #e4e4e4;" class="tabelBorderAll">&nbsp ROB BUNKER ATD AT OUTER BAR (FULL AWAY)</td>
				</tr>
			</table>
			<table border="1" cellpadding="0" cellspacing="0" width="100%" class="tabelBorderAll" style="font:0.9em sans-serif;font-weight:100;color:#485a88;border-style:dotted;padding: 10px;">
				<tr style="background-color: #bdbdbd;font-size: 12px;">
					<td height="22" style="color:#000;" colspan="4" align="center" class="tabelBorderAll">
						DRAFT
					</td>
					<td height="22" style="color:#000;width: 50%;" rowspan="3" align="center" class="tabelBorderAll"></td>
				</tr>
				<tr style="background-color: #bdbdbd;font-size: 12px;">
					<td height="22" style="color:#000;" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;" align="center" class="tabelBorderAll">
						FWD
					</td>
					<td height="22" style="color:#000;" align="center" class="tabelBorderAll">
						AFT
					</td>
					<td height="22" style="color:#000;" align="center" class="tabelBorderAll">
						MEAN
					</td>
				</tr>
				<tr>
					<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">RR</td>
					<td height="22" style="color:#000;width:10%;" id="txtDraftFWD" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:10%;" id="txtDraftAFT" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:10%;" id="txtDraftMEAN" align="center" class="tabelBorderAll"></td>
				</tr>
			</table>
			<table border="1" cellpadding="0" cellspacing="0" width="100%" class="tabelBorderAll" style="font:0.9em sans-serif;font-weight:100;color:#485a88;border-style:dotted;padding: 10px;">
				<tr style="background-color: #bdbdbd;font-size: 12px;">
					<td height="22" style="color:#000;width: 50%;" colspan="4" align="center" class="tabelBorderAll">
						ARRIVAL ESTIMATION
					</td>
					<td height="22" style="color:#000;width: 60%;" align="center" class="tabelBorderAll"></td>
				</tr>
				<tr>
					<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">SS</td>
					<td height="22" style="color:#000;width:8%;" id="txtArrivalEtaDate" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:5%;" id="txtArrivalEtaHour" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:13%;" id="txtArrivalEtaPort" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:10%;background-color: #e4e4e4;" id="txtDraftMEAN" class="tabelBorderAll">&nbsp ETA DATE/ ETA TIME/ NAME OF PORT</td>
				</tr>
				<tr>
					<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">SS1</td>
					<td height="22" style="color:#000;width:8%;" id="txtArrivalDevDate" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:5%;" id="txtArrivalDevHour" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:13%;" id="txtArrivalDevPort" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:10%;background-color: #e4e4e4;" id="txtDraftMEAN" class="tabelBorderAll">&nbsp IF THERE IS DEVIATION ORDER, FILL WITH ETA DATE/ ETA TIME/ NAME OF DEVIATION PORT</td>
				</tr>
			</table>
			<table border="1" cellpadding="0" cellspacing="0" width="100%" class="tabelBorderAll" style="font:0.9em sans-serif;font-weight:100;color:#485a88;border-style:dotted;padding: 10px;">
				<tr style="background-color: #bdbdbd;font-size: 12px;">
					<td height="22" style="color:#000;width: 50%;" colspan="3" align="center" class="tabelBorderAll">
						VESSEL DELAYS (HOURS)
					</td>
					<td height="22" style="color:#000;width: 60%;" rowspan="5" align="center" class="tabelBorderAll"></td>
				</tr>
				<tr>
					<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">TT</td>
					<td height="22" style="color:#000;width:20%;" id="txtVesselDueInstall" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:30%;background-color: #e4e4e4;" id="txtDraftMEAN" class="tabelBorderAll">&nbsp DUE TO INSTALLATION</td>
				</tr>
				<tr>
					<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">UU</td>
					<td height="22" style="color:#000;width:20%;" id="txtVesselDueVessel" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:30%;background-color: #e4e4e4;" id="txtDraftMEAN" class="tabelBorderAll">&nbsp DUE TO VESSEL</td>
				</tr>
				<tr>
					<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">VV</td>
					<td height="22" style="color:#000;width:20%;" id="txtVesselDueAgent" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:30%;background-color: #e4e4e4;" id="txtDraftMEAN" class="tabelBorderAll">&nbsp DUE TO AGENT</td>
				</tr>
				<tr>
					<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">WW</td>
					<td height="22" style="color:#000;width:20%;" id="txtVesselDueOther" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:30%;background-color: #e4e4e4;" id="txtDraftMEAN" class="tabelBorderAll">&nbsp DUE TO OTHERS</td>
				</tr>
			</table>
			<table border="1" cellpadding="0" cellspacing="0" width="100%" class="tabelBorderAll" style="font:0.9em sans-serif;font-weight:100;color:#485a88;border-style:dotted;padding: 10px;">
				<tr style="background-color: #bdbdbd;font-size: 12px;">
					<td height="22" style="color:#000;" colspan="5" align="center" class="tabelBorderAll">
						REMARK
					</td>
				</tr>
				<tr>
					<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">XX</td>
					<td height="22" style="color:#000;width:10%;" id="txtRemarkDestLoc1" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:10%;" id="txtRemarkDestLoc2" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:10%;" id="txtRemarkDestLoc3" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:30%;background-color: #e4e4e4;" class="tabelBorderAll">&nbsp (SAILING ROUTE TO DESTINATION PORT)</td>
				</tr>
				<tr>
					<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">XX1</td>
					<td height="22" style="color:#000;width:10%;" id="txtRemarkDevLoc1" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:10%;" id="txtRemarkDevLoc2" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:10%;" id="txtRemarkDevLoc3" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:30%;background-color: #e4e4e4;" class="tabelBorderAll">&nbsp (SAILING ROUTE TO DEVIATION PORT)</td>
				</tr>
				<tr>
					<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">YY</td>
					<td height="22" style="color:#000;width:10%;" id="txtRemarkInPort" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:10%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">NMILE</td>
					<td height="22" style="color:#000;width:30%;background-color: #e4e4e4;" colspan="2" class="tabelBorderAll">&nbsp DISTANCE FROM "IN PORT" TO REACH DESTINATION PORT "CODE SS"</td>
				</tr>
				<tr>
					<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">YY1</td>
					<td height="22" style="color:#000;width:10%;" id="txtRemarkCurrent" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:10%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">NMILE</td>
					<td height="22" style="color:#000;width:30%;background-color: #e4e4e4;" colspan="2" class="tabelBorderAll">&nbsp DISTANCE FROM CURRENT POSITION TO REACH DEVIATION PORT "CODE SS1"</td>
				</tr>
			</table>
			<table border="1" cellpadding="0" cellspacing="0" width="100%" class="tabelBorderAll" style="font:0.9em sans-serif;font-weight:100;color:#485a88;border-style:dotted;padding: 10px;">
				<tr style="background-color: #bdbdbd;font-size: 12px;">
					<td height="22" style="color:#000;width: 5%;" align="center" class="tabelBorderAll">ZZ</td>
					<td height="22" style="color:#000;width: 60%;" colspan="10" align="center" class="tabelBorderAll">DISCHARGE AGREEMENT (PUMPING RATE & BACK PRESSURE)</td>
				</tr>
				<tr>
					<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;background-color: #e4e4e4;" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;background-color: #e4e4e4;" colspan="2" align="center" class="tabelBorderAll">GRADE A</td>
					<td height="22" style="color:#000;background-color: #e4e4e4;" colspan="2" align="center" class="tabelBorderAll">GRADE B</td>
					<td height="22" style="color:#000;background-color: #e4e4e4;" colspan="2" align="center" class="tabelBorderAll">GRADE C</td>
					<td height="22" style="color:#000;background-color: #e4e4e4;" colspan="2" align="center" class="tabelBorderAll">GRADE D</td>
				</tr>
				<tr>
					<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;background-color: #e4e4e4;" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;width:8%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">kl/MT per hour</td>
					<td height="22" style="color:#000;width:8%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">kg/cm<sup>2</sup></td>
					<td height="22" style="color:#000;width:8%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">kl/MT per hour</td>
					<td height="22" style="color:#000;width:8%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">kg/cm<sup>2</sup></td>
					<td height="22" style="color:#000;width:8%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">kl/MT per hour</td>
					<td height="22" style="color:#000;width:8%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">kg/cm<sup>2</sup></td>
					<td height="22" style="color:#000;width:8%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">kl/MT per hour</td>
					<td height="22" style="color:#000;width:8%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">kg/cm<sup>2</sup></td>					
				</tr>
				<tr>
					<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">ZZ1</td>
					<td height="22" style="color:#000;width:30%;background-color: #e4e4e4;font-size: 12px;" class="tabelBorderAll">VESSEL CAPACITY (CHARTER PARTY)</td>
					<td height="22" style="color:#000;" id="txtVesselCapacityHourA" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;" id="txtVesselCapacityKgA" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;" id="txtVesselCapacityHourB" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;" id="txtVesselCapacityKgB" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;" id="txtVesselCapacityHourC" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;" id="txtVesselCapacityKgC" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;" id="txtVesselCapacityHourD" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;" id="txtVesselCapacityKgD" align="center" class="tabelBorderAll"></td>
				</tr>
				<tr>
					<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">ZZ2</td>
					<td height="22" style="color:#000;width:30%;background-color: #e4e4e4;font-size: 12px;" class="tabelBorderAll">SHORE CAPACITY</td>
					<td height="22" style="color:#000;" id="txtShoreCapacityHourA" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;" id="txtShoreCapacityKgA" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;" id="txtShoreCapacityHourB" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;" id="txtShoreCapacityKgB" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;" id="txtShoreCapacityHourC" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;" id="txtShoreCapacityKgC" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;" id="txtShoreCapacityHourD" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;" id="txtShoreCapacityKgD" align="center" class="tabelBorderAll"></td>
				</tr>
				<tr>
					<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">ZZ3</td>
					<td height="22" style="color:#000;width:30%;background-color: #e4e4e4;font-size: 12px;" class="tabelBorderAll">LOADING / DISCHARGE AGREEMENT</td>
					<td height="22" style="color:#000;" id="txtLoadingCapacityHourA" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;" id="txtLoadingCapacityKgA" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;" id="txtLoadingCapacityHourB" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;" id="txtLoadingCapacityKgB" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;" id="txtLoadingCapacityHourC" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;" id="txtLoadingCapacityKgC" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;" id="txtLoadingCapacityHourD" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;" id="txtLoadingCapacityKgD" align="center" class="tabelBorderAll"></td>
				</tr>
				<tr>
					<td height="22" style="color:#000;width:5%;background-color: #e4e4e4;" align="center" class="tabelBorderAll">ZZ4</td>
					<td height="22" style="color:#000;width:30%;background-color: #e4e4e4;font-size: 12px;" class="tabelBorderAll">AVERAGE ACTUAL LOADING / DISCHARGE RATE</td>
					<td height="22" style="color:#000;" id="txtAverageCapacityHourA" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;" id="txtAverageCapacityKgA" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;" id="txtAverageCapacityHourB" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;" id="txtAverageCapacityKgB" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;" id="txtAverageCapacityHourC" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;" id="txtAverageCapacityKgC" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;" id="txtAverageCapacityHourD" align="center" class="tabelBorderAll"></td>
					<td height="22" style="color:#000;" id="txtAverageCapacityKgD" align="center" class="tabelBorderAll"></td>
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


