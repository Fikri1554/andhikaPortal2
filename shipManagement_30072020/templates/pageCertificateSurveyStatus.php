<link rel="stylesheet" href="../css/jquery-ui.css">
<!-- <script type="text/javascript" src="../js/jquery-1.6.js"></script> -->
<script type="text/javascript" src="../js/niceEdit.js"></script>
<script src="../js/jquery-ui.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#loading").hide();
		$(".formComp").hide();
		$(".data2").hide();
		$(".formData2").hide();
		$(".formUploadPdf").hide();
		$("#btnAdd").attr("disabled","disabled");
		$("#btnEdit").attr("disabled","disabled");
		$("#btnDel").attr("disabled","disabled");
		$("#btnAdd2").attr("disabled","disabled");
		$("#btnEdit2").attr("disabled","disabled");
		$("#btnDel2").attr("disabled","disabled");
		$("#btnUpPdf").attr("disabled","disabled");
		$("#btnViewPdf").attr("disabled","disabled");
		$("#idPlannMaintenance").css("display","none");
        $("#idBtnDeficiency").css("display","none");
        $("#namaKapal").css("display","none");
        bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
        $( "#txtStartDate" ).datepicker({
			dateFormat: 'yy-mm-dd',
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            defaultDate: new Date(),
		});
		$( "#txtEndDate" ).datepicker({
			dateFormat: 'yy-mm-dd',
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            defaultDate: new Date(),
		});
        $("#idSlcVesselSurveyStatus").change(function(){
        	$("#loading").show();
        	$('#tblIdBody').empty();
        	$("#tblDataBody2").empty();
        	$("#btnAdd").attr("disabled",false);
        	var idVsl = $(this).val();
        	if (idVsl == "0") 
        	{
        		$("#btnAdd").attr("disabled","disabled");
        	}
        	$.post('../shipManagement/class/actionNav.php',
			{ actionCertSurveyStatus : "displayData",idVsl : idVsl},
				function(data) 
				{	
					var html = data;
					$('#tblIdBody').append(html);
					$("#loading").hide();
				},
			"json"
			);
        });
		$("#btnAdd").click(function(){
			$(".navBtn").hide();
			$(".equipName").hide();
			$(".formComp").show(300);
		});
		$("#btnSave").click(function(){
			var idEdit = $("#txtIdEdit").val();
			var slctItemSS = $("#slcItemSurveyStatus").val();
			var group = $("#txtGroup").val();
			var idVsl = $("#idSlcVesselSurveyStatus").val();
			var alertNya = $("#slcAlert").val();
			$("#loading").show();
			if (slctItemSS == "0") 
			{
				alert("Don't Empty Survey Item..!!");
				return false;
			}
			$.post('../shipManagement/class/actionNav.php',
			{ actionSaveCertSurveyItem : "save",slctItemSS : slctItemSS,group : group,idVsl : idVsl,idEditNya : idEdit, alert : alertNya},
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
		$("#btnAdd2").click(function(){
			$(".navBtn").hide();
			$(".equipName").hide();
			$(".formComp").hide();
			
			$(".data2").hide();
			$(".formData2").show(300);
		});
		$("#btnCancel2").click(function(){
			location.reload();
		});
		$("#btnCancel3").click(function(){
			location.reload();
		});
		$("#btnSave2").click(function(){
			var idEdit2 = $("#txtIdEdit2").val();
			var startDate = $("#txtStartDate").val();
			var endDate = $("#txtEndDate").val();
			var stPermanent = $('input[name=stPermanent]:checked').val();
			var depart = $("#slcDepart").val();
			var picNya = $("#slcPic").val();
			var nmPic = $("#slcPic option:selected").text();
			var idCert = $("#txtIdCert").val();
			var remark = nicEditors.findEditor( "txtRemark" ).getContent();

			$.post('../shipManagement/class/actionNav.php',
			{ actionSaveTransactionSurveyStatus : "save",idEdit2 : idEdit2,startDate : startDate,endDate : endDate,stPermanent : stPermanent,depart : depart,picNya : picNya,idCert : idCert,nmPic : nmPic,remark : remark},
				function(data) 
				{	
					$("#loading").hide();
					alert(data);
					location.reload();
				},
			"json"
			);
		});
		$("#btnUpPdf").click(function(){
			$(".navBtn").hide();
			$(".equipName").hide();
			$(".formComp").hide();
			$(".data2").hide();
			$(".formData2").hide();
			$(".formUploadPdf").show(300);
		});
		$("#btnViewPdf").click(function(){
			
		});

	});

	function trKlik(id,cekData)
	{
		$("#btnDel").attr("disabled","disabled");
		document.getElementById("btnEdit").onclick = function() { 
	    	editData(id);
	  	};
		document.getElementById("btnDel").onclick = function() { 
	    	delData(id);
	   	};
	   	if (cekData == "kosong")
	   	{
	   		$("#btnDel").attr("disabled",false);
	   	}
	   	$('#tblDataBody2').empty();
	   	getDataTransaction(id);
		$(".clsTR").css("background-color","");
		$("#idTR"+id+"").css("background-color","#CFE6FF");
		$("#btnEdit").attr("disabled",false);
		$("#btnAdd2").attr("disabled",false);
		$(".data2").show(300);
		$("#btnDel2").attr("disabled",true);
		$("#btnEdit2").attr("disabled",true);
		$("#btnUpPdf").attr("disabled",true);
		$("#btnViewPdf").attr("disabled",true);
	}
	function trKlik2(id,urlFile)
	{
		$("#btnDel2").attr("disabled",false);
		document.getElementById("btnEdit2").onclick = function() { 
	    	editData2(id);
	  	};
		document.getElementById("btnDel2").onclick = function() { 
	    	delData2(id);
	   	};
	   	document.getElementById("btnUploadPdf").onclick = function() { 
	    	uploadFile(id);
	   	};
	   	if (urlFile != "")
	   	{
	   		$("#btnViewPdf").attr("disabled",false);
	   		document.getElementById("btnViewPdf").onclick = function() { 
	    	viewFile(urlFile);
	   	};
	   	}else{
	   		$("#btnViewPdf").attr("disabled",true);
	   	}
		$(".clsTR2").css("background-color","");
		$("#idTR2"+id+"").css("background-color","#CFE6FF");
		$("#btnEdit2").attr("disabled",false);
		$("#btnUpPdf").attr("disabled",false);
		$("#txtIdTrans").val(id);
	}
	function getDataTransaction(id)
	{
		$('#tblDataBody2').empty();
		$("#loading").show();
		$.post('../shipManagement/class/actionNav.php',
			{ actionGetTransSurveyStatus : "getData", id : id},
				function(data) 
				{	
					var html = data;
					$('#tblDataBody2').append(html);
					$("#txtIdCert").val(id);
				},
			"json"
		);
		$("#loading").hide();
	}
	function editData(id)
	{
		$("#loading").show();
		$.post('../shipManagement/class/actionNav.php',
			{ actionIdEditCertSurveyItem : id},
				function(data) 
				{	
					$("#txtIdEdit").val(data['idcert']);
					$("#txtGroup").val(data['idgroup']);
					$("#slcItemSurveyStatus").val(data['kdcert']);
					$("#slcAlert").val(data['alert']);
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
				{ actionIdDelCertSurveyItem : id},
				function(data) 
				{	
					alert(data);
					location.reload();
				},
				"json"
			);
		}
	}
	function editData2(id)
	{
		$("#loading").show();
		$.post('../shipManagement/class/actionNav.php',
			{ actionIdEditTransSurveyItem : id},
				function(data) 
				{	
					$("#txtIdEdit2").val(data['idtrans']);
					$("#txtIdCert").val(data['idcert']);
					$("#slcPic").val(data['idpic']);
					$("#slcDepart").val(data['idDepart']);
					$("#txtStartDate").val(data['startdt']);
					$("#txtEndDate").val(data['enddt']);
					$("input[name=stPermanent][value=" + data['permanentenddt'] + "]").attr('checked', 'checked');
					nicEditors.findEditor( "txtRemark" ).setContent(data['remarks']);
				},
			"json"
		);
		$(".navBtn").hide();
		$(".equipName").hide();
		$(".formComp").hide();
		
		$(".data2").hide();
		$(".formData2").show(300);
	}
	function delData2(id)
	{
		if (confirm("Hapus data..")) {
			$.post('../shipManagement/class/actionNav.php',
				{ actionIdDelTransSurveyStatus : id},
				function(data) 
				{	
					alert(data);
					location.reload();
				},
				"json"
			);
		}
	}
	function uploadFile(id)
	{
		var fileData = $("#idFileNya").prop('files')[0];
		var formData = new FormData();
		formData.append('file', fileData);
		formData.append('actionUploadPdf', "uploadPdf");
		formData.append('txtIdTrans', id);
		$.ajax({
	        url: '../shipManagement/class/actionNav.php',
	        dataType: 'text',
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: formData,
	        type: 'post',
	        success: function(data){
	            alert(data);
	            location.reload();
	        }
	     });
	}
	function viewFile(nameFile)
	{
		var urlFile = window.location.pathname+"uploadFile/"+nameFile;
		window.open(urlFile, '_blank');
	}

</script>
<!DOCTYPE html>
<div class="archivesContainer" style="padding-bottom:50px;margin-top:10px;">
	<div class="navBtn" style="width:100%;min-height:0px;" >
		{slcVesselSurveyStatus}
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
	<div class="equipName" style="width:100%;max-height:300px;overflow:scroll;">
		<table cellpadding="0" cellspacing="0" width="100%" border="1" id="tblEquipName">
			<thead style="background-color: #125362;color: #FFFFFF;height:30px;">
				<tr>
					<td style="width: 5%;" align="center">NO</td>
					<td style="width: 5%;" align="center">Group</td>
					<td align="center">Survey Item</td>
					<td style="width: 10%;" align="center">Day Due</td>
					<td style="width: 5%;" align="center">Alert</td>
				</tr>
			</thead>
			<tbody style="font-size: 12px;cursor: pointer;" id="tblIdBody">

			</tbody>
		</table>
	</div>
	<div class="formComp" style="width:100%;max-height:500px;overflow: scroll;">
		<fieldset>
		<legend><h3>&nbsp&nbsp MASTER PIC</h3></legend>
		<table style="width: 100%;padding: 20px;" cellpadding="5" cellspacing="8">
			<tr>
				<td align="right" style="width: 13%; padding: 5px;">
					SURVEY ITEM
				</td>
				<td style="padding: 5px;">
					{slcSurveyItem}
				</td>
			</tr>
			<tr>
				<td align="right" style="width: 13%; padding: 5px;">
					GROUP
				</td>
				<td style="padding: 5px;">
					<input type="text" name="txtGroup" id="txtGroup" class="elementSearch" style="width: 20%;">
				</td>
			</tr>
			<tr>
				<td align="right" style="width: 13%; padding: 5px;">
					ALERT
				</td>
				<td style="padding: 5px;">
					<select id="slcAlert" name="slcAlert" class="elementSearch">
						<option value="Y">Yes</option>
						<option value="N">No</option>
					</select>
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
	<div class="data2" style="width:100%;max-height:300px;overflow:scroll; margin-top: 10px;">
		<div class="navBtnData2" style="width:100%;min-height:0px;" >
			<button class="btnStandar" id="btnAdd2" style="width:100px;height:29px; margin: 10px; float: left;" title="Add Data">
	        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
	            	<tr>
	                	<td align="right" width="25"><img src="../picture/Button-Add-blue-32.png" height="20"/> </td>
	                    <td align="center">Add</td>
	              	</tr>
	         	</table>
	     	</button>
			<button class="btnStandar" id="btnEdit2" style="width:100px;height:29px; margin: 10px; float: left;" title="Edit Data">
	        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
	            	<tr>
	                	<td align="right" width="25"><img src="../picture/Pencil-blue-32.png" height="20"/> </td>
	                    <td align="center">Edit</td>
	              	</tr>
	         	</table>
	     	</button>
	     	<button class="btnStandar" id="btnDel2" style="width:100px;height:29px; margin: 10px; float: left;" title="Delete Data">
	        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
	            	<tr>
	                	<td align="right" width="25"><img src="../picture/Button-Cross-blue-32.png" height="20"/> </td>
	                    <td align="center">Delete</td>
	              	</tr>
	         	</table>
	     	</button>
	     	<button class="btnStandar" id="btnUpPdf" style="width:100px;height:29px; margin: 10px; float: left;" title="File PDF">
	        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
	            	<tr>
	                	<td align="right" width="25"><img src="../picture/Outbox-blue-32.png" height="20"/> </td>
	                    <td align="center">Upload File</td>
	              	</tr>
	         	</table>
	     	</button>
	     	<button class="btnStandar" id="btnViewPdf" style="width:100px;height:29px; margin: 10px; float: left;" title="View PDF">
	        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
	            	<tr>
	                	<td align="right" width="25"><img src="../picture/filePdf.png" height="20"/> </td>
	                    <td align="center">View File</td>
	              	</tr>
	         	</table>
	     	</button>
		</div>
		<table cellpadding="0" cellspacing="0" width="100%" border="1" id="tblData2">
			<thead style="background-color: #125362;color: #FFFFFF;height:30px;">
				<tr>
					<td style="width: 5%;" align="center">NO</td>
					<td style="width: 10%;" align="center">Start Date</td>
					<td style="width: 10%;" align="center">End Date</td>
					<td style="width: 20%;" align="center">Department Resp.</td>
					<td style="width: 20%;" align="center">PIC</td>
					<td style="width: 40%;" align="center">Remark</td>
					<td align="center">File</td>
				</tr>
			</thead>
			<tbody style="font-size: 12px;cursor: pointer;" id="tblDataBody2">
			</tbody>
		</table>
	</div>
	<div class="formData2">
		<fieldset>
			<legend><h3>&nbsp&nbsp TRANSACTION</h3></legend>
			<table style="width: 100%;padding: 20px;" cellpadding="5" cellspacing="8">
				<tr>
					<td align="right" style="width: 10%; padding: 5px;">
						Start Date
					</td>
					<td style="padding: 5px; width: 20%;">
						<input type="text" name="txtStartDate" id="txtStartDate" class="elementSearch" placeholder="Start Date" >
					</td>
					<td align="right" style="width: 10%; padding: 5px;">
						Department
					</td>
					<td style="padding: 5px; width: 40%;">
						{slcDepart}
					</td>
				</tr>
				<tr>
					<td align="right" style="width: 10%; padding: 5px;">
						End Date
					</td>
					<td style="padding: 5px;">
						<input type="text" name="txtEndDate" id="txtEndDate" class="elementSearch" placeholder="End Date" >
					</td>
					<td align="right" style="width: 10%; padding: 5px;">
						PIC
					</td>
					<td style="padding: 5px;">
						{slcPIC}
					</td>
				</tr>
				<tr>
					<td align="right" style="width: 10%; padding: 5px;">
						Permanent
					</td>
					<td style="padding: 5px;">
						<input type="radio" name="stPermanent" class ="elementSearch" value="N" checked = "checked"> No
						<input type="radio" name="stPermanent" class ="elementSearch" value="Y"> Yes
					</td>
					<td align="right" style="width: 10%; padding: 5px;">
						
					</td>
					<td style="padding: 5px;">
						
					</td>
				</tr>
				<tr>
					<td align="right" style="padding: 5px;">
						Remark
					</td>
					<td style="padding: 5px;" colspan="4">
						<textarea class="elementSearch" style="width:600px;height: 50px;" id="txtRemark" name="txtRemark" placeholder="Remark" ></textarea>
					</td>
					
				</tr>
			</table>
			<div class="btnFormNya" align="center" style="padding: 20px;">
				<input type="text" name="txtIdEdit2" value="" id="txtIdEdit2" style="display: none;">
				<input type="text" name="txtIdCert" value="" id="txtIdCert" style="display: none;">
				<button class="btnStandar" id="btnSave2" style="width:80px;height:29px;" title="Save">
					<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
						<tr>
					   		<td align="right" width="25"><img src="../picture/Floppy-Disk-blue-32.png" height="20"/> </td>
					   		<td align="center">Save</td>
						</tr>
					</table>
				</button>
				<button class="btnStandar" id="btnCancel2" style="width:80px;height:29px;" title="Cancel">
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
	<dir class="formUploadPdf">
		<fieldset>
			<legend><h3>&nbsp&nbsp UPLOAD FILE</h3></legend>
			<table style="width: 100%;padding: 20px;" cellpadding="5" cellspacing="8">
				<tr>
					<td align="right" style="width: 10%; padding: 5px;">
						Upload File
					</td>
					<td style="padding: 5px; width: 20%;">
						<input type="file" name="fileNya" id="idFileNya" class="elementSearch"/>
					</td>
				</tr>
				<tr>
					<td align="center" style="width: 10%; padding: 5px;" colspan="2">
						<input type="text" name="txtIdTrans" value="" id="txtIdTrans" style="display: none;">
						<input type="text" name="actionUploadPdf" value="uploadPdf" id="actionUploadPdf" style="display: none;">
						<button class="btnStandar" id="btnUploadPdf" style="width:80px;height:29px;" title="Save">
							<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
								<tr>
							   		<td align="right" width="25"><img src="../picture/Outbox-blue-32.png" height="20"/> </td>
							   		<td align="center">Upload</td>
								</tr>
							</table>
						</button>
						<button class="btnStandar" id="btnCancel3" style="width:80px;height:29px;" title="Cancel">
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
	</dir>
</div>


