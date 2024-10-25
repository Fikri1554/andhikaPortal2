// JavaScript Document

function setup()
{
	var fromBarcode = new NumberMask(new NumberParser(0, "", "", true), "fromBarcode", 5);
	var toBarcode = new NumberMask(new NumberParser(0, "", "", true), "toBarcode", 5);
	
	var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
	var fromDate = new DateMask("dd/MM/yyyy", "fromDate");
	fromDate.validationMessage = errorMessage;
	var toDate = new DateMask("dd/MM/yyyy", "toDate");
	toDate.validationMessage = errorMessage;
	
	$(document).ready(function()
	{
		$('#fromBarcode').focus(function()
		{
			$('#barcode').attr("checked","checked");
		});
		$('#toBarcode').focus(function()
		{
			$('#barcode').attr("checked","checked");
		});
		$('#fromDate').focus(function()
		{
			$('#batchno').attr("checked","checked");
		});
		$('#toDate').focus(function()
		{
			$('#batchno').attr("checked","checked");
		});
	});
}

function klikBtnPrintMailInv()
{
	var reportType = $("input:radio[name='reportType']:checked").val();
	var dataFrom = $('#dataFrom').val();
	var printBy = $('#printBy').val();
	var printBy2 = $("input:radio[name='printBy2']:checked").val();
	var fromBarcode = $('#fromBarcode').val();
	var toBarcode = $('#toBarcode').val();
	var fromDate = $('#fromDate').val();
	var toDate = $('#toDate').val();
	var printModel = $('#printModel').val();
	var divisiBy = $("#printDivisiBy").val();
	//alert(divisiBy);return false;
	$('#formPrintMailInv').attr('action', 'halPrint.php?aksi=printMailInv&reportType='+reportType+'&dataFrom='+dataFrom+'&printBy='+printBy+'&printBy2='+printBy2+'&fromBarcode='+fromBarcode+'&toBarcode='+toBarcode+'&fromDate='+fromDate+'&toDate='+toDate+'&printModel='+printModel+'&divisi='+divisiBy);
	formPrintMailInv.submit()
}

function klikBtnCari()
{
	parent.disabledBtn('btnCariPrintDetail');
	
	var cariBerdasarkan = $('#cariBerdasarkan').val();
	var teksCari = $('#teksCari').val();
	var startDate = $('#startDate').val();
	var endDate = $('#endDate').val();
	
	$('#idErrorMsg').css('visibility','hidden'); 
	if((cariBerdasarkan == "senderVendor" || cariBerdasarkan == "company" || cariBerdasarkan == "mailInvNo" || cariBerdasarkan == "poNumber" || cariBerdasarkan == "senVen") && $.trim( teksCari ) == '')
	{
		pesanError("Please types in your search", "teksCari");
		return false;
	}
	
	if((cariBerdasarkan == "mailInvDate" || cariBerdasarkan == "entryDate") && (startDate == '' && endDate == ''))
	{
		pesanError("Start or End Date is still empty", "startDate");
		return false;
	}
	if((cariBerdasarkan == "mailInvDate" || cariBerdasarkan == "entryDate") && parseInt(tahunbalik(startDate)) > parseInt(tahunbalik(endDate)))
	{
		pesanError("End Date must be greater than Start Date", "startDate");
		return false;
	}
	
	pleaseWait();
	document.onmousedown=disableLeftClick;
	
	$('#iframeList').attr('src', '');
	$('#iframeList').attr('src', 'templates/halCariList.php?aksi=lakukanCari&cariBerdasarkan='+cariBerdasarkan+'&teksCari='+teksCari+'&startDate='+startDate+'&endDate='+endDate);
}

function klikBtnCariPrintDetail()
{
	var idMailInv = $('#idMailInv').val();
	$('#formPrintDetail').attr('action', 'halPrint.php?aksi=printDetail&idMailInv='+idMailInv);
	formPrintDetail.submit()
}

function pilihMenuPrintBy(nilai)
{
	var isiHTML = '';
	if(nilai == 'all')
		isiHTML = '( A00 ) & ( S00 )';
	if(nilai == 'invoice')
		isiHTML = '( A00 )';
	if(nilai == 'mail')
		isiHTML = '( S00 )';
	
	$('#3angkaDpnBarcode').html(isiHTML);
}

function tahunbalik(str)
{
	var thn = str.substring(6,str.length)
	var bln = str.substring(3,5)
	var tgl = str.substring(0,2)
	return thn.concat(bln).concat(tgl)
}

function pesanError(pesan, itemFokus)
{
	document.getElementById(itemFokus).focus(); 
	$('#idErrorMsg').css('visibility','visible'); 
	$('#idErrorMsg').html("<img src=\"../picture/exclamation-red.png\"/>&nbsp;<span>"+pesan+"</span>&nbsp;");
}

function blinker() {
    $('#idErrorMsg').fadeOut(250);
	$('#idErrorMsg img').fadeOut(250);
		
    $('#idErrorMsg').fadeIn(500);
	$('#idErrorMsg img').fadeIn(500);
}

setInterval(blinker, 1500);

