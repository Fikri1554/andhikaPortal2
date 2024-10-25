<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/jquery-ui.js"></script>
<script type="text/javascript" src="../js/niceEdit.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#idBtnDeficiency").css("display","none");
		$("#idPlannMaintenance").css("display","none");
        $("#idBtnSurveyStatus").css("display","none");
        var html = $('<button id ="idBtnDeficiency" class="btnStandarKecil" type="button" onclick="formHome.submit();" style="width:80px;height:50px;float:left;margin-left:10px;" title="Home"><table cellpadding="0" cellspacing="0" width="100%" height="100%"><tr><td align="center"><img src="../picture/Application-View-Icons-32.png" height="18"/></td></tr><tr><td align="center" style="font-size:9px;font-weight: bold;">HOME</td></tr></table></button>');
        $("#idNavHome").append(html);
		 bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
		$("#loading").hide();
		$("#btnEditMst").attr("disabled","disabled");
		$("#btnDelMst").attr("disabled","disabled");
		$("#btnAddDetail").attr("disabled","disabled");
		$("#btnEditDetail").attr("disabled","disabled");
		$("#btnDelDetail").attr("disabled","disabled");
		$("#idFormMst").hide();
		$("#idFormDetail").hide();

		$( "#txtDateJoinMst" ).datepicker({
			dateFormat: 'mm-dd-yy',
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            defaultDate: new Date(),
		});
		$( "#dateSignOffMst" ).datepicker({
			dateFormat: 'mm-dd-yy',
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            defaultDate: new Date(),
		});
		$( "#txtCoDateJoin" ).datepicker({
			dateFormat: 'mm-dd-yy',
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            defaultDate: new Date(),
		});
		$( "#txtCoSignOff" ).datepicker({
			dateFormat: 'mm-dd-yy',
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            defaultDate: new Date(),
		});
		$( "#txtCeDateJoin" ).datepicker({
			dateFormat: 'mm-dd-yy',
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            defaultDate: new Date(),
		});
		$( "#txtCeSignOff" ).datepicker({
			dateFormat: 'mm-dd-yy',
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            defaultDate: new Date(),
		});
		$( "#txtInspectorDate" ).datepicker({
			dateFormat: 'mm-dd-yy',
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            defaultDate: new Date(),
		});
		$( "#txtExpDateOfCompletion" ).datepicker({
			dateFormat: 'mm-dd-yy',
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            defaultDate: new Date(),
		});
		$( "#txtUpdateFromVessel" ).datepicker({
			dateFormat: 'mm-dd-yy',
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            defaultDate: new Date(),
		});
		$( "#txtDateComplte" ).datepicker({
			dateFormat: 'mm-dd-yy',
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            defaultDate: new Date(),
		});
		$("#btnSaveMst").click(function(){
			addEditMst();
		});
		$("#btnAddMst").click(function(){
			$(".navBtn").hide();
			$(".table1").hide();
			$(".table2").hide();
			$("#idFormDetail").hide();
			$("#idFormMst").show(100);
		});
		$("#btnCancelMst").click(function(){
			location.reload();
		});
		$("#btnAddDetail").click(function(){
			$(".navBtn").hide();
			$(".table1").hide();
			$(".table2").hide();
			$("#idFormDetail").show(100);
			$("#idFormMst").hide();
		});
		$("#btnSaveDetail").click(function(){
			addEditDetail();
		});
		$("#btnCancelDetail").click(function(){
			location.reload();
		});

	});

	function addEditMst()
	{
		var idEditMst = $("#idEditMst").val();
		var stData = "";
		if (idEditMst == "") 
		{
			stData = "add";
		}else{
			stData = "update";
		}
		$("#loading").show();
		$.post('../shipManagement/class/actionNav.php',
		{ actionDeficiency : stData,idEditMst:idEditMst,mstName : $("#txtMasterName").val(),mstDateJoin : $("#txtDateJoinMst").val(),mstSignOff : $("#dateSignOffMst").val(), coName : $("#txtCoName").val(),coDateJoin : $("#txtCoDateJoin").val(),coSignOff : $("#txtCoSignOff").val(),ceName : $("#txtCeName").val(),ceDateJoin : $("#txtCeDateJoin").val(),ceSignOff : $("#txtCeSignOff").val(),insName : $("#txtInspectorName").val(),insDate : $("#txtInspectorDate").val()},
			function(data) 
			{	
				alert(data);
				$("#loading").hide();
				location.reload();
			},
		"json"
		);
	}

	function getUpdateMst(id)
	{
		$.post('../shipManagement/class/actionNav.php',
		{ actionUpdateDefMst : id},
			function(data) 
			{
				$("#idEditMst").val(data.id);
				$("#txtMasterName").val(data.master);
				$("#txtDateJoinMst").val(data.mstDateJoin);
				$("#dateSignOffMst").val(data.mstDateSignOff);
				$("#txtCoName").val(data.chief_officer);
				$("#txtCoDateJoin").val(data.coDateJoin);
				$("#txtCoSignOff").val(data.coDateSignOff);
				$("#txtCeName").val(data.chief_engineer);
				$("#txtCeDateJoin").val(data.ceDateJoin);
				$("#txtCeSignOff").val(data.ceDateSignOff);
				$("#txtInspectorName").val(data.inspector_name);
				$("#txtInspectorDate").val(data.insDate);
			},
		"json"
		);
		$(".navBtn").hide();
		$(".table1").hide();
		$(".table2").hide();
		$("#idFormDetail").hide();
		$("#idFormMst").show(100);
	}
	function delMst(id)
	{
		if (confirm("Hapus data..")) {
			$.post('../shipManagement/class/actionNav.php',
				{ idDelDeficiencyMst : id},
				function(data) 
				{	
					alert(data);
					location.reload();
				},
				"json"
			);
		}
	}
	function addEditDetail()
	{
		var idEditDetail = $("#idEditDetail").val();
		var  idDefMst = $("#idDefMst").val();
		var stData = "";
		var txtDefNya = nicEditors.findEditor( "txtDeficiency" ).getContent();
		var txtRemarkNya = nicEditors.findEditor( "txtRemark" ).getContent();
		if (idEditDetail == "") 
		{
			stData = "add";
		}else{
			stData = "update";
		}
		$("#loading").show();
		$.post('../shipManagement/class/actionNav.php',
		{ actionDeficiencyDetail : stData,idDeficiencyMst : idDefMst,idEditDetail : idEditDetail,txtDeficiency : txtDefNya,slcDoneBy : $("#slcDoneBy").val(),txtTimeGiven : $("#txtTimeGiven").val(),txtExpDateOfCompletion : $("#txtExpDateOfCompletion").val(),txtDateComplte : $("#txtDateComplte").val(),txtVerify : $("#txtVerify").val(),txtUpdateFromVessel : $("#txtUpdateFromVessel").val(),txtProperlyDone : $("#txtProperlyDone").val(),txtRemark : txtRemarkNya},
			function(data) 
			{	
				alert(data);
				$("#loading").hide();
				location.reload();
			},
		"json"
		);
	}
	function getUpdateDetail(id)
	{
		$.post('../shipManagement/class/actionNav.php',
		{ actionUpdateDefDetail : id},
			function(data) 
			{
				$("#idEditDetail").val(data.id);
				nicEditors.findEditor( "txtDeficiency" ).setContent(data.deficiency);
				$("#slcDoneBy").val(data.done_by);
				$("#txtTimeGiven").val(data.time_given);
				$("#txtExpDateOfCompletion").val(data.expDateComplete);
				$("#txtDateComplte").val(data.dateComplete);
				$("#txtVerify").val(data.verify);
				$("#txtUpdateFromVessel").val(data.updFromVessel);
				$("#txtProperlyDone").val(data.status_done);
				nicEditors.findEditor( "txtRemark" ).setContent(data.remark);
			},
		"json"
		);
		$(".navBtn").hide();
		$(".table1").hide();
		$(".table2").hide();
		$("#idFormDetail").show(100);
		$("#idFormMst").hide();
	}
	function delDetail(id)
	{
		if (confirm("Hapus data..")) {
			$.post('../shipManagement/class/actionNav.php',
				{ idDelDeficiencyDetail : id},
				function(data) 
				{	
					alert(data);
					location.reload();
				},
				"json"
			);
		}
	}
	function trKlik(id)
	{
		$(".clsTR").css("background-color","");
		$("#idTR"+id+"").css("background-color","#CFE6FF");
		$("#btnEditMst").attr("disabled","");
		$("#btnDelMst").attr("disabled","");
		$("#btnAddDetail").attr("disabled","");
		$("#btnEditDetail").attr("disabled","disabled");
		$("#btnDelDetail").attr("disabled","disabled");
		$("#idDefMst").val(id);
		document.getElementById("btnEditMst").onclick = function() { 
            getUpdateMst(id);
        };
        document.getElementById("btnDelMst").onclick = function() { 
            delMst(id);
        };
		$("#btnEdit").attr("disabled","");
		$("#btnDel").attr("disabled","");
		setTable2(id);
	}
	function setTable2(id)
	{
		$("#loading").show();
		$.post('../shipManagement/class/actionNav.php',
			{ actionSetDefDetail : id},
				function(data) 
				{
					$('#tblBody2').empty();
					var html = data;
					$('#tblBody2').append(html);
					$("#loading").hide();
				},
			"json"
		);
	}
	function trKlikDetail(id)
	{
		$(".clsTRTbl2").css("background-color","");
		$("#idTRDetail"+id+"").css("background-color","#CFE6FF");
		$("#btnEditDetail").attr("disabled","");
		$("#btnDelDetail").attr("disabled","");
		document.getElementById("btnEditDetail").onclick = function() { 
            getUpdateDetail(id);
        };
        document.getElementById("btnDelDetail").onclick = function() { 
            delDetail(id);
        };
		$("#btnEdit").attr("disabled","");
		$("#btnDel").attr("disabled","");
	}
