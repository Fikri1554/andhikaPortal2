<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/jquery-ui.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#loading").hide();
		$(".formComp").hide();
		$("#btnEdit").attr("disabled","disabled");
		$("#btnDel").attr("disabled","disabled");
		$("#idBtnDeficiency").css("display","none");
		$("#idPlannMaintenance").css("display","none");
        $("#idBtnSurveyStatus").css("display","none");
		var html = $('<button id ="idBtnDeficiency" class="btnStandarKecil" type="button" onclick="formPlannMaintenance.submit();" style="width:70px;height:40px;float:left;margin-left:10px;" title="Back"><table cellpadding="0" cellspacing="0" width="100%" height="100%"><tr><td align="center"><img src="../picture/arrow-return-180-left.png" height="18"/></td></tr><tr><td align="center" style="font-size:9px;font-weight: bold;">BACK</td></tr></table></button>');
        $("#idNavHome").append(html);
		
		$("#txtDateSearch").datepicker({ 
			dateFormat: 'yy-mm',
			changeMonth: true,
		    changeYear: true,
		    showButtonPanel: true,
		    onClose: function(dateText, inst) {  
	            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val(); 
	            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val(); 
	            $(this).datepicker('setDate', new Date(year, month, 1)); 
	        }
		});
		$("#txtDateSearch").focus(function () {
			$(".ui-datepicker-calendar").hide();
			$("#ui-datepicker-div").position({
				  my: "center top",
				  at: "center bottom",
				  of: $(this)
				});			
		});
		$("#btnSaveCompRunHrs").click(function(){
			var overhaul = $('input[name^=txtOverhaulForm]').map(function(idx, elem) {
			    return $(elem).val();
			  }).get();
			var monthNow = $('input[name^=txtMonthNowForm]').map(function(idx, elem) {
			    return $(elem).val();
			  }).get();
			var compCode = $('input[name^=txtCompCode]').map(function(idx, elem) {
			    return $(elem).val();
			  }).get();
			var jobCode = $('input[name^=txtJobCode]').map(function(idx, elem) {
			    return $(elem).val();
			  }).get();
			var equipOverhaul = $("#txtOverhaul").val();
			var equipThisMonth = $("#txtThisMonth").val();
			var equipCodeComp = $("#txtIdCodeCompEquip").val();
			var dateSearch = $("#txtDateSearch").val();
			$("#loading").show();

			$.post('../shipManagement/class/actionNav.php',
			{ actionOverhaul : overhaul,actionMonthNow : monthNow,actionCompCode : compCode,actionJobCode : jobCode, actionEquipOverhaul : equipOverhaul,actionEquipThisMonth : equipThisMonth, actionEquipCodeComp : equipCodeComp,actionDateSearch : dateSearch},
				function(data) 
				{	
					$("#loading").hide();
					alert(data);
					location.reload();
				},
			"json"
			);
		});
		$("#btnCancelCompRunHrs").click(function(){
			location.reload();
		});
		$("#btnSearch").click(function(){
			$("#loading").show();
			$("#tblIdBodyEquip").empty();
			$("#tblIdBodyComp").empty();
			$("#descComp").empty();
			$("#btnEdit").attr("disabled","disabled");
			$("#btnDel").attr("disabled","disabled");
		
			var dateSearch = $("#txtDateSearch").val();

			var arr = dateSearch.split('-');
			var monthNow = parseInt(arr[1]);
			var monthBefore = parseInt(arr[1]) - 1;
			//var monthBefore = getMonth(parseInt(arr[1])-1);
			if (monthBefore == "0") {monthBefore = 12;}
			var monthNameNow = getMonth(monthNow);
			var monthNameBefore = getMonth(monthBefore);
			$.post('../shipManagement/class/actionNav.php',
			{ actionDateSearch : dateSearch},
				function(data) 
				{	
					$("#tdHeadBefore").text("Up to "+monthNameBefore);
					$("#tdHeadNow").text("Up to "+monthNameNow);
					$("#tdHeadBeforeComp").text("Up to "+monthNameBefore);
					$("#tdHeadNowComp").text("Up to "+monthNameNow);
					var html = data;
					$('#tblIdBodyEquip').append(html);
					$("#loading").hide();
					$(".equipName").show();
					$(".compName").show();
					$(".descComp").show();
					$(".formComp").hide();
				},
			"json"
			);
		});
	});

	function trKlik(id,urut,uniquekey)
	{
		$("#descComp").empty();
  		if (urut == "1") //untuk table equip jika di klik
  		{
	  		document.getElementById("btnEdit").onclick = function() { 
	            editData(id,uniquekey);
	        };
	        document.getElementById("btnDel").onclick = function() { 
	            delData(id);
	        };
  			$(".clsTR").css("background-color","");
			$("#idTR"+id+"").css("background-color","#CFE6FF");
			setCompName(id);
		}
		else{
			$(".clsTR1").css("background-color","");
			$("#idTR1"+id+"").css("background-color","#CFE6FF");
			setDescComp(id);
		}
		$("#btnEdit").attr("disabled","");
		$("#btnDel").attr("disabled","");
	}
	function setCompName(id)
	{
		$("#loading").show();
		$('#tblIdBodyComp').empty();
		var dateSearch = $("#txtDateSearch").val();
		$.post('../shipManagement/class/actionNav.php',
			{ actionCom : id,actionDateSearch : dateSearch},
				function(data) 
				{	
					var html = data;
					$('#tblIdBodyComp').append(html);
					$("#loading").hide();
				},
			"json"
		);
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
	function editData(id,uniquekey)
	{
		$("#loading").show();
		$('#tblidFormCompRunHours').empty();
		var dateSearch = $("#txtDateSearch").val();
		$.post('../shipManagement/class/actionNav.php',
			{ actionEditComp : id,actionUniqueKey : uniquekey,actionDateSearch : dateSearch},
				function(data) 
				{	
					$("#txtCompName").val(data.dataOutComp['ename']);
					$("#txtOverhaul").val(data.dataOutComp['overhaul']);
					$("#txtLastMonth").val(data.dataOutComp['monthBefore']);
					$("#txtThisMonth").val(data.dataOutComp['monthNow']);
					$("#txtIdCodeCompEquip").val(data.dataOutComp['eCode']);
					
					var html = data.dataOutEquip;
					$('#tblidFormCompRunHours').append(html);
					$("#loading").hide();
					$(".equipName").hide();
					$(".compName").hide();
					$(".descComp").hide();
					$(".formComp").show();
				},
			"json"
		);
	}
	function delData(id)
	{
		$("#loading").show();
		$('#tblIdBodyEquip').empty();
		$('#tblIdBodyComp').empty();
		var dateSearch = $("#txtDateSearch").val();
		$.post('../shipManagement/class/actionNav.php',
			{ actionDelEquip : id,actionDateSearch :dateSearch},
				function(data) 
				{	
					alert(data.stDel);
					var html = data.dataTR;
					$('#tblIdBodyEquip').append(html);
					$("#loading").hide();
				},
			"json"
		);
	}
	function getMonth(idMonth)
	{
		var month = new Array();
		month[1] = "Jan";
		month[2] = "Feb";
		month[3] = "Mar";
		month[4] = "Apr";
		month[5] = "May";
		month[6] = "Jun";
		month[7] = "Jul";
		month[8] = "Aug";
		month[9] = "Sep";
		month[10] = "Oct";
		month[11] = "Nov";
		month[12] = "Dec";
		var valMonth = month[idMonth];
		return valMonth;
	}

</script>
<!DOCTYPE html>
<div class="archivesContainer" style="padding-bottom:50px;margin-top:10px;">
	<div class="navBtn" style="width:100%;min-height:0px;" align="right">
		<img src="../picture/ajax-loader20.gif" style="width: 24px;height: 24px;" id="loading" class="btnStandar">
		<button class="btnStandar" id="btnEdit" style="width:100px;height:29px; margin: 10px; float: left;" title="Edit Data">
        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
            	<tr>
                	<td align="right" width="25"><img src="../picture/Pencil-blue-32.png" height="20"/> </td>
                    <td align="center">Edit</td>
              	</tr>
         	</table>
     	</button>
     	<button class="btnStandar" id="btnDel" style="width:100px;height:29px; margin: 10px; float: left;" title="Delete Data">
        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
            	<tr>
                	<td align="right" width="25"><img src="../picture/Button-Cross-blue-32.png" height="20"/> </td>
                    <td align="center">Delete</td>
              	</tr>
         	</table>
     	</button>
		<input type="text" name="txtDateSearch" id="txtDateSearch" class="elementSearch" style="width: 10%;" placeholder="Date Search">
     	<button class="btnStandar" id="btnSearch" style="width:100px;height:29px; margin: 10px;" title="Search Data">
        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
            	<tr>
                	<td align="right" width="25"><img src="../picture/Button-Check-blue-32.png" height="20"/> </td>
                    <td align="center">Search</td>
              	</tr>
         	</table>
     	</button>
	</div>
	<div class="equipName" style="width:100%;max-height:250px;overflow:scroll;">
		<table cellpadding="0" cellspacing="0" width="100%" border="1" id="tblEquipName">
			<thead style="background-color: #125362;color: #FFFFFF;height:30px;">
				<tr>
					<td style="width: 10%;" align="center">Equipment</td>
					<td style="width: 45%;" align="center">Equipment Name</td>
					<td style="width: 15%;" align="center" id="tdHeadBefore">Up to {blnSblm}</td>
					<td style="width: 15%;" align="center">Overhaul at</td>
					<td style="width: 15%;" align="center" id="tdHeadNow">Up to {blnSkrg}</td>
				</tr>
			</thead>
			<tbody style="font-size: 12px;cursor: pointer;" id="tblIdBodyEquip">
				{getDataEquip}
			</tbody>
		</table>
	</div>
	<div class="compName" style="width:100%;max-height:400px;overflow:scroll;margin-top:20px;">
		<table cellpadding="0" cellspacing="0" width="100%" border="1" id="tblCompName">
			<thead style="background-color: #125362;color: #FFFFFF;height:30px;">
				<tr>
					<td style="width: 5%;" align="center">Code</td>
					<td style="width: 10%;" align="center">Component</td>
					<td style="width: 5%;" align="center">Job</td>
					<td style="width: 5%;" align="center">Freq</td>
					<td style="width: 30%;" align="center">Component Name</td>
					<td style="width: 30%;" align="center">Job Heading</td>
					<td style="width: 5%;" align="center" id="tdHeadBeforeComp">Up to {blnSblm}</td>
					<td style="width: 5%;" align="center">Overhaul at</td>
					<td style="width: 5%;" align="center" id="tdHeadNowComp">Up to {blnSkrg}</td>
				</tr>
			</thead>
			<tbody style="font-size: 12px;cursor: pointer;" id="tblIdBodyComp">
			</tbody>
		</table>
	</div>
	<div class="descComp" style="padding:30px;height:250px;overflow:scroll;background-color:#d8d8d8;margin-top:20px;" id="descComp">
	</div>
	<div class="formComp" style="width:100%;max-height:500px;overflow: scroll;">
		<fieldset>
		<legend><h3>&nbsp&nbsp COMPONENT RUNNING HOURS</h3></legend>
		<table style="width: 100%;padding: 20px;" cellpadding="5" cellspacing="8">
			<tr>
				<td style="width: 55%;padding: 5px;">
					Equipment <br>
					<input disabled="disabled" type="text" name="txtCompName" id="txtCompName" class="elementSearch" style="width: 100%;">
				</td>
				<td style="width: 15%;padding: 5px;">
					Overhaul at <br>
					<input align="right" type="text" name="txtOverhaul" id="txtOverhaul" value="" class="elementSearch" style="width: 100%;">
				</td>
				<td style="width: 15%;padding: 5px;">
					Last Month <br>
					<input disabled="disabled" type="text" name="txtLastMonth" id="txtLastMonth" value="" class="elementSearch" style="width: 100%;">
				</td>
				<td style="width: 15%;padding: 5px;">
					This Month <br>
					<input type="text" name="txtThisMonth" id="txtThisMonth" value="" class="elementSearch" style="width: 100%;">
					<input type="text" name="txtIdCodeCompEquip" value="" id="txtIdCodeCompEquip" style="display: none;">
				</td>
			</tr>
		</table>
		<table cellpadding="0" cellspacing="0" width="100%" border="1" id="tblFormComp">
			<thead style="background-color: #125362;color: #FFFFFF;height:30px;">
				<tr>
					<td style="width: 10%;" align="center">Code</td>
					<td style="width: 30%;" align="center">Component Name</td>
					<td style="width: 20%;" align="center">Job Heading</td>
					<td style="width: 10%;" align="center">Up to Last Month</td>
					<td style="width: 10%;" align="center">Overhaul at</td>
					<td style="width: 10%;" align="center">Up to This Month</td>
				</tr>
			</thead>
			<tbody style="font-size: 11px;cursor: pointer;" id="tblidFormCompRunHours">
			</tbody>
		</table>
		<div class="btnFormNya" align="center" style="padding: 20px;">
			<button class="btnStandar" id="btnSaveCompRunHrs" style="width:80px;height:29px;" title="Save Data">
				<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
					<tr>
				   		<td align="right" width="25"><img src="../picture/Floppy-Disk-blue-32.png" height="20"/> </td>
				   		<td align="center">Save</td>
					</tr>
				</table>
			</button>
			<button class="btnStandar" id="btnCancelCompRunHrs" style="width:80px;height:29px;" title="Cancel">
				<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
					<tr>
						<td align="right" width="25"><img src="../picture/Button-Cross-blue-32.png" height="20"/> </td>
			  			<td align="center">Cancel</td>
					</tr>
				</table>
			</button>
		</div>
		</fieldset>
	</div>
</div>


