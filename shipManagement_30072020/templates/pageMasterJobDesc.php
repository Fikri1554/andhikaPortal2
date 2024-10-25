<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/jquery-ui.js"></script>
<script type="text/javascript" src="../js/niceEdit.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		 bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
		$("#loading").hide();
		$(".formComp").hide();
		$("#idBtnDeficiency").css("display","none");
        $("#idBtnSurveyStatus").css("display","none");
		var html = $('<button id ="idBtnDeficiency" class="btnStandarKecil" type="button" onclick="formPlannMaintenance.submit();" style="width:70px;height:40px;float:left;margin-left:10px;" title="Back"><table cellpadding="0" cellspacing="0" width="100%" height="100%"><tr><td align="center"><img src="../picture/arrow-return-180-left.png" height="18"/></td></tr><tr><td align="center" style="font-size:9px;font-weight: bold;">BACK</td></tr></table></button>');
        $("#idNavHome").append(html);
        
		$("#slcJobCode").change(function(){
			var codeNya = $(this).val();
			$.post('../shipManagement/class/actionNav.php',
			{ actionMstgetNewCode : codeNya},
				function(data) 
				{	
					$("#loading").hide();
					$("#txtCode").val(data);
				},
			"json"
			);
		});
		$("#btnAdd").click(function(){
			
			$(".navBtn").hide();
			$(".equipName").hide();
			$(".equipName").hide();
			$(".descComp").hide();
			$(".formComp").show();

		});
		$("#btnSave").click(function(){
			var slcJobCode = $("#slcJobCode").val();
			var txtJobCode = $("#txtJobCode").val();
			var txtCode = $("#txtCode").val();
			var txtDescJobCode = nicEditors.findEditor( "txtDescJobCode" ).getContent();
			$("#loading").show();
			if (slcJobCode == "" || txtJobCode == "" || txtJobCode.substring(0,1) != slcJobCode) 
			{
				alert("Check Job Code..!!");
				return false;
			}

			$.post('../shipManagement/class/actionNav.php',
			{ actionMstSlcCode : slcJobCode,actionTxtJobCode : txtJobCode,actionCode : txtCode,actionDescJobCode : txtDescJobCode},
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

		$("#descComp").empty();
		$(".clsTR").css("background-color","");
		$("#idTR"+id+"").css("background-color","#CFE6FF");
		setDescComp(id);
	}
	function setDescComp(id)
	{
		$("#loading").show();
		$('.descComp').empty();
		$.post('../shipManagement/class/actionNav.php',
			{ actionMstJobDesc : id	},
				function(data) 
				{	
					var html = data;
					$('.descComp').append(html);
					$("#loading").hide();
				},
			"json"
		);
	}
	function editData(id)
	{
		$("#loading").show();
		$(".navBtn").hide();
		$(".equipName").hide();
		$(".descComp").hide();
		$(".formComp").show();
		$.post('../shipManagement/class/actionNav.php',
			{ actionEditMstDescJob : id},
				function(data) 
				{	
					$("#slcJobCode").val(data['initJobCode']);
					$("#txtJobCode").val(data['jobhead']);
					$("#txtCode").val(data['jobcode']);
					nicEditors.findEditor( "txtDescJobCode" ).setContent(data['jobdesc']);
					$("#loading").hide();
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
			{ actionDelMstJobDesc : id},
				function(data) 
				{	
					alert(data);
					$("#loading").hide();
					location.reload();
				},
			"json"
		);
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
	<div class="equipName" style="width:100%;max-height:250px;overflow:scroll;">
		<table cellpadding="0" cellspacing="0" width="100%" border="1" id="tblEquipName">
			<thead style="background-color: #125362;color: #FFFFFF;height:30px;">
				<tr>
					<td style="width: 10%;" align="center">Job Code</td>
					<td style="width: 90%;" align="center">Job Heading</td>
				</tr>
			</thead>
			<tbody style="font-size: 12px;cursor: pointer;" id="tblIdBodyEquip">
				{getDataMstJobDesc}
			</tbody>
		</table>
	</div>
	<div class="descComp" style="padding:30px;min-height-height:150px;overflow:scroll;background-color:#d8d8d8;margin-top:20px;" id="descComp">
	</div>
	<div class="formComp" style="width:100%;max-height:500px;overflow: scroll;">
		<fieldset>
		<legend><h3>&nbsp&nbsp COMPONENT RUNNING HOURS</h3></legend>
		<table style="width: 100%;padding: 20px;" cellpadding="5" cellspacing="8">
			<tr>
				<td style="width: 8%;padding: 5px;" align="right">
					Job Code <br>
					<select id="slcJobCode" style="width: 80%;"  name="slcJobCode" class="elementSearch"> 
						{getDataSlcCodeJOB}
					</select>
				</td>
				<td style="width: 55%;padding: 5px;"><br>
					<input type="text" name="txtJobCode" id="txtJobCode" class="elementSearch" style="width: 100%;">
				</td>
				<td style="width: 5%;padding: 5px;"><br>
					<input align="right" disabled="disabled" type="text" name="txtCode" id="txtCode" value="" class="elementSearch" style="width: 100%;">
				</td>
			</tr>
			<tr>
				<td align="right" style="padding: 5px;">
					Description
				</td>
				<td colspan="2" style="padding: 5px;">
					<textarea id="txtDescJobCode" style="width: 787px;" name="txtDescJobCode"></textarea>
				</td>
			</tr>
		</table>
		<div class="btnFormNya" align="center" style="padding: 20px;">
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


