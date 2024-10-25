<link rel="stylesheet" href="../css/jquery-ui.css">
<script type="text/javascript" src="../js/jquery-1.6.js"></script>
<script type="text/javascript" src="../js/niceEdit.js"></script>
<script type="text/javascript" src="../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<link rel="stylesheet" type="text/css" href="../calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></link>
<script src="../js/jquery-ui.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#loading").hide();
		setPaging("");
		$("#idPlannMaintenance").css("display","none");
        $("#idBtnDeficiency").css("display","none");
        $("#idBtnSurveyStatus").css("display","none");

        $("#btnSearch").click(function(){
        	$(".pagination").empty();
        	var voyNo = $("#txtSearchVoyageNo").val();
        	if(voyNo == "")
        	{
        		alert("Voyage No Don't empty..!!");
        		return false;
        	}

        	$.post('../shipManagement/class/actionNav.php',
	    	{ actionSearchSummaryCons : "actionSearchSummaryCons",voyNo : voyNo },
	        	function(data) 
	            {
	            	$("#tblIdBody").empty();
	            	var html = data;
					$('#tblIdBody').append(html);
	            },
	         "json"
	         );
        });

        $("#btnRefresh").click(function(){
        	location.reload();
        });

        $("#btnExport").click(function(){
        	var voyNo = "";
        	voyNo = $("#txtSearchVoyageNo").val();
			$("#actionVoyNo").val(voyNo);
			formExportBunkerConSpeed.submit();
        });

		var html = $('<button id ="idBtnDeficiency" class="btnStandarKecil" type="button" onclick="formUploadFileExcel.submit();" style="width:70px;height:40px;float:left;margin-left:10px;" title="Back"><table cellpadding="0" cellspacing="0" width="100%" height="100%"><tr><td align="center"><img src="../picture/arrow-return-180-left.png" height="18"/></td></tr><tr><td align="center" style="font-size:9px;font-weight: bold;">BACK</td></tr></table></button>');
        $("#idNavHome").append(html);
	});
	function getDataPaging(page)
	{
		$("#loading").show();
		$("#tblIdBody").empty();

		setPaging(page);
		
		$.post('../shipManagement/class/actionNav.php',
	    { actionPagingBunkerConsSpeed : "actionPagingBunkerConsSpeed",page : page },
	       	function(data) 
	     	{
				$("#tblIdBody").empty();
	            var html = data;
				$('#tblIdBody').append(html);
	      	},
	  	"json"
	   	);

		$("#loading").hide();
	}
	function setPaging(page)
	{
		$("#idNavPaging").empty();
		var htmlPaging = "";
		var ttlData = $("#ttlData").text();
		var beforePage = "";
		var afterPage = "";
		var totalPage = "";
		var disPlayData = 20 ;
		var sDisplay = "";
		var eDisplay = "";

		totalPage = ttlData/disPlayData;

		totalPage = Math.ceil(totalPage);

		if(page == "" || page == '1')
		{
			if(page == "")
			{
				page = 1;
			}
			htmlPaging += '<span class="disabled">&laquo; First</span>';
			htmlPaging += '<span class="current">'+page+'</span>';
			htmlPaging += '<a onclick="getDataPaging('+(page+1)+');" style="cursor: pointer;">'+(page+1)+'</a>';
			if((page+2) < totalPage)
			{
				htmlPaging += '<a onclick="getDataPaging('+(page+2)+');" style="cursor: pointer;">'+(page+2)+'</a>';
			}
			
			if((page+2) < totalPage)
			{
				htmlPaging += '...';
				htmlPaging += '<a onclick="getDataPaging('+totalPage+');" style="cursor: pointer;">'+totalPage+'</a>';
			}
			htmlPaging += '<a onclick="getDataPaging('+totalPage+');" style="cursor: pointer;">Last &raquo;</a>';
		}else{
			beforePage = page - 1;
			afterPage = page + 1;
			htmlPaging = '<a onclick="getDataPaging('+"1"+');" id="idPgPrev" style="cursor: pointer;">&laquo; First</a>';
			htmlPaging += '<a onclick="getDataPaging('+beforePage+');" style="cursor: pointer;">'+beforePage+'</a>';
			htmlPaging += '<span class="current">'+page+'</span>';

			if((page+1) <= totalPage)
			{
				htmlPaging += '<a onclick="getDataPaging('+afterPage+');" style="cursor: pointer;">'+afterPage+'</a>';
				if(afterPage < totalPage)
				{
					if((page +2) < totalPage)
					{
						htmlPaging += '...';
					}
					htmlPaging += '<a onclick="getDataPaging('+totalPage+');" style="cursor: pointer;">'+totalPage+'</a>';
				}
			}
			
			if(page == totalPage)
			{
				htmlPaging += '<span class="disabled">Last &raquo;</span>';
			}else{
				htmlPaging += '<a onclick="getDataPaging('+totalPage+');" style="cursor: pointer;">Last &raquo;</a>';
			}
		}
		
		if(page == 1)
		{
			$("#sDisplay").text(page);
			$("#eDisplay").text(disPlayData);
		}else{
			$("#sDisplay").text((page-1) * disPlayData +1 );
			$("#eDisplay").text(page * disPlayData);
			var lastNo = $("#ttlData").text();
			var lastNoPage = page * disPlayData;
			if(lastNoPage >= lastNo)
			{
				$("#eDisplay").text(lastNo);
			}
		}
		
		

		$("#idNavPaging").append(htmlPaging);
	}
