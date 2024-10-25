<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/jquery-ui.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#idBtnDeficiency").css("display","none");
		$("#idPlannMaintenance").css("display","none");
        $("#idBtnSurveyStatus").css("display","none");
		var html = $('<button id ="idBtnDeficiency" class="btnStandarKecil" type="button" onclick="formPlannMaintenance.submit();" style="width:70px;height:40px;float:left;margin-left:10px;" title="Back"><table cellpadding="0" cellspacing="0" width="100%" height="100%"><tr><td align="center"><img src="../picture/arrow-return-180-left.png" height="18"/></td></tr><tr><td align="center" style="font-size:9px;font-weight: bold;">BACK</td></tr></table></button>');
        $("#idNavHome").append(html);
		$( "#lastDone" ).datepicker({
			dateFormat: 'mm-dd-yy',
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            defaultDate: new Date(),
		});
		$( "#nextDue" ).datepicker({
			dateFormat: 'mm-dd-yy',
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            defaultDate: new Date(),
		});
		$("#loading").hide();
		$(".formComp").hide();
		$("#btnEdit").attr("disabled","disabled");
		$("#btnDel").attr("disabled","disabled");
		$("#btnExport").attr("disabled","disabled");

		$("#namaEquip").change(function(){
			$('#bodyId').empty();
			$('.descComp').empty();
			$("#btnEdit").attr("disabled","disabled");
			$("#btnDel").attr("disabled","disabled");
			$("#btnExport").attr("disabled","disabled");
			if ($(this).val() == "")
			{
				return false;
			}
			$("#loading").show();
			$.post('../shipManagement/class/actionNav.php',
			{ actionEquip : $(this).val()},
				function(data) 
				{	
					var html = data;
					$('#bodyId').append(html);
					$("#loading").hide();
				},
			"json"
			);
		});

		$('#btnNew').click(function(){
			$(".equipName").hide(300);
			$(".componentName").hide(300);
			$(".descComp").hide(300);
			$(".formComp").show(300);
		});
		$('#btnSave').click(function(){
			$.post('../shipManagement/class/actionNav.php',
			{ 
				partOf : $("#partName").val(),jobCode : $("#codeHeading").val(),
				compCode1 : $("#compCode1").val(),compCode2 : $("#compCode2").val(),
				compCode3 : $("#compCode3").val(),compName : $("#compName").val(),
				plann : $("input[name='rdMaintenancePlan']:checked").val() + $("#txtMonthly").val() + $("#txtWeekly").val() + $("#txtHourly").val(),
				lastDone : $("#lastDone").val(),nextDue : $("#nextDue").val(),idEdit:$("#idEdit").val()
			},
				function(data) 
				{	
					alert(data);
					$('.formComp').find('input:text').val('');
					$('#jobHeading').val("");
					$('#namaEquipAll').val("");
					location.reload();
				},
			"json"
			);
		});
		$('#btnExport').click(function(){	
			$("#loading").show();
			var dtChecked = [];
	        $(':checkbox:checked').each(function(i){
	          dtChecked[i] = $(this).val();
	        });        
			$.post('../shipManagement/class/actionNav.php',
			{ actionExportCompJob : dtChecked},
				function(data) 
				{	
					
					if (data.statusAction == "Success") 
					{
						var urls     = window.location.href+"exportDoc/"+data.fileName;
						var element = document.createElement('a');

						element.setAttribute('href', urls);
						element.setAttribute('download', data.fileName);
						element.style.display = 'none';
						document.body.appendChild(element);
						element.click();
						document.body.removeChild(element);
						$("#loading").hide();
					}else{
						alert(data.statusAction);
						return false;
					}
				},
			"json"
			);
		});
		$("#btnExportExcel").click(function(){
			var eqName = $("#namaEquip").val();
			var nmVslNya = $.trim($("#namaKapal option:selected").text());
			if (eqName == "") 
			{
				alert("Select Equipment..!!");
				return false;
			}else{
				$("#actionEquipNya").val(eqName);
				$("#actionVslName").val(nmVslNya);
				formExportAllCompJob.submit();
			}
		});
		$("#namaEquipAll").change(function(){
			var codeEquip = $(this).val();
			$("#compCode1").val(codeEquip);
		});
		$("#jobHeading").change(function(){
			var codeJob = $(this).val();
			$("#codeHeading").val(codeJob);
		});
		monthlyChecked();
		$("#rdMaintenancePlan1").change(function(){
			monthlyChecked();
			clearTxtMaintenancePlan();
		});
		$("#rdMaintenancePlan2").change(function(){
			weeklyChecked();
			clearTxtMaintenancePlan();
		});
		$("#rdMaintenancePlan3").change(function(){
			hourlyChecked();
			clearTxtMaintenancePlan();
		});

		$("#btnCancel").click(function(){
			location.reload();
		});

	});
	function editData(id)
	{
		$("#loading").show();
		$.post('../shipManagement/class/actionNav.php',
		{ idEdit : id},
			function(data) 
			{	
				$("#txtMonthly").val("");
				$("#txtWeekly").val("");
				$("#txtHourly").val("");

				if (data.freq.substring(0,1) == "M") 
				{
					monthlyChecked();
					$("#txtMonthly").val(data.freq.substring(1,5));
					$("#rdMaintenancePlan1").attr('checked', true);
				}else if(data.freq.substring(0,1) == "W")
				{
					weeklyChecked();
					$("#txtWeekly").val(data.freq.substring(1,5));
					$("#rdMaintenancePlan2").attr('checked', true);
				}else if(data.freq.substring(0,1) == "H")
				{
					hourlyChecked();
					$("#txtHourly").val(data.freq.substring(1,5));
					$("#rdMaintenancePlan3").attr('checked', true);
				}

				$("#idEdit").val(data.sNo);
				$("#partName").val(data.partOf);
				$("#codeHeading").val(data.jobcode);
				$("#jobHeading").val(data.jobcode);
				$("#namaEquipAll").val(data.comcode1);
				$("#compName").val(data.compname);
				$("#compCode1").val(data.comcode1);
				$("#compCode2").val(data.comcode2);
				$("#compCode3").val(data.comcode3);
				$("#lastDone").val(data.lastDone);
				$("#nextDue").val(data.nextDue);
				$("#loading").hide();
			},
		"json"
		);
		$(".equipName").hide(300);
		$(".componentName").hide(300);
		$(".descComp").hide(300);
		$(".formComp").show(300);
	}
	function delData(id)
	{
		if (confirm("Hapus data..")) {
			$.post('../shipManagement/class/actionNav.php',
				{ idDel : id},
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

		var dtChecked = [];
        $(':checkbox:checked').each(function(i){
          dtChecked[i] = $(this).val();
        });
		if (dtChecked.length != "0") 
		{
			$("#btnExport").attr("disabled","");
		}else{
			$("#btnExport").attr("disabled","disabled");
		}
		
		$(".clsTR").css("background-color","");
		$("#idTR"+id+"").css("background-color","#CFE6FF");
		document.getElementById("btnEdit").onclick = function() { 
            editData(id);
        };
        document.getElementById("btnDel").onclick = function() { 
            delData(id);
        };
		setDescComp(id);
		$("#btnEdit").attr("disabled","");
		$("#btnDel").attr("disabled","");
	}
	function setDescComp(id)
	{
		$("#loading").show();
		$('.descComp').empty();
		$.post('../shipManagement/class/actionNav.php',
			{ actionCompDesc : id	 },
				function(data) 
				{	
					var html = data;
					$('.descComp').append(html);
					$("#loading").hide();
				},
			"json"
		);
	}
	function monthlyChecked()
	{
		$("#txtMonthly").attr("disabled",false);
		$("#txtWeekly").attr("disabled",true);
		$("#txtHourly").attr("disabled",true);
	}
	function weeklyChecked()
	{
		$("#txtWeekly").attr("disabled",false);
		$("#txtMonthly").attr("disabled",true);
		$("#txtHourly").attr("disabled",true);
	}
	function hourlyChecked()
	{
		$("#txtHourly").attr("disabled",false);
		$("#txtMonthly").attr("disabled",true);
		$("#txtWeekly").attr("disabled",true);
	}
	function clearTxtMaintenancePlan()
	{
		$("#txtMonthly").val("");
		$("#txtWeekly").val("")
		$("#txtHourly").val("")
	}
	function isNumberKey(evt)
	{
	    var charCode = (evt.which) ? evt.which : event.keyCode
	    if (charCode > 31 && (charCode < 48 || charCode > 57))
	        return false;
	    return true;
	}
</script>
<!DOCTYPE html>
<div class="archivesContainer" style="padding-bottom:50px;margin-top:10px;">
	<div class="equipName" style="padding:10px;" >
		{selectEquipment}
		<button class="btnStandar" id="btnNew" onclick="pilihBtnChoose();" style="width:80px;height:29px;" title="New">
        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
            	<tr>
                	<td align="right" width="25"><img src="../picture/Button-Add-blue-32.png" height="20"/> </td>
                    <td align="center">New</td>
              	</tr>
         	</table>
     	</button>
     	<button class="btnStandar" id="btnEdit" style="width:80px;height:29px;" title="Edit">
        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
            	<tr>
                	<td align="right" width="25"><img src="../picture/Pencil-blue-32.png" height="20"/> </td>
                    <td align="center">Edit</td>
              	</tr>
         	</table>
     	</button>
     	<button class="btnStandar" id="btnDel" style="width:80px;height:29px;" title="Delete">
        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
            	<tr>
                	<td align="right" width="25"><img src="../picture/Button-Cross-blue-32.png" height="20"/> </td>
                    <td align="center">Delete</td>
              	</tr>
         	</table>
     	</button>
     	<button class="btnStandar" id="btnExport" style="width:130px;height:29px;" title="Export To Vessel">
        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
            	<tr>
                	<td align="right" width="25"><img src="../picture/Inbox-blue-32.png" height="20"/> </td>
                    <td align="center">Export To Vessel</td>
              	</tr>
         	</table>
     	</button>
     	<button class="btnStandar" id="btnExportExcel" style="width:130px;height:29px;" title="Export To Excel" {stBtnExport}>
        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
            	<tr>
                	<td align="right" width="25"><img src="../picture/fileXls.png" height="20"/> </td>
                    <td align="center">Export To Excel</td>
              	</tr>
         	</table>
     	</button>
     	<div id="loading" style="float: right;padding-right: 50px;">
     		<img src="../picture/ajax-loader20.gif" style="width: 24px;height: 24px;">
     	</div>
	</div>
	<div class="componentName" style="width:100%;height:250px;overflow:scroll;">
		<table cellpadding="0" cellspacing="0" width="100%" border="1" id="tblDataJobComp">
			<thead>
				<tr style="background-color: #125362;color: #FFFFFF;height:30px;" align="center">
					<th style="width: 7%;" colspan="2">Code</th>
					<th style="width: 5%;">Component</th>
					<th style="width: 5%;">Job</th>
					<th style="width: 5%;">Freq</th>
					<th style="width: 33%;">Component Name</th>
					<th style="width: 30%;">Job Heading</th>
					<th style="width: 5%;">Last Done</th>
					<th style="width: 5%;">Next Due</th>
					<th style="width: 5%;">Run Hours</th>
				</tr>
			</thead>
			<tbody style="font-size: 12px;cursor: pointer;" id="bodyId">
			</tbody>
		</table>
	</div>
	<div class="descComp" style="padding:30px;height:250px;overflow:scroll;background-color:#d8d8d8;">
	</div>
	<div class="formComp" style="width:100%;min-height:250px;overflow: scroll;">
		<fieldset>
		<legend><h3>&nbsp&nbsp Form Component & Job</h3></legend>
		
		<table style="width: 100%;padding: 20px;" cellpadding="0" cellspacing="8">
			<tr>
				<td style="width: 20%;">Part of</td>
				<td style="width: 30%;">
					{selectedPart}
				</td>
				<td style="width: 20%;">Job Heading</td>
				<td style="width: 7%;">
					<input disabled="disabled" style="width: 70%;" type="text" name="codeHeading" id="codeHeading" class="elementSearch">
				</td>
				<td style="width: 25%;">
					{selectJobHeading}
				</td>
			</tr>
			<tr>
				<td style="width: 20%;">Component</td>
				<td style="width: 20%;">
					{selectEquipmentAll}
				</td>
				<td colspan="3">
					<input type="text" disabled="disabled" name="compCode1" id="compCode1" class="elementSearch" style="width: 5%;">
					<input type="text" name="compCode2" id="compCode2" class="elementSearch" style="width: 5%;" maxlength="2" onkeypress="return isNumberKey(event)">
					<input type="text" name="compCode3" id="compCode3" class="elementSearch" style="width: 5%;" maxlength="2" onkeypress="return isNumberKey(event)">
					&nbsp
					<input type="text" name="compName" id="compName" class="elementSearch" style="width: 65%;" placeholder="Name">
				</td>
			</tr>
			<tr>
				<td style="width: 20%;">Maintenance Planned by</td>
				<td colspan="4">
					<input type="radio" name="rdMaintenancePlan" id="rdMaintenancePlan1" checked="true" value="M">Monthly
					<input type="text" name="txtMonthly" id="txtMonthly" class="elementSearch" style="width: 5%;">
					<input type="radio" name="rdMaintenancePlan" id="rdMaintenancePlan2" value="W">Weekly
					<input type="text" name="txtWeekly" id="txtWeekly" class="elementSearch" style="width: 5%;">
					<input type="radio" name="rdMaintenancePlan" id="rdMaintenancePlan3" value="H">Hourly
					<input type="text" name="txtHourly" id="txtHourly" class="elementSearch" style="width: 5%;">
					<input type="text" name="lastDone" id="lastDone" class="elementSearch" style="width: 20%;margin-left: 30px;" placeholder="Last Done">
					<input type="text" name="nextDue" id="nextDue" class="elementSearch" style="width: 20%;margin-left: 10px;" placeholder="Next Due">
				</td>
			</tr>
			<tr>
				<td colspan="5" align="center" style="padding: 30px;">
					<button class="btnStandar" id="btnSave" style="width:80px;height:29px;" title="Save">
			        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
			            	<tr>
			                	<td align="right" width="25"><img src="../picture/Floppy-Disk-blue-32.png" height="20"/> </td>
			                    <td align="center">Save</td>
			              	</tr>
			         	</table>
			     	</button>
			     	<button class="btnStandar" id="btnCancel" style="width:80px;height:29px;" title="Cancel">
			        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
			            	<tr>
			                	<td align="right" width="25"><img src="../picture/Button-Cross-blue-32.png" height="20"/> </td>
			                    <td align="center">Cancel</td>
			              	</tr>
			         	</table>
			     	</button>
			     	<input type="hidden" id="idEdit" name="idEdit" value="">
				</td>
			</tr>

		</table>
		</fieldset>
	</div>
	<form name="formExportAllCompJob" method="post" action="../shipManagement/class/actionNav.php">
		<input type="hidden" id="actionExportExcelAllCompJob" name="actionExportExcelAllCompJob" value="export" />
		<input type="hidden" id="actionVslName" name="actionVslName" value="" />
		<input type="hidden" id="actionEquipNya" name="actionEquipNya" value="" />
	</form>
</div>