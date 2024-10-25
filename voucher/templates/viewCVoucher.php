<html>
<head>	
	<script type="text/javascript" src="../../js/main.js"></script>
	<script type="text/javascript" src="../../js/jquery-1.4.3.js"></script>

<script>
	$(document).ready(function(){

		$("#btnCloseMap").click(function(){
			refreshDataSearch();
	  		parent.tb_remove(false);
	  	});
	  });

	function searchData()
	{
		var txtSearch = $("#txtSearchBatchno").val();

		if(txtSearch == "")
		{
			alert("Batch No Empty..!!");
			return false;
		}

		document.getElementById('idLoading').style.display = "";

		$.post("../halPostVoucher.php",
        { aksi : "searchBatchNo",batchno : txtSearch },
            function(data) 
            {
            	$("#idBody").empty();
            	$("#idBody").append(data.trNya);

            	document.getElementById('idLoading').style.display = "none";
            },
        "json"
        );
	}

	function cancelVoucher(id)
	{
		var cfm = confirm("Yakin di Cancel..??");

		if(cfm)
		{
			document.getElementById('idLoading').style.display = "";

			$.post("../halPostVoucher.php",
	        { aksi : "cancelVoucherByBatchNo",idVoucher : id },
	            function(data) 
	            {
	            	alert(data);
	            	document.getElementById('idLoading').style.display = "none";
	            	searchData();
	            },
	        "json"
	        );
		}
	}

	function refreshDataSearch()
	{
		$("#txtSearchBatchno").val('');
		$("#idBody").empty();
		$("#loading").hide();
	}

</script>
</head>
<body>
	<div class="container">
		<div>			
			<button id="btnCloseMap" style="float:right;cursor:pointer;">X</button>
			<label style="float:right;padding-right:10px;"><i><b>:: CANCEL VOUCHER ::</b></i></label>
			<table cellpadding="0" cellspacing="0" width="100%" style="margin-bottom:10px;">
				<tr>
					<td id="" style="font-weight:normal;width:10%;">
						<input type="text" id="txtSearchBatchno" value="" placeholder="Batch No" autocomplete="off">
					</td>
					<td style="font-weight:normal;width:90%;">
						<button class="btnStandar" onClick="searchData();" style="margin-left:10px;cursor:pointer;">Search</button>
						<button class="btnStandar" onClick="refreshDataSearch();" style="margin-left:10px;cursor:pointer;">Refresh</button>
						<img id="idLoading" src="loading.gif" style="margin-left:10px;display:none;">
					</td>
				</tr>
			</table>
			<table cellpadding="0" cellspacing="0" width="100%" border="1" style="left:0px;top:0px;z-index:10;">
				<thead style="background-color:#3A7100;color:#F9F9F9;font-family:Arial;font-weight:bold;font-size:12px;">
					<tr align="center">
						<td class="tabelBorderRightJust" height="30">NO</td>
						<td class="tabelBorderRightJust">BATCHNO</td>					        
						<td class="tabelBorderRightJust">PAID TO/FROM</td>
						<td class="tabelBorderRightJust">COMPANY</td>
						<td class="tabelBorderRightJust">DATE PAID</td>
						<td class="tabelBorderRightJust">AMOUNT</td>
						<td class="tabelBorderRightJust">ACTION</td>
					</tr>
				</thead>
				<tbody id="idBody">
					
				</tbody>
			</table>
		</div>
		
	</div>
</body>
</html>