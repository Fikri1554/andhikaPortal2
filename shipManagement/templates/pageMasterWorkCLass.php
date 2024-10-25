<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/jquery-ui.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#loading").hide();
		$("#txtWorkCode").hide();
		$("#btnSave").hide();
		$("#btnCancel").hide();
		$("#idBtnDeficiency").css("display","none");
		$("#idPlannMaintenance").css("display","none");
        $("#idBtnSurveyStatus").css("display","none");
		var html = $('<button id ="idBtnDeficiency" class="btnStandarKecil" type="button" onclick="formPlannMaintenance.submit();" style="width:70px;height:40px;float:left;margin-left:10px;" title="Back"><table cellpadding="0" cellspacing="0" width="100%" height="100%"><tr><td align="center"><img src="../picture/arrow-return-180-left.png" height="18"/></td></tr><tr><td align="center" style="font-size:9px;font-weight: bold;">BACK</td></tr></table></button>');
        $("#idNavHome").append(html);
		
		$("#btnCancel").click(function(){
			location.reload();
		});

		$("#btnSave").click(function(){
			var idEditWC = $("#txtIdEditWC").val();
			var txtWC = $("#txtWorkCode").val();
			$("#loading").show();
			$.post('../shipManagement/class/actionNav.php',
			{ actionMstIdWorkClass : idEditWC,actionTxtWorkClass : txtWC},
				function(data) 
				{	
					$("#loading").hide();
					alert(data);
					location.reload();
				},
			"json"
			);
		});
	});
	function trKlik(id)
	{
		document.getElementById("btnEdit").onclick = function() { 
	    	editData(id);
	  	};

		$("#descComp").empty();
		$(".clsTR").css("background-color","");
		$("#idTR"+id+"").css("background-color","#CFE6FF");
	}
	function editData(id)
	{
		$("#loading").show();
		$(".workClassName").hide();
		$("#btnEdit").hide();
		$("#txtWorkCode").show();
		$("#btnSave").show();
		$("#btnCancel").show();
		$.post('../shipManagement/class/actionNav.php',
			{ actionEditMstWorkClass : id},
				function(data) 
				{	
					//alert(data['workclass']);
					$("#txtIdEditWC").val(data['workcode']);
					$("#txtWorkCode").val(data['workclass']);
					$("#loading").hide();
				},
			"json"
		);
	}
</script>
<!DOCTYPE html>
<div class="archivesContainer" style="padding-bottom:50px;margin-top:10px;">
	<div class="navBtn" style="width:100%;min-height:0px;" >
		<img src="../picture/ajax-loader20.gif" style="width: 24px;height: 24px;margin: 10px;" id="loading" class="btnStandar">
		<button class="btnStandar" id="btnEdit" style="width:100px;height:29px; margin: 10px; float: left;" title="Edit Data">
        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
            	<tr>
                	<td align="right" width="25"><img src="../picture/Pencil-blue-32.png" height="20"/> </td>
                    <td align="center">Edit</td>
              	</tr>
         	</table>
     	</button>
     	<input type="text" name="txtWorkCode" value="" id="txtWorkCode" class="elementSearch" style="margin: 10px; float: left;">
     	<input type="text" name="txtIdEditWC" value="" id="txtIdEditWC" class="elementSearch" style="display: none;">
     	<button class="btnStandar" id="btnSave" style="width:100px;height:29px; margin: 10px; float: left;" title="Save Data">
        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
            	<tr>
                	<td align="right" width="25"><img src="../picture/Button-Check-blue-32.png" height="20"/> </td>
                    <td align="center">Save</td>
              	</tr>
         	</table>
     	</button>
		<button class="btnStandar" id="btnCancel" style="width:100px;height:29px;margin: 10px; float: left;" title="Cancel">
			<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
				<tr>
					<td align="right" width="25"><img src="../picture/Button-Cross-blue-32.png" height="20"/> </td>
		  			<td align="center">Cancel</td>
				</tr>
			</table>
		</button>
	</div>
	<div class="workClassName" style="width:100%;max-height:250px;overflow:scroll;">
		<table cellpadding="0" cellspacing="0" width="100%" border="1" id="tblEquipName">
			<thead style="background-color: #125362;color: #FFFFFF;height:30px;">
				<tr>
					<td style="width: 20%;" align="center">Work Code</td>
					<td style="width: 80%;" align="center">Work Class</td>
				</tr>
			</thead>
			<tbody style="font-size: 12px;cursor: pointer;" id="tblIdBodyWorkClass">
				{getDataMstWorkClass}
			</tbody>
		</table>
	</div>

</div>


