<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/jquery-ui.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#loading").hide();
		$(".formComp").hide();
		$("#btnEdit").attr("disabled","disabled");
		$("#btnDel").attr("disabled","disabled");
        $("#idPlannMaintenance").css("display","none");
        $("#idBtnDeficiency").css("display","none");
        $("#namaKapal").css("display","none");

		$("#btnAdd").click(function(){
			$(".navBtn").hide();
			$(".equipName").hide();
			$(".formComp").show(300);
		});
		$("#btnSave").click(function(){
			var idEdit = $("#txtIdEdit").val();
			var vslName = $("#txtVesselName").val();
			var hpVissel = $("#txtHp").val();
			var typeVissel = $("#txtType").val();
			var dwtVissel = $("#txtDwt").val();
			var yearVissel = $("#txtYear").val();
			var grtVissel = $("#txtGrt").val();
			$("#loading").show();
			if (vslName == "") 
			{
				alert("Don't Empty Vessel Name..!!");
				return false;
			}

			$.post('../shipManagement/class/actionNav.php',
			{ actionSaveMasterVisselSurveyStatus : "save",vslName : vslName,hpVissel : hpVissel,typeVissel : typeVissel,dwtVissel : dwtVissel,yearVissel : yearVissel, grtVissel : grtVissel, idEditNya : idEdit},
				function(data) 
				{	
					$("#loading").hide();
					alert(data);
					location.reload();
				},
			"json"
			);
		});
		$("#btnCancel").click(function(){
			location.reload();
		});

	});

	function trKlik(id)
	{
		document.getElementById("btnEdit").onclick = function() { 
	    	editData(id);
	  	};
		document.getElementById("btnDel").onclick = function() { 
	    	delData(id);
	   	};

		$(".clsTR").css("background-color","");
		$("#idTR"+id+"").css("background-color","#CFE6FF");
		$("#btnEdit").attr("disabled","");
		$("#btnDel").attr("disabled","");
	}

	function editData(id)
	{
		$("#loading").show();
		$.post('../shipManagement/class/actionNav.php',
			{ actionEditMstSurveyStatus : id},
				function(data) 
				{	
					$("#txtIdEdit").val(data['idvsl']);
					$("#txtVesselName").val(data['nmvsl']);
					$("#txtHp").val(data['vslHp']);
					$("#txtType").val(data['vslType']);
					$("#txtDwt").val(data['vslDwt']);
					$("#txtYear").val(data['vslYear']);
					$("#txtGrt").val(data['vslGrt']);
					$("#loading").hide();
				},
			"json"
		);
		$(".navBtn").hide();
		$(".equipName").hide();
		$(".formComp").show(300);
	}
	function delData(id)
	{
		if (confirm("Hapus data..")) {
			$.post('../shipManagement/class/actionNav.php',
				{ actionIdDelMstSurveyStatus : id},
				function(data) 
				{	
					alert(data);
					location.reload();
				},
				"json"
			);
		}
	}

</script>
<!DOCTYPE html>
<div class="archivesContainer" style="padding-bottom:50px;margin-top:10px;">
	<div class="navBtn" style="width:100%;min-height:0px;" >
		<img src="../picture/ajax-loader20.gif" style="width: 24px;height: 24px;margin: 10px;" id="loading" class="btnStandar">
		<button class="btnStandar" id="btnAdd" style="width:100px;height:29px; margin: 10px; float: left;" title="Add Data">
        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
            	<tr>
                	<td align="right" width="25"><img src="../picture/Button-Add-blue-32.png" height="20"/> </td>
                    <td align="center">Add</td>
              	</tr>
         	</table>
     	</button>
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
	</div>
	<div class="equipName" style="width:100%;max-height:500px;overflow:scroll;">
		<table cellpadding="0" cellspacing="0" width="100%" border="1" id="tblEquipName">
			<thead style="background-color: #125362;color: #FFFFFF;height:30px;">
				<tr>
					<td style="width: 5%;" align="center">NO</td>
					<td style="width: 20%;" align="center">VESSEL NAME</td>
					<td style="width: 15%;" align="center">TYPE</td>
					<td style="width: 10%;" align="center">YEAR</td>
					<td style="width: 10%;" align="center">HP</td>
					<td style="width: 10%;" align="center">DWT</td>
					<td style="width: 10%;" align="center">GRT</td>
				</tr>
			</thead>
			<tbody style="font-size: 12px;cursor: pointer;" id="tblIdBodyEquip">
				{getDataMstVessel}
			</tbody>
		</table>
	</div>
	<div class="formComp" style="width:100%;max-height:500px;overflow: scroll;">
		<fieldset>
		<legend><h3>&nbsp&nbsp MASTER VESSEL</h3></legend>
		<table style="width: 100%;padding: 20px;" cellpadding="5" cellspacing="8">
			<tr>
				<td align="right" style="width: 13%; padding: 5px;">
					VESSEL NAME
				</td>
				<td colspan="2" style="padding: 5px;">
					<input type="text" name="txtVesselName" id="txtVesselName" class="elementSearch" style="width: 100%;">
				</td>
				<td align="right" style="width: 10%; padding: 5px;">
					HP
				</td>
				<td colspan="2" style="padding: 5px;">
					<input type="text" name="txtHp" id="txtHp" class="elementSearch" style="width: 100%;">
				</td>
			</tr>
			<tr>
				<td align="right" style="padding: 5px;">
					TYPE
				</td>
				<td colspan="2" style="padding: 5px;">
					<input type="text" name="txtType" id="txtType" class="elementSearch" style="width: 100%;">
				</td>
				<td align="right" style="width: 10%; padding: 5px;">
					DWT
				</td>
				<td colspan="2" style="padding: 5px;">
					<input type="text" name="txtDwt" id="txtDwt" class="elementSearch" style="width: 100%;">
				</td>
			</tr>
			<tr>
				<td align="right" style="width: 10%; padding: 5px;">
					YEAR
				</td>
				<td colspan="2" style="padding: 5px;">
					<input type="text" name="txtYear" id="txtYear" class="elementSearch" style="width: 30%;">
				</td>
				<td align="right" style="width: 10%; padding: 5px;">
					GRT
				</td>
				<td colspan="2" style="padding: 5px;">
					<input type="text" name="txtGrt" id="txtGrt" class="elementSearch" style="width: 100%;">
				</td>
			</tr>
		</table>
		<div class="btnFormNya" align="center" style="padding: 20px;">
			<input type="text" name="txtIdEdit" value="" id="txtIdEdit" style="display: none;">
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
		</div>
		</fieldset>
	</div>
</div>


