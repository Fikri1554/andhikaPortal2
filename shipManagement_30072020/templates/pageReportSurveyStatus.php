<link rel="stylesheet" href="../css/jquery-ui.css">
<script type="text/javascript" src="../js/jquery-1.6.js"></script>
<script type="text/javascript" src="../js/niceEdit.js"></script>
<script src="../js/jquery-ui.js"></script>
<script type="text/javascript">
	var dataCheckNya = "";
	$(document).ready(function() {
		$("#idPlannMaintenance").css("display","none");
		$("#idBtnDeficiency").css("display","none");
		$("#namaKapal").css("display","none");
		$("#loading").hide();
		$("#txtInOverDue").hide();
		//$("#dataCert").hide();
		$("#dataVessel").hide();

		getReportDataCert();
		hidVessel();
		// getReportDataVessel();
		
		$("#btnGenerate").click(function(){
			$("#loading").show();
			$.post('../shipManagement/class/actionNav.php',
			{ actionGenerateReportSurveyStatus : "generateData"},
				function(data) 
				{	
					alert(data);
					location.reload();
				},
			"json"
			);
		});
		$("#btnView").click(function(){
			var slcVessel = $("#idSlcVesselSurveyStatus").val();
			var dueWithin = $("#txtDueWithin").val();
			var chkRdNya = $("input[name=inOverDue]:checked").val();
			var txtChkRdYes = $("#txtInOverDue").val();
			getReportDataVessel(slcVessel,dueWithin,chkRdNya,txtChkRdYes);
		});
		$("#idTypePrint").change(function(){
			var cekMode = $(this).val();
			if (cekMode == "c")
			{
				getReportDataCert();
				hidVessel();
				$("#idSlcVesselSurveyStatus").val("allVessel");
				$("#txtDueWithin").val("");
				$("#txtInOverDue").val("");
				$("#txtInOverDue").hide();
				$("#rdNo").prop("checked",true);
			}else{
				$('#tblIdBody2').empty();
				hidCert();
			}
		});
		$("input[name=inOverDue]").click(function(){
			var ckIncOverDue = $(this).val();
			if (ckIncOverDue == "yes")
			{
				$("#txtInOverDue").show(200);
			}else{
				$("#txtInOverDue").hide();
			}
		});
		$("#btnPrint").click(function(){
			var chkTypePrint = $("#idTypePrint").val();
			var sqlPrintNya = "";
			if (chkTypePrint == "c")
			{
				var dtChecked = [];
		        $(':checkbox:checked').each(function(i){
		          dtChecked[i] = $(this).val();
		        });
		        if (dtChecked.length != "0") 
		        {
		        	dataCheckNya = dtChecked;
		        	var url = window.location.pathname+"pdf/cetakReportSurveyStatus.php?aksi=cetakReport&typeData="+chkTypePrint+"&dc="+dataCheckNya+"";
					window.open(url, '_blank');
		        }else{
		        	alert("select check in table..!!!");
		        }
		    }else{
		    	sqlPrintNya = $("#txtSqlNya").val();
		    	var url = window.location.pathname+"pdf/cetakReportSurveyStatus.php?aksi=cetakReport&typeData="+chkTypePrint+"&pl="+sqlPrintNya;
				window.open(url, '_blank');
		    }	   
		});
		$("#chkAll").click(function(){
			if (this.checked) {
	            $(".chkRpt").each(function() {
	                this.checked=true;
	            });
	        } else {
	            $(".chkRpt").each(function() {
	                this.checked=false;
	            });
	        }
		});
	});

	function getReportDataCert()
	{
		$("#loading").show();
		$('#tblIdBody1').empty();
		$.post('../shipManagement/class/actionNav.php',
		{ actionReportCertSurveyStatus : "displayData"},
			function(data) 
			{	
				var html = data;
				$('#tblIdBody1').append(html);
				$("#loading").hide();
			},
		"json"
		);
	}
	function getReportDataVessel(slcVessel,dueWithin,chkRdNya,txtChkRdYes)
	{
		var idVslSlc = "";
		$("#loading").show();
		$.post('../shipManagement/class/actionNav.php',
		{ actionReportVesselSurveyStatus : "displayData",
		  slcVessel : slcVessel,
		  dueWithin : dueWithin,
		  chkRdNya : chkRdNya,
		  txtChkRdYes : txtChkRdYes
		},
			function(data) 
			{	
				// alert(data.sqlPrintNya);
				$('#tblIdBody2').empty();
				var html = data.htmlNya;
				$('#tblIdBody2').append(html);
				$("#loading").hide();
				$("#txtSqlNya").val(data.sqlPrintNya);
			},
		"json"
		);
	}
	function hidVessel()
	{
		$("#dataCert").hide();
		$("#idSlcVesselSurveyStatus").hide();
		$("#dataVessel").hide();
		$("#lblDueWithin").hide();
		$("#txtDueWithin").hide();
		$("#lblIncOver").hide();
		$("#rdYes").hide();
		$("#lblYesRd").hide();
		$("#rdNo").hide();
		$("#lblNoRd").hide();
		// $("#txtInOverDue").hide();
		$("#btnView").hide();
		$("#lblAll").show(100);
		$("#chkAll").show(100);
		$("#btnGenerate").show(100);
		$("#dataCert").show(200);
	}
	function hidCert()
	{
		$("#dataCert").hide();
		$("#btnGenerate").hide();
		$("#lblAll").hide();
		$("#chkAll").hide();
		$("#btnView").show(200);
		$("#dataVessel").show(200);
		$("#idSlcVesselSurveyStatus").show(200);
		$("#lblDueWithin").show(100);
		$("#txtDueWithin").show(100);
		$("#lblIncOver").show(100);
		$("#rdYes").show(100);
		$("#lblYesRd").show(100);
		$("#rdNo").show(100);
		$("#lblNoRd").show(100);
		// $("#txtInOverDue").show(100);
	}