</script>
<!DOCTYPE html>
<div class="archivesContainer" style="padding-bottom:50px;margin-top:10px;">
	
	<div class="navBtn" style="width:100%;min-height:0px;" >
		<button class="btnStandar" id="btnAddMst" style="width:100px;height:29px; margin: 10px; float: left;" title="Add Data">
        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
            	<tr>
                	<td align="right" width="25"><img src="../picture/Button-Add-blue-32.png" height="20"/> </td>
                    <td align="center">Add</td>
              	</tr>
         	</table>
     	</button>
		<button class="btnStandar" id="btnEditMst" style="width:100px;height:29px; margin: 10px; float: left;" title="Edit Data">
        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
            	<tr>
                	<td align="right" width="25"><img src="../picture/Pencil-blue-32.png" height="20"/> </td>
                    <td align="center">Edit</td>
              	</tr>
         	</table>
     	</button>
     	<button class="btnStandar" id="btnDelMst" style="width:100px;height:29px; margin: 10px; float: left;" title="Delete Data">
        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
            	<tr>
                	<td align="right" width="25"><img src="../picture/Button-Cross-blue-32.png" height="20"/> </td>
                    <td align="center">Delete</td>
              	</tr>
         	</table>
     	</button>	
     	<div align="right">
			<img src="../picture/ajax-loader20.gif" style="width: 24px;height: 24px;margin: 10px;" id="loading" class="btnStandar">
		</div>
	</div>
	<div class="table1" style="width:100%;max-height:250px;overflow:scroll;">
		<table cellpadding="0" cellspacing="0" width="100%" border="1" id="tblEquipName">
			<thead style="background-color: #125362;color: #FFFFFF;height:30px;">
				<tr>
					<td style="width: 1%;" align="center">No</td>
					<td style="width: 10%;" align="center">Master <br><i style="font-size: 12px;">( Date Joined - Sign Off )</i></td>
					<td style="width: 10%;" align="center">Chief Officer <br><i style="font-size: 12px;">( Date Joined - Sign Off )</i></td>
					<td style="width: 10%;" align="center">Chief Engineer <br><i style="font-size: 12px;">( Date Joined - Sign Off )</i></td>
					<td style="width: 10%;" align="center">Inspector Name <br><i style="font-size: 12px;">( Inspection Date )</i></td>
				</tr>
			</thead>
			<tbody style="font-size: 11px;cursor: pointer;">
				{getDataMst}
			</tbody>
		</table>
	</div>
	<div class="table2" style="width:100%;max-height:400px;overflow:scroll;margin-top:20px;">
		<fieldset>
			<legend style="padding: 10px;"><b>DETAIL DEFICIENCY</b></legend>
		<div class="navBtn" style="width:100%;min-height:0px;" >
			<button class="btnStandar" id="btnAddDetail" style="width:100px;height:29px; margin: 10px; float: left;" title="Add Data">
        		<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
            		<tr>
                		<td align="right" width="25"><img src="../picture/Button-Add-blue-32.png" height="20"/> </td>
                    	<td align="center">Add</td>
              		</tr>
         		</table>
     		</button>
			<button class="btnStandar" id="btnEditDetail" style="width:100px;height:29px; margin: 10px; float: left;" title="Edit Data">
        		<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
            		<tr>
                		<td align="right" width="25"><img src="../picture/Pencil-blue-32.png" height="20"/> </td>
                    	<td align="center">Edit</td>
              		</tr>
         		</table>
     		</button>
     		<button class="btnStandar" id="btnDelDetail" style="width:100px;height:29px; margin: 10px; float: left;" title="Delete Data">
        		<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
            		<tr>
                		<td align="right" width="25"><img src="../picture/Button-Cross-blue-32.png" height="20"/> </td>
                    	<td align="center">Delete</td>
              		</tr>
         		</table>
     		</button>	
		</div>
		<table cellpadding="0" cellspacing="0" width="100%" border="1" id="tblCompName">
			<thead style="background-color: #125362;color: #FFFFFF;height:30px;">
				<tr>
					<td style="width: 3%;" align="center">No</td>
					<td style="width: 22%;" align="center">Deficiency</td>
					<td style="width: 6%;" align="center">Done By</td>
					<td style="width: 5%;" align="center">Time Given</td>
					<td style="width: 10%;" align="center">Expected Complete</td>
					<td style="width: 9%;" align="center">Date Complete</td>
					<td style="width: 10%;" align="center">Received Date From Vessel</td>
					<td style="width: 10%;" align="center">Verify</td>
					<td style="width: 5%;" align="center">Done</td>
					<td style="width: 30%;" align="center">Remark</td>
				</tr>
			</thead>
			<tbody style="font-size: 12px;cursor: pointer;" id="tblBody2">
			</tbody>
		</table>
		</fieldset>
	</div>
	<div id="idFormMst">
		<fieldset>
			<legend style="padding:10px;"><b>ADD DATA</b></legend>
			<table style="width: 100%;padding: 20px;" cellpadding="0" cellspacing="8">
				<tr>
					<td>Master</td>
					<td>:</td>
					<td>
						<input type="text" name="txtMasterName" id="txtMasterName" class="elementSearch" style="width: 40%;" placeholder="Name Master">
						<input type="text" name="txtDateJoinMst" id="txtDateJoinMst" class="elementSearch" style="width: 15%;margin-left: 30px;" placeholder="Date Join">
						<input type="text" name="dateSignOffMst" id="dateSignOffMst" class="elementSearch" style="width: 15%;margin-left: 10px;" placeholder="Date Sign Off">
					</td>
				</tr>
				<tr>
					<td>Chief Officer</td>
					<td>:</td>
					<td>
						<input type="text" name="txtCoName" id="txtCoName" class="elementSearch" style="width: 40%;" placeholder="Name Chief Officer">
						<input type="text" name="txtCoDateJoin" id="txtCoDateJoin" class="elementSearch" style="width: 15%;margin-left: 30px;" placeholder="Date Join">
						<input type="text" name="txtCoSignOff" id="txtCoSignOff" class="elementSearch" style="width: 15%;margin-left: 10px;" placeholder="Date Sign Off">
					</td>
				</tr>
				<tr>
					<td>Chief Engineer</td>
					<td>:</td>
					<td>
						<input type="text" name="txtCeName" id="txtCeName" class="elementSearch" style="width: 40%;" placeholder="Name Chief Engineer">
						<input type="text" name="txtCeDateJoin" id="txtCeDateJoin" class="elementSearch" style="width: 15%;margin-left: 30px;" placeholder="Date Join">
						<input type="text" name="txtCeSignOff" id="txtCeSignOff" class="elementSearch" style="width: 15%;margin-left: 10px;" placeholder="Date Sign Off">
					</td>
				</tr>
				<tr>
					<td>Inspector</td>
					<td>:</td>
					<td>
						<input type="text" name="txtInspectorName" id="txtInspectorName" class="elementSearch" style="width: 40%;" placeholder="Name Inspector">
						<input type="text" name="txtInspectorDate" id="txtInspectorDate" class="elementSearch" style="width: 15%;margin-left: 30px;" placeholder="Inspection Date">
					</td>
				</tr>
				<tr>
					<td colspan="5" align="center" style="padding: 30px;">
						<button class="btnStandar" id="btnSaveMst" style="width:80px;height:29px;" title="Save">
				        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
				            	<tr>
				                	<td align="right" width="25"><img src="../picture/Floppy-Disk-blue-32.png" height="20"/> </td>
				                    <td align="center">Save</td>
				              	</tr>
				         	</table>
				     	</button>
				     	<button class="btnStandar" id="btnCancelMst" style="width:80px;height:29px;" title="Cancel">
				        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
				            	<tr>
				                	<td align="right" width="25"><img src="../picture/Button-Cross-blue-32.png" height="20"/> </td>
				                    <td align="center">Cancel</td>
				              	</tr>
				         	</table>
				     	</button>
				     	<input type="hidden" id="idEditMst" name="idEditMst" value="">
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
	<div id="idFormDetail">
		<fieldset>
			<legend style="padding:10px;"><b>ADD DATA</b></legend>
			<table style="width: 100%;padding: 20px;" cellpadding="0" cellspacing="8">
				<tr>
					<td>Deficiency</td>
					<td>:</td>
					<td>
						<textarea id="txtDeficiency" style="width: 787px;" name="txtDeficiency"></textarea>
					</td>
				</tr>
				<tr>
					<td>Done By</td>
					<td>:</td>
					<td>
						<select id="slcDoneBy" style="width: 15%;"  name="slcDoneBy" class="elementSearch">
							<option value="crew">Crew</option>
							<option value="team repair">Team Repair</option>
							<option value="shore">Shore</option>
							<option value="dock yard">Dock Yard</option>
						</select>
						&nbsp&nbsp&nbsp&nbsp Time Given :
						<input type="text" name="txtTimeGiven" id="txtTimeGiven" class="elementSearch" style="width: 3%;" placeholder="Day">
						&nbsp&nbsp&nbsp&nbsp Expected Date of Completion :
						<input type="text" name="txtExpDateOfCompletion" id="txtExpDateOfCompletion" class="elementSearch" style="width: 10%;" placeholder="Exp. Date">
						&nbsp&nbsp&nbsp&nbsp Date Completed :
						<input type="text" name="txtDateComplte" id="txtDateComplte" class="elementSearch" style="width: 10%;" placeholder="Date Comp.">
					</td>				
				</tr>
				<tr>
					<td>Verify By</td>
					<td>:</td>
					<td>
						<input type="text" name="txtVerify" id="txtVerify" class="elementSearch" style="width: 14%;" placeholder="Verify By">
						&nbsp&nbsp&nbsp&nbsp Update From Vessel :
						<input type="text" name="txtUpdateFromVessel" id="txtUpdateFromVessel" class="elementSearch" style="width: 10%;" placeholder="Update Date">
						&nbsp&nbsp&nbsp&nbsp Properly Done :
						<input type="text" name="txtProperlyDone" id="txtProperlyDone" value="Y" class="elementSearch" style="width: 3%;">
					</td>
				</tr>
				<tr>
					<td>Remark</td>
					<td>:</td>
					<td>
						<textarea id="txtRemark" style="width: 787px;" name="txtRemark"></textarea>
					</td>
				</tr>
				<tr>
					<td colspan="5" align="center" style="padding: 30px;">
						<button class="btnStandar" id="btnSaveDetail" style="width:80px;height:29px;" title="Save">
				        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
				            	<tr>
				                	<td align="right" width="25"><img src="../picture/Floppy-Disk-blue-32.png" height="20"/> </td>
				                    <td align="center">Save</td>
				              	</tr>
				         	</table>
				     	</button>
				     	<button class="btnStandar" id="btnCancelDetail" style="width:80px;height:29px;" title="Cancel">
				        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
				            	<tr>
				                	<td align="right" width="25"><img src="../picture/Button-Cross-blue-32.png" height="20"/> </td>
				                    <td align="center">Cancel</td>
				              	</tr>
				         	</table>
				     	</button>
				     	<input type="hidden" id="idEditDetail" name="idEditDetail" value="">
				     	<input type="hidden" id="idDefMst" name="idDefMst" value="">
					</td>
				</tr>
			</table>
		</fieldset>
	</div>

	
</div>


