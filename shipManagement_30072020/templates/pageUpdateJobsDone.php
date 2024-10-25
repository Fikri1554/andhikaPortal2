<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/jquery-ui.js"></script>
<script type="text/javascript">
	var dataCheckNya = "";
	$(document).ready(function() {
		$(".formComp").hide();
		$("#idLoading").hide();
		$("#idBtnDeficiency").css("display","none");
        $("#idBtnSurveyStatus").css("display","none");
		var html = $('<button id ="idBtnDeficiency" class="btnStandarKecil" type="button" onclick="formPlannMaintenance.submit();" style="width:70px;height:40px;float:left;margin-left:10px;" title="Back"><table cellpadding="0" cellspacing="0" width="100%" height="100%"><tr><td align="center"><img src="../picture/arrow-return-180-left.png" height="18"/></td></tr><tr><td align="center" style="font-size:9px;font-weight: bold;">BACK</td></tr></table></button>');
        $("#idNavHome").append(html);
		$("#txtDateJob").datepicker({ 
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
		$("#txtDateJob").focus(function () {
			$(".ui-datepicker-calendar").hide();
			$("#ui-datepicker-div").position({
				  my: "center top",
				  at: "center bottom",
				  of: $(this)
				});			
		});
		$("#chkAll").click(function(){
			if (this.checked) {
	            $(".chkJob").each(function() {
	                this.checked=true;
	            });
	        } else {
	            $(".chkJob").each(function() {
	                this.checked=false;
	            });
	        }
		});
		$("#doneJob").dialog({
            modal: true,
            autoOpen: false,
            title: "Done Job",
            width: 400,
            height: 150
        });
        $("#dataUnscheduledJob").dialog({
            modal: true,
            autoOpen: false,
            title: "Component & Job",
            width: 800,
            height: 600
        });
        $("#btnCancelJob").click(function(){
        	$('#doneJob').dialog('close');
        });
        $("#btnSaveJob").click(function(){
        	var dateDone = $("#txtDateJob").val();
        	$.post('../shipManagement/class/actionNav.php',
			{ actionDoneJob : dataCheckNya,actionDateDone : dateDone},
				function(data) 
				{	
					alert(data);
					location.reload();
				},
			"json"
			);
        });
        $("#btnSave").click(function(){
        	$("#idLoading").show();
        	var code = $("#txtCodePMS").val();
        	var compName = $("#txtCompName").val();
        	var jobHeading = $("#txtJobHeading").val();
        	var remark = $("#txtRemark").val();
        	var nextDue = $("#txtNextDue").val();

        	$.post('../shipManagement/class/actionNav.php',
			{ actionCodeUnsch : code,actionCompName : compName, 
			  actionJob : jobHeading, actionRemark : remark, actionNextDue : nextDue},
				function(data) 
				{	
					alert(data);
					$("#idLoading").hide();
		        	location.reload();
				},
			"json"
			);
        });
        $("#btnUnschedule").click(function(){
        	$(".navBtn").hide();
        	$(".componentName").hide();
        	$(".formComp").show();
        });
        $("#btnSearch").click(function(){
        	var searchNya = $("#txtSearch").val();
        	if(searchNya == "")
        	{
        		alert("Search Empty..!!");
        		return false;
        	}
        	$.post('../shipManagement/class/actionNav.php',
			{ actionSearchUpdJobDone : "actionSearchUpdJobDone",searchNya : searchNya},
				function(data) 
				{	
					$("#bodyId").empty();
					$("#bodyId").append(data);
				},
			"json"
			);
        });
	});

	function openUnscheduledJob()
	{
		$.post('../shipManagement/class/actionNav.php',
		{ actionOpenUnscheduled : "halimun"},
			function(data) 
			{	
				var html = data;
				$('#bodyIdUnscheduled').append(html);
				$("#loading").hide();
			},
		"json"
		);
		$('#dataUnscheduledJob').dialog('open');
	}
	function actionBtnDone()
	{
		var dtChecked = [];
        $(':checkbox:checked').each(function(i){
          dtChecked[i] = $(this).val();
        });
        if (dtChecked.length != "0") 
        {
        	$('#doneJob').dialog('open');
        	dataCheckNya = dtChecked;
        }else{
        	alert("select check in table..!!!");
        }
	}
	function actionClick(id)
	{
		var isiTbl1 = $("#idTR"+id).closest('tr').children('td:eq(0)').text();
		var isiTbl2 = $("#idTR"+id).closest('tr').children('td:eq(1)').text();
		var isiTbl3 = $("#idTR"+id).closest('tr').children('td:eq(2)').text();
		var isiTbl4 = $("#idTR"+id).closest('tr').children('td:eq(3)').text();
		$("#txtCodePMS").val(isiTbl1);
		$("#txtCompName").val(isiTbl2);
		$("#txtJobHeading").val(isiTbl3);
		$("#txtNextDue").val(isiTbl4);
		$('#dataUnscheduledJob').dialog('close');
	}
	function actionClick1(id)
	{
		$(".clsTR").css("background-color","");
		$("#idTR"+id+"").css("background-color","#CFE6FF");
	}
</script>
<style media="screen" type="text/css">
	.ui-datepicker
	{
		width: 16%;
	}
</style>
<!DOCTYPE html>

<div id="doneJob" style="display: none" align = "center">
    <table>
    	<tr>
    		<td>
    			<input style="width: 35%;" type="text" name="txtDateJob" id="txtDateJob" class="elementDefault" placeholder="Job Date">
				<button class="btnStandar" id="btnSaveJob"  style="width:80px;height:35px; margin: 10px;" title="Save Job">
					<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
				    	<tr>
				        	<td align="center">Save</td>
				        </tr>
					</table>
				</button>
				<button class="btnStandar" id="btnCancelJob" style="width:80px;height:35px; margin: 10px;" title="Cancel Job">
					<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
				    	<tr>
				        	<td align="center">Cancel</td>
				        </tr>
					</table>
				</button>
				
    		</td>
    	</tr>
    </table>
</div>
<div class="archivesContainer" style="padding-bottom:50px;margin-top:10px;">
	<div class="navBtn" style="width:100%;min-height:0px;" align="right">
		<input style="width: 15%;float:left;margin-top:10px;margin-left:10px;" type="text" name="txtSearch" id="txtSearch" class="elementDefault" placeholder="Componen Name">
		<button class="btnStandar" id="btnSearch" style="width:90px;height:25px; float:left;margin-top:10px;margin-left:10px;" title="Search">
        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
            	<tr>
                	<td align="right" width="25"><img src="../picture/Search-blue-32.png" height="20"/> </td>
                    <td align="center">Search</td>
              	</tr>
         	</table>
     	</button>
		<button class="btnStandar" id="btnUnschedule" style="width:120px;height:29px; margin: 10px;" title="Unscheduled">
        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
            	<tr>
                	<td align="right" width="25"><img src="../picture/Pencil-blue-32.png" height="20"/> </td>
                    <td align="center">Unscheduled</td>
              	</tr>
         	</table>
     	</button>
     	<button class="btnStandar" id="btnNew" onclick="actionBtnDone();" style="width:100px;height:29px; margin: 10px;" title="Done">
        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
            	<tr>
                	<td align="right" width="25"><img src="../picture/Button-Check-blue-32.png" height="20"/> </td>
                    <td align="center">Done</td>
              	</tr>
         	</table>
     	</button>
     	<input type="checkbox" name="chkAll" id="chkAll" value="chkAll"> <label style="margin-right: 20px;">All</label>
	</div>
	<div class="componentName" style="width:100%;height:500px;overflow:scroll;">
		<table cellpadding="0" cellspacing="0" width="100%" border="1" id="tblDataJobComp">
			<thead>
				<tr style="background-color: #125362;color: #FFFFFF;height:30px;" align="center">
					<th style="width: 5%;">Code</th>
					<th style="width: 30%;">Component Name</th>
					<th style="width: 35%;">Job Heading</th>
					<th style="width: 7%;">Freq</th>
					<th style="width: 10%;">Due</th>
					<th style="width: 8%;">Done In</th>
					<th style="width: 5%;">Action</th>
				</tr>
			</thead>
			<tbody style="font-size: 12px;cursor: pointer;" id="bodyId">
				{getDataUpdateJob}
			</tbody>
		</table>
	</div>
	<div class="formComp" style="width:100%;min-height:250px;overflow: scroll;">
		<fieldset>
			<legend><h3>&nbsp&nbsp UNSCHEDULED JOBS</h3></legend>
			<table style="width: 100%;padding: 20px;" cellpadding="0" cellspacing="8">
				<tr>
					<td style="width: 20%;">PMS Code</td>
					<td style="width: 20%;">
						<input disabled="disabled" style="width: 50%;" type="text" name="txtCodePMS" id="txtCodePMS" class="elementSearch">
						<button class="btnStandar" id="btnOpenJob" onclick="openUnscheduledJob();" style="width:50px;height:29px; margin: 10px;" title="Open Data">
				        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
				            	<tr>
				                    <td align="center">Open</td>
				              	</tr>
				         	</table>
				     	</button>
					</td>
					<td style="width: 10%;" align="right">Remark</td>
					<td style="width: 65%;">
						<input style="width: 100%;" type="text" name="txtRemark" id="txtRemark" class="elementSearch">
						<input type="text" id="txtNextDue" name="txtNextDue" value="" style="display: none;">
					</td>
				</tr>
				<tr>
					<td style="width: 20%;">Component Name</td>
					<td style="width: 80%;" colspan="3">
						<input disabled="disabled" style="width: 100%;" type="text" name="txtCompName" id="txtCompName" class="elementSearch">
					</td>
				</tr>
				<tr>
					<td style="width: 20%;">Job Heading</td>
					<td style="width: 80%;" colspan="3">
						<input disabled="disabled" style="width: 100%;" type="text" name="txtJobHeading" id="txtJobHeading" class="elementSearch">
					</td>
				</tr>
				<tr>
					<td colspan="4" align="right">
						<img class="btnStandar" id="idLoading" src="../picture/ajax-loader20.gif" style="width: 24px;height: 24px;">
						<button class="btnStandar" id="btnSave" style="width:100px;height:29px; margin: 10px;" title="Save">
				        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
				            	<tr>
				                	<td align="right" width="25"><img src="../picture/Floppy-Disk-blue-32.png" height="20"/> </td>
				                    <td align="center">Save</td>
				              	</tr>
				         	</table>
				     	</button>
				     	<button class="btnStandar" id="btnCancel" style="width:100px;height:29px; margin: 10px;" title="Cancel Data" onclick="location.reload();">
				        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
				            	<tr>
				                	<td align="right" width="25"><img src="../picture/Button-Cross-blue-32.png" height="20"/> </td>
				                    <td align="center">Cancel</td>
				              	</tr>
				         	</table>
				     	</button>
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
</div>
<div id="dataUnscheduledJob" style="display: none;" align = "center">
	<table cellpadding="0" cellspacing="0" width="100%" border="1" id="tblDataJobComp">
		<thead>
			<tr style="background-color: #125362;color: #FFFFFF;height:30px;" align="center">
				<th style="width: 5%;">Code</th>
				<th style="width: 30%;">Component Name</th>
				<th style="width: 35%;">Job Heading</th>
			</tr>
		</thead>
		<tbody style="font-size: 11px;cursor: pointer;" id="bodyIdUnscheduled">
			<tr id="loading">
				<td colspan="3" align="center">
					<div style="padding: 20px;">
			     		<img src="../picture/ajax-loader20.gif" style="width: 24px;height: 24px;">
			     	</div>
				</td>
			</tr>
		</tbody>
	</table>	
</div>