</script>
<style type="text/css">
	.ui-datepicker {
		width: 17%;
	}
	div.pagination {
		/*position:absolute;*/
		bottom:0;
		left:0;
		width:98%;
		padding: 3px;
		margin: 3px;
		font-family : Tahoma;
		font-size   : 12px;
	}

	div.pagination a {
		padding: 2px 5px 2px 5px;
		margin: 2px;
		border: 1px solid #AAAADD;
		text-decoration: none; /* no underline */
		color: #000099;
	}
	div.pagination a:hover, div.pagination a:active {
		border: 1px solid #000099;
		color: #000;
	}
	div.pagination span.current {
		padding: 2px 5px 2px 5px;
		margin: 2px;
		border: 1px solid #000099;		
		font-weight: bold;
		background-color: #000099;
		color: #FFF;
	}
	div.pagination span.disabled {
		padding: 2px 5px 2px 5px;
		margin: 2px;
		border: 1px solid #EEE;
		color: #DDD;
	}
</style>
<!DOCTYPE html>
<div class="archivesContainer" style="padding-bottom:20px;margin-top:10px;">
	<div class="navBtn" style="width:100%;min-height:0px;" >
		<img src="../picture/ajax-loader20.gif" style="width: 24px;height: 24px;margin: 10px;" id="loading" class="btnStandar">
    	<div id="idNavSearch">
    		<input type="text" name="txtSearchVoyageNo" id="txtSearchVoyageNo" class="elementSearch" style="width: 10%;margin: 10px;float: left;" placeholder="Voyage No">
    		<button class="btnStandar" id="btnSearch" style="width:100px;height:29px; margin: 10px; float: left;" title="View Data">
	        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
	            	<tr>
	                	<td align="right" width="25"><img src="../picture/Search-blue-32.png" height="20"/> </td>
	                    <td align="center">Search</td>
	              	</tr>
	         	</table>
	     	</button>
	     	<button class="btnStandar" id="btnRefresh" style="width:100px;height:29px; margin: 10px; float: left;" title="View Data">
	        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
	            	<tr>
	                	<td align="right" width="25"><img src="../picture/Button-Synchronize-blue-32.png" height="20"/> </td>
	                    <td align="center">Refresh</td>
	              	</tr>
	         	</table>
	     	</button>
	     	<button class="btnStandar" id="btnExport" style="width:100px;height:29px; margin: 10px; float: left;" title="Export Data">
	        	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
	            	<tr>
	                	<td align="right" width="25"><img src="../picture/fileXls.png" height="20"/> </td>
	                    <td align="center">Export</td>
	              	</tr>
	         	</table>
	     	</button>
    	</div>
	</div>
	<div class="equipName" style="width:100%;height:480px;overflow:scroll;">
		<table cellpadding="0" cellspacing="0" width="2000" border="1" id="tblEquipName">
			<thead style="font-size: 12px;background-color: #125362;color: #FFFFFF;height:30px;">
				<tr>
					<td style="background-color: #FFF;"></td>
					<td align="center" colspan="6">Departure (COP)</td>
					<td style="background-color: #FFF;"></td>
					<td align="center" colspan="6">Arrival (EOP)</td>
					<td align="center" colspan="7">Performance</td>
					<td style="background-color: #FFF;" colspan="2"></td>
				</tr>
				<tr>
					<td style="width: 25px;" align="center">NO</td>
					<td style="width: 80px;" align="center">Voyage No</td>
					<td style="width: 120px;" align="center">Port</td>
					<td style="width: 70px;" align="center">Date</td>
					<td style="width: 50px;" align="center">Time</td>
					<td style="width: 50px;" align="center">D.O</td>
					<td style="width: 50px;" align="center">FW</td>
					<td style="width: 120px;" align="center">Cargo (in LT)</td>
					<td style="width: 120px;" align="center">Port</td>
					<td style="width: 70px;" align="center">Date</td>
					<td style="width: 50px;" align="center">Time</td>
					<td style="width: 50px;" align="center">D.O</td>
					<td style="width: 50px;" align="center">FW</td>
					<td style="width: 50px;" align="center">Dist</td>
					<td style="width: 50px;" align="center">Steaming Time in Hours</td>
					<td style="width: 50px;" align="center">Av Speed</td>
					<td style="width: 50px;" align="center">D.O</td>
					<td style="width: 50px;" align="center">FW</td>
					<td style="width: 100px;" align="center">DO/Day</td>
					<td style="width: 50px;" align="center">FW</td>
					<td style="width: 180px;" align="center">Remark</td>
					<td style="width: 60px;" align="center">Bunkering in MT</td>
					<td style="width: 60px;" align="center">D.O Cons in Port</td>
				</tr>
			</thead>
			<tbody style="font-size: 11px;cursor: pointer;" id="tblIdBody">
				{getDataSummaryBunkerConsAndSpeed}
			</tbody>
		</table>
		<div class="pagination">
			<span style="font-size:11px;">
				Display : <strong id="sDisplay"></strong> - <strong id="eDisplay">  </strong> From <strong id="ttlData">{ttlDataConsSpeed}</strong>
			</span>
			<br><br>
			<div id="idNavPaging">

			</div>
		</div>
	</div>
</div>
<form name="formExportBunkerConSpeed" method="POST" action="../shipManagement/class/actionNav.php">
	<input type="hidden" id="actionExportBunkerConSpeed" name="actionExportBunkerConSpeed" value="export" />
	<input type="hidden" id="actionVoyNo" name="actionVoyNo" value="" />
</form>