</script>
<!DOCTYPE html>
<div class="archivesContainer" style="padding-bottom:50px;margin-top:10px;">
	<div class="navBtn" style="width:100%;min-height:0px;" >
		<table>
			<tr>
				<td style="width: 90%;">
					<label style="margin:10px;float: left;" id="txtPrint">Print Mode :</label>
					<select class="elementMenu" id="idTypePrint" name="idTypePrint" style="float: left;width:150px;margin: 10px;">
						<option value="c">By Certificate</option>
						<option value="v">By Vessel</option>
					</select>
					{slcVesselReportSurveyStatus}
					<label style="margin:10px;float: left;" id="lblDueWithin">Due Within :</label>
					<input type="text" id="txtDueWithin" name="txtDueWithin" class="elementSearch" style="float: left;width:3%;margin: 10px;" placeholder="Day">
					<label style="margin:10px;float: left;" id="lblIncOver">Include OverDue :</label>
					<input type="radio" class="elementSearch" id="rdYes" name="inOverDue" value="yes" style="margin:15px 4px 0px 0px; float:left;"><label id="lblYesRd" style="margin-top:10px;float: left;"> Yes</label>
					<input type="radio" class="elementSearch" id="rdNo" name="inOverDue" value="no" checked="checked" style="margin:15px 4px 0px 10px; float:left;"><label id="lblNoRd" style="margin-top:10px;float: left;"> No</label>
					<input type="text" id="txtInOverDue" name="txtInOverDue" class="elementSearch" style="float: left;width:3%;margin: 10px;" placeholder="Day">
					<input type="hidden" name="sqlNya" id="txtSqlNya" value="">
					<img src="../picture/ajax-loader20.gif" style="width: 24px;height: 24px;margin: 10px;" id="loading" class="btnStandar">
				</td>
			</tr>
			<tr>
				<td align="center">&nbsp
					<input type="checkbox" name="chkAll" id="chkAll" value="chkAll"><label id="lblAll" style="margin:2px;">All</label>
					<button class="btnStandar" id="btnGenerate" style="width:100px;height:29px; margin: 10px;" title="Generate">
			        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
			            	<tr>
			                	<td align="right" width="25"><img src="../picture/Inbox-blue-32.png" height="20"/> </td>
			                    <td align="center">Generate</td>
			              	</tr>
			         	</table>
			     	</button>
			     	<button class="btnStandar" id="btnView" style="width:100px;height:29px; margin: 10px; " title="Views">
			        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
			            	<tr>
			                	<td align="right" width="25"><img src="../picture/Search-blue-32.png" height="20"/> </td>
			                    <td align="center">View</td>
			              	</tr>
			         	</table>
			     	</button>
			     	<button class="btnStandar" id="btnPrint" style="width:100px;height:29px; margin: 10px; " title="Print">
			        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
			            	<tr>
			                	<td align="right" width="25"><img src="../picture/Printer-blue-32.png" height="20"/> </td>
			                    <td align="center">Print</td>
			              	</tr>
			         	</table>
			     	</button>
				</td>
			</tr>
		</table>
	</div>
	<div class="dataCert" id="dataCert" style="width:100%;max-height:300px;overflow:scroll;">
		<table cellpadding="0" cellspacing="0" width="100%" border="1" id="tblDataCert" style="padding-left: 1px;">
			<thead style="background-color: #125362;color: #FFFFFF;height:30px;">
				<tr>
					<td style="width: 5%;" align="center" colspan="2">No</td>
					<td style="width: 25%;" align="center">Statutory Certificates</td>
					<td style="width: 10%;" align="center" colspan="2">Mv.A Arsanti</td>
					<td style="width: 10%;" align="center" colspan="2">Mt. John Caine</td>
					<td style="width: 10%;" align="center" colspan="2">Mv.A Kanishka</td>
					<td style="width: 10%;" align="center" colspan="2">Mv.A Kalyani</td>
					<td style="width: 10%;" align="center" colspan="2">Mt.A Larasati</td>
					<td style="width: 10%;" align="center" colspan="2">Mv.A Nareswari</td>
					<td style="width: 10%;" align="center" colspan="2">Mv.A Paramesti</td>
				</tr>
			</thead>
			<tbody style="font-size: 12px;cursor: pointer;" id="tblIdBody1">
			</tbody>
		</table>
	</div>
	<div class="dataVessel" id="dataVessel" style="width:100%;max-height:300px;overflow:scroll;">
		<table cellpadding="0" cellspacing="0" width="100%" border="1" id="tblDataVessel" style="padding-left: 1px;">
			<thead style="background-color: #125362;color: #FFFFFF;height:30px;">
				<tr>
					<td style="width: 15%;" align="center">Vessel Name</td>
					<td style="width: 20%;" align="center">Survey Item</td>
					<td style="width: 15%;" align="center">Range Dates</td>
					<td style="width: 5%;" align="center">Day Due</td>
					<td style="width: 15%;" align="center">Department Reponsible</td>
					<td style="width: 25%;" align="center">Remarks</td>
				</tr>
			</thead>
			<tbody style="font-size: 12px;cursor: pointer;" id="tblIdBody2">
			</tbody>
		</table>
	</div>
</div>


