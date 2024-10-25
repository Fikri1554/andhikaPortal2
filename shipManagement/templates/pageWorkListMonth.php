<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/jquery-ui.js"></script>
<script src="../js/jquery.table2excel.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#loading").hide();
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
		var vesselName = $( "#namaKapal option:selected" ).text();
		var DSNya = $("#txtDateSearch").val();
		var m = new Date();
		var PN = getMonthName(parseInt(m.getMonth())+1)+" "+m.getFullYear();
		$.post('../shipManagement/class/actionNav.php',
			{ actionDateWorkList : "start"},
				function(data) 
				{	
					$('#tblIdBodyWorkList').empty();
					$('#thisMonth').empty();
					$('#thisOther').empty();
					$("#loading").show();
					var tr = data.dataTR;
					$('#tblIdBodyWorkList').append(tr);
					$('#thisMonth').append(data.sNoThisMonth);
					$('#thisOther').append(data.sNoOtherMonth);
					$("#idVName").text("MASTER "+vesselName);
					$("#lblPeriode").text(PN);
					$("#idDateDesc").text("1 "+PN);
					$("#loading").hide();
				},
			"json"
		);
		$("#btnSearch").click(function(){
			var dateSearch = "";
			dateSearch = $("#txtDateSearch").val();
			var arr = dateSearch.split('-');
			PN = getMonthName(parseInt(arr[1]))+" "+arr[0];
			$.post('../shipManagement/class/actionNav.php',
			{ actionDateSearchWorkList : dateSearch},
				function(data) 
				{	
					$('#tblIdBodyWorkList').empty();
					$('#thisMonth').empty();
					$('#thisOther').empty();
					$("#loading").show();
					var trNya = data.dataTR;
					$('#tblIdBodyWorkList').append(trNya);
					$('#thisMonth').append(data.sNoThisMonth);
					$('#thisOther').append(data.sNoOtherMonth);
					$("#idVName").text("MASTER "+vesselName);
					$("#lblPeriode").text(PN);
					$("#idDateDesc").text("1 "+PN);
					$("#loading").hide();
				},
			"json"
			);
		});
		$("#btnExport").click(function(){
			$("#tblWorkListName").table2excel({  
		        name: "Table2Excel",  
		        filename: "workList.xls",  
		        fileext: ".xls"  
	        });  
		});

	});
	function getMonthName(blnNya)
	{
		var month = new Array();
			month[1] = "JANUARI";
			month[2] = "FEBRUARI";
			month[3] = "MARET";
			month[4] = "APRIL";
			month[5] = "MEI";
			month[6] = "JUNI";
			month[7] = "JULI";
			month[8] = "AGUSTUS";
			month[9] = "SEPTEMBER";
			month[10] = "OKTOBER";
			month[11] = "NOVEMBER";
			month[12] = "DECEMBER";
		return month[blnNya];
	}

</script>
<!DOCTYPE html>
<div class="archivesContainer" style="padding-bottom:50px;margin-top:10px;">
	<div class="navBtn" style="width:100%;min-height:0px;" >
		<input type="text" name="txtDateSearch" id="txtDateSearch" class="elementSearch" style="width: 10%;margin: 10px;" placeholder="Date Search">
     	<button class="btnStandar" id="btnSearch" style="width:100px;height:29px; margin: 10px;" title="Search Data" >
        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
            	<tr>
                	<td align="right" width="25"><img src="../picture/Button-Check-blue-32.png" height="20"/> </td>
                    <td align="center">Search</td>
              	</tr>
         	</table>
     	</button>
     	<button class="btnStandar" id="btnExport" style="width:100px;height:29px; margin: 10px;" title="Cetak" {stBtnExport}>
        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
            	<tr>
                	<td align="right" width="25"><img src="../picture/fileXls.png" height="20"/> </td>
                    <td align="center">Export</td>
              	</tr>
         	</table>
     	</button>
		<img src="../picture/ajax-loader20.gif" style="width: 24px;height: 24px;margin: 10px;" id="loading" class="btnStandar">
	</div>
	<div class="workList" style="width:100%;max-height:250px;overflow:scroll;">
		<table cellpadding="0" cellspacing="0" width="100%" border="1" id="tblWorkListName">
			<thead style="background-color: #125362;color: #FFFFFF;height:30px;">
				<tr>
					<td style="width: 5%;" align="center">Code</td>
					<td style="width: 10%;" align="center">Component</td>
					<td style="width: 10%;" align="center">Job</td>
					<td style="width: 5%;" align="center">Freq</td>
					<td style="width: 25%;" align="center">Component Name</td>
					<td style="width: 25%;" align="center">Job Heading</td>
					<td style="width: 10%;" align="center">Last Done</td>
					<td style="width: 10%;" align="center">Next Due</td>
				</tr>
			</thead>
			<tbody style="font-size: 11px;cursor: pointer;" id="tblIdBodyWorkList">
			</tbody>
		</table>
	</div>
	<div class="descWorkList" style="padding:30px;min-height-height:150px;overflow:scroll;background-color:#d8d8d8;margin-top:20px;" id="descWorkList">
		<table cellpadding="0" cellspacing="0" width="100%" style="font-size: 12px;">
			<tr>
				<td style="width: 50px;">TO</td>
				<td style="width: 10px;">:</td>
				<td id="idVName" style="width: 400px;"></td>
				<td style="width: 50px;">FM</td>
				<td>:</td>
			</tr>
			<tr>
				<td style="width: 50px;">DATE</td>
				<td style="width: 10px;">:</td>
				<td id="idDateDesc" style="width: 400px;">01/10/2017</td>
				<td style="width: 50px;">REF</td>
				<td>:</td>
			</tr>
			<tr>
				<td colspan="6" style="padding-top: 20px;text-decoration: underline;">
					FLWG ARE PMS MASTER CODE OF JOBS DUE IN <label id="lblPeriode"></label> : 
				</td>
			</tr>
			<tr style="padding-top: 20px;">
				<td colspan="6" id="thisMonth">
				</td>
			</tr>
			<tr>
				<td colspan="6" style="padding-top: 20px;text-decoration: underline;">
					OVERDUE JOBS (PLS RVT REASONS) : 
				</td>
			</tr>
			<tr style="padding-top: 20px;">
				<td colspan="6" id="thisOther">
				</td>
			</tr>
		</table>
		<div align="center" style="margin-top: 50px; width:100px;background-color: #FFF;border-style: solid;border-width: 1px;">
			DUE
		</div>
		<div align="center" style="float: left; margin-top: 5px; width:100px;background-color: #f5cdbf;border-style: solid;border-width: 1px;">
			OVERDUE
		</div>
	</div>
</div>


