<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/jquery-ui.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#loading").hide();
		$("#idBtnDeficiency").css("display","none");
		$("#idPlannMaintenance").css("display","none");
        $("#idBtnSurveyStatus").css("display","none");
		var html = $('<button id ="idBtnDeficiency" class="btnStandarKecil" type="button" onclick="formPlannMaintenance.submit();" style="width:70px;height:40px;float:left;margin-left:10px;" title="Back"><table cellpadding="0" cellspacing="0" width="100%" height="100%"><tr><td align="center"><img src="../picture/arrow-return-180-left.png" height="18"/></td></tr><tr><td align="center" style="font-size:9px;font-weight: bold;">BACK</td></tr></table></button>');
        $("#idNavHome").append(html);
	});

	function trKlik(id,urut)
	{
		$("#descComp").empty();
  		if (urut == "1") //untuk table equip jika di klik
  		{
  			$(".clsTR").css("background-color","");
			$("#idTR"+id+"").css("background-color","#CFE6FF");
			setCompName(id);
		}
		else{
			$(".clsTR1").css("background-color","");
			$("#idTR1"+id+"").css("background-color","#CFE6FF");
			setDescComp(id);
		}
	}
	function setCompName(id)
	{
		$("#loading").show();
		$('#tblIdBodyComp').empty();
		var dateSearch = $("#txtDateSearch").val();
		$.post('../shipManagement/class/actionNav.php',
			{ actionMstCompJob : id},
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
			{ actionMstCompDesc : id},
				function(data) 
				{	
					var html = data;
					$('.descComp').append(html);
					$("#loading").hide();
				},
			"json"
		);
	}

</script>
<!DOCTYPE html>
<div class="archivesContainer" style="padding-bottom:50px;margin-top:10px;">
	<div class="navBtn" style="width:100%;min-height:0px;" align="center">
		<img src="../picture/ajax-loader20.gif" style="width: 24px;height: 24px;" id="loading" class="btnStandar">
	</div>
	<div class="equipName" style="width:100%;max-height:250px;overflow:scroll;">
		<table cellpadding="0" cellspacing="0" width="100%" border="1" id="tblEquipName">
			<thead style="background-color: #125362;color: #FFFFFF;height:30px;">
				<tr>
					<td style="width: 10%;" align="center">Equipment</td>
					<td style="width: 90%;" align="center">Equipment Name</td>
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
					<td style="width: 7%;" align="center">Last Done</td>
					<td style="width: 8%;" align="center">Next Due</td>
				</tr>
			</thead>
			<tbody style="font-size: 12px;cursor: pointer;" id="tblIdBodyComp">
			</tbody>
		</table>
	</div>
	<div class="descComp" style="padding:30px;height:250px;overflow:scroll;background-color:#d8d8d8;margin-top:20px;" id="descComp">
	</div>

</div>


